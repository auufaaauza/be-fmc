<?php

namespace App\Exports;

use App\Models\InterestCategory;
use App\Models\QuestionnaireAnswer;
use App\Models\QuestionnaireQuestion;
use App\Models\Recommendation;
use App\Models\RecommendationResult;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Rekap Keseluruhan — multi-sheet Excel export.
 *
 * Sheet 1: Ringkasan Statistik
 * Sheet 2: Data Siswa & Nilai Rapor
 * Sheet 3: Hasil Rekomendasi
 * Sheet 4: Distribusi RIASEC
 */
class StudentsFullReportExport implements WithMultipleSheets
{
    private int     $schoolId;
    private ?string $classFilter;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
    }

    public function sheets(): array
    {
        return [
            new FullReportSummarySheet($this->schoolId, $this->classFilter),
            new FullReportStudentsSheet($this->schoolId, $this->classFilter),
            new FullReportRecommendationsSheet($this->schoolId, $this->classFilter),
            new FullReportRiasecSheet($this->schoolId, $this->classFilter),
        ];
    }
}

// ── Sheet 1: Ringkasan ────────────────────────────────────────────────────────

class FullReportSummarySheet implements FromArray, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    private int     $schoolId;
    private ?string $classFilter;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
    }

    public function title(): string { return 'Ringkasan'; }

    public function array(): array
    {
        $questionTotal = QuestionnaireQuestion::count();

        $query = User::where('role', 'student')
            ->where('school_id', $this->schoolId)
            ->withCount(['scores', 'questionnaireAnswers', 'recommendations']);
        if ($this->classFilter) $query->where('class', $this->classFilter);
        $students = $query->get();

        $total            = $students->count();
        $raporComplete    = $students->where('scores_count', '>=', 3)->count();
        $questionComplete = $students->where('questionnaire_answers_count', '>=', $questionTotal)->count();
        $recComplete      = $students->where('recommendations_count', '>', 0)->count();

        $filterLabel = $this->classFilter ? "Kelas: {$this->classFilter}" : 'Semua Kelas';

        return [
            ['REKAP LAPORAN SISWA — FIND MY CAREER'],
            ['Tanggal Export', now()->format('d/m/Y H:i')],
            ['Filter', $filterLabel],
            [],
            ['STATISTIK', 'Jumlah', 'Persentase'],
            ['Total Siswa', $total, '100%'],
            ['Rapor Terisi (≥3 mapel)', $raporComplete, $total > 0 ? round($raporComplete / $total * 100, 1) . '%' : '0%'],
            ['Kuesioner Selesai', $questionComplete, $total > 0 ? round($questionComplete / $total * 100, 1) . '%' : '0%'],
            ['Rekomendasi Terbit', $recComplete, $total > 0 ? round($recComplete / $total * 100, 1) . '%' : '0%'],
            ['Belum Ada Rekomendasi', $total - $recComplete, $total > 0 ? round(($total - $recComplete) / $total * 100, 1) . '%' : '0%'],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1a1a2e']]],
            5 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4f46e5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->getStyle('A5:C10')->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']]],
                ]);
            },
        ];
    }
}

// ── Sheet 2: Data Siswa & Nilai ───────────────────────────────────────────────

class FullReportStudentsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    private int     $schoolId;
    private ?string $classFilter;
    private array   $subjects;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
        $this->subjects    = Subject::orderBy('id')->get()->toArray();
    }

    public function title(): string { return 'Data Siswa & Nilai'; }

    public function headings(): array
    {
        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Status Akun'];
        foreach ($this->subjects as $s) {
            $headers[] = $s['name'];
        }
        return $headers;
    }

    public function collection()
    {
        $query = User::where('role', 'student')
            ->where('school_id', $this->schoolId)
            ->with('scores')
            ->orderBy('class')->orderBy('name');
        if ($this->classFilter) $query->where('class', $this->classFilter);
        $students = $query->get();

        return $students->map(function (User $student, int $index) {
            $row = [
                $index + 1,
                $student->nisn,
                $student->name,
                $student->class ?? '-',
                $student->is_active ? 'Aktif' : 'Nonaktif',
            ];
            $scoreMap = $student->scores->pluck('score', 'subject_id');
            foreach ($this->subjects as $s) {
                $row[] = $scoreMap->get($s['id'], '');
            }
            return $row;
        });
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a1a2e']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow    = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
                $sheet->freezePane('A2');
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(15);
            },
        ];
    }
}

// ── Sheet 3: Hasil Rekomendasi ────────────────────────────────────────────────

class FullReportRecommendationsSheet implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    private int     $schoolId;
    private ?string $classFilter;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
    }

    public function title(): string { return 'Hasil Rekomendasi'; }

    public function headings(): array
    {
        return ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Jurusan #1', 'Skor #1', 'Jurusan #2', 'Skor #2', 'Jurusan #3', 'Skor #3', 'Tanggal Perhitungan', 'Tervalidasi'];
    }

    public function collection()
    {
        $query = User::where('role', 'student')
            ->where('school_id', $this->schoolId)
            ->orderBy('class')->orderBy('name');
        if ($this->classFilter) $query->where('class', $this->classFilter);
        $students = $query->pluck('id');

        // Get latest recommendation per student
        $latestRecIds = Recommendation::whereIn('user_id', $students)
            ->groupBy('user_id')
            ->selectRaw('MAX(id) as id')
            ->pluck('id');

        $recs = Recommendation::whereIn('id', $latestRecIds)
            ->with(['user', 'results' => fn ($q) => $q->orderBy('rank_position')->with('program')])
            ->get()
            ->sortBy('user.name');

        return $recs->values()->map(function (Recommendation $rec, int $index) {
            $results = $rec->results->keyBy('rank_position');
            $r1 = $results->get(1);
            $r2 = $results->get(2);
            $r3 = $results->get(3);

            $calcDate = '-';
            if ($rec->calculated_at) {
                $calcDate = $rec->calculated_at instanceof \DateTimeInterface
                    ? $rec->calculated_at->format('d/m/Y H:i')
                    : date('d/m/Y H:i', strtotime((string) $rec->calculated_at));
            }

            return [
                $index + 1,
                $rec->user?->nisn,
                $rec->user?->name,
                $rec->user?->class ?? '-',
                $r1?->program?->name ?? '-',
                $r1 ? number_format((float) $r1->preference_value, 4) : '-',
                $r2?->program?->name ?? '-',
                $r2 ? number_format((float) $r2->preference_value, 4) : '-',
                $r3?->program?->name ?? '-',
                $r3 ? number_format((float) $r3->preference_value, 4) : '-',
                $calcDate,
                $rec->is_validated ? 'Ya' : 'Belum',
            ];
        });
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4f46e5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow    = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
                $sheet->freezePane('A2');
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(15);
                $sheet->getColumnDimension('E')->setWidth(35);
                $sheet->getColumnDimension('G')->setWidth(35);
                $sheet->getColumnDimension('I')->setWidth(35);
            },
        ];
    }
}

// ── Sheet 4: Distribusi RIASEC ────────────────────────────────────────────────

class FullReportRiasecSheet implements FromArray, WithHeadings, WithTitle, WithStyles, ShouldAutoSize, WithEvents
{
    private int     $schoolId;
    private ?string $classFilter;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
    }

    public function title(): string { return 'Distribusi RIASEC'; }

    public function headings(): array
    {
        return ['Kategori', 'Kode', 'Total Skor', 'Persentase'];
    }

    public function array(): array
    {
        $query = User::where('role', 'student')
            ->where('school_id', $this->schoolId);
        if ($this->classFilter) $query->where('class', $this->classFilter);
        $studentIds = $query->pluck('id');

        $categories    = InterestCategory::orderBy('id')->get();
        $categoryScores = QuestionnaireAnswer::whereIn('questionnaire_answers.user_id', $studentIds)
            ->join('questionnaire_questions', 'questionnaire_answers.question_id', '=', 'questionnaire_questions.id')
            ->select('questionnaire_questions.category_id', DB::raw('SUM(questionnaire_answers.answer_score) as total_score'))
            ->groupBy('questionnaire_questions.category_id')
            ->pluck('total_score', 'category_id');

        $totalScore = $categoryScores->sum();

        return $categories->map(function ($cat) use ($categoryScores, $totalScore) {
            $score = (int) ($categoryScores[$cat->id] ?? 0);
            $pct   = $totalScore > 0 ? round($score / $totalScore * 100, 1) : 0;
            return [
                $cat->name,
                substr($cat->name, 0, 1),
                $score,
                $pct . '%',
            ];
        })->toArray();
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7c3aed']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow    = $sheet->getHighestRow();
                $sheet->getStyle("A1:D{$highestRow}")->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                ]);
            },
        ];
    }
}

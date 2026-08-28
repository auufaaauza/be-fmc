<?php

namespace App\Exports;

use App\Models\Subject;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    private int $schoolId;
    private ?string $classFilter;
    private array $subjects;

    public function __construct(int $schoolId, ?string $classFilter = null)
    {
        $this->schoolId    = $schoolId;
        $this->classFilter = $classFilter;
        $this->subjects    = Subject::orderBy('id')->get()->toArray();
    }

    public function title(): string
    {
        return 'Data Siswa & Nilai';
    }

    public function headings(): array
    {
        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas'];
        foreach ($this->subjects as $subject) {
            $headers[] = $subject['name'];
        }
        return $headers;
    }

    public function collection()
    {
        $query = User::where('role', 'student')
            ->where('school_id', $this->schoolId)
            ->with('scores')
            ->orderBy('class')
            ->orderBy('name');

        if ($this->classFilter) {
            $query->where('class', $this->classFilter);
        }

        $students = $query->get();

        return $students->map(function (User $student, int $index) {
            $row = [
                $index + 1,
                $student->nisn,
                $student->name,
                $student->class ?? '-',
            ];

            // Nilai per mapel (rerata yang tersimpan)
            $scoreMap = $student->scores->pluck('score', 'subject_id');
            foreach ($this->subjects as $subject) {
                $row[] = $scoreMap->get($subject['id'], '');
            }

            return $row;
        });
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row bold + background
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

                // Border untuk seluruh data
                $sheet->getStyle("A1:{$highestColumn}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Freeze header row
                $sheet->freezePane('A2');

                // Lebar minimum untuk kolom NIS dan nama
                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(15);
            },
        ];
    }
}

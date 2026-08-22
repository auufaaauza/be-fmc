<?php

namespace App\Exports;

use App\Models\Subject;
use Maatwebsite\Excel\Concerns\FromArray;
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

/**
 * Template kosong untuk diisi oleh Guru BK, lalu di-import kembali.
 * Kolom: No | NIS | Nama Siswa | Kelas | [Mapel1] | [Mapel2] | ...
 * 
 * Petunjuk:
 * - Isi kolom No, NIS, Nama Siswa, Kelas
 * - Isi nilai rerata rapor untuk setiap mata pelajaran (0-100)
 * - Siswa dengan NIS yang sudah ada akan diperbarui nilainya
 * - Siswa baru (NIS belum ada) akan otomatis dibuat dengan password default: siswa123
 */
class StudentsImportTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle, WithEvents
{
    private array $subjects;

    public function __construct()
    {
        $this->subjects = Subject::orderBy('id')->get()->toArray();
    }

    public function title(): string
    {
        return 'Template Import';
    }

    public function headings(): array
    {
        $headers = ['No', 'NISN', 'Nama Siswa', 'Kelas'];
        foreach ($this->subjects as $subject) {
            $headers[] = $subject['name'];
        }
        return $headers;
    }

    public function array(): array
    {
        // Template dengan 3 baris contoh kosong
        return [
            [1, '', '', '', ...array_fill(0, count($this->subjects), '')],
            [2, '', '', '', ...array_fill(0, count($this->subjects), '')],
            [3, '', '', '', ...array_fill(0, count($this->subjects), '')],
        ];
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
                $highestColumn = $sheet->getHighestColumn();

                $sheet->getStyle("A1:{$highestColumn}20")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color'       => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Row petunjuk di baris ke-2 (sebelum data)
                $sheet->insertNewRowBefore(2, 1);
                $sheet->mergeCells("A2:{$highestColumn}2");
                $sheet->setCellValue('A2', 'PETUNJUK: Isi NISN, Nama, Kelas, dan Nilai Rata-rata (0-100) setiap mapel. Siswa baru otomatis dibuat dengan password "siswa123". Simpan sebagai .xlsx sebelum diupload.');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '7c3aed']],
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ede9fe']],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(30);

                $sheet->getColumnDimension('A')->setWidth(5);
                $sheet->getColumnDimension('B')->setWidth(15);
                $sheet->getColumnDimension('C')->setWidth(30);
                $sheet->getColumnDimension('D')->setWidth(15);

                $sheet->freezePane('A3');
            },
        ];
    }
}

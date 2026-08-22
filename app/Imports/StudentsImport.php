<?php

namespace App\Imports;

use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    private int $schoolId;
    private array $subjectMap; // ['nama_mapel_normalized' => id]
    public array $results = [];
    public array $errors  = [];

    public function __construct(int $schoolId)
    {
        $this->schoolId = $schoolId;

        // Buat map nama mapel → id (normalize: lowercase, trim)
        $this->subjectMap = Subject::all()->mapWithKeys(function ($subject) {
            return [strtolower(trim($subject->name)) => $subject->id];
        })->toArray();
    }

    public function collection(Collection $rows): void
    {
        // Skip baris petunjuk (jika kolom NISN kosong, skip)
        $dataRows = $rows->filter(function ($row) {
            $val = $row['nisn'] ?? $row['nis'] ?? null;
            return ! empty($val) && ! empty($row['nama_siswa']);
        });

        foreach ($dataRows as $index => $row) {
            $rowNum = $index + 2; // Excel row number (header = 1)
            $nisn   = trim((string) ($row['nisn'] ?? $row['nis'] ?? ''));
            $name   = trim((string) $row['nama_siswa']);
            $class  = isset($row['kelas']) ? trim((string) $row['kelas']) : null;

            if (empty($nisn) || empty($name)) {
                $this->errors[] = "Baris {$rowNum}: NISN atau Nama kosong, dilewati.";
                continue;
            }

            try {
                // Cari atau buat siswa
                $student = User::where('nisn', $nisn)
                    ->where('school_id', $this->schoolId)
                    ->first();

                if ($student) {
                    // Update data siswa
                    $student->update([
                        'name'  => $name,
                        'class' => $class ?: $student->class,
                    ]);
                    $action = 'diperbarui';
                } else {
                    // Buat siswa baru dengan password default
                    $student = User::create([
                        'name'      => $name,
                        'nisn'      => $nisn,
                        'class'     => $class,
                        'school_id' => $this->schoolId,
                        'role'      => 'student',
                        'is_active' => true,
                        'password'  => 'siswa123',
                    ]);
                    $action = 'dibuat baru';
                }

                // Simpan nilai mapel
                $scoresSaved = 0;
                foreach ($row as $colKey => $value) {
                    // Skip kolom bukan mapel
                    if (in_array($colKey, ['no', 'nisn', 'nama_siswa', 'kelas', ''])) {
                        continue;
                    }

                    $normalizedKey = strtolower(trim($colKey));
                    $subjectId = $this->subjectMap[$normalizedKey] ?? null;

                    if (! $subjectId) {
                        // Coba partial match
                        foreach ($this->subjectMap as $mapelName => $id) {
                            if (str_contains($mapelName, $normalizedKey) || str_contains($normalizedKey, $mapelName)) {
                                $subjectId = $id;
                                break;
                            }
                        }
                    }

                    if (! $subjectId) continue;

                    $score = $value !== '' && $value !== null ? (float) $value : null;

                    if ($score !== null && $score >= 0 && $score <= 100) {
                        StudentScore::updateOrCreate(
                            ['user_id' => $student->id, 'subject_id' => $subjectId],
                            ['score' => $score]
                        );
                        $scoresSaved++;
                    }
                }

                $this->results[] = [
                    'nisn'   => $nisn,
                    'name'   => $name,
                    'action' => $action,
                    'scores' => $scoresSaved,
                ];

            } catch (\Throwable $e) {
                $this->errors[] = "Baris {$rowNum} (NISN: {$nisn}): " . $e->getMessage();
            }
        }
    }

    /**
     * Heading row normalization — convert to snake_case for consistent column lookup.
     */
    public function headingRow(): int
    {
        return 1;
    }
}

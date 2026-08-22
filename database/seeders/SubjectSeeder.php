<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // Mata Pelajaran Umum (sesuai angket nilai siswa)
            ['id' => 1,  'name' => 'PAI & Budi Pekerti',  'code' => 'PAI'],
            ['id' => 2,  'name' => 'PKn',                  'code' => 'PKN'],
            ['id' => 3,  'name' => 'Bahasa Indonesia',     'code' => 'BIND'],
            ['id' => 4,  'name' => 'Matematika',           'code' => 'MTK'],
            ['id' => 5,  'name' => 'Bahasa Inggris',       'code' => 'BING'],
            ['id' => 6,  'name' => 'PJOK',                 'code' => 'PJOK'],
            ['id' => 7,  'name' => 'Sejarah',              'code' => 'SEJ'],
            ['id' => 8,  'name' => 'Seni Budaya',          'code' => 'SBD'],
            ['id' => 9,  'name' => 'Mulok (B. Sunda)',     'code' => 'MLOK'],
            ['id' => 10, 'name' => 'Informatika',          'code' => 'INF'],
            ['id' => 11, 'name' => 'Matematika Lanjut',    'code' => 'MTKL'],
            ['id' => 12, 'name' => 'Prakarya',             'code' => 'PRAK'],
            // Mata Pelajaran Peminatan
            ['id' => 13, 'name' => 'Fisika',               'code' => 'FIS'],
            ['id' => 14, 'name' => 'Geografi',             'code' => 'GEO'],
            ['id' => 15, 'name' => 'B. Inggris Lanjut',   'code' => 'BINGL'],
            ['id' => 16, 'name' => 'Ekonomi',              'code' => 'EKO'],
            ['id' => 17, 'name' => 'Kimia',                'code' => 'KIM'],
            ['id' => 18, 'name' => 'Biologi',              'code' => 'BIO'],
            ['id' => 19, 'name' => 'Sosiologi',            'code' => 'SOS'],
            // Tabel Bawah (IPA & IPS)
            ['id' => 20, 'name' => 'IPA',                  'code' => 'IPA'],
            ['id' => 21, 'name' => 'IPS',                  'code' => 'IPS'],
        ];

        // Hapus subject selain 1-21
        Subject::whereNotIn('id', array_column($subjects, 'id'))->delete();

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(['id' => $subject['id']], $subject);
        }
    }
}

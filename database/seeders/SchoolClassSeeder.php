<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('name', 'SMAN 18 Garut')->first();

        if (! $school) {
            return;
        }

        $classes = [
            // Kelas XII
            ['name' => 'XII IPA 1', 'grade' => 'XII'],
            ['name' => 'XII IPA 2', 'grade' => 'XII'],
            ['name' => 'XII IPA 3', 'grade' => 'XII'],
            ['name' => 'XII IPS 1', 'grade' => 'XII'],
            ['name' => 'XII IPS 2', 'grade' => 'XII'],
            ['name' => 'XII IPS 3', 'grade' => 'XII'],

            // Kelas XI
            ['name' => 'XI MIPA 1', 'grade' => 'XI'],
            ['name' => 'XI MIPA 2', 'grade' => 'XI'],
            ['name' => 'XI IPS 1', 'grade' => 'XI'],
            ['name' => 'XI IPS 2', 'grade' => 'XI'],

            // Kelas X
            ['name' => 'X-1', 'grade' => 'X'],
            ['name' => 'X-2', 'grade' => 'X'],
            ['name' => 'X-3', 'grade' => 'X'],
            ['name' => 'X-4', 'grade' => 'X'],
            ['name' => 'X-5', 'grade' => 'X'],
        ];

        foreach ($classes as $item) {
            SchoolClass::updateOrCreate(
                [
                    'school_id' => $school->id,
                    'name'      => $item['name'],
                ],
                [
                    'grade'     => $item['grade'],
                    'is_active' => true,
                ]
            );
        }
    }
}

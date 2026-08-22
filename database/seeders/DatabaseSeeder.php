<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
            InterestCategorySeeder::class,
            QuestionnaireSeeder::class,
            StudyProgramSeeder::class,
            SchoolSeeder::class,
            AdminUserSeeder::class,
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::where('name', 'SMAN 18 Garut')->first();

        // Admin (Guru BK SMAN 18 Garut)
        User::updateOrCreate(
            ['email' => 'admin@findmycareer.id'],
            [
                'name'      => 'Guru BK SMAN 18 Garut',
                'password'  => 'admin123',
                'role'      => 'admin',
                'is_active' => true,
                'school_id' => $school?->id,
            ]
        );
    }
}

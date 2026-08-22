<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan SMAN 18 Garut terdaftar sebagai sekolah utama
        $school = School::updateOrCreate(
            ['name' => 'SMAN 18 Garut'],
            [
                'npsn'      => '20209182',
                'address'   => 'Jl. Perum Bumi Abdi Negara 1 Karangpawitan',
                'city'      => 'Kab. Garut',
                'province'  => 'Prov. Jawa Barat',
                'is_active' => true,
            ]
        );

        // Update admin agar terhubung ke SMAN 18 Garut
        User::where('role', 'admin')->update(['school_id' => $school->id]);
    }
}

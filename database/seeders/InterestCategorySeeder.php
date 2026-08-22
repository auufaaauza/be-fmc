<?php

namespace Database\Seeders;

use App\Models\InterestCategory;
use Illuminate\Database\Seeder;

class InterestCategorySeeder extends Seeder
{
    public function run(): void
    {
        // 6 Kategori RIASEC (Holland's Theory of Career Choice)
        $categories = [
            [
                'id'          => 1,
                'name'        => 'Realistic (Praktis)',
                'code'        => 'R',
                'icon'        => '🔧',
                'description' => 'Menyukai aktivitas praktis, teknis, dan bekerja dengan alat/mesin/alam.',
            ],
            [
                'id'          => 2,
                'name'        => 'Investigative (Ilmiah)',
                'code'        => 'I',
                'icon'        => '🔬',
                'description' => 'Menyukai kegiatan berpikir, menganalisis, dan memecahkan masalah ilmiah.',
            ],
            [
                'id'          => 3,
                'name'        => 'Artistic (Kreatif)',
                'code'        => 'A',
                'icon'        => '🎨',
                'description' => 'Menyukai ekspresi kreatif, seni, musik, tulisan, dan hal-hal estetis.',
            ],
            [
                'id'          => 4,
                'name'        => 'Social (Sosial)',
                'code'        => 'S',
                'icon'        => '🤝',
                'description' => 'Menyukai berinteraksi, membantu, mengajar, dan bekerja bersama orang lain.',
            ],
            [
                'id'          => 5,
                'name'        => 'Enterprising (Kepemimpinan)',
                'code'        => 'E',
                'icon'        => '🚀',
                'description' => 'Menyukai memimpin, mempengaruhi orang lain, berwirausaha, dan negosiasi.',
            ],
            [
                'id'          => 6,
                'name'        => 'Conventional (Teratur)',
                'code'        => 'C',
                'icon'        => '📋',
                'description' => 'Menyukai pekerjaan terstruktur, data, administrasi, dan keteraturan.',
            ],
        ];

        foreach ($categories as $category) {
            InterestCategory::updateOrCreate(['id' => $category['id']], $category);
        }

        // Hapus kategori lama jika ada lebih dari 6
        InterestCategory::where('id', '>', 6)->delete();
    }
}

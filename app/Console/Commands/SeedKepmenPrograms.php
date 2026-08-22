<?php

namespace App\Console\Commands;

use App\Models\ProgramCriteria;
use App\Models\RecommendationResult;
use App\Models\StudyProgram;
use Illuminate\Console\Command;

class SeedKepmenPrograms extends Command
{
    protected $signature = 'kepmen:seed';
    protected $description = 'Seed 59 kelompok program studi resmi berdasarkan Kepmendikdasmen No. 102/M/2025 beserta data PTN/PTS rujukan';

    public function handle(): void
    {
        // Subject IDs:
        // 1=MTK, 2=B.Indo, 3=B.Ing, 4=Fis, 5=Kim, 6=Bio, 7=Eko, 8=Sos, 9=Geo, 10=SBD, 11=Sej, 12=PKN, 13=PJOK, 14=Ant
        // Interest RIASEC Category IDs:
        // 1=Realistic, 2=Investigative, 3=Artistic, 4=Social, 5=Enterprising, 6=Conventional

        $programs = [
            // ── 1. HUMANIORA (1 s/d 5) ──
            [
                'name' => 'Seni',
                'faculty' => 'Fakultas Seni dan Desain',
                'description' => 'Mempelajari berbagai cabang seni seperti seni rupa, seni pertunjukan, dan seni kriya sebagai ekspresi budaya dan kreativitas manusia.',
                'career_paths' => ['Seniman', 'Kurator Galeri', 'Desainer', 'Pengajar Seni', 'Animator'],
                'learning_path' => ['Tahun 1' => 'Pengantar seni, sejarah seni, teknik dasar seni rupa', 'Tahun 2' => 'Studio seni, estetika, seni kontemporer', 'Tahun 3' => 'Spesialisasi bidang seni, kritik seni, pameran', 'Tahun 4' => 'Skripsi, pameran karya akhir, magang di lembaga seni'],
                'universities' => ['ITB (FSRD)', 'ISI Yogyakarta', 'ISI Surakarta', 'IKJ Jakarta', 'UNS Surakarta'],
                'criteria' => ['primary_subject_id' => 10, 'primary_weight' => 0.50, 'secondary_subject_id' => 11, 'secondary_weight' => 0.20, 'interest_category_id' => 5, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Sejarah',
                'faculty' => 'Fakultas Ilmu Budaya',
                'description' => 'Mempelajari peristiwa, tokoh, dan perkembangan peradaban manusia dari masa lampau hingga masa kini sebagai fondasi pemahaman dunia modern.',
                'career_paths' => ['Sejarawan', 'Peneliti', 'Kurator Museum', 'Guru Sejarah', 'Arsivis'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu sejarah, historiografi, metodologi sejarah', 'Tahun 2' => 'Sejarah Indonesia, sejarah dunia, sejarah lokal', 'Tahun 3' => 'Sejarah kontemporer, filsafat sejarah, penelitian arsip', 'Tahun 4' => 'Skripsi dan magang di museum atau lembaga kearsipan'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'UPI Bandung', 'Universitas Diponegoro (Undip)'],
                'criteria' => ['primary_subject_id' => 11, 'primary_weight' => 0.50, 'secondary_subject_id' => 2, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Linguistik',
                'faculty' => 'Fakultas Ilmu Budaya',
                'description' => 'Mempelajari struktur, fungsi, dan perkembangan bahasa manusia secara ilmiah, termasuk fonologi, morfologi, sintaksis, dan semantik.',
                'career_paths' => ['Ahli Bahasa', 'Penerjemah', 'Peneliti Bahasa', 'Pengajar Bahasa', 'Penulis'],
                'learning_path' => ['Tahun 1' => 'Pengantar linguistik, fonetik, morfologi', 'Tahun 2' => 'Sintaksis, semantik, sosiolinguistik', 'Tahun 3' => 'Psikolinguistik, linguistik komputasional, analisis wacana', 'Tahun 4' => 'Skripsi dan magang di lembaga bahasa atau penerbitan'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Airlangga (Unair)', 'UPI Bandung'],
                'criteria' => ['primary_subject_id' => 2, 'primary_weight' => 0.50, 'secondary_subject_id' => 3, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Susastra',
                'faculty' => 'Fakultas Ilmu Budaya',
                'description' => 'Mempelajari karya sastra Indonesia dan mancanegara, kritik sastra, serta teori dan sejarah kesusastraan.',
                'career_paths' => ['Penulis', 'Sastrawan', 'Editor', 'Kritikus Sastra', 'Pengajar Sastra'],
                'learning_path' => ['Tahun 1' => 'Pengantar sastra, teori sastra, membaca kritis', 'Tahun 2' => 'Sastra Indonesia, sastra dunia, kritik sastra', 'Tahun 3' => 'Sastra modern, penulisan kreatif, terjemahan sastra', 'Tahun 4' => 'Skripsi dan penerbitan karya'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 2, 'primary_weight' => 0.50, 'secondary_subject_id' => 3, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Filsafat',
                'faculty' => 'Fakultas Filsafat',
                'description' => 'Mempelajari pertanyaan mendasar tentang keberadaan, pengetahuan, nilai, akal, dan pikiran manusia serta hubungannya dengan alam semesta.',
                'career_paths' => ['Filsuf', 'Peneliti', 'Konsultan Etika', 'Pengajar', 'Penulis'],
                'learning_path' => ['Tahun 1' => 'Pengantar filsafat, logika, sejarah filsafat', 'Tahun 2' => 'Metafisika, epistemologi, etika', 'Tahun 3' => 'Filsafat ilmu, filsafat politik, filsafat agama', 'Tahun 4' => 'Skripsi dan pengembangan pemikiran kritis'],
                'universities' => ['Universitas Gadjah Mada (UGM)', 'Universitas Indonesia (UI)', 'Universitas Katolik Parahyangan (Unpar)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 11, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],

            // ── 2. ILMU SOSIAL (6 s/d 9) ──
            [
                'name' => 'Ilmu Sosial',
                'faculty' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'description' => 'Mempelajari perilaku sosial manusia, struktur masyarakat, interaksi sosial, serta fenomena kemasyarakatan secara ilmiah.',
                'career_paths' => ['Peneliti Sosial', 'Konsultan Pembangunan', 'Analis Kebijakan', 'NGO Worker', 'Diplomat'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu sosial, sosiologi dasar, antropologi', 'Tahun 2' => 'Metode penelitian sosial, statistika sosial, teori sosial', 'Tahun 3' => 'Sosiologi pembangunan, kebijakan sosial, riset lapangan', 'Tahun 4' => 'Skripsi dan magang di lembaga penelitian atau NGO'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Airlangga (Unair)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 11, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ekonomi',
                'faculty' => 'Fakultas Ekonomi dan Bisnis',
                'description' => 'Mempelajari bagaimana individu, perusahaan, dan pemerintah mengalokasikan sumber daya dan memahami pasar.',
                'career_paths' => ['Ekonom', 'Analis Ekonomi', 'Konsultan Ekonomi', 'Peneliti', 'PNS Kemenkeu'],
                'learning_path' => ['Tahun 1' => 'Ekonomi mikro, ekonomi makro, matematika ekonomi', 'Tahun 2' => 'Ekonometrika, teori ekonomi, statistika ekonomi', 'Tahun 3' => 'Ekonomi pembangunan, ekonomi internasional, riset ekonomi', 'Tahun 4' => 'Skripsi dan magang di lembaga ekonomi atau bank'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Airlangga (Unair)'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Pertahanan',
                'faculty' => 'Fakultas Keamanan Nasional',
                'description' => 'Mempelajari strategi pertahanan negara, keamanan nasional, hubungan internasional di bidang militer, dan kebijakan pertahanan.',
                'career_paths' => ['Analis Pertahanan', 'PNS Kemenhan', 'Konsultan Keamanan', 'Peneliti Kebijakan Pertahanan'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu pertahanan, Pancasila, geopolitik', 'Tahun 2' => 'Strategi pertahanan, hubungan internasional, hukum internasional', 'Tahun 3' => 'Manajemen krisis, intelijen, keamanan siber', 'Tahun 4' => 'Skripsi dan magang di lembaga pertahanan'],
                'universities' => ['Universitas Pertahanan (Unhan)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)'],
                'criteria' => ['primary_subject_id' => 12, 'primary_weight' => 0.50, 'secondary_subject_id' => 8, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Psikologi',
                'faculty' => 'Fakultas Psikologi',
                'description' => 'Mempelajari perilaku manusia, kesehatan mental, dan penerapan ilmu psikologi dalam berbagai bidang kehidupan.',
                'career_paths' => ['Psikolog Klinis', 'HRD', 'Konselor', 'Psikolog Industri', 'Peneliti Perilaku'],
                'learning_path' => ['Tahun 1' => 'Pengantar psikologi, biologi dasar, statistika', 'Tahun 2' => 'Psikologi kepribadian, psikologi kognitif, psikometri', 'Tahun 3' => 'Psikologi klinis, psikologi industri, asesmen psikologi', 'Tahun 4' => 'Skripsi dan praktikum psikologi'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'UPI Bandung', 'Universitas Airlangga (Unair)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],

            // ── 3. ILMU ALAM (10 s/d 16) ──
            [
                'name' => 'Kimia',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari komposisi, sifat, dan perubahan zat serta reaksi kimia yang mendasari kehidupan dan industri modern.',
                'career_paths' => ['Ahli Kimia', 'Peneliti', 'Quality Control', 'Analis Laboratorium', 'Konsultan Lingkungan'],
                'learning_path' => ['Tahun 1' => 'Kimia dasar, matematika, fisika dasar', 'Tahun 2' => 'Kimia organik, kimia anorganik, kimia analitik', 'Tahun 3' => 'Kimia fisik, kimia lingkungan, instrumentasi', 'Tahun 4' => 'Skripsi dan penelitian laboratorium'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Institut Teknologi Sepuluh Nopember (ITS)'],
                'criteria' => ['primary_subject_id' => 5, 'primary_weight' => 0.50, 'secondary_subject_id' => 6, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Kebumian',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari bumi, struktur geologinya, atmosfer, hidrosfer, dan proses fisika yang terjadi di dalam dan di permukaan bumi.',
                'career_paths' => ['Geolog', 'Ahli Geofisika', 'Peneliti BMKG', 'Konsultan Pertambangan', 'Ahli Bencana Alam'],
                'learning_path' => ['Tahun 1' => 'Fisika dasar, matematika, geologi dasar', 'Tahun 2' => 'Geofisika, mineralogi, kristalografi', 'Tahun 3' => 'Seismologi, vulkanologi, eksplorasi sumber daya', 'Tahun 4' => 'Skripsi dan magang di BMKG atau perusahaan tambang'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)', 'UPN Veteran Yogyakarta'],
                'criteria' => ['primary_subject_id' => 4, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Kelautan',
                'faculty' => 'Fakultas Perikanan dan Ilmu Kelautan',
                'description' => 'Mempelajari ekosistem laut, oseanografi, biota laut, dan pengelolaan sumber daya laut secara berkelanjutan.',
                'career_paths' => ['Oseanografer', 'Peneliti Kelautan', 'Konsultan Lingkungan Laut', 'Analis KKP', 'Ahli Terumbu Karang'],
                'learning_path' => ['Tahun 1' => 'Biologi laut, kimia laut, fisika laut', 'Tahun 2' => 'Oseanografi fisik dan kimia, ekologi laut', 'Tahun 3' => 'Pengelolaan sumber daya laut, pencemaran laut', 'Tahun 4' => 'Skripsi dan penelitian lapangan di laut'],
                'universities' => ['IPB University', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Hasanuddin (Unhas)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Biologi',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari makhluk hidup, struktur, fungsi, pertumbuhan, evolusi, distribusi, dan taksonomi organisme di alam.',
                'career_paths' => ['Peneliti Biologi', 'Ahli Lingkungan', 'Konsultan AMDAL', 'Guru Biologi', 'Biotechnologist'],
                'learning_path' => ['Tahun 1' => 'Biologi sel, kimia dasar, fisika dasar, matematika', 'Tahun 2' => 'Genetika, fisiologi tumbuhan dan hewan, ekologi', 'Tahun 3' => 'Bioteknologi, mikrobiologi, biologi molekuler', 'Tahun 4' => 'Skripsi dan penelitian laboratorium atau lapangan'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'IPB University'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Biofisika',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Menggabungkan prinsip fisika dengan sistem biologi untuk memahami mekanisme fisik di balik proses kehidupan pada tingkat molekuler dan seluler.',
                'career_paths' => ['Peneliti Biofisika', 'Ilmuwan Medis', 'Analis Laboratorium', 'Akademisi', 'Pengembang Instrumen Medis'],
                'learning_path' => ['Tahun 1' => 'Fisika dasar, biologi sel, matematika, kimia', 'Tahun 2' => 'Termodinamika biologi, mekanika kuantum biologi', 'Tahun 3' => 'Biofisika molekuler, spektroskopi, pencitraan biologi', 'Tahun 4' => 'Skripsi dan penelitian interdisipliner'],
                'universities' => ['IPB University', 'Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)'],
                'criteria' => ['primary_subject_id' => 4, 'primary_weight' => 0.50, 'secondary_subject_id' => 6, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Fisika',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari hukum-hukum alam, materi, energi, dan interaksi antar benda dari skala subatomik hingga kosmologis.',
                'career_paths' => ['Fisikawan', 'Peneliti', 'Engineer', 'Analis Data', 'Guru Fisika'],
                'learning_path' => ['Tahun 1' => 'Mekanika klasik, kalkulus, fisika modern', 'Tahun 2' => 'Elektrodinamika, termodinamika, mekanika kuantum', 'Tahun 3' => 'Fisika nuklir, fisika material, komputasi fisika', 'Tahun 4' => 'Skripsi dan penelitian laboratorium'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'ITS Surabaya'],
                'criteria' => ['primary_subject_id' => 4, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Astronomi',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari benda-benda langit, alam semesta, dan fenomena kosmik menggunakan prinsip fisika dan matematika.',
                'career_paths' => ['Astronom', 'Astrofisikawan', 'Peneliti LAPAN/BRIN', 'Akademisi', 'Data Scientist'],
                'learning_path' => ['Tahun 1' => 'Fisika dasar, matematika, pengantar astronomi', 'Tahun 2' => 'Mekanika benda langit, astrofisika, observasi', 'Tahun 3' => 'Kosmologi, spektroskopi bintang, komputasi astronomi', 'Tahun 4' => 'Skripsi dan observasi teleskop'],
                'universities' => ['Institut Teknologi Bandung (ITB)'],
                'criteria' => ['primary_subject_id' => 4, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],

            // ── 4. ILMU FORMAL (17 s/d 19) ──
            [
                'name' => 'Ilmu Komputer',
                'faculty' => 'Fakultas Ilmu Komputer',
                'description' => 'Mempelajari dasar-dasar teoritis komputasi, algoritma, struktur data, kecerdasan buatan, dan pengembangan sistem perangkat lunak.',
                'career_paths' => ['Software Engineer', 'Data Scientist', 'AI Engineer', 'Cybersecurity Analyst', 'System Architect'],
                'learning_path' => ['Tahun 1' => 'Pemrograman dasar, matematika diskrit, logika', 'Tahun 2' => 'Algoritma & struktur data, basis data, jaringan', 'Tahun 3' => 'Kecerdasan buatan, rekayasa perangkat lunak, keamanan siber', 'Tahun 4' => 'Skripsi, magang industri, proyek akhir'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'Universitas Padjadjaran (Unpad)', 'ITS Surabaya', 'Telkom University', 'Binus University'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Logika',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari prinsip-prinsip penalaran yang valid, logika formal, dan aplikasinya dalam matematika, filsafat, dan ilmu komputer.',
                'career_paths' => ['Peneliti', 'Akademisi', 'Analis Sistem', 'Konsultan AI', 'Pengembang Perangkat Lunak'],
                'learning_path' => ['Tahun 1' => 'Logika proposisional, aljabar Boolean, teori himpunan', 'Tahun 2' => 'Logika predikat, teori model, pembuktian formal', 'Tahun 3' => 'Logika modal, logika komputasional, kompleksitas', 'Tahun 4' => 'Skripsi dan penelitian logika terapan'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Matematika',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari bilangan, struktur, ruang, dan perubahan secara abstrak sebagai fondasi ilmu pengetahuan dan teknologi.',
                'career_paths' => ['Aktuaris', 'Data Analyst', 'Peneliti Matematika', 'Dosen Matematika', 'Konsultan Keuangan'],
                'learning_path' => ['Tahun 1' => 'Kalkulus, aljabar linear, logika matematika', 'Tahun 2' => 'Analisis real, aljabar abstrak, probabilitas', 'Tahun 3' => 'Analisis numerik, matematika terapan, statistika matematika', 'Tahun 4' => 'Skripsi dan penelitian matematika murni atau terapan'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'ITS Surabaya'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],

            // ── 5. ILMU TERAPAN (20 s/d 59) ──
            [
                'name' => 'Ilmu Pertanian',
                'faculty' => 'Fakultas Pertanian',
                'description' => 'Mempelajari teknik budidaya tanaman, pengelolaan lahan pertanian, dan inovasi untuk meningkatkan produktivitas pangan secara berkelanjutan.',
                'career_paths' => ['Ahli Pertanian', 'Penyuluh Pertanian', 'Peneliti Pertanian', 'Wirausaha Agribisnis', 'Konsultan Pertanian'],
                'learning_path' => ['Tahun 1' => 'Botani, kimia tanah, dasar agronomi', 'Tahun 2' => 'Fisiologi tumbuhan, hama penyakit, irigasi', 'Tahun 3' => 'Teknologi pertanian, agribisnis, pertanian organik', 'Tahun 4' => 'Skripsi dan magang di perkebunan atau lembaga pertanian'],
                'universities' => ['IPB University', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)', 'Universitas Brawijaya (UB)', 'Universitas Sebelas Maret (UNS)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Peternakan',
                'faculty' => 'Fakultas Peternakan',
                'description' => 'Mempelajari pemeliharaan, pengembangbiakan, dan pengelolaan hewan ternak untuk menghasilkan produk pangan dan non-pangan berkualitas.',
                'career_paths' => ['Peternak', 'Ahli Nutrisi Ternak', 'Konsultan Peternakan', 'Peneliti', 'Wirausaha Peternakan'],
                'learning_path' => ['Tahun 1' => 'Biologi hewan, kimia dasar, anatomi ternak', 'Tahun 2' => 'Nutrisi ternak, reproduksi ternak, manajemen ternak', 'Tahun 3' => 'Teknologi hasil ternak, kesehatan hewan, agribisnis ternak', 'Tahun 4' => 'Skripsi dan magang di peternakan atau industri pakan'],
                'universities' => ['IPB University', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)', 'Universitas Diponegoro (Undip)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Perikanan',
                'faculty' => 'Fakultas Perikanan dan Ilmu Kelautan',
                'description' => 'Mempelajari budidaya ikan, pengelolaan sumber daya perikanan, teknologi pengolahan hasil perikanan, dan kelestarian ekosistem perairan.',
                'career_paths' => ['Pembudidaya Ikan', 'Peneliti Perikanan', 'Konsultan KKP', 'Wirausaha Perikanan', 'Analis Kualitas Ikan'],
                'learning_path' => ['Tahun 1' => 'Biologi perikanan, kimia air, ekologi perairan', 'Tahun 2' => 'Budidaya perikanan, nutrisi ikan, penyakit ikan', 'Tahun 3' => 'Teknologi pengolahan hasil perikanan, manajemen perikanan', 'Tahun 4' => 'Skripsi dan magang di balai perikanan'],
                'universities' => ['IPB University', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Arsitektur',
                'faculty' => 'Fakultas Teknik',
                'description' => 'Mempelajari perancangan bangunan yang estetis, fungsional, dan berkelanjutan dengan memadukan seni, sains, dan teknologi konstruksi.',
                'career_paths' => ['Arsitek', 'Desainer Interior', 'Urban Planner', 'Konsultan Bangunan', 'Pengembang Properti'],
                'learning_path' => ['Tahun 1' => 'Gambar teknik, desain arsitektur dasar, sejarah arsitektur', 'Tahun 2' => 'Struktur bangunan, material bangunan, teknologi konstruksi', 'Tahun 3' => 'Perancangan arsitektur lanjut, arsitektur hijau, urban design', 'Tahun 4' => 'Studio tugas akhir dan magang di firma arsitek'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'ITS Surabaya', 'Universitas Diponegoro (Undip)', 'Universitas Katolik Parahyangan (Unpar)'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 5, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Perencanaan Wilayah dan Kota',
                'faculty' => 'Fakultas Teknik',
                'description' => 'Mempelajari perencanaan tata ruang, pengembangan wilayah, dan pengelolaan kota yang berkelanjutan untuk meningkatkan kualitas hidup masyarakat.',
                'career_paths' => ['Urban Planner', 'Konsultan Tata Ruang', 'Analis Kebijakan Wilayah', 'PNS Bappeda', 'Peneliti Wilayah'],
                'learning_path' => ['Tahun 1' => 'Pengantar perencanaan, geografi, matematika, ekonomi', 'Tahun 2' => 'Analisis wilayah, sistem informasi geografis, tata ruang', 'Tahun 3' => 'Perencanaan transportasi, perencanaan lingkungan, kebijakan kota', 'Tahun 4' => 'Studio tugas akhir dan magang di Bappeda atau konsultan'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Diponegoro (Undip)', 'Universitas Gadjah Mada (UGM)', 'ITS Surabaya', 'Unisba'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Desain',
                'faculty' => 'Fakultas Seni dan Desain',
                'description' => 'Mempelajari prinsip desain visual, komunikasi visual, dan estetika untuk menciptakan solusi kreatif dalam media, produk, dan lingkungan.',
                'career_paths' => ['Desainer Grafis', 'UI/UX Designer', 'Desainer Produk', 'Creative Director', 'Animator'],
                'learning_path' => ['Tahun 1' => 'Dasar desain, tipografi, teori warna, menggambar', 'Tahun 2' => 'Desain digital, ilustrasi, branding, fotografi', 'Tahun 3' => 'UI/UX design, motion graphic, proyek klien', 'Tahun 4' => 'Portofolio akhir dan magang di agensi kreatif'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Telkom University', 'ISI Yogyakarta', 'Universitas Trisakti', 'IKJ Jakarta'],
                'criteria' => ['primary_subject_id' => 10, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 5, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Akuntansi',
                'faculty' => 'Fakultas Ekonomi dan Bisnis',
                'description' => 'Mempelajari pencatatan keuangan, audit, perpajakan, dan pelaporan keuangan perusahaan untuk mendukung pengambilan keputusan bisnis.',
                'career_paths' => ['Akuntan Publik', 'Auditor', 'Tax Consultant', 'Finance Manager', 'CFO'],
                'learning_path' => ['Tahun 1' => 'Pengantar akuntansi, matematika bisnis, ekonomi mikro', 'Tahun 2' => 'Akuntansi keuangan, akuntansi biaya, perpajakan', 'Tahun 3' => 'Audit, akuntansi manajemen, sistem informasi akuntansi', 'Tahun 4' => 'Skripsi dan magang di KAP atau perusahaan'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Manajemen',
                'faculty' => 'Fakultas Ekonomi dan Bisnis',
                'description' => 'Mempelajari pengelolaan organisasi, pemasaran, sumber daya manusia, dan strategi bisnis untuk mencapai tujuan perusahaan secara efektif.',
                'career_paths' => ['Manajer', 'Marketing Manager', 'Business Analyst', 'Entrepreneur', 'Konsultan Manajemen'],
                'learning_path' => ['Tahun 1' => 'Pengantar manajemen, akuntansi, ekonomi bisnis', 'Tahun 2' => 'Manajemen pemasaran, SDM, operasional', 'Tahun 3' => 'Manajemen strategis, kewirausahaan, riset bisnis', 'Tahun 4' => 'Skripsi dan magang di perusahaan'],
                'universities' => ['Universitas Indonesia (UI)', 'ITB (SBM)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Telkom University'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Logistik',
                'faculty' => 'Fakultas Bisnis',
                'description' => 'Mempelajari pengelolaan rantai pasok, distribusi barang, manajemen gudang, dan optimasi jaringan logistik dari produsen ke konsumen.',
                'career_paths' => ['Manajer Logistik', 'Supply Chain Analyst', 'Planner Distribusi', 'Konsultan SCM', 'Manajer Operasional'],
                'learning_path' => ['Tahun 1' => 'Pengantar logistik, matematika bisnis, manajemen dasar', 'Tahun 2' => 'Manajemen rantai pasok, pergudangan, transportasi', 'Tahun 3' => 'Optimasi logistik, teknologi logistik, e-commerce logistics', 'Tahun 4' => 'Skripsi dan magang di perusahaan logistik atau manufaktur'],
                'universities' => ['Universitas Logistik & Bisnis Internasional (ULBI Bandung)', 'ITS Surabaya', 'Institut Transportasi dan Logistik Trisakti'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Administrasi Bisnis',
                'faculty' => 'Fakultas Ilmu Administrasi',
                'description' => 'Mempelajari pengelolaan administrasi perusahaan, strategi bisnis, keuangan perusahaan, dan kebijakan organisasi untuk mencapai efisiensi.',
                'career_paths' => ['Administrator Bisnis', 'Business Development Manager', 'Office Manager', 'Konsultan Bisnis', 'Entrepreneur'],
                'learning_path' => ['Tahun 1' => 'Pengantar administrasi bisnis, ekonomi, akuntansi dasar', 'Tahun 2' => 'Manajemen sumber daya, hukum bisnis, pemasaran', 'Tahun 3' => 'Strategi bisnis, keuangan perusahaan, kewirausahaan', 'Tahun 4' => 'Skripsi dan magang di perusahaan swasta atau BUMN'],
                'universities' => ['Universitas Padjadjaran (Unpad)', 'Universitas Indonesia (UI)', 'Telkom University', 'Universitas Brawijaya (UB)', 'Universitas Diponegoro (Undip)'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Bisnis',
                'faculty' => 'Fakultas Bisnis',
                'description' => 'Mempelajari aspek-aspek bisnis secara komprehensif meliputi pemasaran, keuangan, operasional, dan kewirausahaan untuk membangun dan mengembangkan usaha.',
                'career_paths' => ['Entrepreneur', 'Business Analyst', 'Marketing Executive', 'Product Manager', 'Konsultan Bisnis'],
                'learning_path' => ['Tahun 1' => 'Pengantar bisnis, ekonomi mikro makro, akuntansi dasar', 'Tahun 2' => 'Pemasaran digital, manajemen keuangan, hukum bisnis', 'Tahun 3' => 'Kewirausahaan, strategi bisnis, analitik bisnis', 'Tahun 4' => 'Skripsi dan inkubasi bisnis atau magang startup'],
                'universities' => ['ITB (SBM)', 'IPB University (Sekolah Bisnis)', 'Universitas Indonesia (UI)', 'Universitas Prasetiya Mulya'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Komunikasi',
                'faculty' => 'Fakultas Ilmu Sosial dan Ilmu Politik',
                'description' => 'Mempelajari teori komunikasi, jurnalisme, hubungan masyarakat, penyiaran, dan media massa dalam konteks sosial dan budaya.',
                'career_paths' => ['Jurnalis', 'Public Relations', 'Content Creator', 'News Anchor', 'Media Planner'],
                'learning_path' => ['Tahun 1' => 'Dasar komunikasi, sosiologi, pengantar media', 'Tahun 2' => 'Jurnalistik, public relations, komunikasi massa', 'Tahun 3' => 'Produksi media, riset komunikasi, manajemen media', 'Tahun 4' => 'Skripsi dan magang di media atau perusahaan'],
                'universities' => ['Universitas Padjadjaran (Unpad)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Telkom University', 'Universitas Diponegoro (Undip)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 2, 'secondary_weight' => 0.20, 'interest_category_id' => 5, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Pendidikan',
                'faculty' => 'Fakultas Keguruan dan Ilmu Pendidikan',
                'description' => 'Mempelajari ilmu pendidikan, metode pengajaran, psikologi pendidikan, dan pengembangan kurikulum untuk mencetak tenaga pendidik profesional.',
                'career_paths' => ['Guru', 'Dosen', 'Konselor Pendidikan', 'Pengembang Kurikulum', 'Trainer Perusahaan'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu pendidikan, psikologi perkembangan', 'Tahun 2' => 'Teori belajar, desain pembelajaran, media pembelajaran', 'Tahun 3' => 'Microteaching, manajemen kelas, evaluasi pendidikan', 'Tahun 4' => 'PPL, skripsi, dan magang di sekolah'],
                'universities' => ['UPI Bandung', 'UNJ Jakarta', 'UNY Yogyakarta', 'Universitas Negeri Malang (UM)', 'UNS Surakarta'],
                'criteria' => ['primary_subject_id' => 2, 'primary_weight' => 0.50, 'secondary_subject_id' => 8, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Teknik',
                'faculty' => 'Fakultas Teknik',
                'description' => 'Mempelajari prinsip-prinsip rekayasa untuk merancang, membangun, dan mengoptimalkan sistem, mesin, struktur, dan proses yang bermanfaat bagi manusia.',
                'career_paths' => ['Engineer', 'Project Manager', 'Konsultan Teknik', 'Research & Development', 'Technical Analyst'],
                'learning_path' => ['Tahun 1' => 'Matematika teknik, fisika teknik, kimia teknik', 'Tahun 2' => 'Mekanika, termodinamika, ilmu material', 'Tahun 3' => 'Desain sistem, manajemen proyek, rekayasa khusus', 'Tahun 4' => 'Tugas akhir dan magang di industri'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Indonesia (UI)', 'ITS Surabaya', 'Universitas Gadjah Mada (UGM)', 'Universitas Diponegoro (Undip)', 'Telkom University'],
                'criteria' => ['primary_subject_id' => 4, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Lingkungan',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari interaksi antara manusia dan lingkungan, pencemaran, perubahan iklim, dan strategi pelestarian lingkungan hidup.',
                'career_paths' => ['Ahli Lingkungan', 'Konsultan AMDAL', 'Peneliti Lingkungan', 'Analis KLHK', 'Aktivis Lingkungan'],
                'learning_path' => ['Tahun 1' => 'Ekologi dasar, biologi, kimia lingkungan', 'Tahun 2' => 'Pencemaran lingkungan, pengelolaan limbah, hidrologi', 'Tahun 3' => 'AMDAL, kebijakan lingkungan, perubahan iklim', 'Tahun 4' => 'Skripsi dan magang di KLHK atau lembaga lingkungan'],
                'universities' => ['Universitas Indonesia (UI)', 'IPB University', 'Institut Teknologi Bandung (ITB)', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kehutanan',
                'faculty' => 'Fakultas Kehutanan',
                'description' => 'Mempelajari pengelolaan hutan secara lestari, ekologi hutan, hasil hutan, dan konservasi keanekaragaman hayati hutan.',
                'career_paths' => ['Ahli Kehutanan', 'Manajer Hutan', 'Peneliti KLHK', 'Konsultan Konservasi', 'Wildlife Ranger'],
                'learning_path' => ['Tahun 1' => 'Dendrologi, ekologi hutan, silvikultur dasar', 'Tahun 2' => 'Manajemen hutan, hasil hutan bukan kayu, pemetaan hutan', 'Tahun 3' => 'Konservasi sumber daya alam, kebijakan kehutanan', 'Tahun 4' => 'Skripsi dan magang di Perum Perhutani atau KLHK'],
                'universities' => ['IPB University', 'Universitas Gadjah Mada (UGM)', 'Universitas Hasanuddin (Unhas)', 'Universitas Mulawarman (Unmul)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kedokteran',
                'faculty' => 'Fakultas Kedokteran',
                'description' => 'Mempelajari ilmu kedokteran, diagnosis penyakit, dan praktik klinis untuk menjadi dokter yang kompeten melayani kesehatan masyarakat.',
                'career_paths' => ['Dokter Umum', 'Dokter Spesialis', 'Peneliti Medis', 'Dokter Puskesmas', 'Akademisi Kedokteran'],
                'learning_path' => ['Tahun 1-2' => 'Anatomi, fisiologi, biokimia, histologi', 'Tahun 3-4' => 'Patologi, farmakologi, ilmu penyakit dalam', 'Tahun 5-6' => 'Klinik di rumah sakit (co-ass)', 'Profesi' => 'Internsip dokter 1 tahun'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)', 'Universitas Airlangga (Unair)', 'Universitas Diponegoro (Undip)', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kedokteran Gigi',
                'faculty' => 'Fakultas Kedokteran Gigi',
                'description' => 'Mempelajari ilmu kesehatan gigi dan mulut, diagnosis, pengobatan penyakit gigi, dan praktik kedokteran gigi klinis.',
                'career_paths' => ['Dokter Gigi', 'Spesialis Ortodonti', 'Dokter Gigi Puskesmas', 'Peneliti Gigi', 'Akademisi'],
                'learning_path' => ['Tahun 1-2' => 'Anatomi gigi, biokimia, histologi, embriologi', 'Tahun 3-4' => 'Ilmu konservasi gigi, bedah mulut, ortodonti', 'Tahun 5' => 'Klinik profesi kedokteran gigi', 'Profesi' => 'Internsip dokter gigi'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Padjadjaran (Unpad)', 'Universitas Gadjah Mada (UGM)', 'Universitas Airlangga (Unair)', 'Universitas Trisakti'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kedokteran Hewan',
                'faculty' => 'Fakultas Kedokteran Hewan',
                'description' => 'Mempelajari ilmu kesehatan hewan, diagnosa penyakit hewan, bedah veteriner, serta peran dokter hewan dalam ketahanan pangan dan kesehatan masyarakat.',
                'career_paths' => ['Dokter Hewan', 'Ahli Kesehatan Ternak', 'Peneliti', 'PNS Kementan', 'Konsultan Peternakan'],
                'learning_path' => ['Tahun 1-2' => 'Anatomi hewan, fisiologi, biokimia, histologi', 'Tahun 3-4' => 'Patologi veteriner, farmakologi, bedah hewan', 'Tahun 5' => 'Klinik hewan dan magang', 'Profesi' => 'Koasistensi dokter hewan'],
                'universities' => ['IPB University', 'Universitas Gadjah Mada (UGM)', 'Universitas Airlangga (Unair)', 'Universitas Syiah Kuala (USK)', 'Universitas Udayana (Unud)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Farmasi',
                'faculty' => 'Fakultas Farmasi',
                'description' => 'Mempelajari ilmu obat-obatan, formulasi sediaan farmasi, dan pelayanan kefarmasian untuk mendukung sistem kesehatan nasional.',
                'career_paths' => ['Apoteker', 'Quality Control Farmasi', 'Medical Representative', 'Peneliti Obat', 'Manajer Apotek'],
                'learning_path' => ['Tahun 1' => 'Kimia dasar, biologi, matematika farmasi', 'Tahun 2' => 'Kimia organik, farmakologi, botani farmasi', 'Tahun 3' => 'Teknologi sediaan, kimia analitik, farmakokinetika', 'Tahun 4' => 'Skripsi dan praktik kerja profesi apoteker'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'Universitas Padjadjaran (Unpad)', 'Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Sanata Dharma'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Gizi',
                'faculty' => 'Fakultas Kesehatan Masyarakat',
                'description' => 'Mempelajari ilmu gizi, metabolisme, kebutuhan nutrisi manusia, dan peran gizi dalam pencegahan penyakit dan peningkatan kualitas hidup.',
                'career_paths' => ['Ahli Gizi', 'Dietisien', 'Konsultan Nutrisi', 'Peneliti Gizi', 'Analis Pangan Kemenkes'],
                'learning_path' => ['Tahun 1' => 'Biokimia gizi, anatomi fisiologi, kimia pangan', 'Tahun 2' => 'Gizi klinik, penilaian status gizi, epidemiologi gizi', 'Tahun 3' => 'Gizi masyarakat, teknologi pangan, konseling gizi', 'Tahun 4' => 'Skripsi dan praktik kerja gizi klinik'],
                'universities' => ['IPB University', 'Universitas Indonesia (UI)', 'Universitas Diponegoro (Undip)', 'Universitas Gadjah Mada (UGM)', 'Poltekkes Kemenkes Bandung'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kesehatan Masyarakat',
                'faculty' => 'Fakultas Kesehatan Masyarakat',
                'description' => 'Mempelajari upaya pencegahan penyakit, promosi kesehatan, epidemiologi, dan kebijakan kesehatan untuk meningkatkan derajat kesehatan komunitas.',
                'career_paths' => ['Epidemiolog', 'Ahli Kesehatan Masyarakat', 'Analis Kebijakan Kesehatan', 'Health Promotor', 'Peneliti Kesehatan'],
                'learning_path' => ['Tahun 1' => 'Pengantar kesehatan masyarakat, biologi, statistik', 'Tahun 2' => 'Epidemiologi, biostatistik, kesehatan lingkungan', 'Tahun 3' => 'Promosi kesehatan, administrasi kesehatan, MKIA', 'Tahun 4' => 'Skripsi dan magang di Dinas Kesehatan atau WHO'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Diponegoro (Undip)', 'Universitas Airlangga (Unair)', 'Universitas Hasanuddin (Unhas)', 'UIN Syarif Hidayatullah'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 8, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kebidanan',
                'faculty' => 'Fakultas Kesehatan',
                'description' => 'Mempelajari ilmu kebidanan, pelayanan kesehatan ibu dan anak, asuhan persalinan normal, dan kesehatan reproduksi perempuan.',
                'career_paths' => ['Bidan', 'Bidan Koordinator', 'Peneliti Kesehatan Ibu', 'Dosen Kebidanan', 'Konsultan Laktasi'],
                'learning_path' => ['Tahun 1' => 'Anatomi fisiologi reproduksi, biologi, kimia dasar', 'Tahun 2' => 'Asuhan kebidanan kehamilan, persalinan, nifas', 'Tahun 3' => 'Kebidanan patologi, neonatus, kesehatan reproduksi', 'Tahun 4' => 'Skripsi dan praktik klinik kebidanan'],
                'universities' => ['Universitas Padjadjaran (Unpad)', 'Poltekkes Kemenkes Bandung', 'Universitas Airlangga (Unair)', 'Poltekkes Kemenkes Jakarta'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Keperawatan',
                'faculty' => 'Fakultas Keperawatan',
                'description' => 'Mempelajari ilmu keperawatan, asuhan keperawatan pasien, praktik klinis, dan peran perawat dalam tim kesehatan profesional.',
                'career_paths' => ['Perawat', 'Ners Spesialis', 'Manajer Keperawatan', 'Dosen Keperawatan', 'Perawat Internasional'],
                'learning_path' => ['Tahun 1' => 'Anatomi fisiologi, ilmu keperawatan dasar, biologi', 'Tahun 2' => 'Keperawatan medikal bedah, keperawatan anak, maternitas', 'Tahun 3' => 'Keperawatan jiwa, komunitas, gawat darurat', 'Tahun 4' => 'Skripsi dan praktik klinik di RS'],
                'universities' => ['Universitas Padjadjaran (Unpad)', 'Universitas Indonesia (UI)', 'Universitas Diponegoro (Undip)', 'Poltekkes Kemenkes Bandung', 'Universitas Airlangga (Unair)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Kesehatan',
                'faculty' => 'Fakultas Kesehatan',
                'description' => 'Mempelajari berbagai aspek ilmu kesehatan secara umum meliputi pencegahan penyakit, penanganan medis dasar, dan promosi kesehatan masyarakat.',
                'career_paths' => ['Tenaga Kesehatan', 'Health Educator', 'Peneliti Kesehatan', 'Administrator RS', 'Konsultan Kesehatan'],
                'learning_path' => ['Tahun 1' => 'Biologi dasar, kimia, pengantar ilmu kesehatan', 'Tahun 2' => 'Patofisiologi, farmakologi dasar, gizi kesehatan', 'Tahun 3' => 'Pelayanan kesehatan primer, promosi kesehatan', 'Tahun 4' => 'Skripsi dan magang di fasilitas kesehatan'],
                'universities' => ['Poltekkes Kemenkes Bandung', 'Universitas Indonesia (UI)', 'Universitas Airlangga (Unair)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Informasi',
                'faculty' => 'Fakultas Ilmu Komputer',
                'description' => 'Mempelajari pengelolaan, organisasi, dan penyebaran informasi dalam berbagai format, termasuk perpustakaan digital, arsip, dan sistem informasi.',
                'career_paths' => ['Pustakawan Digital', 'Manajer Rekaman', 'Analis Informasi', 'Arsivis', 'Konsultan Sistem Informasi'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu informasi, matematika, logika informasi', 'Tahun 2' => 'Manajemen koleksi, metadata, database informasi', 'Tahun 3' => 'Sistem temu kembali informasi, preservasi digital', 'Tahun 4' => 'Skripsi dan magang di perpustakaan atau arsip'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Padjadjaran (Unpad)', 'UIN Syarif Hidayatullah'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 2, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Hukum',
                'faculty' => 'Fakultas Hukum',
                'description' => 'Mempelajari ilmu hukum, perundang-undangan, dan praktik hukum dalam kehidupan bermasyarakat, berbangsa, dan bernegara.',
                'career_paths' => ['Pengacara', 'Notaris', 'Hakim', 'Jaksa', 'Legal Officer'],
                'learning_path' => ['Tahun 1' => 'Pengantar ilmu hukum, hukum perdata, hukum pidana', 'Tahun 2' => 'Hukum tata negara, hukum internasional, hukum bisnis', 'Tahun 3' => 'Hukum acara, klinik hukum, penelitian hukum', 'Tahun 4' => 'Skripsi dan magang di lembaga hukum'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Diponegoro (Undip)', 'Universitas Airlangga (Unair)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 12, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Militer',
                'faculty' => 'Akademi Militer / Sekolah Tinggi Pertahanan',
                'description' => 'Mempelajari strategi militer, kepemimpinan, taktik, dan doktrin pertempuran sebagai ilmu yang mendukung pertahanan dan keamanan negara.',
                'career_paths' => ['Perwira TNI', 'Analis Intelijen', 'Konsultan Keamanan', 'Peneliti Pertahanan', 'Diplomat Militer'],
                'learning_path' => ['Tahun 1' => 'Pendidikan militer dasar, sosiologi, geopolitik', 'Tahun 2' => 'Strategi militer, kepemimpinan, hukum perang', 'Tahun 3' => 'Taktik dan operasi, manajemen krisis', 'Tahun 4' => 'Skripsi dan penugasan lapangan'],
                'universities' => ['Akademi Militer (Akmil Magelang)', 'Universitas Pertahanan (Unhan)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 12, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Administrasi Publik',
                'faculty' => 'Fakultas Ilmu Administrasi',
                'description' => 'Mempelajari pengelolaan organisasi pemerintahan, kebijakan publik, pelayanan publik, dan administrasi negara untuk meningkatkan kinerja pemerintahan.',
                'career_paths' => ['PNS / ASN', 'Analis Kebijakan', 'Manajer Pelayanan Publik', 'Konsultan Pemerintahan', 'Peneliti Kebijakan'],
                'learning_path' => ['Tahun 1' => 'Pengantar administrasi publik, ilmu politik, sosiologi', 'Tahun 2' => 'Kebijakan publik, hukum administrasi negara, organisasi publik', 'Tahun 3' => 'Manajemen keuangan publik, e-Government, tata kelola', 'Tahun 4' => 'Skripsi dan magang di instansi pemerintah'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)', 'Universitas Brawijaya (UB)', 'Universitas Diponegoro (Undip)'],
                'criteria' => ['primary_subject_id' => 8, 'primary_weight' => 0.50, 'secondary_subject_id' => 12, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Ilmu Keolahragaan',
                'faculty' => 'Fakultas Ilmu Keolahragaan',
                'description' => 'Mempelajari ilmu olahraga, fisiologi latihan, manajemen olahraga, dan peran olahraga dalam meningkatkan kesehatan dan prestasi manusia.',
                'career_paths' => ['Pelatih Olahraga', 'Ahli Fisiologi Olahraga', 'Manajer Olahraga', 'Fisioterapis', 'Analis Performa Atlet'],
                'learning_path' => ['Tahun 1' => 'Anatomi fisiologi, PJOK dasar, biomekanika', 'Tahun 2' => 'Fisiologi latihan, psikologi olahraga, gizi olahraga', 'Tahun 3' => 'Manajemen olahraga, pelatihan atletik, sport science', 'Tahun 4' => 'Skripsi dan magang di klub olahraga atau dinas olahraga'],
                'universities' => ['UPI Bandung', 'UNY Yogyakarta', 'UNJ Jakarta', 'Universitas Negeri Semarang (UNNES)'],
                'criteria' => ['primary_subject_id' => 13, 'primary_weight' => 0.50, 'secondary_subject_id' => 6, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Pariwisata',
                'faculty' => 'Fakultas Pariwisata',
                'description' => 'Mempelajari industri pariwisata, perhotelan, pemasaran destinasi wisata, dan pengelolaan sumber daya pariwisata secara berkelanjutan.',
                'career_paths' => ['Tour Guide', 'Hotel Manager', 'Event Organizer', 'Travel Agent', 'Manajer Destinasi Wisata'],
                'learning_path' => ['Tahun 1' => 'Pengantar pariwisata, bahasa Inggris, ekonomi pariwisata', 'Tahun 2' => 'Manajemen perhotelan, pemasaran pariwisata, MICE', 'Tahun 3' => 'Ekowisata, pariwisata digital, manajemen destinasi', 'Tahun 4' => 'Skripsi dan magang di hotel atau agen perjalanan'],
                'universities' => ['Politeknik Pariwisata NHI Bandung', 'Universitas Gadjah Mada (UGM)', 'Universitas Udayana (Unud)', 'STP Trisakti'],
                'criteria' => ['primary_subject_id' => 7, 'primary_weight' => 0.50, 'secondary_subject_id' => 3, 'secondary_weight' => 0.20, 'interest_category_id' => 2, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Transportasi',
                'faculty' => 'Fakultas Teknik',
                'description' => 'Mempelajari perencanaan, desain, dan manajemen sistem transportasi darat, laut, dan udara untuk mendukung mobilitas dan logistik nasional.',
                'career_paths' => ['Perencana Transportasi', 'Insinyur Lalu Lintas', 'Analis Kemenhub', 'Konsultan Transportasi', 'Manajer Logistik'],
                'learning_path' => ['Tahun 1' => 'Matematika teknik, fisika, pengantar transportasi', 'Tahun 2' => 'Rekayasa jalan, manajemen lalu lintas, GIS transportasi', 'Tahun 3' => 'Perencanaan transportasi, angkutan umum, keselamatan jalan', 'Tahun 4' => 'Skripsi dan magang di Dinas Perhubungan atau konsultan'],
                'universities' => ['Politeknik Transportasi Darat Indonesia (STTD)', 'Institut Transportasi dan Logistik Trisakti', 'Institut Teknologi Bandung (ITB)'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Bioteknologi',
                'faculty' => 'Fakultas Bioteknologi',
                'description' => 'Mempelajari pemanfaatan organisme hidup dan teknologi untuk mengembangkan produk dan proses baru di bidang kesehatan, pertanian, dan industri.',
                'career_paths' => ['Bioteknolog', 'Peneliti Bioteknologi', 'Analis BPOM', 'Pengembang Produk Bio', 'Konsultan Bioinformatika'],
                'learning_path' => ['Tahun 1' => 'Biologi sel dan molekuler, kimia organik, matematika', 'Tahun 2' => 'Genetika molekuler, biokimia, mikrobiologi industri', 'Tahun 3' => 'Rekayasa genetika, bioproses, bioinformatika', 'Tahun 4' => 'Skripsi dan penelitian laboratorium bioteknologi'],
                'universities' => ['IPB University', 'Institut Teknologi Bandung (ITB)', 'Universitas Brawijaya (UB)', 'Universitas Katolik Indonesia Atma Jaya'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Geografi',
                'faculty' => 'Fakultas Matematika dan Ilmu Pengetahuan Alam',
                'description' => 'Mempelajari permukaan bumi, distribusi fenomena alam dan manusia, serta hubungan antara lingkungan dan aktivitas manusia secara spasial.',
                'career_paths' => ['Ahli Geografi', 'Analis GIS', 'Perencana Wilayah', 'Peneliti Lingkungan', 'Konsultan Peta'],
                'learning_path' => ['Tahun 1' => 'Geografi fisik, kartografi, geomorfologi', 'Tahun 2' => 'SIG (GIS), penginderaan jauh, geografi manusia', 'Tahun 3' => 'Geografi lingkungan, perencanaan wilayah, klimatologi', 'Tahun 4' => 'Skripsi dan magang di BIG atau lembaga tata ruang'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'UPI Bandung', 'UNJ Jakarta'],
                'criteria' => ['primary_subject_id' => 9, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Informatika Medis',
                'faculty' => 'Fakultas Ilmu Komputer',
                'description' => 'Menggabungkan ilmu komputer dan informatika dengan ilmu kesehatan untuk mengembangkan sistem informasi kesehatan, rekam medis elektronik, dan analitik data klinis.',
                'career_paths' => ['Health Informaticist', 'Pengembang Sistem RS', 'Analis Data Kesehatan', 'Konsultan IT Kesehatan', 'Peneliti eHealth'],
                'learning_path' => ['Tahun 1' => 'Biologi dasar, matematika, pengantar informatika', 'Tahun 2' => 'Basis data, pemrograman, terminologi medis', 'Tahun 3' => 'Sistem informasi kesehatan, rekam medis, telemedicine', 'Tahun 4' => 'Skripsi dan magang di RS atau startup healthtech'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Poltekkes Kemenkes'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 1, 'secondary_weight' => 0.20, 'interest_category_id' => 4, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Konservasi Biologi',
                'faculty' => 'Fakultas Biologi',
                'description' => 'Mempelajari strategi pelestarian keanekaragaman hayati, pengelolaan habitat, dan perlindungan spesies yang terancam punah dari berbagai tekanan lingkungan.',
                'career_paths' => ['Ahli Konservasi', 'Wildlife Biologist', 'Peneliti KLHK', 'Manajer Taman Nasional', 'Konsultan Konservasi'],
                'learning_path' => ['Tahun 1' => 'Biologi umum, ekologi, kimia lingkungan', 'Tahun 2' => 'Keanekaragaman hayati, genetika konservasi, ekologi lanskap', 'Tahun 3' => 'Pengelolaan satwa liar, kebijakan konservasi', 'Tahun 4' => 'Skripsi dan penelitian lapangan di taman nasional'],
                'universities' => ['IPB University', 'Universitas Gadjah Mada (UGM)', 'Universitas Indonesia (UI)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Teknologi Pangan',
                'faculty' => 'Fakultas Teknologi Pertanian',
                'description' => 'Mempelajari prinsip ilmiah dan teknologi dalam pengolahan, pengawetan, dan pengembangan produk pangan yang aman, bergizi, dan berkualitas.',
                'career_paths' => ['Teknolog Pangan', 'Quality Assurance', 'Peneliti R&D Pangan', 'Konsultan BPOM', 'Food Scientist'],
                'learning_path' => ['Tahun 1' => 'Kimia pangan, biologi, matematika, fisika dasar', 'Tahun 2' => 'Mikrobiologi pangan, biokimia pangan, teknologi pengolahan', 'Tahun 3' => 'Keamanan pangan, analisis pangan, pengembangan produk', 'Tahun 4' => 'Skripsi dan magang di industri pangan atau BPOM'],
                'universities' => ['IPB University', 'Universitas Padjadjaran (Unpad)', 'Institut Teknologi Bandung (ITB)', 'Universitas Brawijaya (UB)', 'Universitas Gadjah Mada (UGM)'],
                'criteria' => ['primary_subject_id' => 5, 'primary_weight' => 0.50, 'secondary_subject_id' => 6, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Sains Data',
                'faculty' => 'Fakultas Ilmu Komputer',
                'description' => 'Mempelajari metode analisis data besar, statistika, pembelajaran mesin, dan visualisasi data untuk menghasilkan wawasan yang bermanfaat bagi pengambilan keputusan.',
                'career_paths' => ['Data Scientist', 'Machine Learning Engineer', 'Data Analyst', 'AI Researcher', 'Business Intelligence Analyst'],
                'learning_path' => ['Tahun 1' => 'Matematika, statistika dasar, pemrograman Python/R', 'Tahun 2' => 'Basis data, machine learning, probabilitas', 'Tahun 3' => 'Deep learning, big data, visualisasi data', 'Tahun 4' => 'Skripsi, proyek data science, magang di perusahaan teknologi'],
                'universities' => ['Institut Teknologi Bandung (ITB)', 'IPB University', 'Universitas Airlangga (Unair)', 'Telkom University', 'Universitas Brawijaya (UB)'],
                'criteria' => ['primary_subject_id' => 1, 'primary_weight' => 0.50, 'secondary_subject_id' => 4, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Sains Perkopian',
                'faculty' => 'Fakultas Pertanian',
                'description' => 'Mempelajari ilmu dan teknologi kopi secara menyeluruh, mulai dari agronomi tanaman kopi, pasca panen, pengolahan, hingga cita rasa dan industri kopi.',
                'career_paths' => ['Q Grader', 'Barista Profesional', 'Pengusaha Kopi', 'Peneliti Kopi', 'Konsultan Perkebunan Kopi'],
                'learning_path' => ['Tahun 1' => 'Biologi tanaman kopi, kimia kopi, agronomi dasar', 'Tahun 2' => 'Pasca panen kopi, fermentasi, teknologi pengolahan', 'Tahun 3' => 'Sensori kopi, pemasaran kopi, manajemen bisnis kopi', 'Tahun 4' => 'Skripsi dan magang di perkebunan atau industri kopi'],
                'universities' => ['IPB University', 'Politeknik Negeri Jember (Polije)', 'Universitas Padjadjaran (Unpad)'],
                'criteria' => ['primary_subject_id' => 6, 'primary_weight' => 0.50, 'secondary_subject_id' => 5, 'secondary_weight' => 0.20, 'interest_category_id' => 1, 'interest_weight' => 0.30]
            ],
            [
                'name' => 'Studi Humanitas',
                'faculty' => 'Fakultas Ilmu Budaya',
                'description' => 'Mempelajari aspek-aspek kemanusiaan secara interdisipliner meliputi budaya, bahasa, sejarah, filsafat, dan seni sebagai ekspresi peradaban manusia.',
                'career_paths' => ['Peneliti Kemanusiaan', 'Penulis', 'Akademisi', 'Konsultan Budaya', 'Diplomat Budaya'],
                'learning_path' => ['Tahun 1' => 'Pengantar humanitas, antropologi, sosiologi budaya', 'Tahun 2' => 'Kajian teks, studi gender, filsafat kemanusiaan', 'Tahun 3' => 'Kajian postkolonial, media dan kebudayaan', 'Tahun 4' => 'Skripsi dan penelitian interdisipliner humanitas'],
                'universities' => ['Universitas Indonesia (UI)', 'Universitas Gadjah Mada (UGM)', 'Universitas Padjadjaran (Unpad)'],
                'criteria' => ['primary_subject_id' => 14, 'primary_weight' => 0.50, 'secondary_subject_id' => 8, 'secondary_weight' => 0.20, 'interest_category_id' => 3, 'interest_weight' => 0.30]
            ],
        ];

        // Hapus program studi yang tidak ada di daftar 59 Kepmen
        $validNames = array_column($programs, 'name');
        $extraPrograms = StudyProgram::whereNotIn('name', $validNames)->get();
        foreach ($extraPrograms as $extra) {
            RecommendationResult::where('program_id', $extra->id)->delete();
            ProgramCriteria::where('program_id', $extra->id)->delete();
            $extra->delete();
        }

        $count = 0;
        foreach ($programs as $programData) {
            $criteria = $programData['criteria'];
            unset($programData['criteria']);
            $program = StudyProgram::updateOrCreate(['name' => $programData['name']], $programData);
            $program->criteria()->updateOrCreate(['program_id' => $program->id], $criteria);
            $count++;
        }

        $this->info("Berhasil menyinkronkan tepat {$count} kelompok program studi resmi Kepmendikbud beserta data rujukan kampus PTN/PTS.");
    }
}

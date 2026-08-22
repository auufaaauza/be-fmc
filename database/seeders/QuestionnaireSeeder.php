<?php

namespace Database\Seeders;

use App\Models\QuestionnaireQuestion;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        // 6 Kategori RIASEC × 6 Pertanyaan = 36 Pertanyaan Total
        // category_id: 1=Realistic, 2=Investigative, 3=Artistic, 4=Social, 5=Enterprising, 6=Conventional

        $questions = [
            // ── R: Realistic (Praktis/Teknis) ────────────────────────────────────
            ['category_id' => 1, 'question' => 'Saya senang bekerja dengan tangan, misalnya memperbaiki barang, merakit sesuatu, atau berkebun.', 'order_num' => 1],
            ['category_id' => 1, 'question' => 'Saya tertarik mempelajari cara kerja mesin, elektronik, atau peralatan teknis.', 'order_num' => 2],
            ['category_id' => 1, 'question' => 'Saya lebih suka pekerjaan nyata dan fisik daripada pekerjaan di belakang meja.', 'order_num' => 3],
            ['category_id' => 1, 'question' => 'Saya menikmati kegiatan olahraga, pertukangan, atau aktivitas di luar ruangan.', 'order_num' => 4],
            ['category_id' => 1, 'question' => 'Saya ingin berkarir di bidang teknik, pertanian, mekanik, atau konstruksi.', 'order_num' => 5],
            ['category_id' => 1, 'question' => 'Saya lebih mudah memahami sesuatu dengan langsung mempraktikkannya.', 'order_num' => 6],

            // ── I: Investigative (Ilmiah/Analitis) ───────────────────────────────
            ['category_id' => 2, 'question' => 'Saya senang menganalisis data, fakta, atau informasi untuk menemukan pola atau solusi.', 'order_num' => 7],
            ['category_id' => 2, 'question' => 'Saya tertarik pada ilmu pengetahuan seperti biologi, kimia, fisika, atau matematika.', 'order_num' => 8],
            ['category_id' => 2, 'question' => 'Saya suka melakukan riset, eksperimen, atau menyelidiki sesuatu secara mendalam.', 'order_num' => 9],
            ['category_id' => 2, 'question' => 'Saya lebih suka bekerja sendiri dan berpikir secara logis daripada bersosialisasi.', 'order_num' => 10],
            ['category_id' => 2, 'question' => 'Saya ingin berkarir di bidang sains, penelitian, teknologi, atau kedokteran.', 'order_num' => 11],
            ['category_id' => 2, 'question' => 'Saya sering penasaran dengan fenomena alam atau ilmiah dan ingin memahami cara kerjanya.', 'order_num' => 12],

            // ── A: Artistic (Kreatif/Estetis) ────────────────────────────────────
            ['category_id' => 3, 'question' => 'Saya menyukai kegiatan kreatif seperti menggambar, menulis cerita, atau membuat musik.', 'order_num' => 13],
            ['category_id' => 3, 'question' => 'Saya tertarik dengan dunia seni, desain, fotografi, atau perfilman.', 'order_num' => 14],
            ['category_id' => 3, 'question' => 'Saya lebih suka mengekspresikan diri secara bebas daripada mengikuti aturan yang kaku.', 'order_num' => 15],
            ['category_id' => 3, 'question' => 'Saya senang tampil di depan umum, misalnya dalam drama, pertunjukan, atau presentasi kreatif.', 'order_num' => 16],
            ['category_id' => 3, 'question' => 'Saya ingin berkarir di bidang seni, desain grafis, jurnalisme, atau industri kreatif.', 'order_num' => 17],
            ['category_id' => 3, 'question' => 'Saya peka terhadap keindahan, estetika, dan hal-hal yang bersifat artistik di sekitar saya.', 'order_num' => 18],

            // ── S: Social (Sosial/Membantu) ──────────────────────────────────────
            ['category_id' => 4, 'question' => 'Saya senang membantu orang lain yang sedang kesulitan atau membutuhkan dukungan.', 'order_num' => 19],
            ['category_id' => 4, 'question' => 'Saya menikmati kegiatan yang melibatkan kerja sama tim dan interaksi sosial.', 'order_num' => 20],
            ['category_id' => 4, 'question' => 'Saya tertarik menjadi guru, konselor, pekerja sosial, atau tenaga kesehatan.', 'order_num' => 21],
            ['category_id' => 4, 'question' => 'Saya peduli terhadap isu-isu kemanusiaan, sosial, dan kesejahteraan masyarakat.', 'order_num' => 22],
            ['category_id' => 4, 'question' => 'Saya mudah berempati dan memahami perasaan serta kebutuhan orang lain.', 'order_num' => 23],
            ['category_id' => 4, 'question' => 'Saya merasa puas ketika berhasil mengajarkan atau membimbing orang lain.', 'order_num' => 24],

            // ── E: Enterprising (Kepemimpinan/Wirausaha) ─────────────────────────
            ['category_id' => 5, 'question' => 'Saya senang memimpin kelompok atau mengorganisir kegiatan bersama teman-teman.', 'order_num' => 25],
            ['category_id' => 5, 'question' => 'Saya tertarik dengan dunia bisnis, pemasaran, atau kewirausahaan.', 'order_num' => 26],
            ['category_id' => 5, 'question' => 'Saya percaya diri dalam membujuk, bernegosiasi, atau mempresentasikan ide kepada orang lain.', 'order_num' => 27],
            ['category_id' => 5, 'question' => 'Saya memiliki ambisi untuk sukses, memimpin, dan menjadi penggerak perubahan.', 'order_num' => 28],
            ['category_id' => 5, 'question' => 'Saya ingin berkarir di bidang manajemen, hukum, politik, atau kewirausahaan.', 'order_num' => 29],
            ['category_id' => 5, 'question' => 'Saya berani mengambil risiko dan mencoba hal-hal baru demi mencapai tujuan.', 'order_num' => 30],

            // ── C: Conventional (Teratur/Administratif) ──────────────────────────
            ['category_id' => 6, 'question' => 'Saya senang bekerja dengan data, angka, spreadsheet, atau laporan yang rapi dan terstruktur.', 'order_num' => 31],
            ['category_id' => 6, 'question' => 'Saya lebih suka mengikuti prosedur dan aturan yang jelas dalam menyelesaikan pekerjaan.', 'order_num' => 32],
            ['category_id' => 6, 'question' => 'Saya teliti, terorganisir, dan menyukai keteraturan dalam kehidupan sehari-hari.', 'order_num' => 33],
            ['category_id' => 6, 'question' => 'Saya tertarik dengan pekerjaan di bidang akuntansi, administrasi, perbankan, atau keuangan.', 'order_num' => 34],
            ['category_id' => 6, 'question' => 'Saya ingin berkarir di instansi pemerintah, BUMN, atau perusahaan besar dengan struktur yang jelas.', 'order_num' => 35],
            ['category_id' => 6, 'question' => 'Saya merasa nyaman dengan rutinitas dan lebih menyukai stabilitas dibanding ketidakpastian.', 'order_num' => 36],
        ];

        // Hapus pertanyaan lama jika ada lebih dari 36
        QuestionnaireQuestion::where('order_num', '>', 36)->delete();

        foreach ($questions as $question) {
            QuestionnaireQuestion::updateOrCreate(
                ['order_num' => $question['order_num']],
                $question
            );
        }
    }
}

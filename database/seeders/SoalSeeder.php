<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Soal untuk Ujian 1 - Mixed type (3 pilgan + 2 essay)
        $soalData = [
            // Multiple Choice Questions for Ujian 1
            [
                'id' => 1,
                'ujian_id' => 1,
                'pertanyaan' => 'Kapan Indonesia memproklamasikan kemerdekaannya?',
                'jawaban_benar' => 'A',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Indonesia memproklamasikan kemerdekaannya pada tanggal 17 Agustus 1945.'
            ],
            [
                'id' => 2,
                'ujian_id' => 1,
                'pertanyaan' => 'Siapa yang membacakan teks Proklamasi Kemerdekaan Indonesia?',
                'jawaban_benar' => 'B',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Ir. Soekarno yang membacakan teks Proklamasi Kemerdekaan Indonesia pada 17 Agustus 1945.'
            ],
            [
                'id' => 3,
                'ujian_id' => 1,
                'pertanyaan' => 'Peristiwa apa yang terjadi pada tanggal 10 November 1945?',
                'jawaban_benar' => 'C',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Pertempuran Surabaya yang menjadi simbol perlawanan rakyat Indonesia terhadap Sekutu.'
            ],
            
            // Essay Questions for Ujian 1
            [
                'id' => 4,
                'ujian_id' => 1,
                'pertanyaan' => 'Jelaskan secara detail faktor-faktor yang mendorong bangsa Indonesia untuk memproklamasikan kemerdekaan pada tahun 1945!',
                'jawaban_benar' => 'Faktor-faktor yang mendorong proklamasi kemerdekaan Indonesia antara lain: 1) Kekalahan Jepang dalam Perang Dunia II yang menciptakan kekosongan kekuasaan, 2) Tekanan dari golongan muda yang mendesak untuk segera memproklamasikan kemerdekaan, 3) Situasi dunia yang mendukung dekolonisasi, 4) Kematangan persiapan bangsa Indonesia dalam bidang politik dan organisasi, 5) Adanya kesempatan emas (momentum) yang tidak boleh dilewatkan.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Proklamasi kemerdekaan terjadi karena berbagai faktor internal dan eksternal yang saling mendukung.'
            ],
            [
                'id' => 5,
                'ujian_id' => 1,
                'pertanyaan' => 'Analisis mengapa Pertempuran Surabaya pada 10 November 1945 menjadi sangat penting dalam sejarah perjuangan kemerdekaan Indonesia!',
                'jawaban_benar' => 'Pertempuran Surabaya sangat penting karena: 1) Menunjukkan tekad dan semangat juang rakyat Indonesia yang tidak gentar menghadapi kekuatan militer Sekutu yang lebih modern, 2) Menjadi simbol perlawanan heroik yang menginspirasi perjuangan di daerah lain, 3) Membuktikan kepada dunia internasional bahwa Indonesia benar-benar merdeka dan siap mempertahankan kemerdekaannya, 4) Menumbuhkan rasa persatuan dan kesatuan di antara rakyat Indonesia, 5) Menjadi tonggak sejarah yang diabadikan sebagai Hari Pahlawan.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Pertempuran Surabaya menjadi simbol heroisme dan perlawanan rakyat Indonesia.'
            ],

            // Soal untuk Ujian 2 - Essay only
            [
                'id' => 6,
                'ujian_id' => 2,
                'pertanyaan' => 'Jelaskan pengertian dan ciri-ciri puisi modern dalam sastra Indonesia!',
                'jawaban_benar' => 'Puisi modern adalah karya sastra yang tidak terikat oleh aturan-aturan tradisional seperti pantun atau syair. Ciri-cirinya antara lain: 1) Bebas dalam penggunaan rima dan irama, 2) Menggunakan bahasa sehari-hari yang lebih mudah dipahami, 3) Tema yang beragam dan aktual, 4) Penggunaan majas dan simbol yang kaya, 5) Bentuk yang tidak terikat pola tertentu.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Puisi modern memberikan kebebasan berekspresi kepada penyair.'
            ],
            [
                'id' => 7,
                'ujian_id' => 2,
                'pertanyaan' => 'Analisis penggunaan konjungsi dalam kalimat majemuk dan berikan contohnya!',
                'jawaban_benar' => 'Konjungsi dalam kalimat majemuk berfungsi menghubungkan dua klausa atau lebih. Jenisnya: 1) Konjungsi koordinatif (dan, atau, tetapi) yang menghubungkan klausa setara, 2) Konjungsi subordinatif (karena, jika, supaya) yang menghubungkan klausa utama dengan klausa bawahan. Contoh: "Dia belajar dengan giat karena ingin lulus ujian" (subordinatif), "Ani membaca buku dan adiknya bermain game" (koordinatif).',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Konjungsi penting untuk membuat kalimat yang efektif dan padu.'
            ],
            [
                'id' => 8,
                'ujian_id' => 2,
                'pertanyaan' => 'Bagaimana cara mengidentifikasi ide pokok dalam sebuah paragraf?',
                'jawaban_benar' => 'Cara mengidentifikasi ide pokok: 1) Baca keseluruhan paragraf dengan cermat, 2) Cari kalimat utama yang biasanya berada di awal, tengah, atau akhir paragraf, 3) Identifikasi kalimat yang mengandung gagasan umum yang didukung kalimat lain, 4) Perhatikan kata kunci atau kata yang sering diulang, 5) Pastikan kalimat tersebut dapat berdiri sendiri dan kalimat lain hanya menjelaskan atau mendukungnya.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Ide pokok adalah inti dari sebuah paragraf yang didukung oleh kalimat penjelas.'
            ],

            // Soal untuk Ujian 3 - Multiple Choice only (PPKN)
            [
                'id' => 9,
                'ujian_id' => 3,
                'pertanyaan' => 'Pancasila sebagai dasar negara Indonesia terdiri dari berapa sila?',
                'jawaban_benar' => 'A',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Pancasila terdiri dari 5 sila yang menjadi dasar ideologi negara Indonesia.'
            ],
            [
                'id' => 10,
                'ujian_id' => 3,
                'pertanyaan' => 'Apa arti dari sila pertama Pancasila "Ketuhanan Yang Maha Esa"?',
                'jawaban_benar' => 'B',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Sila pertama menekankan pengakuan terhadap Tuhan Yang Maha Esa dan kebebasan beragama.'
            ],
            [
                'id' => 11,
                'ujian_id' => 3,
                'pertanyaan' => 'UUD 1945 disahkan pada tanggal?',
                'jawaban_benar' => 'C',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'UUD 1945 disahkan pada tanggal 18 Agustus 1945 oleh PPKI.'
            ],
            [
                'id' => 12,
                'ujian_id' => 3,
                'pertanyaan' => 'Siapa yang berhak mengubah UUD 1945?',
                'jawaban_benar' => 'D',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'MPR (Majelis Permusyawaratan Rakyat) yang berhak mengubah UUD 1945.'
            ],

            // Soal untuk Ujian 4 - Completed exam (Essay)
            [
                'id' => 13,
                'ujian_id' => 4,
                'pertanyaan' => 'Jelaskan sistem tanam paksa yang diterapkan oleh pemerintah kolonial Belanda di Indonesia!',
                'jawaban_benar' => 'Sistem tanam paksa (cultuurstelsel) adalah kebijakan yang diterapkan Gubernur Jenderal Van den Bosch pada tahun 1830. Sistem ini mewajibkan petani pribumi menanam tanaman ekspor seperti kopi, teh, tembakau, dan nila di seperlima tanah mereka atau bekerja selama 66 hari dalam setahun di perkebunan pemerintah. Hasil panen harus diserahkan kepada pemerintah kolonial.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Tanam paksa memberikan keuntungan besar bagi Belanda namun menyengsarakan rakyat Indonesia.'
            ],
            [
                'id' => 14,
                'ujian_id' => 4,
                'pertanyaan' => 'Apa dampak negatif sistem tanam paksa bagi rakyat Indonesia?',
                'jawaban_benar' => 'Dampak negatif sistem tanam paksa: 1) Kemiskinan dan kelaparan karena petani tidak bisa menanam padi untuk kebutuhan sendiri, 2) Eksploitasi tenaga kerja yang berlebihan, 3) Penderitaan fisik dan mental rakyat, 4) Menurunnya produksi pangan lokal, 5) Terjadi bencana kelaparan di berbagai daerah, 6) Hancurnya sistem pertanian tradisional.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Tanam paksa menyebabkan penderitaan luar biasa bagi rakyat Indonesia.'
            ],

            // Soal untuk Ujian 5 - Mixed type (ongoing exam)
            [
                'id' => 15,
                'ujian_id' => 5,
                'pertanyaan' => 'Apa yang dimaksud dengan kata baku?',
                'jawaban_benar' => 'A',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Kata baku adalah kata yang sesuai dengan kaidah bahasa Indonesia yang benar.'
            ],
            [
                'id' => 16,
                'ujian_id' => 5,
                'pertanyaan' => 'Manakah penulisan kata yang benar?',
                'jawaban_benar' => 'B',
                'tipe_soal' => 'pilgan',
                'pembahasan' => 'Penulisan yang benar harus sesuai dengan PUEBI (Pedoman Umum Ejaan Bahasa Indonesia).'
            ],
            [
                'id' => 17,
                'ujian_id' => 5,
                'pertanyaan' => 'Jelaskan perbedaan antara kalimat aktif dan kalimat pasif disertai dengan contohnya!',
                'jawaban_benar' => 'Kalimat aktif adalah kalimat yang subjeknya melakukan pekerjaan, contoh: "Ani membaca buku". Kalimat pasif adalah kalimat yang subjeknya dikenai pekerjaan, contoh: "Buku dibaca oleh Ani". Ciri kalimat pasif menggunakan awalan di-, ter-, atau ke-an.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Perbedaan aktif dan pasif terletak pada posisi subjek sebagai pelaku atau yang dikenai tindakan.'
            ],
            [
                'id' => 18,
                'ujian_id' => 5,
                'pertanyaan' => 'Buatlah paragraf deskripsi tentang sekolah Anda dengan minimal 5 kalimat!',
                'jawaban_benar' => 'Contoh: Sekolah saya memiliki halaman yang luas dengan banyak pohon rindang. Gedung sekolah terdiri dari tiga lantai dengan cat berwarna putih dan biru. Di halaman depan terdapat tiang bendera yang tinggi dan lapangan upacara yang luas. Ruang kelas dilengkapi dengan AC dan proyektor untuk mendukung pembelajaran. Lingkungan sekolah sangat bersih karena semua warga sekolah ikut menjaga kebersihan.',
                'tipe_soal' => 'essay',
                'pembahasan' => 'Paragraf deskripsi menggambarkan objek secara detail dan jelas.'
            ],
        ];

        DB::table('soal')->insert($soalData);

        // Insert options untuk soal pilihan ganda
        $this->insertOpsiJawaban();
    }

    private function insertOpsiJawaban()
    {
        $opsiData = [
            // Opsi untuk soal 1 (Ujian 1)
            ['soal_id' => 1, 'label' => 'A', 'isi_opsi' => '17 Agustus 1945', 'is_correct' => true],
            ['soal_id' => 1, 'label' => 'B', 'isi_opsi' => '17 Agustus 1946', 'is_correct' => false],
            ['soal_id' => 1, 'label' => 'C', 'isi_opsi' => '1 Juni 1945', 'is_correct' => false],
            ['soal_id' => 1, 'label' => 'D', 'isi_opsi' => '18 Agustus 1945', 'is_correct' => false],
            ['soal_id' => 1, 'label' => 'E', 'isi_opsi' => '20 Mei 1908', 'is_correct' => false],

            // Opsi untuk soal 2 (Ujian 1)
            ['soal_id' => 2, 'label' => 'A', 'isi_opsi' => 'Mohammad Hatta', 'is_correct' => false],
            ['soal_id' => 2, 'label' => 'B', 'isi_opsi' => 'Ir. Soekarno', 'is_correct' => true],
            ['soal_id' => 2, 'label' => 'C', 'isi_opsi' => 'Sutan Sjahrir', 'is_correct' => false],
            ['soal_id' => 2, 'label' => 'D', 'isi_opsi' => 'Ahmad Subardjo', 'is_correct' => false],
            ['soal_id' => 2, 'label' => 'E', 'isi_opsi' => 'Ki Hajar Dewantara', 'is_correct' => false],

            // Opsi untuk soal 3 (Ujian 1)
            ['soal_id' => 3, 'label' => 'A', 'isi_opsi' => 'Proklamasi Kemerdekaan', 'is_correct' => false],
            ['soal_id' => 3, 'label' => 'B', 'isi_opsi' => 'Peristiwa Rengasdengklok', 'is_correct' => false],
            ['soal_id' => 3, 'label' => 'C', 'isi_opsi' => 'Pertempuran Surabaya', 'is_correct' => true],
            ['soal_id' => 3, 'label' => 'D', 'isi_opsi' => 'Konferensi Meja Bundar', 'is_correct' => false],
            ['soal_id' => 3, 'label' => 'E', 'isi_opsi' => 'Peristiwa Bandung Lautan Api', 'is_correct' => false],

            // Opsi untuk soal 9 (Ujian 3)
            ['soal_id' => 9, 'label' => 'A', 'isi_opsi' => '5 sila', 'is_correct' => true],
            ['soal_id' => 9, 'label' => 'B', 'isi_opsi' => '4 sila', 'is_correct' => false],
            ['soal_id' => 9, 'label' => 'C', 'isi_opsi' => '6 sila', 'is_correct' => false],
            ['soal_id' => 9, 'label' => 'D', 'isi_opsi' => '3 sila', 'is_correct' => false],
            ['soal_id' => 9, 'label' => 'E', 'isi_opsi' => '7 sila', 'is_correct' => false],

            // Opsi untuk soal 10 (Ujian 3)
            ['soal_id' => 10, 'label' => 'A', 'isi_opsi' => 'Mengutamakan satu agama saja', 'is_correct' => false],
            ['soal_id' => 10, 'label' => 'B', 'isi_opsi' => 'Pengakuan terhadap Tuhan dan kebebasan beragama', 'is_correct' => true],
            ['soal_id' => 10, 'label' => 'C', 'isi_opsi' => 'Menolak keberagaman agama', 'is_correct' => false],
            ['soal_id' => 10, 'label' => 'D', 'isi_opsi' => 'Memisahkan agama dari negara', 'is_correct' => false],
            ['soal_id' => 10, 'label' => 'E', 'isi_opsi' => 'Memaksakan kepercayaan tertentu', 'is_correct' => false],

            // Opsi untuk soal 11 (Ujian 3)
            ['soal_id' => 11, 'label' => 'A', 'isi_opsi' => '17 Agustus 1945', 'is_correct' => false],
            ['soal_id' => 11, 'label' => 'B', 'isi_opsi' => '1 Juni 1945', 'is_correct' => false],
            ['soal_id' => 11, 'label' => 'C', 'isi_opsi' => '18 Agustus 1945', 'is_correct' => true],
            ['soal_id' => 11, 'label' => 'D', 'isi_opsi' => '22 Juni 1945', 'is_correct' => false],
            ['soal_id' => 11, 'label' => 'E', 'isi_opsi' => '29 Mei 1945', 'is_correct' => false],

            // Opsi untuk soal 12 (Ujian 3)
            ['soal_id' => 12, 'label' => 'A', 'isi_opsi' => 'Presiden', 'is_correct' => false],
            ['soal_id' => 12, 'label' => 'B', 'isi_opsi' => 'DPR', 'is_correct' => false],
            ['soal_id' => 12, 'label' => 'C', 'isi_opsi' => 'DPD', 'is_correct' => false],
            ['soal_id' => 12, 'label' => 'D', 'isi_opsi' => 'MPR', 'is_correct' => true],
            ['soal_id' => 12, 'label' => 'E', 'isi_opsi' => 'MA', 'is_correct' => false],

            // Opsi untuk soal 15 (Ujian 5)
            ['soal_id' => 15, 'label' => 'A', 'isi_opsi' => 'Kata yang sesuai kaidah bahasa Indonesia', 'is_correct' => true],
            ['soal_id' => 15, 'label' => 'B', 'isi_opsi' => 'Kata yang populer digunakan', 'is_correct' => false],
            ['soal_id' => 15, 'label' => 'C', 'isi_opsi' => 'Kata yang mudah diucapkan', 'is_correct' => false],
            ['soal_id' => 15, 'label' => 'D', 'isi_opsi' => 'Kata yang sering digunakan di media', 'is_correct' => false],
            ['soal_id' => 15, 'label' => 'E', 'isi_opsi' => 'Kata yang berasal dari bahasa daerah', 'is_correct' => false],

            // Opsi untuk soal 16 (Ujian 5)
            ['soal_id' => 16, 'label' => 'A', 'isi_opsi' => 'analisa', 'is_correct' => false],
            ['soal_id' => 16, 'label' => 'B', 'isi_opsi' => 'analisis', 'is_correct' => true],
            ['soal_id' => 16, 'label' => 'C', 'isi_opsi' => 'analisys', 'is_correct' => false],
            ['soal_id' => 16, 'label' => 'D', 'isi_opsi' => 'analiesa', 'is_correct' => false],
            ['soal_id' => 16, 'label' => 'E', 'isi_opsi' => 'analysa', 'is_correct' => false],
        ];

        DB::table('opsi_jawaban')->insert($opsiData);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JawabanSiswa;

class JawabanSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEMO DATA - Focused on Siswa 1 with realistic scenarios including exams to take!

        // ========================================
        // Ujian 4 - Completed exam with confirmed grades (SCENARIO: View results)
        // ========================================
        JawabanSiswa::create([
            'siswa_id' => 1,
            'soal_id' => 13,
            'opsi_id' => null,
            'jawaban_teks' => 'Sistem tanam paksa adalah kebijakan yang diterapkan Gubernur Jenderal Van den Bosch pada tahun 1830. Sistem ini mewajibkan petani pribumi menanam tanaman ekspor seperti kopi, teh, tembakau di seperlima tanah mereka atau bekerja 66 hari dalam setahun di perkebunan pemerintah.',
            'skor_diperoleh' => 42.50,
            'waktu_dijawab' => '2025-10-05 14:25:00',
            'nilai_llama3' => 42.50
        ]);

        JawabanSiswa::create([
            'siswa_id' => 1,
            'soal_id' => 14,
            'opsi_id' => null,
            'jawaban_teks' => 'Dampak negatif tanam paksa: kemiskinan karena tidak bisa menanam padi, kelaparan di berbagai daerah, eksploitasi tenaga kerja berlebihan, hancurnya sistem pertanian tradisional.',
            'skor_diperoleh' => 42.50,
            'waktu_dijawab' => '2025-10-05 14:40:00',
            'nilai_llama3' => 42.50
        ]);

        // ========================================
        // Ujian 5 - Ongoing exam with partial answers (SCENARIO: Can continue exam)
        // ========================================
        JawabanSiswa::create([
            'siswa_id' => 1,
            'soal_id' => 15,
            'opsi_id' => 25, // Correct answer
            'jawaban_teks' => null,
            'skor_diperoleh' => 25.00,
            'waktu_dijawab' => '2025-10-05 15:45:00',
            'nilai_llama3' => 25.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 1,
            'soal_id' => 16,
            'opsi_id' => 30, // Correct answer: analisis
            'jawaban_teks' => null,
            'skor_diperoleh' => 25.00,
            'waktu_dijawab' => '2025-10-05 15:48:00',
            'nilai_llama3' => 25.00
        ]);

        // Questions 17 and 18 are left unanswered - siswa can continue from here!

        // ========================================
        // Ujian 1 - NO ANSWERS (SCENARIO: Fresh exam ready to start)
        // ========================================
        // No JawabanSiswa records = exam belum dimulai, siap dikerjakan!

        // ========================================
        // Ujian 2 - NO ANSWERS (SCENARIO: Another fresh exam ready to start)
        // ========================================
        // No JawabanSiswa records = exam belum dimulai, siap dikerjakan!

        // ========================================
        // Additional data for other students to maintain realistic environment
        // ========================================

        // Other students' data for Ujian 4 (to show class performance comparison)
        JawabanSiswa::create([
            'siswa_id' => 2,
            'soal_id' => 13,
            'opsi_id' => null,
            'jawaban_teks' => 'Tanam paksa adalah sistem yang memaksa petani menanam tanaman untuk Belanda. Dimulai tahun 1830 oleh Van den Bosch.',
            'skor_diperoleh' => 35.00,
            'waktu_dijawab' => '2025-10-05 14:20:00',
            'nilai_llama3' => 35.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 2,
            'soal_id' => 14,
            'opsi_id' => null,
            'jawaban_teks' => 'Rakyat menjadi miskin dan kelaparan karena tidak bisa menanam makanan sendiri.',
            'skor_diperoleh' => 43.50,
            'waktu_dijawab' => '2025-10-05 14:45:00',
            'nilai_llama3' => 43.50
        ]);

        // Other student's data for Ujian 1 (completed by siswa 3)
        JawabanSiswa::create([
            'siswa_id' => 3,
            'soal_id' => 1,
            'opsi_id' => 2, // Wrong answer
            'jawaban_teks' => null,
            'skor_diperoleh' => 0.00,
            'waktu_dijawab' => '2025-10-05 08:12:00',
            'nilai_llama3' => 0.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 3,
            'soal_id' => 2,
            'opsi_id' => 6, // Correct answer
            'jawaban_teks' => null,
            'skor_diperoleh' => 20.00,
            'waktu_dijawab' => '2025-10-05 08:15:00',
            'nilai_llama3' => 20.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 3,
            'soal_id' => 3,
            'opsi_id' => 9, // Wrong answer
            'jawaban_teks' => null,
            'skor_diperoleh' => 0.00,
            'waktu_dijawab' => '2025-10-05 08:18:00',
            'nilai_llama3' => 0.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 3,
            'soal_id' => 4,
            'opsi_id' => null,
            'jawaban_teks' => 'Indonesia ingin merdeka karena sudah lama dijajah dan ingin bebas.',
            'skor_diperoleh' => 8.00,
            'waktu_dijawab' => '2025-10-05 08:30:00',
            'nilai_llama3' => 8.00
        ]);

        JawabanSiswa::create([
            'siswa_id' => 3,
            'soal_id' => 5,
            'opsi_id' => null,
            'jawaban_teks' => 'Pertempuran Surabaya menunjukkan keberanian rakyat Indonesia melawan penjajah dan menjadi hari pahlawan.',
            'skor_diperoleh' => 28.00,
            'waktu_dijawab' => '2025-10-05 08:45:00',
            'nilai_llama3' => 28.00
        ]);
    }
}

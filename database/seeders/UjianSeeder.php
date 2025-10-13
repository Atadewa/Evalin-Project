<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ujian;

class UjianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEMO DATA - All exams scheduled for 2025-10-05 with different times
        
        // Ujian 1 - Mixed exam (SCENARIO: Available to take)
        Ujian::create([
            'id' => 1,
            'mapel_id' => 1, // Sejarah
            'nama_ujian' => 'Ujian Tengah Semester Sejarah Kelas 11A',
            'created_by' => 1, // Guru Sejarah (guru_id = 1)
            'jadwal' => '2025-10-05 08:00:00',
            'waktu_selesai' => '2025-10-15 10:00:00',
            'is_published' => false,
            'jenis_ujian' => 'mix',
            'deskripsi' => 'Ujian tengah semester mata pelajaran Sejarah mencakup materi Kemerdekaan Indonesia dan Perjuangan Mempertahankan Kemerdekaan',
            'durasi_jam' => 2,
            'durasi_menit' => 0,
        ]);

        // Ujian 2 - Essay exam (SCENARIO: Available to take)
        Ujian::create([
            'id' => 2,
            'mapel_id' => 2, // Bahasa Indonesia
            'nama_ujian' => 'Ujian Bahasa Indonesia - Sastra dan Tata Bahasa',
            'created_by' => 2, // Guru Bahasa Indonesia (guru_id = 2)
            'jadwal' => '2025-10-05 10:30:00',
            'waktu_selesai' => '2025-10-15 12:00:00',
            'is_published' => false,
            'jenis_ujian' => 'essay',
            'deskripsi' => 'Ujian bahasa Indonesia fokus pada pemahaman sastra dan penguasaan tata bahasa',
            'durasi_jam' => 1,
            'durasi_menit' => 30,
        ]);

        // Ujian 3 - Draft exam (SCENARIO: Not visible, not published)
        Ujian::create([
            'id' => 3,
            'mapel_id' => 3, // PPKN
            'nama_ujian' => 'Ujian PPKN - Pancasila dan UUD 1945',
            'created_by' => 4, // Guru PPKN (guru_id = 4, mapel_id = 3)
            'jadwal' => '2025-10-05 13:00:00',
            'waktu_selesai' => '2025-10-15 14:30:00',
            'is_published' => false,
            'jenis_ujian' => 'pilihan_ganda',
            'deskripsi' => 'Ujian PPKN tentang nilai-nilai Pancasila dan pemahaman UUD 1945',
            'durasi_jam' => 1,
            'durasi_menit' => 30,
        ]);

        // Ujian 4 - Completed exam (SCENARIO: Already completed & graded)
        Ujian::create([
            'id' => 4,
            'mapel_id' => 1, // Sejarah
            'nama_ujian' => 'Quiz Sejarah - Periode Kolonial',
            'created_by' => 1, // Guru Sejarah (guru_id = 1)
            'jadwal' => '2025-10-05 14:00:00',
            'waktu_selesai' => '2025-10-15 15:00:00',
            'is_published' => false,
            'jenis_ujian' => 'essay',
            'deskripsi' => 'Quiz singkat tentang periode kolonial di Indonesia',
            'durasi_jam' => 1,
            'durasi_menit' => 0,
        ]);

        // Ujian 5 - Mixed exam (SCENARIO: Currently ongoing)
        Ujian::create([
            'id' => 5,
            'mapel_id' => 5, // Bahasa Indonesia (guru_id = 5)
            'nama_ujian' => 'Ulangan Harian Bahasa Indonesia',
            'created_by' => 5, // Guru Bahasa Indonesia kedua (guru_id = 5)
            'jadwal' => '2025-10-05 15:30:00',
            'waktu_selesai' => '2025-10-15 17:30:00',
            'is_published' => false,
            'jenis_ujian' => 'mix',
            'deskripsi' => 'Ulangan harian mencakup pemahaman bacaan dan tata bahasa',
            'durasi_jam' => 2,
            'durasi_menit' => 0,
        ]);
    }
}

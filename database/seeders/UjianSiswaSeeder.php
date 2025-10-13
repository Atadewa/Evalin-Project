<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UjianSiswa;

class UjianSiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEMO DATA - Balanced scenarios for Siswa 1 with exams to actually take!
        
        // Ujian 4 - Completed exam with confirmed grades (SCENARIO: View results)
        UjianSiswa::create([
            'ujian_id' => 4,
            'siswa_id' => 1,
            'status' => 'selesai',
            'total_nilai' => 85.00,
            'persentase_nilai_2' => 82.50,
            'status_penilaian' => true,
            'confirmed_at' => '2025-10-05 16:00:00',
            'confirmed_by' => 1,
            'waktu_mulai' => '2025-10-05 14:00:00',
            'waktu_selesai' => '2025-10-05 14:45:00'
        ]);

        // Ujian 5 - Currently ongoing (SCENARIO: Continue exam - started but not finished)
        UjianSiswa::create([
            'ujian_id' => 5,
            'siswa_id' => 1,
            'status' => 'ongoing',
            'total_nilai' => null,
            'persentase_nilai_2' => null,
            'status_penilaian' => false,
            'waktu_mulai' => '2025-10-05 15:30:00',
            'waktu_selesai' => null
        ]);

        // Ujian 1 - Available to start (SCENARIO: Fresh exam to take)
        // No record = exam is available but not started yet
        
        // Ujian 2 - Available to start (SCENARIO: Another fresh exam to take)
        // No record = exam is available but not started yet
        
        // Ujian 3 - Draft exam (SCENARIO: Not visible because not published)
        // No record because exam is not published
        
        // Additional entries for other students to maintain realistic data
        UjianSiswa::create([
            'ujian_id' => 4,
            'siswa_id' => 2,
            'status' => 'selesai',
            'total_nilai' => 78.50,
            'persentase_nilai_2' => 75.00,
            'status_penilaian' => true,
            'confirmed_at' => '2025-10-05 16:00:00',
            'confirmed_by' => 1,
            'waktu_mulai' => '2025-10-05 14:00:00',
            'waktu_selesai' => '2025-10-05 14:50:00'
        ]);

        UjianSiswa::create([
            'ujian_id' => 1,
            'siswa_id' => 3,
            'status' => 'selesai',
            'total_nilai' => 76.00,
            'persentase_nilai_2' => null,
            'status_penilaian' => false,
            'waktu_mulai' => '2025-10-05 08:00:00',
            'waktu_selesai' => '2025-10-05 09:30:00'
        ]);
    }
}

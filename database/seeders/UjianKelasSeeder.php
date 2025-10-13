<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UjianKelas;

class UjianKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DEMO DATA - All exams assigned to Class 11A (kelas_id = 10) where Siswa 1 belongs
        
        // Ujian 1 - Mixed exam for class 11A (SCENARIO: Completed but not graded)
        UjianKelas::updateOrCreate(
            ['ujian_id' => 1, 'kelas_id' => 10],
            []
        );

        // Ujian 2 - Essay exam for class 11A (SCENARIO: Available to start)
        UjianKelas::updateOrCreate(
            ['ujian_id' => 2, 'kelas_id' => 10],
            []
        );

        // Ujian 3 - Draft exam for class 11A (SCENARIO: Not published, invisible)
        UjianKelas::updateOrCreate(
            ['ujian_id' => 3, 'kelas_id' => 10],
            []
        );

        // Ujian 4 - Completed exam for class 11A (SCENARIO: Completed & graded)
        UjianKelas::updateOrCreate(
            ['ujian_id' => 4, 'kelas_id' => 10],
            []
        );

        // Ujian 5 - Ongoing exam for class 11A (SCENARIO: Can continue)
        UjianKelas::updateOrCreate(
            ['ujian_id' => 5, 'kelas_id' => 10],
            []
        );

        // Additional exam assignments for other classes (to maintain realistic data)
        // Ujian 1 also available for class 11B
        UjianKelas::updateOrCreate(
            ['ujian_id' => 1, 'kelas_id' => 11],
            []
        );

        // Ujian 2 also available for class 11C
        UjianKelas::updateOrCreate(
            ['ujian_id' => 2, 'kelas_id' => 12],
            []
        );
    }
}

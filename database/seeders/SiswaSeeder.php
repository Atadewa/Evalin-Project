<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $siswaData = [
            ['id' => 1, 'user_id' => 8, 'kelas_id' => 10, 'nis' => '9202'],
            ['id' => 2, 'user_id' => 9, 'kelas_id' => 10, 'nis' => '8875'],
            ['id' => 3, 'user_id' => 10, 'kelas_id' => 10, 'nis' => '8877'],
            ['id' => 4, 'user_id' => 11, 'kelas_id' => 10, 'nis' => '8899'],
            ['id' => 5, 'user_id' => 12, 'kelas_id' => 10, 'nis' => '8901'],
            ['id' => 6, 'user_id' => 13, 'kelas_id' => 10, 'nis' => '8925'],
            ['id' => 7, 'user_id' => 14, 'kelas_id' => 10, 'nis' => '8929'],
            ['id' => 8, 'user_id' => 15, 'kelas_id' => 10, 'nis' => '8965'],
            ['id' => 9, 'user_id' => 16, 'kelas_id' => 10, 'nis' => '8988'],
            ['id' => 10, 'user_id' => 17, 'kelas_id' => 10, 'nis' => '8989'],
        ];

        DB::table('siswa')->insert($siswaData);
    }
}
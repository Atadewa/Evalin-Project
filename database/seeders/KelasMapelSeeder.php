<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasMapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kelasMapelData = [
            ['id' => 1, 'kelas_id' => 10, 'mapel_id' => 2],
            ['id' => 2, 'kelas_id' => 10, 'mapel_id' => 5],
            ['id' => 3, 'kelas_id' => 10, 'mapel_id' => 3],
            ['id' => 4, 'kelas_id' => 10, 'mapel_id' => 4],
            ['id' => 5, 'kelas_id' => 10, 'mapel_id' => 1],
        ];

        DB::table('kelas_mapel')->insert($kelasMapelData);
    }
}
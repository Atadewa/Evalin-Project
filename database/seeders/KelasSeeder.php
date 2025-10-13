<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kelasData = [
            ['id' => 1, 'nama_kelas' => '10A', 'tingkat' => 1],
            ['id' => 2, 'nama_kelas' => '10B', 'tingkat' => 1],
            ['id' => 3, 'nama_kelas' => '10C', 'tingkat' => 1],
            ['id' => 4, 'nama_kelas' => '10D', 'tingkat' => 1],
            ['id' => 5, 'nama_kelas' => '10E', 'tingkat' => 1],
            ['id' => 6, 'nama_kelas' => '10F', 'tingkat' => 1],
            ['id' => 7, 'nama_kelas' => '10G', 'tingkat' => 1],
            ['id' => 8, 'nama_kelas' => '10H', 'tingkat' => 1],
            ['id' => 9, 'nama_kelas' => '10I', 'tingkat' => 1],
            ['id' => 10, 'nama_kelas' => '11A', 'tingkat' => 2],
            ['id' => 11, 'nama_kelas' => '11B', 'tingkat' => 2],
            ['id' => 12, 'nama_kelas' => '11C', 'tingkat' => 2],
            ['id' => 13, 'nama_kelas' => '11D', 'tingkat' => 2],
            ['id' => 14, 'nama_kelas' => '11E', 'tingkat' => 2],
            ['id' => 15, 'nama_kelas' => '11F', 'tingkat' => 2],
            ['id' => 16, 'nama_kelas' => '11G', 'tingkat' => 2],
            ['id' => 17, 'nama_kelas' => '11H', 'tingkat' => 2],
            ['id' => 18, 'nama_kelas' => '11I', 'tingkat' => 2],
            ['id' => 19, 'nama_kelas' => '12A', 'tingkat' => 3],
            ['id' => 20, 'nama_kelas' => '12B', 'tingkat' => 3],
            ['id' => 21, 'nama_kelas' => '12C', 'tingkat' => 3],
            ['id' => 22, 'nama_kelas' => '12D', 'tingkat' => 3],
            ['id' => 23, 'nama_kelas' => '12E', 'tingkat' => 3],
            ['id' => 24, 'nama_kelas' => '12F', 'tingkat' => 3],
            ['id' => 25, 'nama_kelas' => '12G', 'tingkat' => 3],
            ['id' => 26, 'nama_kelas' => '12H', 'tingkat' => 3],
            ['id' => 27, 'nama_kelas' => '12I', 'tingkat' => 3],
        ];

        DB::table('kelas')->insert($kelasData);
    }
}
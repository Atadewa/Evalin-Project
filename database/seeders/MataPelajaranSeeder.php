<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MataPelajaran::create([
            'nama_mapel' => 'Sejarah',
            'guru_id' => 1
        ]);

        MataPelajaran::create([
            'nama_mapel' => 'Bahasa Indonesia',
            'guru_id' => 2
        ]);

        MataPelajaran::create([
            'nama_mapel' => 'PPKN',
            'guru_id' => 3
        ]);

        MataPelajaran::create([
            'nama_mapel' => 'PPKN',
            'guru_id' => 4
        ]);

        MataPelajaran::create([
            'nama_mapel' => 'Bahasa Indonesia',
            'guru_id' => 5
        ]);
    }
}
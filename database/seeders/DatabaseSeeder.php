<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
            GuruSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            MataPelajaranSeeder::class,
            KelasMapelSeeder::class,
            UjianSeeder::class,
            SoalSeeder::class,
            UjianKelasSeeder::class,
            UjianSiswaSeeder::class,
            JawabanSiswaSeeder::class,
        ]);
    }
}

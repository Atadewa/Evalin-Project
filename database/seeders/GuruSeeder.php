<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Guru;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Guru::create([
            'user_id' => 2,
            'nip' => '111111'
        ]);

        Guru::create([
            'user_id' => 3,
            'nip' => '123456'
        ]);

        Guru::create([
            'user_id' => 4,
            'nip' => '222222'
        ]);

        Guru::create([
            'user_id' => 5,
            'nip' => '333333'
        ]);

        Guru::create([
            'user_id' => 6,
            'nip' => '12121212'
        ]);

        Guru::create([
            'user_id' => 7,
            'nip' => '13131313'
        ]);
    }
}
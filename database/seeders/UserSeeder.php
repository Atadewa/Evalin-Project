<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Guru
        User::create([
            'name' => 'Emmi Pujawati, S.Pd.',
            'email' => 'guru1@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'RYAN',
            'email' => 'ryan@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);

        User::create([
            'name' => 'Siti Nuraisyah, S.Pd.',
            'email' => 'guru2@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Solikhul Huda, S.Pd., M.H.',
            'email' => 'guru4@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Marsam, S.Pd., M.M.',
            'email' => 'guru3@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Aziza Djauhari, S.Pd., M.Pd',
            'email' => 'guru5@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru'
        ]);

        // Siswa (hanya 10 siswa)
        User::create([
            'name' => 'ACH. NURIL FIRDAUS',
            'email' => 'achnurilfirdaus.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        User::create([
            'name' => 'ACHMAD THORIQ ROZZAQI',
            'email' => 'achmadthoriqrozzaqi.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'ADAM FIRDAUS SHIDDIQI',
            'email' => 'adamfirdausshiddiqi.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'AL ERZA ILHAM BINTANG',
            'email' => 'alerzailhambintang.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'ALDINATA PRAYOGA',
            'email' => 'aldinataprayoga.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'BEMBY APRILIAN PUTRI',
            'email' => 'bembyaprilianputri.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'DAFA SATRIO ADILA ACHMAD',
            'email' => 'dafasatrioadilaachmad.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'FIOLA APPRILIA MAULIDYA',
            'email' => 'fiolaappriliamaulidya.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'HETY KHUMAIROH',
            'email' => 'hetykhumairoh.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);

        User::create([
            'name' => 'HILWA SALSABILA',
            'email' => 'hilwasalsabila.11a@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa'
        ]);
    }
}
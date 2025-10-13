<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Soal extends Model
{
    protected $table = 'soal';
    protected $fillable = [
        'ujian_id',
        'pertanyaan',
        'jawaban_benar',
        'opsi_benar',
        'tipe_soal',
        'pembahasan'
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function jawabanSiswas()
    {
        return $this->hasMany(JawabanSiswa::class);
    }

    public function opsiJawaban()
    {
        return $this->hasMany(OpsiJawaban::class);
    }

    public function jawaban()
    {
        // Check if auth user exists and has siswa relationship
        if (Auth::check() && Auth::user()->siswa) {
            return $this->hasOne(JawabanSiswa::class)->where('siswa_id', Auth::user()->siswa->id);
        }

        return $this->hasOne(JawabanSiswa::class)->whereRaw('1=0'); // Return empty relation
    }

}

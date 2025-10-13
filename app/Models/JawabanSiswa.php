<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
    protected $table = 'jawaban_siswa';
     protected $fillable = [
        'siswa_id',
        'soal_id',
        'opsi_id',
        'jawaban_teks',
        'waktu_dijawab',
        'nilai_llama3',
        'percent_text_similarity',
        'time_koreksi',
        'skor',
        'skor_diperoleh',
    ];

    protected $casts = [
        'waktu_dijawab' => 'datetime',
        'nilai_llama3' => 'float',
        'skor_diperoleh' => 'float',
        'is_benar' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function opsiJawaban()
    {
        return $this->belongsTo(OpsiJawaban::class, 'opsi_id');
    }
}

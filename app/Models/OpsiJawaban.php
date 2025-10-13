<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    protected $table = 'opsi_jawaban';
    
    protected $fillable = [
        'soal_id',
        'label',
        'isi_opsi',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }

    public function jawabanSiswa()
    {
        return $this->hasMany(JawabanSiswa::class);
    }
}

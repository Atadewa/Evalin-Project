<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UjianSiswa extends Model
{
    protected $table = 'ujian_siswa';

    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'status',
        'total_nilai',
        'presentase_nilai_2',
        'time_koreksi',
        'waktu_mulai',
        'waktu_selesai',
        'status_penilaian',
        'confirmed_at',
        'confirmed_by'
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'status_penilaian' => 'boolean',
        'total_nilai' => 'float',
        'nilai_1' => 'float',
    ];

    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function confirmedBy()
    {
        return $this->belongsTo(Guru::class, 'confirmed_by');
    }

    public function jawabanSiswa()
    {
        // Hubungkan lewat siswa_id, bukan ujian_id
        return $this->hasMany(JawabanSiswa::class, 'siswa_id', 'siswa_id');
    }
}

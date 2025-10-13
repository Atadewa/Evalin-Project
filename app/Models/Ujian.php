<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = 'ujian';

    protected $fillable = [
        'nama_ujian',
        'mapel_id',
        'created_by',
        'jadwal',
        'waktu_selesai',
        'is_published',
        'jenis_ujian',
        'deskripsi',
        'durasi_jam',
        'durasi_menit',
        'time_koreksi',
    ];

    protected $dates = ['jadwal', 'waktu_selesai'];

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }


    public function guru()
    {
        return $this->belongsTo(Guru::class, 'created_by');
    }

    public function soals()
    {
        return $this->hasMany(Soal::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'ujian_kelas', 'ujian_id', 'kelas_id');
    }

    public function ujianSiswa()
    {
        return $this->hasMany(UjianSiswa::class);
    }

    public function kelasMapel()
    {
        return $this->hasMany(KelasMapel::class, 'mapel_id', 'mapel_id');
    }

    public function getStatusAttribute()
    {
        $now = now();

        if (!$this->is_published) {
            return 'draft';
        }

        if ($now->lt($this->jadwal)) {
            return 'published';
        }

        if ($now->between($this->jadwal, $this->waktu_selesai)) {
            return 'active';
        }

        return 'finished';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

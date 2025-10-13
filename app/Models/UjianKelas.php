<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class JadwalUjianKela
 * 
 * @property int $id
 * @property int $ujian_id
 * @property int $kelas_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Kelas $kelas
 * @property Ujian $ujian
 *
 * @package App\Models
 */
class UjianKelas extends Model
{
	protected $connection = 'mysql';
	protected $table = 'ujian_kelas';

	protected $casts = [
		'ujian_id' => 'int',
		'kelas_id' => 'int'
	];

	protected $fillable = [
		'ujian_id',
		'kelas_id'
	];

	public function kelas()
	{
		return $this->belongsTo(Kelas::class, 'kelas_id');
	}

	public function ujian()
	{
		return $this->belongsTo(Ujian::class);
	}
}

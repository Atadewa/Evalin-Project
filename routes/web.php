<?php

use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Guru\SiswaController;
use App\Http\Controllers\Guru\UjianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Guru\SoalController;
use App\Http\Controllers\Siswa\UjianController as SiswaUjianController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/user/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/user/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/user/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::delete('/profile/photo', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::post('admin/users/import', [UserController::class, 'import'])->name('admin.users.import');
    Route::resource('admin/users', UserController::class)->names('admin.users');

    Route::resource('admin/kelas', KelasController::class)->names('admin.kelas');
    Route::resource('admin/mata-pelajaran', MataPelajaranController::class)->names('admin.mata-pelajaran');
});

Route::middleware(['auth', 'role:guru'])->group(function () {
    Route::post('guru/siswa/import', [SiswaController::class, 'import'])->name('guru.siswa.import');
    Route::resource('guru/siswa', SiswaController::class)->names('guru.siswa');
    
    // Ujian routes
    Route::get('guru/ujian/koreksi/{ujianid}/{siswaid}', [UjianController::class, 'koreksiUjianSiswaPersiswa'])->name('guru.ujian.koreksi-persiswa');
    Route::get('guru/ujian/koreksi/{ujianid}/{siswaid}', [UjianController::class, 'koreksiUjianSiswaPersiswa'])->name('guru.ujian.show.koreksiUjianSiswaPersiswa'); // Nama route lama untuk compatibility
    Route::get('guru/ujian/{id}/koreksi', [UjianController::class, 'showKoreksi'])->name('guru.ujian.show.koreksi'); // Route untuk tampilan koreksi umum
    Route::post('guru/ujian/koreksi/{ujianid}', [UjianController::class, 'koreksiUjianSiswa'])->name('guru.ujian.koreksi-ulang');
    Route::get('guru/ujian-nilai-siswa/{id}/{ujianid}', [UjianController::class, 'showNilaisiswa'])->name('guru.ujian.show.nilaisiswa'); // Nama route lama untuk compatibility
    Route::get('guru/ujian/export/{id}', [UjianController::class, 'exportHasilUjian'])->name('guru.ujian.exportHasilUjian');
    Route::post('guru/ujian/{ujian}/konfirmasi-nilai', [UjianController::class, 'konfirmasiNilai'])->name('guru.ujian.konfirmasi-nilai');
    Route::post('guru/ujian/{ujian}/batal-konfirmasi-nilai', [UjianController::class, 'batalKonfirmasiNilai'])->name('guru.ujian.batal-konfirmasi-nilai');
    Route::post('guru/ujian/{ujian}/publish', [UjianController::class, 'publish'])->name('guru.ujian.publish');
    Route::post('guru/ujian/{ujian}/unpublish', [UjianController::class, 'unpublish'])->name('guru.ujian.unpublish');
    Route::resource('guru/ujian', UjianController::class)->names('guru.ujian');
    
    // Soal routes with ujian_id
    Route::get('guru/soal/create', [SoalController::class, 'create'])->name('guru.soal.create');
    Route::post('guru/soal/import', [SoalController::class, 'import'])->name('admin.soal.import');
    Route::resource('guru/soal', SoalController::class)->except(['create'])->names('guru.soal');
});

Route::middleware(['auth', 'role:siswa'])->group(function () {
    Route::resource('siswa/ujian', SiswaUjianController::class,)->names('siswa.ujian');
    Route::post('siswa/ujian/simpan-jawaban', [SiswaUjianController::class, 'simpanJawaban'])->name('siswa.ujian.simpan-jawaban');
    Route::post('siswa/ujian/selesaikan', [SiswaUjianController::class, 'selesaikanUjian'])->name('siswa.ujian.selesaikan');
    Route::get('siswa/ujian/{ujian}/hasil', [SiswaUjianController::class, 'hasil'])->name('siswa.ujian.hasil');
});

Route::get('/weather', [WeatherController::class, 'padangWeather']);
Route::get('/weather-api', [WeatherController::class, 'getWeatherApi']);
require __DIR__ . '/auth.php';

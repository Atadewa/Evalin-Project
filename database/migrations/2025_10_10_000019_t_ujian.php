<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ujian')->nullable();
            $table->foreignId('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('guru')->onDelete('cascade');
            $table->dateTime('jadwal')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->boolean('is_published')->default(false);
            $table->enum('jenis_ujian', ['pilihan_ganda','essay','mix'])->default('pilihan_ganda');
            $table->text('deskripsi')->nullable();
            $table->integer('durasi_jam')->default(1);
            $table->integer('durasi_menit')->default(0);
            $table->integer('time_koreksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};

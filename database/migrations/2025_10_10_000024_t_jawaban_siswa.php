<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->foreignId('soal_id')->constrained('soal');
            $table->unsignedBigInteger('opsi_id')->nullable();
            $table->text('jawaban_teks')->nullable();
            $table->timestamp('waktu_dijawab')->nullable();
            $table->decimal('nilai_llama3', 8, 2)->nullable();
            $table->decimal('percent_text_similarity', 5, 2)->default(0.00);
            $table->integer('time_koreksi')->nullable();
            $table->decimal('skor', 6, 2)->nullable();
            $table->decimal('skor_diperoleh', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};

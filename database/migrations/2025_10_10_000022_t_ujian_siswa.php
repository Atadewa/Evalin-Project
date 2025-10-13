<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ujian_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian');
            $table->foreignId('siswa_id')->constrained('siswa');
            $table->enum('status', ['incoming','ended','ongoing','selesai'])->default('ongoing');
            $table->decimal('total_nilai', 8, 2)->nullable();
            $table->decimal('persentase_nilai_2', 5, 2)->nullable()->default(0.00);
            $table->timestamp('time_koreksi')->nullable();
            $table->timestamp('waktu_mulai')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->boolean('status_penilaian')->default(false);
            $table->timestamp('confirmed_at')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('guru')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ujian_siswa');
    }
};

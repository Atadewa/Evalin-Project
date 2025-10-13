<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mapel');
            $table->foreignId('guru_id')->constrained('guru');
            $table->timestamps();
        });

        Schema::create('kelas_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('mapel_id')->constrained('mata_pelajaran');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_mapel');
        Schema::dropIfExists('mata_pelajaran');
    }
};

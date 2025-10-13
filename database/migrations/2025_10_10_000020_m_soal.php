<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->nullable()->constrained('ujian')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->text('jawaban_benar')->nullable();
            $table->json('opsi_benar')->nullable();
            $table->enum('tipe_soal', ['pilgan','essay'])->default('essay');
            $table->text('pembahasan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal');
    }
};

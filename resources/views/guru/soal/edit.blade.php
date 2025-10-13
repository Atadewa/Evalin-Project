@extends('layouts.app')

@section('title', 'Edit Soal')

@push('styles')
<style>
    .jenis-card {
        transition: all 0.2s ease;
    }
    
    .jenis-card.selected {
        border-color: #3b82f6 !important;
        background-color: #dbeafe;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    
    .jenis-card.selected svg {
        color: #3b82f6;
    }
    
    .jenis-card.selected h3 {
        color: #1e40af;
    }
    
    .option-item {
        transition: all 0.3s ease;
    }
    
    .option-correct {
        background-color: #dcfce7;
        border-color: #16a34a;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Edit Soal</h1>
                <p class="text-gray-600 mt-2">
                    {{ $soal->ujian->nama_ujian }} • {{ $soal->ujian->mataPelajaran->nama_mapel }}
                </p>
            </div>
            <a href="{{ route('guru.ujian.show', $soal->ujian_id) }}" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">
                        Ada {{ $errors->count() }} kesalahan yang perlu diperbaiki:
                    </h3>
                    <div class="mt-2 text-sm text-red-700">
                        <ul role="list" class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Form -->
    <form method="POST" action="{{ route('guru.soal.update', $soal->id) }}" id="soal-form" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Form Soal -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Info Ujian -->
            <div class="mb-6 bg-gray-50 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                    <span class="text-gray-700">
                        Ujian: <strong>{{ $soal->ujian->nama_ujian }}</strong> •
                        Jenis: <strong>{{ ucfirst(str_replace('_', ' ', $soal->jenis_soal)) }}</strong>
                    </span>
                </div>
            </div>

            <!-- Nomor Soal dan Skor -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="nomor_soal" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Soal <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="nomor_soal" 
                           id="nomor_soal"
                           value="{{ old('nomor_soal', $soal->nomor_soal) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="1" required>
                    @error('nomor_soal')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="skor" class="block text-sm font-medium text-gray-700 mb-2">
                        Skor <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="skor" 
                           id="skor"
                           value="{{ old('skor', $soal->skor) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           min="1" max="100" required>
                    @error('skor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Pertanyaan -->
            <div class="mb-6">
                <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                    Pertanyaan Soal <span class="text-red-500">*</span>
                </label>
                <textarea name="pertanyaan" id="pertanyaan" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tuliskan pertanyaan soal di sini..."
                    required>{{ old('pertanyaan', $soal->pertanyaan) }}</textarea>
                @error('pertanyaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Opsi Jawaban untuk Pilihan Ganda -->
            @if($soal->jenis_soal === 'pilihan_ganda')
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Opsi Jawaban <span class="text-red-500">*</span>
                </label>
                <div id="options-container" class="space-y-3">
                    @foreach($soal->opsiJawaban as $index => $opsi)
                    <div class="option-item flex items-center space-x-3 p-3 border border-gray-200 rounded-lg {{ $opsi->is_correct ? 'option-correct' : '' }}">
                        <input type="radio" name="jawaban_benar" value="{{ $index + 1 }}" 
                               class="text-blue-600 focus:ring-blue-500" 
                               {{ $opsi->is_correct ? 'checked' : '' }} required>
                        <span class="font-medium text-gray-700 min-w-[20px]">{{ $opsi->label }}.</span>
                        <input type="text" name="opsi_{{ $index + 1 }}" 
                               value="{{ $opsi->isi_opsi }}"
                               class="flex-1 border-0 focus:ring-0 p-0" 
                               placeholder="Masukkan opsi jawaban..." required>
                        <button type="button" class="remove-option text-red-500 hover:text-red-700 opacity-50 hover:opacity-100 transition"
                                onclick="removeOption(this)">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-option" 
                        class="mt-3 px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-500 transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Opsi
                </button>
            </div>
            @endif

            <!-- Kunci Jawaban untuk Essay -->
            @if($soal->jenis_soal === 'essay')
            <div class="mb-6">
                <label for="kunci_jawaban" class="block text-sm font-medium text-gray-700 mb-2">
                    Kunci Jawaban / Pembahasan <span class="text-red-500">*</span>
                </label>
                <textarea name="kunci_jawaban" id="kunci_jawaban" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Tuliskan kunci jawaban atau poin-penting yang harus ada dalam jawaban siswa..."
                    required>{{ old('kunci_jawaban', $soal->jawaban_benar) }}</textarea>
                @error('kunci_jawaban')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Kunci jawaban ini akan digunakan untuk penilaian otomatis essay oleh AI
                </p>
            </div>
            @endif

            <!-- Hidden field for jenis_soal -->
            <input type="hidden" name="jenis_soal" value="{{ $soal->jenis_soal }}">
            <input type="hidden" name="ujian_id" value="{{ $soal->ujian_id }}">
        </div>

        <!-- Form Actions -->
        <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
            <a href="{{ route('guru.ujian.show', $soal->ujian_id) }}" 
               class="text-gray-600 hover:text-gray-800 font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="resetForm()"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition">
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Simpan Perubahan
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let optionCount = {{ $soal->opsiJawaban->count() }};
    
    // Add option button (only for multiple choice)
    @if($soal->jenis_soal === 'pilihan_ganda')
    document.getElementById('add-option').addEventListener('click', addOption);
    
    function addOption() {
        optionCount++;
        const container = document.getElementById('options-container');
        
        const optionDiv = document.createElement('div');
        optionDiv.className = 'option-item flex items-center space-x-3 p-3 border border-gray-200 rounded-lg';
        optionDiv.innerHTML = `
            <input type="radio" name="jawaban_benar" value="${optionCount}" class="text-blue-600 focus:ring-blue-500" required>
            <span class="font-medium text-gray-700 min-w-[20px]">${String.fromCharCode(64 + optionCount)}.</span>
            <input type="text" name="opsi_${optionCount}" class="flex-1 border-0 focus:ring-0 p-0" 
                   placeholder="Masukkan opsi jawaban..." required>
            <button type="button" class="remove-option text-red-500 hover:text-red-700 opacity-50 hover:opacity-100 transition"
                    onclick="removeOption(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        container.appendChild(optionDiv);
    }
    
    window.removeOption = function(button) {
        if (optionCount > 2) { // Minimum 2 options
            button.closest('.option-item').remove();
            renumberOptions();
        }
    }
    
    function renumberOptions() {
        const options = document.querySelectorAll('.option-item');
        optionCount = 0;
        
        options.forEach((option, index) => {
            optionCount++;
            const letter = String.fromCharCode(64 + optionCount);
            option.querySelector('span').textContent = letter + '.';
            option.querySelector('input[type="radio"]').value = optionCount;
            option.querySelector('input[type="text"]').name = `opsi_${optionCount}`;
        });
    }
    @endif
});

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
        location.reload();
    }
}
</script>
@endpush
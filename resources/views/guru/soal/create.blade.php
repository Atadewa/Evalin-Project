@extends('layouts.app')

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
                    <h1 class="text-3xl font-bold text-gray-800">
                        @if ($ujian)
                            Tambah Soal: {{ $ujian->nama_ujian }}
                        @else
                            Tambah Soal Ujian
                        @endif
                    </h1>
                    <p class="text-gray-600 mt-2">
                        @if ($ujian)
                            {{ $ujian->mataPelajaran->nama_mapel }} • Tipe:
                            {{ ucfirst(str_replace('_', ' ', $ujian->jenis_ujian)) }}
                        @else
                            Buat soal untuk ujian yang akan diselenggarakan
                        @endif
                    </p>
                </div>
                <a href="{{ $ujian ? route('guru.ujian.show', $ujian->id) : route('guru.ujian.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if ($ujian)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            <span class="text-blue-800">Total soal saat ini:
                                <strong>{{ $ujian->soals()->count() }}</strong></span>
                        </div>
                        <span class="text-sm text-blue-600">Durasi: {{ $ujian->durasi }} menit</span>
                    </div>
                </div>
            @endif
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
        <form method="POST" action="{{ route('guru.soal.store') }}" id="soal-form" class="space-y-6">
            @csrf

            <!-- Ujian Selection (jika belum dipilih) -->
            @if (!$ujian)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <label for="ujian_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Ujian <span class="text-red-500">*</span>
                    </label>
                    <select name="ujian_id" id="ujian_id"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                        <option value="">Pilih Ujian</option>
                        @foreach ($ujians as $ujianOption)
                            <option value="{{ $ujianOption->id }}" data-jenis="{{ $ujianOption->jenis_ujian }}"
                                {{ old('ujian_id') == $ujianOption->id ? 'selected' : '' }}>
                                {{ $ujianOption->nama_ujian }} ({{ $ujianOption->mataPelajaran->nama_mapel }})
                            </option>
                        @endforeach
                    </select>
                    @error('ujian_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
            @endif

            <!-- Form Soal -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <!-- Jenis Soal (hanya jika ujian belum dipilih atau mixed) -->
                @if (!$ujian || $ujian->jenis_ujian == 'mix')
                    <div class="mb-6" id="jenis-soal-container">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Jenis Soal <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer">
                                <input type="radio" name="jenis_soal" value="pilgan" class="sr-only jenis-radio"
                                    {{ old('jenis_soal') == 'pilgan' ? 'checked' : '' }}>
                                <div class="jenis-card border-2 border-gray-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-list-ul text-2xl mb-2 text-gray-600"></i>
                                    <h3 class="font-medium text-gray-900">Pilihan Ganda</h3>
                                    <p class="text-sm text-gray-500 mt-1">Soal dengan opsi jawaban</p>
                                </div>
                            </label>

                            <label class="relative cursor-pointer">
                                <input type="radio" name="jenis_soal" value="essay" class="sr-only jenis-radio"
                                    {{ old('jenis_soal') == 'essay' ? 'checked' : '' }}>
                                <div class="jenis-card border-2 border-gray-200 rounded-lg p-4 text-center">
                                    <i class="fas fa-edit text-2xl mb-2 text-gray-600"></i>
                                    <h3 class="font-medium text-gray-900">Essay</h3>
                                    <p class="text-sm text-gray-500 mt-1">Soal dengan jawaban uraian</p>
                                </div>
                            </label>
                        </div>
                    </div>
                @else
                    <!-- Jenis soal otomatis berdasarkan ujian -->
                    <input type="hidden" name="jenis_soal" value="{{ $ujian->jenis_ujian }}" id="auto-jenis-soal">
                    <div class="mb-6 bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center">
                            <i
                                class="fas fa-{{ $ujian->jenis_ujian == 'pilgan' ? 'list-ul' : 'edit' }} text-blue-600 mr-2"></i>
                            <span class="text-gray-700">
                                Jenis soal: <strong>{{ ucfirst(str_replace('_', ' ', $ujian->jenis_ujian)) }}</strong>
                                (sesuai dengan tipe ujian)
                            </span>
                        </div>
                    </div>
                @endif
                <!-- Pertanyaan -->
                <div class="mb-6">
                    <label for="pertanyaan" class="block text-sm font-medium text-gray-700 mb-2">
                        Pertanyaan Soal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="pertanyaan" id="pertanyaan" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tuliskan pertanyaan soal di sini..." required>{{ old('pertanyaan') }}</textarea>
                    @error('pertanyaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opsi Jawaban untuk Pilihan Ganda -->
                <div id="pilihan-ganda-section" class="mb-6" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Opsi Jawaban <span class="text-red-500">*</span>
                    </label>
                    <div id="options-container" class="space-y-3">
                        <!-- Options will be added dynamically -->
                    </div>
                    <button type="button" id="add-option"
                        class="mt-3 px-4 py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-600 hover:border-blue-500 hover:text-blue-500 transition">
                        <i class="fas fa-plus mr-2"></i>Tambah Opsi
                    </button>
                </div>

                <!-- Kunci Jawaban untuk Essay -->
                <div id="essay-section" class="mb-6" style="display: none;">
                    <label for="jawaban_benar" class="block text-sm font-medium text-gray-700 mb-2">
                        Kunci Jawaban <span class="text-red-500">*</span>
                    </label>
                    <textarea name="jawaban_benar" id="jawaban_benar" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tuliskan kunci jawaban atau poin-poin penting yang harus ada dalam jawaban siswa...">{{ old('jawaban_benar') }}</textarea>
                    @error('jawaban_benar')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Kunci jawaban ini akan digunakan untuk penilaian otomatis essay oleh AI
                    </p>
                </div>

                <!-- Pembahasan (untuk semua jenis soal) -->
                <div class="mb-6" id="pembahasan-section">
                    <label for="pembahasan" class="block text-sm font-medium text-gray-700 mb-2">
                        Pembahasan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="pembahasan" id="pembahasan" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tuliskan penjelasan atau pembahasan untuk soal ini..." required>{{ old('pembahasan') }}</textarea>
                    @error('pembahasan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-lightbulb mr-1"></i>
                        Pembahasan akan ditampilkan kepada siswa setelah ujian selesai
                    </p>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="bg-gray-50 px-6 py-4 flex justify-between items-center">
                <a href="{{ $ujian ? route('guru.ujian.show', $ujian->id) : route('guru.ujian.index') }}"
                    class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>

                <div class="flex space-x-3">
                    <button type="submit" name="action" value="save"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Simpan Soal
                    </button>
                    <button type="submit" name="action" value="save_and_add"
                        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i>Simpan & Tambah Lagi
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let optionCount = 0;

            const jenisRadios = document.querySelectorAll('.jenis-radio');
            const autoJenisSoal = document.getElementById('auto-jenis-soal');
            const pilihanGandaSection = document.getElementById('pilihan-ganda-section');
            const essaySection = document.getElementById('essay-section');
            const container = document.getElementById('options-container');
            const addBtn = document.getElementById('add-option');

            // --- Jenis soal handler ---
            jenisRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    updateJenisSelection();
                    showRelevantSection(this.value);
                });
            });

            if (autoJenisSoal) showRelevantSection(autoJenisSoal.value);

            const checkedRadio = document.querySelector('.jenis-radio:checked');
            if (checkedRadio) {
                updateJenisSelection();
                showRelevantSection(checkedRadio.value);
            }

            function updateJenisSelection() {
                document.querySelectorAll('.jenis-card').forEach(c => c.classList.remove('selected'));
                const checked = document.querySelector('.jenis-radio:checked');
                if (checked) checked.closest('label').querySelector('.jenis-card').classList.add('selected');
            }

            function showRelevantSection(jenis) {
                if (jenis === 'pilgan') {
                    pilihanGandaSection.style.display = 'block';
                    essaySection.style.display = 'none';
                    initializePilihanGanda();
                } else if (jenis === 'essay') {
                    pilihanGandaSection.style.display = 'none';
                    essaySection.style.display = 'block';
                } else {
                    pilihanGandaSection.style.display = 'none';
                    essaySection.style.display = 'none';
                }
            }

            // --- Pilihan Ganda ---
            function initializePilihanGanda() {
                if (optionCount === 0) {
                    for (let i = 1; i <= 4; i++) addOption();
                    updateAddButtonState();
                }
            }

            addBtn.addEventListener('click', addOption);

            function addOption() {
                if (optionCount >= 5) {
                    alert('Maksimal 5 opsi jawaban diperbolehkan');
                    return;
                }

                optionCount++;
                const letter = String.fromCharCode(64 + optionCount); // A, B, C, D, E
                const div = document.createElement('div');
                div.className = 'option-item flex items-center space-x-3 p-3 border border-gray-200 rounded-lg';
                div.innerHTML = `
            <input type="radio" name="jawaban_benar" value="${letter}" required>
            <span class="font-medium text-gray-700 min-w-[20px]">${letter}.</span>
            <input type="text" name="opsi_${optionCount}" class="flex-1 border-0 focus:ring-0 p-0" placeholder="Masukkan opsi..." required>
            <button type="button" class="remove-option text-red-500" onclick="removeOption(this)">
                <i class="fas fa-times"></i>
            </button>
        `;
                container.appendChild(div);

                // Update button state
                updateAddButtonState();
            }

            window.removeOption = function(btn) {
                if (optionCount > 2) {
                    btn.closest('.option-item').remove();
                    renumberOptions();
                    updateAddButtonState();
                } else {
                    alert('Minimal 2 opsi diperlukan');
                }
            }

            function renumberOptions() {
                const options = container.querySelectorAll('.option-item');
                optionCount = 0;
                options.forEach(opt => {
                    optionCount++;
                    const letter = String.fromCharCode(64 + optionCount); // A, B, C, D, E
                    opt.querySelector('span').textContent = letter + '.';
                    opt.querySelector('input[type="radio"]').value = letter;
                    opt.querySelector('input[type="text"]').name = `opsi_${optionCount}`;
                });
            }

            function updateAddButtonState() {
                if (optionCount >= 5) {
                    addBtn.disabled = true;
                    addBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    addBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Maksimal 5 Opsi';
                } else {
                    addBtn.disabled = false;
                    addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    addBtn.innerHTML = '<i class="fas fa-plus mr-2"></i>Tambah Opsi';
                }
            }
        });
    </script>
@endpush

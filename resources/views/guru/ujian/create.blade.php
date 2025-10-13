@extends('layouts.app')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            min-height: 42px;
            background: white;
        }

        .select2-container--default.select2-container--focus .select2-selection {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('guru.ujian.index') }}" class="hover:text-blue-600">Manajemen Ujian</a>
                    <span>/</span>
                    <span class="text-gray-900">Buat Ujian Baru</span>
                </nav>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900">Buat Ujian Baru</h1>
                            <p class="text-gray-600">Lengkapi informasi dasar ujian, lalu lanjutkan ke penambahan soal</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 m-6 mb-0">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
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

                <form method="POST" action="{{ route('guru.ujian.store') }}" id="ujian-form">
                    @csrf

                    <!-- Form Content -->
                    <div class="p-6 space-y-6">

                        <!-- Basic Information -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Dasar</h2>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                <!-- Mata Pelajaran -->
                                <div>
                                    <label for="mapel_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Mata Pelajaran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="mapel_id" id="mapel_id"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($mapels as $mapel)
                                            <option value="{{ $mapel->id }}" {{ old('mapel_id') == $mapel->id ? 'selected' : '' }}>
                                                {{ $mapel->nama_mapel }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mapel_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Ujian -->
                                <div>
                                    <label for="jenis_ujian" class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Ujian <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_ujian" id="jenis_ujian"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        required>
                                        <option value="pilihan_ganda" {{ old('jenis_ujian', 'pilihan_ganda') == 'pilihan_ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                                        <option value="essay" {{ old('jenis_ujian') == 'essay' ? 'selected' : '' }}>Essay
                                        </option>
                                        <option value="mix" {{ old('jenis_ujian') == 'mix' ? 'selected' : '' }}>Campuran
                                        </option>
                                    </select>
                                    @error('jenis_ujian')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Nama Ujian -->
                            <div class="mt-6">
                                <label for="nama_ujian" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Ujian <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="nama_ujian" id="nama_ujian"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    value="{{ old('nama_ujian') }}" placeholder="Contoh: Ujian Akhir Bab 3 - Aljabar Linear"
                                    required>
                                @error('nama_ujian')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Deskripsi -->
                            <div class="mt-6">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi & Instruksi
                                </label>
                                <textarea name="deskripsi" id="deskripsi" rows="4"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Berikan instruksi ujian, aturan khusus, atau catatan penting lainnya...">{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Waktu & Durasi -->
                        <div class="border-b border-gray-200 pb-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Waktu</h2>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Durasi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Durasi Ujian <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <input type="number" name="durasi_jam"
                                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('durasi_jam', 1) }}" min="0" max="23" placeholder="0">
                                        <span class="text-gray-500">jam</span>
                                        <input type="number" name="durasi_menit"
                                            class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            value="{{ old('durasi_menit', 0) }}" min="0" max="59" placeholder="0">
                                        <span class="text-gray-500">menit</span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Waktu maksimal siswa mengerjakan ujian</p>
                                    @error('durasi_jam')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tanggal Mulai -->
                                <div>
                                    <label for="jadwal" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanggal & Waktu Mulai <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="jadwal" id="jadwal"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('jadwal') }}" required>
                                    <p class="mt-1 text-xs text-gray-500">Kapan ujian bisa mulai diakses</p>
                                    @error('jadwal')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Waktu Selesai -->
                                <div>
                                    <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-2">
                                        Batas Waktu Ujian <span class="text-red-500">*</span>
                                    </label>
                                    <input type="datetime-local" name="waktu_selesai" id="waktu_selesai"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        value="{{ old('waktu_selesai') }}" required>
                                    <p class="mt-1 text-xs text-gray-500">Kapan ujian tidak bisa lagi diakses (bebas diatur,
                                        tidak terikat durasi)</p>
                                    @error('waktu_selesai')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Kelas -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Peserta Ujian</h2>

                            <!-- Multiple Select untuk Kelas -->
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Pilih Kelas <span class="text-red-500">*</span>
                                </label>
                                <select name="kelas_id[]" id="kelas_id" multiple
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    @foreach($kelasMengajar as $kelas)
                                        <option value="{{ $kelas->id }}" 
                                            {{ in_array($kelas->id, old('kelas_id', [])) ? 'selected' : '' }}>
                                            {{ $kelas->nama_kelas }} - Tingkat {{ $kelas->tingkat }} ({{ $kelas->jurusan ?? 'Umum' }})
                                        </option>
                                    @endforeach
                                </select>

                                <p class="mt-2 text-sm text-gray-500">Pilih satu atau lebih kelas untuk ujian ini</p>
                                @error('kelas_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 rounded-b-lg">
                        <div class="flex items-center justify-between">
                            <a href="{{ route('guru.ujian.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16l-4-4m0 0l4-4m-4 4h18"></path>
                                </svg>
                                Kembali
                            </a>

                            <button type="submit"
                                class="inline-flex items-center px-6 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Simpan & Lanjutkan ke Soal
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        // === 1. Inisialisasi Select2 untuk Mata Pelajaran ===
        $('#mapel_id').select2({
            placeholder: "Pilih Mata Pelajaran",
            allowClear: false,
            theme: 'default'
        });

        // === 2. Inisialisasi Select2 untuk Kelas (Multiple Select) ===
        $('#kelas_id').select2({
            placeholder: "Pilih Kelas (bisa pilih lebih dari satu)",
            allowClear: true,
            multiple: true,
            theme: 'default',
            width: '100%'
        });

        // === 3. Validasi form submit ===
        $('#ujian-form').on('submit', function (e) {
            let hasError = false;

            // validasi kelas
            const selectedKelas = $('#kelas_id').val();
            if (!selectedKelas || selectedKelas.length === 0) {
                e.preventDefault();
                hasError = true;
                if (!$('#kelas-error').length) {
                    $('#kelas_id').parent().append('<p id="kelas-error" class="mt-1 text-sm text-red-600">Silakan pilih minimal satu kelas untuk ujian ini.</p>');
                }
            } else {
                $('#kelas-error').remove();
            }

            // validasi durasi
            const durasi_jam = parseInt($('input[name="durasi_jam"]').val()) || 0;
            const durasi_menit = parseInt($('input[name="durasi_menit"]').val()) || 0;

            if (durasi_jam === 0 && durasi_menit === 0) {
                e.preventDefault();
                hasError = true;
                if (!$('#durasi-error').length) {
                    $('input[name="durasi_menit"]').parent().parent().append('<p id="durasi-error" class="mt-1 text-sm text-red-600">Durasi ujian harus lebih dari 0 menit.</p>');
                }
                $('input[name="durasi_jam"]').addClass('border-red-500');
                $('input[name="durasi_menit"]').addClass('border-red-500');
            } else {
                $('#durasi-error').remove();
                $('input[name="durasi_jam"]').removeClass('border-red-500');
                $('input[name="durasi_menit"]').removeClass('border-red-500');
            }

            if (hasError) {
                showNotification('Mohon lengkapi semua field yang wajib diisi.', 'error');
                return false;
            }
        });

        // === 4. Fungsi notifikasi toast ===
        function showNotification(message, type = 'success') {
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
            const toast = $(`
                <div class="fixed top-4 right-4 z-50 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300">
                    <div class="flex items-center">
                        <span>${message}</span>
                        <button class="ml-4 text-white hover:text-gray-200" onclick="$(this).closest('div').addClass('translate-x-full').delay(300).queue(function(){$(this).remove();})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            `);

            $('body').append(toast);
            setTimeout(() => toast.removeClass('translate-x-full'), 100);
            setTimeout(() => toast.addClass('translate-x-full').delay(300).queue(function () { $(this).remove(); }), 3000);
        }
    });
</script>
@endpush

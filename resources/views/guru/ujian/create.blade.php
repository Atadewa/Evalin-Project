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
    document.addEventListener('DOMContentLoaded', function () {
        // === 1. Inisialisasi Select2 untuk Mata Pelajaran ===
        const mapelSelect = new Option2(document.getElementById('mapel_id'), {
            placeholder: "Pilih Mata Pelajaran",
            allowClear: false,
            theme: 'default'
        });

        // === 2. Inisialisasi Select2 untuk Kelas (Multiple Select) ===
        const kelasSelect = new Option2(document.getElementById('kelas_id'), {
            placeholder: "Pilih Kelas (bisa pilih lebih dari satu)",
            allowClear: true,
            multiple: true,
            theme: 'default',
            width: '100%'
        });

        // Helper function untuk Select2 initialization dengan vanilla JS
        function Option2(element, options) {
            // Wrapper untuk menggunakan Select2 dengan vanilla JS
            if (window.jQuery) {
                window.jQuery(element).select2(options);
            }
        }

        // === 3. Validasi form submit dengan SweetAlert ===
        const ujianForm = document.getElementById('ujian-form');
        ujianForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default submission
            
            let hasError = false;
            const form = this;

            // validasi kelas
            const kelasIdSelect = document.getElementById('kelas_id');
            const selectedKelas = Array.from(kelasIdSelect.selectedOptions).map(option => option.value);
            
            if (!selectedKelas || selectedKelas.length === 0) {
                hasError = true;
                const existingError = document.getElementById('kelas-error');
                if (!existingError) {
                    const errorElement = document.createElement('p');
                    errorElement.id = 'kelas-error';
                    errorElement.className = 'mt-1 text-sm text-red-600';
                    errorElement.textContent = 'Silakan pilih minimal satu kelas untuk ujian ini.';
                    kelasIdSelect.parentElement.appendChild(errorElement);
                }
            } else {
                const existingError = document.getElementById('kelas-error');
                if (existingError) {
                    existingError.remove();
                }
            }

            // validasi durasi
            const durasiJamInput = document.querySelector('input[name="durasi_jam"]');
            const durasiMenitInput = document.querySelector('input[name="durasi_menit"]');
            const durasi_jam = parseInt(durasiJamInput.value) || 0;
            const durasi_menit = parseInt(durasiMenitInput.value) || 0;

            if (durasi_jam === 0 && durasi_menit === 0) {
                hasError = true;
                const existingError = document.getElementById('durasi-error');
                if (!existingError) {
                    const errorElement = document.createElement('p');
                    errorElement.id = 'durasi-error';
                    errorElement.className = 'mt-1 text-sm text-red-600';
                    errorElement.textContent = 'Durasi ujian harus lebih dari 0 menit.';
                    durasiMenitInput.parentElement.parentElement.appendChild(errorElement);
                }
                durasiJamInput.classList.add('border-red-500');
                durasiMenitInput.classList.add('border-red-500');
            } else {
                const existingError = document.getElementById('durasi-error');
                if (existingError) {
                    existingError.remove();
                }
                durasiJamInput.classList.remove('border-red-500');
                durasiMenitInput.classList.remove('border-red-500');
            }

            // Jika ada error validasi, tampilkan SweetAlert error
            if (hasError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Formulir Belum Lengkap!',
                    text: 'Mohon lengkapi semua field yang wajib diisi sebelum melanjutkan.',
                    confirmButtonText: 'OK, Saya Mengerti',
                    confirmButtonColor: '#dc2626',
                    customClass: {
                        confirmButton: 'px-6 py-2 text-white font-medium rounded-lg',
                    }
                });
                return false;
            }

            // Jika validasi berhasil, langsung submit dengan loading
            Swal.fire({
                icon: 'info',
                title: 'Membuat Ujian...',
                text: 'Mohon tunggu sebentar, sistem sedang memproses data ujian Anda.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form dengan fetch API untuk handling response
            const formData = new FormData(form);
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => {
                if (response.ok) {
                    return response.json().catch(() => {
                        // Jika bukan JSON, redirect ke halaman sukses
                        window.location.href = response.url || "{{ route('guru.ujian.index') }}";
                        return null;
                    });
                } else {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
            })
            .then(data => {
                if (data) {
                    // Handle JSON response (success)
                    const namaUjian = document.querySelector('input[name="nama_ujian"]').value;
                    Swal.fire({
                        icon: 'success',
                        title: 'Ujian Berhasil Dibuat! 🎉',
                        html: `
                            <div class="text-center space-y-3">
                                <p class="text-lg">Ujian <strong>"${namaUjian}"</strong> telah berhasil dibuat.</p>
                                <p class="text-sm text-gray-600">Anda sekarang dapat menambahkan soal-soal untuk ujian ini.</p>
                            </div>
                        `,
                        confirmButtonText: '➤ Tambah Soal Sekarang',
                        confirmButtonColor: '#059669',
                        showCancelButton: true,
                        cancelButtonText: 'Kembali ke Daftar Ujian',
                        cancelButtonColor: '#6b7280',
                        customClass: {
                            confirmButton: 'px-6 py-2 text-white font-medium rounded-lg mr-2',
                            cancelButton: 'px-6 py-2 text-white font-medium rounded-lg',
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = data.redirect_url || "{{ route('guru.soal.create') }}";
                        } else {
                            window.location.href = "{{ route('guru.ujian.index') }}";
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Handle error scenarios
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Membuat Ujian!',
                    html: `
                        <div class="text-center space-y-3">
                            <p>Terjadi kesalahan saat memproses data ujian.</p>
                            <p class="text-sm text-gray-600">Error: ${error.message}</p>
                            <br>
                            <p class="text-sm">Silakan coba lagi atau hubungi administrator jika masalah berlanjut.</p>
                        </div>
                    `,
                    confirmButtonText: '🔄 Coba Lagi',
                    confirmButtonColor: '#dc2626',
                    showCancelButton: true,
                    cancelButtonText: 'Kembali',
                    cancelButtonColor: '#6b7280',
                    customClass: {
                        confirmButton: 'px-6 py-2 text-white font-medium rounded-lg mr-2',
                        cancelButton: 'px-6 py-2 text-white font-medium rounded-lg',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // User wants to try again, do nothing (stay on form)
                    } else {
                        window.location.href = "{{ route('guru.ujian.index') }}";
                    }
                });
            });
        });

        // === 4. Weather Alert saat memilih jadwal ujian ===
        const jadwalInput = document.getElementById('jadwal');
        let lastCheckedDate = null;
        
        jadwalInput.addEventListener('change', function() {
            const selectedDateTime = new Date(this.value);
            const selectedDate = selectedDateTime.toDateString();
            
            // Hanya cek weather jika tanggal berbeda dari yang sebelumnya
            if (selectedDate !== lastCheckedDate && this.value) {
                lastCheckedDate = selectedDate;
                checkWeatherForExamDate(selectedDateTime);
            }
        });

        function checkWeatherForExamDate(examDate) {
            // Tampilkan loading weather info
            const loadingToast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            loadingToast.fire({
                icon: 'info',
                title: 'Mengecek kondisi cuaca...'
            });

            // Fetch weather data
            fetch('/weather-api', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.weather) {
                    showWeatherAlert(data.weather, data.city, examDate);
                } else {
                    // Jika gagal mendapat data cuaca
                    const errorToast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    errorToast.fire({
                        icon: 'warning',
                        title: 'Tidak dapat mengecek cuaca saat ini'
                    });
                }
            })
            .catch(error => {
                console.error('Weather check error:', error);
            });
        }

        function showWeatherAlert(weather, city, examDate) {
            const temperature = weather.Temperature?.Metric?.Value;
            const weatherText = weather.WeatherText;
            const weatherIcon = weather.WeatherIcon;
            
            const examDateStr = examDate.toLocaleDateString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const examTimeStr = examDate.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });

            // Tentukan pesan berdasarkan kondisi cuaca
            let weatherAdvice = '';
            let alertIcon = 'info';
            let alertColor = '#3b82f6';

            if (weatherText && weatherText.toLowerCase().includes('rain') || 
                weatherText && weatherText.toLowerCase().includes('hujan')) {
                weatherAdvice = `
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-3 mt-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Perhatian: Cuaca Hujan</h3>
                                <div class="mt-2 text-sm text-yellow-700">
                                    <p>• Jaringan internet mungkin tidak stabil</p>
                                    <p>• Pastikan backup power/UPS tersedia</p>
                                    <p>• Siapkan rencana kontinjensi untuk siswa</p>
                                    <p>• Pertimbangkan perpanjangan waktu ujian jika diperlukan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                alertIcon = 'warning';
                alertColor = '#f59e0b';
            } else if (temperature && temperature > 32) {
                weatherAdvice = `
                    <div class="bg-red-50 border-l-4 border-red-400 p-3 mt-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Cuaca Sangat Panas</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <p>• Pastikan ruang ujian ber-AC atau ventilasi baik</p>
                                    <p>• Sediakan air minum untuk siswa</p>
                                    <p>• Monitor kondisi siswa selama ujian</p>
                                    <p>• Perangkat elektronik mungkin overheat</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                alertIcon = 'warning';
                alertColor = '#ef4444';
            } else if (temperature && temperature < 18) {
                weatherAdvice = `
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-3 mt-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">Cuaca Dingin</h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>• Pastikan ruang ujian hangat dan nyaman</p>
                                    <p>• Siswa mungkin memerlukan jaket atau selimut</p>
                                    <p>• Perangkat elektronik performa optimal di suhu dingin</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                alertIcon = 'info';
                alertColor = '#3b82f6';
            } else {
                weatherAdvice = `
                    <div class="bg-green-50 border-l-4 border-green-400 p-3 mt-3">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800">Kondisi Cuaca Ideal</h3>
                                <div class="mt-2 text-sm text-green-700">
                                    <p>• Suhu dan kondisi mendukung untuk pelaksanaan ujian</p>
                                    <p>• Lingkungan kondusif untuk konsentrasi siswa</p>
                                    <p>• Minimal gangguan dari faktor cuaca</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                alertIcon = 'success';
                alertColor = '#10b981';
            }

            Swal.fire({
                icon: alertIcon,
                title: `Informasi Cuaca untuk Ujian`,
                html: `
                    <div class="text-left space-y-3">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <h4 class="font-semibold text-gray-800">📅 ${examDateStr}</h4>
                                    <p class="text-sm text-gray-600">Pukul ${examTimeStr}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center gap-2">
                                        <img src="https://developer.accuweather.com/sites/default/files/${String(weatherIcon).padStart(2, '0')}-s.png" 
                                             alt="Weather" class="w-8 h-8" onerror="this.style.display='none'">
                                        <div>
                                            <div class="text-lg font-bold">${temperature}°C</div>
                                            <div class="text-xs text-gray-500">${city}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sm text-gray-700 mt-2">
                                <strong>Kondisi:</strong> ${weatherText}
                            </p>
                        </div>
                        ${weatherAdvice}
                    </div>
                `,
                confirmButtonText: 'Mengerti',
                confirmButtonColor: alertColor,
                customClass: {
                    confirmButton: 'px-6 py-2 text-white font-medium rounded-lg',
                    popup: 'max-w-lg'
                },
                width: '500px'
            });
        }
    });
</script>
@endpush

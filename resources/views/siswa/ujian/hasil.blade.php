@extends('layouts.app')

@push('scripts')
    <script>
        // Ensure sidebar stays expanded on ujian pages
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                // Force sidebar to be expanded for ujian pages
                sidebar.classList.remove('sidebar-collapsed');
                // Temporarily disable collapse state for this page
                localStorage.setItem('tempDisableSidebarCollapse', 'true');
            }
        });

        // Re-enable sidebar collapse when leaving the page
        window.addEventListener('beforeunload', function() {
            localStorage.removeItem('tempDisableSidebarCollapse');
        });
    </script>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <!-- Header Card -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold">{{ $ujian->nama_ujian }}</h1>
                        <p class="text-blue-100 mt-1">
                            {{ $ujian->mataPelajaran->nama_mapel }}
                        </p>
                    </div>
                    <div class="text-right">
                        <i class="fas fa-clipboard-check text-6xl text-blue-200 opacity-50"></i>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Total Soal -->
                    <div class="text-center bg-blue-50 rounded-lg p-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-list-ol text-blue-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm mb-1">Total Soal</p>
                        <h3 class="text-2xl font-bold text-blue-600">{{ $totalSoal }}</h3>
                    </div>

                    <!-- Soal Terjawab -->
                    <div class="text-center bg-green-50 rounded-lg p-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check-circle text-green-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm mb-1">Soal Terjawab</p>
                        <h3 class="text-2xl font-bold text-green-600">
                            {{ $soalTerjawab }}
                        </h3>
                    </div>

                    <!-- Nilai -->
                    <div class="text-center bg-purple-50 rounded-lg p-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-star text-purple-600"></i>
                        </div>
                        <p class="text-gray-600 text-sm mb-1">Nilai Akhir</p>
                        <h3 class="text-2xl font-bold text-purple-600">
                            {{ number_format($ujianSiswa->total_nilai, 1) }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Message -->
        @if (!$ujianSiswa->status_penilaian)
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500 text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-blue-800">
                            Ujian Berhasil Diselesaikan!
                        </h3>
                        <p class="text-blue-700 mt-1">
                            Jawaban Anda telah tersimpan. Nilai akan ditampilkan setelah guru
                            melakukan konfirmasi penilaian.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Ringkasan Jawaban -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-list-alt mr-3 text-blue-500"></i>
                    Ringkasan Jawaban Anda
                </h2>
            </div>
            <div class="overflow-hidden">
                @if ($jawabanSiswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Soal
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jawaban Anda
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Pembahasan
                                    </th>
                                    @if ($ujianSiswa->status_penilaian)
                                        <th
                                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Nilai
                                        </th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($jawabanSiswa as $index => $jawaban)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center w-8 h-8 bg-blue-100 rounded-full">
                                                <span class="text-sm font-medium text-blue-600">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs">
                                                <div class="line-clamp-3"
                                                    title="{{ strip_tags($jawaban->soal->pertanyaan) }}">
                                                    {!! Str::limit(strip_tags($jawaban->soal->pertanyaan), 100) !!}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($jawaban->opsi_jawaban_id)
                                                <!-- Jawaban Pilihan Ganda -->
                                                <div class="flex items-center">
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                                                        {{ $jawaban->opsiJawaban->label }}
                                                    </span>
                                                    <span class="text-sm text-gray-900">
                                                        {{ $jawaban->opsiJawaban->isi_opsi }}
                                                    </span>
                                                </div>
                                            @else
                                                <!-- Jawaban Essay -->
                                                <div class="text-sm text-gray-600 max-w-md">
                                                    <div class="line-clamp-3">
                                                        {{ Str::limit($jawaban->jawaban_teks ?? $jawaban->jawaban_dipilih, 150) }}
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if (!empty($jawaban->soal->pembahasan))
                                                <div class="text-sm text-gray-700 max-w-md">
                                                    {{ Str::limit(strip_tags($jawaban->soal->pembahasan), 200) }}
                                                </div>
                                            @else
                                                <div class="text-sm text-gray-400">-</div>
                                            @endif
                                        </td>
                                        @if ($ujianSiswa->status_penilaian)
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                @if ($jawaban->skor_diperoleh !== null)
                                                    <span
                                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        {{ number_format($jawaban->skor_diperoleh, 1) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-exclamation-triangle text-6xl text-yellow-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            Tidak ada jawaban yang tersimpan
                        </h3>
                        <p class="text-gray-500">
                            Sepertinya terjadi masalah saat menyimpan jawaban Anda.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="text-center">
            <a href="{{ route('siswa.ujian.index') }}"
                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Daftar Ujian
            </a>
        </div>
    </div>
@endsection

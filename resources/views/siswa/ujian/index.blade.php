@extends('layouts.app')

@push('styles')
    <style>
        /* Prevent page overflow on siswa ujian page */
        .space-y-6 {
            max-width: 100%;
            overflow-x: hidden;
        }

        .space-y-6>* {
            max-width: 100%;
        }

        /* Ensure table container doesn't overflow */
        .table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: visible;
        }
    </style>
@endpush

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
    <div class="space-y-4 sm:space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0">
                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                    Daftar Ujian
                </h1>
                <div id="current-time"
                    class="text-xs sm:text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 px-3 sm:px-4 py-2 rounded-lg flex items-center space-x-2 self-start sm:self-auto">
                    <i class="fas fa-clock text-blue-600 flex-shrink-0"></i>
                    <span id="time-text" class="break-words"></span>
                </div>
            </div>
        </div>

        <!-- Ujian Table -->
        <div class="bg-white rounded-lg shadow">
            @if ($ujians->isEmpty())
                <div class="p-4 sm:p-6">
                    <div class="text-center py-8 sm:py-12">
                        <div class="mx-auto h-16 w-16 sm:h-24 sm:w-24 text-gray-400 mb-3 sm:mb-4">
                            <i class="fas fa-clipboard-list text-4xl sm:text-6xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                            Belum ada ujian
                        </h3>
                        <p class="text-sm sm:text-base text-gray-500 px-4">
                            Belum ada ujian yang tersedia untuk kelas Anda saat ini.
                        </p>
                    </div>
                </div>
            @else
                <div class="table-container">
                    <x-table :headers="[
                        'No',
                        'Mata Pelajaran',
                        'Guru',
                        'Waktu Mulai',
                        'Waktu Selesai',
                        'Nama Ujian',
                        'Nilai 1',
                        'Nilai 2',
                        'Status',
                        'Aksi',
                    ]" :searchableColumns="[1, 2, 5]" :sortableColumns="[0, 1, 2, 3, 4, 5, 6, 7]">
                        @foreach ($ujians as $index => $ujian)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $index + 1 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-book text-blue-600"></i>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $ujian->mataPelajaran->nama_mapel ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <i class="fas fa-user text-gray-600 text-xs"></i>
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm text-gray-900">
                                                {{ $ujian->Guru->user->name ?? 'Tidak diketahui' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($ujian->jadwal)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($ujian->jadwal)->format('H:i') }}
                                        WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->translatedFormat('l, d M Y') }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format('H:i') }}
                                        WIB
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $ujian->nama_ujian }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-blue-600">
                                        {{ $ujian->nilai1 ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-green-600">
                                        {{ $ujian->nilai2 ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $ujianSiswaStatus = $ujian->ujianSiswa->first()->status ?? 'incoming';
                                    @endphp

                                    @if ($ujianSiswaStatus == 'incoming')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>
                                            Coming Soon
                                        </span>
                                    @elseif ($ujianSiswaStatus == 'ongoing')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-play mr-1"></i>
                                            Sedang Berlangsung
                                        </span>
                                    @elseif ($ujianSiswaStatus == 'selesai')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>
                                            Selesai
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times mr-1"></i>
                                            Waktu Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if ($ujianSiswaStatus == 'ongoing')
                                        <a href="{{ route('siswa.ujian.show', $ujian->id) }}"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                            <i class="fas fa-pen mr-2"></i>
                                            Mengerjakan
                                        </a>
                                    @else
                                        <button disabled
                                            class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                                            <i class="fas fa-pen mr-2"></i>
                                            Mengerjakan
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function updateTime() {
            const now = new Date();

            const hari = now.toLocaleDateString('id-ID', {
                weekday: 'long'
            });
            const tanggal = now.toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
            const jam = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
            });

            document.getElementById('time-text').textContent =
                $ {
                    hari
                }, $ {
                    tanggal
                } - $ {
                    jam
                };
        }

        setInterval(updateTime, 1000);
        updateTime();
    </script>
@endsection

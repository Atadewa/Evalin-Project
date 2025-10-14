@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content Area -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-t-lg">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                            <div>
                                <h1 class="text-xl font-bold">{{ $ujian->nama_ujian }}</h1>
                                <p class="text-blue-100 text-sm">{{ $ujian->mataPelajaran->nama_mapel ?? 'Mata Pelajaran' }}</p>
                            </div>
                            <div class="bg-white bg-opacity-20 px-4 py-2 rounded-lg">
                                <div id="timer" class="text-white font-mono font-bold text-lg"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <form id="soal-form">
                            <input type="hidden" name="ujian_id" value="{{ $ujian->id }}">
                            <div id="soal-container">
                                @foreach ($ujian->soals as $index => $soal)
                                    <div class="soal-item" data-soal="{{ $soal->id }}" data-index="{{ $index }}"
                                        style="display: {{ $index == 0 ? 'block' : 'none' }};">
                                        <div class="mb-6">
                                            <div class="flex items-center space-x-3 mb-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                                        <span class="text-blue-600 font-bold text-sm">{{ $index + 1 }}</span>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h3 class="text-lg font-semibold text-gray-900">Soal {{ $index + 1 }}</h3>
                                                    <div class="text-sm text-gray-500">{{ count($ujian->soals) }} soal total</div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                                <div class="prose max-w-none text-gray-900">
                                                    {!! $soal->pertanyaan !!}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-6">
                                            <label for="jawaban_{{ $soal->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                                <i class="fas fa-edit mr-2 text-blue-500"></i>
                                                Jawaban Anda:
                                            </label>
                                            <textarea
                                                class="w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none transition-colors duration-200"
                                                id="jawaban_{{ $soal->id }}"
                                                rows="6"
                                                placeholder="Ketikkan jawaban Anda di sini...">{{ $soal->jawaban->jawaban_teks ?? '' }}</textarea>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                                <button type="button"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                        onclick="prevSoal()">
                                    <i class="fas fa-chevron-left mr-2"></i>
                                    Sebelumnya
                                </button>
                                <button type="button"
                                        id="next-btn"
                                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                                        onclick="nextSoal()">
                                    Selanjutnya
                                    <i class="fas fa-chevron-right ml-2"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg sticky top-6">
                    <div class="bg-gradient-to-r from-gray-600 to-gray-700 text-white p-4 rounded-t-lg">
                        <h2 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-list-ol mr-2"></i>
                            Navigasi Soal
                        </h2>
                        <p class="text-gray-200 text-sm mt-1">{{ count($ujian->soals) }} soal total</p>
                    </div>
                    <div class="p-4">
                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Progress</span>
                                <span id="progress-text">0/{{ count($ujian->soals) }}</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div id="progress-bar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Soal Navigation Grid -->
                        <div class="grid grid-cols-5 gap-2" id="nomor-soal-nav">
                            @foreach ($ujian->soals as $index => $soal)
                                @php
                                    $jawabanAda = !empty($soal->jawaban->jawaban_teks);
                                @endphp
                                <button type="button"
                                    class="nomor-soal-btn w-10 h-10 text-sm font-medium rounded-lg border-2 transition-all duration-200 {{ $jawabanAda ? 'bg-green-100 border-green-500 text-green-700 hover:bg-green-200' : 'bg-gray-50 border-gray-300 text-gray-600 hover:bg-gray-100' }}"
                                    onclick="goToSoal({{ $index }})"
                                    id="nomor-btn-{{ $soal->id }}"
                                    title="Soal {{ $index + 1 }} {{ $jawabanAda ? '- Sudah dijawab' : '- Belum dijawab' }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>

                        <!-- Legend -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-xs text-gray-600 space-y-2">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-green-100 border border-green-500 rounded mr-2"></div>
                                    <span>Sudah dijawab</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-gray-50 border border-gray-300 rounded mr-2"></div>
                                    <span>Belum dijawab</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-4 h-4 bg-blue-100 border-2 border-blue-500 rounded mr-2"></div>
                                    <span>Soal aktif</span>
                                </div>
                            </div>
                        </div>

                        <!-- Finish Button -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <button type="button"
                                    class="w-full inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200"
                                    onclick="finishExam()">
                                <i class="fas fa-check-circle mr-2"></i>
                                Selesaikan Ujian
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ujianId = {{ $ujian->id }};
        let currentIndex = 0;
        const soalItems = document.querySelectorAll('.soal-item');
        const nextBtn = document.getElementById('next-btn');

        function showSoal(index) {
            soalItems.forEach((el, i) => el.style.display = i === index ? 'block' : 'none');

            // Update navigation button states
            const navButtons = document.querySelectorAll('.nomor-soal-btn');
            navButtons.forEach((btn, i) => {
                if (i === index) {
                    btn.classList.add('bg-blue-100', 'border-blue-500', 'text-blue-700');
                    btn.classList.remove('bg-green-100', 'border-green-500', 'text-green-700', 'bg-gray-50', 'border-gray-300', 'text-gray-600');
                } else {
                    btn.classList.remove('bg-blue-100', 'border-blue-500', 'text-blue-700');
                    // Restore original state based on whether answered or not
                    const soalId = btn.id.replace('nomor-btn-', '');
                    const textarea = document.getElementById(`jawaban_${soalId}`);
                    if (textarea && textarea.value.trim() !== '') {
                        btn.classList.add('bg-green-100', 'border-green-500', 'text-green-700');
                        btn.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-600');
                    } else {
                        btn.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-600');
                        btn.classList.remove('bg-green-100', 'border-green-500', 'text-green-700');
                    }
                }
            });

            if (index === soalItems.length - 1) {
                nextBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Selesaikan';
            } else {
                nextBtn.innerHTML = 'Selanjutnya<i class="fas fa-chevron-right ml-2"></i>';
            }

            updateProgress();
        }

        function updateProgress() {
            const answeredCount = document.querySelectorAll('textarea').length;
            let actualAnswered = 0;

            document.querySelectorAll('textarea').forEach(textarea => {
                if (textarea.value.trim() !== '') {
                    actualAnswered++;
                }
            });

            const totalSoal = soalItems.length;
            const progressPercent = (actualAnswered / totalSoal) * 100;

            document.getElementById('progress-bar').style.width = progressPercent + '%';
            document.getElementById('progress-text').textContent = actualAnswered + '/' + totalSoal;
        }

        function getCurrentSoalId() {
            const currentSoal = document.querySelector(`.soal-item[data-index="${currentIndex}"]`);
            if (!currentSoal) return null;

            const soalId = currentSoal.getAttribute('data-soal');
            return soalId;
        }

        function goToSoal(index) {
            currentIndex = index;
            showSoal(index);
        }

        function nextSoal() {
            const soalId = getCurrentSoalId();
            submitJawaban(soalId);

            if (currentIndex < soalItems.length - 1) {
                currentIndex++;
                showSoal(currentIndex);
            } else {
                Swal.fire({
                    title: 'Selesaikan Ujian?',
                    text: "Anda yakin ingin menyelesaikan ujian ini sekarang?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Selesaikan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Tampilkan loading screen saat LLM mengoreksi
                        Swal.fire({
                            title: 'Mengoreksi Jawaban...',
                            html: `
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                                    <p class="text-gray-600">AI sedang mengoreksi jawaban essay Anda</p>
                                    <p class="text-sm text-gray-500">Mohon tunggu, proses ini membutuhkan waktu...</p>
                                </div>
                            `,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch("{{ route('siswa.ujian.selesaikan') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ujian_id: ujianId
                            })
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Ujian Selesai',
                                    text: 'Jawaban Anda telah disimpan dan dinilai.',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                setTimeout(() => {
                                    window.location.href = "{{ route('siswa.ujian.hasil', $ujian->id) }}";
                                }, 2000);
                            }
                        }).catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyelesaikan ujian. Silakan coba lagi.',
                                confirmButtonText: 'OK'
                            });
                        });
                    }
                });
            }
        }

        function prevSoal() {
            if (currentIndex > 0) {
                const soalId = getCurrentSoalId();
                submitJawaban(soalId);

                currentIndex--;
                showSoal(currentIndex);
            }
        }

        function submitJawaban(soalId) {
            const textarea = document.getElementById(`jawaban_${soalId}`);
            const jawaban = textarea.value;

            fetch("{{ route('siswa.ujian.simpan-jawaban') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        soal_id: soalId,
                        ujian_id: "{{ $ujian->id }}",
                        jawaban_teks: jawaban
                    })
                }).then(res => res.json())
                .then(data => {
                    const btn = document.getElementById(`nomor-btn-${soalId}`);
                    if (jawaban.trim() !== "") {
                        btn.classList.remove('bg-gray-50', 'border-gray-300', 'text-gray-600');
                        btn.classList.add('bg-green-100', 'border-green-500', 'text-green-700');
                        btn.title = `Soal ${btn.textContent} - Sudah dijawab`;
                    } else {
                        btn.classList.remove('bg-green-100', 'border-green-500', 'text-green-700');
                        btn.classList.add('bg-gray-50', 'border-gray-300', 'text-gray-600');
                        btn.title = `Soal ${btn.textContent} - Belum dijawab`;
                    }
                    updateProgress();
                });
        }

        const endTime = new Date("{{ $endTime->format('Y-m-d H:i:s') }}").getTime();
        const timerEl = document.getElementById("timer");

        const countdown = setInterval(() => {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance <= 0) {
                clearInterval(countdown);
                Swal.fire({
                    icon: 'warning',
                    title: 'Waktu Habis!',
                    text: 'Ujian diselesaikan otomatis.',
                    showConfirmButton: false,
                    timer: 3000
                });

                setTimeout(() => {
                    // Tampilkan loading screen saat LLM mengoreksi
                    Swal.fire({
                        title: 'Mengoreksi Jawaban...',
                        html: `
                            <div class="flex flex-col items-center space-y-4">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
                                <p class="text-gray-600">AI sedang mengoreksi jawaban essay Anda</p>
                                <p class="text-sm text-gray-500">Ujian selesai karena waktu habis, mohon tunggu...</p>
                            </div>
                        `,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    fetch("{{ route('siswa.ujian.selesaikan') }}", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            ujian_id: ujianId
                        })
                    }).then(response => response.json()).then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Ujian Selesai',
                                text: 'Jawaban Anda telah disimpan dan dinilai.',
                                showConfirmButton: false,
                                timer: 2000
                            });

                            setTimeout(() => {
                                window.location.href = "{{ route('siswa.ujian.hasil', $ujian->id) }}";
                            }, 2000);
                        }
                    }).catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Gagal menyelesaikan ujian. Silakan refresh halaman.',
                            confirmButtonText: 'OK'
                        });
                    });
                }, 3000);
            } else {
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                const formatTime = (time) => String(time).padStart(2, '0');

                timerEl.innerHTML =
                `<i class="fas fa-clock mr-2"></i>${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}`;
            }
        }, 1000);

        function finishExam() {
            Swal.fire({
                title: 'Selesaikan Ujian?',
                text: "Anda yakin ingin menyelesaikan ujian ini sekarang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Save current answer first
                    const soalId = getCurrentSoalId();
                    if (soalId) {
                        submitJawaban(soalId);
                    }

                    setTimeout(() => {
                        // Tampilkan loading screen saat LLM mengoreksi
                        Swal.fire({
                            title: 'Mengoreksi Jawaban...',
                            html: `
                                <div class="flex flex-col items-center space-y-4">
                                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                                    <p class="text-gray-600">AI sedang mengoreksi jawaban essay Anda</p>
                                    <p class="text-sm text-gray-500">Mohon tunggu, proses ini membutuhkan waktu...</p>
                                </div>
                            `,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        fetch("{{ route('siswa.ujian.selesaikan') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                ujian_id: ujianId
                            })
                        }).then(response => response.json()).then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Ujian Selesai',
                                    text: 'Jawaban Anda telah disimpan dan dinilai.',
                                    showConfirmButton: false,
                                    timer: 2000
                                });

                                setTimeout(() => {
                                    window.location.href = "{{ route('siswa.ujian.hasil', $ujian->id) }}";
                                }, 2000);
                            }
                        }).catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: 'Gagal menyelesaikan ujian. Silakan coba lagi.',
                                confirmButtonText: 'OK'
                            });
                        });
                    }, 500);
                }
            });
        }

        // Initialize progress on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateProgress();
            showSoal(0);
        });
    </script>
@endsection

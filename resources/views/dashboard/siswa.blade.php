@extends("layouts.app")

@section("content")
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Hero Section -->
    <div
      class="bg-gradient-to-br from-primary via-blue-600 to-purple-600 rounded-xl text-white p-8 shadow-lg relative overflow-hidden"
    >
      <!-- Background decoration -->
      <div class="absolute inset-0 bg-black bg-opacity-10"></div>
      <div
        class="absolute -top-10 -right-10 w-40 h-40 bg-white bg-opacity-10 rounded-full blur-3xl"
      ></div>
      <div
        class="absolute -bottom-10 -left-10 w-32 h-32 bg-white bg-opacity-5 rounded-full blur-2xl"
      ></div>

      <div
        class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6"
      >
        <div class="flex-1">
          <div class="text-3xl lg:text-4xl font-bold mb-2">
            Selamat datang,
            <span id="typed-name" class="text-yellow-300">
              {{ auth()->user()->name }}
            </span>
            !
          </div>

          @php
            $siswa = auth()->user()->siswa;
            $kelas = $siswa ? $siswa->kelas : null;
          @endphp

          <div class="mb-4">
            <div
              class="inline-flex items-center gap-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg px-4 py-2"
            >
              <i class="bi bi-mortarboard text-yellow-300"></i>
              <span class="text-white font-medium">
                @if ($kelas)
                  Tingkat {{ $kelas->tingkat }} - Kelas
                  {{ $kelas->nama_kelas }}
                @else
                    Kelas belum ditentukan
                @endif
              </span>
            </div>
          </div>

          <p class="text-blue-100 text-lg mb-6 max-w-2xl">
            Siap belajar hari ini? Periksa jadwal ujian terbaru dan persiapkan
            diri untuk meraih prestasi terbaik.
          </p>

          <div class="flex flex-col sm:flex-row gap-3">
            <a
              href="{{ route("siswa.ujian.index") }}"
              class="inline-flex items-center gap-2 bg-white text-primary px-6 py-3 rounded-lg font-medium hover:bg-gray-100 transition-colors duration-200"
            >
              <i class="bi bi-calendar3"></i>
              Lihat Jadwal Ujian
            </a>
          </div>
        </div>

        <div class="flex-shrink-0">
          <div
            class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-6 text-center min-w-[180px]"
          >
            <div class="text-blue-100 text-sm mb-2">Ujian Akan Datang</div>
            <div
              class="text-4xl font-bold text-yellow-300 mb-4"
              id="exam-counter"
              data-count="{{ $ujianMendatang ?? 0 }}"
            >
              0
            </div>
            <a
              href="{{ route("siswa.ujian.index") }}"
              class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-300 transition-colors duration-200"
            >
              Lihat Detail
            </a>
          </div>
        </div>
      </div>
    </div>

    {{-- Main Content Grid --}}
    {{--
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 space-y-6">
      <!-- Quick Actions -->
      <div
      class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300"
      >
      <div class="flex items-center justify-between mb-6">
      <div>
      <div class="text-sm text-gray-500">Aksi Cepat</div>
      <h3 class="text-xl font-bold text-secondary">Pilih Aktivitas</h3>
      </div>
      <div
      class="w-12 h-12 bg-blue-100 text-primary rounded-xl flex items-center justify-center"
      >
      <i class="bi bi-rocket-takeoff text-xl"></i>
      </div>
      </div>
      
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <a
      href="{{ url("#") }}"
      class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
      >
      <div
      class="w-10 h-10 bg-blue-100 text-primary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
      >
      <i class="bi bi-pencil-square"></i>
      </div>
      <span class="text-sm font-medium text-gray-900">Latihan</span>
      </a>
      
      <a
      href="{{ url("#") }}"
      class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
      >
      <div
      class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
      >
      <i class="bi bi-bar-chart"></i>
      </div>
      <span class="text-sm font-medium text-gray-900">Nilai</span>
      </a>
      
      <a
      href="{{ url("#") }}"
      class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
      >
      <div
      class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
      >
      <i class="bi bi-calendar3"></i>
      </div>
      <span class="text-sm font-medium text-gray-900">Jadwal</span>
      </a>
      
      <a
      href="{{ url("#") }}"
      class="flex flex-col items-center gap-2 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
      >
      <div
      class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
      >
      <i class="bi bi-question-circle"></i>
      </div>
      <span class="text-sm font-medium text-gray-900">Bantuan</span>
      </a>
      </div>
      </div>
      
      <!-- Study Streak -->
      <div
      class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300"
      >
      <div class="flex items-center justify-between mb-6">
      <div>
      <div class="text-sm text-gray-500">Study Streak</div>
      <h3 class="text-xl font-bold text-secondary">
      Konsistensi Belajar
      </h3>
      <p class="text-sm text-gray-600 mt-1">
      Berapa hari berturut-turut Anda belajar
      </p>
      </div>
      <div
      class="w-12 h-12 bg-red-100 text-red-500 rounded-xl flex items-center justify-center"
      >
      <i class="bi bi-fire text-xl"></i>
      </div>
      </div>
      
      @php
      $streak = $studyStreak ?? 65;
      @endphp
      
      <div class="flex items-center gap-6">
      <div class="text-center">
      <div class="text-3xl font-bold text-secondary">
      {{ $streak }}%
      </div>
      <div class="text-sm text-gray-500">dari target</div>
      </div>
      
      <div class="flex-1">
      <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
      <div
      class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-1000 ease-out"
      style="width: {{ $streak }}%"
      ></div>
      </div>
      <div class="text-sm text-gray-600 mt-2">
      Pertahankan kebiasaan 21 hari berturut-turut
      </div>
      </div>
      </div>
      </div>
      
      <!-- Study Tips -->
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
      <div>
      <div class="text-sm text-gray-500">Tips Belajar</div>
      <h3 class="text-xl font-bold text-secondary">Cepat & Efektif</h3>
      </div>
      <div class="text-sm text-gray-500">
      Butuh ide? Lihat tips harian
      </div>
      </div>
      
      <div
      x-data="{
      currentTip: 0,
      tips: [
      {
      title: 'Pomodoro: Fokus 25 menit',
      desc: 'Kerjakan sesi singkat, istirahat sebentar. Ulangi beberapa kali untuk hasil maksimal.',
      icon: 'bi-clock-history',
      color: 'blue',
      },
      {
      title: 'Ulangi aktif',
      desc: 'Jelaskan kembali materi dengan kata-katamu sendiri untuk memperkuat ingatan.',
      icon: 'bi-chat-left-text',
      color: 'green',
      },
      {
      title: 'Latihan dengan soal',
      desc: 'Kerjakan soal sebanyak mungkin untuk mengenali pola ujian nyata.',
      icon: 'bi-file-earmark-text',
      color: 'purple',
      },
      ],
      }"
      x-init="
      setInterval(() => {
      currentTip = (currentTip + 1) % tips.length
      }, 5000)
      "
      >
      <div class="relative overflow-hidden">
      <template x-for="(tip, index) in tips" :key="index">
      <div
      x-show="currentTip === index"
      x-transition:enter="transition-transform duration-500 ease-in-out"
      x-transition:enter-start="transform translate-x-full"
      x-transition:enter-end="transform translate-x-0"
      x-transition:leave="transition-transform duration-500 ease-in-out"
      x-transition:leave-start="transform translate-x-0"
      x-transition:leave-end="transform -translate-x-full"
      class="flex items-center gap-4 p-4 rounded-lg"
      :class="{
      'bg-blue-50': tip.color === 'blue',
      'bg-green-50': tip.color === 'green',
      'bg-purple-50': tip.color === 'purple'
      }"
      >
      <div
      class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center"
      :class="{
      'bg-blue-100 text-blue-600': tip.color === 'blue',
      'bg-green-100 text-green-600': tip.color === 'green',
      'bg-purple-100 text-purple-600': tip.color === 'purple'
      }"
      >
      <i :class="tip.icon" class="text-xl"></i>
      </div>
      <div class="flex-1">
      <h4
      class="font-semibold text-gray-900 mb-1"
      x-text="tip.title"
      ></h4>
      <p class="text-sm text-gray-600" x-text="tip.desc"></p>
      </div>
      </div>
      </template>
      </div>
      
      <!-- Indicators -->
      <div class="flex justify-center gap-2 mt-4">
      <template x-for="(tip, index) in tips" :key="index">
      <button
      @click="currentTip = index"
      class="w-2 h-2 rounded-full transition-all duration-300"
      :class="currentTip === index ? 'bg-primary w-6' : 'bg-gray-300'"
      ></button>
      </template>
      </div>
      </div>
      </div>
      </div>
      
      <!-- Right Sidebar: Summary -->
      <div class="space-y-6">
      <!-- Activity Summary -->
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <div class="flex items-center justify-between mb-6">
      <div>
      <div class="text-sm text-gray-500">Ringkasan</div>
      <h3 class="text-xl font-bold text-secondary">
      Sekilas Aktivitas
      </h3>
      </div>
      <div class="text-sm text-gray-500">Terbaru</div>
      </div>
      
      <div class="space-y-4">
      <div
      class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
      >
      <div class="flex items-center gap-3">
      <div
      class="w-8 h-8 bg-green-100 text-green-600 rounded-lg flex items-center justify-center"
      >
      <i class="bi bi-check-circle"></i>
      </div>
      <div>
      <div class="font-medium text-gray-900">Latihan Hari Ini</div>
      <div class="text-sm text-gray-500">Durasi 45 menit</div>
      </div>
      </div>
      <span
      class="px-2 py-1 bg-green-100 text-green-800 text-sm font-medium rounded-full"
      >
      Selesai
      </span>
      </div>
      
      <div
      class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
      >
      <div class="flex items-center gap-3">
      <div
      class="w-8 h-8 bg-blue-100 text-primary rounded-lg flex items-center justify-center"
      >
      <i class="bi bi-trophy"></i>
      </div>
      <div>
      <div class="font-medium text-gray-900">Nilai Terakhir</div>
      <div class="text-sm text-gray-500">Matematika</div>
      </div>
      </div>
      <div class="text-right">
      <div class="font-bold text-gray-900">82</div>
      <div class="text-sm text-gray-500">/100</div>
      </div>
      </div>
      
      <div
      class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
      >
      <div class="flex items-center gap-3">
      <div
      class="w-8 h-8 bg-yellow-100 text-yellow-600 rounded-lg flex items-center justify-center"
      >
      <i class="bi bi-calendar-event"></i>
      </div>
      <div>
      <div class="font-medium text-gray-900">Ujian Mendatang</div>
      <div class="text-sm text-gray-500">Periksa jadwal</div>
      </div>
      </div>
      <div class="font-bold text-gray-900">
      {{ $ujianMendatang ?? 0 }}
      </div>
      </div>
      </div>
      
      <div class="mt-6 text-center">
      <a
      href="{{ url("#") }}"
      class="inline-flex items-center gap-2 text-primary hover:text-blue-700 font-medium transition-colors duration-200"
      >
      <span>Lihat semua aktivitas</span>
      <i class="bi bi-arrow-right"></i>
      </a>
      </div>
      </div>
      
      <!-- Study Progress -->
      <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
      <h3 class="text-lg font-bold text-secondary mb-4">
      Progress Belajar
      </h3>
      
      <div class="space-y-4">
      <div>
      <div class="flex justify-between items-center mb-2">
      <span class="text-sm font-medium text-gray-700">
      Matematika
      </span>
      <span class="text-sm text-gray-500">85%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
      <div
      class="bg-blue-600 h-2 rounded-full"
      style="width: 85%"
      ></div>
      </div>
      </div>
      
      <div>
      <div class="flex justify-between items-center mb-2">
      <span class="text-sm font-medium text-gray-700">
      Bahasa Indonesia
      </span>
      <span class="text-sm text-gray-500">72%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
      <div
      class="bg-green-600 h-2 rounded-full"
      style="width: 72%"
      ></div>
      </div>
      </div>
      
      <div>
      <div class="flex justify-between items-center mb-2">
      <span class="text-sm font-medium text-gray-700">IPA</span>
      <span class="text-sm text-gray-500">68%</span>
      </div>
      <div class="w-full bg-gray-200 rounded-full h-2">
      <div
      class="bg-purple-600 h-2 rounded-full"
      style="width: 68%"
      ></div>
      </div>
      </div>
      </div>
      </div>
      </div>
      </div>
    --}}
  </div>

  {{-- JavaScript untuk animasi dan interaksi --}}
  <script>
  document.addEventListener('DOMContentLoaded', function () {
    // Typed name effect
    (function () {
      const el = document.getElementById('typed-name');
      if (!el) return;
      const full = el.textContent.trim();
      el.textContent = '';
      let i = 0;
      const speed = 100;
      (function typeChar() {
        if (i < full.length) {
          el.textContent += full.charAt(i++);
          setTimeout(typeChar, speed);
        }
      })();
    })();

    // Animated counter for exam countdown
    (function () {
      const counter = document.getElementById('exam-counter');
      if (!counter) return;
      const target = parseInt(counter.getAttribute('data-count')) || 0;
      let current = 0;
      const duration = 1200;
      const increment = target / (duration / 50);

      const timer = setInterval(function () {
        current += increment;
        if (current >= target) {
          counter.textContent = target;
          clearInterval(timer);
        } else {
          counter.textContent = Math.floor(current);
        }
      }, 50);
    })();

    {{-- Progress bar animations --}}
    {{--
      setTimeout(() => {
      document.querySelectorAll('[style*="width:"]').forEach((bar) => {
      bar.style.transition = 'width 1.5s ease-out';
      });
      }, 500);
    --}}
  });
  </script>
@endsection

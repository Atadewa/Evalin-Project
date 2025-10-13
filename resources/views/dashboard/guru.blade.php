@extends("layouts.app")

@section("content")
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Welcome Header -->
    <div
      class="bg-gradient-to-r from-primary to-blue-600 rounded-xl text-white p-8 shadow-lg"
    >
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl lg:text-4xl font-bold mb-2">
            Selamat datang, {{ auth()->user()->name }}!
          </h1>
          <p class="text-blue-100 text-lg">
            Anda login sebagai
            <span class="font-semibold">Guru</span>
          </p>
          <div class="mt-4 flex items-center gap-2 text-blue-100">
            <i class="bi bi-calendar3"></i>
            <span>{{ now()->format("l, d M Y") }}</span>
          </div>
        </div>
        <div class="hidden lg:block">
          <div
            class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center"
          >
            <i class="bi bi-person-workspace text-4xl"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Total Ujian Card -->
      <div
        class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group"
      >
        <div class="flex items-center justify-between mb-4">
          <div
            class="w-12 h-12 bg-primary bg-opacity-10 text-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
          >
            <i class="bi bi-clock-history text-xl"></i>
          </div>
          <div class="text-right">
            <div class="text-2xl lg:text-3xl font-bold text-secondary">
              {{ $ujianSaya }}
            </div>
            <div class="text-sm text-gray-500">Ujian</div>
          </div>
        </div>
        <div>
          <h3 class="font-medium text-gray-900 mb-1">
            Total Ujian yang Anda Buat
          </h3>
          <p class="text-sm text-gray-500">
            Ujian yang telah Anda buat dan kelola
          </p>
        </div>
        <div class="mt-4 flex items-center justify-between">
          <div class="flex items-center gap-2 text-sm text-blue-600">
            <i class="bi bi-arrow-up-right"></i>
            <span>Aktif</span>
          </div>
          <a href="{{ route('guru.ujian.index') }}" class="text-primary hover:text-blue-700 text-sm font-medium">
            Lihat Detail →
          </a>
        </div>
        <!-- Bottom accent line -->
        <div
          class="absolute bottom-0 left-0 right-0 h-1 bg-primary transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 rounded-b-xl"
        ></div>
      </div>

      <!-- Total Siswa Card -->
      <div
        class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden"
      >
        <div class="flex items-center justify-between mb-4">
          <div
            class="w-12 h-12 bg-primary bg-opacity-10 text-primary rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300"
          >
            <i class="bi bi-person-lines-fill text-xl"></i>
          </div>
          <div class="text-right">
            <div class="text-2xl lg:text-3xl font-bold text-secondary">
              {{ $totalSiswa }}
            </div>
            <div class="text-sm text-gray-500">Siswa</div>
          </div>
        </div>
        <div>
          <h3 class="font-medium text-gray-900 mb-1">Total Siswa</h3>
          <p class="text-sm text-gray-500">Siswa yang terdaftar dalam sistem</p>
        </div>
        <div class="mt-4 flex items-center justify-between">
          <div class="flex items-center gap-2 text-sm text-blue-600">
            <i class="bi bi-people"></i>
            <span>Terdaftar</span>
          </div>
          <a class="text-primary hover:text-blue-700 text-sm font-medium" href="{{ route('guru.siswa.index') }}">
            Lihat Detail →
          </a>
        </div>
        <!-- Bottom accent line -->
        <div
          class="absolute bottom-0 left-0 right-0 h-1 bg-primary transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 rounded-b-xl"
        ></div>
      </div>
    </div>

    <!-- Quick Actions -->
    {{-- <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
      <h3 class="text-xl font-bold text-secondary mb-6">Aksi Cepat</h3>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <button
          class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
        >
          <div
            class="w-10 h-10 bg-blue-100 text-primary rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
          >
            <i class="bi bi-plus-lg text-lg"></i>
          </div>
          <div class="text-left">
            <div class="font-medium text-gray-900">Buat Ujian</div>
            <div class="text-sm text-gray-500">Ujian baru</div>
          </div>
        </button>

        <button
          class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
        >
          <div
            class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
          >
            <i class="bi bi-file-earmark-text text-lg"></i>
          </div>
          <div class="text-left">
            <div class="font-medium text-gray-900">Bank Soal</div>
            <div class="text-sm text-gray-500">Kelola soal</div>
          </div>
        </button>

        <button
          class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
        >
          <div
            class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
          >
            <i class="bi bi-graph-up text-lg"></i>
          </div>
          <div class="text-left">
            <div class="font-medium text-gray-900">Nilai</div>
            <div class="text-sm text-gray-500">Lihat hasil</div>
          </div>
        </button>

        <button
          class="flex items-center gap-3 p-4 rounded-lg border border-gray-200 hover:border-primary hover:bg-blue-50 transition-all duration-200 group"
        >
          <div
            class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200"
          >
            <i class="bi bi-calendar-check text-lg"></i>
          </div>
          <div class="text-left">
            <div class="font-medium text-gray-900">Jadwal</div>
            <div class="text-sm text-gray-500">Atur jadwal</div>
          </div>
        </button>
      </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl p-6 border border-gray-100 shadow-sm">
      <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-secondary">Aktivitas Terbaru</h3>
        <button class="text-primary hover:text-blue-700 text-sm font-medium">
          Lihat Semua →
        </button>
      </div>

      <div class="space-y-4">
        <div
          class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
        >
          <div
            class="w-10 h-10 bg-blue-100 text-primary rounded-full flex items-center justify-center"
          >
            <i class="bi bi-check-circle text-lg"></i>
          </div>
          <div class="flex-1">
            <div class="font-medium text-gray-900">
              Ujian Matematika telah selesai
            </div>
            <div class="text-sm text-gray-500">2 jam yang lalu</div>
          </div>
          <div class="text-sm text-gray-400">•••</div>
        </div>

        <div
          class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
        >
          <div
            class="w-10 h-10 bg-green-100 text-green-600 rounded-full flex items-center justify-center"
          >
            <i class="bi bi-plus-circle text-lg"></i>
          </div>
          <div class="flex-1">
            <div class="font-medium text-gray-900">
              Bank soal IPA diperbarui
            </div>
            <div class="text-sm text-gray-500">1 hari yang lalu</div>
          </div>
          <div class="text-sm text-gray-400">•••</div>
        </div>

        <div
          class="flex items-center gap-4 p-4 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200"
        >
          <div
            class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center"
          >
            <i class="bi bi-calendar-plus text-lg"></i>
          </div>
          <div class="flex-1">
            <div class="font-medium text-gray-900">
              Jadwal ujian Bahasa Indonesia dibuat
            </div>
            <div class="text-sm text-gray-500">3 hari yang lalu</div>
          </div>
          <div class="text-sm text-gray-400">•••</div>
        </div>
      </div>
    </div> --}}
  </div>
@endsection

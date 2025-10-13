@extends("layouts.app")

{{--
  'totalUsers', 'totalSiswa', 'siswaTerdaftar', 'totalGuru',
  'totalKelas', 'totalMapel', 'totalBankSoal',
  'ujianBerlangsung', 'ujianAkanDatang', 'totalJadwalUjian',
  'komposisiPengguna'
--}}
@section("content")
  @php
    $overviewCards = [
      [
        "title" => "Total Users",
        "value" => number_format($totalUsers),
        "subtitle" => "Semua pengguna",
        "icon" => "bi-people",
        // 'trend' => '+2.5%', // contoh tren: perubahan total pengguna
      ],
      [
        "title" => "Total Siswa",
        "value" => number_format($totalSiswa),
        "subtitle" => "Semua siswa",
        "icon" => "bi-mortarboard",
        // 'trend' => '+3.1%', // contoh tren: perkembangan jumlah siswa
      ],
      [
        "title" => "Siswa Terdaftar",
        "value" => number_format($siswaTerdaftar),
        "subtitle" => "Sudah terdaftar",
        "icon" => "bi-person-check",
        // 'trend' => '+1.8%', // contoh tren: siswa baru terdaftar
      ],
      [
        "title" => "Total Guru",
        "value" => number_format($totalGuru),
        "subtitle" => "Semua guru",
        "icon" => "bi-person-workspace",
        // 'trend' => '+0.4%', // contoh tren: pertumbuhan guru
      ],
      [
        "title" => "Total Kelas",
        "value" => number_format($totalKelas),
        "subtitle" => "Semua kelas",
        "icon" => "bi-collection",
        // 'trend' => '+1.2%', // contoh tren: kelas aktif
      ],
      [
        "title" => "Total Mapel",
        "value" => number_format($totalMapel),
        "subtitle" => "Mata pelajaran",
        "icon" => "bi-book",
        // 'trend' => '+0.9%', // contoh tren: mapel bertambah
      ],
      [
        "title" => "Total Bank Soal",
        "value" => number_format($totalBankSoal),
        "subtitle" => "Butir soal",
        "icon" => "bi-folder",
        // 'trend' => '+5.6%', // contoh tren: bank soal bertambah
      ],
      [
        "title" => "Ujian Berlangsung",
        "value" => number_format($ujianBerlangsung),
        "subtitle" => "Sedang berjalan",
        "icon" => "bi-clock-history",
        // 'trend' => '+0.0%', // contoh tren: stabil
      ],
      [
        "title" => "Ujian Akan Datang",
        "value" => number_format($ujianAkanDatang),
        "subtitle" => "Terjadwal",
        "icon" => "bi-calendar-event",
        // 'trend' => '+2.0%', // contoh tren: jadwal bertambah
      ],
      [
        "title" => "Total Jadwal Ujian",
        "value" => number_format($totalJadwalUjian),
        "subtitle" => "Semua jadwal",
        "icon" => "bi-calendar-check",
        // 'trend' => '+1.0%', // contoh tren: total jadwal naik
      ],
    ];
  @endphp

  <div class="bg-gray-50 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
      <!-- Header Section -->
      <div
        class="bg-blue-600 text-white rounded-3xl p-6 sm:p-9 flex flex-col lg:flex-row justify-between items-start gap-6 shadow-xl mb-10 relative overflow-hidden"
      >
        <div class="relative z-10">
          <h1 class="text-2xl sm:text-3xl font-bold mb-1">Dashboard Admin</h1>
          <div
            class="text-blue-100 max-w-lg text-sm sm:text-base leading-relaxed"
          >
            <span class="font-semibold text-lg">
              Selamat datang di Dashboard Admin LMS 👋
            </span>
            <p>
              Kelola semua aspek sistem ujian dengan lebih mudah. Mulai dari
              data guru, siswa, mata pelajaran, hingga pengaturan kelas.
            </p>
          </div>
        </div>
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center gap-3 relative z-10"
        >
          <div
            class="bg-white bg-opacity-20 rounded-full px-4 py-2 flex items-center gap-2 text-sm font-medium"
          >
            <i class="bi bi-calendar3"></i>
            <span>{{ now()->format("l, d M Y") }}</span>
          </div>
        </div>
      </div>

      <!-- Overview Section -->
      <h2
        class="text-lg font-semibold text-gray-600 uppercase tracking-wide mb-4"
      >
        Overview
      </h2>
      <div
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
      >
        @foreach ($overviewCards as $card)
          <article
            class="bg-white rounded-2xl border border-gray-200 p-6 hover:-translate-y-1 hover:shadow-xl transition-all duration-300 relative overflow-hidden group"
          >
            <span
              class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl inline-flex items-center justify-center text-xl mb-3"
            >
              <i class="bi {{ $card["icon"] }}"></i>
            </span>
            <h3
              class="text-sm font-medium text-gray-600 uppercase tracking-wide mb-2"
            >
              {{ $card["title"] }}
            </h3>
            <div class="text-2xl font-bold text-gray-900 mb-2">
              {{ $card["value"] }}
            </div>
            <span class="text-sm text-gray-500">{{ $card["subtitle"] }}</span>

            <!-- Blue line hover effect -->
            <div
              class="absolute bottom-0 left-0 right-0 h-1 bg-blue-600 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 rounded-b-2xl"
            ></div>

            {{--
              Optional trend badge (enable when ready)
              <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-600 bg-green-100 px-2 py-1 rounded-full mt-2">
              <i class="bi bi-arrow-up-right"></i>
              {{ $card['trend'] ?? '+0%' }}
              </span>
            --}}
          </article>
        @endforeach
      </div>
    </div>
  </div>
@endsection

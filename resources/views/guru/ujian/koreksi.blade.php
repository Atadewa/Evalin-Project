@extends("layouts.app")

@section("title", "Koreksi Ujian - " . $ujian->nama_ujian)

@section("content")
  <div class="w-full max-w-none px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold text-gray-800">Koreksi Ujian</h1>
        <a
          href="{{ route("guru.ujian.index") }}"
          class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali
        </a>
      </div>

      <!-- Info Ujian -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
          <div>
            <h3 class="font-semibold text-gray-700">Nama Ujian</h3>
            <p class="text-lg">{{ $ujian->nama_ujian }}</p>
          </div>
          <div>
            <h3 class="font-semibold text-gray-700">Mata Pelajaran</h3>
            <p class="text-lg">{{ $ujian->mataPelajaran->nama_mapel }}</p>
          </div>
          <div>
            <h3 class="font-semibold text-gray-700">Total Peserta</h3>
            <p class="text-lg">{{ $hasilUjian->count() }} siswa</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
      <div class="bg-blue-50 px-6 py-4 border-b">
        <h2 class="text-xl font-semibold text-gray-800">
          Daftar Siswa untuk Dikoreksi
        </h2>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                No
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Nama Siswa
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Status
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Nilai
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($hasilUjian as $index => $hasil)
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ $index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="text-sm font-medium text-gray-900">
                      {{ $hasil->siswa->user->name }}
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @if ($hasil->status_penilaian)
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                    >
                      Sudah Dikonfirmasi
                    </span>
                  @elseif ($hasil->nilai_2 !== null)
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"
                    >
                      Perlu Konfirmasi
                    </span>
                  @else
                    <span
                      class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800"
                    >
                      Belum Dinilai
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  @if ($hasil->status_penilaian)
                    <span class="font-semibold text-green-600">
                      {{ $hasil->nilai_2 ?? 0 }}
                    </span>
                  @elseif ($hasil->nilai_2 !== null)
                    <span class="text-yellow-600">
                      {{ $hasil->nilai_2 ?? 0 }} (Otomatis)
                    </span>
                  @else
                    <span class="text-gray-400">-</span>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <a
                      href="{{ route("guru.ujian.koreksi-persiswa", [$ujian->id, $hasil->siswa_id]) }}"
                      class="text-blue-600 hover:text-blue-900 transition"
                    >
                      <i class="fas fa-edit mr-1"></i>
                      Koreksi
                    </a>
                    <a
                      href="{{ route("guru.ujian.nilai-siswa", [$hasil->siswa_id, $ujian->id]) }}"
                      class="text-green-600 hover:text-green-900 transition"
                    >
                      <i class="fas fa-eye mr-1"></i>
                      Detail
                    </a>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  <i class="fas fa-inbox text-4xl mb-4 block"></i>
                  Belum ada siswa yang mengikuti ujian ini
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- Statistik -->
    @if ($hasilUjian->count() > 0)
      <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-100 rounded-lg p-4">
          <div class="text-blue-800 text-sm font-medium">Total Peserta</div>
          <div class="text-2xl font-bold text-blue-900">
            {{ $hasilUjian->count() }}
          </div>
        </div>
        <div class="bg-green-100 rounded-lg p-4">
          <div class="text-green-800 text-sm font-medium">
            Sudah Dikonfirmasi
          </div>
          <div class="text-2xl font-bold text-green-900">
            {{ $hasilUjian->where("status_penilaian", 1)->count() }}
          </div>
        </div>
        <div class="bg-yellow-100 rounded-lg p-4">
          <div class="text-yellow-800 text-sm font-medium">
            Perlu Konfirmasi
          </div>
          <div class="text-2xl font-bold text-yellow-900">
            {{ $hasilUjian->where("status_penilaian", 0)->whereNotNull("nilai_2")->count() }}
          </div>
        </div>
        <div class="bg-red-100 rounded-lg p-4">
          <div class="text-red-800 text-sm font-medium">Belum Dinilai</div>
          <div class="text-2xl font-bold text-red-900">
            {{ $hasilUjian->whereNull("nilai_2")->count() }}
          </div>
        </div>
      </div>
    @endif
  </div>
@endsection

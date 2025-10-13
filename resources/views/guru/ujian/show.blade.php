@extends('layouts.app')

@section("title", "Detail Ujian - " . $ujian->nama_ujian)

@if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
        {!! session('success') !!}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
        {!! session('error') !!}
    </div>
@endif


@section("content")
  <div class="w-full max-w-none px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex justify-between items-center mb-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">
            {{ $ujian->nama_ujian }}
          </h1>
          <p class="text-gray-600 mt-2">
            {{ $ujian->mataPelajaran->nama_mapel }}
          </p>
        </div>
        <div class="flex space-x-3">
          <a
            href="{{ route("guru.ujian.index") }}"
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
          >
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali
          </a>

          @if ($ujian->getStatusAttribute() === "draft")
            <a
              href="{{ route("guru.ujian.edit", $ujian->id) }}"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition"
            >
              <i class="fas fa-edit mr-2"></i>
              Edit Ujian
            </a>
          @endif
        </div>
      </div>

      <!-- Status dan Info -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div
          class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Status</p>
              @php
                $status = $ujian->getStatusAttribute();
                $statusConfig = [
                  "draft" => ["text-gray-600", "Draft"],
                  "published" => ["text-blue-600", "Dipublikasi"],
                  "active" => ["text-green-600", "Berlangsung"],
                  "finished" => ["text-purple-600", "Selesai"],
                ];
              @endphp

              <p class="text-lg font-bold {{ $statusConfig[$status][0] }}">
                {{ $statusConfig[$status][1] }}
              </p>
            </div>
            <i
              class="fas fa-{{ $status === "active" ? "play-circle" : ($status === "finished" ? "check-circle" : "clock") }} text-2xl {{ $statusConfig[$status][0] }}"
            ></i>
          </div>
        </div>

        <div
          class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Total Soal</p>
              <p class="text-lg font-bold text-green-600">
                {{ $ujian->soals()->count() }}
              </p>
            </div>
            <i class="fas fa-question-circle text-2xl text-green-600"></i>
          </div>
        </div>

        <div
          class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Durasi</p>
              <p class="text-lg font-bold text-yellow-600">
                {{ $ujian->durasi_jam }} jam {{ $ujian->durasi_menit }} menit
              </p>
            </div>
            <i class="fas fa-clock text-2xl text-yellow-600"></i>
          </div>
        </div>

        <div
          class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500"
        >
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-600">Peserta</p>
              <p class="text-lg font-bold text-purple-600">
                {{ $ujian->ujianSiswa()->count() }}
              </p>
            </div>
            <i class="fas fa-users text-2xl text-purple-600"></i>
          </div>
        </div>
      </div>

      <!-- Info Detail -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
              Informasi Ujian
            </h3>
            <div class="space-y-3">
              <div class="flex justify-between">
                <span class="text-gray-600">Jenis Ujian:</span>
                <span class="font-medium">
                  {{ ucfirst(str_replace("_", " ", $ujian->jenis_ujian)) }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Jadwal Mulai:</span>
                <span class="font-medium">
                  {{ \Carbon\Carbon::parse($ujian->jadwal)->format("d M Y, H:i") }}
                </span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Jadwal Selesai:</span>
                <span class="font-medium">
                  {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format("d M Y, H:i") }}
                </span>
              </div>
              @if ($ujian->deskripsi)
                <div class="pt-2">
                  <span class="text-gray-600">Deskripsi:</span>
                  <p class="mt-1 text-sm text-gray-700">
                    {{ $ujian->deskripsi }}
                  </p>
                </div>
              @endif
            </div>
          </div>

          <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
              Kelas yang Mengikuti
            </h3>
            <div class="space-y-2">
              {{-- {{ dd($ujian->kelasMapel) }} --}}
              @foreach ($ujian->kelasMapel as $kelasMapel)
                <span
                  class="inline-block bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full"
                >
                  {{ $kelasMapel->kelas->nama_kelas }}
                </span>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden w-full">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex w-full">
          <button
            class="tab-button active flex-1 py-4 px-6 text-center border-b-2 border-blue-500 bg-blue-50 text-blue-600 font-medium"
            onclick="showTab('soal')"
          >
            <i class="fas fa-list mr-2"></i>
            Daftar Soal ({{ $ujian->soals()->count() }})
          </button>
          <button
            class="tab-button flex-1 py-4 px-6 text-center border-b-2 border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700 font-medium"
            onclick="showTab('hasil')"
          >
            <i class="fas fa-chart-bar mr-2"></i>
            Hasil Ujian ({{ $ujian->ujianSiswa()->count() }})
          </button>
          <button
            class="tab-button flex-1 py-4 px-6 text-center border-b-2 border-transparent hover:border-gray-300 text-gray-500 hover:text-gray-700 font-medium"
            onclick="showTab('statistik')"
          >
            <i class="fas fa-analytics mr-2"></i>
            Statistik
          </button>
        </nav>
      </div>

      <!-- Tab Content: Soal -->
      <div id="tab-soal" class="tab-content p-6 w-full">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold text-gray-800">Daftar Soal</h3>
          <div class="flex space-x-3">
            @if ($ujian->getStatusAttribute() === "draft")
              <a
                href="{{ route("guru.soal.create", ["ujian_id" => $ujian->id]) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
              >
                <i class="fas fa-plus mr-2"></i>
                Tambah Soal
              </a>
              <button
                onclick="showImportModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition"
              >
                <i class="fas fa-upload mr-2"></i>
                Import Soal
              </button>
            @endif
          </div>
        </div>

        @if ($ujian->soals()->count() > 0)
          <div class="space-y-4 w-full">
            @foreach ($ujian->soals()->get() as $index => $soal)
              <div
                class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition w-full"
              >
                <div class="flex justify-between items-start">
                  <div class="flex-1">
                    <div class="flex items-center mb-2">
                      <span
                        class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3"
                      >
                        Soal {{ $index + 1 }}
                      </span>
                      <span
                        class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3"
                      >
                        {{ ucfirst(str_replace("_", " ", $soal->jenis_soal)) }}
                      </span>
                      <span
                        class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                      >
                        {{ $soal->skor }} poin
                      </span>
                    </div>
                    <p class="text-gray-800 font-medium mb-2">
                      {{ Str::limit(strip_tags($soal->pertanyaan), 100) }}
                    </p>

                    @if ($soal->tipe_soal === "pilgan" && $soal->opsiJawaban->count() > 0)
                      <div class="text-sm text-gray-600">
                        <span class="font-medium">Opsi jawaban:</span>
                        @foreach ($soal->opsiJawaban as $opsi)
                          <span
                            class="ml-2 {{ $opsi->is_correct ? "text-green-600 font-semibold" : "" }}"
                          >
                            {{ $opsi->label }}.
                            {{ Str::limit($opsi->isi_opsi, 30) }}{{ $opsi->is_correct ? " ✓" : "" }}
                          </span>
                        @endforeach
                      </div>
                    @elseif ($soal->jenis_soal === "essay" && $soal->jawaban_benar)
                      <div class="text-sm text-gray-600">
                        <span class="font-medium">Kunci jawaban:</span>
                        <span class="ml-2">
                          {{ Str::limit($soal->jawaban_benar, 50) }}
                        </span>
                      </div>
                    @endif
                  </div>

                  @if ($ujian->getStatusAttribute() === "draft")
                    <div class="flex space-x-2 ml-4">
                      <a
                        href="{{ route("guru.soal.edit", $soal->id) }}"
                        class="text-blue-600 hover:text-blue-800 transition"
                      >
                        <i class="fas fa-edit"></i>
                      </a>
                      <button
                        onclick="deleteSoal({{ $soal->id }})"
                        class="text-red-600 hover:text-red-800 transition"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-12">
            <i class="fas fa-question-circle text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              Belum ada soal
            </h3>
            <p class="text-gray-500 mb-6">
              Mulai dengan menambahkan soal pertama untuk ujian ini
            </p>
            @if ($ujian->getStatusAttribute() === "draft")
              <a
                href="{{ route("guru.soal.create", ["ujian_id" => $ujian->id]) }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition"
              >
                <i class="fas fa-plus mr-2"></i>
                Tambah Soal Pertama
              </a>
            @endif
          </div>
        @endif
      </div>

        <!-- Tab Content: Hasil -->
        <div id="tab-hasil" class="tab-content p-6 hidden">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-gray-800">Hasil Ujian</h3>
                @if ($ujian->ujianSiswa()->count() > 0)
                    <div class="flex space-x-3">
                        {{-- <a href="{{ route('guru.ujian.show.koreksi', $ujian->id) }}"
                            class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-check-circle mr-2"></i>Koreksi
                        </a> --}}
                        <!-- Tombol buka modal -->
                        <button onclick="openExportModal()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                            <i class="fas fa-download mr-2"></i> Export Hasil Ujian
                        </button>
                    </div>
                @endif
            </div>
      <!-- Tab Content: Hasil -->
      <div id="tab-hasil" class="tab-content p-6 hidden w-full">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-semibold text-gray-800">Hasil Ujian</h3>
          @if ($ujian->ujianSiswa()->count() > 0)
            <div class="flex space-x-3">
              <a
                href="{{ route("guru.ujian.show.koreksi", $ujian->id) }}"
                class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg transition"
              >
                <i class="fas fa-check-circle mr-2"></i>
                Koreksi
              </a>
              <a
                href="{{ route("guru.ujian.exportHasilUjian", $ujian->id) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition"
              >
                <i class="fas fa-download mr-2"></i>
                Export
              </a>
            </div>
          @endif
        </div>

        @if ($ujian->ujianSiswa()->count() > 0)
          <div class="overflow-x-auto w-full">
            <table class="min-w-full w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Siswa
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Waktu Mulai
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Waktu Selesai
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Nilai
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Status
                  </th>
                  <th
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($ujian->ujianSiswa()->with("siswa.user")->get() as $hasil)
                  <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="text-sm font-medium text-gray-900">
                        {{ $hasil->siswa->user->name }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ $hasil->siswa->user->email }}
                      </div>
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                    >
                      {{ $hasil->waktu_mulai ? \Carbon\Carbon::parse($hasil->waktu_mulai)->format("d M Y H:i") : "-" }}
                    </td>
                    <td
                      class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                    >
                      {{ $hasil->waktu_selesai ? \Carbon\Carbon::parse($hasil->waktu_selesai)->format("d M Y H:i") : "-" }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      @if ($hasil->status_penilaian && $hasil->total_nilai !== null)
                        <span class="text-lg font-bold text-green-600">
                          {{ $hasil->total_nilai }}
                        </span>
                      @elseif ($hasil->total_nilai !== null)
                        <span class="text-sm text-yellow-600">
                          {{ $hasil->total_nilai }} (Otomatis)
                        </span>
                      @else
                        <span class="text-sm text-gray-400">Belum dinilai</span>
                      @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      @if ($hasil->status_penilaian)
                        <span
                          class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
                        >
                          Dikonfirmasi
                        </span>
                      @elseif ($hasil->total_nilai !== null)
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      <a
                        href="{{ route("guru.ujian.show.nilaisiswa", [$hasil->siswa_id, $ujian->id]) }}"
                        class="text-blue-600 hover:text-blue-900 transition"
                      >
                        <i class="fas fa-eye mr-1"></i>
                        Detail
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-12">
            <i class="fas fa-chart-bar text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
              Belum ada yang mengerjakan
            </h3>
            <p class="text-gray-500">
              Hasil ujian akan muncul setelah siswa mulai mengerjakan
            </p>
          </div>
        @endif
      </div>

        <!-- Tab Content: Statistik -->
        <div id="tab-statistik" class="tab-content p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Statistik Ujian</h3>

            @if ($ujian->ujianSiswa()->whereNotNull('total_nilai')->count() > 0)
                @php
                    $nilaiTertinggi = $ujian->ujianSiswa()->max('total_nilai');
                    $nilaiTerendah = $ujian->ujianSiswa()->min('total_nilai');
                    $rataRata = $ujian->ujianSiswa()->avg('total_nilai');
                    $totalPeserta = $ujian->ujianSiswa()->count();
                    $sudahSelesai = $ujian->ujianSiswa()->whereNotNull('total_nilai')->count();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                        <div class="text-green-600 text-sm font-medium">Nilai Tertinggi</div>
                        <div class="text-2xl font-bold text-green-700">{{ number_format($nilaiTertinggi, 1) }}</div>
                    </div>
                    <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                        <div class="text-red-600 text-sm font-medium">Nilai Terendah</div>
                        <div class="text-2xl font-bold text-red-700">{{ number_format($nilaiTerendah, 1) }}</div>
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                        <div class="text-blue-600 text-sm font-medium">Rata-rata</div>
                        <div class="text-2xl font-bold text-blue-700">{{ number_format($rataRata, 1) }}</div>
                    </div>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="text-purple-600 text-sm font-medium">Tingkat Penyelesaian</div>
                        <div class="text-2xl font-bold text-purple-700">
                            {{ number_format(($sudahSelesai / $totalPeserta) * 100, 1) }}%</div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-chart-line text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data statistik</h3>
                    <p class="text-gray-500">Statistik akan muncul setelah ada siswa yang menyelesaikan ujian</p>
                </div>
            @endif
        </div>
    </div>
    </div>

    <!-- Modal Import Soal -->
    <div id="importModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4">
            <form action="{{ route('admin.soal.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-800">Import Data Soal</h5>
                    <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                            Pilih File (xlsx/csv)
                        </label>
                        <input type="file" name="file" id="file" accept=".xlsx,.csv"
                            class="w-full border border-gray-300 rounded-md p-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 text-blue-700 p-3 rounded-md text-sm">
                        <p class="mb-1">Format: <code>ujian_id, pertanyaan, jawaban_benar</code></p>
                        <p>Contoh File Excel:
                            <a href="{{ asset('contoh_soal2.csv') }}" class="text-blue-600 hover:underline">Download
                                disini</a>
                        </p>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                    <button type="button" onclick="closeImportModal()"
                        class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Import
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Export -->
    <div id="exportModal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <form id="exportForm" action="{{ route('guru.ujian.exportHasilUjian', $ujian->id) }}" method="GET">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-800">Pilih Nilai untuk Export</h5>
                    <button type="button" onclick="closeExportModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 space-y-4">
                    <p class="text-sm text-gray-700">Pilih format file:</p>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="format" value="pdf" id="formatPdf"
                            class="text-blue-600 focus:ring-blue-500" checked>
                        <label for="formatPdf" class="text-gray-700 text-sm">PDF</label>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="radio" name="format" value="excel" id="formatExcel"
                            class="text-blue-600 focus:ring-blue-500">
                        <label for="formatExcel" class="text-gray-700 text-sm">Excel</label>
                    </div>
                </div>

                <div class="p-4 border-t border-gray-200 flex justify-end space-x-2">
                    <button type="button" onclick="closeExportModal()"
                        class="px-4 py-2 rounded-md bg-gray-200 text-gray-800 hover:bg-gray-300">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">
                        Download
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'bg-blue-50', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            document.getElementById(`tab-${tabName}`).classList.remove('hidden');

            // Add active class to clicked tab button
            event.target.classList.add('active', 'border-blue-500', 'bg-blue-50', 'text-blue-600');
            event.target.classList.remove('border-transparent', 'text-gray-500');
        }

        function deleteSoal(id) {
            if (confirm('Apakah Anda yakin ingin menghapus soal ini?')) {
                fetch(`/guru/soal/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Gagal menghapus soal');
                        }
                    });
            }
        }
        // ===== Modal Import Soal =====
        function showImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
        }
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('importModal');
            if (!modal.classList.contains('hidden') && e.target === modal) {
                closeImportModal();
            }
        });

        function openExportModal() {
            document.getElementById('exportModal').classList.remove('hidden');
        }

        function closeExportModal() {
            document.getElementById('exportModal').classList.add('hidden');
        }

        // Tutup modal kalau klik area luar
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('exportModal');
            if (!modal.classList.contains('hidden') && e.target === modal) {
                closeExportModal();
            }
        });
    </script>
@endpush

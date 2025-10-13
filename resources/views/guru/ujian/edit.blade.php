@extends("layouts.app")

@section("title", "Edit Ujian")

@section("content")
  <div class="w-full max-w-none px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex justify-between items-center mb-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Edit Ujian</h1>
          <p class="text-gray-600 mt-2">Perbarui informasi ujian</p>
        </div>
        <a
          href="{{ route("guru.ujian.show", $ujian->id) }}"
          class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali
        </a>
      </div>

      @if ($ujian->getStatusAttribute() !== "draft")
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-yellow-800">Peringatan</h3>
              <div class="mt-2 text-sm text-yellow-700">
                <p>
                  Ujian ini sudah dipublikasi. Beberapa perubahan mungkin
                  mempengaruhi siswa yang sudah mulai mengerjakan.
                </p>
              </div>
            </div>
          </div>
        </div>
      @endif
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
      <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="fas fa-exclamation-triangle text-red-400"></i>
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

    <!-- Main Form -->
    <form
      method="POST"
      action="{{ route("guru.ujian.update", $ujian->id) }}"
      class="space-y-6"
    >
      @csrf
      @method("PUT")

      <!-- Informasi Dasar -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
          Informasi Dasar
        </h3>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <!-- Nama Ujian -->
          <div>
            <label
              for="nama_ujian"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Nama Ujian
              <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="nama_ujian"
              id="nama_ujian"
              value="{{ old("nama_ujian", $ujian->nama_ujian) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Masukkan nama ujian"
              required
            />
            @error("nama_ujian")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Mata Pelajaran -->
          <div>
            <label
              for="mapel_id"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Mata Pelajaran
              <span class="text-red-500">*</span>
            </label>
            <select
              name="mapel_id"
              id="mapel_id"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            >
              <option value="">Pilih Mata Pelajaran</option>
              @foreach ($mapels as $mapel)
                <option
                  value="{{ $mapel->id }}"
                  {{ old("mapel_id", $ujian->mapel_id) == $mapel->id ? "selected" : "" }}
                >
                  {{ $mapel->nama_mapel }}
                </option>
              @endforeach
            </select>
            @error("mapel_id")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Jenis Ujian -->
          <div>
            <label
              for="jenis_ujian"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Jenis Ujian
              <span class="text-red-500">*</span>
            </label>
            <select
              name="jenis_ujian"
              id="jenis_ujian"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            >
              <option value="">Pilih Jenis Ujian</option>
              <option
                value="pilihan_ganda"
                {{ old("jenis_ujian", $ujian->jenis_ujian) == "pilihan_ganda" ? "selected" : "" }}
              >
                Pilihan Ganda
              </option>
              <option
                value="essay"
                {{ old("jenis_ujian", $ujian->jenis_ujian) == "essay" ? "selected" : "" }}
              >
                Essay
              </option>
              <option
                value="mix"
                {{ old("jenis_ujian", $ujian->jenis_ujian) == "campuran" ? "selected" : "" }}
              >
                Campuran
              </option>
            </select>
            @error("jenis_ujian")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <!-- Deskripsi -->
        <div class="mt-6">
          <label
            for="deskripsi"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            Deskripsi Ujian
          </label>
          <textarea
            name="deskripsi"
            id="deskripsi"
            rows="3"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Deskripsi singkat tentang ujian ini..."
          >
{{ old("deskripsi", $ujian->deskripsi) }}</textarea
          >
          @error("deskripsi")
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>
      </div>

      <!-- Jadwal -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Ujian</h3>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
          <!-- Durasi -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Durasi Ujian
              <span class="text-red-500">*</span>
            </label>
            <div class="flex items-center space-x-2">
              <input
                type="number"
                name="durasi_jam"
                class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old("durasi_jam", $ujian->durasi_jam) }}"
                min="0"
                max="23"
                placeholder="0"
              />
              <span class="text-gray-500">jam</span>
              <input
                type="number"
                name="durasi_menit"
                class="w-20 border border-gray-300 rounded-lg px-3 py-2 text-center focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                value="{{ old("durasi_menit", $ujian->durasi_menit) }}"
                min="0"
                max="59"
                placeholder="0"
              />
              <span class="text-gray-500">menit</span>
            </div>
            <p class="mt-1 text-xs text-gray-500">
              Waktu maksimal siswa mengerjakan ujian
            </p>
            @error("durasi_jam")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Jadwal Mulai -->
          <div>
            <label
              for="jadwal"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Jadwal Mulai
              <span class="text-red-500">*</span>
            </label>
            <input
              type="datetime-local"
              name="jadwal"
              id="jadwal"
              value="{{ old("jadwal", \Carbon\Carbon::parse($ujian->jadwal)->format("Y-m-d\TH:i")) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            @error("jadwal")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <!-- Jadwal Selesai -->
          <div>
            <label
              for="waktu_selesai"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Jadwal Selesai
              <span class="text-red-500">*</span>
            </label>
            <input
              type="datetime-local"
              name="waktu_selesai"
              id="waktu_selesai"
              value="{{ old("waktu_selesai", \Carbon\Carbon::parse($ujian->waktu_selesai)->format("Y-m-d\TH:i")) }}"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              required
            />
            @error("waktu_selesai")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      <!-- Kelas -->
      <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
          Kelas yang Mengikuti
        </h3>

        <div
          class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4"
          id="kelas-container"
        >
          @foreach ($semuaKelas as $kelas)
            <label
              class="group flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition"
            >
              <!-- Input pakai sr-only, bukan hidden -->
              <input
                type="checkbox"
                name="kelas_id[]"
                value="{{ $kelas->id }}"
                class="peer sr-only"
                {{ $ujian->kelas->contains($kelas->id) ? "checked" : "" }}
              />

              <!-- Kotak Checkbox -->
              <div
                class="w-5 h-5 mr-3 flex items-center justify-center border border-gray-300 rounded transition peer-checked:bg-blue-600 peer-checked:border-blue-600"
              >
                <svg
                  class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100 transition"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                >
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                  />
                </svg>
              </div>

              <!-- Nama kelas -->
              <span
                class="text-sm font-medium text-gray-700 peer-checked:text-blue-600"
              >
                {{ $kelas->nama_kelas }}
              </span>
            </label>
          @endforeach
        </div>
        @error("kelas_id")
          <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Submit Buttons -->
      <div
        class="bg-gray-50 px-6 py-4 flex justify-between items-center sticky bottom-0"
      >
        <a
          href="{{ route("guru.ujian.show", $ujian->id) }}"
          class="text-gray-600 hover:text-gray-800 font-medium"
        >
          <i class="fas fa-arrow-left mr-2"></i>
          Kembali
        </a>

        <div class="flex space-x-3">
          <button
            type="button"
            onclick="resetForm()"
            class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-lg transition"
          >
            <i class="fas fa-undo mr-2"></i>
            Reset
          </button>
          <button
            type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition"
          >
            <i class="fas fa-save mr-2"></i>
            Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
@endsection

@push("scripts")
  <script>
    function resetForm() {
      if (
        confirm(
          'Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.',
        )
      ) {
        location.reload();
      }
    }

    // Auto calculate end time based on start time and duration
    document.getElementById('jadwal').addEventListener('change', updateEndTime);
    document.getElementById('durasi').addEventListener('input', updateEndTime);

    function updateEndTime() {
      const startTime = document.getElementById('jadwal').value;
      const duration = parseInt(document.getElementById('durasi').value);

      if (startTime && duration) {
        const start = new Date(startTime);
        const end = new Date(start.getTime() + duration * 60000); // Add minutes in milliseconds

        const endTimeString = end.toISOString().slice(0, 16); // Format for datetime-local input
        document.getElementById('waktu_selesai').value = endTimeString;
      }
    }
  </script>
@endpush

@extends("layouts.app")

@section("title", "Manajemen Ujian")

@section("content")
  <div class="w-full max-w-none px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex justify-between items-center mb-4">
        <div>
          <h1 class="text-3xl font-bold text-gray-800">Manajemen Ujian</h1>
          <p class="text-gray-600 mt-2">Kelola ujian dan monitor pelaksanaan</p>
        </div>
        <a
          href="{{ route("guru.ujian.create") }}"
          class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition"
        >
          <i class="fas fa-plus mr-2"></i>
          Buat Ujian Baru
        </a>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
              <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Ujian</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $ujians->count() }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
              <i class="fas fa-check-circle text-green-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Dipublikasi</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $ujians->where("is_published", 1)->count() }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
              <i class="fas fa-clock text-yellow-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Berlangsung</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $ujians->where("is_published", 1)->where("jadwal", "<=", now())->where("waktu_selesai", ">", now())->count() }}
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-2 bg-purple-100 rounded-lg">
              <i class="fas fa-user-graduate text-purple-600 text-xl"></i>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Peserta</p>
              <p class="text-2xl font-bold text-gray-900">
                {{ $ujians->sum(function ($u) { return $u->ujianSiswa->count();}) }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
      <div class="flex flex-wrap gap-4 items-center">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Status
          </label>
          <select
            class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            onchange="filterUjian('status', this.value)"
          >
            <option value="">Semua Status</option>
            <option value="draft">Draft</option>
            <option value="published">Dipublikasi</option>
            <option value="active">Sedang Berlangsung</option>
            <option value="finished">Selesai</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Mata Pelajaran
          </label>
          <select
            class="border border-gray-300 rounded-lg pl-3 pr-14 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            onchange="filterUjian('mapel', this.value)"
          >
            <option value="">Semua Mapel</option>
            @foreach ($ujians->unique("mapel_id") as $ujian)
              <option value="{{ $ujian->mataPelajaran->nama_mapel }}">
                {{ $ujian->mataPelajaran->nama_mapel }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="ml-auto">
          <button
            onclick="resetFilters()"
            class="px-4 py-2 text-gray-600 hover:text-gray-800 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
          >
            <i class="fas fa-undo mr-2"></i>
            Reset Filter
          </button>
        </div>
      </div>
    </div>

    <!-- Ujian List -->
    <div
      class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden"
    >
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="ujian-table">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Ujian
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Mata Pelajaran
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Soal
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Jadwal
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Status
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Peserta
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($ujians as $ujian)
              <tr
                class="hover:bg-gray-50 ujian-row"
                data-status="{{ $ujian->getStatusAttribute() }}"
                data-mapel="{{ $ujian->mataPelajaran->nama_mapel }}"
              >
                <td class="px-6 py-4">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-gray-900">
                        {{ $ujian->nama_ujian }}
                      </div>
                      <div class="text-sm text-gray-500">
                        Durasi: {{ $ujian->durasi }} menit • Jenis:
                        {{ ucfirst(str_replace("_", " ", $ujian->jenis_ujian)) }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">
                    {{ $ujian->mataPelajaran->nama_mapel }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ $ujian->kelasMapel->flatMap->kelas->pluck("nama_kelas")->unique()->implode(", ") }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $ujian->soals()->count() > 0 ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }}"
                  >
                    {{ $ujian->soals()->count() }} soal
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div>
                    {{ \Carbon\Carbon::parse($ujian->jadwal)->format("d M Y") }}
                  </div>
                  <div class="text-gray-500">
                    {{ \Carbon\Carbon::parse($ujian->jadwal)->format("H:i") }}
                    -
                    {{ \Carbon\Carbon::parse($ujian->waktu_selesai)->format("H:i") }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  @php
                    $status = $ujian->getStatusAttribute();
                    $statusConfig = [
                      "draft" => ["bg-gray-100 text-gray-800", "Draft"],
                      "published" => ["bg-blue-100 text-blue-800", "Dipublikasi"],
                      "active" => ["bg-green-100 text-green-800", "Berlangsung"],
                      "finished" => ["bg-purple-100 text-purple-800", "Selesai"],
                    ];
                  @endphp

                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusConfig[$status][0] }}"
                  >
                    {{ $statusConfig[$status][1] }}
                  </span>

                  @if ($status === "draft")
                    <div class="mt-2">
                      <button
                        onclick="publishUjian({{ $ujian->id }})"
                        class="text-xs bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded transition"
                      >
                        <i class="fas fa-paper-plane mr-1"></i>
                        Publikasi
                      </button>
                    </div>
                  @elseif ($status === "published")
                    <div class="mt-2">
                      <button
                        onclick="unpublishUjian({{ $ujian->id }})"
                        class="text-xs bg-gray-600 hover:bg-gray-700 text-white px-2 py-1 rounded transition"
                      >
                        <i class="fas fa-eye-slash mr-1"></i>
                        Sembunyikan
                      </button>
                    </div>
                  @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <div class="flex items-center">
                    <span class="mr-2">
                      {{ $ujian->ujianSiswa()->count() }}
                    </span>
                    @if ($ujian->ujianSiswa()->count() > 0)
                      <div class="text-xs text-gray-500">
                        ({{ $ujian->ujianSiswa()->whereNotNull("total_nilai")->count() }}
                        selesai)
                      </div>
                    @endif
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex items-center space-x-2">
                    <a
                      href="{{ route("guru.ujian.show", $ujian->id) }}"
                      class="text-blue-600 hover:text-blue-900 transition"
                    >
                      <i class="fas fa-eye"></i>
                    </a>

                    @if ($status === "draft")
                      <a
                        href="{{ route("guru.ujian.edit", $ujian->id) }}"
                        class="text-yellow-600 hover:text-yellow-900 transition"
                      >
                        <i class="fas fa-edit"></i>
                      </a>
                    @endif

                    @if ($ujian->ujianSiswa()->count() > 0)
                      <a
                        href="{{ route("guru.ujian.show.koreksi", $ujian->id) }}"
                        class="text-green-600 hover:text-green-900 transition"
                      >
                        <i class="fas fa-check-circle"></i>
                      </a>
                    @endif

                    @if ($status === "draft")
                      <button
                        onclick="deleteUjian({{ $ujian->id }})"
                        class="text-red-600 hover:text-red-900 transition"
                      >
                        <i class="fas fa-trash"></i>
                      </button>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                  <i class="fas fa-inbox text-4xl mb-4 block"></i>
                  <p class="text-lg font-medium">Belum ada ujian</p>
                  <p class="text-sm">Mulai dengan membuat ujian baru</p>
                  <a
                    href="{{ route("guru.ujian.create") }}"
                    class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
                  >
                    <i class="fas fa-plus mr-2"></i>
                    Buat Ujian Pertama
                  </a>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modals -->
  <div
    id="publishModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
      <div class="p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Publikasi Ujian</h3>
        <p class="text-gray-600 mb-4">
          Apakah Anda yakin ingin mempublikasikan ujian ini? Setelah
          dipublikasikan, Anda tidak dapat mengedit soal ujian.
        </p>
        <div class="flex justify-end space-x-3">
          <button
            onclick="closeModal('publishModal')"
            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
          >
            Batal
          </button>
          <div class="relative">
            <button
              id="publishButton"
              onclick="confirmPublish()"
              disabled
              class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed disabled:hover:bg-gray-400 transition-colors"
            >
              Publikasi
            </button>
            <!-- Gray barrier layer -->
            <div
              id="countdownBarrier"
              class="absolute inset-0 bg-gray-500 rounded-lg transition-opacity duration-500"
            ></div>
            <!-- Countdown overlay -->
            <div
              id="countdownOverlay"
              class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xl font-semibold rounded-lg transition-opacity duration-500"
            >
              <div class="flex items-center space-x-2">
                <i
                  id="hourglassIcon"
                  class="fas fa-hourglass-half text-white text-sm transition-transform duration-1000"
                ></i>
                <span id="countdownNumber">5</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push("scripts")
  <script>
    let currentUjianId = null;
    let countdownInterval = null;

    function publishUjian(id) {
      currentUjianId = id;
      document.getElementById('publishModal').classList.remove('hidden');
      document.getElementById('publishModal').classList.add('flex');
      startCountdown();
    }

    function startCountdown() {
      // Clear any existing countdown
      if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
      }

      let timeLeft = 5;
      const countdownOverlay = document.getElementById('countdownOverlay');
      const countdownBarrier = document.getElementById('countdownBarrier');
      const countdownNumber = document.getElementById('countdownNumber');
      const hourglassIcon = document.getElementById('hourglassIcon');
      const publishButton = document.getElementById('publishButton');

      // Reset and show countdown overlay and barrier
      countdownBarrier.style.opacity = '1';
      countdownBarrier.style.display = 'block';

      countdownOverlay.style.opacity = '1';
      countdownOverlay.style.display = 'flex';
      countdownNumber.textContent = timeLeft;

      // Reset hourglass to initial state
      if (hourglassIcon) {
        hourglassIcon.className =
          'fas fa-hourglass-half text-white text-sm transition-transform duration-1000';
      }

      // Disable button
      publishButton.disabled = true;
      publishButton.classList.add(
        'disabled:bg-gray-400',
        'disabled:cursor-not-allowed',
        'disabled:hover:bg-gray-400',
      );
      publishButton.classList.remove('hover:bg-blue-700');

      countdownInterval = setInterval(() => {
        timeLeft--;

        // Animate hourglass rotation every second
        if (hourglassIcon && timeLeft >= 0) {
          // Calculate rotation angle (180 degrees per second)
          const rotation = (5 - timeLeft) * 180;
          hourglassIcon.style.transform = `rotate(${rotation}deg)`;
        }

        if (timeLeft > 0) {
          countdownNumber.textContent = timeLeft;
        } else {
          // Countdown finished
          clearInterval(countdownInterval);
          countdownInterval = null;

          // Hide countdown overlay and barrier with smooth fade effect
          countdownBarrier.style.opacity = '0';
          countdownOverlay.style.opacity = '0';

          // Hide overlay completely after transition completes
          setTimeout(() => {
            countdownBarrier.style.display = 'none';
            countdownOverlay.style.display = 'none';
          }, 500); // Match duration-500 from Tailwind

          // Enable button
          publishButton.disabled = false;
          publishButton.classList.remove(
            'disabled:bg-gray-400',
            'disabled:cursor-not-allowed',
            'disabled:hover:bg-gray-400',
          );
          publishButton.classList.add('hover:bg-blue-700');
        }
      }, 1000);
    }

    function unpublishUjian(id) {
      if (
        confirm('Apakah Anda yakin ingin menyembunyikan ujian ini dari siswa?')
      ) {
        fetch(`/guru/ujian/${id}/unpublish`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute('content'),
            'Content-Type': 'application/json',
          },
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              location.reload();
            } else {
              alert('Gagal menyembunyikan ujian');
            }
          });
      }
    }

    function confirmPublish() {
      fetch(`/guru/ujian/${currentUjianId}/publish`, {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute('content'),
          'Content-Type': 'application/json',
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            location.reload();
          } else {
            alert(
              'Gagal mempublikasikan ujian: ' +
                (data.message || 'Unknown error'),
            );
          }
        })
        .finally(() => {
          closeModal('publishModal');
        });
    }

    function deleteUjian(id) {
      if (
        confirm(
          'Apakah Anda yakin ingin menghapus ujian ini? Tindakan ini tidak dapat dibatalkan.',
        )
      ) {
        fetch(`/guru/ujian/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': document
              .querySelector('meta[name="csrf-token"]')
              .getAttribute('content'),
          },
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              location.reload();
            } else {
              alert('Gagal menghapus ujian');
            }
          });
      }
    }

    function closeModal(modalId) {
      document.getElementById(modalId).classList.add('hidden');
      document.getElementById(modalId).classList.remove('flex');

      // Reset countdown if it's the publish modal
      if (modalId === 'publishModal') {
        resetCountdown();
      }
    }

    function resetCountdown() {
      if (countdownInterval) {
        clearInterval(countdownInterval);
        countdownInterval = null;
      }

      const countdownOverlay = document.getElementById('countdownOverlay');
      const countdownBarrier = document.getElementById('countdownBarrier');
      const countdownNumber = document.getElementById('countdownNumber');
      const hourglassIcon = document.getElementById('hourglassIcon');
      const publishButton = document.getElementById('publishButton');

      // Reset countdown overlay and barrier
      countdownNumber.textContent = '5';

      countdownBarrier.style.opacity = '1';
      countdownBarrier.style.display = 'block';

      countdownOverlay.style.opacity = '1';
      countdownOverlay.style.display = 'flex';

      // Reset hourglass icon and rotation
      if (hourglassIcon) {
        hourglassIcon.className =
          'fas fa-hourglass-half text-white text-sm transition-transform duration-1000';
        hourglassIcon.style.transform = 'rotate(0deg)';
      }

      // Reset button state
      publishButton.disabled = true;
      publishButton.classList.add(
        'disabled:bg-gray-400',
        'disabled:cursor-not-allowed',
        'disabled:hover:bg-gray-400',
      );
      publishButton.classList.remove('hover:bg-blue-700');
    }

    function filterUjian(type, value) {
      const rows = document.querySelectorAll('.ujian-row');

      rows.forEach((row) => {
        let show = true;

        if (type === 'status' && value) {
          show = show && row.dataset.status === value;
        }

        if (type === 'mapel' && value) {
          show = show && row.dataset.mapel === value;
        }

        row.style.display = show ? 'table-row' : 'none';
      });
    }

    function resetFilters() {
      document.querySelectorAll('select').forEach((select) => {
        select.value = '';
      });

      document.querySelectorAll('.ujian-row').forEach((row) => {
        row.style.display = 'table-row';
      });
    }
  </script>
@endpush

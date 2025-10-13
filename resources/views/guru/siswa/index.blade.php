@extends("layouts.app")

@section("content")
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
      >
        <h1 class="text-2xl font-bold text-gray-900">Data Siswa</h1>
      </div>
    </div>
    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow">
      <x-table
        :headers="['Nama', 'NIS', 'Kelas']"
        :searchableColumns="[0, 1, 2]"
        :sortableColumns="[0, 1, 2]"
      >
        @if ($mataPelajaran->count())
          @foreach ($mataPelajaran as $mapel)
            @foreach ($mapel->kelasMapel as $kelas)
              @foreach ($kelas->siswas as $item)
                <tr class="hover:bg-gray-50">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img
                          class="h-10 w-10 rounded-full object-cover"
                          src="{{ $item->user?->photo_profil_path ? asset("storage/" . $item->user->photo_profil_path) : "/images/profile/default.png" }}"
                          alt="{{ $item->user?->name }}"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">
                          {{ $item->user?->name }}
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $item->nis }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                    >
                      {{ $kelas->nama_kelas }}
                    </span>
                  </td>
                </tr>
              @endforeach
            @endforeach
          @endforeach
        @endif
      </x-table>
    </div>
  </div>
@endsection

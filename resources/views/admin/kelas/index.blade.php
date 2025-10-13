@extends("layouts.app")

@section("content")
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
      >
        <h1 class="text-2xl font-bold text-gray-900">Data Kelas</h1>
        <div
          class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3"
        >
          <a href="{{ route("admin.kelas.create") }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah Kelas
          </a>
        </div>
      </div>
    </div>

    <!-- Kelas Table -->
    <div class="bg-white rounded-lg shadow">
      <x-table
        :headers="['No', 'Nama Kelas', 'Aksi']"
        :searchableColumns="[1]"
        :sortableColumns="[0, 1]"
      >
        @foreach ($kelas as $item)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                {{ $loop->iteration }}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $item->nama_kelas }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex items-center space-x-2">
                <a
                  href="{{ route("admin.kelas.edit", $item->id) }}"
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400"
                >
                  <i class="fas fa-edit mr-1"></i>
                  Edit
                </a>

                <form
                  action="{{ route("admin.kelas.destroy", $item->id) }}"
                  method="POST"
                  class="inline form-delete"
                >
                  @csrf
                  @method("DELETE")
                  <button
                    type="button"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 btn-delete"
                    data-nama="{{ $item->nama_kelas }}"
                  >
                    <i class="fas fa-trash mr-1"></i>
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @endforeach
      </x-table>
    </div>
  </div>
@endsection

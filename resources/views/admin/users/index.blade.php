@extends("layouts.app")

@section("content")
  <div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
      <div
        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0"
      >
        <h1 class="text-2xl font-bold text-gray-900">Data User</h1>
        <div
          class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3"
        >
          <a href="{{ route("admin.users.create") }}" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>
            Tambah User
          </a>
          <button onclick="openModal('importModal')" class="btn-success">
            <i class="fas fa-upload mr-2"></i>
            Import User
          </button>
        </div>
      </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow">
      <x-table
        :headers="['Nama', 'Email', 'Role', 'Aksi']"
        :searchableColumns="[0, 1, 2]"
        :sortableColumns="[0, 1, 2]"
      >
        @foreach ($users as $user)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img
                    class="h-10 w-10 rounded-full object-cover"
                    src="{{ $user->photo_profil_path ? asset("storage/" . $user->photo_profil_path) : "/images/profile/default.png" }}"
                    alt="{{ $user->name }}"
                  />
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ $user->name }}
                  </div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ $user->email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 text-xs font-semibold rounded-full @if ($user->role == "admin")
                    bg-red-100
                    text-red-800
                @elseif ($user->role == "guru")
                    bg-blue-100
                    text-blue-800
                @else
                    bg-green-100
                    text-green-800
                @endif"
              >
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <div class="flex items-center space-x-2">
                <a
                  href="{{ route("admin.users.edit", $user->id) }}"
                  class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-400"
                >
                  <i class="fas fa-edit mr-1"></i>
                  Edit
                </a>

                <form
                  action="{{ route("admin.users.destroy", $user->id) }}"
                  method="POST"
                  class="inline form-delete"
                >
                  @csrf
                  @method("DELETE")
                  <button
                    type="button"
                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 btn-delete {{ $user->id === auth()->id() ? "opacity-50 cursor-not-allowed" : "" }}"
                    data-nama="{{ $user->name }}"
                    @if($user->id === auth()->id()) disabled @endif
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

  <!-- Import Modal -->
  <x-modal
    name="importModal"
    id="importModal"
    title="Import Data User"
    size="lg"
  >
    <form
      action="{{ route("admin.users.import") }}"
      method="POST"
      enctype="multipart/form-data"
    >
      @csrf
      <div class="space-y-4">
        <div>
          <label
            for="file"
            class="block text-sm font-medium text-gray-700 mb-2"
          >
            Pilih File (xlsx/csv)
          </label>
          <input
            type="file"
            name="file"
            id="file"
            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-blue-700"
            accept=".xlsx,.csv"
            required
          />
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
          <div class="flex">
            <div class="flex-shrink-0">
              <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div class="ml-3">
              <div class="text-sm text-blue-700">
                <p class="mb-2">
                  Format: name, email, password, role, kelas_id, nis, nip
                </p>
                <p>
                  Contoh File Excel Download
                  <a
                    href="{{ asset("contoh_users.csv") }}"
                    class="font-medium text-blue-600 hover:text-blue-500 underline"
                  >
                    Disini
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-6 flex justify-end space-x-3">
        <button
          type="button"
          onclick="closeModal('importModal')"
          class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary"
        >
          Batal
        </button>
        <button type="submit" class="btn-primary">
          <i class="fas fa-upload mr-2"></i>
          Import
        </button>
      </div>
    </form>
  </x-modal>
@endsection

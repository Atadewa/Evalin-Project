@extends("layouts.app")

@section('content')
   <div class="container-fluid mt-3">
    <div class="card card-body">
          <div class="d-flex justify-content-between">
              <h4 class="card-title">Data Soal Ujian</h4>
              <div>
                    <a href="{{ route('guru.soal.create') }}" class="btn btn-primary mb-3">Tambah Soal</a>
                    <button class="btn btn-success mb-3" data-toggle="modal" data-target="#importModal">
                        Import Soal
                    </button>
              </div>
          </div>
    <div class="table-responsive">
        <table class="table table-striped table-bordered zero-configuration">
            <thead>
                    <th>Ujian</th>
                    <th>Pertanyaan</th>
                    <th>Jawaban Benar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($soals as $soal)
                    <tr>
                        <td>{{ $soal->ujian->name }}</td>
                        <td>{!! $soal->pertanyaan !!}</td>
                        <td>{{ $soal->jawaban_benar }}</td>
                        <td>
                            <a href="{{ route('guru.soal.edit', $soal->id) }}" class="btn btn-sm btn-warning">Edit</a>

    <!-- Soal Table -->
    <div class="bg-white rounded-lg shadow">
      <x-table
        :headers="['Ujian', 'Pertanyaan', 'Jawaban Benar', 'Aksi']"
        :searchableColumns="[0, 1, 2]"
        :sortableColumns="[0, 1, 2]"
      >
        @foreach ($soals as $soal)
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm font-medium text-gray-900">
                {{ $soal->ujian->name }}
              </div>
            </td>
            <td class="px-6 py-4">
              <div
                class="text-sm text-gray-900 max-w-xs truncate"
                title="{!! strip_tags($soal->pertanyaan) !!}"
              >
                {!! $soal->pertanyaan !!}
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"
              >
                {{ $soal->jawaban_benar }}
              </span>
            </td>
            <td
              class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2"
            >
              <a
                href="{{ route("guru.soal.edit", $soal->id) }}"
                class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 px-2 py-1 rounded text-xs"
              >
                Edit
              </a>
              <form
                action="{{ route("guru.soal.destroy", $soal->id) }}"
                method="POST"
                class="inline form-delete"
              >
                @csrf
                @method("DELETE")
                <button
                  type="button"
                  class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded text-xs btn-delete"
                  data-nama="{{ strip_tags($soal->pertanyaan) }}"
                >
                  Hapus
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </x-table>
    </div>
  </div>

  <!-- Import Modal -->
  <div
    class="modal fade"
    id="importModal"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <form
        action="{{ route("admin.soal.import") }}"
        method="POST"
        enctype="multipart/form-data"
      >
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="importModalLabel">Import Data Soal</h5>
            <button type="button" class="close" data-dismiss="modal">
              <span>&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="file" class="form-label">Pilih File (xlsx/csv)</label>
              <input
                type="file"
                name="file"
                class="form-control"
                accept=".xlsx,.csv"
                required
              />
            </div>
            <div class="alert alert-info">
              Format: ujian id, pertanyaan, jawaban benar
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
            >
              Batal
            </button>
            <button type="submit" class="btn btn-primary">Import</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

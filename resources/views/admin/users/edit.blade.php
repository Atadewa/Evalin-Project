@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Update informasi pengguna {{ $user->name }}
                    </p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-lg shadow">
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('name', $user->name) }}" required />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('email', $user->email) }}" required />
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            placeholder="Biarkan kosong jika tidak diubah" />
                        <p class="mt-1 text-sm text-gray-500">
                            Kosongkan jika tidak ingin mengubah password
                        </p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="roleSelect" class="form-label">Role</label>
                        @if ($user->id === auth()->id() && $user->role === 'admin')
                            {{-- Admin yang sedang login tidak bisa ubah rolenya sendiri --}}
                            <select name="role" id="roleSelect" class="form-control bg-gray-100" disabled>
                                <option value="admin" selected>Admin</option>
                            </select>
                            <input type="hidden" name="role" value="admin" />
                            <p class="mt-1 text-sm text-gray-500">
                                Anda tidak dapat mengubah role Anda sendiri
                            </p>
                        @else
                            {{-- User lain: bisa ubah role --}}
                            <select name="role" id="roleSelect"
                                class="form-control @error('role') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                                required>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                    Admin
                                </option>
                                <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>
                                    Guru
                                </option>
                                <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>
                                    Siswa
                                </option>
                            </select>
                        @endif
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="nipField" class="{{ $user->role == 'guru' ? '' : 'hidden' }}">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="number" id="nip" name="nip"
                            class="form-control @error('nip') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('nip', $user->guru?->nip) }}" required />
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="nisField" class="{{ $user->role == 'siswa' ? '' : 'hidden' }}">
                        <label for="nis" class="form-label">NIS</label>
                        <input type="number" id="nis" name="nis"
                            class="form-control @error('nis') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                            value="{{ old('nis', $user->siswa?->nis) }}" required />
                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="kelasGroup" class="{{ $user->role == 'siswa' ? 'md:col-span-2' : 'hidden md:col-span-2' }}">
                        <label for="kelas_id" class="form-label">Kelas</label>
                        <select name="kelas_id" id="kelas_id"
                            class="form-control @error('kelas_id') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                                <option value="{{ $k->id }}"
                                    {{ old('kelas_id', optional($user->siswa)->kelas_id) == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                            Batal
                        </a>
                        <button type="submit" class="btn-success">
                            <i class="fas fa-save mr-2"></i>
                            Update User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const roleSelect = document.getElementById('roleSelect');
                const kelasGroup = document.getElementById('kelasGroup');
                const nisField = document.getElementById('nisField');
                const nipField = document.getElementById('nipField');
                const nisInput = document.getElementById('nis');
                const nipInput = document.getElementById('nip');

                function toggleFields() {
                    const role = roleSelect.value;

                    // Hide semua dulu
                    kelasGroup.classList.add('hidden');
                    nisField.classList.add('hidden');
                    nipField.classList.add('hidden');

                    // Nonaktifkan required
                    nisInput.removeAttribute('required');
                    nipInput.removeAttribute('required');

                    // Tampilkan & aktifkan sesuai role
                    if (role === 'siswa') {
                        kelasGroup.classList.remove('hidden');
                        kelasGroup.classList.add('md:col-span-2');
                        nisField.classList.remove('hidden');
                        nisInput.setAttribute('required', true);
                    } else if (role === 'guru') {
                        nipField.classList.remove('hidden');
                        nipInput.setAttribute('required', true);
                    }
                }

                if (roleSelect && !roleSelect.disabled) {
                    roleSelect.addEventListener('change', toggleFields);
                }

                toggleFields();
            });
        </script>
    @endpush
@endsection

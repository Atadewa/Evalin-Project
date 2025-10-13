@extends("layouts.app")

@section("content")
  <div class="container-fluid mt-3 min-h-full pb-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">{{ __("Profile") }}</h2>

    {{-- Update Profile Info --}}
    <div class="card mb-4">
      <div class="card-body">
        @include("profile.partials.update-profile-information-form")
      </div>
    </div>

    {{-- Update Password --}}
    <div class="card mb-6">
      <div class="card-header px-5 py-3 -mb-3">
        <h4 class="text-lg font-semibold text-gray-700 mb-2">{{ "Perbarui Password" }}</h4>
        <p class="text-sm text-gray-600 mb-0">
          {{ "Pastikan akun Anda menggunakan kata sandi yang kuat untuk tetap aman." }}
        </p>
      </div>

      <div class="card-body">
        <form method="POST" action="{{ route("password.update") }}">
          @csrf
          @method("put")

          <div class="mb-3">
            <label for="update_password_current_password" class="form-label">
              {{ "Password Saat Ini" }}
            </label>
            <input
              type="password"
              name="current_password"
              id="update_password_current_password"
              class="form-control @error("current_password", "updatePassword") is-invalid @enderror"
              autocomplete="current-password"
            />
            @error("current_password", "updatePassword")
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label for="update_password_password" class="form-label">
              {{ "Password Baru" }}
            </label>
            <input
              type="password"
              name="password"
              id="update_password_password"
              class="form-control @error("password", "updatePassword") is-invalid @enderror"
              autocomplete="new-password"
            />
            @error("password", "updatePassword")
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label
              for="update_password_password_confirmation"
              class="form-label"
            >
              {{ "Konfirmasi Password Baru" }}
            </label>
            <input
              type="password"
              name="password_confirmation"
              id="update_password_password_confirmation"
              class="form-control @error("password_confirmation", "updatePassword") is-invalid @enderror"
              autocomplete="new-password"
            />
            @error("password_confirmation", "updatePassword")
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary">
              {{ "Simpan" }}
            </button>

            @if (session("status") === "password-updated")
              <span class="text-success">{{ "Berhasil Disimpan." }}</span>
            @endif
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection

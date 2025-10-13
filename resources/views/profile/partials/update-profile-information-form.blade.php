<section class="mb-6">
  <header class="mb-4">
    <h2 class="text-lg font-semibold text-gray-700 mb-2">
      {{ "Informasi Profil" }}
    </h2>
    <p class="text-sm text-gray-600 mb-4">
      {{ "Perbarui informasi profil dan alamat email akun Anda." }}
    </p>
  </header>

  {{-- Form untuk kirim ulang verifikasi email --}}
  <form
    id="send-verification"
    method="POST"
    action="{{ route("verification.send") }}"
  >
    @csrf
  </form>

  {{-- Form update profile --}}
  <form
    method="POST"
    action="{{ route("profile.update") }}"
    enctype="multipart/form-data"
  >
    @csrf
    @method("patch")

    {{-- Nama --}}
    <div class="mb-3">
      <label for="name" class="form-label">Nama</label>
      <input
        id="name"
        name="name"
        type="text"
        class="form-control @error("name") is-invalid @enderror"
        value="{{ old("name", $user->name) }}"
        required
        autofocus
        autocomplete="name"
      />
      @error("name")
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Email --}}
    <div class="mb-3">
      <label for="email" class="form-label">{{ __("Email") }}</label>
      <input
        id="email"
        name="email"
        type="email"
        class="form-control @error("email") is-invalid @enderror"
        value="{{ old("email", $user->email) }}"
        required
        autocomplete="username"
      />
      @error("email")
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror

      {{-- Jika belum verifikasi --}}
      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="mt-3">
          <p class="text-warning">
            {{ "Alamat email Anda belum diverifikasi." }}
            <button
              form="send-verification"
              class="btn btn-link p-0 m-0 align-baseline"
            >
              {{ "Klik di sini untuk mengirim ulang email verifikasi." }}
            </button>
          </p>

          @if (session("status") === "verification-link-sent")
            <p class="text-success mt-2">
              {{ "Tautan verifikasi baru telah dikirim ke alamat email Anda." }}
            </p>
          @endif
        </div>
      @endif
    </div>

    {{-- Upload Foto --}}
    <div class="mb-3">
      <label for="profile_photo" class="form-label">
        {{ "Foto Profil" }}
      </label>
      <input
        id="profile_photo"
        name="profile_photo"
        type="file"
        class="form-control @error("profile_photo") is-invalid @enderror"
        accept="image/*"
      />
      @error("profile_photo")
        <div class="invalid-feedback">{{ $message }}</div>
      @enderror
    </div>

    {{-- Tombol Simpan --}}
    <div class="d-flex align-items-center gap-3">
      <button type="submit" class="btn btn-primary">{{ "Simpan" }}</button>

      @if (session("status") === "profile-updated")
        <span class="text-success">{{ "Berhasil Disimpan." }}</span>
      @endif
    </div>
  </form>

  {{-- Foto lama + tombol delete dipisah dari form update --}}
  @if ($user->photo_profil_path)
    <div class="mt-6 mb-4">
      <img
        src="{{ asset("storage/" . $user->photo_profil_path) }}"
        alt="Profile Photo"
        width="100"
        class="rounded-circle mb-2"
      />

      <form method="POST" action="{{ route("profile.photo.destroy") }}">
        @csrf
        @method("delete")
        <button type="submit" class="btn btn-danger btn-sm">
          {{ __("Delete Photo") }}
        </button>
      </form>
    </div>
  @else
    {{-- Add spacing when no photo exists to maintain consistent layout --}}
    <div class="mt-6 mb-4">
      <p class="text-sm text-gray-600">
        {{ "Belum ada foto profil yang diunggah." }}
      </p>
    </div>
  @endif

  {{-- Pesan setelah foto dihapus --}}
  @if (session("status") === "profile-photo-deleted")
    <div class="mt-2 mb-4">
      <span class="text-danger d-block">Foto profil telah dihapus.</span>
    </div>
  @endif
</section>

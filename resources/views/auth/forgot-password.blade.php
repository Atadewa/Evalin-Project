<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password - EVALIN</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
  </head>
  <body class="bg-blue-50 min-h-screen flex flex-col">
    <div
      class="bg-blue-800 p-4 text-white border-b-4 border-yellow-400 flex items-center"
    >
      <img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-12 mr-4" />
    </div>

    <div class="flex-1 flex flex-col items-center justify-center px-4">
      <h1 class="text-center text-xl font-bold mb-1 text-gray-800">
        LUPA PASSWORD?
      </h1>
      <p class="text-center text-gray-600 mb-8">
        Masukkan email Anda untuk menerima link reset password
      </p>

      <div class="bg-white rounded-lg p-8 shadow-lg w-full max-w-md">
        <!-- Session Status -->
        @if (session("status"))
          <div
            class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-4"
          >
            {{ session("status") }}
          </div>
        @endif

        <form
          method="POST"
          action="{{ route("password.email") }}"
          class="space-y-4"
        >
          @csrf

          <!-- Email -->
          <div>
            <input
              type="email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary @error("email") border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
              name="email"
              value="{{ old("email") }}"
              required
              autofocus
              placeholder="Email"
            />
            @error("email")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <button
            type="submit"
            class="w-full bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
          >
            Kirim Link Reset Password
          </button>
        </form>

        <div class="mt-6 text-center">
          <a
            href="{{ route("login") }}"
            class="text-primary hover:text-blue-700 text-sm font-medium"
          >
            ← Kembali ke Login
          </a>
        </div>
      </div>
    </div>
  </body>
</html>

<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Confirm Password - EVALIN</title>
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
        KONFIRMASI PASSWORD
      </h1>
      <p class="text-center text-gray-600 mb-8">
        Silakan masukkan password Anda untuk melanjutkan
      </p>

      <div class="bg-white rounded-lg p-8 shadow-lg w-full max-w-md">
        <form
          method="POST"
          action="{{ route("password.confirm") }}"
          class="space-y-4"
        >
          @csrf

          <!-- Password -->
          <div>
            <input
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Password"
            />
            @error("password")
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <button
            type="submit"
            class="w-full bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
          >
            Konfirmasi
          </button>
        </form>
      </div>
    </div>
  </body>
</html>

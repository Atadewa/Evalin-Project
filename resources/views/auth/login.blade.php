<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Evalin</title>
    @vite(["resources/css/app.css", "resources/js/app.js"])
  </head>
  <body class="bg-blue-50 min-h-screen flex flex-col">
    <div class="flex-1 flex flex-col items-center justify-center px-4">
      <img src="{{ asset("images/logo.png") }}" alt="Logo" class="h-[300px] -mt-20 -mb-10" />

      <h1 class="text-center text-xl font-bold mb-1 text-gray-800">
        Evalin: Kebenaran dalam Setiap Jawaban
      </h1>
      <p class="text-center text-gray-600 mb-8">
        Platform Ujian dengan Koreksi Otomatis
      </p>

      <div class="bg-white rounded-lg p-8 shadow-lg w-full max-w-md">
        <form method="POST" action="{{ route("loggedin") }}" class="space-y-4">
          @csrf
          <div>
            <label
              for="email"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Email
            </label>
            <input
              id="email"
              type="email"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
              name="email"
              value="{{ old("email") }}"
              required
              autofocus
              placeholder="Masukkan email"
            />
          </div>
          <div>
            <label
              for="password"
              class="block text-sm font-medium text-gray-700 mb-1"
            >
              Password
            </label>
            <input
              id="password"
              type="password"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
              name="password"
              required
              placeholder="Masukkan password"
            />
          </div>
          @if ($errors->has("email"))
            <div
              class="bg-red-50 border border-red-200 text-red-800 px-4 py-2 rounded-md text-sm"
              id="login-error"
              data-login-error="true"
            >
              {{ $errors->first("email") }}
            </div>
          @endif

          <div class="flex items-center">
            <input
              type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
              id="remember_me"
              name="remember"
            />
            <label class="ml-2 block text-sm text-gray-700" for="remember_me">
              {{ __("Remember me") }}
            </label>
          </div>
          <button
            type="submit"
            class="w-full bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
          >
            {{ __("Log in") }}
          </button>
        </form>
      </div>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        if (!form) return;

        const removeError = () => {
          const errorEl = document.querySelector('[data-login-error="true"]');
          if (errorEl) errorEl.remove();
        };

        const email = form.querySelector('input[name="email"]');
        const password = form.querySelector('input[name="password"]');

        // Clear error when user edits inputs
        [email, password].forEach(
          (el) => el && el.addEventListener('input', removeError),
        );

        // Clear error immediately on submit so UI updates before navigation
        form.addEventListener('submit', function () {
          removeError();
          const btn = form.querySelector('button[type="submit"]');
          if (btn) btn.disabled = true; // optional UX: avoid double submit
        });
      });
    </script>
  </body>
</html>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Register - EVALIN</title>
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="{{ asset("images/logo.png") }}"
    />
    @vite(["resources/css/app.css", "resources/js/app.js"])
  </head>

  <body class="bg-blue-50 min-h-screen">
    <!-- Preloader -->
    <div
      id="preloader"
      class="fixed inset-0 z-50 bg-white flex items-center justify-center"
    >
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
      ></div>
    </div>

    <div class="min-h-screen flex items-center justify-center px-4 py-8">
      <div class="w-full max-w-md">
        <div class="bg-white rounded-lg shadow-lg p-8">
          <div class="text-center mb-6">
            <img
              src="{{ asset("images/logo.png") }}"
              alt="EVALIN"
              class="h-16 mx-auto mb-4"
            />
            <h1 class="text-2xl font-bold text-gray-900">Register</h1>
            <p class="text-gray-600 text-sm">Create your account</p>
          </div>

          <!-- Session Errors -->
          @if ($errors->any())
            <div
              class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-4"
            >
              <ul class="text-sm space-y-1">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form
            method="POST"
            action="{{ route("register") }}"
            class="space-y-4"
          >
            @csrf

            <div>
              <input
                type="text"
                name="name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
                value="{{ old("name") }}"
                required
                autofocus
                placeholder="Full Name"
              />
            </div>

            <div>
              <input
                type="email"
                name="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
                value="{{ old("email") }}"
                required
                placeholder="Email"
              />
            </div>

            <div>
              <input
                type="password"
                name="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
                required
                placeholder="Password"
              />
            </div>

            <div>
              <input
                type="password"
                name="password_confirmation"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary"
                required
                placeholder="Confirm Password"
              />
            </div>

            <button
              type="submit"
              class="w-full bg-primary hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
            >
              Register
            </button>
          </form>

          <p class="mt-6 text-center text-sm text-gray-600">
            Already have an account?
            <a
              href="{{ route("login") }}"
              class="text-primary hover:text-blue-700 font-medium"
            >
              Login
            </a>
          </p>
        </div>
      </div>
    </div>
  </body>
</html>

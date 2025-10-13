<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Evalin</title>

    <!-- Favicon -->
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="{{ asset("images/logo.png") }}"
    />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />

    <!-- Bootstrap Icons -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
      rel="stylesheet"
    />

    <!-- Tailwind CSS and Alpine.js -->
    @vite(["resources/css/app.css", "resources/js/app.js"])

    <style>
      html,
      body {
        overflow-x: hidden !important;
        max-width: 100vw !important;
      }

      * {
        box-sizing: border-box;
      }

      .table-container {
        width: 100%;
        overflow-x: auto;
        overflow-y: visible;
      }

      .table-container table {
        min-width: 1200px;
        width: 1200px;
      }
    </style>

    @yield("css")
    @stack("styles")
  </head>

  <body class="bg-gray-100 overflow-x-hidden min-h-screen flex flex-col">
    <!--*******************
        Preloader start
    ********************-->
    <div
      id="preloader"
      class="fixed inset-0 z-50 bg-white flex items-center justify-center"
    >
      <div
        class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
      ></div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div class="flex flex-1 bg-gray-100 w-full max-w-full main-wrapper">
      <!--**********************************
            Sidebar start
        ***********************************-->
      @include("layouts.sidebar")
      <!--**********************************
            Sidebar end
        ***********************************-->

      <!--**********************************
            Main content start
        ***********************************-->
      <div class="flex-1 flex flex-col min-w-0 min-h-screen content-wrapper">
        <!--**********************************
              Header start
          ***********************************-->
        <header
          class="bg-white shadow-sm border-b border-gray-200 h-[70px] flex-shrink-0"
        >
          <div class="flex items-center justify-between px-6 py-4">
            <div class="flex items-center">
              <button
                id="sidebarToggle"
                class="lg:hidden p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary"
              >
                <img
                  src="{{ asset("images/hamburger-sidebar.svg") }}"
                  alt=""
                  class="h-6 w-6"
                />
              </button>
              <button
                id="desktopSidebarToggle"
                class="hidden lg:block p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary"
              >
                <img
                  src="{{ asset("images/hamburger-sidebar.svg") }}"
                  alt=""
                  class="h-6 w-6"
                />
              </button>
              <h1
                class="md:ml-2 text-sm md:text-xl font-semibold text-gray-900"
              >
                Sistem Ujian Online
              </h1>
            </div>
            <div
              class="relative"
              x-data="{ open: false }"
              x-init="
                () => {
                  console.log('Profile dropdown initialized')
                }
              "
            >
              <button
                @click="open = !open"
                class="flex items-center space-x-2 text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-all duration-200 hover:shadow-md"
                type="button"
              >
                <div class="flex items-center space-x-2">
                  <img
                    class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 flex-shrink-0"
                    src="{{ auth()->user()->photo_profil_path ? asset("storage/" . auth()->user()->photo_profil_path) : asset("images/profile/default.png") }}"
                    alt="{{ auth()->user()->name }}"
                    onerror="this.src='{{ asset("images/profile/default.png") }}'"
                  />
                  <span
                    class="hidden md:block text-gray-700 font-medium max-w-[150px] truncate"
                  >
                    {{ auth()->user()->name }}
                  </span>
                  <i
                    class="fas fa-chevron-down text-gray-400 transition-transform duration-200"
                    :class="{ 'rotate-180': open }"
                  ></i>
                </div>
              </button>

              <div
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-[9999] border border-gray-200"
                style="display: none"
              >
                <div class="px-4 py-2 border-b border-gray-100">
                  <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auth()->user()->name }}
                  </p>
                  <p class="text-xs text-gray-500 truncate break-all">
                    {{ auth()->user()->email }}
                  </p>
                </div>
                <a
                  href="{{ route("profile.edit") }}"
                  class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 group transition-colors duration-150"
                >
                  <i
                    class="fas fa-user mr-2 text-primary group-hover:text-blue-700"
                  ></i>
                  Profile
                </a>
                <hr class="border-gray-100" />
                <form method="POST" action="{{ route("logout") }}">
                  @csrf
                  <button
                    type="submit"
                    class="flex items-center w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 group transition-colors duration-150"
                  >
                    <i
                      class="fas fa-sign-out-alt mr-2 text-red-500 group-hover:text-red-600"
                    ></i>
                    Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </header>
        <!--**********************************
              Header end
          ***********************************-->

        <!--**********************************
              Content body start
          ***********************************-->
        <main
          class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6 min-w-0 flex-grow main-content"
        >
          <div class="w-full max-w-full">
            @yield("content")
          </div>
        </main>
        <!--**********************************
              Content body end
          ***********************************-->

        <!--**********************************
              Footer start
          ***********************************-->
        <footer
          class="bg-white border-t border-gray-200 px-6 py-4 flex-shrink-0 mt-auto w-full"
        >
          <div class="text-center text-sm text-gray-600 w-full">
            © {{ date("Y") }} EVALIN - SISTEM UJIAN ONLINE. All rights
            reserved.
          </div>
        </footer>
        <!--**********************************
              Footer end
          ***********************************-->
      </div>
      <!--**********************************
            Main content end
        ***********************************-->
    </div>
    <!--**********************************
        Main wrapper end
    ***********************************-->

    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Summernote (Rich Text Editor) -->
    <link
      href="{{ asset("plugins/summernote/dist/summernote.css") }}"
      rel="stylesheet"
    />
    <script src="{{ asset("plugins/summernote/dist/summernote.min.js") }}"></script>

    @yield("js")
    @stack("scripts")

    <script>
      // Function to configure SweetAlert2 positioning
      function configureSwalPosition() {
          const sidebar = document.getElementById('sidebar');
          const isCollapsed = sidebar && sidebar.classList.contains('sidebar-collapsed');
          const sidebarWidth = isCollapsed ? '4rem' : '16rem';

          return {
              customClass: {
                  container: 'swal2-container-custom'
              },
              backdrop: 'rgba(0, 0, 0, 0.4)',
              position: 'center',
              allowOutsideClick: true,
              didOpen: (popup) => {
                  const container = popup.closest('.swal2-container');
                  if (container) {
                      // For desktop with sticky sidebar
                      if (window.innerWidth > 1024) {
                          container.style.left = sidebarWidth;
                          container.style.width = `calc(100% - ${sidebarWidth})`;
                          container.style.top = '0';
                          container.style.height = '100vh';
                          container.style.zIndex = '999';
                      } else {
                          // For mobile, show full screen
                          container.style.left = '0';
                          container.style.width = '100%';
                          container.style.top = '70px';
                          container.style.height = 'calc(100vh - 70px)';
                          container.style.zIndex = '999';
                      }
                  }
              }
          };
      }

      document.querySelectorAll('.btn-delete').forEach(button => {
              button.addEventListener('click', function () {
                  const form = this.closest('form');
                  const nama = this.getAttribute('data-nama');

                  Swal.fire({
                      ...configureSwalPosition(),
                      title: 'Yakin ingin menghapus?',
                      text: `Data "${nama}" akan dihapus secara permanen!`,
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#e3342f',
                      cancelButtonColor: '#6c757d',
                      confirmButtonText: 'Ya, hapus!',
                      cancelButtonText: 'Batal'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          form.submit();
                      }
                  });
              });
          });

          @if ($errors->any())
              @foreach ($errors->all() as $error)
                  toastr.error("{{ $error }}", "Error", {
                      closeButton: true,
                      progressBar: true,
                      timeOut: 5000
                  });
              @endforeach
          @endif

          @if (session('success'))
              toastr.success("{{ session('success') }}", "Success", {
                  closeButton: true,
                  progressBar: true,
                  timeOut: 5000
              });
          @endif

          @if (session('error'))
              toastr.error("{{ session('error') }}", "Error", {
                  closeButton: true,
                  progressBar: true,
                  timeOut: 5000
              });
          @endif

          document.querySelectorAll('.btn-koreksi').forEach(button => {
              button.addEventListener('click', function(event) {
                  event.preventDefault(); 
                  const ujianId = this.getAttribute('data-id');

                  Swal.fire({
                      ...configureSwalPosition(),
                      title: 'Loading...',
                      text: 'Sedang memproses, harap tunggu...',
                      allowOutsideClick: false,
                      didOpen: (popup) => {
                          // Apply positioning first
                          const config = configureSwalPosition();
                          if (config.didOpen) {
                              config.didOpen(popup);
                          }
                          // Then show loading
                          Swal.showLoading();
                      }
                  });

                  const url = "{{ route('guru.ujian.show.koreksi', ':id') }}".replace(':id', ujianId);

                  fetch(url)
                      .then(response => {
                          if (response.ok) {
                              Swal.fire({
                                  ...configureSwalPosition(),
                                  icon: 'success',
                                  title: 'Sukses!',
                                  text: 'Ujian siswa telah dinilai.',
                              }).then(() => {
                                  location.reload();
                              });
                          } else {
                              Swal.fire({
                                  ...configureSwalPosition(),
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Terjadi kesalahan!'
                              });
                          }
                      })
                      .catch(error => {
                          Swal.fire({
                              ...configureSwalPosition(),
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Terjadi kesalahan!'
                          });
                      });
              });
          });

          document.querySelectorAll('.btn-koreksi-siswa').forEach(button => {
              button.addEventListener('click', function(event) {
                  event.preventDefault(); 
                  const ujianId = this.getAttribute('data-id');
                   const siswaId = this.getAttribute('data-siswa');

                  Swal.fire({
                      ...configureSwalPosition(),
                      title: 'Loading...',
                      text: 'Sedang memproses, harap tunggu...',
                      allowOutsideClick: false,
                      didOpen: (popup) => {
                          // Apply positioning first
                          const config = configureSwalPosition();
                          if (config.didOpen) {
                              config.didOpen(popup);
                          }
                          // Then show loading
                          Swal.showLoading();
                      }
                  });

                  const url = "{{ route('guru.ujian.show.koreksiUjianSiswaPersiswa', [':ujianid', ':siswaid']) }}"
                  .replace(':ujianid', ujianId)
                  .replace(':siswaid', siswaId);
                  fetch(url)
                      .then(response => {
                          console.log(response);
                          if (response.ok) {
                              Swal.fire({
                                  ...configureSwalPosition(),
                                  icon: 'success',
                                  title: 'Sukses!',
                                  text: 'Ujian siswa telah dinilai.',
                              }).then(() => {
                                  window.location.hash = '#info'; 
                                  location.reload();
                              });
                          } else {
                              Swal.fire({
                                  ...configureSwalPosition(),
                                  icon: 'error',
                                  title: 'Oops...',
                                  text: 'Terjadi kesalahan!'
                              });
                          }
                      })
                      .catch(error => {
                          Swal.fire({
                              ...configureSwalPosition(),
                              icon: 'error',
                              title: 'Oops...',
                              text: 'Terjadi kesalahan!'
                          });
                      });
              });
          });

      // Update modal positioning when sidebar is toggled
      function updateModalPositioning() {
          const activeModal = document.querySelector('.swal2-container');
          if (activeModal) {
              const sidebar = document.getElementById('sidebar');
              const isCollapsed = sidebar && sidebar.classList.contains('sidebar-collapsed');
              const sidebarWidth = isCollapsed ? '4rem' : '16rem';

              activeModal.style.left = sidebarWidth;
              activeModal.style.width = `calc(100% - ${sidebarWidth})`;

              // For mobile, show full screen
              if (window.innerWidth <= 1024) {
                  activeModal.style.left = '0';
                  activeModal.style.width = '100%';
              }
          }
      }

      // Listen for sidebar toggle events
      document.addEventListener('click', function(e) {
          if (e.target.id === 'sidebarToggle' || e.target.id === 'desktopSidebarToggle' || 
              e.target.closest('#sidebarToggle') || e.target.closest('#desktopSidebarToggle')) {
              // Use setTimeout to wait for the sidebar animation to complete
              setTimeout(updateModalPositioning, 300);
          }
      });

      // Listen for window resize
      window.addEventListener('resize', updateModalPositioning);

      // Ensure profile dropdown always works
      function initializeProfileDropdown() {
          const profileDropdown = document.querySelector('[x-data] > button');
          const profileMenu = document.querySelector('[x-show]');

          if (profileDropdown && profileMenu) {
              // Ensure the image loads correctly
              const profileImg = profileDropdown.querySelector('img');
              if (profileImg) {
                  profileImg.onerror = function() {
                      this.src = '{{ asset("images/profile/default.png") }}';
                  };

                  // Force image to be visible
                  profileImg.style.display = 'block';
                  profileImg.style.minWidth = '2rem';
                  profileImg.style.minHeight = '2rem';
              }

              // Add manual toggle if Alpine.js fails
              let isOpen = false;
              profileDropdown.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();

                  isOpen = !isOpen;
                  profileMenu.style.display = isOpen ? 'block' : 'none';
              });

              // Close on outside click
              document.addEventListener('click', function(e) {
                  if (!profileDropdown.contains(e.target) && !profileMenu.contains(e.target)) {
                      isOpen = false;
                      profileMenu.style.display = 'none';
                  }
              });
          }
      }

      document.addEventListener('DOMContentLoaded', function () {
          const hash = window.location.hash;

          if (hash === '#info') {
              document.querySelectorAll('.nav-link, .tab-pane').forEach(el => {
                  el.classList.remove('active', 'show');
              });
              const tabLink = document.querySelector(`a[href="${hash}"]`);
              if (tabLink) {
                  tabLink.classList.add('active');
              }
              const tabPane = document.querySelector(hash);
              if (tabPane) {
                  tabPane.classList.add('active', 'show');
              }
          }

          // Initialize profile dropdown
          setTimeout(initializeProfileDropdown, 100);
      });
    </script>
  </body>
</html>

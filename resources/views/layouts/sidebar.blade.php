<!-- Sidebar -->
<div
  id="sidebar"
  class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform -translate-x-full transition-all duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-0 lg:h-screen overflow-y-auto"
>
  <!-- Logo Area -->
  <div
    class="flex items-center justify-center h-[70px] bg-white border-b border-gray-200"
  >
    <a href="{{ url("/dashboard") }}" class="flex items-center space-x-2">
      <img
        src="{{ asset("images/logo.png") }}"
        alt="EVALIN"
        class="h-[100px] w-auto sidebar-logo"
      />
    </a>
  </div>

  <!-- Navigation -->
  <nav class="mt-6">
    <!-- Dashboard Section -->
    <div class="px-4 mb-6">
      <p
        class="sidebar-section-title text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3"
      >
        Dashboard
      </p>
      <a
        href="{{ url("/dashboard") }}"
        class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 {{ request()->is("dashboard") ? "bg-primary text-white" : "" }}"
        title="Dashboard"
      >
        <i class="fas fa-home sidebar-icon flex-shrink-0"></i>
        <span class="sidebar-text ml-3">Dashboard</span>
      </a>
    </div>

    @if (auth()->user()->role == "admin")
      <!-- Admin Section -->
      <div class="px-4 mb-6">
        <p
          class="sidebar-section-title text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3"
        >
          Admin
        </p>

        <a
          href="{{ route("admin.users.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 mb-2 {{ request()->routeIs("admin.users.*") ? "bg-primary text-white" : "" }}"
          title="User"
        >
          <i class="fas fa-users sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">User</span>
        </a>

        <a
          href="{{ route("admin.kelas.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 mb-2 {{ request()->routeIs("admin.kelas.*") ? "bg-primary text-white" : "" }}"
          title="Kelas"
        >
          <i class="fas fa-graduation-cap sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">Kelas</span>
        </a>

        <a
          href="{{ route("admin.mata-pelajaran.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 {{ request()->routeIs("admin.mata-pelajaran.*") ? "bg-primary text-white" : "" }}"
          title="Mata Pelajaran"
        >
          <i class="fas fa-book sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">Mata Pelajaran</span>
        </a>
      </div>
    @elseif (auth()->user()->role == "guru")
      <!-- Guru Section -->
      <div class="px-4 mb-6">
        <p
          class="sidebar-section-title text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3"
        >
          Guru
        </p>

        <a
          href="{{ route("guru.siswa.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 mb-2 {{ request()->routeIs("guru.siswa.*") ? "bg-primary text-white" : "" }}"
          title="Siswa"
        >
          <i class="fas fa-user-graduate sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">Siswa</span>
        </a>

        <a
          href="{{ route("guru.ujian.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 {{ request()->routeIs("guru.ujian.*") ? "bg-primary text-white" : "" }}"
          title="Jadwal Ujian"
        >
          <i class="fas fa-calendar-alt sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">Jadwal Ujian</span>
        </a>
      </div>
    @else
      <!-- Siswa Section -->
      <div class="px-4 mb-6">
        <p
          class="sidebar-section-title text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3"
        >
          Siswa
        </p>

        <a
          href="{{ route("siswa.ujian.index") }}"
          class="flex items-center px-4 py-3 text-gray-700 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200 {{ request()->routeIs("siswa.ujian.*") ? "bg-primary text-white" : "" }}"
          title="Jadwal Ujian"
        >
          <i class="fas fa-calendar-alt sidebar-icon flex-shrink-0"></i>
          <span class="sidebar-text ml-3">Jadwal Ujian</span>
        </a>
      </div>
    @endif
  </nav>
</div>

<!-- Sidebar Overlay (for mobile) -->
<div
  id="sidebarOverlay"
  class="fixed inset-0 z-40 bg-black bg-opacity-50 hidden lg:hidden"
></div>

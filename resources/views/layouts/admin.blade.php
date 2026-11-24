<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Tong Sampah App') - Tong Sampah App</title>

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.ico') }}">

  <!-- Local Third-Party Libraries (100% Offline Compatible) -->
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-icons/bootstrap-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/apexcharts/apexcharts.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">

  <!-- Main Design System & Custom Stylesheet -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

  @stack('styles')
</head>

<body>

  <!-- ==========================================
         START: Sidebar Component
         ========================================== -->
  <div class="sidebar-wrapper" id="sidebar">
    <!-- Brand Logo / Identity -->
    <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
      <i class="bi bi-trash"></i>
      <span>Tong Sampah</span>
    </a>

    <!-- Navigation Menu -->
    <div class="flex-grow-1 overflow-y-auto">
      <!-- Group: Menu -->
      <div class="sidebar-menu-section">
        <div class="sidebar-menu-title">Menu</div>
        <ul class="sidebar-menu-list">
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.dashboard') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
              id="menu-overview" title="Overview">
              <i class="bi bi-grid-fill"></i>
              <span>Dashboard</span>
            </a>
          </li>

          @can('admin')
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.kategori.index') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}"
              id="menu-kategori" title="Kategori Sampah">
              <i class="bi bi-tags-fill"></i>
              <span>Kategori Sampah</span>
            </a>
          </li>
          @endcan

          @canany(['admin', 'petugas'])
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.nasabah.index') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.nasabah.*') ? 'active' : '' }}"
              id="menu-nasabah" title="Cari Nasabah">
              <i class="bi bi-search"></i>
              <span>Cari Nasabah</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.setoran.index') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.setoran.*') ? 'active' : '' }}"
              id="menu-setoran" title="Input Setoran">
              <i class="bi bi-box-arrow-in-down"></i>
              <span>Input Setoran</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.penarikan.index') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.penarikan.*') ? 'active' : '' }}"
              id="menu-penarikan" title="Verifikasi Penarikan">
              <i class="bi bi-check2-circle"></i>
              <span>Verifikasi Penarikan</span>
            </a>
          </li>
          @endcanany

          @can('admin')
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.laporan.setoran') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}"
              id="menu-laporan" title="Laporan">
              <i class="bi bi-bar-chart-fill"></i>
              <span>Laporan</span>
            </a>
          </li>
          <li class="sidebar-menu-item">
            <a href="{{ route('admin.users.index') }}"
              class="sidebar-menu-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
              id="menu-users" title="Kelola User">
              <i class="bi bi-people-fill"></i>
              <span>Kelola User</span>
            </a>
          </li>
          @endcan
        </ul>
      </div>
    </div>

    <!-- Sidebar Profile Card -->
    <div class="sidebar-profile">
      <img src="{{ asset('assets/images/avatar.png') }}" alt="{{ auth()->user()->name }}" class="sidebar-profile-img"
        onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=256&auto=format&fit=crop'">
      <div class="sidebar-profile-info">
        <div class="sidebar-profile-name">{{ auth()->user()->name }}</div>
        <div class="sidebar-profile-email">{{ auth()->user()->role->label() }}</div>
      </div>
    </div>
  </div>
  <!-- ==========================================
         END: Sidebar Component
         ========================================== -->


  <!-- ==========================================
         START: Main Content Area
         ========================================== -->
  <div class="main-wrapper">

    <!-- START: Top Navbar Component -->
    <header class="navbar-custom">
      <div class="navbar-left">
        <button class="btn-desktop-toggle d-none d-xl-flex align-items-center justify-content-center me-3"
          id="desktop-sidebar-toggle" aria-label="Minimize Sidebar">
          <i class="bi bi-chevron-bar-left"></i>
        </button>
        <button class="sidebar-toggle-btn me-2" id="sidebar-toggle" aria-label="Toggle Navigation">
          <i class="bi bi-list"></i>
        </button>
      </div>

      <div class="navbar-search-wrapper">
        <input type="text" class="navbar-search-input" placeholder="Search anything..." id="main-search">
        <button class="navbar-search-btn" aria-label="Search">
          <i class="bi bi-search"></i>
        </button>
      </div>

      <div class="navbar-actions">
        <button class="navbar-action-btn me-1" aria-label="Toggle Fullscreen" id="btn-fullscreen">
          <i class="bi bi-arrows-fullscreen"></i>
        </button>

        <!-- Profile Dropdown -->
        <div class="dropdown ms-2">
          <button class="navbar-profile-btn dropdown-toggle" type="button" data-bs-toggle="dropdown"
            aria-expanded="false" id="profile-dropdown">
            <img src="{{ asset('assets/images/avatar.png') }}" alt="Profile Image" class="navbar-profile-img">
            <span class="navbar-profile-name d-none d-md-inline">{{ auth()->user()->name }}</span>
            <i class="bi bi-chevron-down navbar-profile-caret"></i>
          </button>
          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profile-dropdown">
            <li class="dropdown-header">Halo, {{ auth()->user()->name }}</li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li>
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="dropdown-item text-danger">
                  <i class="bi bi-box-arrow-right"></i> Logout
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </header>
    <!-- END: Top Navbar Component -->

    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show mx-4 mt-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show mx-4 mt-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    @yield('content')

    <!-- START: Footer Component -->
    <footer class="footer-custom">
      <div class="footer-left">
        <span class="footer-logo">
          <i class="bi bi-asterisk"></i> Tong Sampah
        </span>
        <span class="footer-separator">|</span>
        <span class="footer-copy">&copy; {{ date('Y') }} Tong Sampah App</span>
      </div>
    </footer>
    <!-- END: Footer Component -->

  </div>
  <!-- ==========================================
         END: Main Content Area
         ========================================== -->

  <!-- Local Third-Party Libraries Script dependencies -->
  <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>

  <!-- Local dashboard interactions controller -->
  <script src="{{ asset('assets/js/dashboard.js') }}"></script>

  @stack('scripts')
</body>

</html>

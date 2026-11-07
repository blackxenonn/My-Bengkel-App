<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'My Bengkel App')</title>

    {{-- Baca status sidebar sebelum halaman ditampilkan --}}
    <script>
        try {
            if (localStorage.getItem('bengkelSidebarCollapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (error) {
            // Jika localStorage tidak tersedia, gunakan sidebar normal.
        }
    </script>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- Bootstrap Icons --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    @vite(['resources/css/app.css'])

    <style>
        /* ========================================
           LAYOUT UTAMA
        ======================================== */

        .app-layout-row {
            min-height: 100vh;
            flex-wrap: nowrap;
        }

        .sidebar {
            flex: 0 0 16.666667%;
            width: 16.666667%;
            max-width: 16.666667%;
            min-height: 100vh;
            background: #172033;
            overflow-x: hidden;

            transition:
                width 0.25s ease,
                flex-basis 0.25s ease,
                padding 0.25s ease;
        }

        .main-content {
            flex: 1 1 0;
            max-width: none;
            min-width: 0;
        }

        /* ========================================
           SIDEBAR HEADER
        ======================================== */

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 4px 18px;
        }

        .sidebar-brand {
            color: white;
            font-size: 17px;
            font-weight: 700;
            white-space: nowrap;
        }

        .sidebar-toggle {
            flex-shrink: 0;
            width: 34px;
            height: 34px;
            border: none;
            border-radius: 7px;
            background: transparent;
            color: #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 19px;
            transition: background-color 0.2s ease;
        }

        .sidebar-toggle:hover {
            background: #29364d;
            color: white;
        }

        /* ========================================
           SIDEBAR ROLE
        ======================================== */

        .sidebar-role {
            white-space: nowrap;
            overflow: hidden;
        }

        /* ========================================
           SIDEBAR MENU
        ======================================== */

        .sidebar-section {
            color: #94a3b8;
            font-size: 12px;
            padding: 0 12px;
            margin: 18px 0 10px;
            white-space: nowrap;
        }

        .sidebar-menu {
            display: flex !important;
            align-items: center;
            gap: 12px;
            white-space: nowrap;
            min-height: 44px;
            overflow: hidden;
        }

        .sidebar-menu i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 22px;
            width: 22px;
            min-width: 22px;
            height: 22px;
            font-size: 18px;
            text-align: center;
        }

        .sidebar-menu span {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sidebar-divider {
            border-color: #475569;
            margin: 12px 0;
        }

        /* ========================================
           SIDEBAR MINIMIZE
           Class berada di elemen HTML.
        ======================================== */

        html.sidebar-collapsed .sidebar {
            flex: 0 0 56px;
            width: 56px;
            max-width: 56px;
            padding: 8px 3px !important;
        }

        html.sidebar-collapsed .sidebar-header {
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 0;
            padding: 2px 0 12px;
        }

        html.sidebar-collapsed .sidebar-brand,
        html.sidebar-collapsed .sidebar-role,
        html.sidebar-collapsed .sidebar-section,
        html.sidebar-collapsed .menu-label {
            display: none !important;
        }

        html.sidebar-collapsed .sidebar-menu {
            display: flex !important;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 38px;
            padding: 7px 0 !important;
            margin: 0 0 4px;
            gap: 0;
        }

        html.sidebar-collapsed .sidebar-menu i {
            flex: 0 0 22px;
            width: 22px;
            min-width: 22px;
            height: 22px;
            font-size: 17px;
        }

        html.sidebar-collapsed .sidebar-divider {
            margin: 12px 0;
        }

        html.sidebar-collapsed .sidebar-toggle {
            width: 30px;
            height: 30px;
            font-size: 17px;
        }

        html.sidebar-collapsed .sidebar-logout {
            padding: 10px 0;
            text-align: center;
        }

        html.sidebar-collapsed .sidebar-logout button {
            padding: 8px 0;
        }

        html.sidebar-collapsed .sidebar-logout span {
            display: none;
        }

        /* ========================================
           NAVBAR ATAS
        ======================================== */

        .top-navbar {
            width: 100%;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            border: none;
            border-bottom: 1px solid #e5e7eb;
            border-radius: 0;
            box-shadow: none;
            padding: 0 4px 5px;
            margin-bottom: 20px;
        }

        .navbar-brand-text {
            font-size: 17px;
            font-weight: 600;
            color: #172033;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-avatar {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #e8eeff;
            color: #2456b5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .navbar-user-info {
            line-height: 1.1;
        }

        .navbar-user-info strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.1;
            color: #172033;
        }

        .navbar-user-info small {
            display: block;
            color: #6b7280;
            font-size: 12px;
            line-height: 1;
            margin-top: 3px;
        }

        /* ========================================
           JUDUL HALAMAN
        ======================================== */

        .page-heading {
            margin-bottom: 20px;
        }

        /* ========================================
           RESPONSIVE
        ======================================== */

        @media (max-width: 768px) {
            .app-layout-row {
                flex-wrap: wrap;
            }

            .sidebar {
                flex: 0 0 100%;
                width: 100%;
                max-width: 100%;
                min-height: auto;
            }

            .main-content {
                flex: 0 0 100%;
                max-width: 100%;
            }

            html.sidebar-collapsed .sidebar {
                flex: 0 0 100%;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row app-layout-row">

        {{-- =====================================
             SIDEBAR
        ====================================== --}}

        <aside class="col-md-2 sidebar p-3">

            {{-- HEADER SIDEBAR --}}
            <div class="sidebar-header">

                <div class="sidebar-brand">
                    My Bengkel App
                </div>

                <button
                    type="button"
                    class="sidebar-toggle"
                    id="sidebarToggle"
                    title="Minimalkan sidebar"
                    aria-label="Minimalkan sidebar"
                    aria-expanded="true"
                >
                    <i class="bi bi-layout-sidebar-inset"></i>
                </button>

            </div>

            {{-- ROLE PENGGUNA --}}
            <div class="sidebar-role text-white-50 small px-2 mb-3">
                Role: {{ Auth::user()->role->name ?? '-' }}
            </div>

            {{-- =================================
                 ADMIN / OWNER
            ================================== --}}

            @if(Auth::user()->role?->name === 'owner')

                <hr class="sidebar-divider">

                <div class="sidebar-section">ADMIN</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-label">Dashboard</span>
                </a>

                <a href="{{ route('owner') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('owner') ? 'active' : '' }}"
                   title="Manajemen Owner">
                    <i class="bi bi-person-gear"></i>
                    <span class="menu-label">Manajemen Owner</span>
                </a>

                <a href="{{ route('employees.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                   title="Karyawan">
                    <i class="bi bi-people"></i>
                    <span class="menu-label">Karyawan</span>
                </a>

                <a href="{{ route('reports.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                   title="Laporan">
                    <i class="bi bi-bar-chart"></i>
                    <span class="menu-label">Laporan</span>
                </a>

            @endif

            {{-- =================================
                 KASIR
            ================================== --}}

            @if(in_array(Auth::user()->role?->name, ['owner', 'kasir']))

                <hr class="sidebar-divider">

                <div class="sidebar-section">KASIR</div>

                @if(Auth::user()->role?->name === 'kasir')
                    <a href="{{ route('dashboard') }}"
                       class="nav-link sidebar-menu {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       title="Dashboard">
                        <i class="bi bi-speedometer2"></i>
                        <span class="menu-label">Dashboard</span>
                    </a>
                @endif

                <a href="{{ route('pos') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('pos*') ? 'active' : '' }}"
                   title="POS / Kasir">
                    <i class="bi bi-cart3"></i>
                    <span class="menu-label">POS / Kasir</span>
                </a>

                <a href="{{ route('transactions.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                   title="Riwayat Transaksi">
                    <i class="bi bi-receipt"></i>
                    <span class="menu-label">Riwayat Transaksi</span>
                </a>

                @if(Auth::user()->role?->name === 'owner')

                    <a href="{{ route('cancellation-requests.index') }}"
                       class="nav-link sidebar-menu {{ request()->routeIs('cancellation-requests.*') ? 'active' : '' }}"
                       title="Pengajuan Pembatalan">
                        <i class="bi bi-x-circle"></i>
                        <span class="menu-label">Pengajuan Pembatalan</span>
                    </a>

                    <a href="{{ route('stock-movements.index') }}"
                       class="nav-link sidebar-menu {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                       title="Riwayat Pergerakan Stok">
                        <i class="bi bi-arrow-left-right"></i>
                        <span class="menu-label">Riwayat Pergerakan Stok</span>
                    </a>

                @endif

            @endif

            {{-- =================================
                 GUDANG
            ================================== --}}

            @if(Auth::user()->role?->name === 'owner')

                <hr class="sidebar-divider">

                <div class="sidebar-section">GUDANG</div>

                <a href="{{ route('products.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('products.*') ? 'active' : '' }}"
                   title="Data Produk">
                    <i class="bi bi-box-seam"></i>
                    <span class="menu-label">Data Produk</span>
                </a>

                <a href="{{ route('categories.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                   title="Kategori">
                    <i class="bi bi-tags"></i>
                    <span class="menu-label">Kategori</span>
                </a>

            @elseif(Auth::user()->role?->name === 'gudang')

                <hr class="sidebar-divider">

                <div class="sidebar-section">GUDANG</div>

                <a href="{{ route('dashboard') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   title="Dashboard">
                    <i class="bi bi-speedometer2"></i>
                    <span class="menu-label">Dashboard</span>
                </a>

                <a href="{{ route('stock-movements.index') }}"
                   class="nav-link sidebar-menu {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                   title="Riwayat Pergerakan Stok">
                    <i class="bi bi-arrow-left-right"></i>
                    <span class="menu-label">Riwayat Pergerakan Stok</span>
                </a>

            @endif

            {{-- =================================
                 LOGOUT
            ================================== --}}

            <hr class="sidebar-divider">

            <form action="{{ route('logout') }}"
                  method="POST"
                  class="px-2 sidebar-logout">
                @csrf

                <button type="submit" class="btn btn-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>

        </aside>

        {{-- =====================================
             MAIN CONTENT
        ====================================== --}}

        <main class="col-md-10 main-content">

            {{-- NAVBAR ATAS --}}
            <nav class="top-navbar">

                <div class="navbar-brand-text">
                    My Bengkel App
                </div>

                <div class="navbar-user">

                    <div class="navbar-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="navbar-user-info">
                        <strong>{{ Auth::user()->name }}</strong>
                        <small>{{ ucfirst(Auth::user()->role->name ?? '-') }}</small>
                    </div>

                </div>

            </nav>

            {{-- JUDUL HALAMAN --}}
            <div class="page-heading">

                <h4 class="mb-1">
                    @yield('page-title', 'Dashboard')
                </h4>

                <small class="text-muted">
                    My Bengkel App
                </small>

            </div>

            {{-- KONTEN HALAMAN --}}
            @yield('content')

        </main>

    </div>
</div>

{{-- BOOTSTRAP JAVASCRIPT --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

{{-- =====================================
     SIDEBAR TOGGLE
====================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const sidebarToggle = document.getElementById('sidebarToggle');
        const root = document.documentElement;

        if (!sidebarToggle) {
            return;
        }

        const toggleIcon = sidebarToggle.querySelector('i');
        const storageKey = 'bengkelSidebarCollapsed';

        function updateToggleButton() {

            const isCollapsed = root.classList.contains('sidebar-collapsed');

            sidebarToggle.setAttribute(
                'aria-expanded',
                String(!isCollapsed)
            );

            sidebarToggle.setAttribute(
                'title',
                isCollapsed ? 'Perluas sidebar' : 'Minimalkan sidebar'
            );

            sidebarToggle.setAttribute(
                'aria-label',
                isCollapsed ? 'Perluas sidebar' : 'Minimalkan sidebar'
            );

            if (toggleIcon) {
                toggleIcon.className = isCollapsed
                    ? 'bi bi-layout-sidebar-inset-reverse'
                    : 'bi bi-layout-sidebar-inset';
            }
        }

        // Sinkronkan ikon dengan status awal.
        updateToggleButton();

        // Hanya tombol ini yang boleh mengubah status sidebar.
        sidebarToggle.addEventListener('click', function () {

            root.classList.toggle('sidebar-collapsed');

            const isCollapsed = root.classList.contains('sidebar-collapsed');

            try {
                localStorage.setItem(
                    storageKey,
                    String(isCollapsed)
                );
            } catch (error) {
                // Tampilan tetap dapat berubah meskipun penyimpanan gagal.
            }

            updateToggleButton();

        });

    });
</script>

</body>
</html>

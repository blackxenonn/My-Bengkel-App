<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'My Bengkel App')</title>

    {{-- Memuat status sidebar dan tema sebelum halaman ditampilkan --}}
    <script>
        const rootElement = document.documentElement;

        try {
            if (localStorage.getItem('bengkelSidebarCollapsed') === 'true') {
                rootElement.classList.add('sidebar-collapsed');
            }

            const savedTheme = localStorage.getItem('bengkelTheme');

            rootElement.setAttribute(
                'data-bs-theme',
                savedTheme === 'dark' ? 'dark' : 'light'
            );
        } catch (error) {
            rootElement.setAttribute('data-bs-theme', 'light');
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
                width 0.3s ease,
                max-width 0.3s ease,
                flex-basis 0.3s ease,
                padding 0.3s ease;
        }

        .main-content {
            flex: 1 1 0;
            max-width: none;
            min-width: 0;
            margin-left: 16.666667%;
            width: 83.333333%;

            transition:
                margin-left 0.3s ease,
                width 0.3s ease,
                flex-basis 0.3s ease;
        }

        /* ========================================
           SIDEBAR FIXED / TIDAK IKUT SCROLL
        ======================================== */

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
        }

        html.sidebar-collapsed .main-content {
            margin-left: 56px;
            width: calc(100% - 56px);
        }

        html.sidebar-collapsed .sidebar {
            flex: 0 0 56px;
            width: 56px;
            max-width: 56px;
            height: 100vh;
        }

        /* ========================================
           SIDEBAR HEADER
        ======================================== */

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 0px 4px 0px;
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
        ======================================== */

        html.sidebar-collapsed .sidebar {
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
            min-height: 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent;
            border: none;
            border-bottom: 1px solid var(--app-border);
            border-radius: 0;
            box-shadow: none;
            padding: 0 4px 5px;
            margin-bottom: 20px;
        }

        .navbar-brand-text {
            font-size: 19px;
            font-weight: 700;
            color: var(--app-text);
        }

        .navbar-right {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-avatar {
            width: 36px;
            height: 36px;
            flex-shrink: 0;
            border-radius: 50%;
            background: #e8eeff;
            color: #2456b5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .navbar-user-info {
            line-height: 1.1;
        }

        .navbar-user-info strong {
            display: block;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.1;
            color: var(--app-text);
        }

        .navbar-user-info small {
            display: block;
            color: var(--app-muted);
            font-size: 12px;
            line-height: 1;
            margin-top: 3px;
        }

        /* ========================================
           THEME TOGGLE
        ======================================== */

        .theme-toggle {
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border: 1px solid var(--app-border);
            border-radius: 50%;
            background: var(--app-surface);
            color: var(--app-text);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .theme-toggle:hover {
            background: var(--app-background);
            transform: rotate(15deg);
        }

        /* ========================================
           RESPONSIVE
        ======================================== */

        @media (max-width: 768px) {
            .app-layout-row {
                flex-wrap: wrap;
            }

            .sidebar {
                position: relative;
                flex: 0 0 100%;
                width: 100%;
                max-width: 100%;
                min-height: auto;
                height: auto;
            }

            .main-content,
            html.sidebar-collapsed .main-content {
                flex: 0 0 100%;
                max-width: 100%;
                margin-left: 0;
                width: 100%;
            }

            html.sidebar-collapsed .sidebar {
                flex: 0 0 100%;
                width: 100%;
                max-width: 100%;
                height: auto;
            }

            .navbar-brand-text {
                font-size: 15px;
            }

            .navbar-user-info strong {
                font-size: 12px;
            }

            .navbar-user-info small {
                font-size: 11px;
            }

            .navbar-right {
                gap: 8px;
            }

            .theme-toggle {
                width: 36px;
                height: 36px;
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

                {{-- JUDUL HALAMAN DINAMIS --}}
                <div class="navbar-brand-text">
                    @yield('page-title', 'Dashboard')
                </div>

                {{-- PROFIL DAN TOMBOL TEMA --}}
                <div class="navbar-right">

                    {{-- TOMBOL DARK MODE / LIGHT MODE --}}
                    <button
                        type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        title="Ganti tema"
                        aria-label="Ganti tema"
                    >
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    {{-- PROFIL PENGGUNA --}}
                    <div class="navbar-user">

                        <div class="navbar-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>

                        <div class="navbar-user-info">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ ucfirst(Auth::user()->role->name ?? '-') }}</small>
                        </div>

                    </div>

                </div>

            </nav>

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

        updateToggleButton();

        sidebarToggle.addEventListener('click', function () {

            root.classList.toggle('sidebar-collapsed');

            const isCollapsed = root.classList.contains('sidebar-collapsed');

            try {
                localStorage.setItem(
                    storageKey,
                    String(isCollapsed)
                );
            } catch (error) {
                // Tampilan tetap berubah meskipun penyimpanan gagal.
            }

            updateToggleButton();

        });

    });
</script>

{{-- =====================================
     DARK MODE / LIGHT MODE
====================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const root = document.documentElement;

        if (!themeToggle || !themeIcon) {
            return;
        }

        function updateThemeButton() {

            const currentTheme = root.getAttribute('data-bs-theme');

            if (currentTheme === 'dark') {

                themeIcon.className = 'bi bi-sun-fill';
                themeToggle.title = 'Ganti ke Light Mode';

                themeToggle.setAttribute(
                    'aria-label',
                    'Ganti ke Light Mode'
                );

            } else {

                themeIcon.className = 'bi bi-moon-stars-fill';
                themeToggle.title = 'Ganti ke Dark Mode';

                themeToggle.setAttribute(
                    'aria-label',
                    'Ganti ke Dark Mode'
                );

            }
        }

        updateThemeButton();

        themeToggle.addEventListener('click', function () {

            const currentTheme = root.getAttribute('data-bs-theme');

            const newTheme = currentTheme === 'dark'
                ? 'light'
                : 'dark';

            root.setAttribute('data-bs-theme', newTheme);

            try {
                localStorage.setItem('bengkelTheme', newTheme);
            } catch (error) {
                // Tema tetap berubah meskipun penyimpanan gagal.
            }

            updateThemeButton();

        });

    });
</script>

{{-- =====================================
     LIVE SEARCH
====================================== --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {

        document.querySelectorAll('[data-live-search]').forEach(function (form) {

            const input = form.querySelector('[data-live-search-input]');

            if (!input) {
                return;
            }

            let timeout = null;

            input.addEventListener('input', function () {

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    form.requestSubmit();
                }, 400);

            });

            // Kembalikan fokus ke kolom pencarian setelah halaman dimuat.
            if (input.value.trim() !== '') {
                input.focus();
                input.setSelectionRange(
                    input.value.length,
                    input.value.length
                );
            }

        });

    });
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'My Bengkel App')</title>

    {{-- Bootstrap CSS --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    @vite(['resources/css/app.css'])
</head>
<body>

<div class="container-fluid">
    <div class="row">

        {{-- SIDEBAR --}}
        <aside class="col-md-2 sidebar p-3">

            <div class="brand">
                My Bengkel App
            </div>

            <div class="text-white-50 small px-3 mb-3">
                Role: {{ Auth::user()->role->name ?? '-' }}
            </div>

            {{-- DASHBOARD --}}
            <a
                href="{{ route('dashboard') }}"
                class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
            >
                Dashboard
            </a>

            {{-- MANAJEMEN OWNER --}}
            @if(Auth::user()->role?->name === 'owner')
                <a
                    href="{{ url('/owner') }}"
                    class="nav-link {{ request()->is('owner') ? 'active' : '' }}"
                >
                    Manajemen Owner
                </a>
            @endif

            {{-- POS / KASIR --}}
            @if(in_array(Auth::user()->role?->name, ['owner', 'kasir']))
                <a
                    href="{{ route('pos') }}"
                    class="nav-link {{ request()->routeIs('pos*') ? 'active' : '' }}"
                >
                    POS / Kasir
                </a>

                {{-- RIWAYAT TRANSAKSI --}}
                <a
                    href="{{ route('transactions.index') }}"
                    class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}"
                >
                    Riwayat Transaksi
                </a>
            @endif

            {{-- PERGERAKAN STOK --}}
            @if(in_array(Auth::user()->role?->name, ['owner', 'gudang']))

                <a
                    href="{{ route('stock-movements.index') }}"
                    class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"
                >
                    Riwayat Pergerakan Stok
                </a>

            @endif

            {{-- ADMINISTRASI --}}
            @if(Auth::user()->role?->name === 'owner')

                <hr class="border-secondary">

                <div class="text-white-50 small px-3 mb-2">
                    ADMINISTRASI
                </div>

                <a
                    href="{{ route('products.index') }}"
                    class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"
                >
                    Data Produk
                </a>

                <a
                    href="{{ route('categories.index') }}"
                    class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"
                >
                    Kategori
                </a>

                <a
                    href="{{ route('employees.index') }}"
                    class="nav-link {{ request()->routeIs('employees.*') ? 'active' : '' }}"
                >
                    Karyawan
                </a>

                <a
                    href="{{ route('reports.index') }}"
                    class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"
                >
                    Laporan
                </a>

            @endif

            {{-- LOGOUT --}}
            <hr class="border-secondary">

            <form action="{{ route('logout') }}" method="POST" class="px-2">
                @csrf

                <button type="submit" class="btn btn-danger btn-sm w-100">
                    Logout
                </button>
            </form>

        </aside>

        {{-- MAIN CONTENT --}}
        <main class="col-md-10 main-content">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <div>
                    <h4 class="mb-1">
                        @yield('page-title', 'Dashboard')
                    </h4>

                    <small class="text-muted">
                        My Bengkel App
                    </small>
                </div>

                <div class="text-end">
                    <strong>{{ Auth::user()->name }}</strong>
                </div>

            </div>

            {{-- PAGE CONTENT --}}
            @yield('content')

        </main>

    </div>
</div>

{{-- Bootstrap JavaScript --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

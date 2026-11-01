
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Bengkel Management')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f5f6fa;
        }

        .sidebar {
            min-height: 100vh;
            background: #172033;
        }

        .sidebar a {
            display: block;
            color: #cbd5e1;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 7px;
            margin-bottom: 5px;
        }

        .sidebar a:hover {
            background: #29364d;
            color: white;
        }

        .brand {
            color: white;
            font-size: 19px;
            font-weight: bold;
            padding: 20px 16px;
        }

        .main-content {
            padding: 25px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">

        <aside class="col-md-2 sidebar p-3">
            <div class="brand">
                My-Bengkel-App
            </div>

            <div class="text-white-50 small px-3 mb-3">
                Role: {{ Auth::user()->role->name ?? '-' }}
            </div>

            <a href="{{ route('dashboard') }}">
                Dashboard
            </a>

            @if(Auth::user()->role?->name === 'owner')
                <a href="{{ url('/owner') }}">Manajemen Owner</a>
            @endif

            @if(in_array(Auth::user()->role?->name, ['owner', 'kasir']))
                <a href="{{ route('pos') }}">POS / Kasir</a>
            @endif

            @if(in_array(Auth::user()->role?->name, ['owner', 'gudang']))
                <a href="{{ route('stock-movements.index') }}">
                    Inventaris
                </a>

                <a href="{{ route('stock-movements.create') }}">
                    Tambah Pergerakan Stok
                </a>
            @endif

            @if(Auth::user()->role?->name === 'owner')
                <hr class="border-secondary">
                <div class="text-white-50 small px-3 mb-2">
                    ADMINISTRASI
                </div>
                <a href="{{ route('products.index') }}"
                  class="nav-link text-white">
                    Data Produk
                </a>
                <a href="{{ route('categories.index') }}">Kategori</a>
                <a href="{{ route('employees.index') }}">Karyawan</a>
                <a href="{{ route('reports.index') }}">Laporan</a>
            @endif

            <hr class="border-secondary">

            <form action="{{ route('logout') }}" method="POST" class="px-2">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm w-100">
                    Logout
                </button>
            </form>
        </aside>

        <main class="col-md-10 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">@yield('page-title', 'Dashboard')</h4>
                    <small class="text-muted">
                        Sistem Manajemen Bengkel
                    </small>
                </div>

                <div class="text-end">
                    <strong>{{ Auth::user()->name }}</strong>
                </div>
            </div>

            @yield('content')
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

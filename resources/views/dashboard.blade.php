
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bengkel Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body>

<div class="container py-5">
    <h2>Dashboard Bengkel Management</h2>

    <p>
        Selamat datang,
        <strong>{{ auth()->user()->name }}</strong>!
    </p>

    <p>Role: {{ auth()->user()->role->name }}</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
            Logout
        </button>
    </form>
</div>

</body>
</html>

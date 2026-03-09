<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mitra - FZ Rent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mitra Dashboard</a>
            <div class="navbar-nav">
                <a class="nav-link" href="{{ route('mitra.dashboard') }}">Dashboard</a>
                <a class="nav-link" href="{{ route('mitra.mobil.index') }}">Mobil</a>
                <a class="nav-link" href="{{ route('mitra.pesanan.index') }}">Pesanan</a>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>
</body>
</html>
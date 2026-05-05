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
            
            {{-- Mobile Toggle Button --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mitraNavbar" aria-controls="mitraNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Collapsible Content --}}
            <div class="collapse navbar-collapse" id="mitraNavbar">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link" href="{{ route('mitra.dashboard') }}">Dashboard</a>
                    <a class="nav-link" href="{{ route('mitra.mobil.index') }}">Mobil</a>
                    <a class="nav-link" href="{{ route('mitra.pesanan.index') }}">Pesanan</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        @yield('content')
    </main>

    {{-- Bootstrap JS for Navbar Collapse --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
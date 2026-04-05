<!DOCTYPE html>
<html>
<head>
    <title>Tiket Booking #{{ $transaksi->id }}</title>
    <style>
        body { font-family: sans-serif; padding: 20px; color: #333; }
        .ticket { border: 2px solid #333; padding: 20px; max-width: 600px; margin: 0 auto; position: relative; }
        
        /* LOGIKA WARNA STATUS */
        .status-box { 
            padding: 10px; text-align: center; color: white; font-weight: bold; margin-bottom: 20px; 
            text-transform: uppercase;
        }
        .bg-warning { background-color: #f59e0b; } /* Kuning untuk Pending */
        .bg-success { background-color: #10b981; } /* Hijau untuk Lunas/Disewa */
        .bg-danger  { background-color: #ef4444; } /* Merah untuk Batal */

        .header { text-align: center; border-bottom: 1px solid #ddd; margin-bottom: 20px; }
        
        /* Layout Baris */
        .row { display: flex; justify-content: space-between; margin-bottom: 10px; align-items: flex-start; }
        .label { font-weight: bold; width: 40%; }
        .value { text-align: right; width: 60%; }
        
        /* CAP BELUM LUNAS (Watermark) */
        .watermark {
            position: absolute;
            top: 50%; left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 5rem;
            opacity: 0.2;
            color: red;
            border: 5px solid red;
            padding: 20px;
            z-index: -1;
            display: none; /* Default sembunyi */
        }
        .show-watermark { display: block; }

        @media print { .btn-print { display: none; } }
        .btn-print { display: block; width: 100%; padding: 15px; background: #333; color: #fff; text-align: center; text-decoration: none; margin-top: 20px; cursor: pointer; border: none; font-size: 16px;}
    </style>
</head>
<body>
    
    @php
        $statusLower = strtolower(trim($transaksi->status ?? ''));
        $isPending = ($statusLower === 'pending');
    @endphp

    <div class="ticket">
        
        {{-- HEADER STATUS (DIPERBAIKI STRUKTURNYA AGAR SELALU DI ATAS) --}}
        @if($isPending)
            <div class="status-box bg-warning">
                BUKTI BOOKING SEMENTARA (BELUM LUNAS)
            </div>
            <div class="watermark show-watermark">BELUM LUNAS</div>
        @else
            <div class="status-box bg-success">
                E-TIKET RESMI (SUDAH DIBAYAR)
            </div>
        @endif

        <div class="header">
            {{-- KODE MUTLAK 1: Menarik Nama Pemilik/Rental secara Otomatis (Dinamis) --}}
            {{-- Jika Anda punya tabel/relasi khusus rental, ubah menjadi $transaksi->rental->nama_rental --}}
            <h2>{{ strtoupper($transaksi->rental->nama_rental ?? 'NAMA RENTAL KOSONG') }}</h2>
            
            <p>Kode Booking: <strong>TRX-{{ $transaksi->id }}</strong></p>
        </div>

        <div class="row">
            <span class="label">Penyewa:</span>
            {{-- Menjaga kompatibilitas dengan kolom database Anda --}}
            <span class="value">{{ $transaksi->nama ?? $transaksi->user->name ?? '-' }} ({{ $transaksi->no_hp ?? $transaksi->user->no_hp ?? '-' }})</span>
        </div>
        
        <div class="row">
            <span class="label">Mobil:</span>
            {{-- KODE MUTLAK 2: Mengembalikan pemanggilan Merk dan Model yang akurat --}}
            <span class="value">{{ $transaksi->mobil->merk ?? '-' }} {{ $transaksi->mobil->model ?? '' }}</span>
        </div>
        
        <div class="row">
            <span class="label">Plat Nomor:</span>
            {{-- Mengembalikan pemanggilan nomor plat yang akurat --}}
            <span class="value">{{ $transaksi->mobil->no_plat ?? '-' }}</span>
        </div>
        
        <hr>
        
        <div class="row">
            <span class="label">Jadwal Ambil:</span>
            <span class="value">{{ \Carbon\Carbon::parse($transaksi->tanggal_mulai)->format('d-m-Y (H:i)') }}</span>
        </div>

        <div class="row">
            <span class="label">Lokasi Pengambilan:</span>
            <span class="value">
                @if($transaksi->lokasi_pengambilan == 'lainnya')
                    {{ $transaksi->alamat_pengambilan }}
                    <br><span style="font-size: 11px; color: #666; font-style: italic;">(Layanan Antar)</span>
                @else
                    Ambil di Kantor
                @endif
            </span>
        </div>

        <div class="row">
            <span class="label">Lokasi Pengembalian:</span>
            <span class="value">
                @if($transaksi->lokasi_pengembalian == 'lainnya')
                    {{ $transaksi->alamat_pengembalian }}
                    <br><span style="font-size: 11px; color: #666; font-style: italic;">(Dijemput Driver)</span>
                @else
                    Kembalikan ke Kantor
                @endif
            </span>
        </div>

        <hr>
        
        <div class="row">
            <span class="label">Total Biaya:</span>
            <span class="value" style="font-size: 1.2em; font-weight: bold;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 0.9em; color: gray; border-top: 1px dashed #ccc; padding-top: 10px;">
            @if($isPending)
                <p style="color: #f59e0b; font-weight: bold;">PERHATIAN:</p>
                <p>Status pesanan ini belum dibayar. Silakan lakukan pembayaran melalui menu Riwayat Order Anda.</p>
            @else
                <p style="color: green; font-weight: bold;">INSTRUKSI:</p>
                <p>Tunjukkan tiket ini kepada petugas saat pengambilan kunci mobil.</p>
            @endif
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

</body>
</html>

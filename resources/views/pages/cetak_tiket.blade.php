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
        $status = strtolower($transaksi->status);
        $isPending = ($status == 'pending' || $status == '' || $transaksi->status == null);
        $isActive  = in_array($status, ['process', 'disewa', 'sedang_disewa', 'finished', 'selesai']);
    @endphp

    <div class="ticket">
        
        {{-- 1. HEADER STATUS BERUBAH WARNA --}}
        @if($isPending)
            <div class="status-box bg-warning">
                BUKTI BOOKING (BELUM LUNAS)
            </div>
            {{-- Watermark jika belum bayar --}}
            <div class="watermark show-watermark">BELUM LUNAS</div>
        @elseif($isActive)
            <div class="status-box bg-success">
                E-TIKET RESMI (SUDAH DIBAYAR)
            </div>
        @else
            <div class="status-box bg-danger">
                TIKET TIDAK VALID ({{ strtoupper($status) }})
            </div>
        @endif

        <div class="header">
            <h2>RENTAL MOBIL JAYA</h2>
            <p>Kode Booking: <strong>TRX-{{ $transaksi->id }}</strong></p>
        </div>

        <div class="row">
            <span class="label">Penyewa:</span>
            <span class="value">{{ $transaksi->nama }} ({{ $transaksi->no_hp }})</span>
        </div>
        <div class="row">
            <span class="label">Mobil:</span>
            <span class="value">{{ $transaksi->mobil->merk ?? '-' }} {{ $transaksi->mobil->model ?? '' }}</span>
        </div>
        <div class="row">
            <span class="label">Plat Nomor:</span>
            <span class="value">{{ $transaksi->mobil->no_plat ?? '-' }}</span>
        </div>
        <hr>
        <div class="row">
            <span class="label">Jadwal Ambil:</span>
            <span class="value">{{ $transaksi->tgl_ambil }} ({{ $transaksi->jam_ambil }})</span>
        </div>

        {{-- ========================================== --}}
        {{-- BAGIAN LOKASI (SUDAH DIPERBAIKI) --}}
        {{-- ========================================== --}}

        {{-- 1. Lokasi Pengambilan --}}
        <div class="row">
            <span class="label">Lokasi Pengambilan:</span>
            <span class="value">
                @if(!empty($transaksi->alamat_jemput) && $transaksi->alamat_jemput != '-')
                    {{ $transaksi->alamat_jemput }}
                    <br><span style="font-size: 11px; color: #666; font-style: italic;">(Layanan Antar)</span>
                @else
                    Kantor Rental (Gratis)
                @endif
            </span>
        </div>

        {{-- 2. Lokasi Pengembalian --}}
        <div class="row">
            <span class="label">Lokasi Pengembalian:</span>
            <span class="value">
                @if(!empty($transaksi->alamat_antar) && $transaksi->alamat_antar != '-')
                    {{ $transaksi->alamat_antar }}
                    <br><span style="font-size: 11px; color: #666; font-style: italic;">(Dijemput Driver)</span>
                @elseif(isset($transaksi->lokasi_kembali) && strtolower($transaksi->lokasi_kembali) != 'kantor')
                    {{ $transaksi->alamat_jemput ?? 'Lokasi User' }}
                @else
                    Kantor Rental
                @endif
            </span>
        </div>
        
        {{-- ========================================== --}}

        <hr>
        <div class="row">
            <span class="label">Total Biaya:</span>
            <span class="value" style="font-size: 1.2em; font-weight: bold;">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
        </div>

        <div style="text-align: center; margin-top: 30px; font-size: 0.9em; color: gray; border-top: 1px dashed #ccc; padding-top: 10px;">
            @if($isPending)
                <p style="color: red; font-weight: bold;">PERHATIAN:</p>
                <p>Silakan tunjukkan bukti ini ke kasir untuk melakukan pembayaran tunai.</p>
                <p>Atau transfer bukti bayar melalui menu Riwayat Order.</p>
            @else
                <p style="color: green; font-weight: bold;">INSTRUKSI:</p>
                <p>Tunjukkan tiket ini kepada petugas saat pengambilan kunci mobil.</p>
            @endif
        </div>

        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    </div>

</body>
</html>
import csv

dataset = [
    {
        "question": "Berapa harga sewa Toyota Avanza New per hari lepas kunci di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 350.000 / hari."
    },
    {
        "question": "Berapa harga sewa Toyota Innova Reborn (Solar) di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 600.000 / hari."
    },
    {
        "question": "Apakah biaya sewa harian dengan supir sudah termasuk bensin dan uang tol?",
        "ground_truth": "Tidak, biaya sewa harian dengan supir belum termasuk bensin, tol, dan parkir."
    },
    {
        "question": "Berapa denda keterlambatan pengembalian mobil per jam?",
        "ground_truth": "Denda keterlambatan adalah 10% dari harga sewa per hari untuk setiap jam keterlambatan."
    },
    {
        "question": "Bagaimana ketentuan jika keterlambatan pengembalian mobil lebih dari 5 jam?",
        "ground_truth": "Jika lebih dari 5 jam, akan dihitung sebagai sewa penuh 1 hari (24 jam)."
    },
    {
        "question": "Berapa denda jika merokok di dalam mobil rental?",
        "ground_truth": "Dikenakan biaya salon interior/eksterior mulai dari Rp 50.000 hingga Rp 300.000."
    },
    {
        "question": "Apakah metode pembayaran di platform ini bisa menggunakan QRIS?",
        "ground_truth": "Ya, pembayaran bisa menggunakan QRIS melalui e-wallet (Gopay, OVO, Dana) atau m-banking."
    },
    {
        "question": "Apa saja metode pembayaran otomatis yang didukung oleh platform?",
        "ground_truth": "Pembayaran online otomatis melalui Midtrans, QRIS, dan Transfer Bank (Virtual Account)."
    },
    {
        "question": "Berapa denda jika kehilangan kunci mobil?",
        "ground_truth": "Denda berkisar antara Rp 500.000 hingga Rp 2.000.000 tergantung tipe mobil."
    },
    {
        "question": "Apakah penyewa boleh mengembalikan mobil dengan indikator bensin yang lebih sedikit dari awal sewa?",
        "ground_truth": "Tidak boleh, penyewa wajib mengganti selisih kekurangan BBM jika dikembalikan kurang dari batas awal sewa."
    },
    {
        "question": "Berapa harga sewa Toyota Alphard per hari di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 5.000.000 / hari (termasuk mobil + driver)."
    },
    {
        "question": "Berapa harga sewa Mitsubishi Xpander lepas kunci di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 450.000 / hari."
    },
    {
        "question": "Apa persyaratan utama untuk menyewa mobil lepas kunci?",
        "ground_truth": "Penyewa harus melampirkan KTP, SIM A, KK, dan jaminan lain sesuai ketentuan mitra."
    },
    {
        "question": "Siapa yang bertanggung jawab atas kerusakan ringan seperti lecet atau baret pada sewa lepas kunci?",
        "ground_truth": "Penyewa menanggung penuh biaya perbaikan sesuai estimasi bengkel resmi rekanan."
    },
    {
        "question": "Berapa harga sewa Toyota Innova Zenix per hari lepas kunci di Putra Wijaya?",
        "ground_truth": "Rp 700.000 / hari."
    },
    {
        "question": "Apakah sewa mobil dengan supir memerlukan jaminan KK dan STNK motor?",
        "ground_truth": "Tidak, jaminan KK dan STNK motor biasanya hanya untuk sewa lepas kunci."
    },
    {
        "question": "Apakah ada denda jika unit dikembalikan dalam kondisi kotor parah?",
        "ground_truth": "Ya, dikenakan biaya salon interior/eksterior berkisar Rp 50.000 hingga Rp 300.000."
    },
    {
        "question": "Kemana penyewa harus menghubungi jika ingin membatalkan pemesanan dan meminta pengembalian DP?",
        "ground_truth": "Penyewa wajib menghubungi langsung Customer Service dari masing-masing mitra rental."
    },
    {
        "question": "Dapatkah penyewa mengembalikan mobil sewaan di cabang yang berbeda?",
        "ground_truth": "Sistem belum mendefinisikan/mendukung pengembalian di cabang yang berbeda secara lintas kota."
    },
    {
        "question": "Apakah jam operasional semua kantor cabang mitra rental serentak 24 jam?",
        "ground_truth": "Tidak, jam operasional bergantung pada masing-masing cabang mitra rental."
    },
    {
        "question": "Berapa harga sewa Agya atau Ayla per hari di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 300.000 / hari."
    },
    {
        "question": "Berapa harga sewa Suzuki XL 7 per hari di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 400.000 / hari."
    },
    {
        "question": "Berapa biaya tambahan untuk menyewa driver?",
        "ground_truth": "Tarif sopir dapat ditanyakan langsung dengan menghubungi admin mitra rental terkait."
    },
    {
        "question": "Apakah pendaftaran akun penyewa memerlukan verifikasi email?",
        "ground_truth": "Registrasi memerlukan input email aktif untuk validasi akun."
    },
    {
        "question": "Berapa harga sewa Mitsubishi Pajero Sport di Putra Wijaya Rent Car?",
        "ground_truth": "Rp 1.700.000 / hari (termasuk mobil + driver)."
    },
    {
        "question": "Bagaimana sistem memverifikasi transaksi pembayaran?",
        "ground_truth": "Transaksi akan diverifikasi secara otomatis oleh sistem/payment gateway setelah pembayaran dilakukan."
    },
    {
        "question": "Apakah penyewa dikenakan denda jika mengembalikan mobil lewat 1 jam?",
        "ground_truth": "Ya, dikenakan denda overtime sebesar 10% dari tarif sewa harian."
    },
    {
        "question": "Berapa kisaran biaya salon untuk membersihkan bau rokok di interior?",
        "ground_truth": "Biaya salon berkisar antara Rp 50.000 hingga Rp 300.000."
    },
    {
        "question": "Apakah sewa mobil Toyota Fortuner di Putra Wijaya bisa dilepas kunci?",
        "ground_truth": "Tidak, Toyota Fortuner disewakan dengan tarif Rp 1.700.000 / hari termasuk mobil + driver."
    },
    {
        "question": "Apakah plat nomor kendaraan diinformasikan kepada penyewa sebelum sewa dimulai?",
        "ground_truth": "Ya, plat nomor kendaraan terdokumentasi di dalam detail armada di platform."
    }
]

with open("c:/Users/GF 63/rental-mobil/python_service/dataset_30_pertanyaan.csv", "w", newline="", encoding="utf-8") as f:
    writer = csv.DictWriter(f, fieldnames=["question", "ground_truth"])
    writer.writeheader()
    writer.writerows(dataset)

print("Dataset dengan 30 pertanyaan berhasil dibuat.")

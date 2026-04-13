<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function checkStatus($orderId)
{
    $serverKey = config('midtrans.server_key');

    $response = Http::withBasicAuth($serverKey, '')
        ->get("https://api.sandbox.midtrans.com/v2/$orderId/status");

    $data = $response->json();

    $transaksi = Transaksi::where('order_id', $orderId)->first();

    if (!$transaksi) {
        return response()->json(['message' => 'Transaksi tidak ditemukan']);
    }

    switch ($data['transaction_status']) {
        case 'settlement':
            $transaksi->status = 'success';
            break;

        case 'pending':
            $transaksi->status = 'pending';
            break;

        case 'expire':
            $transaksi->status = 'expired'; // 🔥 INI PENTING
            break;

        case 'cancel':
            $transaksi->status = 'cancel';
            break;
    }

    $transaksi->save();

    return $data;
}
    private function configureMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = (bool) config('services.midtrans.is_production');
        Config::$isSanitized = (bool) config('services.midtrans.is_sanitized');
        Config::$is3ds = (bool) config('services.midtrans.is_3ds');
    }

    private function extractTransaksiIdFromOrderId(?string $orderId): ?int
    {
        if (!$orderId) {
            return null;
        }

        if (preg_match('/^ORDER-(\d+)-/i', $orderId, $m)) {
            return (int) $m[1];
        }

        return null;
    }

    private function applyMidtransStatusToTransaksi(Transaksi $transaksi, string $transactionStatus, ?string $paymentType = null, ?string $fraudStatus = null): void
    {
        $ts = strtolower(trim($transactionStatus));
        $pt = strtolower(trim((string) $paymentType));
        $fs = strtolower(trim((string) $fraudStatus));

        // Standarisasi Status Project: Pending, Disewa, Dibatalkan, Selesai, Ditolak
        $oldStatus = $transaksi->status;

        if ($ts === 'capture') {
            if ($pt === 'credit_card') {
                $transaksi->status = ($fs === 'challenge') ? 'Pending' : 'Disewa';
            } else {
                $transaksi->status = 'Disewa';
            }
        } elseif ($ts === 'settlement') {
            $transaksi->status = 'Disewa';
        } elseif ($ts === 'pending') {
            $transaksi->status = 'Pending';
        } elseif (in_array($ts, ['deny', 'expire', 'cancel', 'refund', 'partial_refund'], true)) {
            $transaksi->status = 'Dibatalkan';
        }

        $transaksi->save();

        // Update status mobil jika status transaksi berubah
        if ($oldStatus !== $transaksi->status) {
            if (strtolower($transaksi->status) === 'disewa') {
                \App\Models\Mobil::where('id', $transaksi->mobil_id)->update(['status' => 'disewa']);
                $this->sendWhatsAppNotification($transaksi, 'sukses_bayar');
            } elseif (strtolower($transaksi->status) === 'dibatalkan') {
                \App\Models\Mobil::where('id', $transaksi->mobil_id)->update(['status' => 'tersedia']);
            }
        }
    }

    private function sendWhatsAppNotification(Transaksi $transaksi, string $jenis)
    {
        // Pastikan relasi user di-load jika belum (untuk ngambil nama/hp aslinya)
        $transaksi->loadMissing('user', 'mobil');
        
        $noHpPenyewa = $transaksi->no_hp ?? ($transaksi->user->no_hp ?? null);
        if (empty($noHpPenyewa)) return;

        $namaPenyewa = $transaksi->user->name ?? $transaksi->nama ?? 'Pelanggan';
        $namaMobil = ($transaksi->mobil->merk ?? '') . ' ' . ($transaksi->mobil->model ?? '');
        $totalHarga = number_format($transaksi->total_harga ?? 0, 0, ',', '.');

        if ($jenis === 'sukses_bayar') {
            $teksPesan = "*PEMBAYARAN BERHASIL - FZ RENT CAR*\n\n"
                       . "Halo {$namaPenyewa},\n"
                       . "Kabar baik! Pembayaran untuk sewa armada *{$namaMobil}* telah *BERHASIL* terkonfirmasi oleh sistem kami.\n\n"
                       . "Total Dibayar: *Rp {$totalHarga}*\n"
                       . "Status: *Disewa*\n\n"
                       . "Terima kasih telah mempercayakan perjalanan Anda kepada kami. Hubungi kontak cabang jika ada pertanyaan lebih lanjut!";
        } else {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => env('WA_API_TOKEN'),
            ])->asForm()->post(env('WA_API_URL'), [
                'target' => $noHpPenyewa, 
                'message' => $teksPesan,
                'countryCode' => '62',
            ]);

            $result = $response->json();
            if (isset($result['status']) && $result['status'] === true) {
                Log::info('WA Webhook Sukses dikirim ke: ' . $noHpPenyewa);
            } else {
                Log::error('WA Webhook Gagal di Fonnte: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Koneksi WA Webhook Putus: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        $this->configureMidtrans();

        try {
            $notification = new Notification();
            $transactionStatus = (string) $notification->transaction_status;
            $type = isset($notification->payment_type) ? (string) $notification->payment_type : null;
            $orderId = isset($notification->order_id) ? (string) $notification->order_id : null;
            $fraudStatus = isset($notification->fraud_status) ? (string) $notification->fraud_status : null;

            $transaksiId = $this->extractTransaksiIdFromOrderId($orderId);
            if (!$transaksiId) {
                Log::warning("Midtrans Webhook: Invalid Order ID format ($orderId)");
                return response()->json(['message' => 'Invalid Order ID'], 400);
            }

            $transaksi = Transaksi::find($transaksiId);
            if (!$transaksi) {
                Log::error("Midtrans Webhook: Transaction not found for ID $transaksiId (Order ID: $orderId)");
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            Log::info("Midtrans Webhook Received: Order ID {$orderId}, Status: {$transactionStatus}, Type: {$type}");

            $this->applyMidtransStatusToTransaksi($transaksi, $transactionStatus, $type, $fraudStatus);

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error("Midtrans Webhook Exception: " . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function finish(Request $request)
    {
        $this->configureMidtrans();

        $orderId = $request->input('order_id');
        if (!$orderId) {
            return response()->json(['message' => 'Order ID is required'], 400);
        }

        $transaksiId = $this->extractTransaksiIdFromOrderId($orderId);
        if (!$transaksiId) {
            return response()->json(['message' => 'Invalid Order ID format'], 400);
        }

        $transaksi = Transaksi::find($transaksiId);
        if (!$transaksi) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        try {
            // Kita fetch status terbaru langsung dari Midtrans API untuk verifikasi
            $status = Transaction::status($orderId);
            $transactionStatus = isset($status->transaction_status) ? (string) $status->transaction_status : 'pending';
            $paymentType = isset($status->payment_type) ? (string) $status->payment_type : null;
            $fraudStatus = isset($status->fraud_status) ? (string) $status->fraud_status : null;

            Log::info("Midtrans Finish (Manual Check): Order ID {$orderId}, Status: {$transactionStatus}");

            $this->applyMidtransStatusToTransaksi($transaksi, $transactionStatus, $paymentType, $fraudStatus);

            return response()->json([
                'message' => 'Payment status updated',
                'status' => $transaksi->status
            ]);
        } catch (\Exception $e) {
            Log::error("Midtrans Finish Exception for Order ID {$orderId}: " . $e->getMessage());
            return response()->json(['message' => 'Failed to verify payment with Midtrans'], 500);
        }
    }
}

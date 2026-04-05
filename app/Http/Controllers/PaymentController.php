<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Transaction;

class PaymentController extends Controller
{
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
            } elseif (strtolower($transaksi->status) === 'dibatalkan') {
                \App\Models\Mobil::where('id', $transaksi->mobil_id)->update(['status' => 'tersedia']);
            }
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

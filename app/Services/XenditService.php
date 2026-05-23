<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected string $secretKey;
    protected string $baseUrl = 'https://api.xendit.co';

    public function __construct()
    {
        // 1. Ambil nilai dari config atau .env secara dinamis
        $this->secretKey = config('services.xendit.secret_key') ?? env('XENDIT_SECRET_KEY') ?? '';

        // 2. JALUR DARURAT DEMO/TESTING: Jika pembacaan cache .env macet, langsung tempel di sini
        if (empty($this->secretKey) || $this->secretKey === '') {
            // SILAKAN PASTE SECRET KEY XENDIT SANDBOX (xnd_development_...) KAMU DI BAWAH INI:
            $this->secretKey = 'xnd_development_lLdwYKQD10Hec26ufWP1Q9iOrxZA6OihqMK5NfpM0fOR1gNmUhKfxJzU0KA';
        }
    }

    /**
     * Buat invoice Xendit untuk DP 50%
     */
    public function createDpInvoice(Order $order): array
    {
        $payment = Payment::create([
            'order_id'          => $order->id,
            'payment_reference' => Payment::generateReference(),
            'type'              => 'dp',
            'amount'            => $order->dp_amount,
            'status'            => 'pending',
        ]);

        $payload = [
            'external_id'       => $payment->payment_reference,
            'amount'            => (int) $order->dp_amount,
            'description'       => "DP 50% - Pesanan {$order->order_number} ({$order->package->name})",
            'invoice_duration'  => 86400, // 24 jam
            'customer'          => [
                'given_names'   => $order->contact_name,
                'mobile_number' => $order->contact_phone,
                'email'         => $order->user->email,
            ],
            'customer_notification_preference' => [
                'invoice_created'  => ['whatsapp','email'],
                'invoice_reminder' => ['whatsapp','email'],
                'invoice_paid'     => ['whatsapp','email'],
            ],
            'success_redirect_url' => route('customer.orders.show', $order->order_number),
            'failure_redirect_url' => route('customer.orders.show', $order->order_number),
            'items' => [[
                'name'     => "DP 50% - {$order->package->name} ({$order->quantity} kotak)",
                'quantity' => 1,
                'price'    => (int) $order->dp_amount,
            ]],
        ];

        // Format otentikasi Basic Auth Xendit menggunakan Base64 (SecretKey + Tanda Titik Dua)
        $apiKeyBase64 = base64_encode($this->secretKey . ':');

        $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $apiKeyBase64,
                'Content-Type'  => 'application/json'
            ])->post("{$this->baseUrl}/v2/invoices", $payload);

        $data = $response->json();

        if ($response->successful()) {
            $payment->update([
                'xendit_invoice_id' => $data['id'],
                'xendit_response'   => $data,
            ]);
            $order->update(['payment_status' => 'dp_pending']);

            return ['success' => true, 'invoice_url' => $data['invoice_url'], 'payment' => $payment];
        }

        Log::error('Xendit DP Invoice Error', ['order' => $order->id, 'response' => $data]);
        return ['success' => false, 'message' => $data['message'] ?? 'Gagal membuat invoice'];
    }

    /**
     * Buat invoice Xendit untuk pelunasan (jika bayar online)
     */
    public function createFullPaymentInvoice(Order $order): array
    {
        $payment = Payment::create([
            'order_id'          => $order->id,
            'payment_reference' => Payment::generateReference(),
            'type'              => 'full_payment',
            'amount'            => $order->remaining_amount,
            'status'            => 'pending',
        ]);

        $payload = [
            'external_id'      => $payment->payment_reference,
            'amount'           => (int) $order->remaining_amount,
            'description'      => "Pelunasan - Pesanan {$order->order_number} ({$order->package->name})",
            'invoice_duration' => 86400,
            'customer'         => [
                'given_names'   => $order->contact_name,
                'mobile_number' => $order->contact_phone,
                'email'         => $order->user->email,
            ],
            'success_redirect_url' => route('customer.orders.show', $order->order_number),
            'failure_redirect_url' => route('customer.orders.show', $order->order_number),
            'items' => [[
                'name'     => "Pelunasan - {$order->package->name} ({$order->quantity} kotak)",
                'quantity' => 1,
                'price'    => (int) $order->remaining_amount,
            ]],
        ];

        $apiKeyBase64 = base64_encode($this->secretKey . ':');

        $response = Http::withHeaders([
                'Authorization' => 'Basic ' . $apiKeyBase64,
                'Content-Type'  => 'application/json'
            ])->post("{$this->baseUrl}/v2/invoices", $payload);

        $data = $response->json();

        if ($response->successful()) {
            $payment->update([
                'xendit_invoice_id' => $data['id'],
                'xendit_response'   => $data,
            ]);
            $order->update(['payment_status' => 'full_pending']);
            return ['success' => true, 'invoice_url' => $data['invoice_url'], 'payment' => $payment];
        }

        Log::error('Xendit Full Payment Error', ['order' => $order->id, 'response' => $data]);
        return ['success' => false, 'message' => $data['message'] ?? 'Gagal membuat invoice'];
    }

    /**
     * Handle webhook dari Xendit
     */
    public function handleWebhook(array $payload): bool
    {
        $externalId = $payload['external_id'] ?? null;
        $status     = $payload['status'] ?? null;

        if (!$externalId || !$status) return false;

        $payment = Payment::where('payment_reference', $externalId)->first();
        if (!$payment) return false;

        if ($status === 'PAID') {
            $payment->update([
                'status'           => 'paid',
                'paid_at'          => now(),
                'xendit_payment_id'=> $payload['id'] ?? null,
                'xendit_response'  => $payload,
                'method'           => $this->resolveMethod($payload),
            ]);

            $order = $payment->order;

            if ($payment->type === 'dp') {
                $order->update([
                    'payment_status' => 'dp_paid',
                    'status'         => 'dp_paid',
                ]);
                $this->notifyDpPaid($order);
            } elseif ($payment->type === 'full_payment') {
                $order->update([
                    'payment_status' => 'fully_paid',
                    'status'         => 'completed',
                ]);
                $this->notifyFullyPaid($order);
            }

            return true;
        }

        if (in_array($status, ['EXPIRED', 'FAILED'])) {
            $payment->update([
                'status'          => strtolower($status),
                'xendit_response' => $payload,
            ]);

            $order = $payment->order;
            if ($order && $payment->type === 'dp' && $order->status === 'pending') {
                $order->update(['payment_status' => 'unpaid']);
            } elseif ($order && $payment->type === 'full_payment' && $order->payment_status === 'full_pending') {
                $order->update(['payment_status' => 'dp_paid']);
            }

            return true;
        }

        return false;
    }

    /**
     * Konfirmasi cash payment oleh admin saat delivery
     */
    public function confirmCashPayment(Order $order, string $notes = ''): Payment
    {
        $payment = Payment::create([
            'order_id'          => $order->id,
            'payment_reference' => Payment::generateReference(),
            'type'              => 'full_payment',
            'amount'            => $order->remaining_amount,
            'method'            => 'cash',
            'status'            => 'paid',
            'paid_at'           => now(),
            'admin_notes'       => $notes,
        ]);

        $order->update([
            'payment_status' => 'fully_paid',
            'status'         => 'completed',
        ]);

        return $payment;
    }

    private function resolveMethod(array $payload): string
    {
        $paymentMethod = strtolower($payload['payment_method'] ?? '');
        return match(true) {
            str_contains($paymentMethod, 'va')     => 'xendit_va',
            str_contains($paymentMethod, 'qris')   => 'xendit_qris',
            in_array($paymentMethod, ['dana','gopay','ovo','linkaja','shopeepay']) => 'xendit_ewallet',
            default => 'xendit_va',
        };
    }

    private function notifyDpPaid(Order $order): void
    {
        Log::info("DP paid for order {$order->order_number}");
    }

    private function notifyFullyPaid(Order $order): void
    {
        Log::info("Fully paid for order {$order->order_number}");
    }
}
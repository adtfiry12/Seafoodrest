<?php

namespace App\Services;

use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class MidtransService
{
    protected string $serverKey;

    protected string $clientKey;

    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = (string) config('services.midtrans.server_key', '');
        $this->clientKey = (string) config('services.midtrans.client_key', '');
        $this->isProduction = (bool) config('services.midtrans.is_production', false);
    }

    public function isConfigured(): bool
    {
        return trim($this->serverKey) !== '' && trim($this->clientKey) !== '';
    }

    public function getClientKey(): string
    {
        return $this->clientKey;
    }

    public function getSnapJsUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }

    protected function getSnapApiUrl(): string
    {
        return $this->isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
    }

    protected function getCoreApiBaseUrl(): string
    {
        return $this->isProduction
            ? 'https://api.midtrans.com/v2'
            : 'https://api.sandbox.midtrans.com/v2';
    }

    public function refundIfNeeded(Transaksi $transaksi): void
    {
        if ($transaksi->metode_pembayaran !== 'QRIS' || $transaksi->status_pembayaran !== 'Lunas') {
            return;
        }

        if (in_array($transaksi->refund_status, ['requested', 'completed'], true)) {
            return;
        }

        if (! $this->isConfigured()) {
            throw ValidationException::withMessages([
                'status_pesanan' => 'Refund belum dapat diproses karena konfigurasi Midtrans belum tersedia.',
            ]);
        }

        $orderId = str_starts_with((string) $transaksi->payment_reference, $transaksi->kode_transaksi)
            ? $transaksi->payment_reference
            : $transaksi->kode_transaksi;
        $refundKey = 'REFUND-'.$transaksi->kode_transaksi;

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->acceptJson()
                ->asJson()
                ->timeout(10)
                ->withOptions([
                    'connect_timeout' => 4,
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ])
                ->post($this->getCoreApiBaseUrl().'/'.urlencode($orderId).'/refund', [
                    'refund_key' => $refundKey,
                    'amount' => (int) $transaksi->total_bayar,
                    'reason' => 'Pesanan dibatalkan',
                ]);

            if (! $response->successful()) {
                Log::warning('Midtrans refund failed', [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'order_id' => $orderId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                throw ValidationException::withMessages([
                    'status_pesanan' => 'Refund Midtrans gagal diproses. Pesanan belum dibatalkan.',
                ]);
            }

            $refundReference = $response->json('refund_key')
                ?? $response->json('refund_id')
                ?? $refundKey;

            $transaksi->update([
                'refund_status' => 'requested',
                'refund_reference' => substr((string) $refundReference, 0, 150),
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Midtrans refund exception', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'status_pesanan' => 'Refund Midtrans tidak dapat dihubungi. Pesanan belum dibatalkan.',
            ]);
        }
    }

    /**
     * Synchronize a requested refund with Midtrans.
     */
    public function syncRefundStatus(Transaksi $transaksi): bool
    {
        if (! $this->isConfigured() || $transaksi->refund_status !== 'requested') {
            return false;
        }

        $orderId = str_starts_with((string) $transaksi->payment_reference, $transaksi->kode_transaksi)
            ? $transaksi->payment_reference
            : $transaksi->kode_transaksi;

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->acceptJson()
                ->timeout(10)
                ->withOptions([
                    'connect_timeout' => 4,
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ])
                ->get($this->getCoreApiBaseUrl().'/'.urlencode($orderId).'/status');

            if ($response->status() === 404) {
                return false;
            }

            if (! $response->successful()) {
                Log::warning('Midtrans refund status check failed', [
                    'kode_transaksi' => $transaksi->kode_transaksi,
                    'order_id' => $orderId,
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return false;
            }

            $data = $response->json();
            $refund = $this->extractRefundData(is_array($data) ? $data : [], $transaksi->refund_reference);
            $refundStatus = $this->getRefundStatus(is_array($data) ? $data : [], $refund);

            $normalizedStatus = match ($refundStatus) {
                'completed', 'success', 'successful' => 'completed',
                'failed', 'failure', 'rejected', 'cancelled', 'canceled' => 'failed',
                'pending', 'processing', 'requested' => 'requested',
                default => null,
            };

            if (! $normalizedStatus) {
                return false;
            }

            $updateData = ['refund_status' => $normalizedStatus];
            $refundReference = $refund['refund_key'] ?? $refund['refund_id'] ?? null;
            if ($refundReference && ! $transaksi->refund_reference) {
                $updateData['refund_reference'] = substr((string) $refundReference, 0, 150);
            }
            if ($normalizedStatus === 'completed' && ! $transaksi->refunded_at) {
                $updateData['refunded_at'] = now();
            }

            $transaksi->update($updateData);

            return true;
        } catch (\Throwable $exception) {
            Log::warning('Midtrans refund status sync error', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'order_id' => $orderId,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * @return array<string, mixed>
     */
    protected function extractRefundData(array $data, ?string $refundReference): array
    {
        $refunds = $data['refunds'] ?? [];
        if (is_array($refunds)) {
            foreach ($refunds as $refund) {
                if (! is_array($refund)) {
                    continue;
                }

                $reference = $refund['refund_key'] ?? $refund['refund_id'] ?? null;
                if ($refundReference && $reference === $refundReference) {
                    return $refund;
                }
            }

            $latestRefund = end($refunds);

            if (is_array($latestRefund)) {
                return $latestRefund;
            }
        }

        return isset($data['refund_status']) || isset($data['status']) ? $data : [];
    }

    /**
     * Request an official Snap Token from Midtrans API for the given transaction.
     */
    public function createSnapToken(Transaksi $transaksi, bool $forceNew = false): ?string
    {
        if (! $forceNew && $transaksi->snap_token && ! str_starts_with($transaksi->snap_token, 'SIM-')) {
            return $transaksi->snap_token;
        }

        if (! $this->isConfigured()) {
            Log::error('Midtrans API Key belum dikonfigurasi di .env');

            return null;
        }

        $transaksi->loadMissing(['detail.menu', 'pelanggan']);

        $itemDetails = [];
        $calculatedTotal = 0;

        foreach ($transaksi->detail as $detail) {
            $price = (int) $detail->harga_satuan;
            $qty = (int) $detail->qty;
            $itemDetails[] = [
                'id' => 'MENU-'.($detail->menu_id ?? $detail->id),
                'price' => $price,
                'quantity' => $qty,
                'name' => substr($detail->menu->nama_menu ?? $detail->nama_menu ?? 'Hidangan Laut', 0, 50),
            ];
            $calculatedTotal += ($price * $qty);
        }

        if ((int) $transaksi->ongkos_kirim > 0) {
            $ongkir = (int) $transaksi->ongkos_kirim;
            $itemDetails[] = [
                'id' => 'ONGKIR-DELIVERY',
                'price' => $ongkir,
                'quantity' => 1,
                'name' => 'Ongkos Kirim Delivery',
            ];
            $calculatedTotal += $ongkir;
        }

        $grossAmount = (int) $transaksi->total_bayar;
        if ($calculatedTotal !== $grossAmount || empty($itemDetails)) {
            $itemDetails = [
                [
                    'id' => $transaksi->kode_transaksi,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => 'Pesanan '.$transaksi->kode_transaksi,
                ],
            ];
        }

        $rawName = trim((string) ($transaksi->nama_pelanggan ?: ($transaksi->pelanggan->nama ?? 'Pelanggan')));
        $customerName = substr($rawName ?: 'Pelanggan', 0, 50);

        $customerEmail = trim((string) ($transaksi->pelanggan->email ?? 'pelanggan@niskalasamudra.com'));
        if (! filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
            $customerEmail = 'pelanggan@niskalasamudra.com';
        }

        $rawPhone = trim((string) ($transaksi->no_hp_pelanggan ?: ($transaksi->pelanggan->no_hp ?? '')));
        $cleanDigits = preg_replace('/[^0-9]/', '', $rawPhone);
        if (str_starts_with($cleanDigits, '628')) {
            $customerPhone = '0'.substr($cleanDigits, 2);
        } elseif (str_starts_with($cleanDigits, '8')) {
            $customerPhone = '0'.$cleanDigits;
        } elseif (str_starts_with($cleanDigits, '08')) {
            $customerPhone = $cleanDigits;
        } else {
            $customerPhone = strlen($cleanDigits) >= 9 ? $cleanDigits : '081234567890';
        }

        $customerAddress = $transaksi->alamat ?: 'Jl. Pantai Kartini No. 12, Jepara (Niskala Samudra)';

        $addressObject = [
            'first_name' => $customerName,
            'email' => $customerEmail,
            'phone' => $customerPhone,
            'address' => substr($customerAddress, 0, 200),
            'city' => 'Jepara',
            'postal_code' => '59411',
            'country_code' => 'IDN',
        ];

        try {
            $currentHost = request()->getSchemeAndHttpHost();
            if ($currentHost && ! str_contains($currentHost, 'localhost') && ! str_contains($currentHost, '127.0.0.1')) {
                $finishUrl = $currentHost.'/checkout/finish';
            } else {
                $finishUrl = route('checkout.midtrans.finish');
            }
        } catch (\Throwable) {
            $finishUrl = url('/checkout/finish');
        }

        $orderId = $transaksi->kode_transaksi;

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $customerName,
                'email' => $customerEmail,
                'phone' => $customerPhone,
                'billing_address' => $addressObject,
                'shipping_address' => $addressObject,
            ],
            'callbacks' => [
                'finish' => $finishUrl,
                'unfinish' => $finishUrl,
                'error' => $finishUrl,
            ],
            'expiry' => [
                'unit' => 'minutes',
                'duration' => 60,
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->serverKey, '')
                ->acceptJson()
                ->asJson()
                ->timeout(10)
                ->withOptions([
                    'connect_timeout' => 4,
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ])
                ->post($this->getSnapApiUrl(), $payload);

            // Jika order_id sudah pernah dipakai sebelumnya di Midtrans, coba ulang dengan sufiks deterministik (-R1, -R2)
            foreach (['-R1', '-R2'] as $suffix) {
                if ($response->status() !== 400) {
                    break;
                }
                $payload['transaction_details']['order_id'] = $transaksi->kode_transaksi.$suffix;
                $response = Http::withBasicAuth($this->serverKey, '')
                    ->acceptJson()
                    ->asJson()
                    ->timeout(15)
                    ->post($this->getSnapApiUrl(), $payload);
            }

            if ($response->successful() && $response->json('token')) {
                $token = (string) $response->json('token');
                $usedId = $payload['transaction_details']['order_id'] ?? $transaksi->kode_transaksi;
                $updateData = ['snap_token' => $token];
                if ($usedId !== $transaksi->kode_transaksi && ! $transaksi->payment_reference) {
                    $updateData['payment_reference'] = $usedId;
                }
                $transaksi->update($updateData);

                return $token;
            }

            Log::warning('Midtrans createSnapToken failed', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Midtrans createSnapToken exception', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Query Midtrans Core API v2 to synchronize transaction payment status in real-time.
     */
    public function syncTransactionStatus(Transaksi $transaksi, ?string $orderIdHint = null, ?string $transactionIdHint = null): array
    {
        if (! $this->isConfigured()) {
            return [
                'synced' => false,
                'status_pembayaran' => $transaksi->status_pembayaran,
                'payment_channel' => $transaksi->payment_channel_label,
                'payment_reference' => $transaksi->payment_reference,
                'refund_status' => $transaksi->refund_status,
                'dibayar_pada' => $transaksi->dibayar_pada?->format('d M Y, H:i'),
            ];
        }

        $candidates = [];
        if ($transactionIdHint && trim($transactionIdHint) !== '') {
            $candidates[] = trim($transactionIdHint);
        }
        if ($orderIdHint && str_starts_with($orderIdHint, $transaksi->kode_transaksi)) {
            $candidates[] = $orderIdHint;
        }
        if ($transaksi->payment_reference && (str_starts_with($transaksi->payment_reference, 'TRX-') || preg_match('/^[0-9a-fA-F-]{36}$/', $transaksi->payment_reference))) {
            $candidates[] = $transaksi->payment_reference;
        }
        $candidates[] = $transaksi->kode_transaksi;
        $candidates = array_values(array_unique(array_filter($candidates)));

        try {
            foreach ($candidates as $candidateOrderId) {
                $url = $this->getCoreApiBaseUrl().'/'.urlencode($candidateOrderId).'/status';
                $response = Http::withBasicAuth($this->serverKey, '')
                    ->acceptJson()
                    ->timeout(4)
                    ->withOptions([
                        'connect_timeout' => 3,
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && isset($data['transaction_status']) && ($data['status_code'] ?? '') !== '404') {
                        if ($transaksi->refund_status === 'requested') {
                            $this->applyRefundStatus($transaksi, $data);
                        }

                        $this->applyGatewayStatus($transaksi, $data);
                        $transaksi->refresh();
                        if ($transaksi->status_pembayaran === 'Lunas') {
                            break;
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Midtrans syncTransactionStatus error', [
                'kode_transaksi' => $transaksi->kode_transaksi,
                'message' => $e->getMessage(),
            ]);
        }

        return [
            'synced' => true,
            'status_pembayaran' => $transaksi->status_pembayaran,
            'payment_channel' => $transaksi->payment_channel_label,
            'payment_reference' => $transaksi->payment_reference,
            'refund_status' => $transaksi->refund_status,
            'dibayar_pada' => $transaksi->dibayar_pada?->format('d M Y, H:i'),
        ];
    }

    /**
     * Apply the latest refund entry from a transaction status response.
     */
    protected function applyRefundStatus(Transaksi $transaksi, array $data): void
    {
        if (
            (! isset($data['refunds']) || ! is_array($data['refunds']))
            && ! in_array(strtolower((string) ($data['transaction_status'] ?? '')), ['refund', 'partial_refund'], true)
        ) {
            return;
        }

        $refund = $this->extractRefundData($data, $transaksi->refund_reference);
        $refundStatus = $this->getRefundStatus($data, $refund);
        $normalizedStatus = match ($refundStatus) {
            'completed', 'success', 'successful' => 'completed',
            'failed', 'failure', 'rejected', 'cancelled', 'canceled' => 'failed',
            'pending', 'processing', 'requested' => 'requested',
            default => null,
        };

        if (! $normalizedStatus) {
            return;
        }

        $updateData = ['refund_status' => $normalizedStatus];
        if ($normalizedStatus === 'completed' && ! $transaksi->refunded_at) {
            $updateData['refunded_at'] = now();
        }

        $transaksi->update($updateData);
    }

    /**
     * Normalize refund status values returned by the transaction status API.
     *
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $refund
     */
    protected function getRefundStatus(array $data, array $refund): string
    {
        $transactionStatus = strtolower((string) ($data['transaction_status'] ?? ''));
        if ($transactionStatus === 'refund') {
            return 'completed';
        }

        if ($transactionStatus === 'partial_refund') {
            return 'requested';
        }

        return strtolower((string) ($refund['status'] ?? $refund['refund_status'] ?? ''));
    }

    /**
     * Handle incoming webhook notification from Midtrans Server.
     */
    public function handleWebhookNotification(array $payload): array
    {
        $orderId = (string) ($payload['order_id'] ?? '');
        $statusCode = (string) ($payload['status_code'] ?? '');
        $grossAmount = (string) ($payload['gross_amount'] ?? '');
        $signatureKey = (string) ($payload['signature_key'] ?? '');

        if ($orderId === '') {
            return ['success' => false, 'message' => 'Order ID kosong.'];
        }

        if ($this->isConfigured()) {
            $expectedSignature = hash('sha512', $orderId.$statusCode.$grossAmount.$this->serverKey);
            if (! hash_equals($expectedSignature, $signatureKey)) {
                return ['success' => false, 'message' => 'Signature key Midtrans tidak valid.'];
            }
        }

        $baseKode = preg_replace('/^(TRX-\d{8}-[A-Z0-9]{5}).*$/', '$1', $orderId);
        $transaksi = Transaksi::where('kode_transaksi', $orderId)
            ->orWhere('kode_transaksi', $baseKode)
            ->first();

        if (! $transaksi) {
            return ['success' => false, 'message' => 'Transaksi tidak ditemukan.'];
        }

        $this->applyGatewayStatus($transaksi, $payload);

        return ['success' => true, 'message' => 'Status pembayaran berhasil diperbarui.'];
    }

    /**
     * Apply Midtrans transaction_status payload onto the Transaksi model.
     */
    public function applyGatewayStatus(Transaksi $transaksi, array $data): void
    {
        $transactionStatus = strtolower((string) ($data['transaction_status'] ?? ''));
        $fraudStatus = strtolower((string) ($data['fraud_status'] ?? 'accept'));
        $rawPaymentType = strtolower((string) ($data['payment_type'] ?? ''));
        $acquirer = strtolower((string) ($data['acquirer'] ?? ''));
        $issuer = strtolower((string) ($data['issuer'] ?? ''));
        $store = strtolower((string) ($data['store'] ?? ''));
        $channelResponse = strtolower((string) ($data['channel_response_message'] ?? ''));
        $channelHint = strtolower((string) ($data['channel'] ?? $data['selected_channel'] ?? ''));

        // Deteksi metode / channel spesifik (DANA, GoPay, ShopeePay, OVO, Virtual Account, dll.)
        $resolvedChannel = null;

        // 1. Cek jika transaksi sudah memiliki channel e-wallet spesifik yang dipilih pelanggan
        if ($transaksi->payment_type && in_array(strtolower($transaksi->payment_type), ['dana', 'gopay', 'ovo', 'shopeepay'], true)) {
            $resolvedChannel = strtolower($transaksi->payment_type);
        }

        // 2. Cek hint eksplisit dari frontend atau request callback
        if (! $resolvedChannel) {
            if (str_contains($channelHint, 'dana')) {
                $resolvedChannel = 'dana';
            } elseif (str_contains($channelHint, 'ovo')) {
                $resolvedChannel = 'ovo';
            } elseif (str_contains($channelHint, 'shopee')) {
                $resolvedChannel = 'shopeepay';
            } elseif (str_contains($channelHint, 'gopay')) {
                $resolvedChannel = 'gopay';
            } elseif (str_contains($channelHint, 'qris')) {
                $resolvedChannel = 'qris';
            }
        }

        // 3. Cek respon Midtrans gateway
        if (! $resolvedChannel) {
            if (str_contains($issuer, 'dana') || str_contains($acquirer, 'dana') || str_contains($channelResponse, 'dana') || $rawPaymentType === 'dana') {
                $resolvedChannel = 'dana';
            } elseif (str_contains($issuer, 'ovo') || str_contains($acquirer, 'ovo') || str_contains($channelResponse, 'ovo') || $rawPaymentType === 'ovo') {
                $resolvedChannel = 'ovo';
            } elseif ($rawPaymentType === 'gopay' || str_contains($issuer, 'gopay') || str_contains($channelResponse, 'gopay')) {
                $resolvedChannel = 'gopay';
            } elseif ($rawPaymentType === 'shopeepay' || str_contains($issuer, 'shopee') || str_contains($acquirer, 'airpay') || str_contains($channelResponse, 'shopeepay')) {
                $resolvedChannel = 'shopeepay';
            } elseif (str_contains($issuer, 'linkaja') || str_contains($acquirer, 'linkaja') || $rawPaymentType === 'linkaja') {
                $resolvedChannel = 'linkaja';
            } elseif (! empty($data['va_numbers'][0]['bank'])) {
                $resolvedChannel = strtolower($data['va_numbers'][0]['bank']).'_va';
            } elseif (! empty($data['bank']) && (str_contains($rawPaymentType, 'va') || str_contains($rawPaymentType, 'transfer') || str_contains($rawPaymentType, 'bank'))) {
                $resolvedChannel = strtolower($data['bank']).'_va';
            } elseif ($rawPaymentType === 'echannel' || ! empty($data['bill_key'])) {
                $resolvedChannel = 'mandiri_va';
            } elseif (! empty($data['permata_va_number'])) {
                $resolvedChannel = 'permata_va';
            } elseif ($rawPaymentType === 'cstore' || ! empty($store)) {
                $resolvedChannel = $store ?: 'cstore';
            } elseif ($rawPaymentType === 'qris') {
                // Untuk QRIS, periksa jika simulator/issuer menyebut spesifik
                if (str_contains($acquirer, 'dana') || str_contains($issuer, 'dana')) {
                    $resolvedChannel = 'dana';
                } elseif (str_contains($issuer, 'gopay') || $channelHint === 'gopay') {
                    $resolvedChannel = 'gopay';
                } else {
                    $resolvedChannel = 'qris';
                }
            } elseif ($rawPaymentType !== '') {
                $resolvedChannel = $rawPaymentType;
            }
        }

        $paymentType = $resolvedChannel ?: ($transaksi->payment_type ?: 'qris');

        // Ekstrak referensi pembayaran (Nomor VA, Bill Key, Payment Code, dsb.)
        $reference = null;
        if (! empty($data['va_numbers'][0]['va_number'])) {
            $reference = (string) $data['va_numbers'][0]['va_number'];
        } elseif (! empty($data['permata_va_number'])) {
            $reference = (string) $data['permata_va_number'];
        } elseif (! empty($data['bill_key'])) {
            $billerCode = ! empty($data['biller_code']) ? trim((string) $data['biller_code']).' - ' : '';
            $reference = $billerCode.trim((string) $data['bill_key']);
        } elseif (! empty($data['payment_code'])) {
            $reference = (string) $data['payment_code'];
        } elseif (! empty($data['va_number'])) {
            $reference = (string) $data['va_number'];
        } elseif (! empty($data['transaction_id'])) {
            $reference = (string) $data['transaction_id'];
        } elseif (! empty($data['payment_reference'])) {
            $reference = (string) $data['payment_reference'];
        } else {
            $reference = $transaksi->payment_reference;
        }

        if ($transactionStatus === 'refund') {
            $transaksi->update([
                'refund_status' => 'completed',
                'refunded_at' => $transaksi->refunded_at ?? now(),
            ]);

            return;
        }

        $isPaid = ($transactionStatus === 'settlement') ||
            ($transactionStatus === 'capture' && $fraudStatus === 'accept') ||
            (($data['status_code'] ?? '') === '200' && in_array($transactionStatus, ['', 'settlement', 'capture'], true));

        $isFailedOrExpired = in_array($transactionStatus, ['deny', 'cancel', 'expire'], true);

        if ($isPaid) {
            $updateData = [
                'status_pembayaran' => 'Lunas',
                'dibayar_pada' => $transaksi->dibayar_pada ?? now(),
            ];

            // Update payment_type jika channel teridentifikasi lebih spesifik
            if ($paymentType && ($paymentType !== 'qris' || ! $transaksi->payment_type || $transaksi->payment_type === 'qris' || $transaksi->payment_type === 'transfer')) {
                $updateData['payment_type'] = $paymentType;
            }

            // Update payment_reference jika ditemukan referensi baru
            if ($reference && (! $transaksi->payment_reference || $reference !== $transaksi->payment_reference)) {
                $updateData['payment_reference'] = substr((string) $reference, 0, 150);
            }

            $transaksi->update($updateData);
        } elseif ($isFailedOrExpired && $transaksi->status_pembayaran !== 'Lunas') {
            DB::transaction(function () use ($transaksi, $paymentType, $reference) {
                $transaksi->update([
                    'status_pembayaran' => 'Batal',
                    'status_pesanan' => 'Dibatalkan',
                    'payment_type' => $paymentType,
                    'payment_reference' => $reference ? substr((string) $reference, 0, 150) : $transaksi->payment_reference,
                ]);
                $transaksi->restoreStockIfNeeded();
            });
        } elseif ($transactionStatus === 'pending') {
            $updateData = [];
            if ($paymentType && $paymentType !== $transaksi->payment_type) {
                $updateData['payment_type'] = $paymentType;
            }
            if ($reference && $reference !== $transaksi->payment_reference) {
                $updateData['payment_reference'] = substr((string) $reference, 0, 150);
            }
            if (! empty($updateData)) {
                $transaksi->update($updateData);
            }
        }
    }
}

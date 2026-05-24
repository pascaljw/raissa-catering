<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $provider;
    protected ?string $apiUrl;
    protected ?string $apiToken;
    protected ?string $deviceId;

    public function __construct()
    {
        $this->provider  = config('services.whatsapp.provider', 'fontte');
        $this->apiUrl    = config('services.whatsapp.api_url');
        $this->apiToken  = config('services.whatsapp.api_token');
        $this->deviceId  = config('services.whatsapp.device_id');
    }

    public function sendTextMessage(string $phone, string $message): bool
    {
        if (empty($this->apiUrl) || empty($this->apiToken)) {
            Log::warning('WhatsAppService is not configured. WA message was not sent.', [
                'phone'   => $phone,
                'message' => $message,
            ]);
            return false;
        }

        return match ($this->provider) {
            'fontte' => $this->sendViaFontte($phone, $message),
            default  => $this->sendViaGeneric($phone, $message),
        };
    }

    private function sendViaFontte(string $phone, string $message): bool
    {
        $formattedPhone = $this->formatPhoneForFontte($phone);

        $payload = [
            'target'  => $formattedPhone,
            'message' => $message,
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiToken, // No "Bearer" prefix — Fontte requires raw token
            ])->asForm()->post($this->apiUrl, $payload); // asForm() — Fontte requires form data, not JSON

            $responseData = $response->json();

            if (($response->successful() || $response->status() === 201) && ($responseData['status'] ?? false) === true) {
                Log::info('WhatsApp message successfully sent via Fontte.', [
                    'phone'    => $phone,
                    'response' => $responseData,
                ]);
                return true;
            }

            Log::error('Fontte WhatsApp API responded with an error.', [
                'phone'   => $phone,
                'status'  => $response->status(),
                'body'    => $responseData,
                'payload' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('WhatsAppService failed to send message via Fontte.', [
                'phone'   => $phone,
                'error'   => $exception->getMessage(),
                'payload' => $payload,
            ]);
        }

        return false;
    }

    private function sendViaGeneric(string $phone, string $message): bool
    {
        $payload = [
            'phone'   => $this->formatPhone($phone),
            'message' => $message,
        ];

        try {
            $response = Http::withToken($this->apiToken)
                ->post($this->apiUrl, $payload);

            if ($response->successful()) {
                Log::info('WhatsApp message successfully sent.', [
                    'phone'    => $phone,
                    'response' => $response->json(),
                ]);
                return true;
            }

            Log::error('WhatsApp API responded with an error.', [
                'phone'   => $phone,
                'status'  => $response->status(),
                'body'    => $response->body(),
                'payload' => $payload,
            ]);
        } catch (\Throwable $exception) {
            Log::error('WhatsAppService failed to send message.', [
                'phone'   => $phone,
                'error'   => $exception->getMessage(),
                'payload' => $payload,
            ]);
        }

        return false;
    }

    private function formatPhoneForFontte(string $phone): string
    {
        $normalized = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($normalized, '0')) {
            return '62' . substr($normalized, 1);
        }

        if (str_starts_with($normalized, '+62')) {
            return substr($normalized, 1);
        }

        if (str_starts_with($normalized, '+')) {
            return substr($normalized, 1);
        }

        if (!str_starts_with($normalized, '62')) {
            return '62' . $normalized;
        }

        return $normalized;
    }

    private function formatPhone(string $phone): string
    {
        $normalized = preg_replace('/[^0-9+]/', '', $phone);

        if (str_starts_with($normalized, '0')) {
            return '+62' . substr($normalized, 1);
        }

        if (!str_starts_with($normalized, '+')) {
            return '+' . $normalized;
        }

        return $normalized;
    }
}
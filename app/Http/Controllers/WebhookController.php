<?php
namespace App\Http\Controllers;

use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function __construct(protected XenditService $xendit) {}

    /**
     * Handle Xendit Invoice Webhook
     * POST /webhook/xendit
     */
    public function xendit(Request $request)
    {
        // Verifikasi token webhook dari Xendit
        $token = $request->header('x-callback-token');
        if ($token !== config('services.xendit.webhook_token')) {
            Log::warning('Invalid Xendit webhook token', ['token' => $token]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $payload = $request->all();
        Log::info('Xendit Webhook Received', ['payload' => $payload]);

        $success = $this->xendit->handleWebhook($payload);

        return response()->json(['success' => $success], 200);
    }
}

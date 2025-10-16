<?php

namespace App\Services;

use App\Jobs\ProcessStripeWebhook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class WebhookService
{
    public function process(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $secretKey = env('STRIPE_SECRET');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secretKey);
        } catch (\UnexpectedValueException $e) {
            Log::error($e->getMessage());
            abort(400);
        }
        ProcessStripeWebhook::dispatch($event);
        return response()->json(['status'=>'Queued']);
    }
}

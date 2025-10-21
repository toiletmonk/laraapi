<?php

namespace App\Jobs;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProcessStripeWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function handle(): void
    {
        switch ($this->event->type) {
            case 'payment_intent.payment_succeeded':
                $paymentIntent = $this->event->data->object;
                $userId = $paymentIntent->metadata->user->id ?? null;
                if (! $userId || ! User::where('id', $userId)->exists()) {
                    Log::warning("Payment intent for not existing user: {$userId}");
                }
                $data = [
                    'payment_status' => $paymentIntent->status,
                    'total_amount' => $paymentIntent->amount,
                    'currency' => $paymentIntent->currency,
                    'user_id' => $userId,
                ];
                $validator = Validator::make($data, [
                    'payment_status' => 'required|in:succeeded,failed,processing',
                    'total_amount' => 'required|numeric|min:10',
                    'currency' => 'required|in:usd,eur',
                    'user_id' => 'required|exists:users,id',
                ]);
                if ($validator->fails()) {
                    Log::error($validator->errors());

                    return;
                }

                if ($userId) {
                    try {
                        Order::updateOrCreate([
                            'user_id' => $userId,
                            'stripe_payment_intent_id' => $paymentIntent->id,
                            'payment_status' => $paymentIntent->status,
                            'total_amount' => $paymentIntent->amount,
                            'currency' => $paymentIntent->currency,
                        ]);
                        CartItem::where('user_id', $userId)->delete();
                    } catch (\Exception $exception) {
                        Log::error($exception->getMessage());
                    }
                }
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $this->event->data->object;
                $userId = $paymentIntent->metadata->user_id ?? null;
                if ($userId) {
                    Log::error("Payment failed for user {$userId}");
                }
                break;
            case 'payment_intent.payment_cancelled':
                $paymentIntent = $this->event->data->object;
                $userId = $paymentIntent->metadata->user_id ?? null;
                if ($userId) {
                    Log::error("Payment cancelled for user {$userId}");
                }
                break;
            default:
                Log::error("Unknown event type {$this->event->type}");
        }
    }
}

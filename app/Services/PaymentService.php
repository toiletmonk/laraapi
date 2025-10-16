<?php

namespace App\Services;


use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        Stripe::$verifySslCerts = false;
    }

    public function createPaymentIntent(int $amount, string $currency, int $userId, array $metadata = []): PaymentIntent
    {
        return PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'payment_method_types' => ['card'],
            'metadata' => array_merge($metadata, ['user_id' => $userId]),
        ]);
    }

    public function calculateAmount($cartItems): int
    {
        return $cartItems->sum(function ($cartItem) {
            return $cartItem->quantity * $cartItem->post->price;
        });
    }
}

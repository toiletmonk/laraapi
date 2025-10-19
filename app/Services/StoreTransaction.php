<?php

namespace App\Services;

use App\Models\Payment;

class StoreTransaction
{
    public function saveTransactionToDB(int $userId, $paymentIntent): Payment
    {
        return Payment::create([
            'user_id' => $userId,
            'provider' => 'stripe',
            'payment_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount,
            'currency' => $paymentIntent->currency,
            'status' => $paymentIntent->status,
        ]);
    }
}

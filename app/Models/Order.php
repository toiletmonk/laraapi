<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_payment_intent_id',
        'payment_status',
        'total_amount',
        'currency',
    ];
}

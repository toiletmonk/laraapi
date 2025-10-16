<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'post_id',
        'quantity',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function total(): float
    {
        return $this->post->price * $this->quantity;
    }

    public function toStripeArray(): array
    {
        return [
            'price' => $this->post->stripe_price_id,
            'quantity' => $this->quantity,
        ];
    }

    /**
     * Simplified array for frontend JSON.
     */
    public function toArrayForFrontend(): array
{
    return [
        'id' => $this->id,
        'quantity' => $this->quantity,
        'post' => [
            'id' => $this->post->id,
            'name' => $this->post->title,
            'description' => $this->post->description,
            'price' => $this->post->price,
            'stripe_price_id' => $this->post->stripe_price_id,
        ],
        'total' => $this->total(),
    ];
}
}

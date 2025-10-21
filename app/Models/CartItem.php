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

    public function toArrayForFrontend()
    {
        return [
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'quantity' => $this->quantity,
        ];
    }
}

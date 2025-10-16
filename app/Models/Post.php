<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use HasFactory, Searchable;
    protected $fillable = [
        'title',
        'content',
        'price',
        'stripe_price_id'
    ];

    public function toArrayForCart(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->title,
            'description' => $this->content,
            'price' => $this->price,
            'stripe_price_id' => $this->stripe_price_id ?? null,
        ];
    }
}

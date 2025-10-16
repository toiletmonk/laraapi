<?php

namespace App;

use App\Models\CartItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CartService
{
    protected function getCacheKey($user): string
    {
        return "cart_items_user_{$user->id}";
    }
    public function addToCart($postId, $quantity)
    {
        $user = auth()->user();
        $cartItem = CartItem::where('user_id', $user->id)->where('post_id', $postId)->first();

        if (!$cartItem) {
            $cartItem = new CartItem([
                'user_id'=>$user->id,
                'post_id'=>$postId,
                'quantity'=>$quantity,
            ]);
        } else {
            $cartItem->quantity += $quantity;
        }
        $cartItem->save();
        return $cartItem;
    }

    public function removeFromCart($postId, $quantity)
    {
        $user = auth()->user();
        $cartItem = CartItem::where('user_id', $user->id)->where('post_id', $postId)->first();
        if (!$cartItem) {
            return null;
        }
        $cartItem->quantity -= $quantity;
        if ($cartItem->quantity <= 0) {
            $cartItem->delete();
        }

        $cartItem->save();
        return $cartItem;
    }

    public function getAllCartItems()
    {
        $user = auth()->user();
        $cacheKey = $this->getCacheKey($user);

        return CartItem::with('post')->where('user_id', $user->id)->get();

    }
}

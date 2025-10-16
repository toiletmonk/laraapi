<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\CartItem;
use App\Models\Post;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function getAllCartItems()
    {
        $cartItems = $this->cartService->getAllCartItems();

        $data = $cartItems->map(fn($item) => $item->toArrayForFrontend());

        return response()->json($data, 200);
    }

    public function addToCart(AddToCartRequest $request, Post $post)
    {
        $quantity = $request->input('quantity', 1);

        $cartItem = $this->cartService->addToCart($post->id, $quantity);

        return response()->json($cartItem->toArrayForFrontend(), 200);
    }

    public function removeFromCart(Request $request, $postId)
    {
        $quantity = (int) $request->query('quantity', 1);
        $removed = $this->cartService->removeFromCart($postId, $quantity);

        return $removed
            ? response()->json(null, 200)
            : response()->json(null, 404);
    }

    public function getTotalAmount()
    {
        return $this->getAllCartItems()->sum(fn($item) => $item->post->price * $item->quantity);
    }

    public function clearAllCartItems(Request $request)
    {
        $user = $request->user();

        CartItem::query()->where('user_id', $user->id)->delete();

        return response()->json(['message'=>'Cart cleared'], 200);
    }
}

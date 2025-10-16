<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected PaymentService $checkoutService;
    protected CartService $cartService;

    public function __construct(PaymentService $checkoutService, CartService $cartService)
    {
        $this->checkoutService = $checkoutService;
        $this->cartService = $cartService;
    }

    public function createPayment(Request $request)
    {
        $user = $request->user();
        $cartItems = $this->cartService->getAllCartItems($user->id);

        if (empty($cartItems)) {
            return response()->json(['status'=>'Cart is empty']);
        }
        $amount = $this->checkoutService->calculateAmount($cartItems);

        $paymentIntent = $this->checkoutService->createPaymentIntent(
            $amount,
            'usd',
            $user->id
        );

        return response()->json(['client-secret'=>$paymentIntent->client_secret]);
    }
}

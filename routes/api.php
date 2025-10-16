<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SmsController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('password-reset', [PasswordResetController::class, 'reset']);
    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink']);
    Route::get('/auth/{provider}/redirect', [OAuthController::class, 'redirectToProvider']);
    Route::get('/auth/{provider}/callback', [OAuthController::class, 'callback']);
});

Route::post('stripe/webhook', [WebhookController::class, 'handleWebhook'])->name('stripe.webhook');
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/send-code', [SmsController::class, 'sendCode']);
    Route::post('/verify-code', [SmsController::class, 'verifyCode']);
});

Route::middleware(['auth:sanctum', 'check.expiration', 'throttle:checkout'])->group(function () {
    Route::delete('logout', [AuthController::class, 'logout']);
    Route::put('change-password', [AuthController::class, 'changePassword']);
    Route::apiResource('posts', PostController::class);

        Route::get('/cart-items', [CartController::class, 'getAllCartItems']);
        Route::post('/cart-items/add/{post}', [CartController::class, 'addToCart']);
        Route::delete('/cart-items/remove/{postId}', [CartController::class, 'removeFromCart']);
        Route::delete('/cart-items/clear', [CartController::class, 'clearAllCartItems']);

    Route::middleware('throttle:checkout')->group(function () {
        Route::post('checkout', [CheckoutController::class, 'createPayment']);
    });

    Route::post('upload', [UploadController::class, 'upload']);
    Route::delete('remove/{id}', [UploadController::class, 'delete']);
    Route::get('download', [DownloadController::class, 'download']);
});

<?php

namespace App;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(array $data): User
    {
        $cachedCode = Cache::get("verify_{$data['phone']}");
        if (!$cachedCode || $cachedCode != $data['phone_code']) {
            throw new AuthException('phone');
        }

        Cache::forget("verify_{$data['phone']}");

        $user = User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'phone_verified_at' => now(),
            'email_verified_at' => null,
        ]);

        $user->sendEmailVerificationNotification();

        return $user;
    }
    public function login(array $data): String
    {
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new AuthException('credentials');
        }

        if (!$user->hasVerifiedEmail() || !$user->phone_verified_at) {
            throw new AuthException('phone_email');
        }

        auth()->login($user);

        $expiresAt = isset($data['remember']) && $data['remember']
            ? now()->addDays(30)
            : now()->addHours(2);
        $token = $user->createToken('api-token', ['*']);
        $token->accessToken->expires_at = $expiresAt;
        $token->accessToken->save();

        return $token;
    }
    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();
    }

    public function changePassword(array $data)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            $message = !$user ? 'User not found' : 'Wrong password';
            $type = !$user ? 'credentials' : 'password';
            throw new AuthException($message, $type);
        }

        $user->password = Hash::make($data['new_password']);
        $saved = $user->save();

        if ($saved) {
            $user->tokens()->delete();
        }

        return $saved;
    }
}

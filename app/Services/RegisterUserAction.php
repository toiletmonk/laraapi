<?php

namespace App\Services;

use App\Exceptions\AuthException;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    protected AuditService $audit;
    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function execute(array $data): User
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

        $this->audit->log($user->id, 'register', [
            'email' => $user->email,
            'phone' => $user->phone,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $user->sendEmailVerificationNotification();

        return $user;
    }
}

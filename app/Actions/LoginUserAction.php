<?php

namespace App\Actions;

use App\Exceptions\AuthException;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginUserAction
{
    protected AuditService $audit;

    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function execute(array $data): string
    {
        $user = User::where('email', $data['email'])->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw new AuthException('credentials');
        }

        if (! $user->hasVerifiedEmail() || ! $user->phone_verified_at) {
            throw new AuthException('phone_email');
        }

        Auth::login($user);

        $this->audit->log($user->id, 'login', [
            'user_id' => $user->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $expiresAt = isset($data['remember']) && $data['remember']
            ? now()->addDays(30)
            : now()->addHours(2);
        $token = $user->createToken('api-token', ['*']);
        $token->accessToken->expires_at = $expiresAt;
        $token->accessToken->save();

        return $token;
    }
}

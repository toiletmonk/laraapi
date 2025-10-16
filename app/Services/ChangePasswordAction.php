<?php

namespace App\Services;

use App\Exceptions\AuthException;
use Illuminate\Support\Facades\Hash;

class ChangePasswordAction
{
    protected AuditService $audit;
    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function execute(array $data)
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

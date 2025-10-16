<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink(array $data): void
    {
        Password::sendResetLink(['email' => $data['email']]);
    }

    public function reset(array $data): string
    {
        return Password::reset(
            $data,
            function (User $user, $password) {
                $user->password = Hash::make($password);
                $user->save();
        }
        );
    }
}

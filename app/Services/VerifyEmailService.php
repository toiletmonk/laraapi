<?php

namespace App\Services;

use App\Jobs\SendVerificationMail;
use App\Jobs\SendWelcomeEmail;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerifyEmailService
{
    public function verify(EmailVerificationRequest $request): array
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return ['message'=>'User already verified.'];
        }
        $request->fulfill();

        SendWelcomeEmail::dispatch($user);

        return ['message'=>'User verified successfully.'];
    }

    public function resend(Request $request): array
    {
        $user = $request->user();
        if ($user->hasVerifiedEmail()) {
            return ['message'=>'User already verified.'];
        }

        $user->sendEmailVerificationNotification();
        return ['message'=>'Email verification link sent to your email address.'];
    }
}

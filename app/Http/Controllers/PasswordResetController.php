<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SendResetLinkRequest;
use App\PasswordResetService;
use Illuminate\Http\Request;

class PasswordResetController extends Controller
{
    protected PasswordResetService $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    public function sendResetLink(SendResetLinkRequest $request)
    {
        $this->passwordResetService->sendResetLink($request->validated());

        return back()->with(['message' => 'Reset link has been sent.']);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = $this->passwordResetService->reset($request->validated());

        if ($status === 'password.reset') {
            return redirect()->route('login')->with(['message' => 'Your password has been reset.']);
        }

        return back()->with(['message' => 'Something went wrong.']);
    }
}

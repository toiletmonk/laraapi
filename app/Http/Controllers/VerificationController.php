<?php

namespace App\Http\Controllers;

use App\Services\VerifyEmailService;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected VerifyEmailService $service;
    public function __construct(VerifyEmailService $service)
    {
        $this->service = $service;
    }

    public function verify(EmailVerificationRequest $request)
    {
        $response = $this->service->verify($request);

        return response()->json(['message'=>$response]);
    }

    public function resend(Request $request)
    {
        $response = $this->service->resend($request);

        return response()->json(['message'=>$response]);
    }
}

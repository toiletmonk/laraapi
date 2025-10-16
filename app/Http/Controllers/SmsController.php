<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwilioRequest;
use App\Jobs\SendVerificationSms;
use App\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SmsController extends Controller
{
    protected $twilio;

    public function __construct(TwilioService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function sendCode(TwilioRequest $request)
    {
        $validated = $request->validated();

        $phone = phone($validated['phone'], 'AUTO')->formatE164();

        $code = rand(100000, 999999);

        Cache::put("verify_{$phone}", $code, now()->addMinutes(10));

        SendVerificationSms::dispatch($phone, $code);

        return response()->json(['status'=>'Message sent successfully.']);
    }
}

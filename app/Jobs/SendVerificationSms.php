<?php

namespace App\Jobs;

use App\TwilioService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerificationSms implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    protected $phone;
    protected $code;
    public function __construct(string $phone, string $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(TwilioService $twilio): void
    {
        $twilio->sendMessage($this->phone, "Your verification code is: {$this->code}");
    }
}

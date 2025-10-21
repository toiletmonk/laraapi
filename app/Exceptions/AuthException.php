<?php

namespace App\Exceptions;

use Exception;

class AuthException extends Exception
{
    protected string $type;

    public function __construct(string $type, ?string $message = null)
    {
        $this->type = $type;

        if (! $message) {
            $message = match ($type) {
                'phone' => 'Phone number is not verified.',
                'email' => 'Email address is not verified.',
                'credentials' => 'Invalid email or password.',
                'password' => 'Password does not match.',
                default => 'Authentication error.',
            };
        }

        parent::__construct($message);
    }

    public function render($request)
    {
        return response()->json([
            'error_type' => $this->type,
            'message' => $this->getMessage(),
        ], 422);
    }
}

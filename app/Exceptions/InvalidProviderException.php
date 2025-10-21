<?php

namespace App\Exceptions;

use Exception;

class InvalidProviderException extends Exception
{
    public function __construct(string $provider)
    {
        parent::__construct("Invalid provider '{$provider}'.");
    }

    public function render($request)
    {
        return response()->json([
            'error' => 'Invalid provider',
            'message' => $this->getMessage(),
        ]);
    }
}

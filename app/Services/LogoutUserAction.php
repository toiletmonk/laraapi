<?php

namespace App\Services;

class LogoutUserAction
{
    public function execute()
    {
        auth()->user()->currentAccessToken()->delete();
    }
}

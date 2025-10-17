<?php

namespace App\Actions;

class LogoutUserAction
{
    public function execute()
    {
        auth()->user()->currentAccessToken()->delete();
    }
}

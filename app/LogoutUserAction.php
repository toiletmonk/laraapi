<?php

namespace App;

class LogoutUserAction
{
    public function execute()
    {
        auth()->user()->currentAccessToken()->delete();
    }
}

<?php

namespace App;

class LogoutUserAction
{
    protected AuditService $audit;
    public function __construct(AuditService $audit)
    {
        $this->audit = $audit;
    }

    public function execute()
    {
        auth()->user()->currentAccessToken()->delete();
        $this->audit->log(auth()->id(), 'logout');
    }
}

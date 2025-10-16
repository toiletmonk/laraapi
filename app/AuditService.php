<?php

namespace App;

use App\Models\AuditLog;

class AuditService
{

    public function log(?int $userId, string $event, array $metadata = []): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId,
            'event' => $event,
            'ip'=>request()->ip(),
            'user_agent'=>request()->userAgent(),
            'metadata'=>$metadata
        ]);
    }
}

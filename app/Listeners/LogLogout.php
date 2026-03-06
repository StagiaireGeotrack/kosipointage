<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function handle(Logout $event): void
    {
        ActivityLogService::log(
            action: 'logout',
            modelType: 'Administration',
            modelId: $event->user?->ID,
            modelLabel: $event->user?->Identifiant_email,
        );
    }
}

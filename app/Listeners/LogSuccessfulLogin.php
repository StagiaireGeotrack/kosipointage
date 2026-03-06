<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        ActivityLogService::log(
            action: 'login_success',
            modelType: 'Administration',
            modelId: $event->user->ID,
            modelLabel: $event->user->Identifiant_email,
        );
    }
}

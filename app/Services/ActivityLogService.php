<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(
        string $action,
        ?string $modelType = null,
        ?int $modelId = null,
        array|string|null $modelLabel = null,
        ?string $description = null,
        ?string $userEmail = null,
    ): void {
        try {
            $user = Auth::user();

            $userId  = $user?->ID ?? null;
            $email   = $userEmail ?? $user?->Identifiant_email ?? 'unknown';
            $siegeId = $user?->SiegeID ?? null;
            $role    = null;

            if ($user) {
                if ($user->isTrueSuperAdmin())  $role = 'superadmin';
                elseif ($user->isSeller())       $role = 'seller';
                elseif ($user->isSimpleAdmin())  $role = 'simple_admin';
            }

            ActivityLog::create([
                'user_id'     => $userId,
                'user_email'  => $email,
                'user_role'   => $role,
                'action'      => $action,
                'model_type'  => $modelType,
                'model_id'    => $modelId,
                'model_label' => $modelLabel,
                'description' => $description,
                'ip_address'  => Request::ip() ?? '0.0.0.0',
                'user_agent'  => substr(Request::userAgent() ?? '', 0, 500),
                'SiegeID'     => $siegeId,
                'created_at'  => now(),
            ]);

        } catch (\Throwable $e) {
            Log::warning('ActivityLog write failed: ' . $e->getMessage());
        }
    }
}

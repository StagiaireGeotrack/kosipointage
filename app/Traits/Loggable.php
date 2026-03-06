<?php

namespace App\Traits;

use App\Services\ActivityLogService;

trait Loggable
{
    public static function bootLoggable(): void
    {
        static::created(function ($model) {
            ActivityLogService::log(
                action: 'create',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });

        static::updated(function ($model) {
            ActivityLogService::log(
                action: 'update',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });

        static::deleted(function ($model) {
            ActivityLogService::log(
                action: 'delete',
                modelType: class_basename($model),
                modelId: (int) $model->getKey(),
                modelLabel: $model->{$model->logLabelField} ?? null,
            );
        });
    }
}

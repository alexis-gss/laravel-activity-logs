<?php

use Illuminate\Support\Facades\Route;
use LaravelActivityLogs\Controllers\ActivityLogController;

Route::prefix(config('laravel-backend.routes.prefix', 'admin'))
    ->name(config('laravel-backend.routes.name', 'back.'))
    ->middleware(['web', 'auth:backend', 'verified'])
    ->group(function (): void {
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
        Route::get('/activity-logs/user/{user:id}', [ActivityLogController::class, 'index'])
            ->name('activity-logs.user');
    });

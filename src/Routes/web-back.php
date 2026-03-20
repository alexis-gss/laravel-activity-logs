<?php

use Illuminate\Support\Facades\Route;
use LaravelActivityLogs\Controllers\ActivityLogController;

Route::prefix('admin')
    ->name('back.')
    ->middleware(['web', 'auth:backend', 'verified'])
    ->group(function () {
        Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
        Route::get('/activity-logs/user/{user:id}', [ActivityLogController::class, 'index'])
            ->name('activity-logs.user');
    });

<?php

namespace LaravelActivityLogs\Traits;

use LaravelActivityLogs\Observers\ModelObserver;
use LaravelActivityLogs\Models\ActivityLog as ActivityLogModel;

/**
 * If there is a static function in a trait (named boot[TraitName]),
 * it will be executed as the boot() function would on an Eloquent model.
 * Futhermore, event in the Model observer doesn't erase actual events in the model,
 * but adds them.
 */
trait ActivityLog
{
    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    public static function bootActivityLog(): void
    {
        static::observe(ModelObserver::class);
    }

    // * RELATIONS

    /**
     * Get Activities of the Model (has-many relationship).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLogModel::class, 'model_id')->with('user');
    }
}

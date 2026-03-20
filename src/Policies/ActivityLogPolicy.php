<?php

namespace LaravelActivityLogs\Policies;

use Illuminate\Database\Eloquent\Model;
use LaravelBackend\Enums\RoleEnum;
use LaravelBackend\Policies\Rules\UserStaticRules;

/**
 * Policy for managing access control on ActivityLog model.
 */
class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any models.
     *
     * @param \Illuminate\Database\Eloquent\Model $authModel
     * @return boolean
     */
    public function viewAny(Model $authModel): bool
    {
        return UserStaticRules::atLeastRole($authModel, RoleEnum::conceptor);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $authModel
     * @return boolean
     */
    public function view(Model $authModel): bool
    {
        return UserStaticRules::atLeastRole($authModel, RoleEnum::conceptor);
    }
}

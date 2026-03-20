<?php

namespace LaravelActivityLogs\Enums;

use LaravelFrontend\Traits\Enums\BaseEnum;

/**
 * Defines the available activity log events.
 */
enum ActivityLogsEventEnum: int
{
    use BaseEnum;

    case created    = 1;
    case updated    = 2;
    case duplicated = 3;
    case deleted    = 4;

    /**
     * Optionnal labels definition.
     *
     * @phpstan-ignore-next-line
     */
    private const LABELS = [
        self::created->name    => 'laravel-backend::crud.actions.create',
        self::updated->name    => 'laravel-backend::crud.actions.edit',
        self::duplicated->name => 'laravel-backend::crud.actions.duplicate',
        self::deleted->name    => 'laravel-backend::crud.actions.delete',
    ];

    /**
     * Custom added classes definition.
     */
    private const TAILWINDCLASSES = [
        self::created->name    => 'successive',
        self::updated->name    => 'preventive',
        self::duplicated->name => 'informative',
        self::deleted->name    => 'destructive',
    ];

    /**
     * Get Class.
     *
     * @return string
     */
    public function tailwindclass(): string
    {
        return self::TAILWINDCLASSES[$this->name];
    }
}

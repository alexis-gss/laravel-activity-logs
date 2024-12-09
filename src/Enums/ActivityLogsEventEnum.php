<?php

namespace LaravelActivityLogs\Enums;

use LaravelActivityLogs\Traits\BaseEnum;

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
        self::created->name    => 'crud.actions.create',
        self::updated->name    => 'crud.actions.edit',
        self::duplicated->name => 'crud.actions.duplicate',
        self::deleted->name    => 'crud.actions.delete',
    ];

    /**
     * Custom added classes definition.
     */
    private const BOOTSTRAPCLASS = [
        self::created->name    => 'success',
        self::updated->name    => 'warning',
        self::duplicated->name => 'secondary',
        self::deleted->name    => 'danger',
    ];

    /**
     * Get Class.
     *
     * @return string
     */
    public function bootstrapClass(): string
    {
        return self::BOOTSTRAPCLASS[$this->name];
    }
}

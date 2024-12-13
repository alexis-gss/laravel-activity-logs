<?php

namespace LaravelActivityLogs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Str;
use LaravelActivityLogs\Enums\ActivityLogsEventEnum;
use LaravelActivityLogs\Factories\ActivityLogFactory;

/**
 * @property string                                           $model_class  Target model.
 * @property array                                            $data         List of changes (old and new values).
 * @property \Illuminate\Database\Eloquent\Model\null         $user_id      Id of the associated user.
 * @property boolean                                          $is_anonymous If there is no user connected when
 * action was realised.
 * @property boolean                                          $is_console   If the action was realised in console.
 * @property integer                                          $model_id     Id of the associated target model.
 * @property \LaravelActivityLogs\Enums\ActivityLogsEventEnum $event        Event of this activity.
 * @property \Illuminate\Support\Carbon                       $created_at   Created date.
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_anonymous',
        'model_class',
        'model_id',
        'event',
        'data',
        'created_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_anonymous' => 'boolean',
        'event'        => ActivityLogsEventEnum::class,
        'data'         => 'json',
        'created_at'   => 'datetime',
    ];

    // * METHODS

    /**
     * Add new activity to the activity logs list.
     *
     * @param \Illuminate\Database\Eloquent\Model              $model
     * @param \LaravelActivityLogs\Enums\ActivityLogsEventEnum $eventEnum
     * @return void
     */
    public static function addActivity(Model $model, ActivityLogsEventEnum $eventEnum): void
    {
        $isAnonymous = true;
        /** @var \Illuminate\Database\Eloquent\Model|null */
        $userModel = auth('backend')->user();
        if (is_object($userModel)) {
            $userModel   = $userModel->getKeyForSelectQuery();
            $isAnonymous = false;
        }
        $activity               = new self();
        $activity->user_id      = $userModel;
        $activity->is_console   = app()->runningInConsole();
        $activity->is_anonymous = $isAnonymous;
        $activity->model_class  = \get_class($model);
        $activity->model_id     = $model->getKey();
        $activity->event        = $eventEnum;
        $activity->data         = static::getChangedColumns($activity, $model, $eventEnum);
        $activity->created_at   = now();
        $activity->saveOrFail();
    }

    /**
     * Get old, new columns that has changed.
     *
     * @param self                                             $activity
     * @param \Illuminate\Database\Eloquent\Model              $model
     * @param \LaravelActivityLogs\Enums\ActivityLogsEventEnum $eventEnum
     * @return array
     */
    public static function getChangedColumns(self $activity, Model $model, ActivityLogsEventEnum $eventEnum): array
    {
        $modelClassName = $activity->model_class;
        /** Get type of each fields of the target model */
        /** @var \Illuminate\Database\Eloquent\Model|null $targetModel */
        $targetModel = $activity->model_class::where(
            (new $modelClassName())->getRouteKeyName(),
            $activity->model_id
        )->first();

        if (\is_null($targetModel)) {
            return [];
        }
        $targetModelTypes = collect($targetModel->toArray())
            ->map(fn($value) => self::getValueType($value))
            ->toArray();
        /** Return old, new and type of values changed */
        return match ($eventEnum) {
            ActivityLogsEventEnum::created    => array_intersect_key($targetModelTypes, $model->toArray()),
            ActivityLogsEventEnum::duplicated => array_intersect_key($targetModelTypes, $model->toArray()),
            ActivityLogsEventEnum::updated    => array_intersect_key($targetModelTypes, $model->getChanges()),
            default => []
        };
    }

    /**
     * Get a value type as a string
     * ! Use this for display purpose only.
     *
     * @param mixed $value
     * @return string
     * @throws \RuntimeException If type is unhlanded.
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity.MaxExceeded
     */
    private static function getValueType(mixed $value): string
    {
        // phpcs:enable
        $type = \gettype($value);
        switch ($type) {
            case 'string':
                return match (true) {
                    Str::length(\strip_tags($value)) !== Str::length($value)           => 'html',
                    Str::startsWith($value, 'storage/modelfiles')                      => 'file',
                    Str::isUuid($value)                                                => 'uuid',
                    Str::isUlid($value)                                                => 'ulid',
                    Str::isUrl($value)                                                 => 'url',
                    \is_numeric($value)                                                => 'numeric',
                    Str::isJson($value) and (Str::startsWith($value, '[') or Str::startsWith($value, '{')) => 'json',
                    default => 'string'
                };
            case 'NULL':
            case 'object':
            case 'array':
            case 'integer':
            case 'boolean':
                return $type;
            case 'double':
                return is_float($value) ? 'float' : 'double';
            case 'resource (closed)':
            case 'resource':
                throw new \RuntimeException('Laravel model properties should not contain resources !');
            case 'unknown type':
            default:
                throw new \RuntimeException('Unhandled Type ' . \gettype($value));
        } //end switch
    }

    /**
     * Set the factory of the model.
     *
     * @return \LaravelActivityLogs\Factories\ActivityLogFactory
     */
    protected static function newFactory(): \LaravelActivityLogs\Factories\ActivityLogFactory
    {
        return new ActivityLogFactory();
    }

    /**
     * Get model that owns the Activity log (belongs-to relationship).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

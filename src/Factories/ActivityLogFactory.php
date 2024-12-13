<?php

namespace LaravelActivityLogs\Factories;

use LaravelActivityLogs\Enums\ActivityLogsEventEnum;
use LaravelActivityLogs\Models\ActivityLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\LaravelActivityLogs\Models\ActivityLog>
 */
final class ActivityLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\LaravelActivityLogs\Models\ActivityLog>
     */
    protected $model = ActivityLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $boolean = rand(0, 1);
        return [
            'user_id'      => null,
            'is_anonymous' => $boolean,
            'is_console'   => !$boolean,
            'model_class'  => "\App\Models\User",
            'model_id'     => 1,
            'event'        => \collect(ActivityLogsEventEnum::toArray())->random()->value,
            'data'         => [],
            'created_at'   => now(),
        ];
    }
}

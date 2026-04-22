<?php

namespace LaravelActivityLogs\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelActivityLogs\Enums\ActivityLogsEventEnum;
use LaravelActivityLogs\Models\ActivityLog;

/**
 * Factory for generating ActivityLog model instances with randomized data.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\LaravelActivityLogs\Models\ActivityLog>
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
            'user_id'       => null,
            'is_anonymous'  => $boolean,
            'is_console'    => !$boolean,
            'model_class'   => "\App\Models\User",
            'model_id'      => 1,
            'event'         => \collect(ActivityLogsEventEnum::cases())->random()->value,
            'modifications' => [],
            'created_at'    => now(),
        ];
    }
}

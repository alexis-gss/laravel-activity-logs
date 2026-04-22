<?php

namespace LaravelActivityLogs\Controllers;

use Illuminate\Http\Request;
use LaravelActivityLogs\Enums\ActivityLogsEventEnum;
use LaravelActivityLogs\Models\ActivityLog;
use LaravelBackend\Controllers\BackendController;
use LaravelBackend\Resources\CommonResource;

/**
 * Handles listing and display of activity logs in the backend.
 */
class ActivityLogController extends BackendController
{
    /**
     * Create the controller instance.
     */
    public function __construct()
    {
        $this->authorizeResource(ActivityLog::class);
        $this->modelName = ActivityLog::modelName();
        $this->modelPage = ActivityLog::modelPage();
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request): \Inertia\Response
    {
        /** @var \Illuminate\Database\Eloquent\Builder<\LaravelActivityLogs\Models\ActivityLog> $query */
        $query = ActivityLog::query()->with("user");

        /** @var string|null $search Search field */
        $search = $request->input('search');
        if ($search) {
            $this->searchQuery(
                $query,
                $search,
                null,
                'modifications',
                'event',
                'model_class',
            );
        }
        /** @var string[] $searchFields */
        $searchFields = [
            trans('laravel-activity-logs::trans.attributes.modifications'),
            trans('laravel-activity-logs::trans.attributes.event'),
            trans('laravel-activity-logs::trans.attributes.model_class'),
        ];

        /** Sort columns with a query */
        $this->sortQuery($query);

        /** Custom pagination */
        $activitylogModels = $this->paginate($query);

        return $this->inertiaRender('back/Pages/ActivityLogs/Index', [
            'activitylogModels'     => CommonResource::collection($activitylogModels),
            'search'                => $search,
            'searchFields'          => implode(', ', $searchFields),
            'activityLogsEventEnum' => ActivityLogsEventEnum::toArray()
        ]);
    }

    /**
     * Show a specific resource.
     *
     * @param \LaravelActivityLogs\Models\ActivityLog $activity_log
     * @return \Inertia\Response
     */
    public function show(ActivityLog $activity_log): \Inertia\Response
    {
        return $this->inertiaRender('back/Pages/ActivityLogs/Show', [
            'data'                  => CommonResource::make($activity_log->load("user")),
            'activityLogsEventEnum' => ActivityLogsEventEnum::toArray()
        ]);
    }
}

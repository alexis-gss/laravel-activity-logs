<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator;
use Illuminate\Support\Str;

/** @var string $routeName */
$routeName = config('laravel-backend.routes.name');

Breadcrumbs::for("{$routeName}activity-logs.index", function (Generator $trail) use ($routeName): void {
    $trail->parent("{$routeName}dashboard.index");
    $trail->push(
        Str::of(trans_choice('laravel-activity-logs::models.classes.activity-logs', 2))->ucfirst(),
        route("{$routeName}activity-logs.index")
    );
});
Breadcrumbs::for("{$routeName}activity-logs.show", function (Generator $trail) use ($routeName): void {
    $trail->parent("{$routeName}activity-logs.index");
    $trail->push(Str::of(trans('laravel-backend::crud.actions.visualize'))->ucfirst());
});

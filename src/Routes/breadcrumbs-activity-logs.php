<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator;
use Illuminate\Support\Str;

// * ACTIVITY LOGS.
Breadcrumbs::for('back.activity-logs.index', function (Generator $trail) {
    $trail->parent('back.dashboard.index');
    $trail->push(
        Str::of(trans_choice('laravel-activity-logs::models.classes.activity-logs', 2))->ucfirst(),
        route('back.activity-logs.index')
    );
});
Breadcrumbs::for('back.activity-logs.show', function (Generator $trail) {
    $trail->parent('back.activity-logs.index');
    $trail->push(Str::ucfirst(trans('laravel-backend::crud.actions.visualize')));
});

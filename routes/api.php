<?php

use Illuminate\Support\Facades\Route;

foreach (glob(__DIR__ . "/api/*-api.php") as $file) {
    $prefix = ucfirst(Str::camel(preg_replace('/\-api/', '', pathinfo($file, PATHINFO_FILENAME))));
    Route::group([
        'prefix'     => $prefix,
        'as'         => "$prefix.",
        'namespace'  => $prefix,
        'middleware' => ["auth:api"],
    ], function () use ($file) {
        // "{FILE_NAME}/*"
        require_once($file);
    });
}

require_once __DIR__ . '/auth-api.php';
require_once __DIR__ . '/panel-api.php';
require_once __DIR__ . '/resources-api.php';

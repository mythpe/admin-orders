<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as'     => "Resources.",
    'prefix' => "Resources",
], function () {
    Route::get('Role', [RoleController::class, 'allIndex'])->name('roles');
    Route::get('Permission', [PermissionController::class, 'allIndex'])->name('permissions');
});

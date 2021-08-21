<?php

use App\Http\Controllers\User\SideMenuController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "User",
], function () {
    Route::get('side-menu', [SideMenuController::class, 'sideMenu'])->name('sideMenu');
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('profile', [UserController::class, 'updateProfile'])->name('updateProfile');
});

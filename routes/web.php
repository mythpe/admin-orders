<?php

use App\Http\Controllers\DebugController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::any('/{any}', [HomeController::class, 'index'])->where('any', '^(?!api).*$')->name('home');

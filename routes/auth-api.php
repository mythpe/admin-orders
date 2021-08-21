<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => '\App\Http\Controllers',
    'as'        => "Auth.",
], function (Router $router) {
    $router->post('login', 'LoginController@login')->name('login');
    $router->group(['middleware' => 'auth:api',], function (Router $router) {
        $router->get('logout', 'LoginController@logout')->name('logout');
        $router->get('check-token', 'LoginController@checkToken')
            ->name('checkToken');
    });
});

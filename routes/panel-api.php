<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

$prefix = "Panel";
Route::group([
    'prefix'     => $prefix,
    'as'         => "$prefix.",
    'middleware' => ['auth:api'],
], function () {

    Route::apiResource('Role', 'RoleController');
    Route::apiResource('User', 'User\\UserController');
    Route::apiResource('Permission', 'PermissionController');
    Route::get('Setting', [SettingController::class, 'get']);
    Route::post('Setting', [SettingController::class, 'save']);

    Route::group(['prefix' => 'Order', 'as' => "Order",], function () {
        Route::get('getOrders', [OrderController::class, 'getOrders'])->name('getOrders');
        Route::post('makeOrder', [OrderController::class, 'makeOrder'])->name('makeOrder');
    });
    //    Route::apiResource('JobPosition', 'JobPositionController');
    //
    //    Route::apiResource('FirstBatch', 'FirstBatchController');
    //
    //    Route::group(['namespace' => 'Rules'], function(){
    //        Route::apiResource('PropertyStatusRule', 'PropertyStatusRuleController');
    //        Route::apiResource('CalculatorRule', 'CalculatorRuleController');
    //    });
    //
    //    Route::group(['namespace' => 'ProductType'], function(){
    //        Route::apiResource('ProductType', 'ProductTypeController');
    //        Route::apiResource('ProductTypeCheckTotal', 'ProductTypeCheckTotalController');
    //    });
    //
    //    Route::group(['namespace' => 'BankTools'], function(){
    //        Route::apiResource('ProfitPercentage', 'ProfitPercentageController');
    //        Route::apiResource('SupportInstallment', 'SupportInstallmentController');
    //    });
    //
    //    Route::group(['namespace' => 'BankJobPosition'], function(){
    //        Route::apiResource('AvailableExtend', 'AvailableExtendController');
    //        Route::apiResource('ExtraFundingYear', 'ExtraFundingYearController');
    //        Route::apiResource('SalaryDeduction', 'SalaryDeductionController');
    //        Route::apiResource('SalaryEquation', 'SalaryEquationController');
    //        Route::apiResource('WithoutTransfer', 'WithoutTransferController');
    //    });
});

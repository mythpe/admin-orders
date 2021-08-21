<?php

namespace App\Providers;

use App\Models\Bank;
use App\Models\BankJobPosition\AvailableExtend;
use App\Models\BankJobPosition\ExtraFundingYear;
use App\Models\BankJobPosition\SalaryDeduction;
use App\Models\BankJobPosition\SalaryEquation;
use App\Models\BankJobPosition\WithoutTransfer;
use App\Models\BankTools\ProfitPercentage;
use App\Models\BankTools\SupportInstallment;
use App\Models\FirstBatch;
use App\Models\JobPosition;
use App\Models\Permission;
use App\Models\ProductType\ProductType;
use App\Models\ProductType\ProductTypeCheckTotal;
use App\Models\Role;
use App\Models\Rules\CalculatorRule;
use App\Models\Rules\PropertyStatusRule;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function(){
            Route::prefix('api')
                 ->middleware('api')
                 ->namespace('App\Http\Controllers')
                 ->group(base_path('routes/api.php'));

            Route::middleware('web')
                 ->namespace($this->namespace)
                 ->group(base_path('routes/web.php'));
        });

        $this->routeModels();
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function(Request $request){
            return Limit::perMinute(60);
        });
    }

    /**
     * Bind route models
     */
    protected function routeModels()
    {
        Route::model('User', User::class);
        Route::model('Role', Role::class);
        Route::model('Permission', Permission::class);
    }
}

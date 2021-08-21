<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View as ViewComposer;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as View;

/**
 * Class AppServiceProvider
 *
 * @package App\Providers
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->viewComposer();
        ini_set('max_execution_time', 0);
        // require_once app_path('helpers.php');
    }

    /**
     *
     */
    protected function viewComposer(): void
    {
        ViewComposer::composer("*", function (View $view) {
            $locale = $this->app->getLocale();
            $name = config('app.name');
            $META_AUTHOR = '';
            $META_KEYWORDS = '';
            $META_DESCRIPTION = '';
            $view->with([
                "APP_NAME" => $name,
                "APP_URL"  => config('app.url', '/'),

                "ALIGN"      => $locale === "ar" ? "right" : "left",
                "ALIGN2"     => $locale === "ar" ? "left" : "right",
                "DIRECTION"  => $locale === "ar" ? "rtl" : "ltr",
                "DIRECTION2" => $locale === "ar" ? "ltr" : "rtl",
                "LOCALE"     => $locale,

                "META_AUTHOR"      => $META_AUTHOR,
                "META_KEYWORDS"    => $META_KEYWORDS,
                "META_DESCRIPTION" => $META_DESCRIPTION,
            ]);
        });
    }

    /**
     *
     */
    protected function bootMacros(): void
    {
        Collection::macro('find', function ($id, $column = 'id') {
            /** @var Collection $items */
            $items = $this;
            return $items->filter(function ($item) use ($id, $column) {
                if ($item instanceof Collection) {
                    return $item->get($column) == $id;
                }
                if ($item instanceof Model) {
                    return $item->{$column} == $id;
                }
                if (is_array($item)) {
                    return array_key_exists($column, $item) ? $item[$column] == $id : false;
                }
                if (is_object($item)) {
                    return isset($item->{$column}) ? $item->{$column} : false;
                }
                return $item == $id;
            })->first();
        });
    }

    /**
     * Set application locale from client
     *
     * @return void
     */
    protected function setAppLocale(): void
    {
        $locale = null;
        $locales = config('app.locales');
        $key = "app-locale";

        if (session()->has($key)) {
            $locale = session($key);
        }

        if (request()->header($key)) {
            $locale = request()->header($key);
        }

        if (($user = request()->user()) && $user->locale) {
            $locale = $user->locale;
        }

        if ($locale && in_array($locale, $locales)) {
            app()->setLocale($locale);
            request()->setLocale($locale);
        }
    }
}

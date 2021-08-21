<?php

namespace App\Providers;

use Carbon\Carbon;
use GeniusTS\HijriDate\Hijri;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

/**
 * Class ValidatorServiceProvider
 *
 * @package App\Providers
 */
class ValidatorServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mobileRule();
        $this->hijriDateRule();
        $this->minHijriAgeRule();
        $this->maxHijriAgeRule();
    }

    /**
     * KSA mobile
     *
     * @return void
     */
    protected function mobileRule()
    {
        Validator::extend("mobile", function ($attribute, $value, $parameters, $validator) {
            $value = trim($value, ' +.');
            return Str::startsWith($value, "05") && strlen($value)==10;
        });
    }

    /**
     * hijri date
     *
     * @return void
     */
    protected function hijriDateRule()
    {
        Validator::extend("hijri_date", function ($attribute, $value, $parameters, $validator) {
            $value = str_ireplace('/', '-', $value ?:'');
            $ex = explode('-', $value);
            if (count($ex)!=3) {
                return false;
            }

            $year = strlen($ex[0])==4 ? $ex[0]:(strlen($ex[2])==4 ? $ex[2]:0);
            $month = $ex[1];
            $day = strlen($ex[0])==4 ? $ex[2]:(strlen($ex[2])==4 ? $ex[0]:0);

            if (!$year || !$month || !$day) {
                return false;
            }

            if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day)) {
                return false;
            }
            return true;
        });
    }

    /**
     * min age by hijri date
     *
     * @return void
     */
    protected function minHijriAgeRule()
    {
        $ruleName = 'min_hijri_age';
        Validator::extend($ruleName, function ($attribute, $value, $parameters, $validator) {
            $age = 0;
            if (($ex = explode('-', $value))) {
                if (count($ex)!=3) {
                    return false;
                }

                $year = strlen($ex[0])==4 ? $ex[0]:(strlen($ex[2])==4 ? $ex[2]:0);
                $month = $ex[1];
                $day = strlen($ex[0])==4 ? $ex[2]:(strlen($ex[2])==4 ? $ex[0]:0);

                if (!$year || !$month || !$day) {
                    return false;
                }

                (($h = Hijri::convertToHijri(Carbon::now()))) && ($age = $h->year - $year);

                foreach ($parameters as $parameter) {
                    if ($age < $parameter) {
                        return false;
                    }
                }
                return true;
                // d($year, $h->year, $parameters, $age);
            }
            return false;
        });
        Validator::replacer($ruleName, function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(__("global.or"), $parameters), $message);
        });
    }

    /**
     * max age by hijri date
     *
     * @return void
     */
    protected function maxHijriAgeRule()
    {
        $ruleName = 'max_hijri_age';
        Validator::extend($ruleName, function ($attribute, $value, $parameters, $validator) {
            $age = 0;
            if (($ex = explode('-', $value))) {
                if (count($ex)!=3) {
                    return false;
                }

                $year = strlen($ex[0])==4 ? $ex[0]:(strlen($ex[2])==4 ? $ex[2]:0);
                $month = $ex[1];
                $day = strlen($ex[0])==4 ? $ex[2]:(strlen($ex[2])==4 ? $ex[0]:0);

                if (!$year || !$month || !$day) {
                    return false;
                }

                (($h = Hijri::convertToHijri(Carbon::now()))) && ($age = $h->year - $year);

                foreach ($parameters as $parameter) {
                    if ($age > $parameter) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        });
        Validator::replacer($ruleName, function ($message, $attribute, $rule, $parameters) {
            return str_replace(':values', implode(__("global.or"), $parameters), $message);
        });
    }
}

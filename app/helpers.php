<?php

use GeniusTS\HijriDate\Date as HijriDate;
use GeniusTS\HijriDate\Hijri;
use Illuminate\Support\Carbon;
use Symfony\Component\VarDumper\VarDumper;

if (!function_exists('s')) {
    /**
     * @param mixed ...$vars
     */
    function s(...$vars)
    {
        $debug = @debug_backtrace();
        foreach ($debug as $call) {
            $line = (isset($call['line']) ? $call['line'] : __LINE__);
            $file = (isset($call['file']) ? $call['file'] : __FILE__);
            $skip = [
                'vendor\\',
                'vendor/',
                'public\index.php',
                'public/index.php',
                'server.php',
            ];

            if (\Illuminate\Support\Str::contains($file, $skip)) continue;
            echo("[{$file}] Line ({$line}): <br>");
        }

        // $call = current($debug);
        // $line = (isset($call['line']) ? $call['line'] : __LINE__);
        // $file = (isset($call['file']) ? $call['file'] : __FILE__);
        // echo("[{$file}] Line ({$line}): <br>");
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }

        die(1);
    }
}

if (!function_exists('manifest_directory')) {
    function manifest_directory($path = null)
    {
        $directory = rtrim(config('app.manifest_directory'), '/');
        if (!is_null($path)) {
            $directory .= '/' . ltrim($path, '/');
        }
        return $directory;
    }
}

if (!function_exists('trans_has')) {
    /**
     * Determine if a translation exists.
     *
     * @param string $key
     * @param string|null $locale
     * @param bool $fallback
     *
     * @return bool
     */
    function trans_has($key, $locale = null, $fallback = true)
    {
        return app('translator')->has($key, $locale, $fallback);
    }
}

if (!function_exists('hijri')) {
    /**
     * helper convert to hijri
     *
     * @param string $date
     *
     * @return HijriDate
     */
    function hijri($date = '')
    {
        if ($date instanceof HijriDate)
            return $date;
        if (!$date instanceof Carbon) {
            $temp = Carbon::make($date);

            # Hijri
            if ($temp->year < 1990) {
                $ex = explode("-", $date);
                count($ex) < 3 && ($ex = explode("/", $date));

                $year = $temp->year;
                $month = isset($ex[1]) && strlen($ex[1]) == 2 ? $ex[1] : 1;
                $day = strpos("$date", "$year") === 0 && isset($ex[2]) ? $ex[2] : 1;

                $date = Hijri::convertToGregorian($day, $month, $year);
            }
            else {
                $date = $temp;
            }
        }

        return Hijri::convertToHijri($date);
    }
}

if (!function_exists('arabic_date')) {
    /**
     * @param $string
     *
     * @return string
     */
    function arabic_date($string)
    {
        $ar = [
            '/',
            '٠',
            '١',
            '٢',
            '٣',
            '٤',
            '٥',
            '٦',
            '٧',
            '٨',
            '٩',
        ];

        $notAr = [
            '-',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
        ];

        $val = str_ireplace($notAr, $ar, $string);
        // $val = date_by_locale($val, 'ar');
        //d($val,$string);
        return "{$val} هـ";
    }
}

if (!function_exists('beforeLast')) {
    /**
     * @param $attribute
     * @param string $search
     */
    function beforeLast($attribute, $search = '_id')
    {
        Str::beforeLast($attribute, $search);
    }
}

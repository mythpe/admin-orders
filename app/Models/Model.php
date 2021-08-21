<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

/**
 * Class Model
 *
 * @mixin Builder
 * @package App\Models
 */
class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory, Notifiable;

    /**
     * @return string
     */
    public static function getModelTable()
    {
        return (new static)->getTable();
    }

    /**
     * @return array
     */
    public static function getModelFillable()
    {
        return (new static)->getFillable();
    }

    /**
     * @param $value
     *
     * @return string
     */
    public function getNameAttribute($value): string
    {
        return (string) ($value ? $value : ($this->{locale_attribute()}));
    }

    /**
     * @param $key
     *
     * @return mixed|string|null|void
     */
    public function __get($key)
    {
        if (!$key) {
            return;
        }

        // If the attribute exists in the attribute array or has a "get" mutator we will
        // get the attribute's value. Otherwise, we will proceed as if the developers
        // are asking for a relationship's value. This covers both types of values.
        if (array_key_exists($key, $this->attributes) ||
            array_key_exists($key, $this->casts) ||
            $this->hasGetMutator($key) ||
            $this->isClassCastable($key)) {
            return $this->getAttributeValue($key);
        }

        /** get_{ATTRIBUTE}_from_{RELATION}_class */
        if (substr($key, 0, strlen(($get = "get_"))) == $get && substr($key, -strlen(($trait = "_class"))) == $trait) {
            $call = substr($key, strlen($get), (strlen($key) - strlen($get)) - strlen($trait));
            $callArray = explode("_from_", $call);
            krsort($callArray);
            $method = $this;
            $i = 0;
            foreach ($callArray as $item) {
                $i++;
                try {
                    $method = $method->{$item};
                }
                catch (\Exception $exception) {
                    $method = '';
                }

                if ($i == count($callArray)) {
                    return ($method instanceof $this ? "" : (is_null($method) ? "" : $method));
                }
            }
        }

        /** get_{RELATION}_name */
        if (Str::startsWith($key, ($f = "get_")) && Str::endsWith($key, ($l = "_name"))) {
            $method = Str::before($key, $l);
            $method = Str::after($method, $f);
            return (($method && ($a = $this->{$method})) ? $a->{$a->getNameColumn()} : '');
        }

        /** {ATTRIBUTE}_code */
        if (Str::endsWith($key, ($t = "_code")) && !$this->isFillable($key)) {
            $method = $this->modelHasMethod(Str::before($key, $t));

            if (!is_null(($a = $this->{$method}))) {
                return $a->code;
            }
        }

        /** {ATTRIBUTE}_id_to_string */
        if (Str::endsWith($key, ($t = "_id_to_string"))) {
            $method = Str::before($key, $t);

            if (!is_null(($a = $this->{$method}))) {
                return $a->{$a->getNameColumn()};
            }

            if (!is_null(($a = $this->{Str::camel($method)}))) {
                return $a->{$a->getNameColumn()};
            }

            return !is_null(($a = $this->{$method})) ? $a->{$a->getNameColumn()} : $a;
        }

        /** {ATTRIBUTE}_to_number_format */
        if (Str::endsWith($key, ($t = "_to_number_format"))) {
            $value = Str::before($key, $t);
            return !is_null(($_name = $this->{$value})) ? to_number_format((float) $_name, 2, __("global.sar")) : $_name;
        }

        /** {ATTRIBUTE}_to_en_yes */
        if (Str::endsWith($key, ($t = "_to_en_yes")) && !$this->isFillable($key)) {
            $method = Str::before($key, $t);
            return !is_null(($_name = $this->{$method})) ? ($_name ? "yes" : "no") : $_name;
        }

        /** {ATTRIBUTE}_to_yes */
        if (Str::endsWith($key, ($t = "_to_yes")) && !$this->isFillable($key)) {
            $method = Str::before($key, $t);
            return !is_null(($_name = $this->{$method})) ? __("global." . ($_name ? "yes" : "no")) : $_name;
        }

        /** {DATE_ATTRIBUTE}_to_date_format */
        if (Str::endsWith($key, ($t = "_to_date_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return $date->format(config('config.date_format.date'));
            }
            //!(($date = $this->{$method}) instanceof Carbon) && ($date = Carbon::parse($date));
            //return $date->format(config('config.date_format.date'));
        }

        /** {DATE_ATTRIBUTE}_to_time_format */
        if (Str::endsWith($key, ($t = "_to_time_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return $date->format(config('config.date_format.time'));
            }
            //!(($date = $this->{$method}) instanceof Carbon) && ($date = Carbon::parse($date));
            //return $date->format(config('config.date_format.date'));
        }

        /** {DATE_ATTRIBUTE}_to_time_string_format */
        if (Str::endsWith($key, ($t = "_to_time_string_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.time_string')));
            }
            //!(($date = $this->{$method}) instanceof Carbon) && ($date = Carbon::parse($date));
            //return $date->format(config('config.date_format.date'));
        }

        /** {DATE_ATTRIBUTE}_to_datetime_format */
        if (Str::endsWith($key, ($t = "_to_datetime_format")) && ($attribute = Str::before($key, $t))) {
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.datetime')));
            }
            //!(($date = $this->{$method}) instanceof Carbon) && ($date = Carbon::parse($date));
            //return $date->format(config('config.date_format.date'));
        }

        /** {DATE_ATTRIBUTE}_to_day_format */
        if (Str::endsWith($key, ($t = "_to_day_format"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return date_by_locale($date->format(config('config.date_format.day')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_hijri */
        if (Str::endsWith($key, ($t = "_to_hijri"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));
                return hijri($date);
            }
        }

        /** {DATE_ATTRIBUTE}_to_full_arabic_date */
        if (Str::endsWith($key, ($t = "_to_full_arabic_date"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                // dd($attribute,$date,hijri($date)->format( app_date_format('date') ) );

                return arabic_date(hijri($date)->format(config('config.date_format.hijri_human')));
            }
        }

        /** {DATE_ATTRIBUTE}_to_arabic_date */
        if (Str::endsWith($key, ($t = "_to_arabic_date"))) {
            $attribute = substr($key, 0, strlen($key) - strlen($t));
            if ($this->isDateAttribute($attribute) && ($date = $this->{$attribute})) {
                !$date instanceof Carbon && ($date = Carbon::parse($date));

                return arabic_date(hijri($date)->format(config('config.date_format.date')));
            }
        }

        /** Original */
        return parent::__get($key);
    }

    protected function modelHasMethod($method): ?string
    {
        $methods = $this->strCasesArray($method);

        foreach ($methods as $m)
            if (method_exists($this, $m))
                return $m;
        return null;
    }

    /**
     * helper
     *
     * @param $relation
     *
     * @return array
     */
    protected function strCasesArray($str): array
    {
        return collect([
            $str,
            Str::snake($str),
            Str::camel($str),
        ])->uniqueStrict()->toArray();
    }

    /**
     * name of attribute will display tne model name Like created_at
     *
     * @return string
     */
    public function getNameColumn(): string
    {
        if ($this->isFillable(locale_attribute()))
            return locale_attribute();
        return 'name';
    }
}

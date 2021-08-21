<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasActiveTrait
 *
 * @package App\Traits
 */
trait HasActiveTrait
{

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActiveOnly(Builder $builder): Builder
    {
        return $builder->where('active', !0);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnactiveOnly(Builder $builder): Builder
    {
        return $builder->where('active', !1);
    }
}

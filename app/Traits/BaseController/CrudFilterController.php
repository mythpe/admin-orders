<?php

namespace App\Traits\BaseController;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Trait CrudFilterController
 *
 * @package App\Traits\BaseController
 */
trait CrudFilterController
{

    /**
     * @var string
     */
    public $filterRequestKey = 'filter';

    /**
     * user_id => "scope"
     * user_id => ["where",'LIKE','name','and']
     * start_date => ["whereDate",'>=','created_at','and']
     *
     * @var array
     */
    public $mapFilterColumns = [];

    /**
     * @var string
     */
    protected $filterTable = '';

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\App\Models\Model|\Illuminate\Database\Eloquent\Model $builder
     * @param null $filters
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filerQuery($builder, $filters = null)
    {
        $filters = is_null($filters) ? $this->request->get($this->filterRequestKey) : $filters;
        if ($filters && is_array($filters)) {
            $model = $builder->getModel();
            $this->filterTable = $model->getTable();
            // d($filters);
            foreach ($filters as $column => $value) {
                if (is_null($value)) continue;
                if ($this->isMapFilterColumns($column)) {
                    $map = $this->getMapFilterColumns($column);
                    if (is_string($map)) {
                        $builder = $builder->{$map}($value);
                    }
                    else {
                        $method = ($map[0] ?? 'where');
                        $operator = ($map[1] ?? '=');
                        $column = ($map[2] ?? $column);
                        $boolean = ($map[3] ?? 'and');
                        $value = strtolower($operator) == 'like' ? "%{$value}%" : $value;
                        $builder = $builder->{$method}($column, $operator, $value, $boolean);
                    }
                }
                else {
                    $builder = $this->setFilterQuery($builder, $column, $value);
                }
            }
        }
        return $builder;
    }

    /**
     * @param $column
     *
     * @return bool
     */
    protected function isMapFilterColumns($column)
    {
        return array_key_exists($column, $this->getMapFilterColumns());
    }

    /**
     * @param null $column
     *
     * @return array|mixed|null
     */
    protected function getMapFilterColumns($column = null)
    {
        return is_null($column) ? $this->mapFilterColumns : (($this->mapFilterColumns[$column]) ?? null);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param $column
     * @param $value
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setFilterQuery($builder, $column, $value)
    {
        if (Schema::hasColumn($this->filterTable, $column)) {
            if (is_array($value)) {
                $builder->whereIn($column, $value);
            }
            else {
                $builder->where($column, '=', $value);
            }
        }
        else {
            // $column = "status_select";
            $method = "WhereHas" . ucfirst(Str::plural(Str::camel(Str::beforeLast(Str::snake($column), '_id'))));
            if (method_exists($builder->getModel(), "scope{$method}")) {
                // d(method_exists($model, $method), $method, $column, $value);

                $builder->{$method}($value);
            }
        }
        return $builder;
    }
}

<?php

namespace App\Traits\BaseController;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Trait PaginateController
 *
 * @package App\Traits\BaseController
 */
trait CrudSearchController
{
    /**
     * @var bool
     */
    public $customSearchColumns = false;

    /**
     * These data will be searched
     *
     * @var array
     */
    public $searchColumns = [];

    /**
     * will map to define relations
     * user_id => ['user']
     * user_id => ['relation' => 'user', 'method' => 'whereHas', 'column' => 'name', 'operator' => 'LIKE', 'value' => '%{v}%']
     *
     * @var array
     */
    public $mapSearchQueryColumns = [];

    /**
     * @var string
     */
    public $searchRequestKey = 'search';

    /**
     * @var string
     */
    public $headersRequestKey = 'headerItems';

    /**
     * @var string
     */
    protected $searchTable = '';

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\App\Models\Model $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder|\App\Models\Model
     */
    protected function searchQuery($builder)
    {
        $words = $this->request->get($this->searchRequestKey);

        if (!$words) return $builder;
        $model = $builder->getModel();
        $this->searchTable = $model->getTable();
        if (!$this->customSearchColumns) {
            if (($headers = $this->request->get($this->headersRequestKey)) && is_array($headers) && !empty($headers)) {
                foreach ($headers as $header) {
                    if (array_key_exists('value', $header)) {
                        $column = $header['value'];
                        foreach (['_to_string', 'ToString', '_to_yes'] as $c) {
                            if (Str::endsWith($column, $c)) {
                                $column = Str::beforeLast($column, $c);
                                break;
                            }
                        }
                        if (Schema::hasColumn($this->searchTable, $column)) {
                            $this->mergeSearchColumns($column);
                        }
                    }
                }
            }
            else {
                $this->mergeSearchColumns($model->getFillable());
            }
        }
        $builder->where(function (Builder $builder) use ($words, $model) {
            foreach ($this->getSearchColumns() as $k => $column) {
                // d($this->getSearchColumns());
                /** Default no custom */
                if (is_numeric($k)) {
                    // if (Str::endsWith($column, '_id')) {
                    // }
                    if (Schema::hasColumn($this->searchTable, $column)) {
                        if ($this->isMapQueryColumn($column)) {
                            $map = $this->getMapSearchQueryColumns($column);
                            $relation = ($map['relation'] ?? ($map[0] ?? beforeLast($column)));
                            $method = ($map['method'] ?? 'orWhereHas');
                            $operator = ($map['operator'] ?? 'LIKE');
                            $value = str_ireplace('{v}', $words, ($map['value'] ?? '%{v}%'));
                            $column = ($map['column'] ?? 'name');
                            // d($relation,$method,$operator,$value,$column);
                            $builder->{$method}($relation, function (Builder $builder) use ($column, $operator, $value, $words) {
                                return $builder->where($column, $operator, $value);
                            });
                        }
                        else {
                            if (Str::endsWith($column, '_id') && !is_numeric($words)) {
                                $relations = [
                                    Str::snake(Str::beforeLast($column, '_id')),
                                    Str::camel(Str::beforeLast($column, '_id')),
                                ];
                                // d($relations);
                                // d($words);
                                foreach ($relations as $relation) {
                                    if (
                                        method_exists($model, $relation)
                                        && ($relationModel = $model->$relation()->getModel())
                                        && Schema::hasColumn($relationModel->getTable(), ($c = $relationModel->getNameColumn()))
                                    ) {
                                        // d($words,$relation,$c);
                                        $builder->orWhere(function (Builder $builder) use ($relation, $c, $words) {
                                            $builder->whereHas($relation, function (Builder $builder) use ($c, $words) {
                                                $builder->where($c, 'LIKE', "%{$words}%");
                                            });
                                        });
                                    }
                                }
                            }
                            else {
                                $builder->orWhere($column, 'LIKE', "%{$words}%");
                            }
                        }
                    }
                }
                else $builder->{$column}($words);
            }
            // d($builder->toSql());
            return $builder;
        });
        // d($builder->toSql(), $words);

        return $builder;
    }

    /**
     * @param string|array $columns
     *
     * @return self
     */
    protected function mergeSearchColumns($columns): self
    {
        !is_array($columns) && ($columns = explode(',', $columns));
        $this->searchColumns = array_merge($this->searchColumns, (array) $columns);
        return $this;
    }

    /**
     * @return array
     */
    protected function getSearchColumns(): array
    {
        return $this->searchColumns;
    }

    /**
     * @param $column
     *
     * @return bool
     */
    protected function isMapQueryColumn($column): bool
    {
        return array_key_exists($column, $this->mapSearchQueryColumns);
    }

    /**
     * @param null $column
     *
     * @return array
     */
    protected function getMapSearchQueryColumns($column = null): array
    {
        return is_null($column) ? $this->mapSearchQueryColumns : ($this->mapSearchQueryColumns[$column] ?? []);
    }
}

<?php

namespace App\Traits\BaseController;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Trait CrudSortController
 *
 * @package App\Traits\BaseController
 */
trait CrudSortController
{

    /**
     * @var string
     */
    public $sortByRequestKey = 'sortBy';

    /**
     * @var string
     */
    public $sortDescRequestKey = 'sortDesc';

    /**
     * @var array
     */
    public $mapSortColumns = [];

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|mixed $query
     *
     * @return mixed
     */
    protected function sortQuery($query)
    {
        /** @var array|string $sortBy */
        $sortBy = $this->request->get($this->sortByRequestKey);
        if ($sortBy && !is_array($sortBy)) $sortBy = ($a = json_decode($sortBy, true)) ? $a : [];

        /** @var array|string $sortDesc */
        $sortDesc = $this->request->get($this->sortDescRequestKey);
        if ($sortDesc && !is_array($sortDesc)) $sortDesc = ($a = json_decode($sortDesc, true)) ? $a : [];

        $table = $query->getModel()->getTable();
        if (
            is_array($sortBy)
            && !empty($sortBy)
            && is_array($sortDesc)
            && !empty($sortDesc)
        ) {
            $emptyBaseOrder = false;
            foreach ($sortBy as $k => $column) {
                $value = $sortDesc[$k] ?? false;
                $direction = ((trim(strtolower($value)) === 'true' || $value === true) ? 'desc' : 'asc');
                $column = $this->getMapSortColumns($column);
                $hasColumn = Schema::hasColumn($table, $column)
                    || (
                        Str::endsWith($column, ($s = 'ToString')) && ($column = Str::beforeLast($column, $s))
                        && Schema::hasColumn($table, $column)
                    )
                    || (
                        Str::endsWith($column, ($s = '_to_string')) && ($column = Str::beforeLast($column, $s))
                        && Schema::hasColumn($table, $column)
                    )
                    || (
                        Str::endsWith($column, ($s = '_to_yes')) && ($column = Str::beforeLast($column, $s))
                        && Schema::hasColumn($table, $column)
                    );

                // if (
                //     Schema::hasColumn($query->getModel()->getTable(), $column)
                //     || (Str::endsWith($column, ($s = 'ToString')) && ($column = Str::beforeLast($column, $s))
                //         && Schema::hasColumn($query->getModel()->getTable(), $column))
                // ) {
                //     $query->orderBy($column, $direction);
                //     continue;
                // }

                // if (Str::endsWith($column, ($s = 'ToString')) && ($column = Str::beforeLast($column, $s))
                //     && Schema::hasColumn($query->getModel()->getTable(), $column)
                // ) {
                //     $query->orderBy($column, $direction);
                //     continue;
                // }
                $hasColumn && !$emptyBaseOrder && ($emptyBaseOrder = true);
                ($query->getQuery()->orders = []);
                $hasColumn && $query->orderBy($column, $direction);
            }
        }

        return $query;
    }

    /**
     * @param $column
     *
     * @return string
     */
    protected function getMapSortColumns($column)
    {
        return ($this->mapSortColumns[$column] ?? $column);
    }
}

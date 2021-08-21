<?php

namespace App\Traits\BaseController;

use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CrudController
 *
 * @package App\Traits\BaseController
 */
trait CrudEventsController
{

    /**
     * @param $query
     *
     * @return mixed|void
     */
    protected function indexing(&$query) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function saving(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function creating(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function saved(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function created(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function updating(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function updated(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function showing(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function deleting(&$model) { }

    /**
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function deleted(&$model) { }

    /**
     * @param array $rules
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function beforeValidate(&$rules, &$model) { }

    /**
     * @param array $rules
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function beforeStoreValidate(&$rules, &$model) { }

    /**
     * @param array $rules
     * @param Model|Builder $model
     *
     * @return mixed|void
     */
    protected function beforeUpdateValidate(&$rules, &$model) { }
}

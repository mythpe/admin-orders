<?php

namespace App\Traits\BaseController;

use App\Http\Resources\ApiResource as Transformer;
use App\Models\Model as Model;
use App\Traits\HasActiveTrait;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait CrudController
 *
 * @package App\Traits\BaseController
 */
trait CrudController
{
    use CrudEventsController, CrudSearchController, CrudRulesController, CrudSortController;

    /**
     * @var string
     */
    public static $controllerModel = Model::class;

    /**
     * @var string
     */
    public static $controllerTransformer = Transformer::class;

    /**
     * @var string
     */
    public static $indexTransformer = Transformer::class;

    /**
     * sort query as latest
     *
     * @var bool
     */
    protected $latest = true;

    /**
     * sort query as oldest
     *
     * @var bool
     */
    protected $oldest = false;

    /**
     * @return mixed|void
     */
    public function allIndex()
    {
        $this->itemsPerPage = -1;
        $query = static::$controllerModel;
        $query = $query::query();
        in_array(HasActiveTrait::class, class_uses(static::$controllerModel)) && $query->activeOnly();
        return $this->index($query);
    }

    /**
     * @return mixed|void
     */
    public function index()
    {
        /** @var \Illuminate\Database\Eloquent\Builder|mixed $query */
        $query = static::$controllerModel;
        $query = func_get_args()[0] ?? $query::query();

        if ($this->latest)
            $query->latest($this->latest === true ? null : $this->latest);

        if ($this->oldest)
            $query->oldest($this->oldest === true ? null : $this->oldest);

        if (($r = $this->indexing($query))) return $r;
        return $this->indexResponse($query);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        /** @var Model $model */
        $model = new static::$controllerModel;

        $rules = $this->storeRules([], $model);
        $rules = $this->requestRules($rules, $model);
        /** Events */
        if (($r = $this->beforeStoreValidate($rules, $model))) return $r;
        if (($r = $this->beforeValidate($rules, $model))) return $r;

        $this->makeValidator($rules, $model);
        $fill = $this->request->only(array_keys($rules));
        $model->fill($fill);

        /** Events */
        if (($r = $this->creating($model))) return $r;
        if (($r = $this->saving($model))) return $r;

        $model->save();

        /** Events */
        if (($r = $this->created($model))) return $r;
        if (($r = $this->saved($model))) return $r;

        return $this->resource($this->getControllerTransformer()::make($model), __("messages.store_success"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Model|Builder $model
     * @param array $extra
     *
     * @return \Illuminate\Http\Response
     */
    public function update($model)
    {
        $rules = $this->updateRules([], $model);
        $rules = $this->requestRules($rules, $model);

        /** Events */
        if (($r = $this->beforeUpdateValidate($rules, $model))) return $r;
        if (($r = $this->beforeValidate($rules, $model))) return $r;

        $this->makeValidator($rules, $model);
        $fill = $this->request->only(array_keys($rules));
        $model->fill($fill);

        /** Events */
        if (($r = $this->updating($model))) return $r;
        if (($r = $this->saving($model))) return $r;

        $model->save();

        /** Events */
        if (($r = $this->updated($model))) return $r;
        if (($r = $this->saved($model))) return $r;

        return $this->resource($this->getControllerTransformer()::make($model), __("messages.updated_success"));
    }

    /**
     * Display the specified resource.
     *
     * @param Model|Builder $model
     *
     * @return \Illuminate\Http\Response
     */
    public function show($model)
    {
        if ($r = $this->showing($model)) return $r;

        return $this->resource($this->getControllerTransformer()::make($model));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Model|Builder $model
     *
     * @throws \Exception
     * @return \Illuminate\Http\Response
     */
    public function destroy($model)
    {
        /** @var Model $user */

        if (($r = $this->deleting($model))) return $r;

        if (($user = auth()->user()) && $model->is($user)) return $this->errorResponse(__("messages.deleted_failed"));

        $model->delete();

        if (($r = $this->deleted($model))) return $r;
        $message = __('messages.deleted_success');
        try {
            return $this->resource($this->getControllerTransformer()::make($model), $message);
        }
        catch (\Exception $exception) {
        }
        return $this->resource($message);
    }

    /**
     * @return Model|null
     */
    protected function getBindModel()
    {
        return $this->request->{class_basename(static::$controllerModel)};
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder|null $builder
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function apply($builder = null)
    {
        if ($builder) {
            $builder = $this->sortQuery($builder);
            $builder = $this->searchQuery($builder);
            $builder = $this->filerQuery($builder);
        }
        return $builder;
    }

}

<?php

namespace App\Traits\BaseController;

use App\Models\Model as Model;

/**
 * Trait CrudController
 *
 * @package App\Traits\BaseController
 */
trait CrudRulesController
{

    /**
     * @param array $rules
     * @param Model|null $model
     *
     * @return array
     */
    protected function storeRules(array $rules = [], &$model = null): array
    {
        return $this->requestRules($rules, $model);
    }

    /**
     * @param array $rules
     * @param Model|null $model
     *
     * @return array
     */
    protected function requestRules(array &$rules = [], &$model = null): array
    {
        return array_merge($rules);
    }

    /**
     * @param array $rules
     * @param Model|null $model
     *
     * @return array
     */
    protected function updateRules(array $rules = [], &$model = null): array
    {
        return $this->requestRules($rules, $model);
    }

    /**
     * @param array|null $rules
     * @param Model|null $model
     */
    protected function makeValidator(array $rules = [], &$model = null): void
    {
        $rules = $rules ?: $this->requestRules($rules, $model);
        $this->request->validate($rules ?: [], $this->validatorMessages());
    }

    /**
     * @return array
     */
    protected function validatorMessages(): array
    {
        return [];
    }

}

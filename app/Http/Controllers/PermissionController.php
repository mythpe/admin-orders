<?php

namespace App\Http\Controllers;

use App\Http\Resources\PermissionResource as Transformer;
use App\Models\Permission as Model;
use Illuminate\Validation\Rule;

/**
 * Class PermissionController
 *
 * @package App\Http\Controllers
 */
class PermissionController extends Controller
{
    /**
     * PermissionController constructor.
     */
    public function __construct()
    {
        self::$indexTransformer = self::$controllerTransformer = Transformer::class;
        self::$controllerModel = Model::class;
        parent::__construct();
    }


    /**
     * @param array $rules
     * @param Model|null $model
     *
     * @return array
     */
    protected function requestRules(&$rules = [], &$model = null): array
    {
        $main = [
            'name' => ['string', Rule::unique(Model::getModelTable())],
        ];
        return array_merge($main, $rules);
    }

}

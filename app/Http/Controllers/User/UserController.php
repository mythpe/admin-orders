<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource as Transformer;
use App\Models\Role;
use App\Models\User as Model;
use Illuminate\Validation\Rule;

/**
 * Class UserController
 *
 * @package App\Http\Controllers\User
 */
class UserController extends Controller
{
    /**
     * BankController constructor.
     */
    public function __construct()
    {
        self::$indexTransformer = self::$controllerTransformer = Transformer::class;
        self::$controllerModel = Model::class;
        parent::__construct();
    }

    /**
     * get auth user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile()
    {
        return $this->resource(Transformer::make(auth()->user()));
    }

    /**
     * get auth user profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateProfile()
    {
        /** @var \App\Models\User $user */
        $user = $this->request->user();
        $rules = [
            'name'     => ['required'],
            'username' => ['nullable', Rule::unique(Model::class, 'username')->ignore($user)],
            //            'role_id'  => ['required', Rule::exists(Role::getModelTable(), 'id')],
            'password' => ['nullable', 'confirmed', 'min:6'],
        ];
        $this->request->validate($rules);
        $fill = $this->request->only(array_keys($rules));
        //        d($fill);
        $user->update($fill);
        return $this->resource(Transformer::make($user), __("messages.updated_success"));
    }

    /**
     * @param \App\Models\Model|\Illuminate\Database\Eloquent\Builder|Model $model
     *
     * @return mixed|void
     */
    public function saved(&$model)
    {
        $model->permissions()->sync($this->request->get('permissions', []));
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
            'name'           => [$this->requiredRule(),],
            'username'       => [$this->requiredRule(), Rule::unique(Model::getModelTable())->ignore($model)],
            'role_id'        => [$this->requiredRule(), Rule::exists(Role::getModelTable(), 'id')],
            'password'       => [$this->requiredRule(), 'confirmed'],
            'positive_color' => ['nullable'],
            'negative_color' => ['nullable'],
        ];
        return array_merge($main, $rules);
    }
}

<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ApiResource;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Class UserIndexResource
 *
 * @package App\Http\Resources\User
 */
class UserResource extends ApiResource
{
    /** @var User */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $model = $this->resource;
        $permissions = $model->permissions;
        return [
            "id"                => (int)$model->id,
            "name"              => (string)$model->name,
            "username"          => (string)$model->username,
            "role_id"           => (int)$model->role_id,
            "role_name"         => (string)$model->role->name,
            "role_id_to_string" => (string)$model->role->name,
            "is_admin"          => $model->role->name == User::ROLES['admin'],

            "permissions"           => $permissions->pluck('id'),
            "permissions_to_string" => strtoupper($permissions->sortBy('name')->pluck('name')->implode(',')) ?: '-',

            "positive_color"          => $model->positive_color,
            "negative_color"          => $model->negative_color,
        ];
    }
}

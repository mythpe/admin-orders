<?php

namespace App\Http\Resources;

use App\Models\Role;
use Illuminate\Http\Request;

/**
 * Class RoleResource
 *
 * @package App\Http\Resources
 */
class RoleResource extends ApiResource
{
    /** @var Role */
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
        return [
            "id"    => (int)$model->id,
            "value" => (int)$model->id,
            "key"   => (string)$model->id,

            "text" => (string)$model->name,
            'name' => (string)$model->name,
        ];
    }
}

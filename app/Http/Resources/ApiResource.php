<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ApiResource
 *
 * @package App\Http\Resources
 */
class ApiResource extends JsonResource
{

    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $model = $this->resource->toArray();
        $id = ($model['id'] ?? ($model['value'] ?? null));
        $name = ($model['name'] ?? ($model['text'] ?? null));

        return array_merge($model, [
            "id"    => $id,
            "value" => $id,
            "key"   => (string)$id,
            "text"  => (string)$name,
        ]);
    }
}

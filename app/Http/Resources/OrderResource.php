<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;

/**
 * Class OrderResource
 *
 * @package App\Http\Resources
 */
class OrderResource extends ApiResource
{
    /** @var Order */
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
            "id"       => (int)$model->id,
            "value"    => (int)$model->id,
            "key"      => (string)$model->id,
            "text"     => (string)$model->total,
            'name'     => (string)$model->total,
            "total"    => (int)$model->total,
            "close"    => (int)$model->total,
            "username" => (string)$model->user->name,
        ];
    }
}

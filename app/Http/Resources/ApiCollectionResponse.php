<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class ApiCollectionResponse
 *
 * @package App\Http\Resources
 */
class ApiCollectionResponse extends ResourceCollection
{

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = 'App\Http\Resources\ApiResource';

    /**
     * ApiCollectionResponse constructor.
     *
     * @param $resource
     * @param null|string $collects
     */
    public function __construct($resource, $collects = null)
    {
        $this->collects = $collects ? $collects : $this->collects;
        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'message' => "",
            'data'    => $this->collection,
            'success' => true,
        ];
    }
}

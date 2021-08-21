<?php

namespace App\Http\Controllers;

use App\Traits\BaseController\CrudController;
use App\Traits\BaseController\CrudFilterController;
use App\Traits\BaseController\PaginateController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    use CrudController, PaginateController, CrudFilterController;

    /** @var \App\Models\User */
    public $user;

    /** @var Request|mixed */
    protected $request;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        $this->request = request();
        method_exists($this, 'iniPaginateRequest') && $this->iniPaginateRequest($this->request);
        $this->middleware(function ($request, \Closure $next) {
            $this->user = $request->user();
            return $next($request);
        });
    }

    /**
     * Send API Unique success Message Response
     *
     * @param array $data
     *
     * @return JsonResponse
     */
    protected function successResponse(array $data = []): JsonResponse
    {
        $data = array_merge([
            "message" => '',
            "data"    => null,
        ], $data, [
            "success" => true,
        ]);
        return $this->json($data);
    }

    /**
     * Send API Unique Response
     *
     * @param array $json response data include message
     * @param int $status
     *
     * @return JsonResponse
     */
    protected function json($json = [], $status = 200): JsonResponse
    {
        ($json['message'] ?? ($json['message'] = ""));
        ($json['data'] ?? ($json['data'] = (object) []));
        $json['success'] = array_key_exists('success', $json) ? $json['success'] : $status == 200;

        $response = response()->json($json, $status);
        try {
            /** For none Json Headers */
            return $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        catch (\Exception $exception) {
        }
        return $response;
    }

    /**
     * Send API Unique Error Message Response
     *
     * @param string $message
     *
     * @return JsonResponse
     */
    protected function errorResponse($message): JsonResponse
    {
        return $this->json([
            'message' => $message,
            "data"    => null,
            "success" => false,
        ], 422);
    }

    /**
     * Send API unique response for model
     * Helper
     *
     * @param string|mixed $model
     * @param string|null $message
     *
     * @return JsonResponse
     */
    protected function resource($model, $message = ''): JsonResponse
    {
        if (is_string($model)) {
            $message = $model;
            //$model = (object) [];
            $model = null;
        }
        return $this->json([
            "message" => $message,
            "data"    => $model,
            "success" => true,
        ]);
    }

    /**
     * @return string|null
     */
    protected function requiredRule(): ?string
    {
        if ($this->isSingle() || $this->getBindModel() && $this->getBindModel()->exists) return null;
        return 'required';
    }

    /**
     * @return bool
     */
    protected function isSingle(): bool
    {
        return $this->request->has('singleItem');
    }
}

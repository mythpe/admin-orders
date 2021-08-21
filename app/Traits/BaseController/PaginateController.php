<?php

namespace App\Traits\BaseController;

use App\Exports\BaseExport;
use App\Http\Resources\ApiCollectionResponse;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Trait PaginateController
 *
 * @package App\Traits\BaseController
 */
trait PaginateController
{

    /**
     * @var string
     */
    static $PdfView = 'layouts.pdf_table';

    /**
     * @var null
     */
    static $ExcelClass = BaseExport::class;

    /** @var int */
    protected $page = 1;

    /** @var int */
    protected $limit = null;

    /** @var int */
    protected $itemsPerPage = 15;

    /**
     * @var array
     */
    protected $mapColumns = [];

    /**
     * @var bool
     */
    protected $enablePaginate = !0;

    /**
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Relations\Relation|mixed $query
     * @param null $transformer
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @return \App\Http\Resources\ApiCollectionResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function indexResponse($query)
    {
        $query = is_null($query) ? static::$controllerModel::whereNull('id') : $query;
        $request = $this->request;
        $args = func_get_args();
        $transformer = ($args[1] ?? $this->getIndexTransformer());
        $indexType = $request->get('indexType');
        $modelName = Str::pluralStudly(class_basename($query->getModel()));
        $pageTitle = $request->get(($a = 'pageTitle')) ? $request->get($a) : (trans_has(($a = "choice.{$modelName}")) ? trans_choice($a, 1) : $modelName);

        if ($indexType == 'pdf' || $indexType == 'excel') {
            $items = $request->get('items', []);
            $headers = $request->get('headerItems', []);

            if (!$items) {
                $query = $this->apply($query);
                $items = $transformer::collection($query->get())->toArray($this->request);
            }
            else {
                $items = $transformer::collection($query->whereIn('id', $items)->get())->toArray($this->request);
            }

            if (!is_array($headers)) $headers = [];
            if (!is_array($items)) $items = [];

            $fileName = "{$modelName}-Export";

            if ($indexType == 'excel') {
                /** @var mixed $excelClass */
                $excelClass = static::$ExcelClass;
                return Excel::download($excelClass::make($headers, $items), "{$fileName}.xlsx");
            }
            // d($headers, $items);
            $compact = [
                'headerItems' => $headers,
                'items'       => $items,
                'pageTitle'   => $pageTitle,
            ];

            $disk = Storage::disk('pdf');
            $fileName = "{$fileName}.pdf";
            $path = $disk->path($fileName);

            $pdf = SnappyPdf::loadView(static::$PdfView, $compact);
            $pdf->setOption('title', $pageTitle);
            $pdf->save($path, !0);
            // return $pdf->inline($fileName);
            $size = $disk->getSize($fileName);
            // return $disk->url($fileName);
            $disk->delete($fileName);
            return $pdf->download($fileName)->header('Content-Length', $size);
            // return $disk->download($fileName);
        }

        if (!$this->enablePaginate) {
            $this->itemsPerPage = -1;
        }

        return new ApiCollectionResponse($this->paginate($query), $transformer);
    }

    /**
     * @return string
     */
    protected function getIndexTransformer()
    {
        return static::$indexTransformer;
    }

    /**
     * Do calc for Pagination.
     *
     * @param Builder $query
     *
     * @return Builder|mixed
     */
    protected function paginate($query)
    {
        $query = $this->apply($query);
        if ($this->itemsPerPage == -1 || !is_null($this->limit)) {
            if (!is_null($this->limit))
                $query->limit((int) ($this->limit));

            $limit = !is_null($this->limit) ? $this->limit : $query->toBase()->limit;
            // d($query->count());
            // d($query->getCountForPagination());
            // $this->itemsPerPage = !is_null($limit) ? $limit : $query->get()->count();
            $this->itemsPerPage == -1 && ($this->page = 1);
            $this->itemsPerPage = !is_null($limit) ? $limit : $query->count();
            // d($this->itemsPerPage);
            // d($query->count());
        }
        return $query->paginate((int) $this->itemsPerPage, ['*'], 'page', (int) $this->page);
    }

    /**
     * @return string|mixed
     */
    protected function getControllerTransformer()
    {
        return static::$controllerTransformer;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return $this
     */
    protected function iniPaginateRequest(Request $request): self
    {
        $this->itemsPerPage = (int) $request->get("itemsPerPage", $this->itemsPerPage);
        $this->page = (int) $request->get("page", $this->page);
        $this->limit = $request->has("limit") ? (int) $request->get("limit", null) : null;
        return $this;
    }

}

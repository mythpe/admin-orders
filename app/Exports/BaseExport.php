<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Class BaseExport
 *
 * @package App\Exports
 */
class BaseExport implements FromCollection
{
    /**
     * @var array|\Illuminate\Support\Collection
     */
    public $headers = [];

    /**
     * @var array|\Illuminate\Support\Collection
     */
    public $items = [];

    /**
     * @param array|\Illuminate\Support\Collection $headers
     * @param array|\Illuminate\Support\Collection $items
     */
    public function __construct($headers = [], $items = [])
    {
        $this->headers = collect($headers);
        $this->items = is_array($items) ? collect($items) : $items;
    }

    /**
     * @return static
     */
    public static function make()
    {
        return new static(...func_get_args());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data = [];
        foreach ($this->headers as $k => $header) {
            $data[] = ($header['text'] ?? $k);
        }
        $data = [$data];

        foreach ($this->items as $itemKey => $item) {
            $v = [];
            foreach ($this->headers as $headerKey => $header) {
                $v[] = ($item[($header['value'] ?? $headerKey)] ?? $itemKey);
            }
            $data[] = $v;
        }
        //d($data);
        return collect($data);
    }
}

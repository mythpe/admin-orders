<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Artisan;

/**
 * Class DebugController
 *
 * @package App\Http\Controllers
 */
class DebugController extends Controller
{
    /**
     * debug function
     *
     * @return mixed
     */
    public function debug()
    {
        if(!config('app.debug')){
            return;
        }
        // $test = Calculator::requestDocumentation();
        // $test = Calculator::responseDocumentation();
        return;
    }

    public function importProfits()
    {
        if(config('app.debug')) $a = Artisan::call('import:profits');
    }

    public function excelProfits()
    {
        Artisan::call('excel:profits');
    }
}

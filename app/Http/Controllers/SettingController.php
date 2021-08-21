<?php

namespace App\Http\Controllers;

/**
 * Class SettingController
 *
 * @package App\Http\Controllers
 */
class SettingController extends Controller
{
    public function save()
    {
        $rules = [
            'start'    => ['required', 'numeric'],
            'rst'      => ['required', 'numeric'],
            'rst_plus'  => ['required', 'numeric'],
            'rst_minus' => ['required', 'numeric'],
        ];
        $this->request->validate($rules);
        $fill = $this->request->only(array_keys($rules));
        setting($fill)->save();
        return $this->get();
    }

    public function get()
    {
        return $this->resource(setting()->all());
    }

}

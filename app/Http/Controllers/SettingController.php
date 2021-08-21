<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;

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
            'start'       => ['required', 'numeric'],
            'lmt_up'      => ['required', 'numeric'],
            'lmt_dn'      => ['required', 'numeric'],
            'clr_plus'    => ['required', 'numeric'],
            'clr_minus'   => ['required', 'numeric'],
            'open_fields' => ['required', 'numeric', Rule::in([1, 2])],
            //'rst'       => ['required', 'numeric'],
            //'rst_plus'  => ['required', 'numeric'],
            //'rst_minus' => ['required', 'numeric'],
        ];
        $this->request->validate($rules);
        $fill = $this->request->only(array_keys($rules));
        setting($fill)->save();
        return $this->get();
    }

    public function get()
    {
        return $this->resource(getSettings());
    }

}

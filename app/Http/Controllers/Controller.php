<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Run the validation routine against the given validator.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function validateWith($validator, Request $request = null)
    {
        $request = $request ?: app('request');

        if ($validator->fails()) {
            $this->throwValidationException($request, $validation);
        }
    }
}

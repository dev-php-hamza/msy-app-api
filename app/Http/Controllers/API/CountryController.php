<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use App\Country;

class CountryController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $data['countries'] = Country::whereSwitch(1)->orderBy('name', 'asc')->get();
        return response()->json(Helper::makeResponse($data,null,null,200,true));
    }
}

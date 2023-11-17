<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Validator;
use App\Country;

class AreaController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country' => 'required|string|max:3',
        ]);

        if ($validator->fails()) {
          return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
        }

        $country = Country::whereCountryCode($request->country)->first();
        if (count($country)>0) {
            $countryLocations = $country->locations()->orderBy('name', 'asc')->get();
            return response()->json(Helper::makeResponse($countryLocations,null,null,200,true));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
}

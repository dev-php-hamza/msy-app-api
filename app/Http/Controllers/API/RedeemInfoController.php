<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\Country;
use App\Helper;
use App\RedeemInfo;

class RedeemInfoController extends Controller
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
            'country' => 'required|string|max:3'
        ]);

        if ($validator->fails()) {
            return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $country = Country::where('country_code', 'like', '%'.strtoupper($request->country).'%')->first();
        if (count($country) > 0 ) {
            $redeemInfo = $country->redeemInfo;
            if (count($redeemInfo) > 0) {
                $redeemInfo->image = $redeemInfo->getImage();
                return response()->json(Helper::makeResponse($redeemInfo,null,null,200,true));
            }
            return response()->json(Helper::makeResponse(null,'Unprocessable Entity','This country does not haev any redeemInfo',200,false));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
        
    }
}

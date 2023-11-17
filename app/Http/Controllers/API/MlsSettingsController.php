<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Helper;
use App\Country;
use App\MlsSettings;

class MlsSettingsController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$user = auth()->user();
		$country_code = $user->userInfo->country;
	    $country = Country::whereCountryCode($country_code)->first();
	    if (count($country) > 0 ) {
	    	$data['mls_service_status'] = (bool)$country->mls_service_status;
	        return response()->json(Helper::makeResponse($data,null,null,200,true));
	    }
	    
	    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
	}
}

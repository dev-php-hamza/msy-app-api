<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Validator;
use App\Helper;
use App\Store;
use App\StoreInfo;
use App\Country;


class StoreController extends Controller
{
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
         'country'  => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
        	return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

		$input = $request->all();
		if (!isset($input['country']) || empty($input['country'])) {
			$stores = Store::with('StoreInfo')->orderBy('name')->get();
		}else{
			/*find country id first from country name*/
			$country = Country::select('id')->whereName($input['country'])->first();
			if (isset($country) && !empty($country) ) {
				$stores = Store::with('StoreInfo')->whereCountryId($country->id)->orderBy('name')->get();
			}
		}

		if(isset($stores) && !empty($stores)){
			$stores = $this->extractData($stores);
			return response()->json(Helper::makeResponse($stores,null,null,200,true));
	    }
	    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,true));
	}

	public function extractData($stores)
	{
		$data = array();
		foreach ($stores as $key => $store) {
			$country = Country::find($store->country_id);
			$temp = array();
			$temp['id']               = $store->id; 
			$temp['name']             = $store->name; 
			$temp['storecode']        = $store->storecode; 
			$temp['address_line_one'] = $store->address_line_one; 
			$temp['address_line_two'] = $store->address_line_two; 
			$temp['country_id']       = $store->country_id;
			$temp['country_code']     = $country->country_code;
			$temp['location_id']      = $store->location_id; 
			$temp['email']            = $store->email; 
			$temp['lat']              = $store->lat; 
			$temp['lon']              = $store->lon; 
			$temp['image'] 	          = $store->getImage(); 
			$temp['delivery_company_name']  = $store->delivery_company_name; 
			$temp['delivery_company_email'] = $store->delivery_company_email; 
			$temp['delivery']         = $store->delivery; 
			$temp['curbside']         = $store->curbside; 
			$temp['store_info']       = $store->StoreInfo;

			$dCompanies = array();
			foreach ($store->deliveryCompanies as $key => $deliveryComapny) {
				$tempDComapny = array();
				$tempDComapny['id']    = $deliveryComapny->id;
				$tempDComapny['name']  = $deliveryComapny->name;
				$tempDComapny['email'] = $deliveryComapny->email;
				$tempDComapny['icon']  = $deliveryComapny->icon;

				$dCompanies[] = $tempDComapny;
			}
			$temp['delivery_companies'] = $dCompanies;

			$data[] = $temp;
		}

		return $data;
	}
}
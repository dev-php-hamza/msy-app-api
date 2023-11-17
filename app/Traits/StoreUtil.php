<?php

namespace App\Traits;
use App\Country;
use App\Store;

trait StoreUtil
{
	/**
	 * Get Stores with storeInfo based on Country or without Country
	 *
	 * @param  string $country
	 * @return Collection|false
	 */
	public function getStores($country=null)
	{
		if (!isset($country) || empty($country)) {
			$stores = Store::with('StoreInfo')->where('storecode','<>', 0)->get();
		}else{
			/* get stores by country if requested */
			$countryData = Country::select('id')->whereName($country)->first();
			if (isset($countryData) && !empty($countryData) ) {
				$stores = Store::with('StoreInfo')->whereCountryId($countryData->id)->where('storecode','<>', 0)->get();
			}
		}

		if(isset($stores) && !empty($stores)){
			foreach ($stores as $key => $store) {
				$cty_info = Country::select('country_code')->whereId($store->country_id)->first(); // Get store country info
				$temp = array();
				$temp['latitude'] = (float) $store->lat;
				$temp['longitude'] = (float) $store->lon;
				$store['coordinates'] = $temp; // Build coordinate array for Google Maps Api
				$store['country_code'] = $cty_info->country_code;
			}
			return $stores;
	    }
	    return false;
	}
}
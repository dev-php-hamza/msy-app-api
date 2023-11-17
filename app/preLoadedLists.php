<?php
namespace App;

use Exception;
use App\Ulist;
use App\Product;
use App\Country;

class preLoadedLists
{
	public static function add($user)
	{
		$listNames = array('Top Items', 'Soup It Up');

		$products = array(
			0 => array(
					'0020074100000',
					'0020040028295',
					'0020961000000',
					'0020070800000',
					'0020961100000',
					'0020961200000',
					'0020592700000',
					'0020240000000',
					'0020417700000',
					'0005431501215',
					'0074901620013',
					'0006897848827',
					'0020406000000',
					'0004722760027',
					'0074830000018',
					'0004900040948',
					'0020095200000',
					'0020142100000',
					'0000009845027',
					'0004722760053',
					'0009849340316',
					'0009848302091',
					'0020029400000',
					'0060945612500',
					'0001887146300',
					'0001887185000',
					'0001887157100',
					'0020000200000',
					'0086866300000',
					'0020472400000',
					'0020423500000',
					'0020409100000',
					'0001780015809',
					'0020486800000',
					'0071328926193',
					'0020406300000',
					'0020407100000',
					'0025539300000',
					'0079313620094', 
			),
			1 => array(
					'0020479400000',
					'0020479200000',
					'0004722784254',
					'0020432200000',
					'0004722760000',
					'0003338366400',
					'0020465500000',
					'0020473400000',
					'0008816900236',
					'0074901627001',
					'0020423500000',
			),
		);

	  $userCountryCode = $user->userInfo->country;
	  $country = Country::whereCountryCode($userCountryCode)->first();
	  if (count($country) > 0) {
	  	$countryId = $country->id;
        $product_ids = self::getValidProducts($products, $countryId);

        foreach ($listNames as $key => $name) {
            $list = new Ulist();
            $list->name = $name;
            $list->user_id = $user->id;
            $list->save();

			// Attach product id array key wise
            $list->products()->attach($product_ids[$key]);
        }
	  }
	  return true;
	}

	public static function getValidProducts($products, $countryId){

	    $product_ids = array();

	    foreach ($products as $arr) {
	        $temp = array();
	        foreach ($arr as $upc) {
	            $product = Product::where('upc',$upc)->where('country_id', $countryId)->first();
	            if(count($product) > 0 ) {
	                $temp[] = $product->id;
	            }
	        }
	        $product_ids[] = $temp;
	    }

	    return $product_ids;
	}
}
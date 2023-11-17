<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Auth;
use App\Helper;
use App\Coupon;
use App\Country;
use App\CouponUsers;
use App\CouponProduct;
use App\Bundle;
use App\Promotion;

class CouponController extends Controller
{
	public function index(Request $request)
	{
		$validator = Validator::make($request->all(), [
	      'country' => 'required|string|max:3',
	    ]);

	    if ($validator->fails()) {
	      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
	    }

    	$input = $request->all();
		$data = array();
	    $product_count = 0;
	    // $user = Auth::user();
	    // $coupons = Coupon::latest()->get();

	    /*find country id first from country code*/
	    $country = Country::select('id')->whereCountryCode($input['country'])->first();
	    if (isset($country) && !empty($country) ) {
	      $coupons = $country->coupons()->Active()->get();
	    }

	    if (count($coupons) > 0) {
	    	foreach ($coupons as $key => $coupon) {
		      	$temp = array();
		      	$temp_bundle = array();
		      	$temp_mm_conditions = array();
		      	$temp['coupon_id'] = $coupon->id;
		      	$temp['title'] = $coupon->title;
		      	$temp['start_date'] = $coupon->start_date;
		      	$temp['start_time'] = $coupon->start_time;
		      	$temp['end_date'] = $coupon->end_date;
		      	$temp['end_time'] = $coupon->end_time;
		      	$temp['image']    = $coupon->getImage();
		      	$temp['barcode']  = $coupon->barcode;
		      	$temp['short_description'] = $coupon->short_description;
		      	$temp['description'] = $coupon->description;
		      	// $couponUser = CouponUsers::whereCouponId($coupon->id)->whereUserId($user->id)->first();
		      	// $temp['active'] = (count($couponUser) > 0)?(bool)$couponUser->active:false;
		      	// $temp['active'] = $coupon->active;
			    $temp['type'] = $coupon->coupon_type;
			    $temp['mix_and_match_type'] = $coupon->mix_and_match_type;

		      	$bundle = Bundle::where('coupon_id', $coupon->id)->first();
		      	if (count($bundle) > 0) {
		      		if ($bundle->name == 'std_bundle') {
		      			$temp_bundle['bundle_price'] = $bundle->bundle_price;
		      			$temp_bundle['number'] = $bundle->number;

		      			$temp['bundle_details'] = $temp_bundle;
		      		} 
		      		
		      	}
		      	if($coupon->coupon_type == 'mix_and_match'){
		      		if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
		      			foreach ($coupon->mix_and_match_conditions as $conditon) {
		      				$temp_mm_conditions['buy_quantity'] = $conditon->conditions;
		      				$temp_mm_conditions['selection_quantity'] = $conditon->selection_quantity;

		      				$temp['mix_and_match_conditions'] = $temp_mm_conditions;
		      			}
		      		}
		      	}
		      	if(isset($coupon->products) && count($coupon->products) > 0){
			  		$coupon_products = $coupon->products;
			  		foreach ($coupon_products as $key => $product) {
			      		$images = $product->images()->latest()->get();
			      		$temp_product = array();
					    $image_temp = array();
					    $temp_product['product_id'] = $product->id;
					    $temp_product['name'] = $product->desc; 
					    $temp_product['price'] = $product->unit_retail;
					    $temp_product['quantity'] = $product->pivot->quantity;
					    $temp_product['total_price'] = $product->pivot->total_price;
					    $temp_product['discount_price'] = $product->pivot->discount_price;
					    $temp_product['discount_percentage'] = $product->pivot->discount_percentage;
					    $temp_product['discount_type'] = $product->pivot->discount_type;
					    $temp_product['type'] = $product->pivot->type;

					    if(count($images) > 0){
				        	foreach ($images as $key => $image) {
				          		$image_temp[] = $image->getImage($product->upc);
				        	}
				      	}
			      		$temp_product['images'] = $image_temp;

			      		$temp['products'][] = $temp_product;
					}
			    }
				$data['coupons'][] = $temp;
			}
	    	return response()->json(Helper::makeResponse($data,null,null,200,true));
	    }else{
	      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','coupons not found',200,false));
	    }
	}

	public function detail(Request $request)
	{
		$validator = Validator::make($request->all(), [
		  'id' => 'required|numeric',
		]);

		if ($validator->fails()) {
		  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$data = array();
		// $user = Auth::user();
		$coupon = Coupon::find($request->id);
		if(isset($coupon)){
			$detail = array();
			$products = array();
			$bundle_details = array();
			$bundle_products = array();
			$temp_mm_conditions = array();
		  	$detail['coupon_id'] = $coupon->id;
		  	$detail['title'] = $coupon->title;
		  	$detail['type'] = $coupon->coupon_type;
		  	$detail['mix_and_match_type'] = $coupon->mix_and_match_type;
		  	$detail['start_date'] = $coupon->start_date;
		  	$detail['start_time'] = $coupon->start_time;
		  	$detail['end_date'] = $coupon->end_date;
		  	$detail['end_time'] = $coupon->end_time;
		  	$detail['short_description'] = $coupon->short_description;
		  	$detail['description'] = $coupon->description;
		  	$detail['image'] = $coupon->getImage();
		  	$detail['barcode'] = $coupon->barcode;

		  	// $couponUser = CouponUsers::whereCouponId($coupon->id)->whereUserId($user->id)->first();
		  	// $detail['active'] = (count($couponUser) > 0)?(bool)$couponUser->active:false;
		  	// $detail['active'] = $coupon->active;

			$data['coupon_detail'] = $detail;

		  	$bundle = Bundle::where('coupon_id', $coupon->id)->first();
		  	if (count($bundle) > 0) {
		  		if ($bundle->name == 'std_bundle') {
		  			$bundle_details['bundle_price'] = $bundle->bundle_price;
		  			$bundle_details['number'] = $bundle->number;

		  			$data['bundle_details'] = $bundle_details;
		  		}
		  	}
		  	if($coupon->coupon_type == 'mix_and_match'){
	      		if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
	      			foreach ($coupon->mix_and_match_conditions as $conditon) {
	      				$temp_mm_conditions['buy_quantity'] = $conditon->conditions;
	      				$temp_mm_conditions['selection_quantity'] = $conditon->selection_quantity;

	      				$data['mix_and_match_conditions'] = $temp_mm_conditions;
	      			}
	      		}
	      	}
		  	if(isset($coupon->products) && count($coupon->products) > 0){
		  		$coupon_products = $coupon->products;
		  		foreach ($coupon_products as $key => $product) {
		      		$images = $product->images()->latest()->get();
		      		$temp = array();
				    $image_temp = array();
				    $temp['product_id'] = $product->id;
				    $temp['name'] = $product->desc; 
				    $temp['price'] = $product->unit_retail;
				    $temp['quantity'] = $product->pivot->quantity;
				    $temp['total_price'] = $product->pivot->total_price;
				    $temp['discount_price'] = $product->pivot->discount_price;
				    $temp['discount_percentage'] = $product->pivot->discount_percentage;
				    $temp['discount_type'] = $product->pivot->discount_type;
				    $temp['type'] = $product->pivot->type;

				    if(count($images) > 0){
			        	foreach ($images as $key => $image) {
			          		$image_temp[] = $image->getImage($product->upc);
			        	}
			      	}
			      	$temp['images'] = $image_temp;

			      	$products[] = $temp;
				}
		    }
				
			$data['products'] = $products;

			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}else{
		  return response()->json(Helper::makeResponse(null,'Unprocessable Entity','coupon not found',200,false));
		}
	}

	public function updateCoupon(Request $request)
	{
		$validator = Validator::make($request->all(), [
		  'id' => 'required|numeric',
		]);

		if ($validator->fails()) {
		  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$user = Auth::user();
		$_updateOrCreate = false;
		$couponUser = CouponUsers::whereCouponId($request->id)->whereUserId($user->id)->first();

		$data = array();
		if (count($couponUser)>0) {
			$couponUser->active = !$couponUser->active;
			$couponUser->save();
			$_updateOrCreate = true;	
		}

		if (count($couponUser) == 0 ) {
			$couponUser = new CouponUsers;
			$couponUser->coupon_id = $request->id;
			$couponUser->user_id = $user->id;
			$couponUser->active = 1;
			$couponUser->save();
			$_updateOrCreate = true;
		}

		if ($_updateOrCreate) {
			$data['active']  = (bool)$couponUser->active;
			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','coupon not found',200,false));
	}

	public function getActiveStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
		  'id' => 'required|numeric',
		]);

		if ($validator->fails()) {
		  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$data = array();
		$coupon = Coupon::find($request->id);
		if(isset($coupon)){
			$data['active'] = (bool)$coupon->active;
			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}else{
		  return response()->json(Helper::makeResponse(null,'Unprocessable Entity','coupon not found',200,false));
		}
	}

	public function getMultipleCouponsWithData(Request $request)
	{
	    $couponIDs = $request->all();
	    $data = array();
	    if(count($couponIDs) < 1){
	    	return response()->json(Helper::makeResponse($data,null,null,200,true));
	    }

	    $coupons = Coupon::whereIn('id', $couponIDs)->get();

        foreach ($coupons as $key => $coupon) {
          $temp_coupon = array();
          $products = array();
          $temp_coupon['coupon_id'] = $coupon->id;
          $temp_coupon['title'] = $coupon->title;
          $temp_coupon['start_date'] = $coupon->start_date;
          $temp_coupon['start_time'] = $coupon->start_time;
          $temp_coupon['end_date'] = $coupon->end_date;
          $temp_coupon['end_time'] = $coupon->end_time;
          $temp_coupon['short_description'] = $coupon->short_description;
          $temp_coupon['description'] = $coupon->description;
          $temp_coupon['image'] = $coupon->getImage();
          $temp_coupon['barcode'] = $coupon->barcode;
          $temp_coupon['type'] = $coupon->coupon_type;
          $temp_coupon['mix_and_match_type'] = $coupon->mix_and_match_type;
          $temp_coupon['bundle_price'] = null;
          $temp_coupon['number'] = null;
          $temp_coupon['buy_quantity'] = null;
      	  $temp_coupon['selection_quantity'] = null;
          $bundle = Bundle::where('coupon_id', $coupon->id)->first();
          if (count($bundle) > 0) {
            if ($bundle->name == 'std_bundle') {
              $temp_coupon['bundle_price'] = $bundle->bundle_price;
              $temp_coupon['number'] = $bundle->number;
            }
          }
          if($coupon->coupon_type == 'mix_and_match'){
          	if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
      			foreach ($coupon->mix_and_match_conditions as $conditon) {
      				$temp_coupon['buy_quantity'] = $conditon->conditions;
      				$temp_coupon['selection_quantity'] = $conditon->selection_quantity;
      			}
      		}
      	  }
          // Get coupon associated products
          if(isset($coupon->products) && count($coupon->products) > 0){
            $coupon_products = $coupon->products;
            foreach ($coupon_products as $key => $product) {
              $images = $product->images()->latest()->get();
              $temp = array();
              $image_temp = array();
              $temp['product_id'] = $product->id;
              $temp['name'] = $product->desc; 
              $temp['price'] = $product->unit_retail;
              $temp['quantity'] = $product->pivot->quantity;
              $temp['total_price'] = $product->pivot->total_price;
              $temp['discount_price'] = $product->pivot->discount_price;
              $temp['discount_percentage'] = $product->pivot->discount_percentage;
              $temp['discount_type'] = $product->pivot->discount_type;
              $temp['type'] = $product->pivot->type;

              if(count($images) > 0){
                foreach ($images as $key => $image) {
                  $image_temp[] = $image->getImage($product->upc);
                }
              }
              $temp['images'] = $image_temp;

              $products[] = $temp;
            }
          }
          $temp_coupon['products'] = $products;

          $data[] = $temp_coupon;
        }

	    return response()->json(Helper::makeResponse($data,null,null,200,true));
	}

	public function featuredCoupons(Request $request)
	{
		$validator = Validator::make($request->all(), [
	      'country' => 'required|string|max:3',
	    ]);

	    if ($validator->fails()) {
	      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
	    }

    	$input = $request->all();
		$data = array();
	    $product_count = 0;
	    // $user = Auth::user();
	    // $coupons = Coupon::latest()->get();

	    /*find country id first from country code*/
	    $country = Country::select('id')->whereCountryCode($input['country'])->first();
	    if (isset($country) && !empty($country) ) {
	      $coupons = $country->coupons()->Active()->where('is_featured', 1)->get();
	    }

	    if (count($coupons) > 0) {
	    	foreach ($coupons as $key => $coupon) {
		      	$temp = array();
		      	$temp_bundle = array();
		      	$temp_mm_conditions = array();
		      	$temp['coupon_id'] = $coupon->id;
		      	$temp['title'] = $coupon->title;
		      	$temp['start_date'] = $coupon->start_date;
		      	$temp['start_time'] = $coupon->start_time;
		      	$temp['end_date'] = $coupon->end_date;
		      	$temp['end_time'] = $coupon->end_time;
		      	$temp['image']    = $coupon->getImage();
		      	$temp['barcode']  = $coupon->barcode;
		      	$temp['short_description'] = $coupon->short_description;
		      	$temp['description'] = $coupon->description;
		      	// $couponUser = CouponUsers::whereCouponId($coupon->id)->whereUserId($user->id)->first();
		      	// $temp['active'] = (count($couponUser) > 0)?(bool)$couponUser->active:false;
		      	// $temp['active'] = $coupon->active;
			    $temp['type'] = $coupon->coupon_type;
			    $temp['mix_and_match_type'] = $coupon->mix_and_match_type;

		      	$bundle = Bundle::where('coupon_id', $coupon->id)->first();
		      	if (count($bundle) > 0) {
		      		if ($bundle->name == 'std_bundle') {
		      			$temp_bundle['bundle_price'] = $bundle->bundle_price;
		      			$temp_bundle['number'] = $bundle->number;

		      			$temp['bundle_details'] = $temp_bundle;
		      		} 
		      		
		      	}
		      	if($coupon->coupon_type == 'mix_and_match'){
		      		if(isset($coupon->mix_and_match_conditions) && count($coupon->mix_and_match_conditions)){
		      			foreach ($coupon->mix_and_match_conditions as $conditon) {
		      				$temp_mm_conditions['buy_quantity'] = $conditon->conditions;
		      				$temp_mm_conditions['selection_quantity'] = $conditon->selection_quantity;

		      				$temp['mix_and_match_conditions'] = $temp_mm_conditions;
		      			}
		      		}
		      	}
		      	if(isset($coupon->products) && count($coupon->products) > 0){
			  		$coupon_products = $coupon->products;
			  		foreach ($coupon_products as $key => $product) {
			      		$images = $product->images()->latest()->get();
			      		$temp_product = array();
					    $image_temp = array();
					    $temp_product['product_id'] = $product->id;
					    $temp_product['name'] = $product->desc; 
					    $temp_product['price'] = $product->unit_retail;
					    $temp_product['quantity'] = $product->pivot->quantity;
					    $temp_product['total_price'] = $product->pivot->total_price;
					    $temp_product['discount_price'] = $product->pivot->discount_price;
					    $temp_product['discount_percentage'] = $product->pivot->discount_percentage;
					    $temp_product['discount_type'] = $product->pivot->discount_type;
					    $temp_product['type'] = $product->pivot->type;

					    if(count($images) > 0){
				        	foreach ($images as $key => $image) {
				          		$image_temp[] = $image->getImage($product->upc);
				        	}
				      	}
			      		$temp_product['images'] = $image_temp;

			      		$temp['products'][] = $temp_product;
					}
			    }
				$data['coupons'][] = $temp;
			}
	    	return response()->json(Helper::makeResponse($data,null,null,200,true));
	    }else{
	      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','coupons not found',200,false));
	    }
	}
}
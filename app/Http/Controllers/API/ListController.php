<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Helper;
use App\User;
use App\Ulist;
use App\Product;
use App\ListProduct;
use App\Coupon;
use App\CouponList;
use App\CouponListProduct;
use App\Bundle;
use Validator;

class ListController extends Controller
{
	public function index(Request $request)
	{
		$user = Auth::user();
       	$data = array();

   		if(isset($user->lists) && count($user->lists) > 0){
   			$lists = $user->lists()->with('products')->latest()->get();
   			foreach ($lists as $list) {
   				$list['coupon_count'] = count($list->coupons);
   				if(isset($list['coupons'])) { unset($list['coupons']); }
   				$data[] = $list;
   			}
   		}
   		return response()->json(Helper::makeResponse($data,null,null,200,true));
	}

	public function create(Request $request)
	{
		$validator = Validator::make($request->all(), [
         'name'     => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
        	return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $user = Auth::user();
        $data = array();
	    $input = $request->all();
	    $list = new Ulist();
	    $list->name = $input['name'];
	    $list->user_id = $user->id;
	    $list->save();

	    $data[] = $list;
	    return response()->json(Helper::makeResponse($data,null,null,200,true));
	}

	public function update(Request $request)
	{
		$validator = Validator::make($request->all(), [
         'name'     => 'required|string|max:255',
         'list_id'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
        	return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

	    $input = $request->all();
	    $list = Ulist::where('id', $input['list_id'])->first();
	    if(isset($list)){
	    	$list->name = $input['name'];
		    $list->save();

		    return response()->json(Helper::makeResponse(null,null,null,200,true));
	    }
	    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function delete(Request $request)
	{
		$validator = Validator::make($request->all(), [
         'list_id'  => 'required|numeric',
        ]);

        if ($validator->fails()) {
        	return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $input = $request->all();
	    $list = Ulist::where('id', $input['list_id'])->first();
	    if(isset($list) && !empty($list)){
		    $list->delete();

		    return response()->json(Helper::makeResponse(null,null,null,200,true));
	    }
	    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function addItemsToList(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' 		=> 'required|numeric',
			'product_id' 	=> 'required|numeric',
			'product_quantity' => 'nullable|string'
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$input = $request->all();

		$list = Ulist::find($input['list_id']);
		$product = Product::find($input['product_id']);

		if(isset($list) && isset($product) && !empty($list) && !empty($product)){

			$listProduct = ListProduct::updateOrCreate([
				'list_id' 		=> $input['list_id'],
				'product_id' 	=> $input['product_id'],
			],[
				'list_id' 		=> $input['list_id'],
				'product_id' 	=> $input['product_id'],
				'product_quantity' => $input['product_quantity']??0,
			]);

			return response()->json(Helper::makeResponse(null,null,null,200,true));
		}

		// $list = Ulist::find($input['list_id']);
		// $list->products()->attach($input['product_id']);

		// $data[] = $listProduct;
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List or product not found',200,false));
	}

	public function getListItems(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$data = array();
		$input = $request->all();
		
		$list = Ulist::find($input['list_id']);

		if (isset($list) && !empty($list)) {
			$data['checked'] = $list->checkedProducts();
			$data['unchecked'] = $list->uncheckedProducts();
			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function getListItemsNew(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$data = array();
		// $products = array();
		// $bundles = array();
		$input = $request->all();
		
		$list = Ulist::find($input['list_id']);
		
		if (isset($list) && !empty($list)) {

			// $products['checked'] = $list->checkedProducts();
			// $products['unchecked'] = $list->uncheckedProducts();

			// $bundles['checked'] = $this->getBundleData($list->checkedBundles());
			// $bundles['unchecked'] = $this->getBundleData($list->uncheckedBundles());

			$data['products'] = $this->getRegularProductsData($list->products);
			$data['bundles'] = $this->getBundleData($list->coupons);
			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function deleteListItem(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' 	  => 'required|numeric',
			'product_id'  => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$listItem = ListProduct::whereListId($request->list_id)->whereProductId($request->product_id)->first();
		if (isset($listItem) && !empty($listItem)) {
			$listItem->delete();
			return response()->json(Helper::makeResponse(null,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function updateItemStatus(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' 	  => 'required|numeric',
			'product_id'  => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$listItem = ListProduct::whereListId($request->list_id)->whereProductId($request->product_id)->first();

		if (isset($listItem) && !empty($listItem)) {
			$listItem->checked = !$listItem->checked;
			$listItem->save();
			return response()->json(Helper::makeResponse(null,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List not found',200,false));
	}

	public function addBundleToList(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' 		=> 'required|numeric',
			'coupon_id' 	=> 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$input = $request->all();
		$data = array();

		$list = Ulist::find($input['list_id']);
		$coupon = Coupon::find($input['coupon_id']);

		if(isset($list) && isset($coupon) && !empty($list) && !empty($coupon)){

			// $coupon_list = CouponList::updateOrCreate([
			// 	'list_id' 		=> $input['list_id'],
			// 	'coupon_id' 	=> $input['coupon_id'],
			// ],[
			// 	'list_id' 		=> $input['list_id'],
			// 	'coupon_id' 	=> $input['coupon_id'],
			// ]);

			$coupon_list = CouponList::create([
				'list_id' 		=> $input['list_id'],
				'coupon_id' 	=> $input['coupon_id'],
			]);

			$data['coupon_list_id'] = $coupon_list->id;

			return response()->json(Helper::makeResponse($data,null,null,200,true));
		}

		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','List or coupon not found',200,false));
	}

	public function deleteListBundle(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'list_id' 	  => 'numeric',
			'coupon_id'  => 'numeric',
			'list_coupon_id'  => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		// $listCoupon = CouponList::whereListId($request->list_id)->whereCouponId($request->coupon_id)->first();
		$listCoupon = CouponList::whereId($request->list_coupon_id)->first();
		if (isset($listCoupon) && !empty($listCoupon)) {
			$listCoupon->delete();
			return response()->json(Helper::makeResponse(null,null,null,200,true));
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Record not found',200,false));
	}

	public function addMixAndMatchItemToList(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'coupon_list_id' => 'required|numeric',
		]);

		if ($validator->fails()) {
			return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}
		$input = $request->all();
		$coupon_list = CouponList::find($input['coupon_list_id']);
		if(isset($coupon_list) && isset($input['products']) && !empty($coupon_list) && !empty($input['products'])){
			CouponListProduct::where('coupon_list_id', $input['coupon_list_id'])->delete();
			foreach ($input['products'] as $product) {
				$coupon_list_product = CouponListProduct::create([
					'coupon_list_id' => $input['coupon_list_id'],
					'product_id' 	 => $product['product_id'],
					'quantity'       => $product['quantity']??0,
					'type'       	 => $product['type'],
				]);
			}
			return response()->json(Helper::makeResponse(null,null,null,200,true));
		}

		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Coupon list record or products not found',200,false));
	}

	public function getBundleData($coupons=NULL)
	{
		$data = array();
		if(count($coupons) > 0){
			foreach ($coupons as $key => $coupon) {
	          $temp_coupon = array();
	          $products = array();
	          $mix_n_match_products_added_to_list = array();
	          $bundle_products = array();
	          $temp_coupon['coupon_id'] = $coupon->id;
	          $temp_coupon['list_coupon_id'] = $coupon->pivot->id;
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

	          // Mix and Match selected products if any exist in list
	          if($coupon->coupon_type == 'mix_and_match'){
	          	$mix_n_match_list_products = CouponListProduct::where('coupon_list_id', $coupon->pivot->id)->get();
	            if(isset($mix_n_match_list_products) && count($mix_n_match_list_products) > 0){
	              foreach ($mix_n_match_list_products as $mml_product) {
	              	$mml_temp = array();
	                $mml_temp['product_id'] = $mml_product->product_id;
	                $mml_temp['quantity'] = $mml_product->quantity;
	                $mml_temp['type'] = $mml_product->type;

	                $mix_n_match_products_added_to_list[] = $mml_temp;
	              }
	            }
	          }
	          $temp_coupon['mix_n_match_products_added_to_list'] = $mix_n_match_products_added_to_list;

	          $data[] = $temp_coupon;
	        }
		}
		return $data;
	}

	public function getRegularProductsData($products=null)
	{
		$data = array();
		if(count($products) > 0){
			foreach ($products as $key => $product) {
				$couponIds = array();
				$product['is_promotional'] = false;
            	$product['related_coupons'] = $couponIds;

            	// Check related coupons
	            $coupons = $product->coupons;
	            if(isset($coupons) && count($coupons) > 0){
	                foreach ($coupons as $coupon) {
	                    if($coupon->isActive()){
	                        $couponIds[] = $coupon->id;
	                    }
	                }
	                if(count($couponIds) > 0){
	                    $product['is_promotional'] = true;
	                }
	                $product['related_coupons'] = $couponIds;
	            }
	            if(isset($product['coupons'])) { unset($product['coupons']); }

            	$data[] = $product;
			}
		}
		return $data;
	}
}

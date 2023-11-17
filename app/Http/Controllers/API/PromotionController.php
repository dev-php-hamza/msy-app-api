<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Helper;
use App\Product;
use App\Promotion;
use App\ProductPromotion;
use App\Country;
use App\CouponProduct;
use App\Bundle;
use Validator;


class PromotionController extends Controller
{
  public function index(Request $request)
  {
    // $validator = Validator::make($request->all(), [
    //   'location' => 'required|string|max:255',
    // ]);

    // if ($validator->fails()) {
    //   return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    // }

    // $data = array();
    // $product_count = 0;
    // $promotions = Promotion::latest()->get();
    // if (count($promotions) > 0) {
    //   foreach ($promotions as $key => $promotion) {
    //     $product_count = count($promotion->products);
    //     if($product_count > 0){
    //       $promotion_products = $promotion->products;
    //       foreach ($promotion_products as $key => $product) {
    //         $images = $product->images()->latest()->get();
    //         $temp = array();
    //         $image_temp = array();
    //         $temp['promo_id'] = $promotion->id;
    //         $temp['product_id'] = $product->id;
    //         $temp['name'] = $product->desc; 
    //         $temp['old_price'] = $product->unit_retail;
    //         $temp['new_price'] = $product->pivot->sale_price;
    //         if(count($images) > 0){
    //           foreach ($images as $key => $image) {
    //             $image_temp[] = $image->getImage($product->upc);
    //           }
    //         }
    //         $temp['images'] = $image_temp;
    //         $data['products'][] = $temp;
    //       }
    //     }
    //   }
    //   return response()->json(Helper::makeResponse($data,null,null,200,true));
    // }else{
    //   return response()->json(Helper::makeResponse(null,'Unprocessable Entity','promotions not found',200,false));
    // }

    $validator = Validator::make($request->all(), [
      'country' => 'required|string|max:3',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    }

    $data = array();
    $data['promotions'] = array();

    $product_count = 0;
    $input = $request->all();

    /*find country id first from country code*/
    $country = Country::select('id')->whereCountryCode($input['country'])->first();
    if (isset($country) && !empty($country) ) {
      $promotions = $country->promotions()->Active()->get();
    }

    if ( isset($promotions) && count($promotions) > 0) {
      foreach ($promotions as $key => $promotion) {
        $temp = array();
          $temp['id']          = $promotion->id;
          $temp['title']       = $promotion->title;
          $temp['type']        = $promotion->type;
          $temp['description'] = $promotion->description;
          $temp['country_id']  = $promotion->country_id;
          $temp['start_date']  = $promotion->start_date;
          $temp['end_date']    = $promotion->end_date;
          $temp['image']       = $promotion->getImage();
          $temp['products']    = array();

        $promotion_products  = $promotion->products;
        $product_count = count($promotion_products);
        if($product_count > 0) {
          $tempProduct = array();
          foreach ($promotion_products as $key => $product) {
            $temp['products'][] = $this->makeProductCard($product, $tempProduct);
          }
        }
        $data['promotions'][]  = $temp; // Storing Promotional Products
      }
    }

    return response()->json(Helper::makeResponse($data,null,null,200,true));
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
    $promotion = Promotion::find($request->id);
    if(isset($promotion)){
      $detail = array();
      $products = array();
      $detail['promo_id'] = $promotion->id;
      $detail['title'] = $promotion->title; 
      $detail['type'] = $promotion->type; 
      $detail['start_date'] = (isset($promotion->start_date) && !empty($promotion->start_date))?date("d F Y", strtotime($promotion->start_date)):'';
      $detail['end_date'] = (isset($promotion->end_date) && !empty($promotion->end_date))?date("d F Y", strtotime($promotion->end_date)):'';
      $detail['description'] = $promotion->description;
      $detail['image'] = $promotion->getImage();

      if(isset($promotion->products) && count($promotion->products) > 0){
        $promotion_products = $promotion->products;
        foreach ($promotion_products as $key => $product) {
          $images = $product->images()->latest()->get();
          $temp = array();
          $image_temp = array();
          $temp['product_id'] = $product->id;
          $temp['name'] = $product->desc; 
          $temp['regular_retail'] = $product->regular_retail;
          $temp['unit_retail'] = $product->unit_retail;
          if(count($images) > 0){
            foreach ($images as $key => $image) {
              $image_temp[] = $image->getImage($product->upc);
            }
          }
          $temp['images'] = $image_temp;
          $products[] = $temp;
        }
      }
      $data['promo_detail'] = $detail;
      $data['products'] = $products;
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }else{
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','promotion not found',200,false));
    }
  }

  public function getBundles(Request $request)
  {

    $validator = Validator::make($request->all(), [
      'id' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $promotion = Promotion::find($request->id);
    if(isset($promotion)){
      $detail = array();
      $coupons = array();
      $detail['promo_id'] = $promotion->id;
      $detail['title'] = $promotion->title; 
      $detail['type'] = $promotion->type; 
      $detail['start_date'] = (isset($promotion->start_date) && !empty($promotion->start_date))?date("d F Y, h:i A", strtotime($promotion->start_date)):'';
      $detail['end_date'] = (isset($promotion->end_date) && !empty($promotion->end_date))?date("d F Y, h:i A", strtotime($promotion->end_date)):'';
      $detail['description'] = $promotion->description;
      $detail['image'] = $promotion->getImage();

      if(isset($promotion->coupons) && count($promotion->coupons) > 0){
        $promotion_coupons = $promotion->coupons()->Active()->get();
        foreach ($promotion_coupons as $key => $coupon) {
          $temp_coupon = array();
          $products = array();
          $bundle_products = array();
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

          $coupons[] = $temp_coupon;
        }
      }
      // $data['promo_detail'] = $detail;
      // $data['coupons'] = $coupons;
      $detail['coupons'] = $coupons;
      $data = $detail;
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }else{
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','promotion not found',200,false));
    }
  }

  public function promotionalProductDetail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'promotion_id' => 'required|numeric',
      'product_id'   => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $promoProduct = ProductPromotion::wherePromotionId($request->promotion_id)->whereProductId($request->product_id)->first();
    if (isset($promoProduct)) {
      $product   = Product::find($promoProduct->product_id);
      $promotion = Promotion::whereId($promoProduct->promotion_id)->first();
      $data = array();
      $data['id'] = $product->id;
      $data['name'] = $product->desc;
      $data['regular_retail'] = $product->regular_retail;
      $data['unit_retail'] = $product->unit_retail;
      $data['size'] = $product->size;
      $data['promo_id'] = $promotion->id;
      $data['promo_title'] = $promotion->title;
      $data['promo_start_date'] = $promotion->start_date;
      $data['promo_end_date'] = $promotion->end_date;
      $data['promo_description'] = $promotion->description;

      $images = $product->images()->latest()->get();
      $image_temp = array();
      if(count($images) > 0){
        foreach ($images as $key => $image) {
          $image_temp[] = $image->getImage($product->upc);
        }
      }
      $data['images'] = $image_temp;

      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }
    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Promotion or Product not found',200,false));
    
  }

  private function makeProductCard($product, $tempProduct)
  {
    $tempProduct['id']            = $product->id;
    $tempProduct['upc']           = $product->upc;
    $tempProduct['name']          = $product->desc;
    $tempProduct['size']          = $product->size;
    $tempProduct['item_packing']  = $product->item_packing;
    $tempProduct['old_price']     = $product->unit_retail;
    $tempProduct['new_price']     = $product->pivot->sale_price;
    $tempProduct['unit_retail']   = $product->unit_retail;
    $tempProduct['regular_retail'] = $product->regular_retail;
    
    $images = $product->images()->latest()->get();
    $image_temp = array();
    if(count($images) > 0){
      foreach ($images as $key => $image) {
        $image_temp[] = $image->getImage($product->upc);
      }
    }
    $tempProduct['images'] = $image_temp;
    return $tempProduct;
  }
}
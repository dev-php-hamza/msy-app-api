<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Helper;
use App\Product;
use App\Promotion;
use App\Coupon;
use App\Country;
use App\CouponUsers;

class HomeController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth:api')->except('guestHome');
  }

  public function guestHome(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'country' => 'required|string|max:3',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    }

    $data = array();
      $data['promotions'] = array();
      $data['coupons']    = array();

    $product_count = 0;
    $input = $request->all();

    /*find country id first from country code*/
    $country = Country::select('id')->whereCountryCode($input['country'])->first();
    if (isset($country) && !empty($country) ) {
      $promotions = $country->promotions()->Active()->get();
      $coupons    = $country->coupons()->Active()->get();
    }

    if ( isset($promotions) && count($promotions) > 0) {
      foreach ($promotions as $key => $promotion) {
        $temp = array();
          $temp['id']          = $promotion->id;
          $temp['title']       = $promotion->title;
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

    // Getting Coupons data
    if ( isset($coupons) && count($coupons) > 0) {
      foreach ($coupons as $key => $coupon) {
        $temp = array();
          $temp['id'] = $coupon->id;
          $temp['title'] = $coupon->title;
          $temp['short_description'] = $coupon->short_description;
          $temp['description'] = $coupon->description;
          $temp['country_id']  = $coupon->country_id;
          $temp['start_date']  = $coupon->start_date;
          $temp['end_date'] = $coupon->end_date;
          $temp['image']    = $coupon->getImage();
          $temp['active']   = false;
          $temp['barcode']  = $coupon->barcode;
          $temp['products'] = array();

        $coupon_products  = $coupon->products;
        $product_count = count($coupon_products);
        if ($product_count > 0 ) {
          $tempProduct = array();
          foreach ($coupon_products as $key => $product) {
            $temp['products'][]    = $this->makeProductCard($product, $tempProduct);
          }
        }

        $data['coupons'][] = $temp;
      }
    }
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function index(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'country' => 'required|string|max:3',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    }

    $data = array();
    $data['slider_promotions'] = array();
    $data['products'] = array();
    $data['coupons'] = array();
    $product_count = 0;

    $input = $request->all();

    /*find country id first from country code*/
    $country = Country::select('id')->whereCountryCode($input['country'])->first();
    if (isset($country) && !empty($country) ) {
      $promotions = $country->promotions()->Active()->get();
      $coupons    = $country->coupons()->Active()->get();
    }

    if ( isset($promotions) && count($promotions) > 0) {
      foreach ($promotions as $key => $promotion) {
        $p_temp = array();
        $p_temp['promo_id'] = $promotion->id;
        $p_temp['promo_image'] = '';
        if(isset($promotion->image) && !empty($promotion->image)){
          $p_temp['promo_image'] = $promotion->getImage();
        }
        $data['slider_promotions'][] = $p_temp; // Storing Promotions
        $product_count = count($promotion->products);
        if($product_count > 0){
          $promotion_products = $promotion->products;
          foreach ($promotion_products as $key => $product) {
            $images = $product->images()->latest()->get();
            $temp = array();
            $image_temp = array();
            $temp['promo_id'] = $promotion->id;
            $temp['product_id'] = $product->id;
            $temp['name'] = $product->desc; 
            $temp['old_price'] = $product->unit_retail;
            $temp['new_price'] = $product->pivot->sale_price;
            if(count($images) > 0){
              foreach ($images as $key => $image) {
                $image_temp[] = $image->getImage($product->upc);
              }
            }
            $temp['images'] = $image_temp;
            $data['products'][] = $temp; // Storing Promotional Products
          }
        }
      }
    }

    // Getting Coupons data
    if ( isset($coupons) && count($coupons) > 0) {
      $user = Auth::user();
      foreach ($coupons as $key => $coupon) {
        $temp = array();
        $temp['coupon_id'] = $coupon->id;
        $temp['title'] = $coupon->title;
        $temp['short_description'] = $coupon->short_description;
        $temp['end_date'] = $coupon->end_date;
        $temp['image']    = $coupon->getImage();
        $couponUser = CouponUsers::whereCouponId($coupon->id)->whereUserId($user->id)->first();
        $temp['active'] = (count($couponUser) > 0)?(bool)$couponUser->active:false;
        $temp['barcode'] = $coupon->barcode;
        $data['coupons'][] = $temp;
      }
    }
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function home(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'country' => 'required|string|max:3',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    }

    $data = array();
    $data['promotions'] = array();
    // $data['coupons']    = array();

    $product_count = 0;
    $input = $request->all();

    /*find country id first from country code*/
    $country = Country::select('id')->whereCountryCode($input['country'])->first();
    if (isset($country) && !empty($country) ) {
      $promotions = $country->promotions()->Active()->get();
      // $coupons    = $country->coupons()->Active()->get();
    }

    if ( isset($promotions) && count($promotions) > 0) {
      foreach ($promotions as $key => $promotion) {
        if(is_null($promotion->type) || $promotion->type == 'product'){
          $temp = array();
          $temp['id']          = $promotion->id;
          $temp['title']       = $promotion->title;
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
    }

    // Getting Coupons data
    // if ( isset($coupons) && count($coupons) > 0) {
    //   $user = Auth::user();
    //   foreach ($coupons as $key => $coupon) {
    //     $temp = array();
    //       $temp['id'] = $coupon->id;
    //       $temp['title'] = $coupon->title;
    //       $temp['short_description'] = $coupon->short_description;
    //       $temp['description'] = $coupon->description;
    //       $temp['country_id']  = $coupon->country_id;
    //       $temp['start_date']  = $coupon->start_date;
    //       $temp['end_date'] = $coupon->end_date;
    //       $temp['image']    = $coupon->getImage();
    //       $couponUser       = CouponUsers::whereCouponId($coupon->id)->whereUserId($user->id)->first();
    //       $temp['active'] = (count($couponUser) > 0)?(bool)$couponUser->active:false;
    //       $temp['barcode'] = $coupon->barcode;
    //       $temp['products'] = array();

    //     $coupon_products  = $coupon->products;
    //     $product_count = count($coupon_products);
    //     if ($product_count > 0 ) {
    //       $tempProduct = array();
    //       foreach ($coupon_products as $key => $product) {
    //         $temp['products'][]    = $this->makeProductCard($product, $tempProduct);
    //       }
    //     }

    //     $data['coupons'][] = $temp;
    //   }
    // }
    return response()->json(Helper::makeResponse($data,null,null,200,true));
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

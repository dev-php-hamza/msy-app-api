<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Helper;
use App\Product;
use App\Offer;
use Validator;


class OfferController extends Controller
{
  public function getOffersByLocation(Request $request)
  {
    $data = array();
    $product_count = 0;
    $offers = Offer::all();
    if (count($offers) > 0) {
      foreach ($offers as $key => $offer) {
        $product_count = count($offer->products);
        if($product_count > 0){
          $offerArr = array();
          $offerArr['name'] = $offer->name;
          $offer_products = $offer->products;
          foreach ($offer_products as $key => $product) {
            $images = $product->images;
            $temp = array();
            $image_temp = array();
            $temp['name'] = $product->desc; 
            $temp['old_price'] = $product->unit_retail;
            $temp['new_price'] = $product->unit_retail - 5;
            if(count($images) > 0){
              foreach ($images as $key => $image) {
                $image_temp[] = url('/').'/product/images/'.$product->upc.'/'.$image->file_name;
              }
            }
            $temp['images'] = $image_temp;
            $offerArr['products'][] = $temp;
          }
          $data['offers'][] = $offerArr;
        }

      }
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }else{
      return response()->json(Helper::makeResponse(null,'empty',['offer'=>'offers does not exist'],200,false));
    }
  }
}

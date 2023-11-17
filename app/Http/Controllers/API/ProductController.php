<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Http\Controllers\Controller;
use App\Helper;
use App\Product;
use App\Promotion;
use App\ListProduct;
use App\ProductPromotion;
use App\ProductStore;
use App\Country;
use App\Category;
use Validator;
use App\AlternateCodes;
use App\SubDepartment;
use App\Traits\ProductSearchAdvance;

class ProductController extends Controller
{
  use ProductSearchAdvance;
  
  public function getProductsByLocation(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'location' => 'required|string|max:255',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'ValidationFailed',$validator->errors(),422,false));
    }

    $columns = Schema::getColumnListing('products');
    $input = $request->all();
    $loc_name = $input['location'];
    $columnName = strtolower(str_replace(' ','_',$loc_name));

    $json = array();
    $foundColumn = (in_array($columnName,$columns)) ? true : false;
    if ($foundColumn) {
      $products = Product::all('upc','desc','size','item_packing',$columnName);
      $data['products'] = (count($products)) ?  $products : -1;
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }else{
      return response()->json(Helper::makeResponse(null,'InvalidLocation',['location'=>$loc_name.' does not exist'],404,false));
    }
  }

  public function getByBarCode(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'barcode' => 'required|numeric',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $is_promo = 0;
    $country = Country::where('country_code', $request->country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    // UPC A/ EAN 13 conversion method
    $barcode = $request->barcode;
    if (strlen($barcode) >= 9 || strlen($barcode) <= 13) {
      $barcode = substr($barcode, 0, -1);
      $barcode = str_pad($barcode,13,"0",STR_PAD_LEFT);
    }

    /*// EAN 8 conversion method
    if(strlen($barcode) == 8){

    }*/

    // First round of search with 13 digit UPC
    $product = Product::whereUpc($barcode)->where('country_id', $country->id)->first();

    // Next round of UPC conversion into (Bakery PLU Code); if no product is found
    if (count($product) < 1) {

      // MASSY BAKERY conversion method
      $bakeryBarcode = $this->massyBakeryBarcode($barcode);
      if (!isset($bakeryBarcode)) {
        return response()->json(Helper::makeResponse($data,null,null,419,false));
      }

      $product = Product::whereUpc($bakeryBarcode)->where('country_id', $country->id)->first();
    }

    // Now checking barcode against ALT CODES; if no product is found against (UPC A/EAN 13) or (PLU Code) 
    /*if(count($product) < 1){
      $alt_code = AlternateCodes::where('alternate_code', $request->barcode)->where('country_id', $country->id)->first();
      if(count($alt_code) > 0){
        $product = Product::whereUpc($alt_code->master_upc)->where('country_id', $country->id)->first();
      }
    }*/

    if (count($product) > 0) {
      
      $curr_date = date('Y-m-d');
      $promotions = Promotion::where('country_id', $country->id)->whereDate('start_date', '<=', $curr_date)->whereDate('end_date', '>=', $curr_date)->get();

      $promoIds = array();
      foreach ($promotions as $key => $promotion) {
        array_push($promoIds, $promotion->id);
      }

      if (count($promoIds) > 0 ) {
        $prodPromotion = ProductPromotion::whereIn('promotion_id', $promoIds)->whereProductId($product->id)->first();
      }

      $temp = array();
      if (isset($prodPromotion) && !empty($prodPromotion) && count($prodPromotion)> 0) {
        $temp['type']       = 'promotion';
        $temp['promo_id']   = $prodPromotion->promotion_id;
        $temp['product_id'] = $product->id;
        $temp['name']       = $product->desc;
        $temp['old_price']  = $product->unit_retail;
        $temp['new_price']  = $prodPromotion->sale_price;
      }else{
        $temp['type']       = 'product';
        $temp['promo_id']   = '';
        $temp['product_id'] = $product->id;
        $temp['name']       = $product->desc;
        $temp['old_price']  = $product->unit_retail;
        $temp['new_price']  = '';
      }

      $images = $product->images()->latest()->get();
      $image_temp = array();
      if(count($images) > 0){
        foreach ($images as $key => $image) {
          $image_temp[] = $image->getImage($product->upc);
        }
      }
      $temp['images'] = $image_temp;
      $data['products'][] = $temp;
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }
    return response()->json(Helper::makeResponse($data,null,null,419,false));
  }

  public function searchProduct_Old(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'         => 'required|string|max:255',
      'list_id'      => 'required|numeric',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $product_recs = array();
    // $related = array();
    // $unrelated = array();
    $productIds = array();
    $input = $request->all();
    $strUpper = strtoupper($input['term']);
    $strLower= strtolower($input['term']);
    $country = Country::where('country_code', $request->country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    // $products = Product::where('desc','LIKE','%'.$strUpper.'%')->where('country_id', $country->id)->orWhere('desc','LIKE','%'.$strLower.'%')->where('country_id', $country->id)->get();

    $products = Product::where('desc','LIKE','%'.$strUpper.'%')->where('is_searchable', 1)->where('country_id', $country->id)->orWhere('desc','LIKE','%'.$strLower.'%')->where('is_searchable', 1)->where('country_id', $country->id)->get();
    
    if (isset($products) && !empty($products) && count($products) > 0) {

      // Get related products if any exist
      $listItems = ListProduct::whereListId($input['list_id'])->get();
      if (isset($listItems) && !empty($listItems) && count($listItems) > 0) {
        foreach ($listItems as $key => $item) {
          array_push($productIds, $item->product_id);
        }
      }

      // Build response items array
      foreach ($products as $key => $product) {
        $temp = array();
        $is_present = 0;
        if (in_array($product->id, $productIds)) {
          // array_push($related, $product);
          $is_present = 1;
        }/*else{
          array_push($unrelated, $product);
        }*/
        $temp['id'] = $product->id;
        $temp['name'] = $product->desc;
        $temp['size'] = $product->size;
        $temp['unit_retail'] = $product->unit_retail;
        $temp['is_present'] = $is_present;
        $product_recs[] = $temp;
      }
      
      $data['products'] = $product_recs;
      // $data['related']   = $related;
      // $data['unrelated'] = $unrelated;
    }
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function searchProduct_Old2(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'         => 'required|string|max:255',
      'list_id'      => 'required|numeric',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $product_recs = array();

    $productIds = array();
    $input = $request->all();
    $country = Country::where('country_code', $request->country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    $termChunks = explode(' ', $input['term']);

    $term = '';
    $productIds = array();
    for ($index=0; $index < count($termChunks); $index++) {
      if ($index >= 1) {
        if (strlen($termChunks[$index]) > 3) {
          $makeTerm = '';
          for ($inner=0; $inner <= $index; $inner++) { 
            $makeTerm .= $termChunks[$inner].' ';
          }
          $term = trim($makeTerm);
        }else{
          continue;
        }
      }else{
        $term = $termChunks[$index];
      }

      // $productIds = $this->getProductIds($term, $country, $input);
    }

    $productIds = $this->getProductIds($term, $country, $input);

    if (isset($productIds) && !empty($productIds) && count($productIds) > 0) {
      // Get related products if any exist
      $listProductIds = array();
      $listItems = ListProduct::whereListId($input['list_id'])->get();
      if (isset($listItems) && !empty($listItems) && count($listItems) > 0) {
        foreach ($listItems as $key => $item) {
          array_push($listProductIds, $item->product_id);
        }
      }

      $products = Product::whereIn('id', $productIds)->get();
      // Build response items array
      foreach ($products as $key => $product) {
        $temp = array();
        $is_present = 0;

        if (in_array($product->id, $listProductIds)) {
          $is_present = 1;
        }

        $temp['id'] = $product->id;
        $temp['name'] = $product->desc;
        $temp['size'] = $product->size;
        $temp['unit_retail'] = $product->unit_retail;
        $temp['is_present'] = $is_present;
        $product_recs[] = $temp;
      }

      $data['products'] = $product_recs;
      
    }

    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function searchProduct(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'         => 'required|string|max:255',
      'list_id'      => 'nullable|numeric',
      'category_id'  => 'nullable|numeric',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $product_recs = array();

    $productIds = array();
    $input = $request->all();
    $country = Country::where('country_code', $request->country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    // $termChunks = explode(' ', $input['term']);

    $term = $input['term'];
    // $productIds = array();
    // for ($index=0; $index < count($termChunks); $index++) {
    //   if ($index >= 1) {
    //     if (strlen($termChunks[$index]) > 3) {
    //       $makeTerm = '';
    //       for ($inner=0; $inner <= $index; $inner++) { 
    //         $makeTerm .= $termChunks[$inner].' ';
    //       }
    //       $term = trim($makeTerm);
    //     }else{
    //       continue;
    //     }
    //   }else{
    //     $term = $termChunks[$index];
    //   }

    //   // $productIds = $this->getProductIds($term, $country, $input);
    // }

    $productIds = $this->getProductIds($term, $country, $input);

    if (isset($productIds) && !empty($productIds) && count($productIds) > 0) {
      // Get related products if any exist
      $listProductIds = array();
      if(isset($input['list_id']) && !empty($input['list_id']) && $input['list_id'] != ''){
        $listItems = ListProduct::whereListId($input['list_id'])->get();
        if (isset($listItems) && !empty($listItems) && count($listItems) > 0) {
          foreach ($listItems as $key => $item) {
            array_push($listProductIds, $item->product_id);
          }
        }
      }

      $products = Product::whereIn('id', $productIds)->get();
      // Build response items array
      foreach ($products as $key => $product) {
        $temp = array();
        $is_present = 0;

        if (in_array($product->id, $listProductIds)) {
          $is_present = 1;
        }

        $temp['id'] = $product->id;
        $temp['name'] = $product->desc;
        $temp['size'] = $product->size;
        $temp['unit_retail'] = $product->unit_retail;
        $temp['is_scalable'] = $product->is_scalable;

        $images = $product->images()->latest()->get();
        $image_temp = array();
        if(count($images) > 0){
          foreach ($images as $key => $image) {
            $image_temp[] = $image->getImage($product->upc);
          }
        }
        $temp['images'] = $image_temp;

        $temp['is_present'] = $is_present;
        $product_recs[] = $temp;
      }

      $data['products'] = $product_recs;
      
    }

    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function getProductIds($term, $country, $input)
  {
    $data = array();
    $productIds = array();
    $strUpper = strtoupper($term);
    $strLower = strtolower($term);
    $catProdIds  = array();
    if (isset($input['category_id']) && $input['category_id'] != '') {
      $catProdObj  = Category::with('products')->whereId($input['category_id'])->first();
      foreach ($catProdObj->products as $key => $product) {
        $catProdIds[] = $product->id;
      }
    }
    /* get product query builder */
    $query = $this->productQuery($term);

    if (count($catProdIds) > 0) {
      $products = $query->where('is_searchable', 1)->where('country_id', $country->id)->whereIn('id', $catProdIds)->get();
    }else{
      $products = $query->where('is_searchable', 1)->where('country_id', $country->id)->get();
    }

    // if (isset($products) && !empty($products) && count($products) > 0) {

    //   // Get related products if any exist
    //   $listItems = ListProduct::whereListId($input['list_id'])->get();
    //   if (isset($listItems) && !empty($listItems) && count($listItems) > 0) {
    //     foreach ($listItems as $key => $item) {
    //       array_push($productIds, $item->product_id);
    //     }
    //   }
    // }

    // Build response items array
    foreach ($products as $key => $product) {
      array_push($productIds, $product->id);
    }
    
    // $data['products'] = $product_recs;
    // $data['related']   = $related;
    // $data['unrelated'] = $unrelated;



    return $productIds;
  }

  public function getDetail(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'id' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $product = Product::find($request->id);
    if (count($product) > 0 ) {
      $data = array();
      $data['id'] = $product->id;
      $data['desc'] = $product->desc;
      $data['size'] = $product->size;
      $data['item_packing'] = $product->item_packing;
      $data['unit_retail']  = $product->unit_retail;

      $images = $product->images()->latest()->get();
      $image_temp = array();
      if(count($images) > 0){
        foreach ($images as $key => $image) {
          $image_temp[] = $image->getImage($product->upc);
        }
      }
      $data['images'] = $image_temp;
      $data['stockInfo'] = array();
      $productStores = $product->stores;
      if (count($productStores) > 0) {
        foreach ($productStores as $key => $store) {

          $data['stockInfo'][] = $this->extractStoreData($store);
        }
      }
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }
    return response()->json(Helper::makeResponse(null,'Unprocessable Entity','product not found',200,false));
  }

  private function extractStoreData($store)
  {
    $temp = array();
    $temp['id'] = $store->id;
    $temp['name'] = $store->name;
    $temp['storecode'] = $store->storecode;
    $temp['address_line_one'] = $store->address_line_one;
    $temp['address_line_two'] = $store->address_line_two;
    $temp['country'] = $store->country->name;
    $temp['location'] = $store->location->name;
    $temp['lat'] = $store->lat;
    $temp['lon'] = $store->lon;
    $temp['quantity'] = $store->pivot->quantity;
    return $temp;
  }

  private function massyBakeryBarcode($ReqBarcode)
  {
    $bakeryBarcode = null;
    if (strlen($ReqBarcode) >= 9 || strlen($ReqBarcode) <= 13) {
      $bakeryBarcode = substr($ReqBarcode, 0, 8);
      $bakeryBarcode = str_pad($bakeryBarcode,13,"0",STR_PAD_RIGHT);
    }
    return $bakeryBarcode;
  }

  public function getStock(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'product_id' => 'required|numeric',
      'store_id' => 'required|numeric',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $data = array();
    $input = $request->all();
    
    $product_id = $input['product_id'];
    $store_id = $input['store_id'];

    $data['product_id'] = $product_id;
    $data['quantity'] = 0;

    $product_store_data = ProductStore::where('product_id', $product_id)->where('store_id', $store_id)->first();
    if(isset($product_store_data) && !empty($product_store_data) && count($product_store_data) > 0){
      $data['quantity'] = $product_store_data->quantity;
    }
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function searchProductAdvance(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'         => 'required|string|max:255',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $term         = $request->term;
    $country_code = $request->country_code;

    $country = Country::where('country_code', $country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    $data = $this->getSearchedData($term, $country->id);
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function searchProductAdvanceNew(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'         => 'required|string|max:255',
      'country_code' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    // pagination start
    $offset = 0;
    if(isset($request['offset']) && !empty($request['offset'])){
        $offset = $request['offset'];
    }

    $limit = 5;
    if(isset($request['limit']) && !empty($request['limit'])){
        $limit = $request['limit'];
    }
    // pagination end

    $term         = $request->term;
    $country_code = $request->country_code;

    $country = Country::where('country_code', $country_code)->first();

    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }
    
    $data = $this->getSearchedData($term, $country->id, $offset, $limit);
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }

  public function searchProductAdvanceBySubDepart(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'term'              => 'required|string|max:255',
      'sub_department_id' => 'required',
      'country_code'      => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    }

    $term              = $request->term;
    $sub_department_id = $request->sub_department_id;
    $country_code      = $request->country_code;

    $country = Country::where('country_code', $country_code)->first();
    if(!isset($country)|| empty($country)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Country not found',200,false));
    }

    $countryId = $country->id;

    $subDepartment = SubDepartment::whereId($sub_department_id)->first();
    if(!isset($subDepartment)|| empty($subDepartment)){
      return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Sub Department not found',200,false));
    }

    $categoryIds = $subDepartment->categories->filter(function ($category, $key) use($countryId){
      return $category->country_id == $countryId;
    })->pluck('id')->toArray();

    $data = $this->getSearchedDataBySub($term, $countryId, $categoryIds);
    return response()->json(Helper::makeResponse($data,null,null,200,true));
  }
}

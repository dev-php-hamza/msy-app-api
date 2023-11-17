<?php

namespace App\Http\Controllers\API\Picker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ProductSearchAdvance;
use App\Helper;
use App\Product;
use App\Country;
use App\Category;
use Validator;

class ProductController extends Controller
{
    use ProductSearchAdvance;
    public function searchProduct(Request $request)
    {
      $validator = Validator::make($request->all(), [
        'term'         => 'required|string|max:255',
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
      }

      $productIds = $this->getProductIds($term, $country, $input);

      if (isset($productIds) && !empty($productIds) && count($productIds) > 0) {
        $products = Product::whereIn('id', $productIds)->get();

        // Build response items array
        foreach ($products as $key => $product) {
          $temp = array();

          $temp['id'] = $product->id;
          $temp['name'] = $product->desc;
          $temp['size'] = $product->size;
          $temp['unit_retail'] = $product->unit_retail;

          $images = $product->images()->latest()->get();
          $image_temp = array();
          if(count($images) > 0){
            foreach ($images as $key => $image) {
              $image_temp[] = $image->getImage($product->upc);
            }
          }
          $temp['images'] = $image_temp;
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

      if (count($catProdIds) > 0) {
        $products = Product::where('desc','LIKE','%'.$strUpper.'%')->where('is_searchable', 1)->where('country_id', $country->id)->whereIn('id', $catProdIds)->orWhere('desc','LIKE','%'.$strLower.'%')->where('is_searchable', 1)->where('country_id', $country->id)->whereIn('id', $catProdIds)->get();
      }else{
        $products = Product::where('desc','LIKE','%'.$strUpper.'%')->where('is_searchable', 1)->where('country_id', $country->id)->orWhere('desc','LIKE','%'.$strLower.'%')->where('is_searchable', 1)->where('country_id', $country->id)->get();
      }

      // Build response items array
      foreach ($products as $key => $product) {
        array_push($productIds, $product->id);
      }

      return $productIds;
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
      
      $data = $this->getSearchedData($term, $country->id, true);
      return response()->json(Helper::makeResponse($data,null,null,200,true));
    }
}

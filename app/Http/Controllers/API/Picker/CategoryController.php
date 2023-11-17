<?php

namespace App\Http\Controllers\API\Picker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Validator;
use App\Country;
use App\Category;
use App\SubDepartment;

class CategoryController extends Controller
{
    public function categoriesList(Request $request)
    {
        $validator = Validator::make($request->all(), [
          'sub_department_id' => 'required|numeric',
          'country_code'      => 'required|string'
        ]);

        if ($validator->fails()) {
          return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $data = array();
        $subDepartment = SubDepartment::whereId($request->sub_department_id)->first();
        $country       = Country::whereCountryCode($request->country_code)->first();

        if (isset($subDepartment) && !empty($subDepartment) && !is_null($subDepartment)) {
            if (isset($country) && !empty($country) && !is_null($country)) {
                $categoryObjs = \DB::table('categories as cat')
                                        ->join( 'category_subdepartments as cat_sub', 'cat.id', '=', 'cat_sub.category_id' )
                                        ->join( 'sub_departments as sub_depart', 'cat_sub.sub_department_id', '=', 'sub_depart.id' )
                                        ->where( 'cat.country_id', $country->id)
                                        ->where( 'sub_depart.id', $subDepartment->id)
                                        ->select('cat.id as cat_id','cat.number as cat_number','cat.name as cat_name')
                                        ->get();
                $data['categories'] = $this->extractCategoriesList($categoryObjs);
                return response()->json(Helper::makeResponse($data,null,null,200,true));
            }
            return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Country not found',200,false));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Sub Department not found',200,false));
    }

    public function extractCategoriesList($categories)
    {
        $output = array();
        foreach ($categories as $key => $category) {
            $temp = array();
            $temp['id']     = $category->cat_id;
            $temp['number'] = $category->cat_number;
            $temp['name']   = $category->cat_name;

            $output[] = $temp;
        }

        return $output;
    }
    
    public function categoryProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $category = Category::whereId($request->category_id)->first();
        if (isset($category) && !empty($category) && !is_null($category)) {
            $products = $category->products()->with('images')->whereCountryId($category->country_id)->whereIsSearchable(1)->orderBy('desc')->get();
            $data['products'] = $this->extractProducts($products);
            return response()->json(Helper::makeResponse($data,null,null,200,true));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Category not found',200,false));

    }

    public function extractProducts($products)
    {
        $output = array();
        foreach ($products as $key => $product) {
            $temp       = array();
            $tempImages = array();

            $temp['id']   = $product->id;
            $temp['upc']  = $product->upc;
            $temp['name'] = $product->desc;
            $temp['size'] = $product->size;
            $temp['item_packing']  = $product->item_packing;
            $temp['price']         = $product->unit_retail;
            $temp['country_id']    = $product->country_id;
            $temp['is_searchable'] = $product->is_searchable;
            $temp['has_images']    = $product->has_images;

            $images = $product->images()->latest()->get();
            if(count($images) > 0){
                if ($product->has_images) {
                    foreach ($images as $key => $image) {
                        $tempImages[] = $image->file_name;
                    }
                }else{
                    foreach ($images as $key => $image) {
                        $tempImages[] = $image->getImage($product->upc);
                    }
                }
            }
            
            $temp['images'] = $tempImages;

            $output[] = $temp;
        }

        return $output;
    }
}

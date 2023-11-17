<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Validator;
use App\SubDepartment;
use App\Country;
use App\Category;

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
                                        ->orderBy('cat.name')
                                        ->get();
                $data['categories'] = $this->extractCategoriesList($categoryObjs, $country->id);
                return response()->json(Helper::makeResponse($data,null,null,200,true));
            }
    		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Country not found',200,false));
    	}
    	return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Sub Department not found',200,false));
    }

    public function extractCategoriesList($categories, $countryId)
    {
        $output = array();
        foreach ($categories as $key => $category) {
            $temp     = array();
            $category = Category::whereId($category->cat_id)->whereCountryId($countryId)->first();
            // $products = $category->products()->with('images')->whereCountryId($countryId)->orderBy('desc')->get();
            $products = $category->products()->whereCountryId($countryId)->count();

            // $productsArr = $this->extractProducts($products);
            if (isset($products) && !empty($products) && $products > 0) {
                $temp['id']       = $category->id;
                $temp['number']   = $category->number;
                $temp['name']     = $category->name;
                // $temp['products'] = $this->extractProducts($products);
                $output[] = $temp;
            }
        }

        return $output;
    }

    public function categoriesProductBySubDepart(Request $request)
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
                                        ->orderBy('cat.name')
                                        ->get();
                $data['categories'] = $this->extractCategoryWithProduct($categoryObjs, $country->id);
                return response()->json(Helper::makeResponse($data,null,null,200,true));
            }
            return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Country not found',200,false));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Sub Department not found',200,false));
    }

    public function extractCategoryWithProduct($categories, $countryId)
    {
        $output = array();
        foreach ($categories as $key => $category) {
            $temp     = array();
            $category = Category::whereId($category->cat_id)->whereCountryId($countryId)->first();
            $products = $category->products()->with('images')->whereCountryId($countryId)->orderBy('desc')->get();

            $productsArr = $this->extractProducts($products);
            if (isset($productsArr) && !empty($productsArr) && $productsArr != '') {
                $temp['id']       = $category->id;
                $temp['number']   = $category->number;
                $temp['name']     = $category->name;
                $temp['products'] = $productsArr;
                $output[] = $temp;
            }
        }

        return $output;
    }

    public function categoryProductsOld(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $category = Category::whereId($request->category_id)->first();
        if (isset($category) && !empty($category) && !is_null($category)) {
            $products = $category->products()->with('images')->whereCountryId($category->country_id)->orderBy('desc')->get();
            $data['products'] = $this->extractProducts($products);
            return response()->json(Helper::makeResponse($data,null,null,200,true));
        }
        return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Category not found',200,false));

    }

    public function categoryProducts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
        }

        $offset = 0;
        if(isset($request['offset']) && !empty($request['offset'])){
            $offset = $request['offset'];
        }

        $limit = 5;
        if(isset($request['limit']) && !empty($request['limit'])){
            $limit = $request['limit'];
        }

        $category = Category::whereId($request->category_id)->first();
        if (isset($category) && !empty($category) && !is_null($category)) {
            $products = $category->products()->with('images')->whereCountryId($category->country_id)->orderBy('desc')->skip($offset)->take($limit)->get();
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
            $couponIds = array();

            $temp['id']   = $product->id;
            $temp['upc']  = $product->upc;
            $temp['desc'] = $product->desc;
            $temp['size'] = $product->size;
            $temp['item_packing']  = $product->item_packing;
            $temp['unit_retail']   = $product->unit_retail;
            $temp['regular_retail']   = $product->regular_retail;
            $temp['is_scalable']   = $product->is_scalable;
            $temp['country_id']    = $product->country_id;
            $temp['is_searchable'] = $product->is_searchable;
            $temp['is_promotional'] = false;
            $temp['related_coupons'] = $couponIds;
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

            // Check related coupons
            $coupons = $product->coupons;
            if(isset($coupons) && count($coupons) > 0){
                foreach ($coupons as $coupon) {
                    if($coupon->isActive()){
                        $couponIds[] = $coupon->id;
                    }
                }
                if(count($couponIds) > 0){
                    $temp['is_promotional'] = true;
                }
                $temp['related_coupons'] = $couponIds;
            }

            $output[] = $temp;
        }

        return $output;
    }
}

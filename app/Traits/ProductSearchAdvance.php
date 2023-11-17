<?php

namespace App\Traits;
use App\Category;
use App\Product;
use App\CategoryProduct;
use App\Helper;

trait ProductSearchAdvance
{
  /**
 * Get data from category and products
 *
 * @param  string $term
 * @param  string $countryId
 * @return Collection
 */
  function getSearchedData($term, $countryId, $offset=null, $limit=null ,$picker=false)
  {
    $output = array();
    $output['categories'] = array();
    $output['products']   = array();

    $strUpper = strtoupper($term);
    $strLower = strtolower($term);

    // Find All Country And Term Specific Categories
    // $categories = Category::has('products')->where('name','LIKE','%'.$strUpper.'%')->whereCountryId($countryId)->orWhere('name','LIKE','%'.$strLower.'%')->whereCountryId($countryId)->orderBy('name')->get();
    $categories = Category::has('products')->where(function ($query) use ($strUpper, $strLower){
      return $query->where('name','LIKE','%'.$strUpper.'%')
                   ->orWhere('name','LIKE','%'.$strLower.'%');
    })->whereCountryId($countryId)->orderBy('name')->get();

    $categoryTemp = array();
    foreach ($categories as $key => $category) {
      $subDepartAndDepart = Helper::getSubDepartAndDepartment($category, $countryId);
      if (count($subDepartAndDepart) && isset($subDepartAndDepart) && !empty($subDepartAndDepart) && $subDepartAndDepart != '') {
        $tempArr = array();
        $tempArr['id']         = $category->id;
        $tempArr['name']       = $category->name;
        $tempArr['number']     = $category->number;
        $tempArr['country_id'] = $category->country_id;
        $tempArr['sub_department_id']   = $subDepartAndDepart['sub_department_id'];
        $tempArr['sub_department_name'] = $subDepartAndDepart['sub_department_name'];
        $tempArr['department_id']   = $subDepartAndDepart['department_id'];
        $tempArr['department_name'] = $subDepartAndDepart['department_name'];

        $categoryTemp[] = $tempArr;
      }
    }

    // Find All Country And Term Specific Products
    // $products = Product::where('desc','LIKE','%'.$strUpper.'%')->where('is_searchable', 1)->where('country_id', $countryId)->orWhere('desc','LIKE','%'.$strLower.'%')->where('is_searchable', 1)->where('country_id', $countryId)->get();
    $query = $this->productQuery($term);
    if (is_null($offset) || is_null($limit)) { 
      $products = $query->has('categories')->where('country_id', $countryId)->where('is_searchable', 1)->orderBy('desc')->get();
    }else{
      $products = $query->has('categories')->where('country_id', $countryId)->where('is_searchable', 1)->orderBy('desc')->skip($offset)->take($limit)->get();
    }

    // $products = Product::has('categories')->where(function ($query) use ($strUpper, $strLower){
    //   return $query->where('desc','LIKE','%'.$strUpper.'%')
    //                ->orWhere('desc','LIKE','%'.$strLower.'%');
    // })->where('country_id', $countryId)->orderBy('desc')->get();

    $productTemp = array();
    foreach ($products as $key => $product) {

      $productPriceColumn = ($picker)?'price':'unit_retail';
      
      $temp = array();
      $temp['id']   = $product->id;
      $temp['name'] = $product->desc;
      $temp['size'] = $product->size;
      $temp[$productPriceColumn] = $product->unit_retail;
      $temp['regular_retail'] = $product->regular_retail;
      $temp['is_scalable']       = $product->is_scalable;
      $temp['is_promotional'] = false;
      $temp['related_coupons'] = array();

      $images = $product->images()->latest()->get();
      $image_temp = array();
      if(count($images) > 0){
        foreach ($images as $key => $image) {
          $image_temp[] = $image->getImage($product->upc);
        }
      }
      $temp['images'] = $image_temp;

      $catTemp = array();
      foreach ($product->categories as $key => $category) {
        $tempArr = array();
        $tempArr['id']         = $category->id;
        $tempArr['name']       = $category->name;
        $tempArr['number']     = $category->number;
        $tempArr['country_id'] = $category->country_id;

        $catTemp[] = $tempArr;
      }
      $temp['categories'] = $catTemp;

      // Check related coupons
      $couponIds = array();
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

      $productTemp[] = $temp;
    }

    $output['categories'] = $categoryTemp;
    $output['products']   = $productTemp;

    return $output;
  }

  function getSearchedDataBySub($term, $countryId, $categoryIds, $picker=false)
  {
    $output = array();
    // $output['categories'] = array();
    $output['products']   = array();

    $strUpper = strtoupper($term);
    $strLower = strtolower($term);

    // Find All Country And Term Specific Categories

    // $categories = Category::has('products')->whereIn('id', $categoryIds)->where(function ($query) use ($strUpper, $strLower){
    //   return $query->where('name','LIKE','%'.$strUpper.'%')
    //                ->orWhere('name','LIKE','%'.$strLower.'%');
    // })->where('country_id', $countryId)->orderBy('name')->get();

    // $categoryTemp = array();
    // $tempCatIds   = array(); 
    // foreach ($categories as $key => $category) {
    //   $tempArr = array();
    //   $tempArr['id']         = $category->id;
    //   $tempArr['name']       = $category->name;
    //   $tempArr['number']     = $category->number;
    //   $tempArr['country_id'] = $category->country_id;

    //   $tempCatIds[] = $category->id;

    //   $categoryTemp[] = $tempArr;
    // }

    $productIds  = CategoryProduct::whereIn('category_id', $categoryIds)->get(['product_id'])->toArray();

    $query    = $this->productQuery($term);
    $products = $query->whereIn('id', $productIds)->where('country_id', $countryId)->orderBy('desc')->get();

    $productTemp = array();
    foreach ($products as $key => $product) {

      $productPriceColumn = ($picker)?'price':'unit_retail';
      
      $temp = array();
      $temp['id']   = $product->id;
      $temp['name'] = $product->desc;
      $temp['size'] = $product->size;
      $temp[$productPriceColumn] = $product->unit_retail;
      $temp['is_scalable']       = $product->is_scalable;

      $images = $product->images()->latest()->get();
      $image_temp = array();
      if(count($images) > 0){
        foreach ($images as $key => $image) {
          $image_temp[] = $image->getImage($product->upc);
        }
      }
      $temp['images'] = $image_temp;

      $catTemp = array();
      foreach ($product->categories as $key => $category) {
        $tempArr = array();
        $tempArr['id']         = $category->id;
        $tempArr['name']       = $category->name;
        $tempArr['number']     = $category->number;
        $tempArr['country_id'] = $category->country_id;

        $catTemp[] = $tempArr;
      }
      $temp['categories'] = $catTemp;

      $productTemp[] = $temp;
    }

    // $output['categories'] = $categoryTemp;
    $output['products']   = $productTemp;

    return $output;
  }

  function productQuery($term)
  {
    $termChunks = explode(' ', $term);
    
    $query = Product::query();
    foreach ($termChunks as $key => $value) {
      $query = $query->where('desc','LIKE','%'.$value.'%');
    }
    return $query;
  }
}

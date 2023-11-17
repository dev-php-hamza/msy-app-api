<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helper;
use App\ProductImage;
use App\Product;
use App\Country;

class ProductImageController extends Controller
{
    public function testUtility()
    {
      $countries = Country::all();
      return view('admin.product.testutility', compact('countries'));
    }

    public function testUtilitySearch(Request $request)
    {
      set_time_limit(0);
    	$this->validate($request,[
          'country_id'    => 'required',
	        'upc'           => 'required|string|min:13|max:13',
	        'generated_upc' => 'required|string',
      	]);

      $upc = $request->upc;
      $generated_upc = $request->generated_upc;

      // Get Country Code
      $country   = Country::findOrFail($request->country_id);
      $countryId = $country->id;
      $errors['error']  = '';
      $imageData        = array();

      $product   = Product::whereUpc($upc)->whereCountryId($countryId)->first();
      if(isset($product) && !empty($product) && $product != ''){
        // if (!$product->has_images) {
          $blockUpcPluCodes = config('blockupcplucodes');

          if(array_key_exists($country->country_code, $blockUpcPluCodes)){
            $bupcData = $blockUpcPluCodes[$country->country_code];
            $pluCodes = $bupcData['pluCodes'];
            $blockDepartments = $bupcData['departments'];
            
            $finalUpc = Helper::validateUpc($product, $upc, $countryId, $pluCodes, $blockDepartments);

            if (!is_null($finalUpc)) {
              $response = $this->getImagesUpcItemDb($generated_upc);
              if (!empty($response['error']) && $response['error'] != '') {
                $errors['error'] = $response['error'];
              }else{
                // check product title also for more clarification
                $proDescCheck = Helper::validateProductDesc($product->desc, $response['title']);
                if($proDescCheck){
                  $product->is_product_match = 1;
                  $product->save();
                } 
                // $imageData = $response['images'];
                // Remove all broken images
                $imageData = Helper::removeBrokenImages($response['images']);
                // dd($imageData);
              }
            }else{
              $errors['error'] = 'Provided UPC is fall into our Own created UPC, You can not get images from UpcItemDb API.' ;
            }
          }else{
            $errors['error'] = 'Sorry! There is no configuration is available for selected country. If Error persist, Please contact with service provider.';
          }
        // }else{
        //   $productImages = $product->images;
        //   foreach ($productImages as $key => $productImage) {
        //     $imageData[] = $productImage->getImage($product->upc);
        //   }
        // }
      }else{
        $errors['error'] = 'Your Entered UPC is not found!';
      }

      $countries = Country::all();
      return view('admin.product.testutility', ['upc' => $upc, 'generated_upc' => $generated_upc ,'images' => $imageData, 'errors' => $errors, 'countries' => $countries]);
    }

    public function removeProductImage(ProductImage $productImage)
    {
      $productId = $productImage->product_id;
      $fileToBeDeleted = Helper::getFileNameForDelete($productImage->file_name);
      if ($fileToBeDeleted != null) {
        $fileToBeDeleted = public_path().'/product/images/'.$fileToBeDeleted;
        @unlink($fileToBeDeleted);
      }

      $productImage->delete();

      $imagesCount = ProductImage::whereProductId($productId)->count();
      if ($imagesCount == 0) {
        $product = Product::findOrFail($productId);
        $product->has_images = 0;
        $product->save();
      }

      return redirect()->back();
    }

    public function getImagesUpcItemDb($generated_upc)
    {
      $upcitemdb = config('services.upcitemdb');

      $user_key = $upcitemdb['user_key'];
      $endpoint = $upcitemdb['url'];

      $ch = curl_init();
       // if your client is old and doesn't have our CA certs
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "user_key:".$user_key,
        "key_type: 3scale"
      ));

      // HTTP GET
      curl_setopt($ch, CURLOPT_POST, 0);
      curl_setopt($ch, CURLOPT_URL, $endpoint.'?upc='.$generated_upc);
      $response = curl_exec($ch);
      $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

      $data      = json_decode($response, true);

      $output = array();
      $output['error']  = '';
      $output['images'] = array();

      if ($httpcode != 200){
        switch ($httpcode) {
          case 429:
            $output['error'] = $data['message'];
            break;
          
          default:
            $output['error'] = $data['message'];
            break;
        }
      }else{
          $items = $data['items'];
          if (isset($items) && !empty($items) && $items != '') {
            $output['title']  = $items[0]['title'];
            $output['images'] = $items[0]['images'];
          }
        curl_close($ch);
      }
      return $output;
    }

    public function removeBrokenImagesOld($imagesArr)
    {
      // dd($imagesArr);
      $result = array();

      // multi handle
      $ch = curl_init();
      foreach ($imagesArr as $index => $link) {
        // echo($link);
        // echo "<br>";
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $response = curl_exec($ch);
        // dd($response);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // dd($httpCode);
        if ($httpCode == 200) {
          $result[] = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        }
      }
      // dd($result);
      curl_close($ch);
      return $result;
    }
}

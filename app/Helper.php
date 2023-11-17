<?php
namespace App;
use App\Product;

class Helper
{

  // if (!function_exists('makeResponse')) {
    public static function makeResponse($data=null,$errorType,$error=null,$statusCode,$successStatus)
    {
      $resTemplate = array();
      $resTemplate = ['status'=>$successStatus,'code'=>$statusCode];
      if (isset($data)) {$resTemplate['data'] = $data;}
      if (isset($error)) {
        $resTemplate['errors']['error'] = $error;
        $resTemplate['errors']['type'] = $errorType;
      }
      return $resTemplate;
    }
  // }

    public static function getFileNameForDelete($term)
    {
      if(is_null($term) || empty($term) || $term == ''){
        return $term;
      }

      $fileName = null;
      if (strpos($term, 'http') !== false) {
          $urlChunk = explode('images/', $term);
          if (isset($urlChunk[1])) {
            $fileName = $urlChunk[1];
          }
      }

      return $fileName;
    }

    /**
     *
     * @param string $string string to be encrypted/decrypted
     * @param string $action what to do with this? e for encrypt, d for decrypt
     */
    public static function crypt( $string, $action = 'e' ) {
      $secret_key = config('app.appIntegration.secret_key'); // Secret key
      $secret_iv  = config('app.appIntegration.secret_iv'); // Secret iv

      $output = false;
      $encrypt_method = "AES-256-CBC";
      $key = hash( 'sha256', $secret_key );
      $iv  = substr( hash( 'sha256', $secret_iv ), 0, 16 );

      if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
      }
      else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
      }

      return $output;
    }

    public static function getCheckDigit($upc)
    {
      $output = null;

      $evenNumsCount = 0;
      $oddNumsCount  = 0;

      $upcLength = strlen($upc);

      if ($upcLength === 13) {
        for ($i=0; $i < $upcLength ; $i++) { 
          if ($i%2 === 0) {
            $oddNumsCount  = (int)$oddNumsCount + (int)$upc[$i];
          }else{
            $evenNumsCount = (int)$evenNumsCount + (int)$upc[$i];
          }
        }
        
        $checkDigit = 0;
        $oddNum   = 3 * $oddNumsCount;
        $finalNum = $oddNum + $evenNumsCount;
        $finalNum = $finalNum % 10;
            
        if($finalNum != 0){
          $checkDigit = 10 - $finalNum;
        }

        $upc    = substr($upc, 1);
        $output = $upc.$checkDigit;
      }

      return $output;
    }

    /**
     * exclude any barcodes that starts with:-
     * 002 – Random Weight codes
     * 096 - Coupons
     * 098 - Coupons
       
     *  Also, please exclude any barcodes where the last 4 digits or last 5 digits are non-zeros AND the leading digits are ZEROs AND belongs to the following departments:
     * Department Number   Department Name
     * 300                                 DELI
     * 350                                 BAKERY
     * 400                                 PRODUCE
     * 450                                 DAIRY & CHILLED PRODUCTS
     * 550                                 MEATS & SEAFOOD
     * 740                                 PHARMACEUTICAL
     * 780                                 PHARMACY & PRESCRIPTION
     * 900                                 BOTTLES AND SHELLS
     * 905                                 CMC MEAT
     * 910                                 CMC PET MEAT
     * 915                                 CCK DELI
     * @param string $upc
     * @param integer $countryId
     * @param array $blockUpcPluCodes
     * @param array $blockDepartments
     * @return null | upc
     */
    public static function validateUpc($product, $upc, $countryId, $blockUpcPluCodes, $blockDepartments)
    {
      $flag = false;
      $finalUpc = null;
      foreach($blockUpcPluCodes as $key => $pluCode){
        $strpos = strpos($upc, $pluCode);
        if($strpos !== false && $strpos === 0){
          $flag = true;
          break;
        }
      }
      
      if($flag === false){
        $last4Digits   = substr($upc, 9);
        $last5Digits   = substr($upc, 8);
        $last4DigitSum = self::getSum($last4Digits);
        $last5DigitSum = self::getSum($last5Digits);
        if(($last4DigitSum != 0) || ($last5DigitSum !=0) ){

          $first9Digits   = substr($upc, 0, 9);
          $first8Digits   = substr($upc, 0, 8);
          $first9DigitSum = self::getSum($first9Digits);
          $first8DigitSum = self::getSum($first8Digits);
          if (($first9DigitSum == 0) || ($first8DigitSum == 0)) {
            // Check upc department with blockDepartments List
            // if yes then simply return null
            // else return upc

            $flagDepartment = false;
            $categories = $product->categories;
            if (count($categories) >0  && isset($categories) && !empty($categories) && $categories != '') {
              $subdepartment = $categories[0]->subDepartments;
              if (count($subdepartment) >0 && isset($subdepartment) && !empty($subdepartment) && $subdepartment != '') {
                $departments   = $subdepartment[0]->departments;
                if (count($departments) >0 && isset($departments) && !empty($departments) && $departments != '') {
                  $departmentNum = $departments[0]->number;

                  foreach ($blockDepartments as $key => $departmentConf) {
                    if ($departmentConf['number'] === $departmentNum) {
                      $flagDepartment = true;
                      break;
                    }
                  }
                }
                if ($flagDepartment === false) {
                  $finalUpc = $upc;
                }
              }
            }
          }else{
            $finalUpc = $upc;
          }
        }
      }

      return $finalUpc;
    }

    public static function getSum($str)
    {
      $sum = 0;
      for($i=0; $i < strlen($str); $i++){
        $sum += (int) $str[$i];
      }

      return $sum;
    }

    public static function getSubDepartAndDepartment($categoryObj, $countryId)
    {
      $output = array();
      $subdepartment = $categoryObj->subDepartments;
      if (count($subdepartment) >0 && isset($subdepartment) && !empty($subdepartment) && $subdepartment != '') {
        $subdepartment = $subdepartment[0];
        $departments   = $subdepartment->departments()->whereCountryId($countryId)->get();
        if (count($departments) >0 && isset($departments) && !empty($departments) && $departments != '') {
          $department = $departments[0];
          $output['sub_department_id']   = $subdepartment->id;
          $output['sub_department_name'] = $subdepartment->name;
          $output['department_id']   = $department->id;
          $output['department_name'] = $department->name;
        }
      }

      return $output;
    }

    /**
     *
     * @param object $product
     * @return boolean $output
     */
    public static function couponAndPromotions($productObj)
    {
      $output     = false;

      $coupons    = $productObj->coupons;
      $promotions = $productObj->promotions;
      if ((count($coupons) > 0 && isset($coupons) && !empty($coupons) && $coupons != '') || (count($promotions) > 0 && isset($promotions) && !empty($promotions) && $promotions != '')) {
        $output = true;
      }

      return $output;
    }

    /**
     *
     * @param string $str
     * @return string $str i.e:- 'KG' | 'OZ' | G 'CT' etc
     */
    public static function getMeasuringUnit($str)
    {
      $str = preg_replace('/\d+/', '', $str);
      return trim($str);
    }

    /**
     *
     * @param string $str
     * @return string $str i.e:- 'KG' | 'OZ' | G 'CT' etc
     */
    public static function getSubPrice($unit, $unitPrice)
    {
      $subprice = 0;
      
      if ($unit === 'KG' || (stripos($unit, 'KG') !== false)) {
        $subprice = $unitPrice/2.20462;
      }
      if ($unit === 'G') {
        $subprice = $unitPrice/453.59237;               
      }
      if (stripos($unit, 'OZ') !== false) {
        $subprice = $unitPrice/16;
      }
      
      return $subprice;
    }

    /**
     *
     * @param array $urls images
     * @return array valid images urls 
     */
    public static function removeBrokenImages($urls)
    {
        // Options
        $curlOptions = [
            CURLOPT_HEADER => false,
            CURLOPT_NOBODY => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
        ];

        // Init multi-curl
        $mh = curl_multi_init();
        $chArray = [];

        foreach ($urls as $key => $url) {
            // Init of requests without executing
            $ch = curl_init($url);
            curl_setopt_array($ch, $curlOptions);

            $chArray[$key] = $ch;

            // Add the handle to multi-curl
            curl_multi_add_handle($mh, $ch);
        }

        // Execute all requests simultaneously
        $active = null;
        do {
            $mrc = curl_multi_exec($mh, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);

        while ($active && $mrc == CURLM_OK) {
            // Wait for activity on any curl-connection
            if (curl_multi_select($mh) === -1) {
              usleep(100);
            }

            while (curl_multi_exec($mh, $active) == CURLM_CALL_MULTI_PERFORM);
        }

        // Close the resources
        foreach ($chArray as $ch) {
          curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        // Access the results
        $result = [];
        foreach ($chArray as $key => $ch) {
            // Get response
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpCode == 200) {
              $result[] =  curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            }
        }

        return $result;
    }

    public function getProductDesc2Words($sysDesc)
    {
      $strChunks   = explode(" ", $sysDesc);
      $first2Words = '';

      for($i=0; $i < 3; $i++){
        $word = $strChunks[$i];

        if(strlen($word) > 1)
          $first2Words .= $strChunks[$i].' ';
      }

      return $first2Words;
    }

    /**
     *
     * @param string $sysDesc
     * @param string $apiDesc
     * @return boolean
     */
    public static function validateProductDesc($sysDesc, $apiDesc)
    {
      $sysDesc = strtolower($sysDesc);
      $apiDesc = strtolower($apiDesc);

      $strChunks = explode(" ", $sysDesc);

      $foundWords = 0;
      foreach($strChunks as $key => $word){
        $word = trim($word);
        if (isset($word) && $word != '') {
          if(strpos($apiDesc, $word) !== false){
            $foundWords++;
          }
        }
      }

      // Calculate found Word percentage
      // threshold is minimum 60%
      $sysDescCount = count($strChunks);

      $percentage = $foundWords / $sysDescCount * 100;
      if($percentage >= 60){
        return true;
      }
      return false;
    }
}

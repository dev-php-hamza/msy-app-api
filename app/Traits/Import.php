<?php

namespace App\Traits;
use Excel;
use Exception;

trait Import
{
  /**
 * Get data from excel file
 *
 * @param  string $fileName
 * @param  bool $isEmpty true|false
 * @param  string $headingType slugged|numeric
 * @return Collection
 */
  function getExcelData($fileName, $isEmpty, $headingType)
  {
  	$arr = array();
    config()->set('excel.import.heading', $headingType);
    config()->set('excel.import.ignoreEmpty', $isEmpty);
    $path = 'public/import_files/'.$fileName;
    $data = collect(Excel::load($path)->get());
    if ($headingType === 'slugged') {
    	$arr['header'] = $data[0]->toArray();
    	$arr['products'] = $data->splice(1)->toArray();
    }else{
    	$arr['header'] = $data[7]->toArray();
    	$arr['products'] = $data->splice(8)->toArray();
    }
    
    return $arr;
  }

  public function getExcelDataSimpleFile($fileName , $modelName)
  {
    $arr = array();
    $path = 'public/import_files/'.$fileName;
    config()->set('excel.import.heading', 'numeric');
    try {
      $data = Excel::load($path)->get();
      $arr['status'] = true;
      // dd($data);
      $arr['header'] = $data[0]->toArray();
      $arr[$modelName] = $data->splice(1)->toArray();
      // dd($data[0]->toArray());
      // if ($headingType === 'slugged') {
      //   $arr['header'] = $data[0]->toArray();
      //   $arr['products'] = $data->splice(1)->toArray();
      // }else{
      //   $arr['header'] = $data[7]->toArray();
      //   $arr['products'] = $data->splice(8)->toArray();
      // }
      
      return $arr;
    } catch (Exception $e) {
      $arr['status']  = false;
      $arr['message'] = $e->getMessage();
      return $arr;
    }
  }

  public function getCsvData($fileName , $modelName)
  {
    $arr = array();
    $path = public_path().'/import_files/'.$fileName;
    try {
      $csvData = array_map('str_getcsv', file($path));
      $arr['status'] = false;
      if(isset($csvData[0])){
        $arr['header'] = $csvData[0];
        $arr[$modelName] = array_splice($csvData, 1);
        if (count($csvData[0]) > 1) {
          if (count($arr[$modelName]) > 0) {
            $arr['status'] = true;
          }else{
            $arr['message'] = 'File Data not Found';
          }
        }
        else{
          $arr['message'] = 'File Headers not Found';
        }
      }else{
        $arr['message'] = 'Empty File';
      }
      return $arr;
    } catch (Exception $e) {
      $arr['status']  = false;
      $arr['message'] = $e->getMessage();
      return $arr;
    }
  }

  // public function getCsvDataAdvance($fileName , $modelName, $dir)
  // {
  //   $arr = array();
  //   $path = public_path().$dir.'/'.$fileName;
  //   try {
  //     $csvData = array_map('str_getcsv', file($path));
  //     $arr['status'] = true;
  //     $arr['header'] = $csvData[0];
  //     $arr[$modelName] = array_splice($csvData, 1);
  //     return $arr;
  //   } catch (Exception $e) {
  //     $arr['status']  = false;
  //     $arr['message'] = $e->getMessage();
  //     return $arr;
  //   }
  // }
}

<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use Illuminate\Http\Request;
use Excel;
use App\Traits\Import;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;
use App\Product;
use App\ProductImage;
use App\Country;
use App\Location;
use App\ProductStore;
use phpseclib\Net\SFTP;
use App\Store;
use DB;

class ProductController extends Controller
{
  use Import;

  private $serverFileNames = array('TT_Stock.csv', 'SLU_Stock.csv', 'BB_Stock.csv', 'GY_Stock.csv', 'SVG_Stock.csv');

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $products = Product::with('country', 'images')->latest()->paginate(10);
      return view('admin.product.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.product.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request,[
        'upc' => 'required|string|min:12|max:13',
        'desc' => 'required|string|max:191',
        'item_packing' => 'required|numeric',
        'unit_retail' => 'required|numeric',
        'regular_retail' => 'required|numeric',
        'country_id' => 'required|numeric',
        'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
      ]);

      $product = Product::updateOrCreate([
        'upc'        => $request->upc,
        'country_id' => $request->country_id,
        ],[
        'desc'         => $request->desc,
        'size'         => $request->size,
        'item_packing' => $request->item_packing,
        'unit_retail'  => $request->unit_retail,
        'regular_retail'  => $request->regular_retail,
      ]);

/*      if ($request->has('locations')) {
        foreach ($request->locations as $locationId => $productQty) {
        $productLocation = LocationProduct::updateOrCreate([
          'product_id'=>$product->id,'location_id'=>$locationId
          ],
          [
            'product_id'=>$product->id,'location_id'=>$locationId
          ]);

          if (!$productLocation->wasRecentlyCreate) {
            $productLocation->quantity = $productLocation->quantity + $productQty;
            $productLocation->save();
          }
        }
      }*/

      if ($request->hasFile('file')) {
        if (! File::isDirectory(public_path("product/images/".$request->upc))) {
          $path = 'product/images/'.$request->upc;
          File::makeDirectory(public_path().'/'.$path, 777,true);
          chmod(public_path().'/'.$path, 0777);
        }

        foreach ($request->file as $file) {
          $filename = $file->getClientOriginalName();
          $filename = time().$filename;
          $file->move('product/images/'.$request->upc, $filename);

          $filename = url('/').'/product/images/'.$request->upc.'/'.$filename;

          $productImage = ProductImage::updateOrCreate(['product_id'=>$product->id, 'file_name' => $filename],[
            'product_id' => $product->id,
            'file_name'  => $filename,
          ]);
        }

        $product->has_images = 1;
        $product->save();
      }

      return redirect()->route('products.index')->with('message','Product has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $country = Country::find($product->country_id)->first();
        $countryName = $country->name;
        $productStores  = $product->stores; 
        return view('admin.product.details', ['product'=>$product, 'countryName'=>$countryName, 'productStores'=>$productStores]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::find($id);
        $countries = Country::all();
        return view('admin.product.edit', ['product'=>$product, 'countries'=>$countries]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      // $country_id = 1;
      $this->validate($request,[
        'upc' => 'required|string|min:12|max:13',
        'desc' => 'required|string|max:191',
        'item_packing' => 'required|numeric',
        'unit_retail' => 'required|numeric',
        'regular_retail' => 'required|numeric',
        'country_id' => 'required|numeric',
        'file.*' => 'image|mimes:jpeg,png,jpg,gif,svg',
      ]);

      $product = Product::whereId($id)->update([
        'country_id' => $request->country_id,
        'desc'         => $request->desc,
        'size'         => $request->size,
        'item_packing' => $request->item_packing,
        'unit_retail'  => $request->unit_retail,
        'regular_retail'  => $request->regular_retail,
      ]);

      /*foreach ($request->locations as $locationId => $productQty) {
        $productLocation = LocationProduct::updateOrCreate([
          'product_id'=>$id,'location_id'=>$locationId
          ],
          [
            'product_id'=>$id,'location_id'=>$locationId
          ]);

          if (!$productLocation->wasRecentlyCreate) {
            $productLocation->quantity = $productQty;
            $productLocation->save();
          }
      }*/

      if ($request->hasFile('file')) {
        if (! File::isDirectory(public_path("product/images/".$request->upc))) {
          $path = 'product/images/'.$request->upc;
          File::makeDirectory(public_path().'/'.$path, 777,true);
          chmod(public_path().'/'.$path, 0777);
        }

        foreach ($request->file as $file) {
          $filename = $file->getClientOriginalName();
          $filename = time().$filename;
          $file->move('product/images/'.$request->upc, $filename);

          $filename = url('/').'/product/images/'.$request->upc.'/'.$filename;
          
          $productImage = ProductImage::updateOrCreate(['product_id'=>$request->id, 'file_name' => $filename],[
            'product_id' => $id,
            'file_name' => $filename,
          ]);
        }

        $product = Product::find($id);
        $product->has_images = 1;
        $product->save();
      }

      return redirect()->route('products.index')->with('message','Product has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId)
    {
      try {
        $product = Product::where('id',$productId)->first();
        $path = public_path("product/images/".$product->upc);
        if(File::isDirectory($path)){
          $files = glob($path.'/*');
          foreach ($files as $file) {
            unlink($file);
          }
          rmdir($path);
        }

        $product->delete();
        return redirect()->route('products.index')->with('message','Product has been deleted successfully!');
      } catch (\Exception $e) {
        echo "data you are looking for is not found";
      }
    }

    public function exportExcel()
    {
      // $path = public_path().'\\import_files/products.xlsx';
      // $products = Excel::load($path)->get()->toArray();
      $products = Product::all()->toArray();
      // dd($products);
      Excel::create('export_products_excel_example', function($excel) use ($products) {
        //set set title
        $excel->setTitle('Product Dump');
        $excel->setCreator('Massy Store Admin');
        $excel->setDescription('use for office purpose');
        $excel->sheet('Sheet1', function($sheet) use ($products) {
          $sheet->setOrientation('landscape');
          $sheet->fromArray($products, NULL, 'A1');
        });

      })->download('xlsx');
    }

    public function showImportDailyProductForm()
    {
      $countries = Country::all();
      return view('admin.import_daily_products_form', compact('countries'));
    }

    public function importDailyProductsExcel(Request $request)
    {
      set_time_limit(0);
      // $file = $request->file('import_file');
      // $filename = $file->getClientOriginalName();
      // $file->move('import_files', $filename);
      // $csvData = $this->getCsvData($filename, 'products');
      // excel file have lot of data in one file 91776 records are found
      $file_ext = $request->file('import_file')->getClientOriginalExtension();
      if ($file_ext === 'xls' || $file_ext === 'xlsx' || $file_ext === 'csv') {
        $file = $request->file('import_file');
        $filename = $file->getClientOriginalName();
        $file->move('import_files', $filename);

        if ($file_ext === 'csv') {
          $fileData = $this->getCsvData($filename, 'products');
        }else{
          $fileData = $this->getExcelDataSimpleFile($filename, 'products');
        }
        
        // $products = array_chunk($fileData['products'], 10);
        if ($fileData['status']) {
          $fileHeader = $fileData['header'];
          /*storeIds is array having index as file index for store code found and value as store id as stores in database*/
          $storeIds = $this->getStoresByCountry($request->country_id, $fileHeader);
          // $this->storeProducts($products[0], $request->country_id, $filename, $storeIds);
          $this->storeProducts($fileData['products'], $request->country_id, $filename, $storeIds);
        }else{
          return redirect()->route('products.index')->with('error','Format issue found in file. Please choose a file that has no style applied in it. PHP library only reads data.');
        }
        return redirect()->route('products.index')->with('message','All data from file has been saved successfully!');
      }
      $this->validate($request, [
        'import_file' => 'required|mimes:xls,xlsx,csv'
      ]);
    }

    public function showImportProductViaSFTPForm()
    {
      $countries = Country::all();
      return view('admin.product.import_products_via_sftp_form', compact('countries'));
    }

    public function importProductsViaSFTP(Request $request)
    {
      set_time_limit(0);
      $this->validate($request,[
        'country_id' => 'required|numeric|exists:countries,id',
      ]);

      $country = Country::find($request->country_id);
      $this->getFilesOverSFTP($country->country_code);
      $filesInFolder = File::allFiles(public_path().'/import_files');
      foreach ($filesInFolder as $key => $path) {
        $file = pathinfo($path);
        $fileName = $file['basename'];
        $file_ext = $file['extension'];
        // $fileCheck = $country->country_code.'_Stock.xlsx';
        $fileCheck = in_array($fileName, $this->serverFileNames);
        if ($fileCheck) {
          if ($file_ext === 'csv') {
            $fileData = $this->getCsvData($fileName, 'products');
          }else{
            $fileData = $this->getExcelDataSimpleFile($fileName, 'products');
          }

          // $products = array_chunk($fileData['products'], 10);
          if ($fileData['status']) {
            $fileHeader = $fileData['header'];
            /*storeIds is array having index as file index for store code found and value as store id as stores in database*/
            $storeIds = $this->getStoresByCountry($request->country_id, $fileHeader);
            // $this->storeProducts($products[0], $country->id, $fileName, $storeIds);
            $this->storeProducts($fileData['products'], $country->id, $fileName, $storeIds);
            break;
          }
          return redirect()->route('products.index')->with('error','Format issue found in file. Please choose a file that has no style applied in it. PHP library only reads data.');
        }
        return redirect()->route('products.index')->with('message','No file found on Server');
      }
      return redirect()->route('products.index')->with('message','All data from file has been saved successfully!');
    }

    public function storeProducts($products, $countryId, $fileName, $storeIds)
    {
      // -5 is beign use to get exact header from excel file because 5 headings are same for all countries like upc, desc, size, unit retail, item packing
      // $products = array_chunk($products,10);
      // if (count($locations) === count($filteredHeader)-5) {
        foreach ($products as $key => $product) {
        // foreach ($products[14] as $key => $product) {
          try{
            if (!is_null($product[0]) && (strlen($product[0])==13) && !is_null($product[1]) ) {
              // $parseInt = (int)$product[0];
              // $upc = (string)$parseInt;
              if (!preg_match('/^\d+$/',$product[0])) {
                continue;
              }
              $productDb = Product::updateOrCreate([
                'upc'        => $product[0],
                'country_id' => $countryId,
              ],[
                'desc'         => utf8_encode(trim($product[1])),
                'size'         => (isset($product[2]))?$product[2]:0,
                'item_packing' => (isset($product[3]) && $product[3] != '')?$product[3]:0.00,
                'unit_retail'  => (isset($product[4]) && $product[4] != '')?$product[4]:0.00,
              ]);

              foreach ($storeIds['validStores'] as $fileIndex => $storeIdDb) {
                $product_quantity = (isset($product[$fileIndex]))?$product[$fileIndex]:0;

                $productStore = ProductStore::updateOrCreate([
                  'product_id' => $productDb->id,
                  'store_id'   => $storeIdDb
                ],[
                  'quantity'   => floor($product_quantity)
                ]);
              }
            }else{
              continue;
            } 
          }catch(\Exception $e){
            // store logs
          }            
        }
        $fileToBeDeleted =  public_path().'/import_files/'.$fileName;
        @unlink($fileToBeDeleted);
    }

    public function getFilesOverSFTP($countryCode)
    {
      set_time_limit(0);
      $host          = '3.215.84.51';
      $remoteDir     = 'DailyStockDump';
      $sftpServers = array(
        'TT' => array('user'=>'Trinidad', 'pass'=>'mz6Ku^&#', 'remoteDirPath'=>'/var/www/html/massyteam/Trinidad/DailyStockDump/' ),
        'LC' => array('user'=>'SLU', 'pass'=>'KQ!gpH7J', 'remoteDirPath'=>'/var/www/html/massyteam/SLU/DailyStockDump/'),
        'GY' => array('user'=>'Guyana', 'pass'=>'ZU3weMys', 'remoteDirPath'=>'/var/www/html/massyteam/Guyana/DailyStockDump/'),
        'BB' => array('user'=>'Barbados', 'pass'=>'j^D2?ev^', 'remoteDirPath'=>'/var/www/html/massyteam/Barbados/DailyStockDump/'),
        'VC' => array('user'=>'SVG', 'pass'=>'xQJ-7Daw', 'remoteDirPath'=>'/var/www/html/massyteam/SVG/DailyStockDump/'));

      $localDir = public_path().'/import_files/';
      $logFile  = public_path().'/logs/import_products.txt';

      foreach ($sftpServers as $key => $sftpServer) {
        if ($key === $countryCode) {
          $sftp = new SFTP($host);
          if (!$sftp->login($sftpServer['user'], $sftpServer['pass'])) {
            File::append($logFile, 'failed due to unathorization for user '.$sftpServer['user'].' '.now().PHP_EOL);
          }else{
            File::append($logFile,'Login successfully for user '.$sftpServer['user'].' '.now().PHP_EOL);
            $files = $sftp->nlist($remoteDir);
            foreach ($files as $key => $file) {
              if ($file == "." || $file == "..")
                continue;

              // $fileToCheck = $countryCode.'_Stock.xlsx';
              $fileToCheck = in_array($file, $this->serverFileNames);
              if ($fileToCheck) {
               if (!$sftp->get($sftpServer['remoteDirPath'].$file, $localDir.$file)) {
                 File::append($logFile,'There are some exception occure in downloading '.$file.'. Kindly make contact to your administration '.now().PHP_EOL);
               }
               File::append($logFile, $file.' has been successfully downloaded '.now().PHP_EOL);
              }
              
              /*after coping file delete file from remote server too*/
              // $sftp->delete($file); // deletes directories recursively
            }
          }
        }
      }
    }
    
    /**
     * get country stores
     *
     * @param  int  $id
     * @return array('1'=>35, '2'=>8) => index as fileIndex and value as storeIDb
     */
    private function getStoresByCountry($countryId, $fileHeader)
    {
      $storeCodesDb = array();
      $tempArr = array();
      $tempArr['validStores']   = array();
      $tempArr['missingStores'] = array();
      $stores = Store::whereCountryId($countryId)->get();
      foreach ($stores as $key => $store) {
        $storeCodesDb[$store->id] = $store->storecode;
      }

      for ($key=5; $key < count($fileHeader) ; $key++) { 
        $validStoreId = array_search($fileHeader[$key], $storeCodesDb);
        if ($validStoreId) {
          $tempArr['validStores'][$key] = $validStoreId;
        }else{
          $tempArr['missingStores'][]   = $fileHeader[$key]; 
        }
      }

      return $tempArr;
    }

    public function showSearchForm()
    {
      $countries = Country::all();
      $products = Product::latest()->paginate(10);
      return view('admin.product.searchByUpcForm',compact('countries', 'products'));
    }

    public function getProductsByUpcByCountry(Request $request)
    {
      $this->validate($request, [
        'upc'       => 'nullable|string|min:12|max:13',
        'prodName'  => 'nullable|string|max:191',
        'countryId' => 'required|numeric',
        'image'     => 'nullable|boolean',
        'searchable' => 'nullable|boolean',
      ]);

      $input = $request->all();
      $query = Product::query();

      if (isset($input['upc'])) {
        $query = $query->whereUpc($input['upc']);
      }

      if (isset($input['prodName'])) {
        $query = $query->where('desc', 'LIKE','%'.$input['prodName'].'%');
      }

      $searchable = '';
      $image = '';

      if (isset($input['image'])) {
        if ($input['image'] === "1") {
          $query = $query->has('images');
        }
        if ($input['image'] === "0") {
          $query = $query->doesnthave('images');
        }
        $image = $input['image'];
      }

      if (isset($input['searchable'])) {
        if ($input['searchable'] === "1") {
          $query = $query->where('is_searchable', 1);
        }
        if ($input['searchable'] === "0") {
          $query = $query->where('is_searchable', 0);
        }
        $searchable = $input['searchable'];
      }

      // $searchable = $input['searchable'];
      // $image = $input['image'];
      $products  = $query->whereCountryId($input['countryId'])->paginate(10);
      $countries = Country::all();
      return view('admin.product.searchByUpcForm', compact('image' ,'searchable','countries', 'products'));
    }

    /**
     * get expired products
     *
     * @param  void
     * @return array() 
     */
    public function getExpiredProducts()
    {
      $data = array();
      $countries = Country::all();
      foreach ($countries as $key => $country) {
        // $products = Product::where('country_id', $country->id)->get();
        
        $expired_store_products = DB::table('products')
        ->select('products.id')
        ->join('product_stores', 'products.id', '<>', 'product_stores.product_id')
        ->where('products.country_id', $country->id)
        ->get();

        $temp = array();
        // $temp['total_count'] = count($products); 
        // $temp['total_count_in_stores'] = count($store_products);
        $temp['total_expired'] = count($expired_store_products);
        $data[$country->country_code] = $temp; 
      }

      print '<pre>';
      print_r($data);
      print '</pre>';
    }
}

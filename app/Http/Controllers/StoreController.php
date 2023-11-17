<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Country;
use App\StoreInfo;
use App\Location;
use App\Product;
use App\Traits\Import;
use App\Helper;

class StoreController extends Controller
{
    use Import;

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
    public function index(Request $request)
    {
        $input = $request->all();
        $query = Store::query();
        if (isset($input['name']) && !empty($input['name']) && $input['name'] != '') {
            $query = $query->where('name', 'LIKE','%'.$input['name'].'%');
        }
        if (isset($input['country_id']) && !empty($input['country_id']) && $input['country_id'] != '') {
            $query = $query->where('country_id', $input['country_id']);
        }
        $stores = $query->latest()->paginate(10);
        return view('admin.store.index',compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.store.create',compact('countries'));
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
            'name'          => 'required|string|max:255',
            'addresslnOne'  => 'required|string|max:255',
            'addresslnTwo'  => 'string|max:255|nullable',
            'country_id'    => 'required|numeric',
            'location_id'   => 'required|numeric',
            // 'lat'           => 'required|string',
            // 'lon'           => 'required|string',
            'storecode'     => 'required|digits:3',
            'file.*'        => 'image|mimes:jpeg,png,jpg',
            'email'         => 'nullable|string|email|max:255',
            'phone_number'  => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'category'      => 'required|string|max:255',
            'delivery_company_name'  => 'nullable|string|max:255',
            'delivery_company_email' => 'nullable|email|max:255',
            'delivery'      => 'required|string|max:4',
            'curbside'      => 'required|string|max:4',
            'website'       => ['nullable','regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
            'sndayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'sndayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'mndayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'mndayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'tsdayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'tsdayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'wddayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'wddayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'thrsdayFrom'   => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'thrsdayTo'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'frdayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'frdayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'satrdayFrom'   => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'satrdayTo'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
        ]);

        $store = Store::create([

            'name'             => $request->name,
            'address_line_one' => $request->addresslnOne,
            'address_line_two' => $request->addresslnTwo,
            'country_id'       => $request->country_id,
            'location_id'      => $request->location_id,
            'storecode'        => $request->storecode,
            'lat'              => $request->lat,
            'lon'              => $request->lon,
            'email'            => $request->email,
            'delivery_company_name'  => $request->delivery_company_name,
            'delivery_company_email' => $request->delivery_company_email,
            'delivery'         => $request->delivery,
            'curbside'         => $request->curbside
        ]);

        $storeInfo = StoreInfo::create([

            'store_id' => $store->id,
            'phone_number' => $request->phone_number,
            'website' => $request->website,
            'primary_category' => $request->category,
            'sunday_hours_from' => $request->sndayFrom,
            'sunday_hours_to' => $request->sndayTo,
            'monday_hours_from' => $request->mndayFrom,
            'monday_hours_to' => $request->mndayTo,
            'tuesday_hours_from' => $request->tsdayFrom,
            'tuesday_hours_to' => $request->tsdayTo,
            'wednesday_hours_from' => $request->wddayFrom,
            'wednesday_hours_to' => $request->wddayTo,
            'thursday_hours_from' => $request->thrsdayFrom,
            'thursday_hours_to' => $request->thrsdayTo,
            'friday_hours_from' => $request->frdayFrom,
            'friday_hours_to' => $request->frdayTo,
            'saturday_hours_from' => $request->satrdayFrom,
            'saturday_hours_to' => $request->satrdayTo,
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$store->id.'.'.$fileExten;
            $file->move('store/images/',$fileCompName);

            $fileCompName = url('/').'/store/images/'.$fileCompName;

            $store->image = $fileCompName;
            $store->save();
        }

        return redirect()->route('stores.index')->with('message','Store has been saved successfully!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        $storeInfo = StoreInfo::where('store_id', $store->id)->first();
        $country = Country::where('id', $store->country_id)->first();
        $location = Location::where('id', $store->location_id)->first();
        $countryName = $country->name;
        $locationName = $location->name;
        return view('admin.store.details',compact('store','storeInfo','countryName','locationName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function edit(Store $store)
    {
        $storeCategories = array('Pharmacy','Supermarket', 'Hypermarket', 'Department store', 'Corporate office');
        $countries = Country::all();
        $countryLocations = Location::where('country_id',$store->country_id)->get();
        $storeInfo = StoreInfo::where('store_id',$store->id)->first();
        // dd($storeInfo);
        return view('admin.store.edit',compact('store','storeInfo','countries','countryLocations','storeCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        $this->validate($request,[
            'name'          => 'required|string|max:255',
            'storecode'     => 'required|digits:3',
            'addresslnOne'  => 'required|string|max:255',
            'addresslnTwo'  => 'string|max:255|nullable',
            'country_id'    => 'required|numeric',
            'location_id'   => 'required|numeric',
            // 'lat'           => 'required|string',
            // 'lon'           => 'required|string',
            'file.*'        => 'image|mimes:jpeg,png,jpg',
            'email'         => 'nullable|string|email|max:255',
            'phone_number'  => ['required','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'category'      => 'required|string|max:255',
            'delivery_company_name'  => 'nullable|string|max:255',
            'delivery_company_email' => 'nullable|email|max:255',
            'delivery'      => 'required|string|max:4',
            'curbside'      => 'required|string|max:4',
            'website'       => ['nullable','regex:/^((?:https?\:\/\/|www\.)(?:[-a-z0-9]+\.)*[-a-z0-9]+.*)$/'],
            'sndayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'sndayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'mndayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'mndayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'tsdayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'tsdayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'wddayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'wddayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'thrsdayFrom'   => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'thrsdayTo'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'frdayFrom'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'frdayTo'       => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'satrdayFrom'   => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
            'satrdayTo'     => ['nullable','regex:/([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?/'],
        ]);

        $fileCompName = null;
        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$store->id.'.'.$fileExten;
            if ($fileCompName != $store->image) {

                $fileToBeDeleted = Helper::getFileNameForDelete($store->image);
                $fileToBeDeleted = public_path().'/store/images/'.$fileToBeDeleted;

                @unlink($fileToBeDeleted);
                $file->move('store/images/',$fileCompName);
            }
        }

        $store->name = $request->name;
        $store->storecode = $request->storecode;
        $store->address_line_one =$request->addresslnOne ;
        $store->address_line_two =$request->addresslnTwo ;
        $store->country_id = $request->country_id;
        $store->location_id = $request->location_id;
        $store->email = $request->email;
        $store->lat = $request->lat;
        $store->lon = $request->lon;
        $store->delivery_company_name = $request->delivery_company_name;
        $store->delivery_company_email = $request->delivery_company_email;
        $store->delivery = $request->delivery;
        $store->curbside = $request->curbside;
        if (isset($fileCompName))
            $store->image = url('/').'/store/images/'.$fileCompName;
        $store->save();

        $storeInfo = StoreInfo::updateOrCreate([
            'store_id' => $store->id,
        ],[
            'store_id' => $store->id,
            'phone_number' => $request->phone_number,
            'website' => $request->website,
            'primary_category' => $request->category,
            'sunday_hours_from' => $request->sndayFrom,
            'sunday_hours_to' => $request->sndayTo,
            'monday_hours_from' => $request->mndayFrom,
            'monday_hours_to' => $request->mndayTo,
            'tuesday_hours_from' => $request->tsdayFrom,
            'tuesday_hours_to' => $request->tsdayTo,
            'wednesday_hours_from' => $request->wddayFrom,
            'wednesday_hours_to' => $request->wddayTo,
            'thursday_hours_from' => $request->thrsdayFrom,
            'thursday_hours_to' => $request->thrsdayTo,
            'friday_hours_from' => $request->frdayFrom,
            'friday_hours_to' => $request->frdayTo,
            'saturday_hours_from' => $request->satrdayFrom,
            'saturday_hours_to' => $request->satrdayTo,
        ]);

        return redirect()->route('stores.index')->with('message','Store has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Store  $store
     * @return \Illuminate\Http\Response
     */
    public function destroy(Store $store)
    {
        $fileToBeDeleted = Helper::getFileNameForDelete($store->image);
        
        @unlink(public_path().'/store/images/'.$fileToBeDeleted);
        $store->delete();
        return redirect()->route('stores.index')->with('message', 'Store has been deleted successfully!');
    }

    public function showImportStoresForm()
    {
        return view('admin.store.import_store_form');
    }

    public function importStoresExcel(Request $request)
    {
      // excel file have lot of data in one file 91776 records are found
      set_time_limit(0);
      $checkFile = $this->checkExcelFile($request);
      if ($checkFile) {
        $file = $request->file('import_file');
        $filename = $file->getClientOriginalName();
        $file->move('import_files', $filename);
        $fileData = $this->getExcelDataSimpleFile($filename, 'stores');
        $countries = $this->getCountries();
        $locations = $this->getLocations();
        
        foreach ($fileData['stores'] as $key => $store) {
            if (in_array($store[4], $countries)) {
                if (!is_null($store[0])) {
                    // get country id based on country/ Region
                    // country code found then get country id
                    $countryId = array_search($store[4], $countries);

                    // get location id based on locality
                    $locationId = array_search($store[3], $locations);

                    if ($locationId != 0) {
                        // create store based on location id
                        $storeDb = $this->updateOrCreateStore($store, $locationId, $countryId);
                        $storeInfo = $this->updateOrCreateStoreInfo($storeDb,$store);
                    }
                    /*else{
                        // create new location
                        $location = Location::create(['name' => $store[3], 'country_id' => $countryId ]);
                        $storeDb = $this->updateOrCreateStore($store, $location->id, $countryId);
                        $storeInfo = $this->updateOrCreateStoreInfo($storeDb,$store);
                    }*/
                }
            }
            /*else{
                // country code not found, create new country
                // halt for further clarificaiton
            }*/
        }
        $fileToBeDeleted =  public_path().'/import_files/'.$filename;
        @unlink($fileToBeDeleted);
        return redirect()->route('stores.index')->with('message','All data from file has been saved successfully!');
      }else{
        $this->validate($request, [
          'import_file' => 'required|mimes:xls,xlsx'
        ]);
      }
    }

    public function checkExcelFile($request){
      if ($request->hasfile('import_file')) {
        $file_ext = $request->file('import_file')->getClientOriginalExtension();
        // array("xls","xlsx","xlm","xla","xlc","xlt","xlw");
        $extensions=array(
          'xls','xlsx' // add your extensions here.
        );
        return in_array($file_ext,$extensions) ? true : false;
      }else{
        return false;
      }
    }

    /**
     * get countries
     *
     * @return indexd Array example Array ( '0' => 'TT' , '1' => 'BB' )
     */
    public function getCountries()
    {
        $arrCountries = array();
        $countries = Country::select('id','country_code')->get();
        foreach ($countries as $key => $country) {
            $arrCountries[$country->id] = $country->country_code;
        }

        return $arrCountries;
    }

    public function getLocations()
    {
        $arrLocations = array();
        $locations = Location::select('id','name')->get();
        foreach ($locations as $key => $location) {
          $arrLocations[$location->id] = $location->name;
        }

        return $arrLocations;
    }

    public function updateOrCreateStore($store, $locationId, $countryId)
    {
        $store = Store::updateOrCreate([
            'address_line_one' => $store[1],
            'location_id'      => $locationId,
        ],[
            'name' => $store[0] ,
            'address_line_one' => $store[1] ,
            'address_line_two' => $store[2] ,
            'country_id' => $countryId ,
            'location_id' => $locationId ,
            'lat' => $store[8] , 
            'lon' => $store[9] ,
        ]);

        return $store;
    }

    public function updateOrCreateStoreInfo($storeDb, $storeExcel)
    {
        $workingHours = $this->spliter($storeExcel);
        
        $storeInfo = StoreInfo::updateOrCreate([
            'store_id' => $storeDb->id,
        ],[
            'phone_number'         => $storeExcel[5],
            'website'              => $storeExcel[6],
            'primary_category'     => $storeExcel[7],
            'sunday_hours_from'    => (!empty($workingHours[0])?$workingHours[0]['from']:null),
            'sunday_hours_to'      => (!empty($workingHours[0])?$workingHours[0]['to']:null),
            'monday_hours_from'    => (!empty($workingHours[1])?$workingHours[1]['from']:null), 
            'monday_hours_to'      => (!empty($workingHours[1])?$workingHours[1]['to']:null),
            'tuesday_hours_from'   => (!empty($workingHours[2])?$workingHours[2]['from']:null),
            'tuesday_hours_to'     => (!empty($workingHours[2])?$workingHours[2]['to']:null),
            'wednesday_hours_from' => (!empty($workingHours[3])?$workingHours[3]['from']:null), 
            'wednesday_hours_to'   => (!empty($workingHours[3])?$workingHours[3]['to']:null),
            'thursday_hours_from'  => (!empty($workingHours[4])?$workingHours[4]['from']:null),
            'thursday_hours_to'    => (!empty($workingHours[4])?$workingHours[4]['to']:null),
            'friday_hours_from'    => (!empty($workingHours[5])?$workingHours[5]['from']:null), 
            'friday_hours_to'      => (!empty($workingHours[5])?$workingHours[5]['to']:null),
            'saturday_hours_from'  => (!empty($workingHours[6])?$workingHours[6]['from']:null),
            'saturday_hours_to'    => (!empty($workingHours[6])?$workingHours[6]['to']:null),
        ]);

        return $storeInfo;
    }

    public function spliter($storeExcel)
    {
        $workingHours = array();
        $hours = array_splice($storeExcel, 10);
        foreach ($hours as $key => $timeExcel) {
            if (is_null($timeExcel)) {
                array_push($workingHours, []);
            }else{
                $temp = array();
                $time = explode('-', $timeExcel);
                $temp['from'] = $time[0];
                $temp['to'] = $time[1];
                array_push($workingHours, $temp);
            }
        }
        
        return $workingHours;
    }

    public function validateStorecode($storecode, $countryId)
    {
        $json = array();
        $status = false;
        $record_id = ''; 

        $stcodeval = Store::where('storecode', $storecode)->where('country_id', $countryId)->first();
        if(isset($stcodeval) && !empty($stcodeval) && $stcodeval != '' && $stcodeval != '[]'){
            $status = true;
            $record_id = $stcodeval->id;
        }

        $json['stcode_exists'] = $status;
        $json['record_id'] = $record_id;
        return json_encode($json);
        die;
    }

    public function storesByCountry($countryId)
    {
        $json = array();
        $json['stores'] = Store::whereCountryId($countryId)->get();
        return json_encode($json);
        die;
    }
}

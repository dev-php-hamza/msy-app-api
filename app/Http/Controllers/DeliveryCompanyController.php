<?php

namespace App\Http\Controllers;

use App\DeliveryCompany;
use App\Country;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DeliveryCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $deliveryCompanies = DeliveryCompany::with('stores')->latest()->paginate(10);
        return view('admin.deliverycompany.index', compact('deliveryCompanies', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.deliverycompany.create', compact( 'countries'));
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
            'country_id'     => 'required|numeric',
            'dCompany_name'  => 'required|string|unique:delivery_companies,name',
            'dCompany_email' => 'required|string|max:191',
            'file'  => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $deliveryCompany = DeliveryCompany::create([
            'country_id' => $request->country_id,
            'name'       => $request->dCompany_name,
            'email'      => $request->dCompany_email
        ]);

        if ($request->hasFile('file')) {
            if (! File::isDirectory(public_path('delivery_company/images/'))) {
                File::makeDirectory(public_path().'/delivery_company/images/', 755,true);
                chmod(public_path().'/delivery_company/images/', 0755);
            }

            $file = $request->file;
            $filename = $file->getClientOriginalName();
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('delivery_company/images/', $filename);

            $filename = url('/').'/delivery_company/images/'.$filename;

            $deliveryCompany->icon = $filename;
            $deliveryCompany->save();
        }

        return redirect()->route('delivery-companies.index')->with('message','Delivery Company has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\DeliveryCompany  $deliveryCompany
     * @return \Illuminate\Http\Response
     */
    public function show(DeliveryCompany $deliveryCompany)
    {
        $country        = $deliveryCompany->country;
        $countryName    = $country->name;
        $assignedStores = $deliveryCompany->stores()->orderBy('name')->get();

        return view('admin.deliverycompany.detail', compact('deliveryCompany', 'countryName', 'assignedStores'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DeliveryCompany  $deliveryCompany
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryCompany $deliveryCompany)
    {
        $countries      = Country::all();
        $country        = $deliveryCompany->country;
        $dCompanyCountryId      = $country->id;
        $assignedStores = $deliveryCompany->stores()->orderBy('name')->get();

        return view('admin.deliverycompany.edit', compact('deliveryCompany', 'countries' ,'dCompanyCountryId', 'assignedStores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\DeliveryCompany  $deliveryCompany
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryCompany $deliveryCompany)
    {
        $this->validate($request,[
            'country_id'     => 'required|numeric',
            'dCompany_name'  => 'required|string|unique:delivery_companies,name,'.$deliveryCompany->id,
            'dCompany_email' => 'required|string|max:191',
            'file'           => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->country_id != $deliveryCompany->country_id) {
            $deliveryCompany->stores()->detach();
        }

        $deliveryCompany->country_id = $request->country_id;
        $deliveryCompany->name  = $request->dCompany_name;
        $deliveryCompany->email = $request->dCompany_email;
        $deliveryCompany->save();

        if ($request->hasFile('file')) {
            if (! File::isDirectory(public_path('delivery_company/images/'))) {
                File::makeDirectory(public_path().'/delivery_company/images/', 755,true);
                chmod(public_path().'/delivery_company/images/', 0755);
            }

            $fileToBeDeleted = public_path().'/delivery_company/images/'.$deliveryCompany->icon;
            @unlink($fileToBeDeleted);

            $file = $request->file;
            $filename = $file->getClientOriginalName();
            $filename = time().'.'.$file->getClientOriginalExtension();
            $file->move('delivery_company/images/', $filename);

            $filename = url('/').'/delivery_company/images/'.$filename;

            $deliveryCompany->icon = $filename;
            $deliveryCompany->save();
        }

        return redirect()->route('delivery-companies.index')->with('message','Delivery Company has been saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DeliveryCompany  $deliveryCompany
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryCompany $deliveryCompany)
    {
        //
    }

    public function showAssignStoreForm(DeliveryCompany $deliveryCompany)
    {
        $country        = $deliveryCompany->country;
        $countryName    = $country->name;
        $assignedStores = $deliveryCompany->stores()->orderBy('name')->get();
        $assignedStoreIds = array();
        foreach ($assignedStores as $key => $assignedStore) {
            $assignedStoreIds[] = $assignedStore->id;
        }
        $stores         = Store::whereNotIn('id', $assignedStoreIds)->whereCountryId($country->id)->orderBy('name')->get();
        return view('admin.deliverycompany.assign_stores', compact('deliveryCompany', 'stores', 'countryName', 'assignedStores'));
    }

    public function saveAssignStore(Request $request)
    {
        $storeIds = $request->storeIds;
        $deliverycompany = DeliveryCompany::whereId($request->dCompany_id)->first();
        $deliverycompany->stores()->attach($storeIds);

        return redirect()->route('delivery-companies.index')->with('message','Stores has been assigned successfully!');
    }

    public function unAssignStore($deliveryCompanyId, $storeId)
    {
        $deliveryCompany = DeliveryCompany::findOrFail($deliveryCompanyId);
        $store = Store::findOrFail($storeId);
        $deliveryCompany->stores()->detach($store->id);

        return redirect()->back()->with('message','Stores has been assigned successfully!');
    }
}

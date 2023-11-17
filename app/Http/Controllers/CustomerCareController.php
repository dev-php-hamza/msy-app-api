<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CustomerCare;
use App\Country;

class CustomerCareController extends Controller
{
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
    	$countries = Country::all();
        $customercares = CustomerCare::latest()->paginate(10);
        return view('admin.customercare.index', compact('customercares', 'countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.customercare.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_feedback_email'      => 'nullable|email|string|max:255',
            'massy_card_support_email'     => 'nullable|email|string|max:255',
            'massy_app_tech_support_email' => 'nullable|email|string|max:255',
            // 'phone' => ['nullable','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'phone' => 'nullable|string',
            'country_id' => 'required|numeric',
        ]);

        $customerCare = CustomerCare::updateOrCreate([
            'country_id' => $request->country_id
        ],[
            'customer_feedback_email'      => $request->customer_feedback_email,
            'massy_card_support_email'     => $request->massy_card_support_email,
            'massy_app_tech_support_email' => $request->massy_app_tech_support_email,
            'phone' => $request->phone
        ]);
        // $customerCare = CustomerCare::create($request->all());
        return redirect()->route('customercares.index')->with('message','CustomerCare has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerCare $customercare)
    {
    	
    	$countryData = $customercare->country;
    	$countryName = $countryData->name;
        return view('admin.customercare.detail', compact('customercare', 'countryName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerCare $customercare)
    {
        $countries = Country::all();
        return view('admin.customercare.edit', compact('customercare','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerCare $customercare)
    {
        $this->validate($request,[
            'customer_feedback_email'      => 'nullable|email|string|max:255',
            'massy_card_support_email'     => 'nullable|email|string|max:255',
            'massy_app_tech_support_email' => 'nullable|email|string|max:255',
            // 'phone' => ['nullable','regex:/(\+?( |-|\.)?\d{1,2}( |-|\.)?)?(\(?\d{3}\)?|\d{3})( |-|\.)?(\d{3}( |-|\.)?\d{2})/'],
            'phone' => 'nullable|string',
            'country_id' => 'required|numeric',
        ]);
        $customercare->update($request->all());
        return redirect()->route('customercares.index')->with('message', 'CustomerCare has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Location  $location
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerCare $customercare)
    {
        $customercare->delete();
        return redirect()->route('customercares.index')->with('message', 'CustomerCare has been deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Country;
use App\OrderSettings;
use Illuminate\Http\Request;

class OrderSettingsController extends Controller
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
        $countries = Country::with('orderSettings')->get();
        return view('admin.ordersettings.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'country_id'          => 'required|numeric',
            'primary_email'       => 'nullable|string|email|max:255',
            // 'cc_emial_addresses'  => 'nullable|string|email',
            'customer_email_text' => 'required',
        ]);

        $orderInstances = OrderSettings::whereCountryId($request->country_id)->get();
        if (count($orderInstances) > 0 ) {
            $ordersettings = OrderSettings::updateOrCreate([
                'country_id' => $request->country_id,
            ],[
                'massy_card_required' => $request->massy_card_required,
                'primary_email' => $request->primary_email,
                'cc_email_addresses'  => $request->cc_email_addresses,
                'minimum_order_price' => $request->minimum_order_price,
                'quantity_text' => $request->quantity_text,
                'pickup_customer_notice_text' => $request->pickup_customer_notice_text,
                'delivery_customer_notice_text' => $request->delivery_customer_notice_text,
                'order_services_text' => $request->order_services_text,
                'welcome_text'    => $request->welcome_text,
                'completion_text' => $request->completion_text,
                'customer_email_text' => $request->customer_email_text,
            ]);

            if (!isset($ordersettings->primary_email)) {
                $country = Country::whereId($request->country_id)->update([
                    'order_service_status' => 0
                ]);
            }
        }else{
            $ordersettings = OrderSettings::create([
                'country_id' => $request->country_id,
                'massy_card_required' => $request->massy_card_required,
                'primary_email' => $request->primary_email,
                'cc_email_addresses'  => $request->cc_email_addresses,
                'minimum_order_price' => $request->minimum_order_price,
                'quantity_text' => $request->quantity_text,
                'pickup_customer_notice_text' => $request->pickup_customer_notice_text,
                'delivery_customer_notice_text' => $request->delivery_customer_notice_text,
                'order_services_text' => $request->order_services_text,
                'welcome_text'    => $request->welcome_text,
                'completion_text' => $request->completion_text,
                'customer_email_text' => $request->customer_email_text,
            ]);
        }

        return redirect()->route('orderSettings.index')->with('message','Order Setting has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function updateOrderServiceStatus(Request $request)
    {

        $country = Country::whereCountryCode($request->country_code)->first();
        if (count($country) > 0) {
            $country->order_service_status = !$country->order_service_status;
            $country->save();
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
}

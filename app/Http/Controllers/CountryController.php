<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;

class CountryController extends Controller
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
        $countries = Country::latest()->paginate(10);
        return view('admin.country.index', compact('countries'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.country.create');
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
        'name' => 'required|string|max:255|unique:countries',
       ]);

       $country = Country::create(['name'=>$request->name]);
       return redirect()->route('countries.index')->with('message','Country has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $country = Country::find($id);
        return view('admin.country.details', compact('country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $country = Country::find($id);
        return view('admin.country.edit', compact('country'));
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
        $this->validate($request, [
         'name' => 'required|string|max:255|unique:countries',
        ]);

        $country = Country::where('id',$id)->update(['name'=>$request->name]);

        return redirect()->route('countries.index')->with('message','Country has been updated successfully!');
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

    public function getCountryLocations($countryId)
    {
        $locations = Country::where('id',$countryId)->with('locations')->get();

        return response()->json(['locations'=>$locations[0]['locations']]);
    }

    public function updateCountrySwitch(Request $request)
    {
        $country = Country::whereCountryCode($request->country_code)->first();
        if (count($country) > 0) {
            $country->switch = !$country->switch;
            $country->save();
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }
}

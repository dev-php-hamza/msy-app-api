<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Country;
use App\RedeemInfo;
use App\Helper;

class RedeemInfoController extends Controller
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
        $redeemInfos = RedeemInfo::latest()->paginate(10);
        return view('admin.redeemInfo.index', compact('redeemInfos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all();
        return view('admin.redeemInfo.create', compact('countries'));
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
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'country_id'  => 'required|numeric',
            'file'        => 'image|mimes:jpeg,png,jpg',
        ]);

        $redeemInfo = RedeemInfo::updateOrCreate([
            'country_id' => $request->country_id
        ],[
            'title' => $request->title,
            'description' => $request->description
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$redeemInfo->id.'.'.$fileExten;

            if (isset($redeemInfo->image)) {

                $fileToBeDeleted = Helper::getFileNameForDelete($redeemInfo->image);
                $fileToBeDeleted = public_path().'/redeemInfo/images/'.$fileToBeDeleted;

                @unlink($fileToBeDeleted);
                
            }

            $file->move('redeemInfo/images/',$fileCompName);
            
            $fileCompName = url('/').'/redeemInfo/images/'.$fileCompName;

            $redeemInfo->image = $fileCompName;
            $redeemInfo->save();
        }

        return redirect()->route('redeemInfos.index')->with('message','RedeemInfo has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(RedeemInfo $redeemInfo)
    {
        $countryName = $redeemInfo->country->name;
        return view('admin.redeemInfo.detail', compact('redeemInfo', 'countryName'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(RedeemInfo $redeemInfo)
    {
        $countries = Country::all();
        return view('admin.redeemInfo.edit', compact('redeemInfo','countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RedeemInfo $redeemInfo)
    {
        $this->validate($request, [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'country_id'  => 'required|numeric',
            'file'        => 'image|mimes:jpeg,png,jpg',
        ]);

        $redeemInfoCheck = RedeemInfo::whereCountryId($request->country_id)->get();

        if ($redeemInfo->country_id != $request->country_id && count($redeemInfoCheck) > 0) {
            return redirect()->back()->with('error', 'This country already has Redeem Info. kindly Choose differet Country');
        }

        $redeemInfo->update([
            'title'       => $request->title,
            'description' => $request->description,
            'country_id'  => $request->country_id
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file;
            $fileName = pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME);
            $fileExten = $file->getClientOriginalExtension();
            $fileCompName = rand().'_'.$redeemInfo->id.'.'.$fileExten;

            if (isset($redeemInfo->image)) {

                $fileToBeDeleted = Helper::getFileNameForDelete($redeemInfo->image);
                $fileToBeDeleted = public_path().'/redeemInfo/images/'.$fileToBeDeleted;

                @unlink($fileToBeDeleted);
                $file->move('redeemInfo/images/',$fileCompName);
            }else{
                $file->move('redeemInfo/images/',$fileCompName);
            }

            $redeemInfo->image = url('/').'/redeemInfo/images/'.$fileCompName;
            $redeemInfo->save();
        }
        return redirect()->route('redeemInfos.index')->with('message','RedeemInfo has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RedeemInfo $redeemInfo)
    {
        $fileToBeDeleted = Helper::getFileNameForDelete($redeemInfo->image);
        @unlink(public_path().'/redeemInfo/images/'.$fileToBeDeleted);
        $redeemInfo->delete();
        return redirect()->route('redeemInfos.index')->with('message', 'RedeemInfo has been deleted successfully!');
    }
}

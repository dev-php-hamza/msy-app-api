<?php

namespace App\Http\Controllers;

use App\AppIntegration;
use Illuminate\Http\Request;
use App\Helper;

class AppIntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $apps = AppIntegration::latest()->paginate(10);
        return view('admin.appintegrations.index', compact('apps'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.appintegrations.create');
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
            'app_name'   => 'required|string|max:255|unique:app_integrations',
            'auth_token' => 'required|string|max:255',
        ]);

        $appTokenData = json_decode($request->appData);
        $appData = $appTokenData->appData;

        $appIntegration = AppIntegration::create([
            'app_name'    => $request->app_name,
            'salt'        => $appData->salt,
            'base64_salt' => $appData->base64_salt,
            'auth_token'  => $request->auth_token,
        ]);

       return redirect()->route('apps.index')->with('message','App has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AppIntegration  $appIntegration
     * @return \Illuminate\Http\Response
     */
    public function show(AppIntegration $appIntegration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AppIntegration  $appIntegration
     * @return \Illuminate\Http\Response
     */
    public function edit(AppIntegration $appIntegration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AppIntegration  $appIntegration
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AppIntegration $appIntegration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AppIntegration  $appIntegration
     * @return \Illuminate\Http\Response
     */
    public function destroy(AppIntegration $appIntegration)
    {
        //
    }

    public function generateAuthToken($appName)
    {
        $salt = $appName.''.substr(md5(mt_rand()), 0, 5);
        $base64_salt = base64_encode($salt);
        $auth_token  = Helper::crypt($salt, 'e');

        $data = array();
        $data['app_name']    = $appName;
        $data['salt']        = $salt;
        $data['base64_salt'] = $base64_salt;
        $data['auth_token']  = $auth_token;

        return response()->json(['appData'=>$data]);
    }
}

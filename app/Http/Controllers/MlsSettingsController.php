<?php

namespace App\Http\Controllers;

use App\MlsSettings;
use Illuminate\Http\Request;
use App\Country;
class MlsSettingsController extends Controller
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
        $mlsSettings = MlsSettings::paginate(10);
        return view('admin.mlssettings.index', compact('mlsSettings', 'countries'));
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
            'country_id'  => 'required|numeric',
            'base_url'    => 'required|string',
            'mlid'        => 'required|string',
            'secretKey'   => 'required|string',
            'type'        => 'required|string',
        ]);

        $mlsInstances = MlsSettings::whereCountryId($request->country_id)->get();
        if (count($mlsInstances) > 0 ) {
            $mlssettings = MlsSettings::updateOrCreate([
                'country_id' => $request->country_id,
                'type'       => $request->type,
            ],[
                'base_url'   => $request->base_url,
                'mlid'       => $request->mlid,
                'secret_key' => $request->secretKey,
            ]);
        }else{
            $mlssettings = MlsSettings::create([
                'country_id' => $request->country_id,
                'type'       => $request->type,
                'base_url'   => $request->base_url,
                'mlid'       => $request->mlid,
                'secret_key' => $request->secretKey,
                'switch'     => 1
            ]);
        }

        return redirect()->route('mlsSettings.index')->with('message','MLS Setting has been saved successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MlsSettings  $mlsSettings
     * @return \Illuminate\Http\Response
     */
    public function show(MlsSettings $mlsSettings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MlsSettings  $mlsSettings
     * @return \Illuminate\Http\Response
     */
    public function edit(MlsSettings $mlsSettings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MlsSettings  $mlsSettings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MlsSettings $mlsSettings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MlsSettings  $mlsSettings
     * @return \Illuminate\Http\Response
     */
    public function destroy(MlsSettings $mlsSettings)
    {
        //
    }

    public function showMlsTest(Request $request)
    {
        $countryMlsSetting = MlsSettings::select('id','base_url', 'type')->whereType($request->type)->whereCountryId($request->countryId)->first();
        return view('admin.mlssettings.testmls', compact('countryMlsSetting'));
    }

    public function updateMlsServiceStatus(Request $request)
    {

        $country = Country::whereCountryCode($request->country_code)->first();
        if (count($country) > 0) {
            $country->mls_service_status = !$country->mls_service_status;
            $country->save();
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function chooseInstance(Request $request)
    {
        $country = Country::whereCountryCode($request->country_code)->first();
        if (count($country) > 0) {
            $mlssettings = $country->mlssettings;
            foreach ($mlssettings as $key => $mlssetting) {
                $mlssetting->switch = !$mlssetting->switch;
                $mlssetting->save();
            }
            return response()->json(['status'=>true]);
        }
        return response()->json(['status'=>false]);
    }

    public function testmslb($instanceId, $cardNum)
    {
        $mlsSetting = MlsSettings::find($instanceId);
        $temp = array();
        $temp['status'] = false;
        if (count($mlsSetting) > 0) {
            $mlid = $mlsSetting->mlid;
            $ts = time();
            $secret = $mlsSetting->secret_key;
            $card = $cardNum;
            $data = array($card,$mlid,$ts);
            natsort($data);
            $query_string = implode("::", $data);
            $hash = hash_hmac("sha256", $query_string, $secret);
            $data = array('card'=>$card,'mlid'=>$mlid,'ts'=>$ts,'qsa'=>$hash);
            $data = http_build_query($data);

            $url = $mlsSetting->base_url."cardLookup";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url.'?'.$data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

            $result = json_decode(curl_exec($ch));
            if (curl_errno($ch)) {
                $error_msg = curl_error($ch);
                $temp['status'] = false;
                $temp['error']  = json_decode($error_msg, true);
            }else{
                $temp['status'] = true;
                $temp['mls'] = $result;
            }
            curl_close($ch);
        }
        return $temp;
    }
}

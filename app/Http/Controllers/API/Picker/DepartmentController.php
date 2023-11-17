<?php

namespace App\Http\Controllers\API\Picker;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Validator;
use App\Country;
use App\SubDepartment;

class DepartmentController extends Controller
{
    public function departmentsList(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    	  'country_code' => 'required',
    	]);

    	if ($validator->fails()) {
    	  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    	}

    	$data = array();
    	$country = Country::whereCountryCode($request->country_code)->first();

    	if (isset($country) && !empty($country) && !is_null($country)) {
            $departments = \DB::table('departments as depart')
                                        ->join( 'department_subdepartments as depart_sub_depart', 'depart.id', '=', 'depart_sub_depart.department_id' )
                                        ->join( 'sub_departments as sub_depart', 'depart_sub_depart.sub_department_id', '=', 'sub_depart.id' )
                                        ->where( 'depart.country_id', $country->id)
                                        ->select('depart.id as depart_id','depart.number as depart_number','depart.name as depart_name','sub_depart.*')
                                        ->get();
            $data['departments'] = $this->extractDepartmentList($departments, $country->id);
    		return response()->json(Helper::makeResponse($data,null,null,200,true));
    	}
    	return response()->json(Helper::makeResponse(null,'Unprocessable Entity','country not found',200,false));
    }

    public function extractDepartmentList($departments, $countryId)
    {
        $output = array();
        $arrIndex = 0;
        foreach ($departments as $key => $department) {
            $temp_depart_arr     = array();
            $temp_sub_depart_arr = array();

            $tempDepartArrIndex = null;
            foreach ($output as $index => $tempDepart) {
                if ($tempDepart['id'] === $department->depart_id) {
                    $tempDepartArrIndex = $index;
                    break;
                }
            }

            if (!is_null($tempDepartArrIndex)) {
                $subDepart  = SubDepartment::whereHas('categories', function ($query) use ($countryId) {
                    $query->where('country_id', '=', $countryId);
                })
                ->whereId($department->id)
                ->first();
                if (count($subDepart) > 0) {
                    $temp_sub_depart_arr['id']     = $department->id;
                    $temp_sub_depart_arr['number'] = $department->number;
                    $temp_sub_depart_arr['name']   = $department->name;
                    $output[$tempDepartArrIndex]['sub_departments'][] = $temp_sub_depart_arr;
                }
            }else{
                $temp_depart_arr['id']     = $department->depart_id;
                $temp_depart_arr['number'] = $department->depart_number;
                $temp_depart_arr['name']   = $department->depart_name;
                $output[$arrIndex] = $temp_depart_arr;

                $subDepart  = SubDepartment::whereHas('categories', function ($query) use ($countryId) {
                    $query->where('country_id', '=', $countryId);
                })
                ->whereId($department->id)
                ->first();

                if (count($subDepart) > 0) {
                    $temp_sub_depart_arr['id']     = $department->id;
                    $temp_sub_depart_arr['number'] = $department->number;
                    $temp_sub_depart_arr['name']   = $department->name;
                    $output[$arrIndex]['sub_departments'][] = $temp_sub_depart_arr;
                    $arrIndex++;
                }
            }
        }

        return $output;
    }
}

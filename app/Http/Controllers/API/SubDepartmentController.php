<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helper;
use Validator;
use App\Department;

class SubDepartmentController extends Controller
{
    public function subDepartmentsList(Request $request)
    {
    	$validator = Validator::make($request->all(), [
    	  'department_id' => 'required|numeric',
    	]);

    	if ($validator->fails()) {
    	  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
    	}

    	$data = array();
    	$department = Department::whereId($request->department_id)->first();

    	if (isset($department) && !empty($department) && !is_null($department)) {
    		$subDepartmentObjs = $department->subDepartments()->orderBy('name')->get();
    		$data['subDepartments'] = $this->extractSubDepartmentList($subDepartmentObjs);
    		return response()->json(Helper::makeResponse($data,null,null,200,true));
    	}
    	return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Department not found',200,false));
    }

    public function extractSubDepartmentList($subDepartments)
    {
        $output = array();
        foreach ($subDepartments as $key => $subDepartment) {
            $temp = array();
            $temp['id']     = $subDepartment->id;
            $temp['number'] = $subDepartment->number;
            $temp['name']   = $subDepartment->name;

            $output[] = $temp;
        }

        return $output;
    }
}

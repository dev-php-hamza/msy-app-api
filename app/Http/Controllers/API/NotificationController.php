<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\NotificationUser;
use App\Promotion;
use App\Coupon;
use App\Country;
use App\Helper;
use Auth;

class NotificationController extends Controller
{

	public function index(Request $request)
	{
		$data = array();
		$user = Auth::user();
		$userCountry = $user->userInfo->country;
		$country     = Country::whereCountryCode($userCountry)->first(); 
		$countryId   = $country->id;

		if(count($user->notificationsCountry($countryId)) > 0){
			$unreadNotifications = $user->unreadNotifications($countryId)->latest()->get();
			$readNotifications   = $user->readNotifications($countryId)->latest()->get();

			$unread = $this->extratNotifyData($unreadNotifications);
			$data['new_count'] = count($unread);
			$data['unread'] = $unread;
			$data['read']   = $this->extratNotifyData($readNotifications);
			
		}

		return response()->json(Helper::makeResponse($data,null,null,200,true));
	}

	public function extratNotifyData($notifications)
	{
		$tempData = array();
		foreach ($notifications as $key => $notification) {
			$objectData = "";
			if ($notification->object === 'promotion') {
				$objectData = Promotion::find($notification->object_id);
			}

			if ($notification->object === 'coupon') {
				$objectData = Coupon::find($notification->object_id);
			}

			$temp = array();
			$temp['id'] 		= $notification->id;
			$temp['object'] 	= $notification->object;
			$temp['object_id'] 	= $notification->object_id;
			$temp['object_title'] = $notification->title;
			$temp['message'] 	= $notification->text;
			$temp['created_at'] = $notification->created_at->toDateTimeString();
			array_push($tempData, $temp);
		}

		return $tempData;
	}

	public function update(Request $request)
	{
		// $validator = Validator::make($request->all(), [
		//   'id' => 'required|numeric',
		// ]);

		// if ($validator->fails()) {
		//   return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		// }

		if(isset($request->id) && !empty($request->id) && $request->id != ''){
			$user = Auth::user();
			$userNotification = NotificationUser::whereUserId($user->id)->whereNotificationId($request->id)->first();
			if (count($userNotification)>0) {
				$userNotification->read = 1;
				$userNotification->save();			
			}
		}
		return response()->json(Helper::makeResponse(null,null,null,200,true));
		// return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Notification not found',200,false));
	}

	public function unreadCount(Request $request)
	{
		$data = array();
		$data['new_count'] = 0;
		$user = Auth::user();
		$userCountry = $user->userInfo->country;
		$country     = Country::whereCountryCode($userCountry)->first(); 
		$countryId   = $country->id;
		
		if(count($user->notificationsCountry($countryId)) > 0){
			$unreadNotifications = $user->unreadNotifications($countryId)->get();
			$data['new_count'] = count($unreadNotifications);
			
		}

		return response()->json(Helper::makeResponse($data,null,null,200,true));
	}

	public function delete(Request $request)
	{
		$validator = Validator::make($request->all(), [
		  'id' => 'required|numeric',
		]);

		if ($validator->fails()) {
		  return response()->json(Helper::makeResponse(null,'Validation',$validator->errors(),422,false));
		}

		$user = Auth::user();
		$userNotification = NotificationUser::whereUserId($user->id)->whereNotificationId($request->id)->first();
		if (count($userNotification)>0) {
			$userNotification->delete();
			return response()->json(Helper::makeResponse(null,null,null,200,true));			
		}
		return response()->json(Helper::makeResponse(null,'Unprocessable Entity','Notification not found',200,false));
	}

	public function deleteAll()
	{
		$user = Auth::user();
		$userNotification = NotificationUser::whereUserId($user->id)->delete();
		return response()->json(Helper::makeResponse(null,null,null,200,true));
	}
}
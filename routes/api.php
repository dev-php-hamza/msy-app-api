<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Auth Start*/
Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::post('social/auth', 'API\UserController@socialAuth');
Route::post('social/auth/apple', 'API\UserController@socialAuthApple');
Route::post('profile-image/upload', 'API\UserController@uploadImage');
Route::post('user/password/forgot', 'API\UserController@forgotPassword');
Route::get('check-email', 'API\UserController@checkEmail');
Route::get('refresh-user-access-token', 'API\UserController@refreshUserAccessToken');
/*locations/Area Start*/
Route::get('areas', 'API\AreaController');
Route::get('countries', 'API\CountryController');
Route::get('guest-home', 'API\HomeController@guestHome');

/*Store start*/
Route::get('stores', 'API\StoreController@index');
Route::get('get-access-token', 'API\MassycardController@getAccessToken');

/*Restricted Url by access token*/
Route::group(['middleware' => 'auth:api'], function(){

	Route::post('user/password/update', 'API\UserController@changePassword');
	Route::post('user/profile/update', 'API\UserController@updateProfile');
	Route::post('user/profile/update/image', 'API\UserController@updateProfileImage');
	Route::get('user', 'API\UserController@details');
	Route::get('user/orders', 'API\OrderController@orders');
	Route::get('get-products', 'API\ProductController@getProductsByLocation');
	Route::get('product/detail', 'API\ProductController@getDetail');
	Route::get('product/stock-info', 'API\ProductController@getStock');
	Route::post('user/delete', 'API\UserController@delete');

	/*Home Url*/
	Route::get('home', 'API\HomeController@index');
	Route::get('new-home', 'API\HomeController@home');

    /*Promotion start*/
	Route::get('promotions', 'API\PromotionController@index');
	Route::get('promotions/detail', 'API\PromotionController@detail');
	Route::get('promotions/get-bundles', 'API\PromotionController@getBundles');
	Route::get('promotions/product/detail', 'API\PromotionController@promotionalProductDetail');
	Route::get('productsAndPromotionsByBarcode', 'API\ProductController@getByBarCode');

	/*Offer start but not in use*/
	Route::get('get-offers', 'API\OfferController@getOffersByLocation');

	/*User List Start*/
	Route::get('lists', 'API\ListController@index');
	Route::post('lists/create', 'API\ListController@create');
	Route::post('lists/update', 'API\ListController@update');
	Route::post('lists/delete', 'API\ListController@delete');
	Route::post('lists/items', 'API\ListController@addItemsToList');
	Route::get('lists/items', 'API\ListController@getListItems');
	Route::post('lists/items/remove', 'API\ListController@deleteListItem');
	Route::post('lists/items/update', 'API\ListController@updateItemStatus');
	Route::get('lists/products/search', 'API\ProductController@searchProduct');
	Route::post('lists/bundles', 'API\ListController@addBundleToList');
	Route::get('lists/items-and-bundles', 'API\ListController@getListItemsNew');
	Route::post('lists/bundles/remove', 'API\ListController@deleteListBundle');
	Route::post('lists/bundles/mix-and-match/products', 'API\ListController@addMixAndMatchItemToList');


	/*coupons start*/
	// Route::get('coupons', 'API\CouponController@index');
	Route::get('coupons-new', 'API\CouponController@index');
	Route::get('coupons/detail', 'API\CouponController@detail');
	Route::post('coupons/update', 'API\CouponController@updateCoupon');
	Route::get('coupons/active-status', 'API\CouponController@getActiveStatus');
	Route::post('coupons/detail/multiple', 'API\CouponController@getMultipleCouponsWithData');
	Route::get('coupons/featured', 'API\CouponController@featuredCoupons');

	/*Notification start*/
	Route::get('notifications', 'API\NotificationController@index');
	Route::post('notifications/update', 'API\NotificationController@update');
	Route::get('notifications/count', 'API\NotificationController@unreadCount');
	Route::post('notifications/delete', 'API\NotificationController@delete');
	Route::post('notifications/delete-all', 'API\NotificationController@deleteAll');

	/*MassyCard start*/
	Route::get('massycard/connect', 'API\MassycardController@connectMassyCard');
	Route::get('massycard/connect-advance', 'API\MassycardController@connectMassyCardAdvance');
	Route::post('massycard/create', 'API\MassycardController@createMassyCard');
	Route::get('massycard/points', 'API\MassycardController@getMassyCardPoints');
	Route::post('massycard/activate', 'API\MassycardController@activateMassycard');
	Route::post('massycard/resend-verificationcode', 'API\MassycardController@resendVerifyCode');
	Route::get('massycard/removecard', 'API\MassycardController@removeCard');
	Route::get('massycard/pickup-locations', 'API\MassycardController@pickupLocations');
	Route::post('massycard/request-embossed-card', 'API\MassycardController@requestEmbossedCard');
	Route::get('massycard/balance', 'API\MassycardController@balance');
	Route::post('massycard/verificaton-advance', 'API\MassycardController@verficationAdvance');

	/*CustomerCare Start*/
	Route::get('customercare/detail', 'API\CustomerCareController@detail');
	Route::post('customercare/send-email', 'API\CustomerCareController@sendCustomerCareEmail');

	/*RedeemInfo Start*/
	Route::get('redemptionInfo', 'API\RedeemInfoController');

	/*Mls service Status*/
	Route::get('mls-service-status', 'API\MlsSettingsController');

	/* Order service Status */
	Route::get('order-service-status', 'API\OrderSettingsController');

	/* Order Start */
	Route::post('order/create', 'API\OrderController@store');
	Route::post('order/create-advance', 'API\OrderController@storeAdvance_new');

	/*Department Start */
	Route::get('departments', 'API\DepartmentController@departmentsList');
	Route::get('sub-departments', 'API\SubDepartmentController@subDepartmentsList');
	Route::get('categories', 'API\CategoryController@categoriesList');
	Route::get('categories/subdepartment', 'API\CategoryController@categoriesProductBySubDepart');

	Route::get('category/products', 'API\CategoryController@categoryProducts');

	/*Product Advance Search*/
	Route::get('products/search-advance', 'API\ProductController@searchProductAdvance');
	Route::get('products/search-advance-new', 'API\ProductController@searchProductAdvanceNew');
	Route::get('products/search-by-sub', 'API\ProductController@searchProductAdvanceBySubDepart');

});

Route::group([ 'prefix' => 'picker'], function (){ 
    Route::group(['namespace' => 'API\Picker','middleware' => 'appAuthorize'], function () {
        Route::get('products', 'ProductController@searchProduct');
        Route::get('departments', 'DepartmentController@departmentsList');
        Route::get('categories', 'CategoryController@categoriesList');
        Route::get('category/products', 'CategoryController@categoryProducts');

        Route::get('products/search-advance', 'ProductController@searchProductAdvance');
    });
});

Route::fallback(function(){
  return response()->json(['errors'=> ['message'=>'Page Not Found. If error persists, contact info@website.com']],404);
});
// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

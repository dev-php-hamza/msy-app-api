<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('auth.login');
// });
Route::get('/', 'HomeController@index');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::prefix('admin')->group(function(){
  Route::get('/', 'AdminController@index')->name('admin.home');
  Route::post('check','UserController@checkAdmin')->name('check.admin');

  //product start
  Route::resource('products', 'ProductController');
    
  Route::get('test/utility', 'ProductImageController@testUtility')->name('products.utility');
  Route::post('test/utility/search', 'ProductImageController@testUtilitySearch')->name('search.utility.image');

  Route::get('export-excel', 'ProductController@exportExcel')->name('export_products_excel');
  Route::get('show-import-daily-products-form', 'ProductController@showImportDailyProductForm')->name('show_import_daily_products_form');
  Route::post('import-daily-products-excel', 'ProductController@importDailyProductsExcel')->name('import_daily_products_excel');
  Route::get('show-import-products-form-via-sftp', 'ProductController@showImportProductViaSFTPForm')->name('show_import_products_form_via_sftp');
  Route::post('import-products-via-sftp', 'ProductController@importProductsViaSFTP')->name('import_products_via_sftp');
  Route::get('product/search-form', 'ProductController@showSearchForm')->name('search_form');
  Route::get('product/search', 'ProductController@getProductsByUpcByCountry')->name('search_by_upc');
  Route::get('product/image/remove/{productImage}', 'ProductImageController@removeProductImage')->name('product_image_remove');

  //User Start
  Route::get('users/search', 'UserController@searchUser')->name('users_search');
  Route::get('users/export', 'UserController@exportUsers')->name('users_export');
  Route::resource('users', 'UserController');

  // Country start
  Route::resource('countries', 'CountryController');
  Route::post('country/status', 'CountryController@updateCountrySwitch');
  Route::get('country/{id}/locations', 'CountryController@getCountryLocations');
  Route::post('import-daily-products-excel', 'ProductController@importDailyProductsExcel')->name('import_daily_products_excel');

  // Promotion start
  Route::resource('promotions', 'PromotionController');
  Route::get('promotion/{promotionId}/choose-products-from-country/{countryId}', 'PromotionController@showProductsByCountry')->name('choose_products_for_promotion');
  // Route::get('promotion/{promotionId}/edit-choosen-products-from-country/{countryId}', 'PromotionController@editChoosenProductsByCountry')->name('edit_choosen_products_for_promotion');
  Route::get('promotion/{promotionId}/edit-choosen-products-for-promotion', 'PromotionController@editChoosenProductsForPromotion')->name('edit_choosen_products_for_promotion');
  Route::post('promotion/{promotionId}/save-promotion-products', 'PromotionController@savePromotionProducts')->name('save_promotion_products');
  Route::get('promotion/{countryId}/products/{upc}', 'PromotionController@getProductsByUpcByCountry')->name('get_products_for_promotion');
  Route::post('promotion/{promotionId}/product/{productId}/remove', 'PromotionController@removeProductByPromotionId')->name('remove_product_by_promotion_id');
  Route::get('promotion/{filter}', 'PromotionController@promotionsFilter')->name('promotion-filter');

  // offers start
  // Route::resource('offers', 'OfferController');
  // Route::post('offer/{offerId}/save-offer-products', 'OfferController@saveOfferProducts')->name('save_offer_products');
  // Route::get('offer/{offerId}/choose-products-from-country/{countryId}', 'OfferController@showProductsByCountry')->name('choose_products_for_offer');

  Route::resource('locations','LocationController');

  /*store start*/
  Route::resource('stores','StoreController');
  Route::get('admin/stores/{store_name?}', 'StoreController@index')->name('stores.index');
  Route::get('show-import-stores-form', 'StoreController@showImportStoresForm')->name('show_import_stores_form');
  Route::post('import-stores-excel', 'StoreController@importStoresExcel')->name('import_stores_excel');
  Route::get('storecode/{storecode}/country/{countryId}', 'StoreController@validateStorecode');
  Route::get('stores/country/{countryId}', 'StoreController@storesByCountry');

  /*coupon start*/
  Route::resource('coupons','CouponController');
  Route::get('coupon/{couponId}/choose-products', 'CouponController@showProductsByCountry')->name('choose_products_for_coupon');
  Route::post('coupon/{couponId}/save-coupon-products', 'CouponController@saveCouponProducts')->name('save_coupon_products');
  Route::get('coupon/{countryId}/products/{upc}', 'CouponController@getProductsByUpcByCountry')->name('get_products_for_promotion');
  Route::get('coupon/{couponId}/edit-choosen-products-for-coupon', 'CouponController@editChoosenProductsForCoupon')->name('edit_choosen_products_for_coupon');
  Route::post('coupon/{couponId}/product/{productId}/remove', 'CouponController@removeProductByCouponId')->name('remove_product_by_coupon_id');
  Route::get('coupon/{filter}', 'CouponController@couponsFilter')->name('coupon-filter');
  Route::post('coupon/status', 'CouponController@updateCouponStatus')->name('update-coupon-active-status');
  Route::post('coupon/featured', 'CouponController@updateCouponFeature')->name('update-coupon-featured-status');

  Route::resource('notifications', 'NotificationController');

  // Route::get('promotion-for-notification/{promotion}', 'NotificationController@getDataByNotificationType')->name('promotions_for_notification');
  // Route::get('coupon-for-notification/{coupon}', 'NotificationController@getDataByNotificationType')->name('coupons_for_notification');
  Route::get('notifications/{countryId}/{type}', 'NotificationController@getNotificationsByCountryAndType')->name('notifications_by_type_and_country');
  Route::post('notifications/choose-users', 'NotificationController@next')->name('notifications.next');
  Route::post('notifications/save', 'NotificationController@save')->name('notifications.save');

  Route::get('notifications/users/country/{countryCode}', 'NotificationController@getUsersByCountryCode')->name('notifications_users_country');

  Route::post('notifications/users-search/', 'NotificationController@getUsersByCountryAndTerm')->name('notifications_users_country_term');

  Route::resource('customercares', 'CustomerCareController');
  Route::resource('redeemInfos', 'RedeemInfoController');

  Route::post('mlssetting/choose-instance', 'MlsSettingsController@chooseInstance')->name('mlssetting_choose_inctance');
  Route::resource('mlsSettings', 'MlsSettingsController');
  Route::get('mlsSetting/test-mls', 'MlsSettingsController@showMlsTest')->name('mlssetting_test_mls');
  Route::post('mlsSettings/service/status', 'MlsSettingsController@updateMlsServiceStatus');


  Route::get('test-mslb/{instanceId}/{card}', 'MlsSettingsController@testmslb')->name('testMLSB');
  Route::get('test-cron-product', 'AdminController@testCRONProduct')->name('testCRONProduct');
  Route::get('get-expired-products', 'ProductController@getExpiredProducts');

  Route::resource('orders', 'OrderController');
  Route::get('orders/resend-store-email/{orderId}', 'OrderController@resendStoreEmail')->name('orders_resend_email_store');
  Route::post('orders/resend-customer-email/{orderId}', 'OrderController@resendCustomerEmail')->name('orders_resend_email_customer');
  Route::get('orders-export-form', 'OrderController@showExport')->name('orders_export_form');
  Route::post('orders-export', 'OrderController@exportOrdersCsv')->name('orders_export_csv');

  Route::resource('orderSettings', 'OrderSettingsController');
  Route::post('orderSettings/service/status', 'OrderSettingsController@updateOrderServiceStatus');

  Route::get('apps/auth-token/{appName}','AppIntegrationController@generateAuthToken');
  Route::resource('apps', 'AppIntegrationController');

  Route::get('delivery-companies/store/assign/{deliveryCompany}', 'DeliveryCompanyController@showAssignStoreForm')->name('delivery-companies_assign_store');
  Route::post('delivery-companies/store/assign', 'DeliveryCompanyController@saveAssignStore')->name('delivery-companies_assign_store_save');
  Route::get('delivery-companies/{deliveryCompanyId}/store/unassign/{storeId}', 'DeliveryCompanyController@unAssignStore')->name('delivery-companies_unassign_store');
  Route::resource('delivery-companies', 'DeliveryCompanyController');

});



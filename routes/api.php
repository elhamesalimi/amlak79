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
Route::get('/set-estate-photos', 'ImageController@setEstatePhotos');

//report bug
Route::post('/report-bug','Api\BugController@storeReportBug');
Route::post('/login-with-activation-code','Api\AuthenticationController@login');
Route::get('/send-sms/{phone}/{code}','Api\EstateController@sendSms');

Route::get('/darkhast/list','Api\DarkhastController@getUserDarkhasts');

//manage
Route::get('/manage/{code}/{email_from?}','Api\EstateController@manage');
Route::get('/set-active-phone/{phone}','Api\UserController@activePhone');
Route::get('bookmark-estates/{estateIds}','Api\EstateController@bookmarkEstates');

//Route::get('/search/{category}/{query}', 'Api\EstateController@index');
Route::post('register', 'Admin\Auth\RegisterController@register');
//Route::post('logout', 'AuthenticationController@logoutAPI');

Route::middleware('auth:api,admin-api')->get('/user', function (Request $request) {
    return response()->json($request->user());
    Route::get('getUserAdvertisment','EstateController@getMyEstate');
});
    Route::get('getUserEstates','Api\EstateController@getUserEstate')->middleWare('api');

Route::group(['namespace'=>'Api'], function () {
    Route::get('getUserAdvertisment','EstateController@getMyEstate')->middleware('auth:api,admin-api');
    //get user information
    Route::get('getUserInformation','UserController@getUserInformation')->middleware('auth:api,admin-api');

//darkhast
    Route::get('getUserDarkhasts','DarkhastController@getUserDarkhasts');
    Route::post('/remove-darkhast-by-id/','DarkhastController@removeDarkhastById');
    Route::post('/remove-darkhast-estate','DarkhastController@removeDarkhastEstate');
    Route::get('/remove-all-darkhasts','DarkhastController@removeAllDarkhastOfUser');
});


//Route::group(['prefix'=>'admin','namespace'=>'Api\Admin'],function (){

//    Route::get('login','Api\LoginController@showLoginForm');
//    Route::post('login','Auth\LoginController@login');
//});
//    Route::post('login','Auth\LoginController@login');
//    Route::post('register','Auth\RegisterController@register');
//    Route::post('logout','Auth\LoginController@logout');
//    Route::post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail');
//    Route::post('password/reset','Auth\ResetPasswordController@reset');

//user
Route::get('/getUserContact/{estate_id}','Api\EstateController@getUserContact');

Route::get('/estates/','Api\EstateController@index');
Route::get('/estate/{id}','Api\EstateController@show');
Route::post('/estates/store','Api\EstateController@store');
Route::get('/estates/{code}/edit','Api\EstateController@edit');
Route::get('/change-Fail-status/{id}','Api\EstateController@changeFailStatus');
Route::get('/set-password-send-sms/{phone}/{code}','Api\EstateController@setPasswordAndSendSms');

Route::put('/estates/{id}/edit','Api\EstateController@update');
Route::get('/remove-estate/{id}','Api\EstateController@removeEstate');
Route::post('/estates/darkhast/store_sell','Api\DarkhastController@storeSell');
Route::post('/estates/darkhast/store_rent','Api\DarkhastController@storeRent');

Route::get('/cities','Api\EstateController@getCities');
Route::get('/getRegions/{city_id}','Api\EstateController@getRegions');
Route::get('/getAllRegions','Api\EstateController@getAllRegions');

Route::get('/getRegionsByCitySlug/{city_slug?}','Api\EstateController@getRegionsByCitySlug');
Route::get('/getPlans','Api\EstateController@getPlans');
Route::get('/getTypes','Api\EstateController@getTypes');
Route::get('/getFacilities','Api\EstateController@getFacilities');
Route::get('/getFields','Api\EstateController@getFields');

Route::get('/subitems/{subcategory}', 'CategoryController@subcategories');
//Route::get('/search/{category}/{query}', 'ProductController@search');
Route::get('/product/{product_id}', 'ProductController@product_info');
Route::get('/category/{subcategory}', 'CategoryController@subcategory_products');
//Route::post('register', 'Auth\RegisterController@register');
Route::post('logout', 'Api\AuthenticationController@logoutAPI');
Route::post('placeorder', 'OrderController@place_order');
//Route::get('testemail', 'OrderController@test_email');
Route::post('contact', 'ContactController@contact');


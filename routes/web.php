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
Route::get('test',function(){
    return view('admin.test');
});
Route::get('/da/{code}',function($code){
   return redirect("/darkhast/list/$code");
});
//cropnJob
Route::get('/cron-job','Api\DarkhastController@cronJob');
//send sms darkhast
Route::get('/send-darkhast-sms','Api\DarkhastController@sendDarkhastSms');
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Auth::routes();

    Route::group(['middleware' => 'admin'], function () {
        //dashboard
        Route::get('/', 'DashboardController@index');
        //report references Route
        Route::get('/reports','ReportController@references');
        //report bug Route
        Route::get('/bugs','BugController@index');
        Route::get('/bugs','BugController@index');
        Route::delete('/bugs/{estate_id}/{bug_id}','BugController@destroy');

        Route::group(['prefix' => '/regions' , 'middleware'=>'superAdmin'], function () {
            Route::get('/','RegionController@index');
            Route::get('/create','RegionController@create');
            Route::post('/create','RegionController@store');
            Route::get('/{region}/edit','RegionController@edit');
            Route::put('/{region}/edit','RegionController@update');
            Route::delete('/{region}/delete','RegionController@destroy');
        });

        Route::group(['prefix' => 'estates'], function () {
            Route::get('/', 'EstateController@index');
            Route::get('/create/{category}', 'EstateController@create');
            Route::get('/{id}/edit', 'EstateController@edit');
            Route::put('/{id}/edit', 'EstateController@update');
            Route::delete('/{id}/delete', 'EstateController@destroy');
            Route::delete('/empty-trash', 'EstateController@emptyTrash');

            Route::post('/create', 'EstateController@store');
        });
        //type
        Route::get('create-type-fields', 'TypeController@createTypeFields')->middleware('superAdmin');
        Route::delete('/types/{type_id}/{field_id}/delete', 'TypeController@destroy');
        Route::post('store-type-fields', 'TypeController@storeTypeFields');
        //ajax Route
        Route::get('/renderFields', 'EstateController@getFields');
        Route::get('/renderRegions', 'EstateController@renderRegions');

        // darkhast
        Route::group(['prefix' => '/darkhasts'], function () {
            Route::get('/', 'DarkhastController@index');
            Route::delete('/{id}/delete', 'DarkhastController@destroy');
        });

        //users
        Route::group(['prefix' => '/users', 'middleware' => 'superAdmin'], function () {
            Route::get('/', 'UserController@index');
            Route::get('/create', 'UserController@create');
            Route::post('/create', 'UserController@store');
            Route::get('/{user}/edit', 'UserController@edit');
            Route::put('/{user}/edit', 'UserController@update');
            Route::get('/change-password/{user}', 'UserController@showChangePasswordForm');
            Route::post('/update-password', 'UserController@changePassword');
            Route::delete('/{user}/delete', 'UserController@destroy');
        });

        Route::get('/403', function () {
            return view('admin.403');
        });
        //Ajax
        Route::get('change-estate-status','EstateController@changeEstateStatus');
    });

});


Route::group(['prefix' => '/photos'], function () {
    Route::get('/', 'ImageController@getPhotos');
    Route::post('/', 'ImageController@uploadPhotos');
    Route::post('/upload', 'ImageController@upload');
    Route::delete('/', 'ImageController@destroyPhoto');
    Route::get('/hide', 'ImageController@hidePhoto');
    Route::get('/set-avatar', 'ImageController@setAvatar');

});
    Route::get('/server-images/{id}', ['as' => 'server-images', 'uses' => 'ImageController@getEstateImages']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '([A-z\d-\/_.]+)?');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


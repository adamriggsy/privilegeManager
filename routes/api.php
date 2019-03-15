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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/child-privilege-feed/{childID}', 'HomeController@childPrivilegesAPI')->name('childPrivilegeFeed');

Route::middleware('auth:api')->prefix('json')->group(function () {
	Route::get('/children-status', 'HomeController@childrenStatus')->name('getChildrenStatusJSON');
	Route::post('/children-status', 'HomeController@childrenStatus')->name('getChildrenStatusJSON');
	Route::get('/logout', 'HomeController@jsonLogout')->name('logoutJSON');
	Route::get('/user', 'UserController@index')->name('getUserJSON');
});

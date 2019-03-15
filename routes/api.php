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

	Route::get('/child/{id}/privilege/ban', 'ChildrenController@banPrivilege')->name('child.privilege.ban.json');
	Route::post('/child/{id}/privilege/ban', 'ChildrenController@banPrivilegeProcess')->name('child.privilege.ban.process.json');
	Route::get('/child/{id}/privilege/restore', 'ChildrenController@restorePrivilege')->name('child.privilege.restore.json');
	Route::post('/child/{id}/privilege/restore', 'ChildrenController@restorePrivilegeProcess')->name('child.privilege.restore.process.json');
});

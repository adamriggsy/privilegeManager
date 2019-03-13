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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
	Route::get('/children-status', 'HomeController@childrenStatus')->name('getChildrenStatus');

	Route::get('/child/{id}/manage', 'ChildrenController@managePrivileges')->name('child.manage');
	Route::get('/child/{id}/privilege/ban', 'ChildrenController@banPrivilege')->name('child.privilege.ban');
	Route::post('/child/{id}/privilege/ban', 'ChildrenController@banPrivilegeProcess')->name('child.privilege.ban.process');
	Route::get('/child/{id}/privilege/restore', 'ChildrenController@restorePrivilege')->name('child.privilege.restore');
	Route::post('/child/{id}/privilege/restore', 'ChildrenController@restorePrivilegeProcess')->name('child.privilege.restore.process');
	Route::get('user/privileges', function(){
		return redirect()->route('user.index');
	});

	Route::resource('child', 'ChildrenController');
	Route::resource('user/privileges', 'PrivilegeController')->except([
	    'index', 'show', 'edit'
	]);
	Route::resource('user', 'UserController')->except([
	    'create', 'destroy'
	]);
});


Route::prefix('json')->group(function () {
	Route::get('/children-status', 'HomeController@childrenStatus')->name('getChildrenStatusJSON');
	Route::get('/login', 'HomeController@jsonLogin')->name('loginJSON');
});




// Route::get('/ajax/child-privilege-feed/{id}', 'ChildrenController@childPrivilegesAJAX')->name('childPrivilegeFeed');
//Route::get('/ajax/nl/child-privilege-feed/{id}', 'HomeController@childPrivilegesAPI')->name('childPrivilegeFeed');

Route::post('/ajax/child-privilege/{id}/update', 'ChildrenController@childPrivilegesUpdate')->name('childPrivilegeUpdate');


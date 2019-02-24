 <?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//  Display login page
Route::get('login', function () {
    return redirect('/');
});

Route::any('/', [
    'as'   => 'backend.auth.login', 
    'uses' => 'AuthController@login'
]);

Route::group(['middleware' => ['auth', 'admin']], function() {


	Route::any('dashboard', [
        'as'   => 'backend.dashboard.index', 
        'uses' => 'DashboardController@index'
    ]);

	Route::any('settings', [
        'as'   => 'backend.settings.index', 
        'uses' => 'SettingController@index'
    ]);
	
	Route::any('account', [
	    'as'   => 'account.index', 
	    'uses' => 'AccountController@index'
	]);
	Route::any('edit-profile', [
	    'as'   => 'account.edit-profile', 
	    'uses' => 'AccountController@editProfile'
	]);
	Route::any('account/upload-cover', [
	    'as'   => 'account.upload-cover', 
	    'uses' => 'AccountController@uploadCover'
	]);
	Route::any('account/signout', [
	    'as'   => 'account.signout', 
	    'uses' => 'AccountController@signout'
	]);

});

Route::any('note/add', [
    'as'   => 'backend.setting.add-note', 
    'uses' => 'SettingController@addNote'
]);
Route::any('note/delete/{id}', [
    'as'   => 'backend.setting.delete-note', 
    'uses' => 'SettingController@deleteNote'
]);

Route::get('logout', [
    'as'   => 'backend.auth.logout', 
    'uses' => 'AuthController@logout'
]);

Route::any('forgot-password', [
    'as'   => 'frontend.forgot-password', 
    'uses' => 'FrontendController@forgotPassword'
]);

//Forgot Password
Route::any('forgot-password/{token?}',[
    'as'    => 'backend.auth.forgot-password',
    'uses'  => 'AuthController@forgotPassword'
]);

Route::any('tds/pdf/view/{id}/{token}',[
	'as' 	=> 'backend.tds.pdf-view',
	'uses'	=> 'TdsController@pdfView'
]);

Route::any('sds/pdf/view/{id}/{token}',[
	'as' 	=> 'backend.sds.pdf-view',
	'uses'	=> 'SdsController@pdfView'
]);
Route::any('request',[
	'as' 	=> 'backend.sds.request',
	'uses'	=> 'SdsController@request'
]);

Route::group(['middleware' => ['auth']], function() {

	Route::group(['prefix' => 'sds', 'middleware' => ['admin']], function() {
		Route::any('/',[
			'as' 	=> 'backend.sds.index',
			'uses'	=> 'SdsController@index'
		]);
		Route::any('view/{id}',[
			'as' 	=> 'backend.sds.view',
			'uses'	=> 'SdsController@view'
		]);
		Route::any('add',[
			'as' 	=> 'backend.sds.add',
			'uses'	=> 'SdsController@add'
		]);
		Route::post('store',[
			'as' 	=> 'backend.sds.store',
			'uses'	=> 'SdsController@store'
		]);
		Route::any('edit/{id}',[
			'as' 	=> 'backend.sds.edit',
			'uses'	=> 'SdsController@edit'
		]);
		Route::post('update/{id}',[
			'as' 	=> 'backend.sds.update',
			'uses'	=> 'SdsController@update'
		]);
		Route::any('destroy/{id}',[
			'as' 	=> 'backend.sds.destroy',
			'uses'	=> 'SdsController@destroy'
		]);
		Route::any('duplicate/{id}',[
			'as' 	=> 'backend.sds.duplicate',
			'uses'	=> 'SdsController@duplicate'
		]);
		Route::any('empty-trash',[
			'as' 	=> 'backend.sds.empty-trash',
			'uses'	=> 'SdsController@emptyTrash'
		]);

	});

	Route::group(['prefix' => 'sds'], function() {
		Route::any('/',[
			'as' 	=> 'backend.sds.index',
			'uses'	=> 'SdsController@index'
		]);
		Route::any('view/{id}',[
			'as' 	=> 'backend.sds.view',
			'uses'	=> 'SdsController@view'
		]);
	});

	Route::group(['prefix' => 'tds', 'middleware' => ['admin']], function() {
		Route::any('/',[
			'as' 	=> 'backend.tds.index',
			'uses'	=> 'TdsController@index'
		]);
		Route::any('view/{id}',[
			'as' 	=> 'backend.tds.view',
			'uses'	=> 'TdsController@view'
		]);
		Route::any('add',[
			'as' 	=> 'backend.tds.add',
			'uses'	=> 'TdsController@add'
		]);
		Route::post('store',[
			'as' 	=> 'backend.tds.store',
			'uses'	=> 'TdsController@store'
		]);
		Route::any('edit/{id}',[
			'as' 	=> 'backend.tds.edit',
			'uses'	=> 'TdsController@edit'
		]);
		Route::post('update/{id}',[
			'as' 	=> 'backend.tds.update',
			'uses'	=> 'TdsController@update'
		]);
		Route::any('destroy/{id}',[
			'as' 	=> 'backend.tds.destroy',
			'uses'	=> 'TdsController@destroy'
		]);
		Route::any('duplicate/{id}',[
			'as' 	=> 'backend.tds.duplicate',
			'uses'	=> 'TdsController@duplicate'
		]);
		Route::any('empty-trash',[
			'as' 	=> 'backend.tds.empty-trash',
			'uses'	=> 'TdsController@emptyTrash'
		]);

	});

	Route::group(['prefix' => 'users', 'middleware' => ['admin']], function() {
		Route::any('/',[
			'as' 	=> 'backend.users.index',
			'uses'	=> 'UserController@index'
		]);
		Route::any('add',[
			'as' 	=> 'backend.users.add',
			'uses'	=> 'UserController@add'
		]);
		Route::post('store',[
			'as' 	=> 'backend.users.store',
			'uses'	=> 'UserController@store'
		]);
		Route::any('edit/{id}',[
			'as' 	=> 'backend.users.edit',
			'uses'	=> 'UserController@edit'
		]);
		Route::post('update/{id}',[
			'as' 	=> 'backend.users.update',
			'uses'	=> 'UserController@update'
		]);
		Route::any('delete/{id}',[
			'as' 	=> 'backend.users.delete',
			'uses'	=> 'UserController@delete'
		]);
		Route::any('profile', [
		    'as'   => 'backend.users.profile', 
		    'uses' => 'UserController@profile'
		]);
	});


});

// , 'middleware' => 'auth'

<?php
Route::group(['prefix' => 'posts'], function() {
	Route::any('/',[
		'as' 	=> 'backend.posts.index',
		'uses'	=> 'PostController@index'
	]);
	Route::any('add',[
		'as' 	=> 'backend.posts.add',
		'uses'	=> 'PostController@add'
	]);
	Route::post('store',[
		'as' 	=> 'backend.posts.store',
		'uses'	=> 'PostController@store'
	]);
	Route::any('edit/{id?}',[
		'as' 	=> 'backend.posts.edit',
		'uses'	=> 'PostController@edit'
	]);
	Route::post('update/{id?}',[
		'as' 	=> 'backend.posts.update',
		'uses'	=> 'PostController@update'
	]);
	Route::any('delete/{id?}',[
		'as' 	=> 'backend.posts.delete',
		'uses'	=> 'PostController@delete'
	]);
});
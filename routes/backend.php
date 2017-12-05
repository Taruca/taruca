<?php
Route::get('index', 'IndexController@index');

Route::get('menus', 'MenusController@index');
Route::get('get_menus', 'MenusController@getMenus');
Route::get('get_menu', 'MenusController@getMenu');
Route::post('get_menu', 'MenusController@setMenu');
Route::post('store_menu', 'MenusController@store');
Route::post('destroy_menu', 'MenusController@destroy');
Route::post('change_parent', 'MenusController@changeParent');

Route::get('users', 'UsersController@index');
Route::get('user', 'UsersController@show');
Route::delete('user', 'UsersController@destroy');
Route::post('user', 'UsersController@store');

Route::get('segments', 'SegmentsController@index');
Route::post('segments', 'SegmentsController@add');
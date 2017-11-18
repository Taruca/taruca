<?php
Route::get('index', 'IndexController@index');
Route::get('menus', 'MenusController@index');
Route::get('get_menus', 'MenusController@getMenus');
Route::get('get_menu', 'MenusController@getMenu');
Route::post('get_menu', 'MenusController@setMenu');
<?php
Route::get('backend/index', 'IndexController@index');
Route::get('backend/menus', 'MenusController@index');
Route::get('backend/get_menus', 'MenusController@getMenus');
Route::get('backend/get_menu', 'MenusController@getMenu');
Route::post('backend/get_menu', 'MenusController@setMenu');
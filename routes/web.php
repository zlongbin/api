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
Route::get('/info', function () {
	    phpinfo();
});
// api测试
Route::post('/api/str',"Api\ApiController@str");
Route::post('/api/array',"Api\ApiController@array");
Route::post('/api/json',"Api\ApiController@json");

Route::get('/request/restrict',"Api\ApiController@restrict")->middleware('Request10times');
Route::post('/api/restrict',"Api\ApiController@restrict")->middleware('Request10times');
// 用户注册、登录
Route::post('/user/reg',"User\UserController@reg");
Route::post('/user/login',"User\UserController@login");

Route::get('/user/my',"User\UserController@my")->middleware(['Request10times','Checklogin']);
Route::post('/user/my',"User\UserController@my")->middleware(['Request10times','Checklogin']);
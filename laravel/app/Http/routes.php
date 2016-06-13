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

Route::get('/','IndexController@index');

Route::get('/home',function(){
	echo '欢迎来到用户中心';
});

Route::get('/auth/register','Auth\AuthController@getRegister');
Route::post('/auth/register',[
	'middleware' => 'App\Http\Middleware\EmailMiddleware',
	'uses' => 'Auth\AuthController@postRegister'
	]);

Route::get('/auth/login','Auth\AuthController@getLogin');
Route::post('/auth/login','Auth\AuthController@postLogin');

Route::get('/auth/logout','Auth\AuthController@getLogout');

Route::get('jie','ProController@jie');
Route::post('jie','ProController@jiePost');
Route::get('pro/{pid}','ProController@pro');
Route::post('touzi/{pid}','ProController@touzi');

Route::get('prolist','CheckController@prolist');
Route::get('check/{pid}','CheckController@check');
Route::post('check/{pid}','CheckController@checkPost');

Route::get('payrun','GrowController@run');
Route::get('myzd','ProController@myzd');
Route::get('mytz','ProController@mytz');
Route::get('mysy','ProController@mysy');

Route::post('pay','ProController@pay');



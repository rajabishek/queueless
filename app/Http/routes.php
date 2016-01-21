<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['namespace' => 'Api\v1','prefix' => 'api/v1'], function ($app)
{	
	Route::group(['namespace' => 'Auth'], function ($app)
	{
		Route::post('register','AuthController@postRegister');
		Route::post('login','AuthController@postLogin');
		Route::get('user', 'AuthController@getUser');
		Route::get('token/validate', 'AuthController@validateToken');
	});
});

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/
Route::group(['middleware' => ['web']], function () {
    
    Route::get('/', ['as' => 'pages.home', 'uses' => 'PagesController@getHome']);

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
    	
    	Route::get('register', ['as' => 'auth.getRegister', 'uses' => 'AuthController@getRegister']);
   		Route::post('register', ['as' => 'auth.postRegister', 'uses' => 'AuthController@postRegister']);

   		Route::get('login', ['as' => 'auth.getLogin', 'uses' => 'AuthController@getLogin']);
   		Route::post('login', ['as' => 'auth.postlogin', 'uses' => 'AuthController@postLogin']);
    
    	Route::get('logout', [ 'as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
    });
});

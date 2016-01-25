<?php

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

Route::group(['domain' => env('APP_DOMAIN', 'queueless.com'), 'middleware' => ['web']], function()
{
    Route::get('/',['as' => 'pages.home','uses' => 'PagesController@getHome']);

    Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function() {
        
        Route::get('register', ['as' => 'auth.getRegister', 'uses' => 'AuthController@getRegister']);
        Route::post('register', ['as' => 'auth.postRegister', 'uses' => 'AuthController@postRegister']);
        Route::get('register/verify/{confirmationCode}',['as' => 'auth.register.confirm','uses' => 'AuthController@confirm']);
    
        Route::get('verification/resend',['as' => 'auth.verification.getResend','uses' => 'AuthController@getResend']);
        Route::post('verification/resend',['as' => 'auth.verification.postResend','uses' => 'AuthController@postResend']);
    });
});

Route::group(['domain' => '{domain}.' . env('APP_DOMAIN','queueless.com'),'middleware' => 'verify-domain'], function()
{
    Route::group(['middleware' => ['web']], function(){

        Route::get('/',function($domain){ return redirect()->route('auth.getLogin',$domain); });
    
        Route::group(['namespace' => 'Auth', 'prefix' => 'auth'], function()
        {
            Route::get('password/email', ['as' => 'password.getEmail', 'uses' => 'PasswordController@getEmail']);
            Route::post('password/email', ['as' => 'password.postEmail','uses' => 'PasswordController@postEmail']);

            Route::get('password/reset/{token}', ['as' => 'password.getReset','uses' => 'PasswordController@getReset']);
            Route::post('password/reset', ['as' => 'password.postReset','uses' => 'PasswordController@postReset']);

            Route::get('login', ['as' => 'auth.getLogin', 'uses' => 'AuthController@getLogin']);
            Route::post('login', ['as' => 'auth.postLogin', 'uses' => 'AuthController@postLogin']);
        
            Route::get('logout', [ 'as' => 'auth.logout', 'uses' => 'AuthController@getLogout']);
        });

        Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'designation:Admin'],function() 
        {
            Route::get('employees/download',['as' => 'admin.employees.getDownload','uses' => 'EmployeesController@getDownload']);
            Route::post('employees/download',['as' => 'admin.employees.postDownload','uses' => 'EmployeesController@postDownload']);
            Route::resource('employees', 'EmployeesController');

            Route::post('settings/changePassword',['as' => 'admin.settings.changePassword','uses' => 'SettingsController@changePassword']);
            Route::post('settings/changeProfile',['as' => 'admin.settings.changeProfile','uses' => 'SettingsController@changeProfile']);
            Route::resource('settings', 'SettingsController',['only' => ['index','store']]);
        });
    });

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

});



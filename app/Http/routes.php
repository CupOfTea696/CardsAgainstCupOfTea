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
    Route::get( '/',    ['as' => 'home', 'uses' => 'HomeController@index'   ]);
    Route::post('join', ['as' => 'join', 'uses' => 'HomeController@join'    ]);
    
    Route::group(['namespace' => 'Auth'], function () {
        Route::get( 'sign/in',  ['as' => 'login',           'uses' => 'AuthController@loginForm'    ]);
        Route::post('sign/in',  ['as' => 'login.do',        'uses' => 'AuthController@login'        ]);
        Route::get( 'sign/up',  ['as' => 'signUp',          'uses' => 'AuthController@signUpForm'   ]);
        Route::post('sign/up',  ['as' => 'signUp.do',       'uses' => 'AuthController@signUp'       ]);
        Route::post('/',        ['as' => 'loginOrSignUp',   'uses' => 'AuthController@loginOrSignUp']);
        Route::get( 'sign/out', ['as' => 'logout',          'uses' => 'AuthController@logout'       ]);
        
        Route::get( 'forgot/password',          ['as' => 'pw.forgot',   'uses' => 'PasswordController@showLinkRequestForm'  ]);
        Route::post('forgot/password',          ['as' => 'pw.email',    'uses' => 'PasswordController@sendResetLinkEmail'   ]);
        Route::get( 'reset/password/{token}',   ['as' => 'pw.reset',    'uses' => 'PasswordController@showResetForm'        ]);
        Route::post('reset/password',           ['as' => 'pw.reset.do', 'uses' => 'PasswordController@reset'                ]);
    });
});

Route::group(['middleware' => ['web', 'has.username']], function () {
    Route::get('lobby', ['as' => 'lobby', 'uses' => 'GameController@lobby']);
    
    Route::get( 'game/create', ['as' => 'room.create',  'uses' => 'GameController@create'   ]);
    Route::post('game/create', ['as' => 'room.store',   'uses' => 'GameController@store'    ]);
    Route::get( 'game/{game}', ['as' => 'room.show',    'uses' => 'GameController@show'     ]);
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get( 'my/account', ['as' => 'account.edit',      'uses' => 'AccountController@edit'  ]);
    Route::post('my/account', ['as' => 'account.update',    'uses' => 'AccountController@update']);
});


Route::get('test', function() {
    return redirect('test/complete');
});
Route::get('test/complete', function() {
    return 'yay';
});
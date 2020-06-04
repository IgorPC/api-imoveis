<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api')->name('api.')->group(function (){
        Route::get('/search', 'RealStateSearchController@index')->name('search');
        Route::get('/search/{RealStateId}', 'RealStateSearchController@show')->name('show');

        Route::post('/login', 'Auth\\LoginJwtController@login')->name('login');
        Route::get('/logout', 'Auth\\LoginJwtController@logout')->name('logout');
        Route::get('/refresh', 'Auth\\LoginJwtController@refresh')->name('refresh');

        Route::group(['middleware' => 'jwt.auth'], function (){
            Route::resource('real-states', 'RealStateController');
            Route::delete('/photos/{id}', 'RealStatePhotoController@destroy');
            Route::put('/photos/{id}/{realState}', 'RealStatePhotoController@setThumb');
            Route::resource('users', 'UserController');
            Route::resource('categories', 'CategoryController');
            Route::get('/categories/{id}/real-states', 'CategoryController@realStates');
        });
});

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

#Route::get('/home', function () {
#    return view('welcome');
#});
Route::get('/login', [
    'as' => 'login',
    'uses' => 'IndexController@login'
]);
Route::match(['get', 'post'], '/home', [
    'as' => 'home',
    'uses' => 'IndexController@home'
#]);
])->middleware('login');


#Route::match(['get', 'post'], '/search', [
Route::post('/search', [
    'as' => 'search',
    'uses' => 'IndexController@search'
])->middleware('login');

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
    //
});

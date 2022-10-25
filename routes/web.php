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

Auth::routes();

/*
|--------------------------------------------------------------------------
| 1) User 認証不要
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('top');});

/*
|--------------------------------------------------------------------------
| 2) User ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['middleware' => 'auth:user'], function() {
    Route::get('/home', 'HomeController@index')->name('home');
});

/*
|--------------------------------------------------------------------------
| 3)　Userプロフィール画面
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('show/{id}', 'UserController@show')->name('users.show');
    Route::get('edit/{id}', 'UserController@edit')->name('users.edit'); // この行を追記
    Route::post('update/{id}', 'UserController@update')->name('users.update'); // この行を追記
});

/*
|--------------------------------------------------------------------------
| 4) User 勤怠画面表示
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('kintai/{id}', 'KintaiController@show')->name('users.kintai');
    Route::post('update/{id}', 'KintaiController@update')->name('users.update');
});

Route::group(['prefix' => 'users', 'middleware' => 'auth'], function () {
    Route::get('jisseki/{id}', 'KintaiController@index')->name('users.jisseki');
});


/*
|--------------------------------------------------------------------------
| 5) Admin 認証不要
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin'], function() {
    Route::get('/',         function () { return redirect('admin/top'); });
    Route::get('login',     'Admin\LoginController@showLoginForm')->name('admin.login');
    Route::post('login',    'Admin\LoginController@login');
});

/*
|--------------------------------------------------------------------------
| 6) Admin ログイン後
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function() {
    Route::post('logout',   'Admin\LoginController@logout')->name('admin.logout');
    Route::get('home',      'Admin\HomeController@index')->name('admin.home');
});

/*
|--------------------------------------------------------------------------
| 7) Admin 勤怠画面表示
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::get('kintai/{id}', 'Admin\KintaiController@show')->name('admin.kintai');
    Route::post('update/{id}', 'Admin\KintaiController@update')->name('admin.update');
});

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {
    Route::get('jisseki/{id}', 'Admin\KintaiController@index')->name('admin.jisseki');
});


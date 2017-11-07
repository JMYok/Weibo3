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

Route::get('/','StaticPageController@home')->name('home');
Route::get('/help','StaticPageController@help')->name('help');
Route::get('/about','StaticPageController@about')->name('about');

//注册路由
Route::get('/signup','UserController@create')->name('signup');

//用户CURD
Route::resource('/users','UserController');

//登录路由
Route::get('/login', 'SessionsController@create')->name('login');
Route::post('/login', 'SessionsController@store')->name('login');
Route::delete('/logout', 'SessionsController@destroy')->name('logout');

//激活码路由
Route::get('/signup/confirm/{token}','UserController@confirmedEmail')->name('confirm_email');

//忘记密码
//显示重制密码显示页
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
//邮箱发送重设链接
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
//密码更新页
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
//执行密码更新操作
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

//微博操作
Route::resource('statuses','StatusesController',['only'=>['store','destroy']]);

//关注人列表和粉丝列表
Route::get('/users/{user}/followings','UserController@followings')->name('users.followings');

Route::get('/users/{user}/followers','UserController@followers')->name('users.followers');
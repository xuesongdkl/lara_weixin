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


//测试（微信）
Route::get('/weixin/u/{openid}','Weixin\WxController@getUserInfo');     //获取用户信息
Route::get('/weixin/gettags','Weixin\WxController@getWxTags');     //获取用户标签
Route::get('/weixin/createtags','Weixin\WxController@createWxTags');     //创建用户标签

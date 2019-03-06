<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->get('/weixin/viewsendmsg', 'Weixin\UserController@sendMsgView');    //消息群发视图
    $router->post('/weixin/sendmsg', 'Weixin\UserController@sendMsg');          //消息群发

    $router->resource('/weixin/users', Weixin\UserController::class);   //用户管理

});

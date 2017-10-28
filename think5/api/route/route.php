<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
Route::get('index','v1/IndexController/index');
Route::any('banner','v1/BannerController/getBanner');
Route::any('register','v1/UserLoginController/register');
Route::any('login','v1/UserLoginController/login');
Route::any('letout','v1/UserLoginController/letOutLogin');
Route::any('token','v1/ApiController/index');
Route::any('userList','v1/UsersController/userList');
Route::any('oxs','v1/UserLoginController/gee');

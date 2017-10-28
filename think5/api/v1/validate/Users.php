<?php
namespace api\v1\validate;
use api\common\validate\Base;

class Users extends Base{
    protected $rule = [
        'name'  =>  'require|unique:users',
        'password' =>  'require',
    ];
    
    protected $message = [
        'name.require'  =>  '用户名必须',
        'name.unique'  =>  '该用户名已经被注册了,请找回密码或者用其他用户名注册!',
        'password.require'      =>  '密码必须',
    ];
    
    protected $scene = [
        'add'   =>  ['name','password'],
        'edit'  =>  ['name'],
    ];
    protected function checkMobile($value)
    {
        return $rule == $value ? true : '名称错误';
    }
}
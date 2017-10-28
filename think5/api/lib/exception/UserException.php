<?php
namespace api\lib\exception;

/**
 * token验证失败时抛出此异常 
 */
class UserException extends BaseException
{
    public $code = 401;
    public $msg = '用户不存在';
    public $errorCode = 60000;
}
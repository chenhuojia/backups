<?php
namespace api\lib\exception;

/**
 * token验证失败时抛出此异常 
 */
class WeChatException extends BaseException
{
    public $code = 999;
    public $msg = '谁谁谁';
    public $errorCode = 60000;
}
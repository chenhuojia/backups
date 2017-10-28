<?php
namespace api\lib\exception;
use api\lib\exception\BaseException;
class BannerMisssException extends BaseException{
    public $code=404;
    public $msg='浏览页面不存在';
    public $errorCode=40000;
}
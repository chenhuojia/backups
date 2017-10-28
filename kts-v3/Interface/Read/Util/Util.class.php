<?php
namespace Order\Util;

class Util
{
    /*
     * 生成定长22位的订单码
     * */
    public static function MyOrderNo22(){
        $code  =date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $code .= randCodeM(22-strlen($code),1);
        return $code;
    }
}


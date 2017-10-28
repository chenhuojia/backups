<?php
namespace Common\Common;

class CheckPhoneCode
{
    public static function CheckCode($op,$phone,$zone,$code){
       import('Common/Common/requests');
       $Osp=array('','');
       $data=\requests::post(C('SMSUrl'),array(
            'appkey' => C('SMSappkey')[$op],
            'phone' => $phone,
            'zone' =>$zone,
            'code' => $code,
         ));
       $p=json_decode($data,true);
       if($p['status']==200) return 200;
       return 300;
    }
}


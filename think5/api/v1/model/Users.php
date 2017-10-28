<?php
namespace api\v1\model;
use think\Model;

class Users extends Model{
    
    protected $pk = 'user_id';
    protected $table = 'users';
    protected $auto = [];
    protected $insert = ['last_ip','password','reg_time','last_login','sex','unionid','appkey'];
    protected $update = ['last_ip','last_login'];
    protected $unionid;
    protected $user_id;
    protected $name;
    protected $appkey;
    protected $field = true;
    public function initialize(){
        parent::initialize();
        $this->unionid=mt_rand();
    }
    protected function setLastIpAttr()
    {
        return request()->ip();
    } 
    
    protected function setRegTimeAttr(){
        return $_SERVER['REQUEST_TIME'];
    }
    protected function setSexAttr(){
        return 1;
    }
    
    protected function setLastLoginAttr(){
        return $_SERVER['REQUEST_TIME'];
    }
    
    protected function setPasswordAttr($value){
        return md5($value.$this->unionid);
    }
    
    protected function setUnionidAttr(){
        return $this->unionid;
    }
    
    protected function setAppkeyAttr(){
        return mt_rand();
    }
    
}
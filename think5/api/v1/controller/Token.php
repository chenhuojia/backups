<?php
namespace api\v1\controller;
use api\v1\logic\UserToken;
use api\common\controller\BaseController;
class Token extends BaseController{
    
    public function getToken($code){
        $token=new UserToken($code);
        return $token->get();
    }
    
}
<?php
namespace api\common\controller;
use think\Controller;
use think\Loader;
use api\lib\exception\ForbiddenException;
use think\Hook;
class ApiController extends Controller
{
    public function __construct()
    {   
        parent::__construct();
        $sign=request()->param('sign','');
        $a=Loader::import('rsa.Token',EXTEND_PATH); 
        $config=array(
            'rsa_private_key_file'       =>  EXTEND_PATH.'/rsa/rsa_private_key.pem',    // 私钥文件
            'rsa_public_key_file'        =>  EXTEND_PATH.'/rsa/rsa_public_key.pem',    // 公钥文件
        );
        $token=new \Token($config);
        if (!$token->get_private_key_sign($sign)){
           throw new ForbiddenException(['msg'=>'不是本站请求','errorCode'=>10003]);
        }
        $result = Hook::exec('api\\common\\behavior\\SqlBehavior','run');
        return message(200, '通过验证');    
    }
    
    
    
}

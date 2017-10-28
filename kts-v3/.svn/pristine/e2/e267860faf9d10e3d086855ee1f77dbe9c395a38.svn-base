<?php
/**
 * Api公共类
 * Interface引用api模式，没有display等view的渲染和页面模版输出
 */
namespace Common\Controller;
use Think\Controller;

class ApiController extends Controller
{
    
    public function _initialize(){
        $config_url=C('Nologin');
        $message=B('Common\Behaviors\AuthCheckBehavior',$config_url);
        if($message['error']==200){
            self::userLogin(session('user_id'));
            return;
        }
        if($message['error']==401){
           $this->myApiPrint(401,$message['message']);
        }
    }

    public function userLogin($user_id)
    {
        $today =date("Y-m-d");
        GetRedisConn()->select(2);
        $login= GetRedisConn()->setbit($user_id."-".$today, $user_id, 1);
        if(empty($login)){
            self::login_integral_log($user_id);
        }
        return 1;  
    }

     function login_integral_log($user_id){  
         $integration=M('integration')->field('source,number')->where(array('identify'=>'login','is_deleted'=>0))->find();
         if(empty($integration)) return 0;
         $Integral=M('user');
         $Integral->startTrans();
         $data=$Integral->where(array('user_id'=>$user_id))->find();
         $result=$Integral->where(array('user_id'=>$user_id))->setInc('integral',$integration['number']);
         $after = $integration['number']+$data['integral'];
         if ($result){
             $arr=array(
                 'user_id'=>$user_id,
                 'update_time'=>time(),
                 'amount'=>$integration['number'],
                 'title'=>$integration['source'],
                 'description'=>date('Y-m-d').'登录获取到积分'.$integration['number'],
                 'is_inc'=>1,
                 'before_change'=>$data['integral'],
                 'after_change'=>$after
             );
             $detail=M('integral_xq')->add($arr);            
         }
         if ($detail && $result){ $Integral->commit();return 1;}
         else{$Integral->rollback();return 0;}   
   }  
     
     
}
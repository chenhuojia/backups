<?php
namespace Common\Controller;
use Think\Controller;

class CommonController extends Controller {
    
    public function _initialize(){
       self::public_assign();
    }

   
    public function public_assign(){    

        $this->success(556);
    }

   protected function time_tran($the_time){
        $now_time = time();
        $dur = $now_time - $the_time;
        if($dur < 0){
            return $the_time;
        }else{
            if($dur < 60){
                return $dur.'秒前';
            }elseif($dur < 3600){
              return floor($dur/60).'分钟前';
            }elseif ($dur < 86400){
                return floor($dur/3600).'小时前';
            }elseif($dur < 259200){
                return floor($dur/86400).'天前';
            }else{
                return date('Y-m-d',$the_time);
            }             
        }         
   }
   
   
   
}
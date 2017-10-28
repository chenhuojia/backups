<?php
namespace api\v1\logic;
use think\Model;

class Users extends Model{
    
    /***
     * 用户列表
     * @return unknown
     * ***/
    public  function getUserList(){
        $data=$this->where('is_lock=:is_lock')
        ->bind(['is_lock'=>0])
        ->limit(10)
        ->field('user_id,name,reg_time,last_login,last_ip')
        ->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['reg_time']=date('Y-m-d H:i:s',$v['reg_time']);
                $data[$k]['last_login']=date('Y-m-d H:i:s',$v['last_login']);
            }
        }
        return $data;
    }
    
    /**
     * 用户登录
     */
    public function userLogin($name,$password){
        $user=$this->where('name = :name')->bind(['name'=>$name])->find();
        if(empty($user)) return message(300, '账号或者密码不存在');
        if($user['password'] != md5($password.$user['unionid'])){
            return message(300, '账号或者密码不存在');
        }
        if($user['is_lock'] == 1){
            return message(300, '该账号已被禁用,请联系客服');
        }
        $data=[
            'user_id'=>$user['user_id'],
            'name'=>$user['name'],
            'appkey'=>$user['appkey'],
        ];
        return message(200, '登录成功',$data);
    }
    
    
}
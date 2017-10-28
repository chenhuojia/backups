<?php
/**
 * 用户登陆方式相关
 *
 */
namespace api\v1\controller;
use api\v1\model\Users as UsersModel;
use think\Session;
use think\Validate;
use api\common\controller\ApiController;
use api\lib\exception\UserException;
use api\v1\model\Base as BaseModel;
class UserLoginController extends ApiController
{  
     
    /**
     * 普通注册
     */
    public function register(){
        $user = new UsersModel();
        $data=request()->param();
        $result=$user->validate(true)->save($data);
        if(false === $result){
            // 验证失败 输出错误信息
            throw new UserException([
                'msg'=>$user->getError(),
            ]);
        }
        $user_id=$user->user_id;
        $token_info=self::userChageToken($user_id,$user->appkey);
        $ret=[
            'user_id'=>$user_id,
            'name'=>$user->name,
            'token'=>$token_info,
            'create_time'=>date('Y-m-d H:i:s',$user->reg_time),
        ];
        self::save($token_info,$ret);
        return json($ret);     
    }
   

    public function gee(){
        $model=new BaseModel();
        return $model::all();

    }
    
    public function login(){
        $name=request()->param('name','');
        $password=request()->param('password','');
        $validate = new Validate([
            'name'  => 'require|max:25',
            'password' => 'require|max:32',
        ]);
        $data = [
            'name'  => $name,
            'password' => $password
        ];
        if (!$validate->check($data)) {
            ajaxReturn(300,$validate->getError());
        }        
        $user= \think\Loader::model('Users','logic');
        $data=$user->userLogin($name, $password);
        if ($data['error']==200){
            $user_id=$data['data']['user_id'];
            $token_info=self::userChageToken($user_id,$data['data']['appkey']);
            $data['data']['token'] = $token_info;
            unset($data['data']['appkey']);
            self::save($token_info,$data['data']);
            return json($data['data']);
        }else{
            throw new UserException([
                'msg'=>$data['message']
            ]);
        }
    }

    /**
     * 退出登录
     * ***/
    public function letOutLogin(){
         Session::delete('user_id');
         ajaxReturn(200,'退出成功');
    }
    
    /**
     * @param unknown $token
     * @param unknown $art  登录通过后，保存用户信息,仅在登录方法实用
     */
    private static function save($token,$art){        
        start_session($token);
        Session::set('user_id',$art);
    }
    
    /**
     * 更改用户的token
     */
    private static function userChageToken($userid,$appkey){
        $strToken = md5($appkey).'-'.$userid.'-'.time();
        //$token = myEncode($strToken);
        return $strToken;
    }



    


}


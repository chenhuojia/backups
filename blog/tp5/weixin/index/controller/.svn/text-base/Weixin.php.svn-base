<?php
namespace weixin\index\controller;
use think\controller;
use weixin\index\common\controller\common;
use think\Config;
use think\Db;
use weixin\index\model\Music;
use Think\Model;
use think\Session;
class Weixin extends common
{
   //授权登录
   public function login(){
       $appid = Config::get('weixinTest_AppID');
       $redict_url=urlencode(Config::get('URL'));
       $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.$redict_url.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
       header("Location:".$url);
   }

   //获取openID
   public function callback(){
       $appid = Config::get('weixinTest_AppID');
       $secret =Config::get('weixinTest_AppSecret');
       $code = request()->get('code');
       $url='https://chenhuojia/xin';
       if (empty($code)){
           $this->error('授权失败',$url,'',1);
       }
       $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
       $res=http_curl($get_token_url);
       $json_obj = json_decode($res,true);
       //根据openid和access_token查询用户信息
       $access_token = $json_obj['access_token'];
       $openid = $json_obj['openid'];
       $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
       $res=httpsRequest($get_user_info_url);
       $user_obj = json_decode($res,true);
       if (!empty($user_obj)){
          $data=self::userAction($user_obj);
          if ($data['code']==300) $this->error($data['msg'],'https://chenhuojia.xin/index.php/Login/login','',1);
          Session::set('userInfo',$data['data']);
          $this->success('登录成功，正在跳转请稍后','https://chenhuojia.xin','',2);
       }
   }
   
   public function qr_code(){
       $url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->test_access_token;
       $data=array(
           'action_name'=>'QR_LIMIT_STR_SCENE',
           'action_info'=>array(
               'scene'=>array('scene_str'=>123),
           ),
       );
       $data=json_encode($data,JSON_UNESCAPED_UNICODE);       
       $result=httpsRequest($url,$data);  
       $result=json_decode($result,true);
       $ticket=urlencode($result['ticket']);
       $url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;  
       $result=httpsRequest($url);
       if ($result){
           /* $f=fopen('/web/www/blog/tp5/public/a.png','w+');
           fwrite($f,$result); fclose($f); */
           echo file_put_contents('/web/www/blog/tp5/public/a.png',$result);
       }
   }
   
   
   public function getUserInfo(){
       $openid = "o48amv0zptc709vLTzlsEi7g0ECk";
       $access_token=$this->test_access_token;   
       //print_r($this->test_access_token);die;   
       $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
       $result=http_curl($url);
       $result=json_decode($result,true);
       print_r($result);  
   }
   
   public function userAction($user_obj=''){      
        $userInfo=Db::query('select u.user_id,u.name,u.avatar,u.profession,u.phone,u.email,u.contact_address,u.login_time,u.is_show from bk_third_party as t left join bk_user u on t.user_id = u.user_id where t.weixin_openid = :appid limit 1',['appid'=>$user_obj['openid']]); 
            $time=$_SERVER['REQUEST_TIME'];
            if (empty($userInfo)){
               $data=[
                   'name'=>$user_obj['nickname'],
                   'password'=>md5(123456),
                   'avatar'=>$user_obj['headimgurl'],
                   'profession'=>'程序员',
                   'phone'=>'13725474374',
                   'email'=>'1126089253@qq.com',
                   'contact_address'=>$user_obj['province'].$user_obj['city'],
                   'addtime'=>$time,
                   'login_time'=>$time,
               ];
               $userId=Db::name('user')->insertGetId($data);
               if ($userId){
                   $sql='insert into bk_third_party (user_id,weixin_openid) values (:user_id,:openid)';
                   Db::execute($sql,['user_id'=>$userId,'openid'=>$user_obj['openid']]);
               }
               unset($data['password']);
               return ['code'=>200,'msg'=>'success','data'=>$data];
            }
            if ($userInfo[0]['is_show']==1){
                if (empty($userInfo['avatar'])){
                    $sql='update bk_user set login_time =:loginTime, avatar=:avatar where user_id = :user_id ';
                }else{
                    $sql='update bk_user set login_time =:loginTime where user_id = :user_id ';
                }  
                Db::execute($sql,['user_id'=>$userInfo[0]['user_id'],'loginTime'=>$time,'avatar'=>$user_obj['headimgurl']]);                
                $userInfo[0]['login_time']=$time;
                return ['code'=>200,'msg'=>'success','data'=>$userInfo[0]];
            }
            return ['code'=>300,'msg'=>'该用户不允许登录'];            
            
   }
   
   public function test(){
       Session::clear();
       //Session::set('name',1234);
       //print_r(Session::get('name'));
   }
}

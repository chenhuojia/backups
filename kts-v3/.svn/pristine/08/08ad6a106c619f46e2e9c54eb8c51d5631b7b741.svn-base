<?php
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller {

    /*
     * 初始化操作
     */
    public function _initialize() {            
        $this->public_assign(); 
    }

    /**
     * 保存公共变量到 smarty中 比如 标题中 
     */
    public function public_assign()
    {  
       $config['keywords']=M('config')->where("config_field= 'KeyWords' and config_type=5")->getField('config_value');
       $config['description']=M('config')->where("config_field= 'Description' and config_type=5")->getField('config_value');
       $config['title']=M('config')->where("config_field= 'Title' and config_type=5")->getField('config_value');   
       $user = session('user');
       // $user=array('user_id'=>35,'name'=>'小小邱','phone'=>'13570315105','imageurl'=>'http://121.196.230.128/kts/Public/pic_hand_img.png');
       //var_dump($user);
       $this->assign('user', $user);               
       $this->assign('config', $config);          
    }

    /* 空操作，用于输出404页面 */
    public function _empty(){
        $this->redirect('Index/index');
    }

 



    /* 用户登录检测 */
    protected function login(){
        /* 用户登录检测 */
        is_login() || $this->error('您还没有登录，请先登录！', U('User/login'));
    }

    // 查询手机号码是否注册
    public function checkPhone(){
       $phone = I('post.phone');
       $where['phone'] = $phone;
       $user = D('user')->checkValue(array('phone'=>$phone));
       if($user){
       	   $data['status']  = 1;
    		   $data['msg'] = '该手机号码已经被注册了,请找回密码或者用其他号码注册！';
    		   $this->ajaxReturn($data);
           //$this->success('该手机号码已经被注册了,请找回密码或者用其他号码注册！',U('Home/Index/index'));
       }else{
       	   $data['status']  = 1;
    		   $data['msg'] = '恭喜,该号码可以使用!';
    		   $this->ajaxReturn($data);
       }
    }

    // 验证码
    public function verify()
    {
        //验证码类型
        $type = I('get.type') ? I('get.type') : '';
        $fontSize = I('get.fontSize') ? I('get.fontSize') : '40';
        $length = I('get.length') ? I('get.length') : '4';
        
        $config = array(
            'fontSize' => $fontSize,
            'length' => $length,
            'useCurve' => true,
            'useNoise' => false,
        );
        $Verify = new Verify($config);
        $Verify->entry($type);        
    }

    

    



}
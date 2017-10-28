<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends BaseController {
    public $user_id = 0;
    public $user = array();
  
    public function _initialize() {      
        parent::_initialize();
        if(session('?user'))
        {
          $user = session('user');
          $user = M('user')->where("user_id = {$user['user_id']}")->field('user_id,name,phone,token,type')->find();
          $user['imageurl'] = M('user_xq')->where(array('user_id'=>$user['user_id']))->getField("imageurl");
          session('user',$user);  //覆盖session 中的 user               
          $this->user = $user;
          $this->user_id = $user['user_id'];
          $this->assign('user',$user); //存储用户信息
          $this->assign('user_id',$this->user_id);
        }else{
          $nologin = array(
              'login','logout','checkPhone','checkName','threelogin','set_pwd','finished','reg',
          );
          if(!in_array(ACTION_NAME,$nologin)){
            header("location:".U('Home/User/login'));
            exit;
          }
        }      
    }

    /*
     * 用户中心首页
     */
    public function index(){
        $this->display();
    }
    public function login(){
     if($this->user_id > 0){
            header("Location: ".U('Home/User/Index'));
     }    
     if(IS_POST){
         $phone = I('post.phone');
         $password = md5(I('post.password'));
         $user = M("user")->where(array('phone'=>$phone,'password'=>$password))->field('user_id,name,phone,token,type')->find();
         if($user){
             //更新用户详情的信息
             $ins_data['source'] = "PC" ;
             $ins_data ['last_login_time'] = time();
             $ins_data ['last_login_ip'] = get_client_ip();;
             M('user_xq')->where('user_id=' . $user['user_id'])->data($ins_data)->save();
             // $this->success('登录成功', U('Home/Index/index')); 
             session('user',$user);
             $data['status'] = 1;
             $data['msg'] = "";
             $data['url'] = U('Home/Index/Index');
             $this->ajaxReturn($data,"JSON");
         }else{
             $data['status'] = 0;
             $data['msg'] = "账号或者密码错误,请重新登录!";
             $data['url'] = "Home/User/login";
             $this->ajaxReturn($data,"JSON");
            // $this->error('账号或者密码错误,请重新登录!'); 
         }
      }
        $this->display();
    }

    /**
     *  注册
     */
    public function reg(){
      
        if(IS_POST){
            $phone = I('post.phone','');
            $name = I('post.name','');
            $password = md5(I('post.password',''));
            $data['phone'] =$phone;
            $data['name'] =$name;
            $data['password'] =$password;
            $user_id=D("user")->addData($data);
            if ($user_id > 0){
                $userDetail['user_id'] = $user_id;
                $userDetail['imageurl'] = C('HAND_IMG_PATH');
                $userDetail['create_time'] = time();
                $ip = get_client_ip();
                //$ins_data['from'] = $from;
                $userDetail ['last_login_time'] = time();
                $userDetail ['last_login_ip'] = $ip;
                $userDetail['source'] ='PC';
                $userInfo = M('user_xq')->data($userDetail)->add();
                session('uid', $userDetail ['user_id']);
                session('phone', $data ['phone']);
                session('imageurl', $userDetail ['imageurl']); // 头像
                session('name', $data ['name']); //帐号名

                $data['status'] = 1;
                $data['msg'] = "";
                $data['url'] = U('Home/User/login');
                $this->ajaxReturn($data,"JSON");
                //$this->success('注册成功！',U('Home/User/index'));                                                                                                      
            }else{
                $data['status'] = 0;
                $data['msg'] = "网络失败，请刷新页面后重试!";
                $data['url'] = "";
                $this->ajaxReturn($data,"JSON");
            }
         }   
        $this->display();
    }

    /*用户手机号码检验*/
    public function checkPhone()
    {
       $phone = I('post.phone');
       $user = M('user')->where(array('phone'=>$phone))->find();
       if($user){
           $data['status'] = 0;
           $data['msg'] = "手机号码已经被注册";
           $this->ajaxReturn($data,"JSON");
       }else{
           $data['status'] = 1;
           $data['msg'] = "恭喜,该号码可以使用!";
           $this->ajaxReturn($data,"JSON");
       }
        
    }
    /*用户昵称检验*/
    public function checkName()
    {
       $name = I('post.name');
       $user = M('user')->where(array('name'=>$name))->find();
       if($user){
           $data['status'] = 0;
           $data['msg'] = "该昵称已经存在！";
           $this->ajaxReturn($data,"JSON");
       }else{
           $data['status'] = 1;
           $data['msg'] = "恭喜,该昵称可以使用!";
           $this->ajaxReturn($data,"JSON");
       }
        
    }

    /**
     * 用户注销登录
     */
    public function Logout()
    {
        session(null);
        $this->success('退出成功!', U('Home/User/Login'));
    }

    public function modifyPassword()
    {
       $phone = I('post.phone');
       $user_id=I('post.user_id');
       $password=I('post.password');
       $map['phone'] = $phone;
       $map['user_id'] = $user_id;
       $data['password'] =$password;
       $lastId=D("user")->editData($map,$data);
       if($lastId){
         session(null);
         $this->success('注册成功！',U('Home/Login/login'));
       }else{
          $this->error('退出失败，请重新操作！');
       }
    }

    public function forgetPwd(){
      if($this->user_id > 0){
        header("Location: ".U('Home/User/Index'));
      }
      if(IS_POST){
        $username = I('post.username');
        $code = I('post.code');
        $new_password = I('post.new_password');
        $confirm_password = I('post.confirm_password');
        $pass = false;
      
        //检查是否手机找回
        if(check_mobile($username)){
          if(!$user = get_user_info($username,2))
            $this->error('账号不存在');
          $check_code = $logic->sms_code_verify($username,$code,$this->session_id);
          if($check_code['status'] != 1)
            $this->error($check_code['msg']);
          $pass = true;
        }
        //检查是否邮箱
        if(check_email($username)){
          if(!$user = get_user_info($username,1))
            $this->error('账号不存在');
          $check = session('forget_code');
          if(empty($check))
            $this->error('非法操作');
          if(!$username || !$code || $check['email'] != $username || $check['code'] != $code)
            $this->error('邮箱验证码不匹配');
          $pass = true;
        }
        if($user['user_id'] > 0 && $pass)
          $data = $logic->password($user['user_id'],'',$new_password,$confirm_password,false); // 获取用户信息
        if($data['status'] != 1)
          $this->error($data['msg'] ? $data['msg'] :  '操作失败');
        $this->success($data['msg'],U('Home/User/login'));
        exit;
      }
        $this->display();
    }


     public function threeLogin($type = null)
    {
        vendor('ThinkSDK.ThinkOauth#class');
        empty($type) && $this->error('参数错误');

        $sns = \ThinkOauth::getInstance($type);
        redirect($sns->getRequestCodeURL());
    }

    //授权回调地址
    public function callback($type = null, $code = null){
        (empty($type) || empty($code)) && $this->error('参数错误');
        
        //加载ThinkOauth类并实例化一个对象
        vendor('ThinkSDK.ThinkOauth#class');
        $sns  = \ThinkOauth::getInstance($type);

        //腾讯微博需传递的额外参数
        $extend = null;
        if($type == 'tencent'){
            $extend = array('openid' => $_GET['openid'], 'openkey' => $_GET['openkey']);
        }

        //请妥善保管这里获取到的Token信息，方便以后API调用
        //调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
        //如： $qq = ThinkOauth::getInstance('qq', $token);
        $token = $sns->getAccessToken($code , $extend);
        //获取当前登录用户信息
        if(is_array($token)){
            if($type=='qq')
            {
                $qq   = \ThinkOauth::getInstance('qq', $token);
                $data = $qq->call('user/get_user_info');

                if($data['ret'] == 0){
                    echo("<h1>恭喜！使用 {$type} 用户登录成功</h1><br>");
                    echo("授权信息为：<br>");
                    dump($token);
                    echo("当前登录用户信息为：<br>");
                    dump($data);
                    //$this->calllogin('qq',$token['openid'],$data['nickname'],$data['figureurl_2']);
                } else {
                    throw_exception("获取腾讯QQ用户信息失败：{$data['msg']}");
                }
            }
        }
    }

    public function calllogin($type,$openid,$nickname,$touxiang)
    {
        //登录数据处理
        //增加用户快捷登录绑定表，数据提交到此表与用户id关联，做判断处理。此处代码省略。。。。
    }

       /**
        *我的分享
        * **/       
       public function sharedBook($n = '12'){
           $user_id=$this->user_id;
           $where['s.user_id']=$user_id; 
           $order='s.share_time desc';
           $count =M('share')->alias('s')                 
                 ->join('left join kts_book as b on b.book_id = s.book_id')
                 ->where($where)->count();
           $Page = new \Think\Page($count, $n);
           $this->Page = $Page->show();
           $data=M('share')->alias('s')                 
                 ->join('left join kts_book as b on b.book_id = s.book_id')
                 ->join('left join kts_image as m on m.book_id = s.book_id')
                 ->field('s.share_id,b.book_id,b.type,b.name as book_name,b.author,b.username,b.imageurl as user_img,m.imageurl as book_imageurl')
                 ->group('s.book_id')
                 ->where($where)->order($order)->limit($Page->firstRow . ',' . $Page->listRows)->select();
           $this->data = $data;
           $this->assign('empty','<span style="margin-left:50px;;">没有数据</span>');
           $this->display();
       }

    /**
     * 热门书评相关展示
     */
    public function bookReview($n = '6')
    {
        $map['t.is_show'] = 1;
        $map['t.fid'] = 0;//父级评论
        $map['t.user_id']=$this->user_id;
        $count =M('book_comment')->alias('t')                 
                 ->where($map)->count();
        $Page = new \Think\Page($count, $n);
        $this->Page = $Page->show();
        $va=M('book_comment')
                ->alias('t')
                ->join('LEFT JOIN kts_book as b ON t.book_id = b.book_id')
                ->join('LEFT JOIN kts_comment_click as l ON t.comment_id = l.comment_id')
                ->where($map)
                ->field('t.comment_id,t.book_id,t.comment_time,t.content,t.grade,t.sums,b.name,b.author,t.username,t.imageurl as user_image,l.sum as likes')
                ->order('t.sums desc')
                ->limit($Page->firstRow . ',' . $Page->listRows)
                ->select();
        foreach ($va as $key => $value) {
          $va[$key]['is_like'] = 0;
          //查询某书评下某用户已经点赞
          $likesmap =array('user_id' =>$user_id,'comment_id'=>$va[$key]['comment_id']);
          $is_like = M('book_comment_click')->where($likesmap)->find();
          if($is_like){
            $va[$key]['is_like'] = 1;
          }
          //查询某书评下的第一张图片
          $imagemap =array('book_id'=>$va[$key]['book_id'],'type'=>1);
          $va[$key]['book_image'] = M('image')->where($imagemap)->getField('imageurl');
          if($va[$key]['book_image']==null){
            $va[$key]['book_image'] ="";
          } 
         }
         $this->data = $va;
         $this->assign('empty','<span style="margin-left:50px;;">没有数据</span>');
         $this->display();         
       
    }
     /**
     * 我的积分
     */   
     public function mypoints()
     {
         $where['user_id']=$this->user_id;
         $map['is_deleted'] =0;
         $data=M('integration')                
                 ->field('id,source,update_time,number,icon,state')
                 ->where($map)->select(); 
         $data1=M('coupon')
               ->field('id,name,money,integration')
               ->where(array('use_end_time'=>array('gt',time()),'use_send_time'=>array('gt',time()),'createnum'=>array('gt',0)))
               ->select(); 
         foreach ($data1 as $key => $value) {
             $data1[$key]['money'] = (int)$data1[$key]['money'];
         }
         
         //var_dump($data);    
         $this->assign('task',$data); 
         $this->assign('coupon',$data1);
         $this->assign('empty','<span style="margin-left:50px;;">没有数据</span>');
         $this->display();     
     }

     /**
     * 用户地址列表
     */   
     public function address()
     {
         //$where['user_id']=$this->user_id;
         $where['user_id']=9;
         $data=M('user_address')                
                 ->field('address_id,user_id,postcode,province,city,area,address,street,consignee,mobile,is_default')
                 ->where($map)->select(); 
         $this->assign('address',$data); 
         $this->assign('empty','<span style="margin-left:50px;;">没有数据</span>');
         $this->display();     
     }

    /*
     * 添加地址
     */
    public function addAddress(){
        header("Content-type:text/html;charset=utf-8");
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,0,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');
            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $this->assign('province',$p);
        $this->display('edit_address');

    }

    /*
     * 地址编辑
     */
    public function editAddress(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id');
        $address = M('user_address')->where(array('address_id'=>$id,'user_id'=> $this->user_id))->find();
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,$id,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');

            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        //获取省份
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $c = M('region')->where(array('parent_id'=>$address['province'],'level'=> 2))->select();
        $d = M('region')->where(array('parent_id'=>$address['city'],'level'=> 3))->select();
        if($address['twon']){
          $e = M('region')->where(array('parent_id'=>$address['district'],'level'=>4))->select();
          $this->assign('twon',$e);
        }

        $this->assign('province',$p);
        $this->assign('city',$c);
        $this->assign('district',$d);
        $this->assign('address',$address);
        $this->display();
    }

    /*
     * 设置默认收货地址
     */
    public function setAddrDefault(){
        $id = I('get.id');
        M('user_address')->where(array('user_id'=>$this->user_id))->save(array('is_default'=>0));
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$id))->save(array('is_default'=>1));
        if(!$row)
            $this->error('操作失败');
        $this->success("操作成功");
    }
    
    /*
     * 地址删除
     */
    public function delAddress(){
        $id = I('get.id');
        $address = M('user_address')->where("address_id = $id")->find();
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$id))->delete();                
        // 如果删除的是默认收货地址 则要把第一个地址设置为默认收货地址
        if($address['is_default'] == 1)
        {
            $address2 = M('user_address')->where("user_id = {$this->user_id}")->find();            
            $address2 && M('user_address')->where("address_id = {$address2['address_id']}")->save(array('is_default'=>1));
        }        
        if(!$row)
            $this->error('操作失败',U('User/address_list'));
        else
            $this->success("操作成功",U('User/address_list'));
    }




}
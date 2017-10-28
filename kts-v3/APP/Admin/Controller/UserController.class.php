<?php
namespace Admin\Controller;
use Common\Controller\AdminController;

class UserController extends AdminController {
    
    
    public function verify(){
    header("content-type: image/png");
        $config = array(
            'fontSize' => 16, // 验证码字体大小
            'length' => 4, // 验证码位数
            'imageH' => 40,
            'imageW' => 120,
        );
        ob_end_clean();
        //exit;
        $Verify = new \Think\Verify($config);
        $Verify->entry();
         
    }
    
   /**
    * 验证验证码
    * @param  [int] $code  验证码
    * @param  string $id   id
    * @return [mix]        1为成功
    */
   public function check_verify($code, $id = ''){
     $verify = new \Think\Verify();
     return $verify->check($code, $id);
   }
    
    public function Login()
    {   

        if (IS_POST) {
            $data ['user_name'] = trim(I('post.username'));
            $password = md5(trim(I('post.password')));
            /* $verify = trim(I('post.verify'));
            $checkcode =$this->check_verify($verify);
            if($checkcode!=1){
                $this->error('验证码错误，请重新输入！');
            }  */
            $find = M('system_user')->where($data)
            ->field('user_id,user_name,password,status,remark,last_login_time,province_id,city_id')
            ->find();
            //有数组并且密码不为错
            if ($find && ($password === $find ['password'])) {
                if ($find ['status'] == 1) {
                    // 登录成功，设置session
                    session('uid', $find ['user_id']);
                    session('remark', $find ['remark']);
                    session('userName', $find ['user_name']); // 帐号名
                    session('last_login_time', $find ['last_login_time']); // 上次登录时间
                    session('provinceId', $find ['province_id']); // 省
                    session('cityId', $find ['city_id']); // 市
    
                    //更新数据
                    $ip = get_client_ip(); // 本次登录IP，时间，登录位置
                    $Ip = new \Org\Net\IpLocation();
                    $area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
                    $upData ['last_login_time'] = date('Y-m-d H:i:s', time());
                    $upData ['last_login_ip'] = $ip;
                    $upData ['last_location'] = $area ['country'] . $area ['area'];
                    M('system_user')->where('user_id=' . $find ['user_id'])->data($upData)->save();
                    //跳转回到来源页面
                    $link = session('link');
    
                    if (!empty($link) || isset($link)) {
                        session('link', null);
                        //$this->addLog('login_id='.$find ['user_id'].'&ip='.$ip,1);// 记录操作日志
                        $this->insert_log($find ['user_id'],$find ['user_name'],'',1,$ip,$upData ['last_location']);// 记录登录日志
                        $this->success('登录成功，返回来源页', U($link));
                    } else {
                        $this->insert_log($find ['user_id'],$find ['user_name'],'',1,$ip,$upData ['last_location']);// 记录登录日志
                        $this->redirect('Admin/Index/index',array(),0);
                    }
                } else {
                    //$this->addLog('login_id='.$find ['user_id'].'&ip='.get_client_ip(),0);// 记录操作日志
                    $this->insert_log($find ['user_id'],$find ['user_name'],'',0,get_client_ip());// 记录登录日志
                    $this->error('帐号审核中！');
                }
            } else {
                $this->error('帐号密码错误！');
            }
        } else {
            $this->display();
        }
    }
    
    //登录跳转
    public function dispatch()
    {
        echo I('post.name');
        echo file_get_contents ( 'php://input' );
    }
    
    /**
     * 用户注销登录
     */
    public function Logout()
    {
        session(null);
        $this->success('退出成功。', U('Admin/User/Login'));
    }

   
    /**
     * 学员列表
     */
    public function userList($n = '15')
    {
        $searchKey = I('get.searchKey');
        if ($searchKey){
            $searchType = I('get.searchType',0);
            switch($searchType)
            {
                case 1:
                    $searchType= 'phone';
                    break;
                default:
                    $searchType = 'name';
                    break;
            }
            $where[$searchType] = array('like','%'.$searchKey.'%');
        }
        $where['s.is_show']=0;
        $userList = M('user');
        if (I('get.newuser')){
            date_default_timezone_set("Asia/Shanghai");
            $time=date("Y-m-d 00:00:00");
            $deadline=date("Y-m-d 24:00:00");
            $news=strtotime($time);
            $dead=strtotime($deadline);
            $where['create_time']=array(array('gt',$news),array('lt',$dead));
            $count = $userList->alias('s')->join('left join kts_user_xq x on x.user_id=s.user_id')->where($where)->count();
        }elseif (I('get.IOS')){
            $where['x.source']='IOS';
            $count=$userList->alias('s')
                    ->join('left join kts_user_xq x on x.user_id = s.user_id')
                    ->where($where)->count();
        }elseif(I('get.Android')){
            $where['x.source']='Android';
            $count=$userList->alias('s')->join('left join kts_user_xq x on x.user_id=s.user_id')->where($where)->count();
        }else{
            $count = $userList->alias('s')->where($where)->count();
        }                       
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $userList = $userList->alias('s')
            ->join('LEFT JOIN kts_user_xq x ON x.user_id = s.user_id')
            ->join('LEFT JOIN kts_integral i ON i.user_id = s.user_id')
            ->join('LEFT JOIN kts_money m ON m.user_id = s.user_id')
            ->join('LEFT JOIN kts_city c ON c.city_id = x.city')
            ->field('s.user_id,s.name,s.phone,x.sex,x.imageurl,x.last_login_time,x.source,c.city,s.type,
            i.num as integral,m.num as money,x.shop_name,x.introduce,x.create_time,x.bind_qq,x.bind_wechat')
                        ->where($where)->order('s.user_id desc')->group('s.user_id')->limit($Page->firstRow.','.$Page->listRows)->select();           
        foreach ($userList as $k => $v){
            $userList[$k]['share_count']=M('share')->where(array('user_id'=>$v['user_id']))->count();
            //$userList[$k]['collect_count']=$user_info['coupon_count'] =      
            $userList[$k]['groups_count']=M('user_groups')->where('user_id= %d',$userList[$k]['user_id'])->count();
            $userList[$k]['coupon_count']=M('coupon_list')->where("uid ={$v['user_id']} and use_time = 0")->count(); //获取优惠券列表    
            if ($userList[$k]['imageurl']==null){
                $userList[$k]['imageurl']='Public/Upload/User/10016.gif';
            }
            if ($userList[$k]['integral']==null){
                $userList[$k]['integral']=0;
            }
/*             if($userList[$k]['last_login_ip']==null){
                //$userList[$k]['last_login_ip']="0.0.0.0";
            } */
            if($userList[$k]['money']==null){
                $userList[$k]['money']=0;
            }
            $userList[$k]['last_login_time']=date('Y-m-d H:i:s',$userList[$k]['last_login_time']);
        }
        //var_dump($userList);                
        $this->list = $userList;
        
        $this->display();
    }
    
    /**
     * 学员注册
     */
    public function userAdd()
    {    
        if (IS_POST)
        {                
            $user=D('user');
            if ($user->where(array('phone'=>I('post.phone')))->find())
                $this->error('该账号已注册');
            if ($_FILES['img']['tmp_name']){
                $attr['imageurl']=C('QINIU').$this->uploadF($_FILES['img']['tmp_name']);
            }else{
                $attr['imageurl']=C('HAND_IMG_PATH');
            }
            $data['name']=I('post.name');
            $data['phone']=I('post.phone');
            $data['password']=md5(I('post.user_password'))?md5(I('post.user_password')):md5(123456);            
            $data['type']=I('post.type');           
            if (!$user->create($_POST,1)){
                $this->error($user->getError());
            }  
             $user_id=$user->data($data)->add();
             if ($data['type']==1){
                 $attr['shop_name']=I('post.shop_name');
             }
             $attr['user_id']=$user_id;
             $attr['create_time']=time();
             $attr['sex']=I('post.sex');
             $attr['source']='Android';
             $ret=D('user_xq')->data($attr)->add();
            if($user_id){
                if ($ret){
                    $this->success('注册成功', U('Admin/User/userList'));
                }
                //$this->addLog('user_nickname='.I('post.nickname').'&user_id='.$result,1);// 记录操作日志
            }else{
                //$this->addLog('user_nickname='.I('post.nickname').'&user_id='.$result,0);// 记录操作日志
                $this->error('注册失败');
            }
        }
        else
        {   
            //
            $this->display();
        }
    }

    /**
     * 修改学员密码
     */
    public function userChangepwd($user_id)
    {
        if (IS_POST)
        {
            $oldPassword = I('post.old_password', '', 'trim');
            if (empty($oldPassword))
                $this->error('请输入旧密码');
            $newPassword = I('post.user_password', '', 'trim');
            $new_PwdConfirm = I('post.new_password2', '', 'trim');
            //var_dump($new_PwdConfirm,$newPassword);die;
            if ($newPassword != $new_PwdConfirm)
                $this->error("两次密码不相同！");
            $user = M('user');
            $where['user_id'] = $user_id;
            $userPwd = $user->where($where)->getField('password');
            if (empty($userPwd))
            {
                $this->error('该会员不存在');
            }
            if ($userPwd !== md5($oldPassword))
            {
                $this->error('原始密码错误');
            }
            $_POST['password'] = md5($newPassword);            
            if (!$user->create($_POST,2)){
                $this->error($user->getError());
            }
            if (false!==$user->where('user_id = ' . $user_id)->save()) {
                //$this->addLog('user_id='.$user_id,1);// 记录操作日志
                $this->success("密码修改成功！", U("Admin/User/userList"));
            } else {
                //$this->addLog('user_id='.$user_id,0);// 记录操作日志
                $this->error("密码修改失败！");
            }
        }
        else
        {
            $userdent = M('user');
            if ($user_id)
            {
                $user = $userdent->field('user_id,name,phone')->find($user_id);
                $this->user = $user;
            }
            $this->display();
        }
    }
    
    /**
     * 修改用户信息
     */
    public function userEdit($user_id)
    {
        $user = M('user');   
        if (IS_POST)
        {      
            $attr['user_id']=$user_id;
            if (I('post.father_id')){
                if ((int)I('post.father_id')==9999){
                    $attr['city']=null;
                }else{
                    $attr['city']=I('post.father_id');
                }  
            }
            if (I('post.class_id')){
                if ((int)I('post.class_id')==9999){
                    $attr['city']=null;
                }else{
                    $attr['city']=I('post.class_id');
                }
            }
            if (I('post.shop_name')!=null){
                $type=1;
                $attr['shop_name']=I('post.shop_name');
            }else {
                $type=0;
            } 
            $attr['bind_qq']=I('post.bind_qq');
            $attr['bind_wechat']=I('post.bind_wechat');
            if (I('post.type')==1){
                $attr['shop_name']=I('post.shop_name');
            }            
            $attr['introduce']=I('post.introduce');
            if (!$user->create($_POST,2)){
                $this->error($user->getError());
            }
            if (!empty($_FILES['imageurl']['tmp_name'])){
                $attr['imageurl']=$this->uploadF($_FILES['imageurl']['tmp_name']);
                $attr['imageurl']=C('QINIU').$attr['imageurl'];
                //var_dump($attr);die;
                //$attr['imageurl']=$this->uploadFile(1)[0];               
                $oldimg=I('post.oldimg');
                $oldimg=substr($oldimg, -14);
                if ($oldimg){
                    $this->del($oldimg);
                }
            } 
            $info=array(
                'name'=>I('post.name'),
                'phone'=>I('post.phone'),
                'type'=>$type,
            ); 
            $attr['sex']=I('post.sex');             
            $result = $user->where('user_id = '.$user_id)->save($info);
            //print_r($attr);die;
            if($result !== false){
                $xq=M('user_xq');                               
                $ret=$xq->where('user_id = '.$user_id)->find();
                if ($ret){
                    $xq->where('user_id = '.$user_id)->save($attr);
                }else{
                    $xq->data($attr)->add();
                }
                
                //$this->addLog('user_nickname='.I('user_nickname').'&user_id='.$user_id,1);// 记录操作日志
                S(md5($user_id)."userInformation",null);
                $this->success('编辑成功', U('Admin/User/userList'));
            }else{
                //$this->addLog('user_nickname='.I('user_nickname').'&user_id='.$user_id,0);// 记录操作日志
                $this->error('编辑失败');
            }
        }
        else
        {
            if ($user_id)
            {   
                $this->province=M('province')->where(array('open'=>1))->field('province_id,province')->select();
                $data=$user->alias('a')
                    ->join('LEFT JOIN kts_user_xq x ON a.user_id = x.user_id')
                    ->join('left join kts_city c on x.city=c.city_id')
                    ->where('a.user_id='.$user_id)->group('a.user_id')->field('a.user_id,a.name,a.phone,a.type,x.sex,x.imageurl,x.city,c.father,x.bind_qq,x.bind_wechat,x.introduce,x.shop_name')->find();
               $this->user =$data;               
            }
            //print_r($data);
            $this->display();
        } 
    }
    

    /**
     * 删除学员
     */   
    public function userDel()
    {
        $user = M('user');
        $user_attr=M('user_xq');
        $user_id=I('get.user_id');
        $where['user_id'] = $user_id;
        $data['is_show']=1;
        if ($user->where($where)->save($data))
        {     
            S(md5($user_id)."userInformation",null);
           $this->success('删除成功', U('Admin/User/userList'));
        } else {
            $this->error('删除失败');
        }
    }
    
    public function userDet()
    {   $user=M('user');
        $user_id=I('get.user_id');
        $this->province=M('province')->where(array('open'=>1))->field('province_id,province')->select();
        $data=$user->alias('a')
        ->join('LEFT JOIN kts_user_xq x ON a.user_id = x.user_id')
        ->join('left join kts_city c on x.city=c.city_id')
        ->where('a.user_id='.$user_id)->group('a.user_id')->field('a.user_id,a.name,a.phone,a.type,x.sex,x.imageurl,x.city,c.father,x.bind_qq,x.bind_wechat,x.introduce,x.shop_name')->find();
        $this->user =$data;
        $this->display();
    }

    /**
     * 查询用户名，返回模糊搜索数据
     */
    public function ajaxSearchbyname() {
        $name = I('q', '');
        $page = I('page',0);
        $condition = array();
        if (!empty($name)) {
            $condition['name'] = array('like', "%$name%");
        }
        $userLogic = M('user');
        $res=$userLogic
                        ->where($condition)
                        ->field('user_id,name')
                        ->limit($page . ',' . 100)
                        ->select();
        $resultStrArr = array();
        foreach ($res as $item) {
            array_push($resultStrArr, json_encode(array('rid' => $item['user_id'], 'name' => $item['name'])));
        }
        //用\n换行符隔开的json
        exit(implode("\n", $resultStrArr));
    }
    
    /**
     * 重置密码
     */
    public function userResetpwd($user_id)
    {
        $where['user_id'] = $user_id;
        $data['password'] = md5('123456');
        $userdent = M('user')->where($where)->data($data)->save();

        if ($userdent !== false)
        {
            //$this->addLog('$user_id = '.$user_id.', reset pwd.',1);
            $this->success('重置密码成功');
        }
        else
        {
            $this->addLog('user_id = '.$user_id.', reset pwd.',0);
            $this->error('重置密码失败');
        }
    }

    /**
     * 学员收藏列表
     */
    public function userCollect($user_id="",$n='15')
    {
        $collect = M('collect');
        $searchKey = I('get.searchKey');
        if ($searchKey){
            
            $searchType = I('get.searchType',0);
            switch($searchType)
            {
                case 1:
                    $searchType= 'b.author';
                    
                    break;
                default:
                    $searchType = 'b.name';
                    
                    break;
            }
            $where[$searchType] = array('like','%'.$searchKey.'%');
        }
         if (I('get.type')){
            $where['c.type']=I('get.type');
        }
       
        $collect = M('collect');
        $where['c.user_id'] = $user_id ? $user_id : I('get.user_id');      
        $where['c.is_show']=1;
       
        $count = $collect->alias('c')
            ->join('LEFT JOIN kts_book as b ON b.book_id = c.book_id')
            ->where($where)->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        if ((int)$where['c.type']==0){
            
            $where['c.type']=1;
        }
        $list['book'] = $collect->alias('c')
            ->join('LEFT JOIN kts_book as b ON b.book_id = c.book_id')
            ->join('LEFT JOIN kts_book_attr as a ON a.book_id = c.book_id')
            ->where($where)
            //->field('c.book_id,c.collect_id,c.type,c.collect_time,b.name as book_name,b.author')
            //->group('b.name')
            ->order('c.collect_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        if ((int)$where['c.type']==1){
            
            $where['c.type']=0;
        }
        $list['old_book']=$collect->alias('c')
            ->join('LEFT JOIN kts_old_book as b ON b.book_id = c.book_id')
            ->join('LEFT JOIN kts_book_attr as a ON a.book_id = c.book_id')
            ->where($where)
            //->field('c.book_id,c.collect_id, c.type,c.collect_time,b.name as book_name,b.author')
            //->group('b.name')
            ->order('c.collect_time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $list['user_id']=$user_id;
        $this->list=$list;
        //print_r($list);
        $this->display();
    }

    /**
     * 取消收藏
     */
public function collectDel($collect_id,$type)
    {
        $collect = M('collect');
        $where['c.collect_id'] = $collect_id;        
        $c_book_id = $collect->alias('c')->where($where)->getField('book_id');
        $user_id = $collect->alias('c')->where($where)->getField('user_id');
        if ((int)$type==0){
            $book_id=D('book')->where("book_id=%d",$c_book_id)->getField('book_id');
        }elseif((int)$type==1){
            $book_id=D('old_book')->where("book_id=%d",$c_book_id)->getField('book_id');
        }
        if ($c_book_id != $book_id){
            $this->error("收藏ID错误");
        }
        $result=$collect->alias('c')->where($where)->save(array('is_show'=>0));    
        if ($result){
            $this->success("取消收藏成功",U('Admin/User/userCollect?user_id='.$user_id));
        }else {
            $this->error('取消收藏失败');
        }
       

        /* if ($collect->where($where)->delete($co_id))
        {
            M('video')->where('vi_id = '.$vi_id)->setDec('vi_collect_count');
            M('teacher')->where('tea_id = '.$tea_id)->setDec('tea_collect_count');

            $this->addLog('collect_id='.$co_id,1);// 记录操作日志
            $this->success('取消收藏成功', U('Admin/userdent/userCollect?user_id='.$userID));
        } else {
            $this->addLog('collect_id='.$co_id,0);// 记录操作日志
            $this->error('取消收藏失败');
        } */
    }

    /**
     * 删除记录
     */
    public function learnDel($learn_id)
    {
        $userLearn = M('userdent_learn');
        if (stripos($learn_id,','))
            $where['lern_id'] = array('in',$learn_id);
        else
            $where['lern_id'] = $learn_id;
        $userID = $userLearn->where($where)->getField('user_id');

        if ($userLearn->where($where)->delete())
        {
            $this->addLog('userdent_learn_id = '.$learn_id,1);
            $this->success('删除成功',U('Admin/userdent/userLearn?user_id='.$userID));
        }
        else
        {
            $this->addLog('userdent_learn_id = '.$learn_id,0);
            $this->error('删除失败');
        }
    }

    /**
     * 学员偏好
     */
    public function userLike($user_id)
    {
        if (IS_POST)
        {
            $arr = $_POST['class_id'];
            if (empty($arr))
                $this->error('学员偏好不能为空');
            $classID = implode(',',$arr);

            $where['user_id'] = $user_id;
            $data['class_id'] = $classID;
            $userdent = M('userdent')->where($where)->data($data)->save();
            if ($userdent !== false)
            {
                $this->addLog('user_id = '.$user_id,1);
                $this->success('设置偏好成功',U('Admin/userdent/userList'));
            }
            else
            {
                $this->addLog('user_id = '.$user_id,0);
                $this->error('设置偏好失败');
            }
        }
        else
        {
            //$this->user = M('userdent')->where('user_id = '.$user_id)->field('user_id,user_nickname,class_id')->find();
            $videoClass = M('video_class')->where('father_id = 0')->field('class_id,class_name')->select();
            $this->videoClass = $videoClass;
            $this->display();
        }
    }

    

    public function changeType(){
        $user_id=I('post.user_id');
        $shop_name=I('post.shop_name');
        $data=M('user')->find($user_id);
        if ($data['type']==1){
            $state=0;
            $shop_name="";
        }elseif($data['type']==0){
            $state=1;
        }
        $result=M('user')->where(array('user_id'=>$user_id))->save(array('type'=>$state));
        if ($result){
            S(md5($user_id)."userInformation",null);
            $this->ajaxReturn('true');
        }else{
            $this->ajaxReturn('false');
        }
    }
    
} 
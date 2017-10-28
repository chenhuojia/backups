<?php
/**
 * 用户基本信息CRUD相关
 * @author David
 *
 */
namespace User\Controller;

use Common\Controller\ApiController;
use User\Util;
class UserInfoController extends ApiController
{

    /**
     * 修改昵称
     */
    public function nickname()
    {   
       
        $user_id= session("user_id"); //用户id
        $name=I('get.name');
        $username = M('user')->where(array('name'=>$name))->count(1);
        if(empty($username)){
          $data = M('user')->where(array('user_id'=>$user_id))->save(array('name'=>$name));
          $user=\User\Util\Util::SetUserNickName($user_id,$name);
          $this->myApiPrint(200, '修改成功');
        }else{
          $this->myApiPrint(300, '已被使用');
        }
        
    }

    /**
     * 修改头像
     */
    public function avatar()
    {  
        import('User/Util/Util');
        $user_id= session("user_id"); //用户id
        $msg = \User\Util\Util::Set_Avtar($user_id);
        $this->myApiPrint($msg['error'], $msg['message']);
    }

    /**
     * 修改密码
     */
    public function changePwd()
    {
       import('Common/Common/CheckPhoneCode');
       $user_id= session("user_id"); //用户id
       $original_pwd=I('post.original_pwd');
       $password=I('post.new_pwd');
       $zone=I('post.zone','86');
       $phone=I('post.phone');
       $code=I('post.code');
       $op = I('post.op',1);
       if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
       if (empty($password)) $this->myApiPrint(300,'新输入的密码不能为空');
       $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$phone,$zone,$code);
       if($status==200||$code='1234'){
           $user=M('user')->field('user_id,name,phone,password,salt,appkey')->where(array('user_id'=>$user_id))->find();
           if($user['phone'] != $phone) return $this->myApiPrint(300, '该手机号码不匹配您的注册账号');
           if($user['password'] != md5($original_pwd.$user['salt'])){
                return $this->myApiPrint(300, '原密码错误');
           }
           $password=md5($password.$user['salt']);
           $user=M('user')->where('user_id=' . $user_id)->data(array('password'=>$password))->save();
           if(empty($user)) return $this->myApiPrint(300, '修改失败');
           else return $this->myApiPrint(200, '修改成功');
       }else{
            $this->myApiPrint(300,'短信验证失败');
       }

    }

    /**
     * 更换手机
     */
    public function changePhone()
    {
        
        import('Common/Common/CheckPhoneCode');
        $zone=I('post.zone','86');
        $phone=I('get.phone');
        $code=I('post.code');
        $op=I('post.op',1);
        $user_id= session("user_id"); //用户id
        if (empty($phone)||!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'新手机号码格式不正确!');
        $userinfo = M('user')->where(array('phone'=>$phone))->find();
        if($userinfo) $this->myApiPrint(300,'输入新的手机号码已经被注册了,请重新换一个');
        $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$original_phone,$zone,$code);
        if($status==200){
           $user=M('user')->where('user_id=' . $user_id)->data(array('phone'=>$phone))->save();
           if(empty($user)) return $this->myApiPrint(300, '修改失败');
           else return $this->myApiPrint(200, '修改成功');
        }else{
           $this->myApiPrint(300, '短信验证失败');
        }
    }

    /**
     * 解绑第三方
     */
    public function UnBang()
    {
       $user_id= session("user_id"); //用户id
       $type=I('post.ty');
       $re=M('user_third_login')->where(array('user_id'=>$user_id,'ty'=>$type))->save(array('del'=>1));
       if($re){
            $this->myApiPrint(200,'成功');
        }else{
            $this->myApiPrint(300,'失败');
        }
    }

    /**
     * 修改签名
     */
    public function changeSignName()
    {
        $user_id= session("user_id"); //用户id
        $introduce=I('get.introduce');
        $num=strlen($introduce);
        ($num<1) AND $this->myApiPrint(300,'不能为空');
        ($num>250) AND $this->myApiPrint(300,'签名只能250个字');
        $re=M('user_xq')->where(array('user_id'=>$user_id))->save(array('introduce'=>$introduce));
        if($re){
            $this->myApiPrint(200,'成功');
        }else{
        $this->myApiPrint(300,'失败');
        }
    }

     /**
     * 用户地址添加/编辑
     */
    public function addAddress(){
        $userAddress = D("user_address");
        $user_id=session('user_id');
        $info=$_POST['data'];
        $post=json_decode($info,true);
        if (!$userAddress->create($post)){
           $this->myApiPrint(300,$userAddress->getError());
        }else{
            $address_id=I('post.address_id');
            $post['user_id']=$user_id;
            if($address_id == 0)
            {
                $c = $userAddress->where("user_id = $user_id")->count();
                if($c >= 20) $this->myApiPrint(300,'最多只能添加20个收货地址');
            }
            if($address_id > 0){
                $address = $userAddress->where(array('address_id'=>$address_id,'user_id'=> $user_id))->find();
                if($post['is_default'] == 1) M('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0));
                $row = $userAddress->where(array('address_id'=>$address_id,'user_id'=> $user_id))->save($post);
                if($row) $this->myApiPrint(200,'设置成功');
                else $this->myApiPrint(300,'设置失败');
            }
            if($post['is_default']==1)  $userAddress->where(array('user_id'=>$user_id))->save(array('is_default'=>0));
            $c = $userAddress->where("user_id = ".$user_id)->count();
            if($c == 0)  $post['is_default'] = 1;               
            $data=$userAddress->add($post); 
            if($data) $this->myApiPrint(200,'设置成功');
            else $this->myApiPrint(300,'设置失败');
            
        }    
    }
     /**
     * 设置用户默认地址
     */
    public function setDefaultAddress(){
        $address_id=I('post.address_id');
        $user_id= session("user_id"); //用户id
        M('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0)); //改变以前的默认地址地址状态
        $row = M('user_address')->where(array('user_id'=>$user_id,'address_id'=>$address_id))->save(array('is_default'=>1));
        if($row) $this->myApiPrint(200,'设置默认地址成功');
        else $this->myApiPrint(300,'设置默认地址失败');
    }
    
     /**
     * 删除地址
     */
    public function delAddress(){
        $address_id=I('post.address_id');
        $user_id= session("user_id"); //用户id
        $row = M('user_address')->where(array('user_id'=>$user_id))->delete($address_id);
        if($row) $this->myApiPrint(200,'删除地址成功');
        else $this->myApiPrint(300,'删除地址失败');
    }
     /**
     * 用户地址列表
     */
     public function addressList(){
        $user_id= session("user_id"); //用户id
        $data = M('user_address')->field('address_id,user_id,postcode,province,city,area,street,address,consignee,mobile,is_default')->where(array('user_id'=>$user_id))->select();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }
    /**
     * 用户地址详情
     */
    public function addressDetail(){
        $user_id= session("user_id"); //用户id
        $address_id=I('get.address_id',0);
        $data = M('user_address')->field('address_id,user_id,postcode,province,city,area,street,address,consignee,mobile,is_default')->where(array('user_id'=>$user_id,'address_id'=>$address_id))->find();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

     /**
     * 个人中心
     */
     public function index(){
        $user_id= session("user_id"); //用户id
        $UserLogic=D('User','Logic');
        $data=M('user')->alias('u')
                       ->join('left join kts_user_xq x on u.user_id = x.user_id')
                       ->field('u.user_id,u.phone,u.money,u.integral,x.share_num,x.sell_num')
                       ->where(array('u.user_id'=>$user_id))
                       ->find();
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
        $data['avatar'] = $userMes['avatar'];
        $data['name'] = $userMes['name'];
        $data['coupon']=M("user_coupon")->where('user_id='.$user_id)->count();
        $data['topic_num']=M("book_comment")->where(array('user_id'=>$user_id,'is_show'=>1,'fid'=>0))->count();
        $data['ebook_num']=M("ebook_read")->where(array('user_id'=>$user_id,'is_show'=>1))->count();
        $data['cart_num']=M("cart")->where('user_id='.$user_id)->count();
        $data['order'] = array(
          '0'=>$UserLogic->getOrderNum($user_id,1),
          '1'=>$UserLogic->getOrderNum($user_id,2),
          '2'=>$UserLogic->getOrderNum($user_id,3),
          '3'=>$UserLogic->getOrderNum($user_id,4),
          );
        $data['follow'] = M("topic_follow")->where('user_id='.$user_id)->count();
        $data['fans'] = M("topic_follow")->where('follow_user='.$user_id)->count();
        $apply= M("shop_apply")->field('id,is_checked')->where('user_id='.$user_id)->find();
        $data['shop_state'] =-1;
        $data['shop_id'] =0;
        if(!empty($apply)){ 
           $data['shop_state'] =$apply['is_checked'];
           if($apply['is_checked']==1) $data['shop_id'] = M("shop")->where(array('apply_id'=>$apply['id']))->getField('shop_id');
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

     public function myFans(){
           $skip=I('get.skip',0);
           $take=I('get.take',10);
           $user_id= session("user_id"); //用户id
           $where['t.follow_user']=$user_id;
           $data=M('topic_follow')->alias('t')
                   ->join('left join kts_user us on t.user_id = us.user_id')
                   ->field('t.follow_id,t.user_id,us.name')
                   ->order('t.addtime desc')
                   ->where($where)->limit($skip,$take)->select();
           foreach ($data as $k=>$v){
               $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
               $data[$k]['imageurl'] = $userMes['avatar'];
               $a=M('topic_follow')->where(array('user_id'=>$user_id,'follow_user'=>$v['user_id']))->find();
               if ($a) $data[$k]['is_follow']=1;
               else  $data[$k]['is_follow']=0;
           }
           if ($data) $this->myApiPrint(200,'success',$data);
           else if(empty($data)) $this->myApiPrint(202,'暂无数据');
           else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
       }


       public function myFollow(){
           $skip=I('get.skip',0);
           $take=I('get.take',10);
           $where['t.user_id']=session("user_id"); //用户id
           $data=M('topic_follow')->alias('t')
                       ->join('left join kts_user us on t.follow_user = us.user_id')
                       ->field('t.user_id,t.follow_id,t.follow_user,us.name')
                       ->order('t.addtime desc')
                       ->where($where)->limit($skip,$take)->select();
          foreach ($data as $k=>$v){
               $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['follow_user']);
               $data[$k]['imageurl'] = $userMes['avatar'];
          }
          if ($data) $this->myApiPrint(200,'success',$data);
          else if(empty($data)) $this->myApiPrint(202,'暂无数据');
          else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
       }

        /**
        *我的分享
        * **/       
       public function myShareBook(){
           $user_id=session("user_id"); //用户id
           $skip=I('get.skip',0);
           $take=I('get.take',10);
           $data=M('book_share')->alias("b")
                ->join('LEFT JOIN kts_book_attr as r ON r.book_id = b.book_id')
                ->field('b.share_id,b.user_id,b.user_name,b.book_id,b.book_name,b.author,b.cover_img,b.is_show,b.share_time,r.press,r.publish_price')
                ->where(array('b.user_id'=>$user_id,'b.is_show'=>1))
                ->order(array('b.share_id'=>'desc'))
                ->limit($skip,$take)
                ->select();
          foreach ($data as $k=>$v){
               $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
               $data[$k]['user_avatar'] = $userMes['avatar'];
               $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
          }
          if ($data) $this->myApiPrint(200,'success',$data);
          else if(empty($data)) $this->myApiPrint(202,'暂无数据');
          else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
           
       }
        /**
        *我的收藏-图书
        * **/  
        public function myCollectBook(){
           $user_id=session("user_id"); //用户id
           $type=I('get.type',2);
           $skip=I('get.skip',0);
           $take=I('get.take',10);
           $map['c.book_id']  = array('gt',0);
           $map['c.type']  = array('eq',1);
           $map['b.type']  = array('eq',$type);
           $map['c.user_id']  = $user_id;
           $data=M('collect')->alias('c')
               ->join('left join kts_book b on b.book_id= c.book_id')
               ->field('c.id as collect_id,c.book_id,c.user_id,c.collect_time,b.name as book_name,b.author,b.cover_img')
               ->where($map)
               ->order(array('c.id'=>'desc'))
               ->group('b.book_id')
               ->limit($skip,$take)
               ->select();
          foreach ($data as $k=>$v){
               $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
               $data[$k]['user_avatar'] = $userMes['avatar'];
               $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
          }
          if ($data) $this->myApiPrint(200,'success',$data);
          else if(empty($data)) $this->myApiPrint(202,'暂无数据');
          else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
       }

       /**
        *我的收藏-书评
        * **/  
        public function myCollectComment(){
           $user_id=session("user_id"); //用户id
           $skip=I('get.skip',0);
           $take=I('get.take',10);
           $where['b.is_show'] = array('EQ','1');
           $where['b.user_id'] = array('NEQ',0);
           $where['c.type']  = array('eq',2);
           $where['b.fid'] = array('EQ',0);
           $where['c.user_id']  = $user_id;
           $data=M('collect')->alias('c')
              ->join('left join kts_book_comment b on c.topic_id = b.comment_id')
              ->where($where)
              ->field("c.id as collect_id,b.comment_id,b.type,b.book_id,b.user_id,b.imageurl as user_avatar,b.comment_time,b.content,b.book_name,b.image as cover_img, b.author,b.grade,b.fid,b.likes,b.sums")
              ->order(array('b.comment_time'=>'desc'))
              ->limit($skip,$take)
              ->select();
          foreach ($data as $k=>$v){
               $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
               $data[$k]['user_avatar'] = $userMes['avatar'];
               $data[$k]['username'] = $userMes['name'];
               $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
          }
          if ($data) $this->myApiPrint(200,'success',$data);
          else if(empty($data)) $this->myApiPrint(202,'暂无数据');
          else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
       }

      /**
        *我的收藏-店家
        * **/  
      public function mycollectshop(){
         $user_id=session("user_id"); //用户id
         $data=M('collect')->alias('cl')
          ->join('left join kts_shop s on cl.shop_id = s.shop_id')
          ->join('left join kts_activity a on a.shop_id = s.shop_id')
          ->field('cl.id as collect_id ,s.shop_id,s.shop_name,s.username,s.introduction,s.comment_num,a.act_name,s.shop_logo,s.shop_addr')
          ->order('s.comment_num desc')         
          ->where(array('cl.user_id'=>$user_id,'cl.type'=>3))
          ->select();
         if ($data) $this->myApiPrint(200,'success',$data);
         else if(empty($data)) $this->myApiPrint(202,'暂无数据');
         else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
     }

      /**
      *发布的书评
      * **/  
      public function myBookComment(){
         $user_id=session("user_id"); //用户id
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $where['b.user_id'] = array('EQ',$user_id);
         $where['b.fid'] = array('EQ',0);
         $data=M("book_comment")
            ->alias('b')
            ->where($where)
            ->field("b.comment_id,b.type,b.book_id,b.user_id,b.imageurl as user_avatar,b.comment_time,b.content,b.book_name,b.image as cover_img, b.author,b.grade,b.fid,b.likes,b.sums,b.is_secret,b.is_show,b.is_secret")
            ->order('b.comment_id desc')
            ->limit($skip,$take)
            ->select();
        foreach ($data as $k=>$v){
             $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
             $data[$k]['user_avatar'] = $userMes['avatar'];
             $data[$k]['username'] = $userMes['name'];
             $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
     }

    //我出售的二手图书列表
    public function myOldBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $user_id=session("user_id"); //用户id
        $data=M('book_old')
          ->alias('o')
          ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
          ->join('LEFT JOIN kts_book_attr as r ON r.book_id = b.book_id')
          ->where(array('b.isdelete'=>0,'b.type'=>0,'o.user_id'=>$user_id))
          ->field('b.book_id,b.user_id,b.type,b.name,b.price,b.author,b.cover_img,o.description,r.press,r.publish_price')
          ->order('b.book_id desc')
          ->limit($skip,$take)
          ->select();
        foreach ($data as $k => $v) {
          $userMes = \User\Util\Util::GetUserAvatrAndNick($data[$k]['user_id']);
          $data[$k]['user_avatar'] = $userMes['avatar'];
          $data[$k]['user_name'] = $userMes['name'];
          $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$v['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 个人中心
     */
     public function userInfo(){
        $user_id= session("user_id"); //用户id
        $data=M('user')->alias('u')
                       ->join('left join kts_user_xq x on u.user_id = x.user_id')
                       ->field('u.user_id,u.name,phone,x.introduce')
                       ->where(array('u.user_id'=>$user_id))
                       ->find();
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
        $data['avatar'] = $userMes['avatar'];
        $data['name'] = $userMes['name'];
        $data['share_num']=M("book_share")->where('user_id='.$user_id)->count();
        $data['comment_num'] = M("book_comment")->where('user_id='.$user_id)->count();
        $data['old_num'] = M("book_old")->where('user_id='.$user_id)->count();
        $data['follow_num']=M("topic_follow")->where('follow_user='.$user_id)->count();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

    //电子书
    public function myEbookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $user_id=session("user_id"); //用户id
        $data=M('ebook_read')
          ->alias('o')
          ->join('LEFT JOIN kts_book_ebook as b ON b.book_id = o.book_id')
          ->where(array('o.is_show'=>1,'o.user_id'=>$user_id))
          ->field('o.read_id,o.is_show as is_bookdel,o.schedule,b.book_id,b.book_name,b.cover_img')
          ->limit($skip,$take)
          ->select();
        foreach ($data as $k => $v) {
          $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$v['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 我的代金卷列表
     */
    public function myCoupon()
    {
        $user_id=session('user_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=M('user_coupon')->alias('u')
        ->join('left join kts_coupon s on u.c_id=s.id')
        ->field('u.c_id,u.dead_time,u.addtime,s.name,s.money,s.integration')
        ->where(array('u.user_id'=>$user_id,'u.spare'=>array('gt',0),'u.use_time'=>0))
        ->limit($skip,$take)
        ->select();
        
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['is_out_time']=0;
                if ($v['dead_time']<($v['addtime']+(60*60*24*30*6))){
                    $data[$k]['is_out_time']=1;
                }
                $data[$k]['dead_time']=date('Y-m-d',$v['dead_time']);
            }
            $this->myApiPrint(200,'success',$data);
        }
        $this->myApiPrint(202,'没有数据');
    }

    /**
     * 购物车列表
     * @param type $user   用户
     */
    function cartList()
    {
        $where['user_id']=session('user_id');
        $where['shop_id']=array('gt',0); 
        $shop=M('cart')->where($where)->group('shop_id')->field('shop_id,shop_name')->select();
        if ($shop){
            foreach ($shop as $k=>$v){
                $map['shop_id']=$shop[$k]['shop_id'];
                $cartList['shop'][]=M('cart')->field('cart_id,book_id,book_name,goods_price,goods_number,book_type,shop_id,shop_name,image,selected')->where($map)->order('addtime desc')->select();          
            }
        }else{
            $cartList['shop']=array();
        } 
        $where['shop_id']=0;
        $where['sell_id']=array('neq',0);        
        $man=M('cart')->where($where)->group('sell_id')->field('sell_id')->select();
        if ($man){
            foreach ($man as $k=>$v){
                $mat['sell_id']=$man[$k]['sell_id'];
                $cartList['old'][]=M('cart')->field('cart_id,book_id,book_name,market_price,goods_price,goods_number,book_type,book_attr,sell_id,sell_name,image,selected')->where($mat)->order('addtime desc')->select();
            }
        }else{
            $cartList['old']=array();
        }
        if ($cartList['shop']){
            foreach ($cartList['shop'] as $k=>$v){
                foreach ($v as $kk=>$vv){
                    $cartList['shop'][$k][$kk]['image']=C('QINIU_IMG_PATH').$vv['image'];
                }
            }
        }
        if ($cartList['old']){
            foreach ($cartList['old'] as $k=>$v){
                foreach ($v as $kk=>$vv){
                    $cartList['old'][$k][$kk]['image']=C('QINIU_IMG_PATH').$vv['image'];
                }
            }
        }            
        $cartList['all']=M('cart')->where(array('user_id'=>$where['user_id']))->count();
        if ($cartList) $this->myApiPrint(200,'success',$cartList);
        else if(empty($cartList)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');

    }
    //环信的token
    public function getImToken()
    {  
       $user_id= session('user_id');
       $name= session('name');
       $avatar= session('avatar');
       $userIm = M("user_im")->field("user_id,token")->where(array('user_id'=>$user_id))->find();
       if(empty($userIm)){
           $LoginLogic=D('User','Logic');
           $tokenRes =$LoginLogic->rongYunCurl('/user/getToken', array('userId' => $user_id, 'name' => $name, 'portraitUri' => $avatar));
           $tokenResArr = json_decode($tokenRes, true);
           if($tokenResArr['code']==200){
               M("user_im")->data(array('user_id'=>$user_id,'token'=>$tokenResArr['token'],'addtime'=>time()))->add();
               $this->myApiPrint(200,'获取融云token成功',$tokenResArr);
           }else{
              $this->myApiPrint(300,'获取融云token失败');
           }
       }
       $this->myApiPrint(200,'获取融云token成功',$userIm);
    }
   //积分兑换优惠券
    public function exchangeCoupon()
    {
        $coupon_id=I('post.coupon_id',0);
        $user_id= session("user_id"); //用户id
        $userCoupon=M("user_coupon");
        $userCoupon->startTrans();
        $coupon = M("coupon")->field("name,createnum,send_num,send_start_time,send_end_time,use_start_time,use_end_time,integration")->where(array('id'=>$coupon_id))->find();
        if(empty($coupon)) $this->myApiPrint(300,'找不到该优惠券');
        if($coupon['send_start_time']>time()) $this->myApiPrint(300,'优惠券兑换尚未开始');
        if($coupon['send_end_time']<time()) $this->myApiPrint(300,'优惠券兑换已经结束');
        if($coupon['createnum']<0) $this->myApiPrint(300,'您来晚了,优惠券已经被兑换完了');
        $integral=M("user")->where('user_id='.$user_id)->getField('integral');
        if($integral<$coupon['integration']) $this->myApiPrint(200,'您的积分不足,积分仅剩'.$integral);
        $user_coupon = $userCoupon->where(array('c_id'=>$coupon_id,'user_id'=>$user_id,'use_time'=>0))->find();
        if($user_coupon) $this->myApiPrint(300,'同一个优惠券不能兑换多张');
        $integral_log=integral_log($user_id,'兑换优惠券','积分兑换优惠券',$coupon['integration'],0,0);
        $row = $userCoupon->add(array('user_id'=>$user_id,'c_id'=>$coupon_id,'use_time'=>0,'dead_time'=>($_SERVER['REQUEST_TIME']+(30*6*24*60*60)),'use_number'=>1,'spare'=>1,'addtime'=>$_SERVER['REQUEST_TIME']));
        $Userintegral=M('user')->where('user_id='.$user_id)->setDec('integral',$coupon['integration']); // 用户的积分减
        $goods=M('coupon')->where('id='.$coupon_id)->save(array('createnum'=>$coupon['createnum']-1,'send_num'=>$coupon['send_num']+1)); 
        if ($row && $Userintegral && $goods && $integral_log['status']==1){ $userCoupon->commit();$this->myApiPrint(200,'兑换优惠券成功');
        }else{$userCoupon->rollback();$this->myApiPrint(300,'兑换优惠券失败');}
    }

    //我的钱包列表
    public function myMoney(){
        $user_id=session("user_id"); //用户id
        $where['user_id']=$user_id;
        $where['update_time']=today();
        $where['is_inc']=1;
        $Inc=M('money_xq')->where($where)->sum('amount');
        $where['is_inc']=0;
        $Dec=M('money_xq')->where($where)->sum('amount');
        $data['today']=bcsub($Inc,$Dec,2);
        $data['num']=M('user')->where(array('user_id'=>$user_id))->getField('money as total_money');
        $this->myApiPrint(200,'获取成功',$data);    
    }
     //收入列表
     public function addList(){
        $where['user_id']=session("user_id"); //用户id
        $where['is_inc']=1;
        $data=M('money_xq')->where($where)->field('update_time,title,description,amount')->select();
        foreach ($data as $k =>$v){
            $data[$k]['update_time']=date('m月d日 H:i:s',$v['update_time']);
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }
    
    //支出列表
    public function decList(){
        $where['user_id']=session("user_id"); //用户id
        $where['is_inc']=0;
        $data=M('money_xq')->where($where)->field('update_time,title,description,amount')->select();
        foreach ($data as $k =>$v){
            $data[$k]['update_time']=date('m月d日 H:i:s',$v['update_time']);
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }
     //设置支付密码
     public function setPayPwd(){
        $pay_pwd=md5(trim(I('post.pay_pwd')));
        $user_id=session("user_id"); //用户id
        if($pay_pwd=="") $this->myApiPrint(300,'支付密码不能为空');
        $pay=M('user')->where(array('user_id'=>$user_id))->getField("pay_pwd");
        if(!empty($pay)) $this->myApiPrint(300,'已经设置了支付密码,勿重复操作');
        $data=M('user')->where(array('user_id'=>$user_id))->save(array('pay_pwd'=>$pay_pwd));
        if(empty($data)) return $this->myApiPrint(300, '修改失败');
        else return $this->myApiPrint(200, '修改成功',$data);
    }

    //修改支付密码
    public function updPayPwd()
    {
       import('Common/Common/CheckPhoneCode');
       $user_id= session("user_id"); //用户id
       $original_pwd= md5(trim(I('post.original_pwd')));
       $pay_pwd=md5(trim(I('post.new_pwd')));
       $zone=I('post.zone','86');
       $phone=I('post.phone');
       $code=I('post.code');
       $op=I('post.op',1);
       if (!preg_match("/^1[345678]{1}\d{9}$/",$phone)) $this->myApiPrint(300,'手机号码格式不正确!');
       //if (empty($password)) $this->myApiPrint(300,'新输入的密码不能为空');
       $status=\Common\Common\CheckPhoneCode::CheckCode($op-1,$phone,$zone,$code);
       if($status==200||$code='1234'){
           $user=M('user')->field('user_id,name,phone,pay_pwd,salt,appkey')->where(array('user_id'=>$user_id))->find();
           if($user['phone'] != $phone) return $this->myApiPrint(300, '该手机号码不匹配您的注册账号');
           if(empty($user['pay_pwd'])) $this->myApiPrint(300,'您尚未设置过支付密码,设前去设置');
           if($user['pay_pwd'] != $original_pwd){
                return $this->myApiPrint(300, '原密码错误');
           }
           $data=M('user')->where($where)->save(array('pay_pwd'=>$pay_pwd));
           if(empty($user)) return $this->myApiPrint(300, '修改失败');
           else return $this->myApiPrint(200, '修改成功');
       }else{
            $this->myApiPrint(300,'短信验证失败');
       }

    }

     
    //申请提现
    public function withdrawsApply(){
        $user_id=session("user_id"); //用户id
        $money=trim(I('post.money',0));
        $pwd=md5(trim(I('post.pwd')));
        $bank_name=trim(I('post.bank_name'));
        $account_bank=I('post.account_bank','');
        $account_name=trim(I('post.account_name'));
        $remark=I('post.remark','');
        if($bank_name=="") $this->myApiPrint(300,'提现名称不能为空');
        if($account_name=="") $this->myApiPrint(300,'提现账户名不能为空');
        if($pwd=="") $this->myApiPrint(300,'支付密码不能为空');
        $user_money= M('money')->field("state,frozen_time")->where(array('user_id'=>$user_id))->find();
        $user=M('user')->field('pay_pwd,money')->where(array('user_id'=>$user_id))->find();
        $withdrawals=M('withdrawals')->where(array('user_id'=>$user_id,'status'=>0))->count();
        if($withdrawals) $this->myApiPrint(300,'您尚有一笔资金正在提现中,请耐心等待!');
        if(!empty($user_money) && $user_money['state']==0) $$this->myApiPrint(300,'您的账号已被冻结,请联系客服!');
        if($user['money']<$money) $this->myApiPrint(300,'提取金额不足,您当前的金额为'.$user['money']);
        if ($user['pay_pwd']==$pwd){
            $add=array(
                'user_id'=>$user_id,
                'create_time'=>time(),
                'money'=>$money,
                'bank_name'=>$bank_name,
                'account_bank'=>$account_bank,
                'account_name'=>$account_name,
                'remark'=>$remark
            );
            $data=M('withdrawals')->add($add);
            if ($data){
                money_log($user_id,'金额提现','提现申请中',$money,0,0,0,$data,2,0);
                $this->myApiPrint(200,'提交申请成功');
            }else   $this->myApiPrint(300,'提交申请失败');
        }else{
           $user_pay =$user_id.'pay';
           $num =0;
           if(session('?$user_pay')){
            session($user_pay,$num+1); 
            if($num>3){ 
              if(empty($user_money)) M('money')->add(array('user_id'=>$user_id,'state'=>0,'frozen_time'=>time()));
              else M('money')->where(array('user_id'=>$user_id))->save(array('state'=>0,'frozen_time'=>time()));
              $this->myApiPrint(300,'您的密码输错三次,已被冻结');
            }
           }else{ session($user_pay,$num); }
           $this->myApiPrint(300,'密码错误');
        } 

    }


     /**
    * 获取积分明细列表
    * **/ 
    public function myIntegralList(){
        $user_id = session("user_id"); //用户id
        $skip=I('get.skip',0);
        $take=I('get.take',10);
        $data = M('integral_xq')
            ->where(array('is_show'=>1,'user_id'=>$user_id))
            ->field('i_xq_id,user_id,update_time,title,description,amount,is_inc')
            ->order('i_xq_id desc')
            ->limit($skip,$take)
            ->select();
        foreach ($data as $key => $value) {
            $data[$key]['update_time']=date('m月d日 H:i:s',$value['update_time']);
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);        
    }

     //个人信息
    public function info(){
        $user_id= session("user_id"); //用户id
        $UserLogic=D('User','Logic');
        $data=M('user')->alias('u')
                       ->join('left join kts_user_xq x on u.user_id = x.user_id')
                       ->field('u.user_id,u.phone,x.introduce')
                       ->where(array('u.user_id'=>$user_id))
                       ->find();
        $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
        $data['avatar'] = $userMes['avatar'];
        $data['name'] = $userMes['name'];
        $data['phone'] = substr_replace($data['phone'],'****',3,4);
        $data['is_qq_bind']=M("user_third_login")->where(array('user_id'=>$user_id,'ty'=>1))->count();
        $data['is_weixin_bind']=M("user_third_login")->where(array('user_id'=>$user_id,'ty'=>2))->count();
        $data['is_weibo_bind']=M("user_third_login")->where(array('user_id'=>$user_id,'ty'=>3))->count();
        $this->myApiPrint(200,'success',$data);
    }

    //反馈信息
    public function feedback()
    {   
        $user_id=session("user_id"); //用户id
        $content=I("post.content",''); 
        if($content=='') $this->myApiPrint('反馈信息不能为空！');
        $result = M("feedback")->add(array('user_id'=>$user_id,'fd_content'=>$content,'fd_time'=>time())); 
        if(empty($result)) return $this->myApiPrint(300, '提交失败');
        else return $this->myApiPrint(200, '提交成功');
    }

    //用户读取消息
    public function readMessage()
    {   
        $message_id= I('post.message_id',0); // 消息ID
        $user_id= session("user_id"); //用户id
        $notesID = M('message')->where(array('message_id'=>$message_id))->find();
        if(!$notesID) $this->myApiPrint(300,'该消息不存在');
        $read = M('user_message')->where(array('message_id'=>$message_id,'user_id'=>$user_id))->find();
        if (empty($read)){
            $result = M('user_message')->add(array('message_id'=>$message_id,'user_id'=>$user_id,'read_time'=>time()));
            M('message')->where(array('message_id'=>$message_id))->setInc('click_time');
            if(empty($result)) return $this->myApiPrint(300, '读取失败');
            else return $this->myApiPrint(200, '读取成功');
        }
        $this->myApiPrint(200, '读取成功');
    }
     //评论回复列表
    public function myAnswer()
    {
        $user_id=session("user_id"); //用户id
        $skip=I('get.skip',0);
        $take=I('get.take',10);
        $data=M('answer')->where(array('user_id'=>$user_id))
            ->field('id,type,three_id,user_id,user_name,user_content,answer_user_id,answer_user_name,answer_time,answer_content,answer_type ')
            ->limit($skip,$take)
            ->select();
        foreach ($data as $k=>$v){
           $userMes = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
           $data[$k]['user_avatar'] = $userMes['avatar'];
           $data[$k]['answer_time']=date('Y-m-d',$v['answer_time']); 
           $userMes1 = \User\Util\Util::GetUserAvatrAndNick($v['answer_user_id']);
           $data[$k]['answer_user_avatar'] = $userMes1['avatar'];

        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);    
    }
   //回复
   public function reply()
   {
       $answer_id = I('post.answer_id',0);
       $content = I('post.content');
       $user_id= session("user_id"); //用户id
       $user_name= session("name"); //用户名称
       if(empty($content)) return $this->myApiPrint(300, '评论内容不可为空');
       $answer=M('answer')->field("id,type,three_id")->where(array('id'=>$answer_id,'is_show'=>1))->find();
       if(empty($answer)) return $this->myApiPrint(300, '该评论不存在!');
       switch ($answer['type']) {//0话题1书评
         case '0':
           $topic_id=M('topic_comment')->where(array('comment_id'=>$answer['three_id'],'is_show'=>1))->getField("topic_id");
           if(!$topic_id) return $this->myApiPrint(300, '该话题评论不存在');
           $three_id=add_topic_comment($answer['three_id'],$topic_id,$user_id,$user_name,$content);
           $data=add_answer($answer_id,0,$three_id,$user_id,$user_name,$content);
           break;
         case '1':
           $comment_id=M('book_comment_reply')->where(array('reply_id'=>$answer['three_id'],'is_show'=>1))->getField("comment_id");
           if(!$comment_id) return $this->myApiPrint(300, '该书评评论不存在');
           $three_id=add_topic_comment_reply($answer['three_id'],$comment_id,$user_id,"",$content);
           $data=add_answer($answer_id,1,$three_id,$user_id,$user_name,$content);
           break;
         default:
           return $this->myApiPrint(300, '参数错误');
           break;
       }
      if($data) $this->myApiPrint(200,'评论成功'); 
      else $this->myApiPrint(300,'评论失败');

   }

   //解绑第三方
   public function delThirdLogin(){
      $user_id=session("user_id"); //用户id
      $ty=I('post.ty',2);//QQ:1 WX:2
      $is_third=M("user_third_login")->where(array('user_id'=>$user_id,'ty'=>$ty))->count();
      if(!$is_third) return $this->myApiPrint(300, '您没有绑定该第三方账号!');
      $data=M('user_third_login')->where(array('user_id'=>$user_id,'ty'=>$ty))->delete();
      if($data) $this->myApiPrint(200,'解绑成功'); 
      else $this->myApiPrint(300,'解绑失败');
   }

   //获取用户的总积分
   public function getIntegralTotal(){
      $user_id=session("user_id"); //用户id
      $data= M('user')->where(array('user_id'=>$user_id))->getField('integral');
      if(isset($data)) $this->myApiPrint(200,'获取成功',$data); 
      else $this->myApiPrint(300,'获取失败');
   }

   //获取用户的钱数
   public function getMoneyTotal(){
      $user_id=session("user_id"); //用户id
      $data= M('user')->field("money,pay_pwd")->where(array('user_id'=>$user_id))->find();
      $user_money= M('money')->field("state,frozen_time")->where(array('user_id'=>$user_id))->find();
      if(!empty($data)){
         if($data['pay_pwd']=="0" && empty($data['pay_pwd'])) $data['is_setPay']=0;
         else $data['is_setPay']=1;
         if(!empty($user_money) && $user_money['state']==0) $data['money_state']=0;
         else $data['money_state']=1;
         unset($data['pay_pwd']);
         $this->myApiPrint(200,'获取成功',$data); 
      }else{ $this->myApiPrint(300,'获取失败');}
      
   }

    //绑定第三方
    public function bindingThree(){
        $three_code=I('post.three_code');
        $user_id=session("user_id"); //用户id
        $ty=I('post.ty',1);
        if (empty($three_code)) $this->myApiPrint(300,'第三方登录参数错误!');
        $ThreeLogic=D('UserThirdLogin','Logic');
        $Login= $ThreeLogic->isUserThirdLogin($user_id,$ty);
        if($Login['error'] == 200){
              //用户成功后的操作
              if($ty==1){ $openid = $three_code;}
              else{
                  $openid = $ThreeLogic->getWeiXinOpenid($three_code); 
                  if($openid =="")  $this->myApiPrint(300,'微信授权出现异常'); 
              } 
              $bing=$ThirdLogic->getUserFindOpenid($openid,$ty);
              if($bing['error'] == 300)  $this->myApiPrint(300,$bing['message']);
              $ThreeLogic->userBindingThree($userid,$ty,$openid);//绑定第三方
              $this->myApiPrint(200,'绑定成功');
         }else{
              $this->myApiPrint(300,$Login['message']);
         }
    }

     //积分兑换商品
    public function exchangeIntegralGoods()
    {
        $g_id=I('post.g_id',0);
        $address_id=I('post.address_id',0);
        $user_id= session("user_id"); //用户id
        $user_goods=M('user_integral_goods');
        $user_goods->startTrans();
        $coupon = M("integral_goods")->field("name,createnum,send_num,send_start_time,send_end_time,integration")->where(array('id'=>$g_id))->find();
        if(empty($coupon)) $this->myApiPrint(300,'找不到该兑换商品信息');
        if($coupon['send_start_time']>time()) $this->myApiPrint(300,'商品兑换尚未开始');
        if($coupon['send_end_time']<time()) $this->myApiPrint(300,'商品兑换已经结束');
        if($coupon['createnum']<0) $this->myApiPrint(300,'您来晚了,商品已经被兑换完了');
        $integral=M("user")->where('user_id='.$user_id)->getField('integral');
        if($integral<$coupon['integration']) $this->myApiPrint(200,'您的积分不足,积分仅剩'.$integral);
        $user_coupon = M("user_integral_goods")->where(array('c_id'=>$g_id,'user_id'=>$user_id))->find();
        if($user_coupon) $this->myApiPrint(300,'你已经兑换过了,勿重复');
        $address = M("user_address")->field("province,city,area,street,address,consignee,mobile")->where(array('user_id'=>$user_id,'address_id'=>$address_id))->find();
        if(empty($address)) $this->myApiPrint(300,'找不到该地址,请设置');
        $row = $user_goods->add(array('user_id'=>$user_id,'g_id'=>$g_id,'addtime'=>time(),'province'=>$address['province'],'city'=>$address['city'],'street'=>$address['street'],'address'=>$address['address'],'consignee'=>$address['consignee'],'mobile'=>$address['mobile']));
        $Userintegral=M('user')->where('user_id='.$user_id)->setDec('integral',$coupon['integration']); // 用户的积分减
        $goods=M('integral_goods')->where('id='.$g_id)->save(array('createnum'=>$coupon['createnum']-1,'send_num'=>$coupon['send_num']+1)); 
        $integral_log=integral_log($user_id,'兑换商品','积分兑换商品'.$coupon['name'],$coupon['integration'],0,0);
        if ($row && $Userintegral && $goods && $integral_log['status']==1){ $user_goods->commit();$this->myApiPrint(200,'兑换商品成功');
        }else{$user_goods->rollback();$this->myApiPrint(300,'兑换商品失败');}
    }

}


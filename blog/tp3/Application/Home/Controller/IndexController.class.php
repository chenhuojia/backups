<?php
namespace Home\Controller;
use Think\Controller;
use Home\Common\Controller\CommonController;
use think\Session;
use Org\Util\Face;




class IndexController extends CommonController {
   
    public function index(){         
        $data['ra']=self::recommend();
        $data['ral']=self::recommendList();
        $data['hot']=self::hotList();
        $data['jssdk']=self::getJsSdk();
        $this->assign('discuss',self::newDiscuss());
        $this->assign('data',$data);
        $this->assign('private_url','https://chenhuojia.xin/discuz.php/FAQ/Index/privaty');
        $this->display('default');
       
    }
    
    
    public function resume(){

    
        $this->display('php');
         
    }

    protected function newDiscuss(){
        $mo=M('discuss');
        $data=$mo->alias('b')
        ->join("left join bk_user u on b.user_id=u.user_id")
        ->field("b.id,b.art_id,b.art_title,b.parent_id,b.grade,b.content,b.user_id,b.addtime,u.name,u.avatar")
        ->where(array('b.is_show'=>1,'parent_id'=>0))
        ->order(array('b.addtime'=>'desc'))
        ->limit(0,5)->select();
        if ($data){
            foreach ($data as $k=>$v){
                $data[$k]['addtime']=self::time_tran($v['addtime']);
            }
        }
        return $data;
    }
    
    
    protected function recommend(){
        $mo=M('article_recommend');
        $data=$mo->alias('b')
        ->join("left join bk_article a on b.art_id= a.art_id")
        ->join("left join bk_article_images i on b.art_id= i.art_id")
        ->field("b.art_id,a.title,a.cate_id,a.introduction,a.discuss_total,a.approve_total,i.image_url,FROM_UNIXTIME(b.addtime, '%Y-%m-%d') as addtime")       
        ->where(array('a.is_show'=>1,'b.is_rec'=>1))
        ->order(array('b.sort'=>'desc'))
        ->limit(0,10)->select();
        return $data;
        
    }
    
    protected function recommendList(){
        $mo=M('article_recommend');
        $data=$mo->alias('b')       
        ->field('b.art_id,b.title')
        ->where(array('b.is_rec'=>1))
        ->order(array('b.sort'=>'desc'))
        ->limit(0,10)->select(); 
        return $data;  
    }
    
    protected function hotList(){
        $mo=M('article_hot');
        $data=$mo->alias('b')
        ->field('b.cate_id,b.art_id,b.cate_name,b.title')
        ->order(array('b.hot'=>'desc'))
        ->limit(0,10)->select();
        return $data;
    }
    
    
    //单个邮件发送
    public function SendOneEmial(){
        $user_email='1126089253@qq.com';
        $user_name=':-D';
        $body='我是你上司';
        $title='你被解雇了';
        $data=$this->sendEmailTmpl($user_email, $user_name, $title, $body);
        $this->ajaxReturn($data);
    }
    
    //多封邮件发送
    public function SendMoreEmail(){
        $data=M()->query('select * from bk_user ');
        $this->ajaxReturn($data);
    }
    //微信sdk
    protected function getJsSdk(){
        include dirname(dirname(__FILE__)).'/Util/jssdk/jssdk.php';
        $jssdk = new \JSSDK(C('appID'), C('appsecret'));
        $signPackage = $jssdk->GetSignPackage();
        return $signPackage;
    }
    
    //聊天
    public function chat(){
      $user=session('userInfo');
      if (empty($user)){
          $this->error('你还没有进行登录!正在跳转登录页面',U('Login/login'),3);
      }
      header('location:http://101.201.70.63:55151?tpy='.$user['name']);
      //$this->success('success','http://101.201.70.63:55151?user=125464',0);
    }
    
    public function face_age(){
        $data=$_FILES['face']['tmp_name'];        
        if ($data){
            $d=Face::get_age($data);
            $this->ajaxReturn(array('code'=>200,'msg'=>'请选择照片','data'=>$d));
        }
        $this->ajaxReturn(array('code'=>300,'msg'=>'请选择照片'));
    }

}

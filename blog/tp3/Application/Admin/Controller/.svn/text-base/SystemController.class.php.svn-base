<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;
use think\Session;
use Org\Util\Page;


class SystemController extends CommonController {
   
    public function systemBase(){         
        
        $this->display('system-base');
       
    }

    public function systemData(){         
            
            $this->display('system-data');
           
    }
        
    public function systemLog(){         
            
            $this->display('system-log');
           
    }
    
    public function systemShielding(){
    
        $this->display('system-shielding');
         
    }
    
    public function adminList(){
        $count=M('admin')->count();
        $Page=new Page($count,10);
        $data=M()->query('select * from bk_admin order by id desc limit '.$Page->firstRow.','.$Page->listRows);
        $this->list=$data;
        $this->page=$Page->show();
        $this->display('admin-list');
         
    }
    
    public function adminPasswordEdit(){
    
        $this->display('admin-password-edit');
         
    }
    public function adminPermission(){
    
        $this->display('admin-permission');
         
    }
    
    public function adminRoleAdd(){
    
        $this->display('admin-role-add');
         
    }
    public function adminRole(){
    
        $this->display('admin-role');
         
    }

    
    public function Login(){
        if (IS_POST){
            $admin=D('admin');
            if (!$admin->create()){
                $this->error($admin->getError());
            }else{
                $Verify =     new \Think\Verify();
                if (!$Verify->check(I('post.verify'))){
                    //$this->error('验证码错误');
                }
                $data=$admin->where(array('name'=>I('post.name')))->find();
                if (empty($data)){$this->error('管理员不存在错误');}
                if ($data['password'] != md5(md5(I('post.password')).$data['encrypt_code'])){$this->error('密码错误');}
                M()->execute('update bk_admin set last_login_time= \''.date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']).'\',login_num=login_num+1,login_ip=\''.$_SERVER['SERVER_ADDR'].'\' where id='.$data['id']);
                session('admin',$data);
                $this->redirect('/Index',array(),1,'登录成功 正在跳转首页');
            }
        }
    
        $this->display('login');
    }
    
    public function Verify(){
        $config =    array(
            'fontSize'    =>    30,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    true, // 关闭验证码杂点
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }
}
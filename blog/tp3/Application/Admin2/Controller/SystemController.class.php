<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;
use think\Session;


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

}
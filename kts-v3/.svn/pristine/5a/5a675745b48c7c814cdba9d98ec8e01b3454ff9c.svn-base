<?php
namespace Admin\Controller;
use Common\Controller\AdminController;
class IntegralController extends AdminController {
    
    public function integralList(){
        echo microtime ();
        $int=D('integration');
        $this->list=$int->select();
        $this->display();
    }
    
    public function integrationAdd(){
        $int=D('integration');
        if (IS_POST){
            //var_dump($_POST['num']);die;            
           if (!$int->create()){
               exit($int->getError());
           }else{
               $data=$int->add();
               if ($data){
                   $this->success('添加成功');
               }
           } 
        }       
        $this->display();
    }
    /**
     * 积分详情
     */
    public function integralDet($n = '10')
    {
        $user_id = I('get.user_id');
        $count = M('integral_xq')->where(array('user_id'=>$user_id,'is_show'=>0))->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $integral = M('integral')->alias('i')
                                ->join('left join kts_user u on u.user_id=i.user_id')
                                ->join('left join kts_user_xq xq on xq.user_id=i.user_id')
                                ->where(array('i.user_id'=>$user_id,'i.is_expire'=>1))
                                ->field('i.i_id,i.num,u.name,xq.imageurl')
                                ->find();
    
        $integral_xq=M('integral_xq')->where(array('i_id'=>$integral['i_id'],'is_show'=>0))->order('update_time')->select();
        $this->list = $integral_xq;
        $this->integral = $integral;
        $this->display();
    }  
    
    public function integralAdd(){
        $value=trim(I('post.value'));
        $user_id=I('post.user_id');
        $i_id=I('post.i_id');
        $Integral=M('integral');
        $data=$Integral->field('num')->find($i_id);
        $newtotal=$data['num']>0?$data['num']+$value:$value;
        $Integral->startTrans();
        if ($data){
            $result=M('integral')->where(array('i_id'=>$i_id))->save(array('num'=>$newtotal));
        }else{
            $i_id=$Integral->add(array('user_id'=>$user_id,'num'=>$newtotal));
        } 
        if ($result){
            $arr=array(
                'user_id'=>$user_id,
                'i_id'=>$i_id,
                'update_time'=>time(),
                'description'=>'通过管理员充值，充值了'.$value.'积分',
                'amount'=>$value,
                'sum'=>$newtotal,
            );           
            $data=$Integral_xq->add($arr);            
        }  
        if ($data){
            $Integral->commit();
            $this->ajaxReturn(1);
        }else{
            $Integral->rollback();
            $this->ajaxReturn(0);
        }       
    }
    
    
    public function integralReduce(){
        $id=I('post.id');
        $data=M('integration')->where(array('id'=>$id))->setDec('number');
        $data=M('integration')->where(array('id'=>$id))->setField(array('update_time'=>time()));
        if ($data){
            $this->clean_all();
            $ret=M('integration')->find($id);
            if ($ret){
                $ret['update_time']=date("Y-m-d H:i:s",$ret['update_time']);
                $this->ajaxReturn($ret);
            }
        }
    }
     
    public function integralInc(){
        $id=I('post.id');
        $data=M('integration')->where(array('id'=>$id))->setInc('number');
        $data=M('integration')->where(array('id'=>$id))->setField(array('update_time'=>time()));
        if ($data){
            $this->clean_all();
            $ret=M('integration')->find($id);
            if ($ret){
                $ret['update_time']=date("Y-m-d H:i:s",$ret['update_time']);
                $this->ajaxReturn($ret);
            }
        }
    }
     
    public function integralTotal(){
        $id=I('post.id');
        $value=I('post.value');
        $data=M('integration')->where(array('id'=>$id))->save(array('number'=>$value,'update_time'=>time()));
        if ($data){
            $this->clean_all();
            $ret=M('integration')->find($id);
            if ($ret){
                $ret['update_time']=date("Y-m-d H:i:s",$ret['update_time']);
                $this->ajaxReturn($ret);
            }
        }
    }
    
    public function changeStatus(){
        $value=I('get.value');
        $id=I('get.id');
        $ret=M('integration')->where(array('id'=>$id))->setField(array('is_deleted'=>$value));
        if ($ret){
            $this->redirect(U('Admin/Integral/integralList'),0);
        }
       
    }
    
    
    public function integralEdit(){
        $int=D('integration');
        $id=I('get.id');
        if ($id){
            $this->info=$int->find($id);
        }
        if (IS_POST){
            $data=array('source'=>I('post.source'),'number'=>I('post.number'),'update_time'=>time());
                $result=$int->where('id='.$id)->save($data);
                if ($result){
                    $this->success('修改成功');
                }
        }        
        $this->display();
    }
    
    
    public function integralDel(){
        $id=I('get.id');
        $data=M('integration')->delete($id);
        if ($data){
            $this->redirect(U('Integral/integralList/'),0);
        }
    }
}
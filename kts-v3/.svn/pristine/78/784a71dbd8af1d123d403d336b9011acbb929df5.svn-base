<?php
namespace Admin\Controller;
use Common\Controller\AdminController;
class MoneyController extends AdminController {
    
    /**
     * 积分详情
     */
    public function moneyDet($n = '10')
    {
        $user_id = I('get.user_id');
        $count = M('money_xq')->where(array('user_id'=>$user_id))->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $money = M('money')->alias('i')
                                ->join('left join kts_user u on u.user_id=i.user_id')
                                ->join('left join kts_user_xq xq on xq.user_id=i.user_id')
                                ->where(array('i.user_id'=>$user_id))
                                ->field('i.money_id,i.num,u.name,xq.imageurl')
                                ->find();
    
        $money_xq=M('money_xq')->where(array('money_id'=>$money['money_id']))->order('update_time')->select();
    
        //print_r($integral_xq);
        $this->list = $money_xq;
        $this->money = $money;
        $this->display();
    }  
    
    public function moneyAdd(){
        $value=trim(I('post.value'));
        $user_id=I('post.user_id');
        $money_id=I('post.money_id');
        $Money=M('money');
        $Money_xq=M('money_xq');
        $Money->startTrans();
        $data=$Money->field('num')->find($money_id);
        $newtotal=$data['num']>0?bcadd($data['num'], $value,2):$value;        
        if ($data){            
            $result=$Money->where(array('money_id'=>$money_id))->save(array('num'=>$newtotal));
            if ($result){
                $Money->commit();
            }else{
                $Money->rollback();
            } 
        }else{
            $money_id=$Money->add(array('user_id'=>$user_id,'num'=>$newtotal));
            if ($money_id){
                $result=true;
                $Money->commit();
            }else{
                $Money->rollback();              
            } 
        } 
        if ($result){
            $arr=array(
                'user_id'=>$user_id,
                'money_id'=>$money_id,
                'update_time'=>time(),
                'description'=>'通过管理员充值，充值了'.$value.'金额',
                'amount'=>$value,
                'sum'=>$newtotal,
            );
            $Money_xq=M('money_xq');
            $data=$Money_xq->add($arr);           
        }  
        if ($data){
            $this->ajaxReturn(1);
        }      
    }
    
}
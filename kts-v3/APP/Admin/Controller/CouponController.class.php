<?php
namespace Admin\Controller;
use Common\Controller\AdminController;
class CouponController extends AdminController {
    
    /**
     * 积分详情
     */
    public function couponDet($n = '10')
    {
        $user_id = I('get.user_id');
        $c_id = I('get.c_id');
        if ($user_id){
            $count = M('use_coupon')->where(array('user_id'=>$user_id,'c_id'=>$c_id))->count();
            $user_coupon=M('user_coupon')->alias('us')
                        ->join('left join kts_coupon c on c.c_id=us.c_id')
                        ->field('c.name,us.num,c.imageurl')
                        ->where(array('us.user_id'=>$user_id,'us.c_id'=>$c_id))
                        ->find();
           $use_coupon = M('use_coupon')
                        ->where(array('user_id'=>$user_id,'c_id'=>$c_id))
                        ->field('c_id,use_number,use_time,description,spare')
                        ->select();
            $this->all_coupon=M('coupon')->select();
        }else{
            $user_coupon=M('coupon')->where(array('c_id'=>$c_id))->find();
            $use_coupon = M('use_coupon')->where(array('user_id'=>0,'c_id'=>$c_id))
                                ->field('c_id,use_number,use_time,description,spare')
                                ->select();
            $count =count($use_coupon);
        }
        $this->list = $use_coupon;
        $this->user_coupon = $user_coupon;
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $this->display();
    }  
    
    public function couponAdd(){
        $value=trim(I('post.value'));
        $user_id=I('post.user_id');
        $c_id=I('post.old_c_id');
        $new_c_id=I('post.new_c_id');
        $user_coupon=M('user_coupon');
        $coupon=M('coupon');
        $old_coupon=$user_coupon->where(array('c_id'=>$c_id,'user_id'=>$user_id))->find();
        $new_coupon=$user_coupon->where(array('c_id'=>$new_c_id,'user_id'=>$user_id))->find();    
        if ($c_id==$new_c_id){
            if ($old_coupon){
                $newtotal=$old_coupon['num']+$value;
                $result=$user_coupon->where(array('c_id'=>$c_id))->save(array('num'=>$newtotal));
            }else{
                $newtotal=$value;
                $result=$user_coupon->add(array('user_id'=>$user_id,'c_id'=>$new_c_id,'num'=>$value));
            }
            if ($result){
                $coupon->where('c_id='.$c_id)->setDec('num',$value);
                $result=$c_id;
            }
        }else{ 
            if ($new_coupon){ 
                $newtotal=$new_coupon['num']+$value;
                $result=$user_coupon->where(array('c_id'=>$new_c_id))->save(array('num'=>$newtotal));
            }else{ 
                $newtotal=$value;
                $result=$user_coupon->add(array('user_id'=>$user_id,'c_id'=>$new_c_id,'num'=>$newtotal));  
            }             
            if ($result){
                $coupon->where('c_id='.$new_c_id)->setDec('num',$value);
                $result=$new_c_id;
            }
        }
        if ($result){
            $data=$coupon->find($c_id);
            $user=M('user')->find($user_id);
            $arr=array(
                'user_id'=>$user_id,
                'c_id'=>$result,
                'use_time'=>time(),
                'description'=>'通过管理员添加,用户'.$user['name'].'增加了'.$data['name'].'优惠卷'.$value.'张',
                'use_number'=>$value,
                'spare'=>$newtotal,
            );
            $data=M('use_coupon')->add($arr);            
        }  
        if ($result && $data){
            $this->ajaxReturn(1);
        }
   
    }
    
    function couponList(){
        
        $data=M('coupon')->select();
        print_r($data);
        
        
        
    }
    
    
    
    public function get_coupon($user_id,$type =0 ){
       
        //查询条件
        //    $type = I('get.type',0);           
     

        $where = ' AND l.order_id = 0 AND c.use_end_time > '.time(); // 未使用
        if($type == 1){
            //已使用
            $where = ' AND l.order_id > 0 AND l.use_time > 0 ';
        }
        if($type == 2){
            //已过期
            $where = ' AND '.time().' > c.use_end_time ';
        }        
        //获取数量
        $sql = "SELECT count(l.id) as total_num FROM __PREFIX__coupon_list".
            " l LEFT JOIN __PREFIX__coupon".
            " c ON l.cid =  c.id WHERE l.uid = {$user_id} {$where}";
        $count = $this->query($sql);
        $count = $count[0]['total_num'];

        $Page = new Page($count,10);

        $sql = "SELECT l.*,c.name,c.money,c.use_end_time,c.condition FROM __PREFIX__coupon_list".
            " l LEFT JOIN __PREFIX__coupon".
            " c ON l.cid =  c.id WHERE l.uid = {$user_id} {$where}  ORDER BY l.send_time DESC,l.use_time LIMIT {$Page->firstRow},{$Page->listRows}";

        $logs = $this->query($sql);

        $return['status'] = 1;
        $return['msg'] = '获取成功';
        $return['result'] = $logs;
        $return['show'] = $Page->show();
        $this->display();
    }
    
    public function couponsAdd(){
       $val=I('post.value');
       $c_id=I('post.c_id');
       $data=M('coupon')->where(array('c_id'=>$c_id))->setInc('num',$val);
       if ($data){
           $data=M('coupon')->find($c_id);
           $arr=array(
               'user_id'=>0,
               'c_id'=>$c_id,
               'use_number'=>$val,
               'use_time'=>time(),
               'description'=>'通过管理员添加'.$data['name'].'优惠卷'.$val.'张',
               'spare'=>$data['num'],
           );
           $result=M('use_coupon')->add($arr);
           if ($result){
               $this->ajaxReturn($result);
           }
       } 
    }
    
    
    public function couponEdit(){
        $c_id=I('get.c_id');
        $user_id=I('get.user_id');
        $where['u.c_id']=$c_id;
        if (!$user_id){
            $data=D('coupon')->field('c_id,name,price,num')->find($c_id);
        }else{
            $where['u.user_id']=$user_id;
            $data=M('user_coupon')->alias('u')->join('left join kts_coupon c on c.c_id=u.c_id')->where($where)
            ->field('c.c_id,c.name,c.price,u.num,u.user_id,c.num as number')
            ->find();
        }
        if (IS_POST){
            if ($user_id){                
                $ret=M('user_coupon')->where(array('c_id'=>$c_id,'user_id'=>$user_id))->setField('num',I('post.num'));
                if ($ret){                  
                    M('coupon')->where('c_id='.$c_id)->save(I('post.num'));
                    $user=M('user')->find($user_id);
                    $result=array(
                        'c_id'=>$c_id,
                        'user_id'=>$user_id,
                        'use_time'=>time(),
                        'use_number'=>I('post.num'),
                        'spare'=>I('post.num'),
                        'description'=>'通过管理员更改,用户'.$user['name'].'还剩下'.I('post.num').'张优惠卷',
                    );
                    M('use_coupon')->add($result);
                }
            }else{
                if ($_FILES['imageurl']['tmp_name']){
                    $imageurl=$this->uploadM($_FILES['imageurl']['tmp_name']);
                }
                $date=array('name'=>$_POST['name'],'num'=>I('post.num'),'price'=>I('post.price'),'imageurl'=>$imageurl);
                $tmp=D('coupon')->where(array('c_id'=>$c_id))->save($date);
                if ($tmp){
                    $result=array(
                        'c_id'=>$c_id,
                        'user_id'=>0,
                        'use_time'=>time(),
                        'use_number'=>I('post.num'),
                        'spare'=>I('post.num'),
                        'description'=>'通过管理员更改'.$data['name'].'还剩下'.I('post.num').'张',
                    );
                    M('use_coupon')->add($result);
                }
            } 
        }             
        $this->data=$data;
        $this->display();
    }
}
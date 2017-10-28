<?php
namespace Shop\Controller;
use Common\Controller\ApiController;
class ShopActionController extends ApiController{


    /**
     * 开通店铺认证
     * **/
    public function addShopApply(){
        $shopApply = D("shop_apply");
        $user_id=session('user_id');
        $result=$_POST;        
        $shopApply->startTrans();
        $check=$shopApply->create();
        if (empty($check)){
           $this->myApiPrint(300,$shopApply->getError());
        }else{
            $data=$shopApply->where(array('user_id'=>$user_id))->find();
            $order_sn=$check['order_sn'];
            if ($data){   
                $id=$data['id'];
                $b=$shopApply->where(array('id'=>$id))->save($result);
                if (empty($b)) $this->myApiPrint(300,'你已经提交申请了');
            }else{
                $id=$shopApply->add(); 
            }
             $User = M('shop_cash_deposit');
             $d=$User->add(array('apply_id'=>$id,'order_sn'=>$order_sn,'user_id'=>$user_id,'order_amount'=>1000,'addtime'=>$_SERVER['REQUEST_TIME']));
             $a=logOrder($order_sn,'提交保证金','提交订单',$user_id);
             if ($id && $d && $a){
                 $shopApply->commit();
                 $this->myApiPrint(200,'提交审核成功',array('apply_id'=>$id,'order_sn'=>$order_sn));
             }
             $shopApply->rollback();
             $this->myApiPrint(300,'提交失败');
        }
    }

    
    /**
     * 经办人认证
     * **/
    public function operatorApply(){
        $shopApply = D("shop_apply");
        $user_id=session('user_id');
        $result=$_POST;
        $rules = array(
             array('operator','require','请填写经办人姓名！',1),
             array('operator_idcard_no','require','请填写经办人身份证号码！',1), 
             array('operator_phone','/1[34578]\d{9}$/','经办人联系电话格式错误！',1,'regex'),
             array('id_card1','require','请上传身份证原件正面',1),
             array('id_card2','require','请上传身份证原件反面',1,),
             array('id_card3','require','请上传经办人手持身份证正面',1,),
        );
        $operator_idcard_no=I('post.operator_idcard_no');
        $check=self::isIdCard($operator_idcard_no);
        if (!$check) $this->myApiPrint(300,'经办人身份证号码格式错误');
        if (!$shopApply->validate($rules)->create()){
            // 如果创建失败 表示验证没有通过 输出错误提示信息
            $this->myApiPrint(300,$shopApply->getError());
        }else{
            $data=$shopApply->where(array('user_id'=>$user_id))->find();
            if ($data){                
                $b=$shopApply->where(array('id'=>$data['id']))->save($result);               
            }else{
                $result['user_id']=$user_id;
                $result['addtime']=$_SERVER['REQUEST_TIME'];
                $b=$shopApply->add($result);
            }
            if ($b){
                $this->myApiPrint(200,'提交经办人认证成功');
            } 
             $this->myApiPrint(300,'fail');
        }
    }
    
    /**
     * 身份证验证
     * @param unknown $number
     * @return boolean
     * ***/
    private  function isIdCard($number) {
            $sigma = '';
            //加权因子 
            $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            //校验码串 
            $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            //按顺序循环处理前17位 
            for ($i = 0;$i < 17;$i++) { 
                //提取前17位的其中一位，并将变量类型转为实数 
                $b = (int) $number{$i}; 
                //提取相应的加权因子 
                $w = $wi[$i]; 
                //把从身份证号码中提取的一位数字和加权因子相乘，并累加 得到身份证前17位的乘机的和 
                $sigma += $b * $w;
            }
        //echo $sigma;die;
            //计算序号  用得到的乘机模11 取余数
            $snumber = $sigma % 11; 
            //按照序号从校验码串中提取相应的余数来验证最后一位。 
            $check_number = $ai[$snumber];
            if ($number{17} == $check_number) {
                return true;
            } else {
                return false;
            }
    }
    
    /***
     * 提交保证金
     * **/
   public function payCashDeposit(){
       $user_id=session('user_id');
       $user_name=session('name');
       $order_sn=I('post.order_sn');
       $userModel=D('user');
       $user=$userModel->find($user_id);
       $orderModel=M('shop_cash_deposit');
       $order=$orderModel->where(array('order_sn'=>$order_sn))->find();
       $ret=bccomp($user['money'],$order['order_amount'],2);
       if ($ret == 0 || $ret == 1){
           $userModel->startTrans();
           $uPuser=$userModel->where(array('user_id'=>$user_id))->setDec('money',$order['order_amount']);
           $pay_time=$_SERVER['REQUEST_TIME'];
           $state=update_pay_status($user_id,$order_sn,$pay_time);
           $after=bcsub($user['money'],$order['order_amount'],2);
           $buyUser=account_log($user_id,$user_name,$order['apply_id'],$order_sn,$user['money'],$after,$order['order_amount'],0,1);           
           if ($uPuser && $buyUser && $state){
               $userModel->commit();
               $this->myApiPrint(200,'success');
           }
           $userModel->rollback();
           $this->myApiPrint(300,'fail');
       }elseif ($ret == -1){
           $this->myApiPrint(300,'余额不足,请选择其他支付或先充值再支付');
       }
   }
   

   
   /**
    * 申请更换认证资料
    * **/
   public function applyReplace(){
       $shop = D("shop_apply_replace");
       $reason=I('post.reason');
       $user_id=session('user_id');
       if (empty($reason)) $this->myApiPrint(300,'请填写原因');
       $apply_id=M('shop_apply')->where(array('user_id'=>$user_id))->getField('id');
       if (empty($apply_id)) $this->myApiPrint(300,'你还没有提交申请');
       if($shop->where(array('user_id'=>$user_id,'is_agree'=>2))->find())
           $this->myApiPrint(300,'你已提交过了！请等管理员审核');
       $result=$shop->add(array('user_id'=>$user_id,'apply_id'=>$apply_id,'reason'=>$reason,'addtime'=>$_SERVER['REQUEST_TIME']));
       if ($result) $this->myApiPrint(200,'提交申请成功');
       $this->myApiPrint(300,'fail');
   }
   
    
   /**
    * 查看认证资料
    * **/
   public function seeShopApply(){
       $shop = D("shop_apply");       
       $user_id=session('user_id');
       $url=C('QINIU_SHOP_ING');
       $data=$shop
       ->field('legal,phone,shop_name,shop_address,business_license,bl_time,pbln,pbln_time,business_license_img,pbln_img,
           operator,operator_idcard_no,operator_phone,id_card1,id_card2,id_card3,is_checked')
       ->where(array('user_id'=>$user_id))
       ->find();            
       if ($data){
           if ($data['business_license_img']) $data['business_license_img']=$url.$data['business_license_img'];
           if ($data['pbln_img'] =$url.$data['pbln_img']) $data['pbln_img'] =$url.$data['pbln_img'];
           if ($data['id_card1']) $data['id_card1']=$url.$data['id_card1'];
           if ($data['id_card2']) $data['id_card2'] =$url.$data['id_card2'];
           if ($data['id_card3']) $data['id_card3'] =$url.$data['id_card3']; 
       }$this->myApiPrint(200,'success',$data);
       $this->myApiPrint(202,'没有数据');
   }
   


   //店家详情
   public function shopDetail(){
       $user_id = session('user_id');
       $data=M('shop_apply')->where(array('user_id'=>$user_id))->find();
       if ($data){
           if(empty($data['legal'])) $this->myApiPrint(300,'你还没有提交店铺认证，请先提交认证');
           elseif (empty($data['operator'])) $this->myApiPrint(300,'你还没有提交经办人认证，请先提交认证');
           if (empty($data['pay_sn']) && $data['is_checked'] == 2 ){ 
               $this->myApiPrint(201,'请提交保证金',array('order_sn'=>$data['order_sn'],'deposit'=>$data['deposit']));
           }
           if ($data['is_checked']== 0) $this->myApiPrint(300,'你的店铺审核不通过,请重新提交!');
           //!empty($data['pay_sn']) &&
           if ( $data['is_checked']== 1){
               $ret=M('shop')->field('shop_id,shop_name,shop_logo as img,shop_addr')->where(array('user_id'=>$user_id,'is_show'=>1))->find();
               $avg=M('book_comment')->where(array('shop_id'=>$ret['shop_id'],'fid'=>0,'is_show'=>1))->avg('grade');
               $ret['grade']=round($avg);               
               $this->myApiPrint(200,'success',$ret);
           }
       }
       $this->myApiPrint(300,'你还没有提交开通店铺认证和经办人认证，请先提交认证');
   }
   

   
}


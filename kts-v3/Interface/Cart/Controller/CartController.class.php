<?php
namespace Cart\Controller;

use Think\Controller;
use Common\Controller\ApiController;

class CartController extends ApiController
{   

    
    /**
     * 将商品加入购物车
     */
    function addCart()
    {
        $user_id=session('user_id');
        $token=session('token');
        $goods_id = I("post.book_id"); // 商品id
        $book_num = I("post.goods_number",1);// 商品数量
        $cart=D('cart','Logic');
        $code=$cart->addCarts($goods_id, $book_num,$user_id,$token); // 将商品加入购物车
        switch ($code){
            case 200:
                $msg='success';
                break;
            case 302:
                $msg='购物车最多只能放20种商品';
                break;
            case 300:
                $msg='加入购物车失败';
                break;
            case 304:
                $msg='不能购买自己的书';
                break;
        }
        $this->myApiPrint($code,$msg);
    }
    
    /**
     * 删除购物车
     * **/
    public function delCart(){       
       $cart_id=I('post.cart_id',0);
       $cart_id=json_decode($cart_id,true);
       if ($cart_id){
           foreach ($cart_id as $k=>$v){
               $result=M('cart')->delete($v);
           }
       }
       if ($result) $this->myApiPrint(200,'success');
       $this->myApiPrint(300,'fali');
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
        $cartList['all']=cart_goods_num($where['user_id']);
        if (!empty($cartList)){
            $this->myApiPrint('success',200,$cartList);
        }
    }
    

   /**
    * 结算
    * **/
   public function cart2(){
       $user_id=session('user_id');
       $cart=D('cart','Logic');
       $data=$_POST['data'];
       $data=json_decode($data,true);
       if ($data){           
            M('cart')->where(array('user_id'=>$user_id))->setField('selected',0);
            $gods=$data[0]['book_id']?M('shop_books')->where(array('is_show'=>0))->getField('book_id',true):array();
            $cart_ids=null;
            $sql2=null;   
            $sql='update kts_cart set goods_number = case cart_id ';
            foreach ($data as $k => $v) { 
                if (in_array($v['cart_id'],$gods)){
                    $this->myApiPrint(300,'部分产品已下架');
                }           
                $cart_ids .=$v['cart_id'].',';
                $sql .= sprintf("WHEN %d THEN %d ", $v['cart_id'], $v['goods_number']);
                $sql2 .= sprintf("WHEN %d THEN %d ", $v['cart_id'],1);
             }
           $sql .=' end, selected = case cart_id '.$sql2;
           $cart_ids=substr($cart_ids,0,-1);
           $sql .= "END WHERE cart_id IN ($cart_ids);";            
           M()->execute($sql);           
           $result=$cart->cartSelectedList($user_id);
           if (empty($result)) $this->myApiPrint(300,'购物车没有此商品，请先添加商品');
           $address=M('user_address')->where(array('user_id'=>$user_id,'is_default'=>1))->find();
           if (empty($address)) $address=array();
           $tmp=array(
               'info'=>$result,
               'address'=>$address,
           );
           $this->myApiPrint(200,'success',$tmp);
       }
       $this->myApiPrint(300,'请选择商品');
   }
    

   /**
    * 检查库存
    * **/
   public function checkKuCun(){
       $book_id=I('post.book_id');
       $goods_number=I('post.goods_number',2);
       $type=I('post.type',1);
       if ($type==0) $this->myApiPrint(300,'该产品只能购买一本');
       $inventory=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
       if ($inventory){
           if ($goods_number >= $inventory) $this->myApiPrint(300,'库存不足');
           $this->myApiPrint(200,'库存充足,放心购买');
       }
       $this->myApiPrint(300,'图书不存在');
   }


   
}


<?php
namespace Goods\Logic;

use Think\Model;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class GoodsLogic extends Model
{

    
  /**
   * 添加购物车
   * @param unknown $goods_id
   * @param unknown $goods_num
   * @param number $user_id
   * 
   * **/  
  public  function addCarts($goods_id,$goods_num,$user_id)
    {
        $goods = M('book')->where("book_id = $goods_id")->find(); // 找出这个商品
        return $goods;
        if(empty($goods) || $goods['type']==2)
            return 300;
            //$this->myApiPrint(300,'购买商品不存在'); 
        if($catr_goods)
        {
            $result = M('cart')->where("cart_id =".$catr_goods['cart_id'])->save(array("goods_number"=> ($catr_goods['goods_number'] + $goods_num))); // 数量相加
        }else{
            $catr_count = M('cart')->where(array('user_id'=>$user_id))->count(); // 查找购物车商品总数量
            if($catr_count >= 20){
                return 302;
                //$this->myApiPrint('购物车最多只能放20种商品',202);
            }
            $data = array(
                'user_id'         => $user_id,   // 用户id
                'book_id'         => $goods_id,   // 商品id
                'book_SBN'        => $goods['book_number'],   // 商品货号
                'book_name'       => $goods['name'],   // 商品名称
                'goods_price'     => $goods['price'],  // 购买价
                'goods_number'    => $goods_num, // 购买数量
                'book_type'       => $goods['type'], // 规格key
                'addtime'         => $_SERVER['REQUEST_TIME'],
                'image'           => $goods['cover_img'],
            );
            if ($goods['type']==1){
                $shop=M('shop')->where(array('user_id'=>$goods['shop_id']))->find();
                $data['shop_name']=$shop['shop_name'];
                $data['shop_id']=$shop['shop_id'];
            }elseif ($goods['type']==0){
                $shop=M('user')->where(array('user_id'=>$goods['user_id']))->find();
                $goods_desc=M('book_old')->where(array('book_id'=>$goods_id))->getField('description');
                $data['sell_id']=$old['user_id'];
                $data['sell_name']=$old['user_name'];
                $data['book_attr']=$old['description'];
            }
            $insert_id = M('cart')->add($data);
        }                  
        if ($result ||$insert_id){
            return 200;
            $this->myApiPrint(200,'加入购物车成功');
        }else
            return 300;
            $this->myApiPrint(300,'加入购物车失败');
    }
    
    
}
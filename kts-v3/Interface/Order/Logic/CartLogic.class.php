<?php
namespace Order\Logic;

use Think\Model;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class CartLogic extends Model
{

    
  /**
   * 添加购物车
   * @param unknown $goods_id
   * @param unknown $goods_num
   * @param number $user_id
   * 
   * **/  
  public  function addCarts($goods_id,$goods_num,$user_id,$token)
    {
        $goods = M('book')->alias('b')
        ->join('left join kts_book_attr a on b.book_id=a.book_id')
        ->field('b.*,a.introduce')
        ->where(array('b.book_id'=>$goods_id))
        ->find(); // 找出这个商品
        if(empty($goods) || $goods['type']==2)
            return 300;
        return $goods;
        $catr_goods=M('cart')->where(array('book_id'=> $goods_id,'user_id'=>$user_id))->find(); 
        if($catr_goods)
        {
            $result = M('cart')->where("cart_id =".$catr_goods['cart_id'])->save(array("goods_number"=> ($catr_goods['goods_number'] + $goods_num))); // 数量相加
        }else{
            $catr_count = M('cart')->where(array('user_id'=>$user_id))->count(); // 查找购物车商品总数量
            if($catr_count >= 20){
                return 302;
            }
            $data = array(
                'user_id'         => $user_id,   // 用户id
                'session_id'      =>$token,
                'book_id'         => $goods_id,   // 商品id
                'book_SBN'        => $goods['book_number'],   // 商品货号
                'book_name'       => $goods['name'],   // 商品名称
                'book_desc'       => mb_substr($goods['introduce'],0,15,'utf-8'),  
                'goods_price'     => $goods['price'],  // 购买价
                'goods_number'    => $goods_num, // 购买数量
                'book_type'       => $goods['type'], // 规格key
                'addtime'         => $_SERVER['REQUEST_TIME'],
                'image'           => $goods['cover_img'],
            );
            if ($goods['type']==1){
                $shop=M('shop')->where(array('shop_id'=>$goods['shop_id']))->find();
                $data['shop_name']=$shop['shop_name'];
                $data['shop_id']=$shop['shop_id'];
            }elseif ($goods['type']==0){               
                $old=M('book_old')->where(array('book_id'=>$goods_id))->find();
                $data['sell_id']=$old['user_id'];
                $data['sell_name']=$old['user_name'];
                $data['book_attr']=$old['description'];
            }
            $insert_id = M('cart')->add($data);
            sleep(0.02);
        }                  
        if ($result ||$insert_id){
            return 200;
        }else
            return 300;
    }
    
    
   /**
    * 购物车已选商品统计
    * **/
   public function cartSelectedList($user_id){
        $where['user_id']=$user_id;
        $where['selected']=1;
        $tmp=array();
        $data['book']=M('cart')->where($where)->select();
        if ($data['book']){
            foreach ($data['book'] as $k=>$v){
                if ($v['book_type']==0){
                    $tmp['goods'][$k]['shop_id']=$v['sell_id'];
                    $tmp['goods'][$k]['shop_name']=$v['sell_name'];                   
                }elseif ($v['book_type']==1){
                    $tmp['goods'][$k]['shop_id']=$v['shop_id'];
                    $tmp['goods'][$k]['shop_name']=$v['shop_name'];                   
                }
                $tmp['goods'][$k]['book_id']=$v['book_id'];
                $tmp['goods'][$k]['book_type']=$v['book_type'];
                $tmp['goods'][$k]['book_name']=$v['book_name'];
                $tmp['goods'][$k]['book_desc']=$v['book_desc'];
                $tmp['goods'][$k]['book_number']=$v['book_sbn'];
                $tmp['goods'][$k]['cover_img']=C('QINIU_IMG_PATH').$v['image'];
                $tmp['goods'][$k]['market_price']=$v['market_price'];
                $tmp['goods'][$k]['goods_price']=$v['goods_price'];
                $tmp['goods'][$k]['goods_number']=$v['goods_number'];
                $tmp['goods'][$k]['book_attr']=$v['book_attr'];
                $price=bcadd(bcmul($data['book'][$k]['goods_number'], $data['book'][$k]['goods_price'],2), $price,2);
                $anum=$data['book'][$k]['goods_number']+$anum;
            }
            switch ($data['book'][0]['book_type']){
                case 0:
                    $shipping_price=M('book_old')->where(array('book_id'=>$data['book'][0]['book_id']))->getField('shipping_price');
                    break;
                case 1:
                    $shipping=M('shop_shipping')->where(array('shop_id'=>$data['book'][0]['shop_id'],'enabled'=>1))->getField('shipping_price');
                    if ($shipping){
                        $shipping_price=$shipping;
                    }else{
                        $shipping_price=0;
                    }
                    break;
            }
                  
            $tmp['book_type']=$data['book'][0]['book_type'];
            $tmp['price']=$price;
            $tmp['shipping_price']=$shipping_price;
            $tmp['total']=bcadd($price,$shipping_price,2);
            $tmp['anum']=$anum;
        }
        return $tmp;
    }
    

    
    
}
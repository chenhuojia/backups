<?php
namespace User\Controller;

use Common\Controller\ApiController;
use User\Util\Util;

class BookController extends ApiController
{   
    //删除分享的书
    public function shareBookDel()
    {  
       $user_id=session("user_id");
       $type =I('post.type',0);
       $book_id = I('post.book_id',0);
       $book=M('book');
       $book->startTrans();
       switch ($type) {//图书类型1普通的书 0二手书 2分享的书,3电子书'
           case '0':
               $bookType =M('book_old')->where(array('book_id'=>$book_id,'user_id'=>$user_id))->save(array('is_show'=>0));
               break;
           case '2':
               $bookType =M('book_share')->where(array('book_id'=>$book_id,'user_id'=>$user_id))->save(array('is_show'=>0));
               break;
           case '3':
               $bookType =M('book_ebook')->where(array('book_id'=>$book_id,'user_id'=>$user_id))->save(array('is_show'=>0));
               break;
           default:
               $this->myApiPrint(300,'参数错误');
               break;
       }
       $data = $book->where(array('book_id'=>$book_id,'user_id'=>$user_id))->save(array('isdelete'=>1));
       if ($data && $bookType) {
           $book->commit();
           $this->myApiPrint(200,'删除成功');
       } else {
           $book->rollback();
           $this->myApiPrint(300,'删除失败');
       }
       
    }
    //分享的书放置出售二手书
    public function shareAddOld()
    {
        $user_id=session("user_id");
        $book_id = I('post.book_id',0);
        $degree = I('post.extent');
        $price =I('post.price',0);
        $shipping_price = I('post.shipping_price',0);
        if (empty($degree)) $this->myApiPrint(300,'请选择新旧程度');
        if (empty($price)) $this->myApiPrint(300,'请填写出售的价格');
        $data=M('book')->where(array('book_id'=>$book_id,'type'=>2,'user_id'=>$user_id))->find();
        if (empty($data)) $this->myApiPrint(300,'找不到分享的书');
        if (M('book_old')->where(array('book_id'=>$book_id,'user_id'=>$user_id))->find()){
                  $this->myApiPrint(300,'这本图书你已经上传过了，请换一本再上传吧');
        }
        $books=M('book');
        $books->startTrans();
        $last_id=$books->where(array('book_id'=>$book_id))->save(array('type'=>0,'price'=>$price));
        if ($last_id){
            $book_number=$data['book_number']; 
            $book_name=$data['name'];
            $cover_img=$data['cover_img'];
            $author=$data['author'];
            $a=M('book_old')->add(array('book_id'=>$book_id,'book_number'=>$book_number,'book_name'=>$book_name,'cover_img'=>$cover_img,'author'=>$author,'user_id'=>$user_id,'user_name'=>"",'sell_time'=>$_SERVER['REQUEST_TIME'],'description'=>$degree,'shipping_price'=>$shipping_price));             
            //$c=M()->execute('update kts_book_attr set type=0 where book_id = '.$book_id);
            if ($a){
                $books->commit();
                $user=M('user_xq')->where(array('user_id'=>$user_id))->getField('share_num');
                $share_num=($user>=0)?($user-1):0;
                M()->execute('update kts_user_xq set sell_num= sell_num+1,share_num='.$share_num.'  where user_id ='.$user_id);
                M()->execute('delete from kts_book_share where book_id ='.$book_id);
                $this->myApiPrint(200,'添加成功');
            }
        }
        $books->rollback();
        $this->myApiPrint(300,'添加失败');
    }

    private function  checkCommon($data,$type,$user_id,$shop_id='',$price=0){
        $book=array(
            'name'=>$data['name'],
            'author'=>$data['author'],
            'book_number'=>$data['book_number'],
            'price'=>$price,
            'type'=>$type,
            'time'=>$_SERVER['REQUEST_TIME'],
            'cover_img'=>$data['cover_img'],
            'user_id'=>$user_id,
            'time'=>$_SERVER['REQUEST_TIME'],
            'category_path'=>$data['category_path'],
        );
        return $book;

    }

    private function addCommon($type,$book_id,$data,$user_id){
        $att=array(
            'type'=>$type,
            'book_id'=>$book_id,
            'press'=>$data['press'],
            'publish_time'=>date('Y-m-d',strtotime($data['publish_time'])),
            'publish_price'=>$data['publish_price'],
            'edition'=>isset($data['edition'])?$data['edition']:0,
            'impression'=>isset($data['impression'])?$data['impression']:0,
            'page'=>isset($data['page'])?$data['page']:0,
            'words'=>isset($data['words'])?$data['words']:0,
            'format'=>isset($data['format'])?$data['format']:0,
            'paper'=>isset($data['paper'])?$data['paper']:0,
            'binding'=>isset($data['binding'])?$data['binding']:0,
            'language'=>isset($data['language'])?$data['language']:0,
            'introduce'=>isset($data['book_desc'])?$data['book_desc']:0,
            'author_area'=>isset($data['author_area'])?$data['author_area']:1,
            'author_desc'=>isset($data['author_desc'])?$data['author_desc']:0,
            'applicable_age'=>isset($data['applicable_age'])?$data['applicable_age']:0,
            'copyright'=>$data['copyright'],
            'other'=>$data['other'],
            'cate_name'=>$data['cate_name'],
            ); 
       if (isset($data['video']) && !empty($data['video'])){
           $att['video_title']=$data['video']['title'];
           $att['video']=$data['video']['cover'].';'.$data['video']['url'];
        }
        if (isset($data['address']) && !empty($data['address'])){
            $att['address']=$data['address'];
            $att['longitude']=$data['longitude'];
            $att['latitude']=$data['latitude'];
        }
        if (isset($data['catalog']) && !empty($data['catalog'])){
            $att['catalog']=$data['catalog'];
        }
        M('book_add')->add(array('book_id'=>$book_id,'book_number'=>$data['book_number']));
        if(!empty($data['address']) && !empty($data['longitude']) && !empty($data['latitude'])) D('Book','Logic')->addBookshelfBook($user_id,$book_id,$type,$data['address'],$data['longitude'],$data['latitude'],$data['press']);
        return  M('book_attr')->add($att);
    }

    /**
      *我的书架
      * **/       
     public function myBookshelfList(){
         $user_id=session("user_id");
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $type = I('get.type',2);
         $data=M('bookshelf')
              ->field('shelf_id,user_id,addr,longitude,longitude,latitude,book_sums')
              ->where(array('user_id'=>$user_id))
              ->order(array('shelf_id'=>'desc'))
              ->limit($skip,$take)
              ->select();
        foreach ($data as $k=>$v){
             $userMes = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
             $data[$k]['username'] = $userMes['name'];
             $data[$k]['user_avatar'] = $userMes['avatar'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
         
     }


     /**
      *我的书架上的书
      * **/       
     public function myBookshelfBookList(){
         $shelf_id = I('get.shelf_id',0);
         $type = I('get.type',2);
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $data=M('bookshelf_book')
              ->field('id,user_id,book_id,shelf_id,type,book_name,author,cover_img,addtime,press,price as publish_price')
              ->where(array('shelf_id'=>$shelf_id,'type'=>$type))
              ->order(array('id'=>'desc'))
              ->limit($skip,$take)
              ->select();
        foreach ($data as $k=>$v){
             $userMes = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
             $data[$k]['user_name'] = $userMes['name'];
             $data[$k]['user_avatar'] = $userMes['avatar'];
             $data[$k]['cover_img'] = C('QINIU_IMG_PATH').$data[$k]['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
         
     }

      /*
     * 我的二手书出售订单列表
     */
    public function myOldShippingList()
    {  
       $skip=I('get.skip',0);
       $take=I('get.take',10);
       $user_id=session('user_id');
       $type=I('get.type',0);
       switch ($type) {
           case '0': $where ="payee=$user_id and payee_shop = 0 and order_status!=4";
             break;//全部
           case '1': $where ="payee=$user_id and payee_shop = 0 and  order_status in (1) and shipping_status=0 and pay_status=2";
             break;//待发货
          case '2': $where ="payee=$user_id and payee_shop = 0 and  order_status in (1) and shipping_status=1 and pay_status=2";
             break;//已发货
          case '3': $where ="payee=$user_id and payee_shop = 0 and  order_status in (1) and shipping_status !=1 and pay_status in (3,4)";
             break;//退款
           default: $this->myApiPrint(300,'参数错误');
             break;
       }
       //$where = $where." and payee > 0 and payee_shop = 0";
       $data = M('order')
              ->field('order_id,order_status,user_id,shipping_status,pay_status,order_amount,order_sn,shop_name,payee,payee_shop,anum')
              ->where($where)->order('order_id desc')->limit($skip,$take)->select();
       foreach ($data as &$value) {
           $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
           $value['username'] = $userMes['name'];
           $value['user_avatar'] = $userMes['avatar'];
       }
       foreach ($data as $key => $value) {
          $data[$key]['goods'] = M('order_goods')
                 ->field('order_id,book_id,book_thumb,book_name,book_attr,shop_id,shop_name,publish_price,book_price')
                 ->where(array('is_show'=>1,'order_id'=>$data[$key]['order_id']))->order('order_id desc')->select();
          foreach ($data[$key]['goods'] as $k => $v) {
             $data[$key]['goods'][$k]['book_thumb']= C("QINIU_IMG_PATH").$v['book_thumb'];
          }
          $data[$key]['common_status'] = "";//订单app显示上的状态
          if($data[$key]['order_status']<=1){//订单未确认、已下单
               $data[$key]['btn'] = array('联系买家');
               $data[$key]['common_status'] = "已下单";
          }elseif($data[$key]['order_status']==3) {//已取消订单
               $data[$key]['btn'] = array('联系买家');
               $data[$key]['common_status'] = "已取消";
          }elseif($data[$key]['order_status']==2) {//订单无效
               $data[$key]['common_status'] = "订单无效";
               $data[$key]['btn'] = array('联系买家');
          }elseif($data[$key]['order_status']==5) {//已完成待评价
               $data[$key]['common_status'] = "待评价";
               $data[$key]['btn'] = array('联系买家');
          }elseif($data[$key]['order_status']==6) {//已评价
               $data[$key]['common_status'] = "已评价";
               $data[$key]['btn'] = array('联系买家');
          }
          if($data[$key]['pay_status']==1 && $data[$key]['order_status']==1) {//付款中
               $data[$key]['common_status'] = "付款中";
               $data[$key]['btn'] = array('联系买家');
          }elseif($data[$key]['pay_status']==2 && $data[$key]['order_status']==1) {//已付款
               $data[$key]['common_status'] = "已付款";
               $data[$key]['btn'] = array('联系买家');
          }elseif($data[$key]['pay_status']==3 && $data[$key]['order_status']==1) {//退款中
               $data[$key]['common_status'] = "退款中";
               $data[$key]['btn'] = array('联系买家','同意退款','不退款');
          }elseif($data[$key]['pay_status']==4 && $data[$key]['order_status']==1) {//已退款
               $data[$key]['common_status'] = "退款成功";
               $data[$key]['btn'] = array('联系买家');
          }
          if($data[$key]['shipping_status']==0 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//未发货
               $data[$key]['common_status'] = "待发货";
               $data[$key]['btn'] =array('联系买家','立即发货');
          }elseif($data[$key]['shipping_status']==1 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//已发货
               $data[$key]['common_status'] = "已发货";
               $data[$key]['btn'] = array('联系买家','查看物流');
          }elseif($data[$key]['shipping_status']==2 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//已收货
               $data[$key]['common_status'] = "交易成功";
               $data[$key]['btn'] = array('评价','联系买家');
          }elseif($data[$key]['shipping_status']==3 && $data[$key]['order_status']==1 && $data[$key]['pay_status']==2) {//备货中
               $data[$key]['common_status'] = "备货中";
               $data[$key]['btn'] = array('联系买家');
          }
          unset($data[$key]['order_status']);
          unset($data[$key]['shipping_status']);
          unset($data[$key]['pay_status']);
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

  
    
}


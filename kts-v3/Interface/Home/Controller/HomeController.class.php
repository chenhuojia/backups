<?php
/**
 * 公开接口API
 * 城市，登录，注册，版本，更新等
 * 不需要验证用户身份token
 * @author David
 *
 */
namespace Home\Controller;

use Think\Controller;
use User\Util\Util;
use Common\Geo\Geohash;
class HomeController extends Controller{

    /**
     * 首页下的数据
     */
    public function index()
    {  
        $latitude =I('get.latitude','23.115262');
        $longitude = I('get.longitude','113.410505');
        //banner
        $data['banner']=$this->banner();
        //阅读会
        $data['read'] =array();
        $data['read']['top']=$this->readType('sorts');
        $data['read']['hot']=$this->readArray('addtime',0,10);
        $data['read']['new']=$this->readArray('discuss_number',0,10);
        //热销图书
        $data['hot_book'] = $this->hotBook(0,3);

        //每日上新
        $data['new_book'] = $this->newsBook(0,3);
        //二手图书
        $data['old_book'] = $this->oldBook(0,3);
        //每周专场
        $data['special'] = $this->specialTopic(0,3);
        //推荐书店
        $data['recommend_shop'] = $this->recommendShop();
        //精彩书评
        $data['great_topic'] = $this->bookComment(0,10);
        //地图数据
        $data['addr'] =array();
        $data['addr']['shop']=$this->addrShop($latitude,$longitude);
        $data['addr']['share']=$this->addrBookShelf($latitude,$longitude,2);
        $data['addr']['old']=$this->addrBookShelf($latitude,$longitude,0);
        //话题
        $data['topic'] = $this->topicComment(0,2);
        //精品书单
        $data['boutique_bookList'] = $this->boutiqueBookList();

        $this->myApiPrint(200,'成功',$data);
    }

    public function indexV2()
    {
        $latitude =I('get.latitude','23.115262');
        $longitude = I('get.longitude','113.410505');
        //阅读会
        $data['read'] =array();
        $TopicLogic=D('Topic','Logic');
        $data['read']['top']=$TopicLogic->readTop(0,2);
        $data['read']['banner']=$TopicLogic->getTopicBanner(0,3);
        //每日上新
        $data['new_book'] = $this->newsBook(0,6);
        //每周专场
        $data['special'] = $this->specialTopic(0,3);
        //精彩书评
        $data['great_topic'] = $this->bookComment(0,10);
        //推荐视频
        $data['recommend_video'] = D('Book','Logic')->recommendBookVideo(0,4);
        //地图数据
        $data['addr'] =array();
        $data['addr']['shop']=$this->addrShop($latitude,$longitude);
        $data['addr']['share']=$this->addrBookShelf($latitude,$longitude,2);
        $data['addr']['old']=$this->addrBookShelf($latitude,$longitude,0);
        //精品书单
        $data['boutique_bookList'] = $this->boutiqueBookList();

        $this->myApiPrint(200,'成功',$data);
    }

    //热销图书列表
    public function hotBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $orderlist=array('b.time'=>'desc');
        $data=M('order_goods')
              ->alias('o')
              ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
              ->join('LEFT JOIN kts_book_attr as k ON k.book_id = o.book_id')
              ->where(array('b.isdelete'=>0,'b.type'=>1))
              ->field('b.book_id,b.type,b.book_number,b.user_id,b.name,b.price,b.author,b.cover_img,k.press,k.introduce')
              ->group('o.book_id')
              ->order($orderlist)
              ->limit($skip,$take)
              ->select();
        if(empty($data)){
           $data=M('book')
              ->alias('b')
              ->join('LEFT JOIN kts_book_attr as k ON k.book_id = b.book_id')
              ->where(array('b.isdelete'=>0,'b.type'=>1))
              ->field('b.book_id,b.type,b.user_id,b.name,b.price,b.author,b.cover_img,k.press,k.introduce,k.publish_price')
              ->limit($skip,$take)
              ->order($orderlist)
              ->select();
        }
        foreach ($data as $key => $value) {
          $data[$key]['kind']=M("book")->where('book_number='.$value['book_number'])->getField('type',true);
          $data[$key]['kind']=array_merge(array_unique($data[$key]['kind']));
          $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
          $data[$key]['grade']=round(M('book_comment')->where(array('book_id'=>$value['book_id']))->avg('grade'));
          $data[$key]['comment']=round(M('book_comment')->where(array('book_id'=>$value['book_id']))->count('comment_id'));
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $data[$key]['user_name'] = $userMes['name'];
          $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //图书分享列表
    public function shareBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $data=M('book_share')
              ->alias('b')
              ->join('LEFT JOIN kts_book_attr as k ON k.book_id = b.book_id')
              ->field('b.book_id,b.book_name as name,b.user_id,b.book_number,b.user_id,b.author,b.cover_img,k.publish_price,k.press')
              ->limit($skip,$take)
              ->order(array('b.book_id'=>'desc'))
              ->select();
        foreach ($data as $key => $value) {
          $data[$key]['kind']=M("book")->where('book_number='.$value['book_number'])->getField('type',true);
          $data[$key]['kind']=array_merge(array_unique($data[$key]['kind']));
          $data[$key]['type']=2;
          $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $data[$key]['user_name'] = $userMes['name'];
          $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     //每日上新列表
    public function newsBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $orderlist=array('b.time'=>'desc');
        $data=M('book')
          ->alias('b')
          ->join('LEFT JOIN kts_book_attr as k ON k.book_id = b.book_id')
          ->where(array('b.isdelete'=>0,'b.type'=>1))
          ->field('b.book_id,b.type,b.user_id,b.shop_id,b.book_number,b.name,b.price,b.author,b.cover_img,k.press,k.introduce,k.publish_price')
          ->limit($skip,$take)
          ->order($orderlist)
          ->select();
        foreach ($data as $key => $value) {
          $data[$key]['kind']=M("book")->where('book_number='.$value['book_number'])->getField('type',true);
          $data[$key]['kind']=array_merge(array_unique($data[$key]['kind']));
          $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
          $shop=M('shop')->field('shop_name')->where(array('shop_id'=>$value['shop_id']))->find();
          $data[$key]['shop_name'] = $shop['shop_name'];
          $data[$key]['shop_logo'] = \User\Util\Util:: getShopLogoUrl($value['shop_id'],C('SHOP_PATH'),C('WEB_PATH'));
          $data[$key]['comment_sum'] = M("book_comment")->where('book_id='.$value['book_id'])->count('comment_id');
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $data[$key]['username'] = $userMes['name'];
          $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //二手图书列表
    public function oldBookList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $orderlist=array('o.sell_time'=>'desc');
        $data=M('book_old')
          ->alias('o')
          ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
          ->join('LEFT JOIN kts_book_attr as k ON k.book_id = o.book_id')
          ->where(array('b.isdelete'=>0,'b.type'=>0))
          ->field('b.book_id,b.type,b.user_id,b.book_number,b.name,b.price,b.author,b.cover_img,k.press,k.introduce,k.publish_price,o.description')
          ->limit($skip,$take)
          ->order($orderlist)
          ->select();
        foreach ($data as $key => $value) {
          $data[$key]['kind']=M("book")->where('book_number='.$value['book_number'])->getField('type',true);
          $data[$key]['kind']=array_merge(array_unique($data[$key]['kind']));
          $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $data[$key]['username'] = $userMes['name'];
          $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }
    
    //专场列表
    public function specialTopicList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $data=$this->specialTopic($skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }


    //推荐书店
    public function recommendShopList()
    {
       $skip=I('get.skip',0);
       $take=I('get.take',10);
       $data=M('shop')->alias('s')
          ->join('left join kts_activity a on a.shop_id = s.shop_id')
          ->field('s.shop_id,s.shop_name,s.introduction,s.comment_num,a.act_name,s.shop_addr')
          ->order('s.comment_num desc')
          ->where(array('s.is_show'=>1))
          ->limit($skip,$take)
          ->select();
       foreach ($data as $k=>$v){
          $data[$k]['img'] = C('QINIU_IMG_PATH').$v['img'];
          $data[$k]['img'] = \User\Util\Util:: getShopLogoUrl($v['shop_id'],C('SHOP_PATH'),C('WEB_PATH'));
          $data[$k]['grade']=round(M('book_comment')->where(array('shop_id'=>$ret['shop_id'],'fid'=>0,'is_show'=>1))->avg('grade'));          //评分
          $data[$k]['good']=99.3;        //好评率
          $data[$k]['comment']=$v['comment_num'];        //好评率
          $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
          $data[$key]['username'] = $userMes['name'];
       } 
       if ($data) $this->myApiPrint(200,'success',$data);
       else if(empty($data)) $this->myApiPrint(202,'暂无数据');
       else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //精彩书评列表
    public function bookCommentList()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $data=$this->bookComment($skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //热门图书展示搜索
    public function hotBookSearch()
    {     
        $skip = I('get.skip',0);
        $take = I('get.take',10);
        $type = I('get.kind',0);
        if($type==0) $grouplist="b.name";
        elseif($type==1) $grouplist="b.author";
        else $grouplist="k.press";
        $orderlist=array('b.time'=>'desc');
        $data=M('book_likes')
          ->alias('o') 
          ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
          ->join('LEFT JOIN kts_book_attr as k ON k.book_id = o.book_id')
          ->where(array('b.isdelete'=>0))
          ->group($grouplist)
          ->field('b.book_id,b.type,b.name,b.author,k.press')
          ->limit($skip,$take)
          ->order($orderlist)
          ->select();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //热门搜索
    public function bookSearch()
    {     
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $search = I('post.search','');
        if(trim($search) !== ''){
             $where['b.name'] = array('like','%'.$search.'%');
             $where['b.author'] = array('like','%'.$search.'%');
             $where['k.press'] = array('like','%'.$search.'%');
             $where['_logic'] = 'or';
        }
        $where['b.isdelete'] =0;
        $orderlist=array('b.time'=>'desc');
        $data=M('book')
          ->alias('b')
          ->join('LEFT JOIN kts_book_attr as k ON k.book_id = b.book_id')
          ->where($where)
          ->field('b.book_id,b.type,b.name,b.price,b.cover_img,k.press,k.introduce')
          ->limit($skip,$take)
          ->order($orderlist)
          ->group("b.book_id")
          ->select();
        foreach ($data as $key => $value) {
          $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    //热门搜索
    public function searchBookByKeyword()
    {     
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $type = I('post.type',0);
        $search = I('post.search','');
        switch ($type) {
          case '0':
            $data['name'] = $this->searchByKeyword('name',$search,0,5);
            $data['author'] = $this->searchByKeyword('author',$search,0,5);
            $data['press'] = $this->searchByKeyword('press',$search,0,5);
            if(empty($data['name']) && empty($data['author']) && empty($data['press'])) $data =array();
            break;
          case '1':
            $data = $this->searchByKeyword('name',$search,$skip,$take);
            break;
          case '2':
            $data = $this->searchByKeyword('author',$search,$skip,$take);
            break;
          case '3':
            $data = $this->searchByKeyword('press',$search,$skip,$take);
            break;
          default:
            $this->myApiPrint(300,'参数错误');
            break;
        }
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }


    //根据类型搜索
    public function searchByKeyword($keyword,$search,$skip,$take)
    {     
        $sql = "b.isdelete = 0";
        if(trim($search) !== ''){
             if($keyword == 'press'){
                $sql = $sql.' and k.'.$keyword." like "."'%$search%'";
                $group = "k.".$keyword;
             }else{
                $sql = $sql.' and b.'.$keyword." like "."'%$search%'";
                $group = "b.".$keyword;
             }    
        }else{
             $group = "b.book_id";
        }
        $data=M('book')
          ->alias('b')
          ->join('LEFT JOIN kts_book_attr as k ON k.book_id = b.book_id')
          ->where($sql)
          ->field('b.book_id,b.type,b.name,b.author,k.press')
          ->limit($skip,$take)
          ->group($group)
          ->select();
        return $data;
    }

    //banner
    public function banner()
    {     
          $banner=M('banner')
                ->where(array('is_show'=>1,'type'=>1))
                ->field('id,title,imageurl,url,desc,content')
                ->select();
          foreach ($banner as $key => $value) {
             $banner[$key]['imageurl'] = C('QINIU_IMG_PATH').$value['imageurl'];
          }
          return $banner;
    }
    //根据阅读会参数查询
    public function readType($type='sorts')
    {     
          $orderlist=array($type=>'desc');
          $topic=M('topic')
                ->where(array('is_show'=>1))
                ->field('topic_id,title,addtime,content')
                ->order($orderlist)
                ->find();
          return $topic;
    }
    //根据阅读会参数查询
    public function readArray($type='sorts',$skip='0',$take='10')
    {     
          $orderlist=array($type=>'desc');
          $topic=M('topic')
                ->where(array('is_show'=>1))
                ->field('topic_id,title,addtime,content')
                ->order($orderlist)
                ->limit($skip,$take)
                ->select();
          if(count($topic) % 2 != 0){
             array_pop($topic);
          }
          return $topic;
    }
    //热销图书
    public function hotBook($skip='0',$take='10')
    {     
          // $orderlist=array('b.time'=>'desc');
          // $book=M('order_goods')
          //       ->alias('o')
          //       ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
          //       ->where('b.isdelete=1 AND b.type=1')
          //       ->field('b.book_id,b.type,b.name,b.price,b.cover_img')
          //       ->group('o.book_id')
          //       ->order($orderlist)
          //       ->limit($skip,$take)
          //       ->select();
          // if(empty($book)){
          //    $book=M('book')
          //       ->where(array('isdelete'=>0,'type'=>1))
          //       ->field('book_id,type,name,price,cover_img')
          //       ->limit($skip,$take)
          //       ->select();
          // }
          $book=M('book')
                ->where(array('isdelete'=>0,'type'=>2))
                ->field('book_id,type,name,price,cover_img')
                ->order('book_id desc')
                ->limit($skip,$take)
                ->select();
          foreach ($book as $key => $value) {

            $book[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'].C('index_pic');
            
          }
          return $book;
    }

    //每日上新
    public function newsBook($skip='0',$take='10')
    {     
          $orderlist=array('book_id'=>'desc');
          $book=M('book')
                ->where(array('isdelete'=>0,'type'=>1))
                ->field('book_id,type,name,price,cover_img')
                ->order($orderlist)
                ->limit($skip,$take)
                ->select();
          foreach ($book as $key => $value) {
            $book[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'].C('index_pic');
            $book[$key]['like_num'] = '11';
          }
          return $book;
    }
    
    //二手图书
    public function oldBook($skip='0',$take='10')
    {     
          $orderlist=array('o.sell_time'=>'desc');
          $data=M('book_old')
              ->alias('o')
              ->join('LEFT JOIN kts_book as b ON b.book_id = o.book_id')
              ->where(array('b.isdelete'=>0,'b.type'=>0))
              ->order('o.book_id desc')
              ->field('b.book_id,b.type,b.name,b.price,b.cover_img')
              ->limit($skip,$take)
              ->select();
          foreach ($data as $key => $value) {
            $data[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'].C('index_pic');
          }
          return $data;

    }

    //每周专场
    public function specialTopic($skip='0',$take='10')
    {     
          $orderlist=array('create_time'=>'desc');
          $special_topic=M('special_topic')
                ->where(array('is_show'=>1))
                ->field('id as special_id,url,title,installments')
                ->order($orderlist)
                ->limit($skip,$take)
                ->select();
          foreach ($special_topic as $key => $value) {
            $special_topic[$key]['url'] = C('QINIU_IMG_PATH').$value['url'].C('index_pic');
          }
          return $special_topic;
    }

    //推荐书店
    public function recommendShop()
    {     
         
          $orderlist=array('create_time'=>'desc','book_saless'=>'desc');
          $shop=M('shop')
                ->where(array('is_show'=>1))
                ->field('shop_id,username,shop_name,shop_logo as img')
                ->order($orderlist)
                ->limit(0,3)
                ->select();
          foreach ($shop as $key => $value) {
            //$shop[$key]['img'] = C('QINIU_IMG_PATH').$value['img'];
          }
          return $shop;
    }

    //精彩书评
    public function bookComment($skip='0',$take='10')
    {     
  
          $orderlist=array('comment_time'=>'desc','sums'=>'desc');
          $topic=M('book_comment')
                ->where(array('is_show'=>1,'fid'=>0))//取父级0评论
                ->field('comment_id,book_id,user_id,imageurl as user_avatar,comment_time,content,image ,sums')
                ->order($orderlist)
                ->limit($skip,$take)
                ->select();
          foreach ($topic as $key => $value) {
            $book=M('book')
                  ->where(array('book_id'=>$value['book_id']))
                  ->field('book_id,type,name,author,cover_img')
                  ->find();
            $topic[$key]['cover_img'] = C('QINIU_IMG_PATH').$book['cover_img'].C('index_discuss_pic');
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $topic[$key]['username'] = $userMes['name'];
            $topic[$key]['user_avatar'] = $userMes['avatar'];
            $topic[$key]['book_id'] = $book['book_id'];
            $topic[$key]['book_type'] = $book['type'];
            $topic[$key]['book_name'] = $book['name'];
          }
          return $topic;
    }
    //某一专题详情
    public function specialTopicDetail(){
        $special_id=I('get.special_id',0);
        $data=M('special_topic')->field('id as special_id,url,title,installments')->where(array('id'=>$special_id))->find();
        if ($data){
           $data['url'] = C('QINIU_IMG_PATH').$data['url'];
           $this->myApiPrint(200,'success',$data);
        }else if(empty($data)){ $this->myApiPrint(202,'暂无数据');}
        else{ $this->myApiPrint(300,'系统繁忙，请稍后再试');}
    }
    //专题列表图书
    public function specialBookDetail(){
        $skip=I('get.skip',0);
        $id=I('get.id');
        $take=I('get.take',10);
        $data=M('special_book')->where(array('special_id'=>$id))->limit($skip,$take)->select();
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

    /**
     * 获取省份
     * **/    
    public function getProvince(){
        $data=M('province')->field('province_id as id,province as name')->select();
        $this->myApiPrint(200,'success',$data);
    }
    
    /**
     * 获取省下的市
     * **/
    public function getCity(){
        $province=I('post.id');
        $data=M('city')->field('city_id as id,city as name')->where(array('father'=>$province))->select();
        $this->myApiPrint(200,'success',$data);
    }
    
    /**
     * 获取市下的区
     * **/
    public function getArea(){
        $area=I('post.id');
        $data=M('area')->field('area_id as id,area as name')->where(array('father'=>$area))->select();
        $this->myApiPrint(200,'success',$data);
    }
    
    public function addrMaps()
    {
        $latitude =I('get.latitude','23.115262');
        $longitude = I('get.longitude','113.410505');
        //地图数据
        $data['shop']=$this->addrShop($latitude,$longitude);
        $data['share']=$this->addrBookShelf($latitude,$longitude,2);
        $data['old']=$this->addrBookShelf($latitude,$longitude,0);
        if(empty($data['shop'])&&empty($data['share'])&&empty($data['old'])){ $this->myApiPrint(202,'暂无数据');}
        else{$this->myApiPrint(200,'success',$data);}
    }
    
    public function addrShop($latitude='23.115262',$longitude='113.410505')
    {
        $squares=self::getnearBy($longitude,$latitude);
        $data=M('shop')->alias('b')
            ->field('b.shop_id,b.user_id,b.shop_addr,b.latitude,b.longitude')
            ->where(array('latitude'=>$latitude,'longitude'=>$longitude))
            ->limit(0,20)
            ->order('shop_id')
            ->group('b.shop_id')
            ->select();
        return $data;
    }
    public function addrBookShelf($latitude='23.115262',$longitude='113.410505',$type=0)
    {
        $squares=self::getnearBy($longitude,$latitude);
        $data=M('bookshelf')->alias('b')
            ->field('b.shelf_id,b.user_id,b.addr,type,b.latitude,b.longitude')
            ->where(array('latitude'=>$latitude,'longitude'=>$longitude,'type'=>$type))
            ->limit(0,20)
            ->order('shelf_id')
            ->group('b.user_id')
            ->select();
        return $data;
    }


     public function getnearBy($lng,$lat){
         $distance = 1;//范围（单位千米）
         $earth_radius = 6371;//地球半径，平均半径为6371km
         $dlng = 2 * asin(sin($distance / (2 * $earth_radius)) / cos(deg2rad($lat)));
         $dlng = rad2deg($dlng);
         $dlat = $distance/$earth_radius;
         $dlat = rad2deg($dlat);
         $squares = array('left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
         'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
         'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
         'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
         );
         return $squares;
    }

     //精彩话题
    public function topicComment($skip='0',$take='10')
    {     
          $orderlist=array('t.discuss_number'=>'desc');
          $result= M('topic')
            ->alias('t')
            ->where(array('t.is_show'=>1))
            ->field('t.topic_id,t.tag_id,t.user_id,t.title,t.addtime,t.content,t.imageurl,t.videourl,t.discuss_number,t.sorts')
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
          foreach ($result as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $result[$key]['user_name'] = $userMes['name'];
            $result[$key]['user_avatar'] = $userMes['avatar'];
            if(empty($result[$key]['imageurl'])){
              $result[$key]['imageurl'] = array();
            }else{
               $image_arr= explode(';', $result[$key]['imageurl']);
               //查询图片
               for ($i=0; $i <count($image_arr) ; $i++) { 
                  //$image_arr[$i] = C('QINIU_IMG_PATH').$image_arr[$i];
                  if(!empty($image_arr[$i])){
                     $image_arr[$i] = C('QINIU_IMG_PATH').$image_arr[$i];
                  }else{
                     unset($image_arr[$i]);
                  }
               }
               $result[$key]['image_arr'] = $image_arr;
            }
            $result[$key]['addtime'] = friend_date($result[$key]['addtime']);
          }
          return $result;
    }

    //精品书单
    public function boutiqueBookList()
    {     
         
          $orderlist=array('collect_num'=>'desc','id'=>'desc');
          $data=M('booklist')
                ->where(array('is_show'=>1))
                ->field('id,user_id,cover,name,collect_num')
                ->order($orderlist)
                ->limit(0,3)
                ->select();
          foreach ($data as &$value) {
            $value['cover'] = C('QINIU_IMG_PATH').$value['cover'];
          }
          return $data;
    }
  

}
<?php
namespace Book\Controller;

use Think\Controller;
use User\Util\Util;

class BookController extends Controller
{   
    
    /**
     * 图书类型第一栏目
     */
    public function categoryOne()
    {
        $orderlist = array(
            'sorts' => 'desc'
        );
        $data = M('category')->where(array(
            'is_show' => 1,
            'level' => 0
        ))
            ->field('cg_id,name')
            ->order($orderlist)
            ->select();
        if ($data)
            $this->myApiPrint(200, 'success', $data);
        else 
            if (empty($data))
                $this->myApiPrint(202, '暂无数据');
            else
                $this->myApiPrint(300, '系统繁忙，请稍后再试');
    }


    /**
     * 图书类型第二三栏目
     */
    public function categoryTwo()
    {
        $cg_id = I('get.cg_id', 0) . '|%';
        $data = M('category')->where("path like '$cg_id' AND is_show=1")
            ->field('cg_id,level,name,path,sorts')
            ->select();
       $re=array();
        foreach ($data as $key => &$v) { // 快速排序
            if ($v['level']== 1) {
                $re[]= $v;
                unset($data[$key]);
                continue;
            }
                unset($v['level']);
        }
       foreach($data as $v){
           foreach ($re as &$vv){
              if(strpos($v['path'],$vv['path'])===0){
                $vv['third'][]=$v;
              }
           }
       }
            if (empty($re))
                $this->myApiPrint(300, '');
            else
                $this->myApiPrint(200, '获取成功',$re);
    }
    /**
     * 获取某一图书类型值并找到其父节点
     */
    public function typeFind()
    {   
       $cg_id = I('get.cg_id',0);
       $Tag=D('Category','Logic');
       $data= $Tag->getOne(array('is_show'=>1,'cg_id'=>$cg_id));
       if($data['f_id']>0)  $data['second']= $Tag->getOne(array('is_show'=>1,'cg_id'=>$data['f_id']));
       if($data['second']>0)  $data['third']= $Tag->getOne(array('is_show'=>1,'cg_id'=>$data['second']['f_id']));
       if ($data) $this->myApiPrint(200,'success',$data);
       else if(empty($data)) $this->myApiPrint(202,'暂无数据');
       else  $this->myApiPrint(300,'');
    }

    //图书高级搜索
    public function searchBookSenior()
    {     
        $info = I('post.info');
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $info=self::tmp();
        //$info=json_decode($info,true);
        $cg_id = $info['cate_id'];
        $sql ="";
        $order = "";
        if($cg_id>0) $sql=$sql."b.category_path like "."'$cg_id|%'"." or b.category_path like "."'%|$cg_id'"." or b.category_path like "."'%|$cg_id|%'"." or b.category_path = $cg_id"." and ";
        if(!empty($info['press'])) $sql=$sql."r.press like '%".$info['press']."%' and ";
        if(!empty($info['book_name'])) $sql=$sql."b.name like '%".$info['book_name']."%' and ";
        if(!empty($info['author'])) $sql=$sql."b.author like '%".$info['author']."%' and ";
        if(!empty($info['language'])) $sql=$sql."r.language='".$info['language']."' and ";
        if($info['low_price']&& $info['low_price']) $sql=$sql."b.price >=".$info['low_price']." and b.price <=".$info['high_price']." and ";
        if($info['low_publish_time']&& $info['low_publish_time']) $sql=$sql."r.publish_time >=".$info['low_publish_time']." and r.publish_time <=".$info['high_publish_time']." and ";
        //if(!empty($info['time'])) $sql=$sql."r.publish_time =".$info['time']['common_era']." and ";
        if(!empty($info['author_area'])) $sql=$sql."r.author_area='".$info['author_area']."' and ";
        if(!empty($info['clc'])) $sql=$sql."r.clc='".$info['clc']."' and ";
        if(!empty($info['cate_name'])) $sql=$sql."r.tag='".$info['tag']."' and ";
        if(!empty($info['edition'])) $sql=$sql."r.edition='".$info['edition']."' and ";
        if(!empty($info['impression'])) $sql=$sql."r.impression='".$info['impression']."' and ";
        if(!empty($info['words'])) $sql=$sql."r.words='".$info['words']."' and ";
        if(!empty($info['page'])) $sql=$sql."r.page='".$info['page']."' and ";
        if(!empty($info['format'])) $sql=$sql."r.format='".$info['format']."' and ";
        if(!empty($info['sheet'])) $sql=$sql."r.sheet='".$info['sheet']."' and ";
        if(!empty($info['paper'])) $sql=$sql."r.paper='".$info['paper']."' and ";
        if(!empty($info['binding'])) $sql=$sql."r.binding='".$info['binding']."' and ";
        if(!empty($info['applicable_age'])) $sql=$sql."r.applicable_age='".$info['applicable_age']."' and ";
        if($info['type']>-1) $sql=$sql."b.type=".$type." and ";
        if(!empty($info['price_rule']) && $info['price_rule']==1) $order=$order."b.price desc, ";
        if(!empty($info['price_rule']) && $info['price_rule']==2) $order=$order."b.price asc, ";
        if(!empty($info['popular_rule']) && $info['popular_rule']==1) $order=$order."k.comment_num desc, ";
        if(!empty($info['popular_rule']) && $info['popular_rule']==2) $order=$order."k.comment_num asc, ";
        if(!empty($info['sale_rule']) && $info['sale_rule']==1) $order=$order."k.sell_num desc, ";
        if(!empty($info['sale_rule']) && $info['sale_rule']==2) $order=$order."k.sell_num asc, ";
        $order=$order."b.book_id desc";
        $sql=$sql."b.isdelete=0";
        $data=$this->searchAll($sql,$order,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
       
    }

    //图书搜索
    public function searchBook()
    {     
         
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $search = json_decode($_POST['search'],true);
        $cg_id = I('post.cg_id',0);
        $type = I('post.type',-1);
        $rule = json_decode($_POST['rule'],true);
        $pirce = json_decode($_POST['pirce'],true);
        $public = json_decode($_POST['public'],true);
        $applicable_age = json_decode($_POST['applicable_age'],true);
        $author_area = json_decode($_POST['author_area'],true);
        $sql ="";
        $order = "";
        if($cg_id>0) $sql=$sql."b.category_path like "."'$cg_id|%'"." or b.category_path like "."'%|$cg_id'"." or b.category_path like "."'%|$cg_id|%'"." or b.category_path = $cg_id"." and ";
        if(!empty($search)){ 
             if($search[0]=="press") $sql=$sql."r.press like "."'%$search[1]%'"." and ";
             elseif($search[0]=="name") $sql=$sql."b.name like "."'%$search[1]%'"." and ";
             elseif($search[0]=="author") $sql=$sql."b.author like "."'%$search[1]%'"." and ";
             else  $this->myApiPrint(300,'参数错误');
        }
        if(!empty($pirce)) $sql=$sql."b.price >=".$pirce[0]." and b.price <=".$pirce[1]." and ";
        if(!empty($public)) $sql=$sql."r.publish_time >=".$public[0]." and r.publish_time <= ".$public[1]." and ";
        if(!empty($applicable_age)) $sql=$sql."r.applicable_age in (".implode(',',$applicable_age).") and ";
        if(!empty($author_area)) $sql=$sql."r.author_area in (".implode(',',$applicable_age).") and ";
        if($type>-1) $sql=$sql."b.type=".$type." and ";
        $sql=$sql."b.isdelete=0";
        if($rule[0]==1) $order=$order."b.price desc, ";
        if($rule[0]==2) $order=$order."b.price asc, ";
        if($rule[1]==1) $order=$order."k.comment_num desc, ";
        if($rule[1]==2) $order=$order."k.comment_num asc, ";
        if($rule[2]==1) $order=$order."k.sell_num desc, ";
        if($rule[2]==2) $order=$order."k.sell_num asc, ";
        $order=$order."b.book_id desc";
        $data=$this->searchAll($sql,$order,$skip,$take);
        if ($data) $this->myApiPrint(200,'success',$data);
        else if(empty($data)) $this->myApiPrint(202,'暂无数据');
        else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

   public function searchAll($sql,$order,$skip,$take)
    {   
        $data=M('book')
        ->alias('b')
        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = b.book_id')
        ->join('LEFT JOIN kts_book_add as k ON k.book_number = b.book_number')
        ->where($sql)
        ->field('b.book_id,b.shop_id,b.user_id,b.type,b.name,b.author,b.price,b.cover_img,r.press,r.introduce,b.book_number')
        ->limit($skip,$take)
        ->order($order)
        ->group('b.book_id')
        ->select();
        foreach ($data as $k => $v) {
            $data[$k]['cover_img'] = C("QINIU_IMG_PATH").$v['cover_img'];
            $data[$k]['comment_sum'] = 0;
            $data[$k]['new_degree'] = "";
            $data[$k]['size'] = "";
            $data[$k]['shop_name'] = "";
            $user=getUserAvater($v['user_id']);
            $data[$k]['user_avatar'] =$user['avatar'];           
            $data[$k]['shop_logo'] = "";           
            $data[$k]['user_name'] = "";
            $data[$k]['comment_rate'] = '98.3';
            $data[$k]['kind']=M("book")->where('book_number='.$v['book_number'])->getField('type',true);
            $data[$k]['kind']=array_merge(array_unique($data[$k]['kind']));
            if($v['type']==0){
                 $old =M("book_old")->field('description,user_name')->where('book_id='.$v['book_id'])->find();
                 $data[$k]['new_degree'] = $old['description'];
                 $data[$k]['user_name'] = $user['name'];
            }
            if($v['type']==1){
                 $shop=M('shop')->field('shop_name,shop_logo')->where(array('shop_id'=>$v['shop_id']))->find();
                 $data[$k]['shop_name'] = $shop['shop_name'];
                 $data[$k]['shop_logo'] = \User\Util\Util:: getShopLogoUrl($v['shop_id'],C('SHOP_PATH'),C('WEB_PATH'));
                 $data[$k]['comment_sum'] = M("book_comment")->where('book_id='.$v['book_id'])->count('comment_id');
            }
            if($v['type']==2){
                 $data[$k]['user_name'] = $user['name'];
            }
            if($v['type']==3){
                 $data[$k]['size'] = M("book_ebook")->where('book_id='.$v['book_id'])->getField('size');
            }
        }
        return $data;
    }

     //根据类别搜索图书
    public function bookTypeList()
    {
       $skip = I('post.skip',0);
       $take = I('post.take',10);
       $type = I('post.type',1);
       $search = I('post.search',"");
       if(empty($search)) $this->myApiPrint(300,'参数错误');
       $data=M('book')
        ->alias('b')
        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = b.book_id')
        ->where(array('b.type'=>$type,'b.isdelete'=>0,'b.book_number'=>$search))
        ->field('b.book_id,b.shop_id,b.user_id,b.type,b.name,b.author,b.price,b.cover_img,r.press,r.introduce,b.book_number')
        ->limit($skip,$take)
        ->group('b.book_id')
        ->select();
       foreach ($data as $k => $v) {
            $data[$k]['cover_img'] = C("QINIU_IMG_PATH").$v['cover_img'];
            $data[$k]['comment_sum'] = 0;
            $data[$k]['new_degree'] = "";
            $data[$k]['size'] = "";
            $data[$k]['shop_name'] = "";
            $user=getUserAvater($v['user_id']);
            $data[$k]['user_avatar'] =$user['avatar'];
            $data[$k]['user_name'] = "";
            $data[$k]['comment_rate'] = '98.3';
            $data[$k]['kind']=M("book")->where('book_number='.$v['book_number'])->getField('type',true);
            $data[$k]['kind']=array_merge(array_unique($data[$k]['kind']));
            if($v['type']==0){
                 $old =M("book_old")->field('description,user_name')->where('book_id='.$v['book_id'])->find();
                 $data[$k]['new_degree'] = $old['description'];
                 $data[$k]['user_name'] = $user['name'];
            }
            if($v['type']==1){
                 $data[$k]['shop_name'] = M("shop")->where(array('shop_id'=>$v['shop_id']))->getField('shop_name');
                 $data[$k]['comment_sum'] = M("book_comment")->where('book_id='.$v['book_id'])->count('comment_id');
            }
            if($v['type']==2){
                 $data[$k]['user_name'] = $user['name'];
            }
            if($v['type']==3){
                 $data[$k]['size'] = M("book_ebook")->where('book_id='.$v['book_id'])->getField('size');
            }
        }
       if ($data) $this->myApiPrint(200,'success',$data);
       else if(empty($data)) $this->myApiPrint(202,'暂无数据');
       else  $this->myApiPrint(300,'系统繁忙，请稍后再试');
    }

     /**
     * 搜索
     */
    public function searchBooks(){
          $search=I('post.search','');
          $skip = I('post.skip',0);
          $take = I('post.take',10);
          $sql="b.isdelete=0";
          if($search!="") $sql=$sql."and b.name like "."'%$search%'";
          $data=M('book')
            ->alias('b')
            ->where("b.isdelete=0")
            ->field('b.book_id,b.type,b.name,b.author,b.cover_img')
            ->limit($skip,$take)
            ->group('b.book_id')
            ->select();
          foreach ($data as $k => $v) {
              $data[$k]['cover_img'] = C("QINIU_IMG_PATH").$v['cover_img'];
          }
         if ($data) $this->myApiPrint(200,'success',$data);
         else if(empty($data)) $this->myApiPrint(202,'暂无数据');
         else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300);
    }

    /**
     * 扫一扫ISBN
     */
    public function saomiao(){
            $book_number=I('post.book_number');
            $type=I('post.type',2);
            $skip = I('post.skip',0);
            $take = I('post.take',10);
            if(empty($book_number)) $this->myApiPrint(300,'参数错误');
            $data=M('book')
              ->alias('b')
              ->join('LEFT JOIN kts_book_attr as r ON r.book_id = b.book_id')
              ->where(array('b.type'=>$type,'b.isdelete'=>0,'b.book_number'=>$book_number))
              ->field('b.book_id,b.shop_id,b.user_id,b.type,b.name,b.author,b.price,b.cover_img,r.press,r.introduce,book_number')
              ->limit($skip,$take)
              ->group('b.book_id')
              ->select();
             foreach ($data as $k => $v) {
               $data[$k]['cover_img'] = C("QINIU_IMG_PATH").$v['cover_img'];
             }
            if ($data) $this->myApiPrint(200,'success',$data);
            else if(empty($data)) $this->myApiPrint(202,'暂无数据');
            else  $this->myApiPrint(300,'系统繁忙，请稍后再试',300); 
    }
    
    public function saomiaoshare($book_number,$skip,$take){           
          $book=M('book_share')->alias('o')
                      ->join('left join kts_book b on b.book_id = o.book_id')
                      ->join('left join kts_user u on u.user_id = o.user_id')
                      ->field('o.book_id,o.user_id,b.cover_img,b.type,b.name,b.author,u.name as username')
                      ->where(array('o.book_number'=>$book_number))
                      ->group('o.user_id')
                      ->limit($skip,$take)
                      ->select();
              foreach ($book as $key => $value) {
                $book[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
                
                $user=getUserAvater($value['user_id']);
                $book[$key]['user_avatar'] = $user['avatar'];
                $book[$key]['user_name'] =$user['name'];
              }
           return $book;   
    }

    public function saomiaoOld($book_number,$skip,$take){
           $book=M('book_old')->alias('o')
                  ->join('left join kts_book b on b.book_id = o.book_id')
                  ->field('b.book_id,b.user_id,b.type,b.name,b.author,b.price,o.description,b.cover_img')
                  ->where(array('o.book_number'=>$book_number))
                  ->group('o.book_id')
                  ->limit($skip,$take)
                  ->select();
              foreach ($book as $key => $value) {
                $book[$key]['cover_img'] = C('QINIU_IMG_PATH').$value['cover_img'];
                $user=getUserAvater($value['user_id']);
                $book[$key]['user_avatar'] = $user['avatar'];
                $book[$key]['user_name'] =$user['name'];
              }
           return $book;
    }
    public function saomiaonew($book_number,$skip,$take){
            $book=M('book')->alias('o')
                    ->join('left join kts_book b on b.book_id = o.book_id')
                    ->field('b.book_id,b.cover_img,b.type,b.name,b.author,b.price')
                    ->where(array('o.book_number'=>$book_number))
                    //TODO:分页呢
                    ->limit($skip,$take)
                    ->select();
            foreach ($book as $k=> $v){
                $book[$k]['good']='98.3%';
                $book[$k]['cover_img'] = C('QINIU_IMG_PATH').$v['cover_img'];
                import('User/Util/Util');
                $book[$k]['user_avatar'] = getUserAvater($v['user_id'])['avatar'];
                //TODO:循坏查询
                $data[$key]['comment']=round(M('book_comment')->where(array('book_id'=>$value['book_id']))->count('comment_id'));
            }
            return  $book;
    }
    
    
    public function getAplicablAge(){
        $data=M('book_aplic_age')->where('is_show=1')->order(array('sort'=>'desc','addtime'=>'desc'))->select();
        $this->myApiPrint(200,'success',$data);
    }
    
    /**
     * 书籍
     * **/
    public function BookDet(){
        $book_id=I('post.book_id');
        $book=D('Book','Logic');
        $data=$book->getBookDet($book_id);
        if ($data){
            $book->changeBookLikes($book_id);           
            $this->myApiPrint(200,'success',$data);
        }        
    }
    
    
    /**
     *评论列表 
     ***/
    public function BooKDiscuss(){
        $book_id=I('post.book_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $books=D('Book','Logic');
        //TODO:通过查找
        $book=M('book')->where(array('book_id'=>$book_id,'isdelete'=>0))->find();
        $data=$books->getBookDiscuss($book_id,$skip,$take);
        if($data)  $this->myApiPrint(200,'success',$data);
        $this->myApiPrint(202,'暂无数据');
    }
    
    /**
     *评论详情
     ***/
    public function BooKDiscussDet(){
        $comment_id=I('post.comment_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $books=D('Book','Logic');
        $data=$books->getBookDiscussDet($comment_id,$skip,$take);
        if($data)  $this->myApiPrint(200,'success',$data);
        $this->myApiPrint(202,'暂无数据');
    }
    
    /**
     *详情
     ***/
    public function BooKDesc(){
        $book_id=I('post.book_id');
        $data=M('book')->alias('b')       
        ->join('left join kts_book_attr a on b.book_id = a.book_id')
        ->join('left join kts_country cou on a.author_area = cou.country_id')
        ->join('left join kts_book_tag t on b.book_id = t.book_id')
        ->field('b.book_id,b.name as book_name,b.author,a.translator,cou.name_chinese as country,a.language,
            a.publishing_place,a.press,a.publish_time,a.lunar_calendar,a.calendar,
            b.book_number,a.cate_name,t.one,t.two,t.three,a.edition,a.impression,a.words,a.page,a.format,a.sheet,           
            a.paper,a.binding,a.publish_price,
            a.author_desc,a.introduce,a.desc_video,a.desc_images,a.catalog,a.video_title,a.video')
        ->where(array('b.book_id'=>$book_id))->find();
        $data['tag_name']='';
        if (!empty($data['one'])){ $data['tag_name']='#'.$data['one'];}
        if (!empty($data['two'])){ $data['tag_name'] .='#'.$data['two'];}
        if (!empty($data['three'])){ $data['tag_name'] .='#'.$data['three'];}
        $cate=explode('&&',$data['cate_name']);
        $data['cate_name']=$cate[0];
        /* if ($data['publish_time']){
            $data['publish_time']=date('Y年 m日',$data['publish_time']);
        } */
        if ($data['video'] && $data['video_title']){
            $video=explode(';',$data['video']);
            foreach ($video as $k=>$v){
                $video[$k]=C('QINIU_IMG_PATH').$v;
            }
            $data['video_cover']=$video[0];
            $data['video']=$video[1];
        }else{
            $data['video_cover']="";
            $data['video']="";
        }
        if ($data['desc_video']){
            $video=explode(';',$data['desc_video']);
            foreach ($video as $k=>$v){
                $video[$k]=C('QINIU_IMG_PATH').$v;
            }
            $data['desc_video_cover']=$video[0];
            $data['desc_video']=$video[1];
        }else{
            $data['desc_video_cover']="";
            $data['desc_video']="";
        }
        if ($data['desc_images']){
            $video=explode(';',$data['desc_images']);
            foreach ($video as $k=>$v){
                $video[$k]=C('QINIU_IMG_PATH').$v;
            }
            $data['desc_images']=$video;
        }else{
            $data['desc_images']=array();
        }     
        $this->myApiPrint(200,'success',$data);
    }

     public function nearByBookShelf()
    {
        $latitude =I('post.latitude','23.115262');
        $longitude = I('post.longitude','113.410505');
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $type = I('post.type',2);
        $squares=self::getnearBy($longitude,$latitude);
        $data=M('bookshelf')->alias('b')
            ->field('b.shelf_id,b.user_id,b.addr,b.longitude,b.longitude,b.latitude,b.book_sums,b.type')
            ->where("b.latitude<>0 and b.latitude>{$squares['right-bottom']['lat']} and b.latitude<{$squares['left-top']['lat']} and b.longitude>{$squares['left-top']['lng']} and b.longitude<{$squares['right-bottom']['lng']} and b.type=$type")
             ->order("(($longitude - b.longitude)*($longitude - b.longitude) + ($latitude - b.latitude) * ($latitude - b.latitude))", 'ASC')
            ->group('b.longitude,b.latitude')
            ->limit($skip,$take)
            ->select();
        foreach ($data as $key => $value) {
            $data1=M('bookshelf')->alias('b')
                ->field('b.user_id')
                ->where(array('latitude'=>$value['latitude'],'longitude'=>$value['longitude'],'type'=>$type))
                ->limit(5)
                ->group('b.user_id')
                ->select();
            foreach ($data1 as $k => $v) {
                    $userMes = \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
                    $data[$key]['info']['avatar_arr'][$k] =$userMes['avatar'];
                }
            $data[$key]['info']['sums'] =count($data1);
        }
        if($data)  $this->myApiPrint(200,'success',$data);
        $this->myApiPrint(202,'暂无数据');

    }

    public function nearByBookShelfUser()
    {
        $latitude =I('post.latitude','23.115262');
        $longitude = I('post.longitude','113.410505');
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $type = I('post.type',2);
        $squares=self::getnearBy($longitude,$latitude);
        $data=M('bookshelf')->alias('b')
            ->field('b.shelf_id,b.user_id,b.addr,b.longitude,b.longitude,b.latitude')
            ->where("b.latitude<>0 and b.latitude>{$squares['right-bottom']['lat']} and b.latitude<{$squares['left-top']['lat']} and b.longitude>{$squares['left-top']['lng']} and b.longitude<{$squares['right-bottom']['lng']}")
             ->order("(($longitude - b.longitude)*($longitude - b.longitude) + ($latitude - b.latitude) * ($latitude - b.latitude))", 'ASC')
            ->limit($skip,$take)
            ->group('b.user_id')
            ->select();
        foreach ($data as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $data[$key]['user_name'] = $userMes['name'];
            $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if($data)  $this->myApiPrint(200,'success',$data);
        $this->myApiPrint(202,'暂无数据');
    }

    public function addrBookShelfUser()
    {
        $latitude =I('post.latitude','23.115262');
        $longitude = I('post.longitude','113.410505');
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $type = I('post.type',2);
        $data=M('bookshelf')->alias('b')
            ->field('b.shelf_id,b.user_id,b.addr,b.longitude,b.longitude,b.latitude,book_sums,type')
            ->where(array('latitude'=>$latitude,'longitude'=>$longitude,'type'=>$type))
            ->order('shelf_id')
            ->limit($skip,$take)
            ->group('b.user_id')
            ->select();
        foreach ($data as $key => $value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $data[$key]['user_name'] = $userMes['name'];
            $data[$key]['user_avatar'] = $userMes['avatar'];
        }
        if($data)  $this->myApiPrint(200,'success',$data);
        $this->myApiPrint(202,'暂无数据');
    }

    public function addrBookShelfUserPic()
    {
        $latitude =I('post.latitude','23.115262');
        $longitude = I('post.longitude','113.410505');
        $skip = I('post.skip',0);
        $take = I('post.take',10);
        $type = I('post.type',2);
        $squares=self::getnearBy($longitude,$latitude);
        $data=M('bookshelf')->alias('b')
            ->field('b.user_id,b.addr')
            ->where(array('latitude'=>$latitude,'longitude'=>$longitude,'type'=>$type))
            ->order('shelf_id')
            ->limit($skip,$take)
            ->group('b.user_id')
            ->select();
        $data2 =array();
        if($data){
            foreach ($data as $key => $value) {
                $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
                $data2['user_avatar'][$key] =$userMes['avatar'];
            }
            $data2['addr'] =$data[0]['addr'];
            $data2['sums'] =count($data);
            $this->myApiPrint(200,'success',$data2);
        }else{
            $this->myApiPrint(202,'暂无数据');
        }
    }


     public function getnearBy($lng,$lat){
         $distance = 1;//范围（单位千米）
         // $lat = '113.873643';
         // $lng = '22.573969';
         define('EARTH_RADIUS', 6371);//地球半径，平均半径为6371km
         $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
         $dlng = rad2deg($dlng);
         $dlat = $distance/EARTH_RADIUS;
         $dlat = rad2deg($dlat);
         $squares = array('left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
         'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
         'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
         'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
         );
         return $squares;
    }

     /**
      *某一书架上的书列表
      * **/       
     public function bookshelfBookList(){
         $shelf_id = I('get.shelf_id',0);
         $skip=I('get.skip',0);
         $take=I('get.take',10);
         $type = I('get.type',2);
         $data=M('bookshelf_book')
              ->field('id,user_id,book_id,shelf_id,type,book_name,author,cover_img,addtime,press,price as publish_price')
              ->where(array('shelf_id'=>$shelf_id))
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

     /**
      * 查看编辑图书
      * ***/
     public function editBook(){
         $book_id=I('post.book_id');
         $book=M('book')->alias('b')
         ->join('left join kts_book_attr as a on b.book_id = a.book_id')
         ->join('left join kts_book_tag t on b.book_id = t.book_id')
         ->where(array('b.book_id'=>$book_id))
         ->field('b.book_id,b.cover_img,a.cover_explain,a.copyright,a.other,b.type,
                  b.name as book_name,b.author,a.translator,a.author_area,a.language,
                  a.publishing_place,a.press,a.publish_time,a.lunar_calendar,a.calendar,
                  b.book_number,a.clc,b.category_path as cate_id,a.cate_name,
                  t.one,t.two,t.three,a.edition,a.impression,a.words,a.page,a.format, 
                  a.sheet,a.paper,a.binding,a.publish_price,b.price,a.introduce,a.author_desc,                                  
                  a.desc_video,a.desc_images,a.catalog,a.video_title,a.video,    
                  a.applicable_age,a.address,a.longitude,a.latitude')
                   ->find();
         if ($book){
             $book['tags']=array();
             if (!empty($book['one'])){     $book['tags'][]=$book['one'];}
             if (!empty($book['two'])){     $book['tags'][]=$book['two'];}
             if (!empty($book['three'])){   $book['tags'][]=$book['three'];}
             $url=C('QINIU_IMG_PATH');
             $book['inventory']=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
             if (empty($cate_name=$book['cate_name'])){
                 $book['cate_name']=array();
                 $book['cate_path']=array();
             }else{
                 $cate_name=explode('&&',$book['cate_name']);
                 $book['cate_name']=explode(',',$cate_name[0]);
                 $book['cate_path']=explode(',',$cate_name[1]);
             }
             $book['cover_img']=$url.$book['cover_img']."={$book['cover_explain']}";
             $book['copyright']=$book['copyright']?$url.$book['copyright']:'';             
             $other=$book['other'];
             if (!empty($other)){
                 $other=explode(';',$other);
                 foreach ($other as $k=>$v){
                     $other[$k]=$url.$v;
                 }
                 $book['other']=$other;
             }else{
                 $book['other']=array();
             }
             if ($book['video']){
                 $video=explode(';',$book['video']);
                 $videos['video_cover']=$url.$video[0];
                 $videos['video']=$url.$video[1];
                 $book['video']=$videos;
             }else{
                $book['video']=array();
             }             
             if (!empty($book['desc_video'])){
                 $desc_video=explode(';',$book['desc_video']);
                 $book['desc_video']=$desc_video[1]?$url.$desc_video[1]:'';
                 $book['desc_video_cover']=$desc_video[0]?$url.$desc_video[0]:'';
             }else{
                 $book['desc_video']="";
                 $book['desc_video_cover']="";
             } 
             if (!empty($book['desc_images'])){
                 $desc_images=explode(';',$book['desc_images']);
                 foreach ($desc_images as $k=>$v){
                     $desc_images[$k]=$url.$v;
                 }
                 $book['desc_images']=$desc_images;
             }else{
                 $book['desc_images']=array();
             } 
             $this->myApiPrint(200,'success',$book);
         }
         $this->myApiPrint(300,'fail');
     }

    /**
     * 作者地区
     * **/
    public function getAuthorArea(){
        $data=M('country')->field('country_id as id,name_chinese as name')->order('sorts desc')->select();
        if ($data){
            $this->myApiPrint(200,'success',$data);
        }
        $this->myApiPrint(300,'系统没有可选地区');
    }
    
    /**
     * 标签
     * **/
    public function getBookTag(){
        $data=M('column_tag')->field('tag_name')->select();
        if ($data){
            $this->myApiPrint(200,'success',$data);
        }
        $this->myApiPrint(300,'系统没有可选标签');
    }

   public function test()
   {
     $data = D('Book','Logic')->addBookVideo(1,1,'未知','44','44');
     var_dump($data);
   }
}


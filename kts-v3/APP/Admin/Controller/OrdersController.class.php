<?php
/**
 * 图书管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
use Think\Page;

class OrdersController extends AdminController {

    /*
     * 订单列表
     */
    public function ordersList()
    {  
       //$where['i.user_id']=$this->userID;
       //$where['user_id']=1;
       $type=I('get.type',1);
       if($type){//1，待付款；2，待发货；3，待收货；4，待评价
           switch ($type) {
               case '1':
                   $where['i.order_status']<=1;
                   $where['i.shipping_status']= 0;
                   $where['i.pay_status']= 0;
                   break;
               case '2':
                   $where['i.order_status']= 1;
                   $where['i.shipping_status']= 0;
                   $where['i.pay_status']= 2;
                   break;
               case '3':
                   $where['i.order_status']= 1;
                   $where['i.shipping_status']= 1;
                   $where['i.pay_status']= 2;
                   break;
               case '4':
                   $where['i.order_status']= 1;
                   $where['i.shipping_status']= 2;
                   $where['i.pay_status']= 2;
                   break;
               default:
                   # code...
                   break;
           }
       }
       $where['o.is_show'] = 1;
       unset($where['o.is_show']);
       $count=M('order_info')->alias('i')->where($where)->count();
       $page =new Page($count,10);
       $where['o.is_show']=1;
       $data=M('order')
              ->alias('o')
              ->join('left join kts_order_info i on i.order_id = o.order_id')
              ->field('o.order_id,o.book_name,o.book_thumb,o.book_id,o.shop_id,o.shop_name,i.user_id,o.publish_price,o.book_price,o.condition,o.book_number,i.order_status,i.shipping_status,i.pay_status,i.order_amount,i.add_time')
              ->where($where)->order('i.add_time desc')->limit($page->firstRow,$page->listRows)->select();
       
       $this->page=$page->show();      
       $this->assign('list',$data);
       $this->display();
       $this->ajaxReturn($data);
    }
    
    function changetype(){
        //$type=1;
        //条件搜索
         
    }
    
    public function changeStatus(){
        $order_id=I('post.order_id');
        $status=I('post.status');
        
        
        if ($result){
            $this->clean_all();
            $this->ajaxReturn('true');
        }else{
            $this->ajaxReturn('false');
        }
    }
    
     
     /**
      * 修改图书信息
      */
     public function bookEdit()
     {   
         $type=I('get.type');
         if (IS_POST){             
             if (I('post.father_id')){
                 if ((int)I('post.father_id')==9999){
                     $fid=0;
                 }else{
                     $fid=I('post.father_id');
                 }
             }
             if (I('post.class_id')){
                 if ((int)I('post.class_id')==9999){
                     $fid=I('post.father_id');
                 }else{
                     $fid=I('post.class_id');
                 }
             }if (I('post.fid')){
                 if ((int)I('post.fid')==9999){
                     $fid=I('post.class_id');
                 }else{
                     $fid=I('post.fid');
                 }
             }
             $format=I('post.format');
             if ($_FILES){
                 $video=$this->uploadFile();
             }
             if(strpos($format, '开')){
                 $format=$format;
             }else {
                 $format=$format.'开';
             }
             $where['book_id']=I('post.book_id');
             $book=array(
                 'f_id'=>$fid,
                 'name'=>I('post.book_name'),
                 'author'=>I('post.author'),
                 'inventory'=>I('post.total'),
                 'book_number'=>I('post.book_number'),
                 'state'=>I('post.status'),
                 'price'=>I('post.price'),
             );
             $book=M('book')->where($where)->save($book);
             $attr=array(
                 'press'=>I('post.press'),
                 'publish_time'=>strtotime(I('post.publish_time')),
                 'publish_price'=>I('post.publish_price'),
                 'impression'=>I('post.impression'),
                 'page'=>I('post.page'),
                 'edition'=>I('post.edition'),
                 'words'=>I('post.words'),
                 'paper'=>I('post.paper'),
                 'format'=>$format,
                 'binding'=>I('post.binding'),
                 'language'=>I('post.language'),
             );
             $at=M('book_attr')->where($where)->save($attr);
             $add=array(
                 'introduce'=>I('post.introduce'),
                 'author_desc'=>I('post.author_desc'),
                 'author_area'=>I('post.county'),
                 'applicable_age'=>I('post.age'),
             );
             $ad =M('book_add')->where($where)->save($add);
             $catalog=array(
                 'book_number'=>I('post.book_number'),
                 'description'=>I('post.catalog'),
             );
             $ca=M('catalog')->where($where)->save($catalog);
             $data=I('post.file');            
             $data=array_unique($data);
             if ($data){
                 foreach ($data as $k=>$v){
                     $pic=array('book_id'=>$where['book_id'],'imageurl'=>C('IMG_URL').$v);
                     $im=M('image')->add($pic);
                 }
             }
             if ($video){
                 foreach ($video as $k=>$v){
                     $video=array('book_id'=>$where['book_id'],'title'=>I('post.title')[$k],'url'=>$v);                     
                     $vi=M('video')->add($video);
                 }
             }
         if ($book || $at||$ca||$im||$vi){
                $this->clean_all();
                if ($type==1){                    
                    $this->success('修改成功',U('Admin/Book/bookList'));
                }elseif($type==2){
                    $this->success('修改成功',U('Admin/Book/shareList'));
                }else{
                    $this->success('修改成功',U('Admin/Book/oldBookList'));
                }
                
            }else{
                if ($type==1){
                    $this->success('没有进行修改',U('Admin/Book/bookList'));
                }elseif($type==2){
                    $this->success('没有进行修改',U('Admin/Book/shareList'));
                }else{
                    $this->success('没有进行修改',U('Admin/Book/oldBookList'));
                }                 
            }
         }else{
             $where['b.book_id']=I('get.book_id');
             $data=M('book')->alias('b')
                     ->join('left join kts_book_attr att on att.book_id =b.book_id')
                     ->join('left join kts_book_add ad on ad.book_id =b.book_id')
                     ->join('left join kts_catalog ca on ca.book_id=b.book_id')
                     ->field('b.book_id,b.f_id,b.name as book_name,b.state,b.book_number,b.author,b.price,b.inventory,
                        att.press,att.publish_time,att.publish_price,ad.author_area,att.format,att.edition,att.paper,att.page,
                        att.impression,att.words,att.language,att.binding,ad.author_desc,ad.introduce,ca.description,ad.applicable_age
                        ')
                     ->where($where)
                     ->find();
             $img=M('image')->alias('b')->where($where,array('is_delete'=>1))->field('img_id,book_id,imageurl')->select();
             $video=M('video')->alias('b')->where($where)->select();
             $data['publish_time']=date('Y-m-d',$data['publish_time']);
             $this->country=M('country')->field('name_chinese')->select();
             $paper=array('凸版印刷纸','新闻纸','胶版印刷纸','铜板纸','书皮纸','字典纸','拷贝纸','板纸');
             $language=array('中文','英文','法语','德语','俄语','韩语','日语','印度语');
             $binding=array('平装 ','精装','线装');
             $this->book=M('category')->where(array('f_id'=>0))->field('cg_id,name')->select();
             $parent=$this->get_parent($data['f_id']);
             $parent=$this->get_fid($parent);
             $parent=explode(',', $parent);
             array_pop($parent);
             $count=count($parent);
             foreach ($parent as $k=>$v){
                 if ($k==$count-1){
                     $cate=M('category')->where(array('f_id'=>$parent[$k]))->select();
                 }
                 if ($k==$count-2){
                     $last=M('category')->where(array('f_id'=>$parent[$k]))->select();
                 }
             }
     
             if ($count<3 && $count>1){
                 array_unshift($parent,0);
     
             }if ($count==1){
                 array_unshift($parent,0);
                 array_unshift($parent,0);
             }
             if (empty($cate)){
                 $cate=0;
             }if (empty($last)){
                 $last=0;
             }
             $this->cate=$cate;
             $this->last=$last;
             $this->parent=$parent;
             $this->data=$data;
             $this->lang=$language;
             $this->paper=$paper;
             $this->binding=$binding;
             $this->img=$img;
             $this->video=$video;
             $this->display();
         }
     
     }
     
     
     /**
      * 图书列表
      */
     public function bookList()
     {
        $searchKey = I('get.searchKey');
        $newbook=I('get.newbook');
         $fid=I('get.f_id');
         if ($fid){
             $where['b.f_id']=$fid;
         }
         $where['b.isdelete']=0;
         $where['b.user_id']=0;
         $where['b.type']=1;
         if ($searchKey){
             $searchType = trim(I('get.searchType',0));
             switch($searchType)
             {
                 case 1:
                     $searchType= 'b.author';
                     break;
                 case 2:
                     $searchType= 'b.book_number';
                     break;
                 default:
                     $searchType = 'b.name';
                     break;
             }
             $where[$searchType] = array('like','%'.$searchKey.'%');
             $count=M('book')->alias('b')->where($where)->count();
         }elseif($newbook){
            date_default_timezone_set("Asia/Shanghai");
            $time=date("Y-m-d 00:00:00");
            $deadline=date("Y-m-d 24:00:00");
            $news=strtotime($time);
            $dead=strtotime($deadline);
            $where['b.time']=array(array('gt',$news),array('lt',$dead));
            $count=D('book')->alias('b')->where($where)->count();
        }else{
             $count=M('book')->alias('b')->where($where)->count();
         }
         $page=new \Think\Page($count,5);
         $data = M('book')->alias('b')
                 ->join('left join kts_book_attr att on b.book_id =att.book_id')
                 ->join('left join kts_image i on i.book_id = b.book_id')
                 ->join('left join kts_book_add ad on ad.book_id = b.book_id')
                 ->join('left join kts_category ca on ca.cg_id =b.f_id')
                 //->join('left join kts_catalog c on c.book_id =b.book_id')
                 ->field('b.book_id,b.name,b.type,b.book_number,b.inventory,b.state,b.f_id,b.price,b.author,i.imageurl,ad.author_area,ca.name as category')
                 ->group('b.book_id')
                 ->where($where)->order('b.book_id desc')->limit($page->firstRow.','.$page->listRows)->select();
         $this->page=$page->show();
         $this->list=$data;
         //print_r($page);
         $this->display();
     }
     
     
     public function oldBookList()
     {
         $searchKey = I('get.searchKey');
         $newoldbook=I('get.newoldbook');
         $fid=I('get.f_id');
         if ($fid){
             $where['b.f_id']=$fid;
         }
         $where['b.isdelete']=0;
         $where['b.user_id']=0;
         $where['b.type']=0;
         if ($searchKey){
             $searchType = trim(I('get.searchType',0));
             switch($searchType)
             {
                 case 1:
                     $searchType= 'b.author';
                     break;
                 case 2:
                     $searchType= 'b.book_number';
                     break;
                 default:
                     $searchType = 'b.name';
                     break;
             }
             $where[$searchType] = array('like','%'.$searchKey.'%');
             $count=M('book')->alias('b')->where($where)->count();
         }elseif($newoldbook){
            date_default_timezone_set("Asia/Shanghai");
            $time=date("Y-m-d 00:00:00");
            $deadline=date("Y-m-d 24:00:00");
            $news=strtotime($time);
            $dead=strtotime($deadline);
            $where['b.time']=array(array('gt',$news),array('lt',$dead));
            $count=D('book')->alias('b')->where($where)->count();
        }else{
             $count=M('book')->alias('b')->where($where)->count();
         }
         $page=new \Think\Page($count,10);
         $data = M('book')->alias('b')
         ->join('left join kts_book_attr att on b.book_id =att.book_id')
         ->join('left join kts_image i on i.book_id = b.book_id')
         ->join('left join kts_book_add ad on ad.book_id = b.book_id')
         ->join('left join kts_category ca on ca.cg_id =b.f_id')
         //->join('left join kts_catalog c on c.book_id =b.book_id')
         ->field('b.book_id,b.name,b.type,b.book_number,b.inventory,b.state,b.f_id,b.price,b.author,i.imageurl,ad.author_area,ca.name as category')
         ->group('b.book_id')
         ->where($where)->order('b.book_id desc')->limit($page->firstRow.','.$page->listRows)->select();
         //print_r($data);
         $this->page=$page->show();
         $this->list=$data;
         $this->display();
         return $data;
     
     }
     
     
     public function book($n='15')
     {
         $searchKey = I('get.searchKey');
         if ($searchKey){
             $searchType = I('get.searchType',0);
             switch($searchType)
             {
                 case 1:
                     $searchType= 'phone';
                     break;
                 default:
                     $searchType = 'name';
                     break;
             }
             $where[$searchType] = array('like','%'.$searchKey.'%');
         }
         $book_number=I('request.book_number');
         $user_id=I('request.user_id');
     
         $bookList = M('book');
         $count = $bookList->alias('b')->where('b.isdelete=0 and b.user_id=0')->count();
         $Page = new \Think\Page($count,$n);
         $this->Page = $Page->show();
         $list = $bookList->alias('b')
         ->join('left join kts_book_attr att on b.book_id =att.book_id')
         ->join('left join kts_image i on i.book_id = b.book_id')
         ->join('left join kts_book_add ad on ad.book_id = b.book_id')
         ->join('left join kts_category ca on ca.cg_id =b.f_id')
         //->join('left join kts_catalog c on c.book_id =b.book_id')
         ->field('b.book_id,b.name,b.type,b.book_number,b.inventory,b.f_id,b.price,b.author,i.imageurl,ad.author_area,ca.name as category')
         ->group('b.book_id')
         ->where('b.isdelete=0 and b.user_id=0')->order('b.book_id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
         //$this->list=$list;
         $this->display();
     }
     
     /**
      * 添加图书
      */
     public function bookAdd()
     {
     
         if (IS_POST){
             $type=I('post.type');
             $book_number=I('post.book_number');
             $user_id=I('post.sell_id');
             if ($type==2){
                $ret=M('book')->where(array('book_number'=>$book_number,'user_id'=>$user_id))->find();
                if ($ret){
                    $this->error('该用户已经分享过这本书了');
                }
             }
             if ($_FILES){
                 $video=$this->uploadFile();
             }
             if (I('post.father_id')){
                 if ((int)I('post.father_id')==9999){
                     $fid=0;
                 }else{
                     $fid=I('post.father_id');
                 }
             }
             if (I('post.class_id')){
                 if ((int)I('post.class_id')==9999){
                     $fid=I('post.father_id');
                 }else{
                     $fid=I('post.class_id');
                 }
             }if (I('post.fid')){
                 if ((int)I('post.fid')==9999){
                     $fid=I('post.class_id');
                 }else{
                     $fid=I('post.fid');
                 }
             }
             $format=I('post.format');
             if(strpos($format, '开')){
                 $format=$format;
             }else {
                 $format=$format.'开';
             }
             $book=array(
                 'f_id'=>$fid,
                 'name'=>I('post.book_name'),
                 'author'=>I('post.author'),
                 'inventory'=>I('post.total'),
                 'book_number'=>$book_number,
                 'state'=>I('post.status'),
                 'price'=>I('post.price'),
                 'type'=>$type,
             );
             if ($type!=2){
                 $book['time']=time();
             }
             $book_id=M('book')->add($book);
             if ($type==0){
                 $old_book=array(
                     'book_id'=>$book_id,
                     'book_number'=>$book_number,
                     'description'=>I('post.description'),
                     'sell_time'=>time(),
                     'sell_address'=>I('post.address'),
                     'user_id'=>I('post.sell_id'),
                 );
                 M('old_book')->add($old_book);
             }elseif ($type==2){
                 $share=array(
                     'book_id'=>$book_id,
                     'book_number'=>$book_number,
                     'description'=>I('post.description'),
                     'share_time'=>time(),
                     'share_address'=>I('post.address'),
                     'user_id'=>I('post.sell_id'),
                 );
                 M('share')->add($share);
             }
             $attr=array(
                 'book_id'=>$book_id,
                 'press'=>I('post.press'),
                 'publish_time'=>strtotime(I('post.publish_time')),
                 'publish_price'=>I('post.publish_price'),
                 'impression'=>I('post.impression'),
                 'page'=>I('post.page'),
                 'edition'=>I('post.edition'),
                 'words'=>I('post.words'),
                 'paper'=>I('post.paper'),
                 'format'=>$format,
                 'binding'=>I('post.binding'),
                 'language'=>I('post.language'),
             );
             $at=M('book_attr')->add($attr);
             $add=array(
                 'book_id'=>$book_id,
                 'introduce'=>I('post.introduce'),
                 'author_desc'=>I('post.author_desc'),
                 'author_area'=>I('post.county'),
                 'applicable_age'=>I('post.age'),
             );
             $ad =M('book_add')->add($add);
             $catalog=array(
                 'book_id'=>$book_id,
                 'book_number'=>$book_number,
                 'description'=>I('post.catalog'),
             );
             $ca=M('catalog')->add($catalog);
             $data=I('post.file');
             $data=array_unique($data);
             if ($data){
                 foreach ($data as $k=>$v){
                     $pic=array('book_id'=>$book_id,'imageurl'=>$v);
                     M('image')->add($pic);
                 }
             }
             if ($video){
                 foreach ($video as $k=>$v){
                     $video=array('book_id'=>$book_id,'title'=>I('post.title')[$k],'url'=>$v);
                     M('video')->add($video);
                 }
             }
             if ($book_id){
                 $this->clean_all();
                 $this->success('添加成功',U('Admin/Book/shareList'));
             }
              
         }
         $this->country=M('country')->field('name_chinese')->select();
         $this->user=M('user')->where('is_show=0')->field('user_id,name')->select();
         $paper=array('凸版印刷纸','新闻纸','胶版印刷纸','铜板纸','书皮纸','字典纸','拷贝纸','板纸');
         $language=array('中文','英文','法语','德语','俄语','韩语','日语','印度语');
         $binding=array('平装 ','精装','线装');
         $this->lang=$language;
         $this->paper=$paper;
         $this->binding=$binding;
         $this->book=$this->bookTypeList();
         $this->display();
     }
     
     
     
     /**
      * 查看更多
      */
     public function bookDet($book_id='0',$type='0')
     {
         $book=array();
         $where['b.book_id'] = $book_id;
         $vi =M('book');
         $book['attr']=$vi->alias('b')
         ->join('left join kts_category as cate on b.f_id = cate.cg_id')
         ->join("LEFT JOIN kts_book_attr as a ON b.book_id = a.book_id")
         ->join("left join kts_book_add as f on b.book_id = f.book_id")
         ->join('left join kts_catalog as c on c.book_id=b.book_id')
         ->where($where)
         ->field('b.book_id,b.name,b.author,f.author_desc,f.author_area,b.price,a.press,a.publish_time,a.page,a.words,
                                 f.introduce,f.applicable_age,a.language,c.description,b.book_number,
                                 cate.name as category')
                                      ->find();
         $book['attr']['comment']=M('book_comment')->alias('c')->where(array('fid'=>0,'is_show'=>1,'book_number'=>$book['attr']['book_number']))->count().'条商品评价';
         //$book['cata']=$this->bookCatalog($book_id);
         $book['image']=M('image')->where(array('book_id'=>$book_id))->field('imageurl,img_id')->limit(8)->select();
         $book['video']=M('video')->where(array('book_id'=>$book_id))->field('title,url,vid')->limit(8)->select();
         $book['attr']['description']=htmlspecialchars_decode($book['attr']['description']);
         $this->book=$book;
         //print_r($book);
         $this->display();
     }
     /**
      * 分享列表
      * **/
     public function shareList()
     {
         $book_number=I('request.book_number');
         $user_id=I('request.user_id');
         $newshare=I('get.newshare');
         if($book_number){
             $data=$this->share($book_number);
         }elseif ($user_id){
             $data=$this->share(0,$user_id);
         }elseif ($newshare){                
                 $data=$this->share(0,0,$newshare);
         }else {
             $data=$this->share();
         }
         //print_r($data);
         $this->list=$data['list'];
         $this->assign('page',$data['page']);
         $this->display();
     }
     
     /**
      * 删除分享
      * **/
     public function shareDel(){
         $share_id=I('share_id');
         $book_id=I('book_id');
         $result=M('share')->where(array('share_id'=>$share_id))->save(array('is_show'=>0));
         if ($result){
             $msg=1;
         }else{
             $msg=0;
         }
         $data=M('book')->where(array('book_id'=>$book_id))->save(array('isdelete'=>1));
         if($data && msg){
             $this->clean_all();
             $this->success('删除成功',U('Admin/Book/shareList'));
         }else {
             $this->error('删除失败');
         }
     }
     
     public function bookDel(){
         $book_id=I('book_id');
         $type=M('book')->find($book_id);
         $data=M('book')->where(array('book_id'=>$book_id))->save(array('isdelete'=>1));
         if($data){
             $this->clean_all();
             $this->success('删除成功',U('Admin/Book/bookList'));
         }else {
             $this->error('删除失败');
         }
     }
     
     public function bookCatalog($book_id){
         //$book_id=(int)I('request.book_id');
         $boo=M('book')->field('book_number')->find($book_id);
         $data=M('catalog')->where(array('book_id'=>$book_id))
         ->field('description')->find();
         //print_r($data);
         return $data;
     }
     
     
     
     static public function tree($items,$f_id=0,$level=0)
     {
          
         $tree = array();
         foreach ($items as $v) {
             if ($v['f_id'] == $f_id) {
                 $v['son'] = self::tree($items, $v['cg_id'], $level+1);
                 $tree[] = $v;
             }
         }
         return $tree;
     }
     static public function get_parent($cg_id)
     {
     
         $r = M('category')->field('cg_id,name,f_id')->where('cg_id='.$cg_id)->find();
         if($r['f_id']!=null){
             $r['parent'] = self::get_parent($r['f_id']);
             $ra[] = $r;
         }
         return $ra;
     
     }
      
     public function get_fid($data){
         foreach ($data as $k=>$v){
             $html .=$v['cg_id'].',';
             if (!empty($data[$k]['parent'])){
                 $html .=$this->get_fid($data[$k]['parent']);
             }
              
         }
         return $html;
     }
     
     /**
      * 获取图书类型列表
      */
     public function bookTypeList()
     {
         header("Content-type: text/html;charset=utf-8");
         $items = M('category')->field('cg_id,name,imageurl,f_id')->select();
         $categoryList = self::tree($items);
         return $categoryList;
         print_r($categoryList);
     }
     /**
      * 获取某一图书类型值并找到其父节点
      */
     public function bookTypeFind()
     {
         $cg_id = I('get.cg_id',0);
         // $items = M('category')->field('cg_id,name,imageurl,f_id')->select();
         $categoryList = self::get_parent($cg_id);
         //var_dump($categoryList);
         if ($categoryList)
             $this->myApiPrint('success',200,$categoryList);
         else if ($categoryList === null)
             $this->myApiPrint('暂无数据',202);
         else
             $this->myApiPrint('系统繁忙，请稍后再试',300);
     }
     
     public function uploadImage(){
         import('Org.Net.UploadFile');
         $upload= new \UploadFile();
         $upload->maxSize = 31424871;
         $upload->allowExts =array('wmv','mp4','avi','jpg', 'gif', 'png', 'jpeg');
         $upload->savePath='Public/Upload/Book/image/'.date('Y-m-d').'/';
         $info =$upload->upload();
         $info =$upload->getUploadFileInfo();
         if ($info){
             foreach ($info as $v){
                 $data[]=C('IMG_SITE_PREFIX').$v['savepath'].$v['savename'];
             }
         }else {
             $data=$upload->getErrorMsg();
         }
         return $data;
     }
     
     public function imageEdit(){
         $where['img_id']=I('get.img_id');
         $where['book_id']=I('get.book_id');
         $image=M('image')->where($where)->field('imageurl')->find();
         if (IS_POST){
             $imageurl=$this->uploadFile(0,1);             
             if ($imageurl=="没有选择上传文件"){
                 $this->redirect('Admin/Book/bookEdit',array('book_id'=>$where['book_id']),0);
             }
             $a=@unlink($image['imageurl']);
             $data=array('imageurl'=>$imageurl[0]);
             $result=M('image')->where($where)->save($data);
             if ($a || $result){
                 $this->clean_all();
                 $this->redirect('Admin/Book/bookEdit',array('book_id'=>$where['book_id']),3,'正在跳转，请稍候......');
             }
         }
         $this->img=$image;
         $this->display();
     }
     
     
     public function changeState(){
         $book_id=I('get.book_id');
         $data=M('book')->find($book_id);
         if ($data['state']==1){
             $state=2;
         }elseif ($data['state']==2){
             $state=1;
         }
         $result=M('book')->where(array('book_id'=>$book_id))->save(array('state'=>$state));
         if ($result){
             $this->clean_all();
             $this->ajaxReturn('true');
         }else{
             $this->ajaxReturn('false');
         }
     }
     
     
     
     public function bookReduce(){
         $book_id=I('post.book_id');
         $type=I('post.book_type');
         $data=M('book')->where(array('book_id'=>$book_id))->setDec('inventory');
         if ($data){
             $this->clean_all();
             $ret=M('book')->where(array('book_id'=>$book_id))->getField('inventory');
             if ($ret){
                 $this->ajaxReturn($ret);
             }
         }
     }
     
     public function bookInc(){
         $book_id=I('post.book_id');
         $type=I('post.book_type');
         $data=M('book')->where(array('book_id'=>$book_id))->setInc('inventory');
         if ($data){
             $this->clean_all();
             $ret=M('book')->where(array('book_id'=>$book_id))->getField('inventory');
             if ($ret){
                 $this->ajaxReturn($ret);
             }
         }
     }
     
     public function bookTotal(){
         $book_id=I('post.book_id');
         $type=I('post.book_type');
         $value=I('post.value');
         $data=M('book')->where(array('book_id'=>$book_id))->save(array('inventory'=>$value));
         if ($data){
             $this->clean_all();
             $this->ajaxReturn("success");
         }
     }
     
     public function bookPrice(){
         $book_id=I('post.book_id');
         $value=I('post.value');
         $type=I('post.book_type');
         $data=M('book')->where(array('book_id'=>$book_id))->save(array('price'=>$value));
         if ($data){
             $this->clean_all();
             $this->ajaxReturn("success");
         }
     } 
 
} 
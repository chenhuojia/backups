<?php
/**
 * 图书管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
class BookController extends AdminController {

    /**
     * 图书类型列表
     */
    public function categoryList($n = '12')
    {
        header("Content-type: text/html;charset=utf-8");
        $father_id = I('get.father_id');
        $class_id = I('get.class_id');
        $name = I('get.name');
        $where=array();
        //过滤筛选条件
        if($father_id!=0 && $class_id ==0)
        {
            $like = $father_id;
            $where['_string'] = "(f_id like '$like')  OR ( cg_id like '$like') ";
            // $second_id = M('category')->where('f_id='.$father_id)-select();
            // foreach ($second_id as $key => $value) {
            //     $third_id = M('category')->where('f_id='.$second_id[$key]['cg_id'])-select();
            //     foreach ($third_id as $key1 => $value1) {
            //         $like1 = $third_id[$key1]['cg_id'];
            //         $where['_string'].= "or (cg_id like '$like')";
            //     }
            // }
        }
        if ($class_id!=0)
        {
            //$where['f_id']=$class_id;
            $like = $class_id;
            $where['_string'] = "(f_id like '$like')  OR ( cg_id like '$like') ";
        }
        if ($name)
        {
             $where['name']=array('like','%'.$name.'%');
        }

        $note = M('category')->alias('v1');
        $count = $note->where($where)->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $items =  M('category')
            // ->join('left join kts_groups v2 on v1.groups_id = v2.group_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)->field('cg_id,name,imageurl,f_id')
            ->select();
        foreach ($items as $key => $value) {
            $items[$key]['relation'] = self::get_parent($items[$key]['cg_id']);  
            
        }

        // foreach ($items as $k => $v) {
        //         $items[$k]['introduce'] = $items[$k]['name'];
        //         foreach ($items[$k]['parent'] as $k1 => $v1) {
        //             $items[$k]['introduce'].="<<".$items[$k]['parent'][$k1]['name'];
        //             foreach ($items[$k]['parent'][$k1]['parent'] as $k2 => $v2)
        //             {
        //                    $items[$k]['introduce'].="<<".$items[$k]['parent'][$k1]['parent'][$k2]['name'];
        //             }


        //         }
            
        // }
        // 查询某id对应的全部列表(三级)
         foreach ($items as $k => $v) {
              foreach ($items[$k]['relation'] as $key => $value) {
                $items[$k]['introduce'] = $items[$k]['relation'][$key]['name'];
                $items[$k]['level'] =1;
                foreach ($items[$k]['relation'][$key]['parent'] as $k1 => $v1) {
                    $items[$k]['introduce'].="<<".$items[$k]['relation'][$key]['parent'][$k1]['name'];
                    $items[$k]['level'] = 2;
                    foreach ($items[$k]['relation'][$key]['parent'][$k1]['parent'] as $k2 => $v2)
                    {
                           $items[$k]['introduce'].="<<".$items[$k]['relation'][$key]['parent'][$k1]['parent'][$k2]['name'];
                           $items[$k]['level'] = 3;
                    }

                 }
              }
            
        }







        $this->list=$items;
        //var_dump( $this->list);
        //die;
       
        $this->typeList = M('category')->where('f_id='.'0')->field('cg_id,name,imageurl,f_id')->select();
        $this->display();

    }



    /**
     * 级联返回的栏目类型
     */
    public function childTypeFind()
    {
        $father_id = I('post.father_id');
        $where['f_id'] = $father_id;
        $result = M('category')->where($where)->field('cg_id,name,imageurl,f_id')->select();
        $this->ajaxReturn($result,"JSON");
    }



     /**
     * 新增图书类型
     */
    public function categoryAdd()
    {   
            header("Content-type: text/html;charset=utf-8");
           
            //$data['is_show'] =1;
            $data['f_id'] = 0;
            $this->typeList = M('category')->where($data)->field('cg_id,name,imageurl,f_id')->select();
            $this->display();
    }
    /**
     * 新增图书类型
     */
    public function categoryPost()
    {       
            C('TOKEN_ON',false);//指定页面无令牌表单验证
            // $result['status'] = 1 ;
            // $this->ajaxReturn($result,"JSON");
            // die;

            $father_id = I('post.father_id');
            $class_id = I('post.class_id');
            $data['name'] = I('post.name');
            if($father_id==0){
                $data['f_id'] = 0;
                $data['is_show'] = 0;
            }
            elseif($class_id !=0){
              $data['f_id'] = $class_id;
              //判断是否父节点is_show是否为空
              $whereand = array('cg_id'=>$class_id,'is_show'=>'1');
              $second = M('category')->where($whereand)->find();
              //父节点is_show为0时
              if($second==null){
                $data['is_show'] = 1;
                $seconddata['is_show'] = 1;
                //父级都变成1
                M('category')->where('cg_id = '.$class_id)->save($seconddata);//父节点is_show改为1
                //$second1= M('category')->where('cg_id='.$father_id)->find();
                M('category')->where('cg_id = '.$father_id)->save($seconddata);//爷节点is_whow改为1
              }else{
                $data['is_show'] = 1;
              }
            }else{
              $data['f_id'] = $father_id;
              $data['is_show'] = 0;
            }
            $categoryType = M('category');
            if (!$categoryType->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($categoryType->getError());
            }
            $lastId=$categoryType->add($data);
            if ($lastId) {

                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,1);// 记录操作日志
                /*$this->success('新增图书类型成功', U('Admin/Book/categoryAdd'));*/
                $result['status'] = 1 ;
                $this->ajaxReturn($result,"JSON");
            }else{
                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,0);// 记录操作日志
                /*$this->error('新增图书类型失败');*/
                $result['status'] = 0 ;
                $this->ajaxReturn($result,"JSON");
            }
    }

     /**
     * 新增图书类型
     */
    public function categoryAdd1()
    {   
        header("Content-type: text/html;charset=utf-8");
        // if ($_FILES){
        //     $data['imageurl']=$this->upload();
        // }  
        
        if (IS_POST)
        {   
           
            $father_id = I('post.father_id');
            $class_id = I('post.class_id');
            $data['name'] = I('post.name');
            if($father_id==0){
                $data['f_id'] = 0;
                $data['is_show'] = 0;
            }
            elseif($class_id !=0){
              $data['f_id'] = $class_id;
              //判断是否父节点is_show是否为空
              $whereand = array('cg_id'=>$class_id,'is_show'=>'1');
              $second = M('category')->where($whereand)->find();
              //父节点is_show为0时
              if($second==null){
                $data['is_show'] = 1;
                $seconddata['is_show'] = 1;
                //父级都变成1
                M('category')->where('cg_id = '.$class_id)->save($seconddata);//父节点is_show改为1
                //$second1= M('category')->where('cg_id='.$father_id)->find();
                M('category')->where('cg_id = '.$father_id)->save($seconddata);//爷节点is_whow改为1
              }else{
                $data['is_show'] = 1;
              }
            }else{
              $data['f_id'] = $father_id;
              $data['is_show'] = 0;
            }
            $categoryType = M('category');
            if (!$categoryType->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($categoryType->getError());
            }
            $lastId=$categoryType->add($data);
            if ($lastId) {

                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,1);// 记录操作日志
                $this->success('新增图书类型成功', U('Admin/Book/categoryAdd'));
            }else{
                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,0);// 记录操作日志
                $this->error('新增图书类型失败');
            }
        }
        else
        {   
            //$data['is_show'] =1;
            $data['f_id'] = 0;
            $this->typeList = M('category')->where($data)->field('cg_id,name,imageurl,f_id')->select();
            $this->display();
        } 
       
    }


    public function findCategory($id='0')
    {
        $typeList = M('category')->where('id='.$id)->field('cg_id,name,imageurl,f_id')->find();
        return $typeList;
    }

   /**
     * 修改图书类型信息
     */
    public function categoryEdit($cg_id='0')
    {   

        // if ($_FILES){
        //     $_POST['imageurl']=$this->upload();
        // } 

        if (IS_POST)
        {   
            // if($_POST['imageurl']==null){
            //   $_POST['imageurl']=$_POST['imageurl1'];
            // }
            if($_POST['father_id']!=0){
              $_POST['f_id'] = $_POST['father_id'];
            }
            if($_POST['class_id']!=0){
               $_POST['f_id'] = $_POST['class_id'];
            }
            // echo $cg_id;
            // var_dump($_POST);
            //  die;
            $categoryClass = M('category');
            if (!$categoryClass->create($_POST,2)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($categoryClass->getError());
            }
            $lastId=$categoryClass->where('cg_id = '.$cg_id)->save($_POST);
            if ($lastId !== false) {
                //当父类为隐藏的时候，所有子类也都相应地隐藏
                if($_POST['is_show'] ==0){

                        $catagorychildrent = M('category')->field('cg_id,name,imageurl,f_id')->select();
                        $this->childrentchange($catagorychildrent,$cg_id);
                }else{
                    $seconddata['is_show'] = 1;
                    //父级都变成1
                    $first1= M('category')->where('cg_id='.$cg_id)->find();
                    if($first1){
                         M('category')->where('cg_id = '.$first1['f_id'])->save($seconddata);
                         $second1= M('category')->where('cg_id='.$first1['f_id'])->find();
                         if($second1!=null){
                            M('category')->where('cg_id = '.$second1['f_id'])->save($seconddata);
                         }
                    }
                   
                    
                }
                $this->clean_all();
                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,1);// 记录操作日志
                $this->success('编辑图书类型成功', U('Admin/Book/categoryList'));
            }else{
                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,0);// 记录操作日志
                $this->error('编辑图书类型失败');
            }
        }
        else
        {   

            $this->typeList = M('category')->where('f_id = 0')->field('cg_id,name,imageurl,f_id')->select();
            $this->classType = M('category')->field('cg_id,name,imageurl,f_id,is_show')->find($cg_id);
            $this->display();
            
        } 
        
    }
    
   //找到相应的父id得值，并修改其状态
   public function childrentchange($arr,$f_id,$level=0)
   {    
        
        foreach ($arr as $value) {
            if ($value['f_id'] == $f_id) {
                    $data['is_show'] = 0;
                    M('category')->where('cg_id = '.$value['cg_id'])->save($data);
                    self::childrentchange($arr,$value['f_id'],$level+1);
            }
        }
        $this->clean_all();
     } 
         
     /**
      * 修改图书信息
      */
     public function bookEdit()
     {           
         $type=I('get.type');
         $book_id=I('get.book_id');
         $where['b.book_id']=$book_id;
         $data=M('book')->alias('b')
                 ->join('left join kts_book_attr att on att.book_id =b.book_id')
                 ->join('left join kts_book_inventory i on i.book_id =b.book_id')
                 ->join('left join kts_book_recommend  r on r.book_id =b.book_id')
                 ->field('b.*,b.name as book_name,r.status,i.inventory,att.*')
                 ->where($where)
                 ->find(); 
         $data['cover_img']=C('QINIU').$data['cover_img'];
         $data['copyright']=C('QINIU').$data['copyright'];
         $image=array();
         if ($data['other']){
             $image=explode(';',$data['other']);
             foreach ($image as $k=>$v){
                 $img[$k]['name']=$v;
                 $img[$k]['url']=C('QINIU').$v;
             }
         }
         $data['publish_time']=date('Y-m-d',$data['publish_time']);
         $paper=array('凸版印刷纸','新闻纸','胶版印刷纸','铜板纸','书皮纸','字典纸','拷贝纸','板纸');
         $language=array('中文','英文','法语','德语','俄语','韩语','日语','印度语');
         $binding=array('平装 ','精装','线装');
         $this->book=M('category')->where(array('f_id'=>0))->field('cg_id,name')->select();
         $data['f_id']=M('book_tag')->where('book_id='.$book_id)->select();
         $this->catalog=M('catalog')->where('book_id='.$book_id)->getField('description'); 
         if ($data['type']==1){
             $this->total=M('book_inventory')->where('book_id='.$book_id)->getField('inventory');
         }elseif ($data['type']==0){
             $old=M('book_old')->where('book_id='.$book_id)->find();
             $old['user']=M('user')->where(array('user_id'=>$old['user_id']))->getField('name');
             $this->old=$old; 
         }
         foreach ($data['f_id'] as $k=>$v){
             $a=array();
             $a[]=M('category')->where(array('f_id'=>$v['first']))->field('cg_id,name')->select();
             $a[]=M('category')->where(array('f_id'=>$v['second']))->field('cg_id,name')->select();
             $tmp[]=$a;
         }
         $this->fid=$tmp;
         $this->data=$data;
         $this->image=$img;
         $this->lang=$language;
         $this->paper=$paper;
         $this->binding=$binding;
         $this->display();
        
     
     }
     
     public function html($data){
         //echo count($data);
         for ($i=count($data)-1;$i>=0;$i--){
             if ($i==0){
                 $html .=$data[$i];
             }else 
             $html .=$data[$i].">>";
         }
         return $html;
     }
     
     /**
      * 图书列表
      */
     public function bookList()
     {
        $searchKey = I('get.searchKey',0);
        $newbook=I('get.newbook');
         $fid=I('get.f_id');        
         $where['b.isdelete']=0;
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
        }elseif ($fid){
            $where['ca.cg_id']=$fid;
            $count=M('book_tag')->where(array('cg_id'=>$fid))->count();
         }else{
            $count=M('book')->alias('b')->where($where)->count();
         }
         $page=new \Think\Page($count,10);
         $data = M('book')->alias('b')
                 ->join('left join kts_book_tag ca on ca.book_id =b.book_id')
                 ->join('left join kts_book_recommend r on r.book_id =b.book_id')
                 ->join('left join kts_book_inventory count on count.book_id =b.book_id')
                 ->field('b.*,ca.cg_id,r.status,count.inventory as total')
                 ->where($where)->order('b.book_id desc')->group('b.book_id')->limit($page->firstRow.','.$page->listRows)->select();      
         foreach ($data as $k=>$v){
             $data[$k]['cover_img']=C('QINIU').$v['cover_img'];
             if ($v['status']==null || $v['status']==0){
                 $v['status']==0;
             }
             if ($v['total']==null){
                 $v['total']==0;
             }
             $category=M('category')->find($v['cg_id']);
             $data[$k]['category']=$category['name'];
         }
         
         $this->page=$page->show();
         $this->list=$data;
         $this->display();
     }
     
     
     
     public function oldBookList()
     {
         $searchKey = I('get.searchKey');
         $newoldbook=I('get.newoldbook');
         $fid=I('get.f_id');        
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
            $where['old.sell_time']=array(array('gt',$news),array('lt',$dead));
            $count=D('old_book')->alias('old')->where($where)->count();
        }elseif ($fid){
              $where['t.cg_id']=$fid;
              $count=M('book_tag')->where(array('cg_id'=>$fid))->count();
         }else{
             $count=M('old_book')->alias('b')->where($where)->count();
         }
         $page=new \Think\Page($count,10);
         $data = M('old_book')->alias('old')
            ->join('left join kts_book b on old.book_id = b.book_id')
            ->join('left join kts_book_inventory i on old.book_id = i.book_id')
            ->join('left join kts_book_tag t on old.book_id = t.book_id')
            ->join('left join kts_category c on t.cg_id = c.cg_id')
            ->join('left join kts_book_attr att on old.book_id = att.book_id')
            ->join('left join kts_image a on old.book_id = a.book_id')
            ->where($where)
            ->group('old.book_id')
            ->field('b.book_id,b.name,a.imageurl,c.name as category,c.cg_id as f_id,b.book_number,i.inventory,b.author,b.type,b.price,old.user_id,old.sell_time,old.description')             
            ->limit($page->firstRow.','.$page->listRows)->select();
         foreach ($data as $k=>$v){
             $data[$k]['imageurl']=C('QINIU').$v['imageurl'];
         }
         $this->page=$page->show();
         $this->list=$data;
         $this->display();
         return $data;
     
     }
     

     
     /**
      * 添加图书
      */
     public function bookAdd()
     { 
         $this->age=M('book_aplic_age')->where('is_show=1')->select();
         $this->shop=M('shop')->where('is_show=1')->select();
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
                 ->join("LEFT JOIN kts_book_attr as a ON b.book_id = a.book_id")
                 ->join('left join kts_catalog as c on c.book_id=b.book_id')
                 ->join('left join kts_user as u on b.user_id=u.user_id')
                 ->where($where)
                 ->field('b.*,a.*,c.description,b.book_number,u.name as user_name')
                 ->find();        
         $book['attr']['category']=M('book_tag')->alias('t')
             ->join('left join kts_category as c on t.cg_id = c.cg_id')
             ->field('c.name')   
             ->where(array('book_id'=>$book_id))->select();
         if ($book['attr']['type']==0){
            $book['attr']['old_book']='二手书';
            $book['attr']['old_desc']=M('book_old')->where(array('book_id'=>$book_id))->find();
         }elseif ($book['attr']['type']==2){
             $book['attr']['share_book']='分享书';
             $book_number=$book['attr']['book_number'];
             $book['attr']['share']=M('book_share')->alias('s')->where(array('book_number'=>$book_number,'s.user_id'=>array('neq',$book['attr']['user_id'])))
              ->join('left join kts_user u on u.user_id=s.user_id')->select();
         }elseif ($book['attr']['type']==1){            
             $book['attr']['shop']=M('shop')->find($book['attr']['shop_id']);
         }
         $book['attr']['comment']=M('book_comment')->alias('c')->where(array('fid'=>0,'is_show'=>1,'book_number'=>$book['attr']['book_number']))->count().'条商品评价';
         //$book['cata']=$this->bookCatalog($book_id);
         $book['image'][]=C('QINIU').$book['attr']['cover_img'];
         if ($book['attr']['other']){
             $img=explode(';',$book['attr']['other']);
             foreach ($img as $k=>$v){
                 $book['image'][]=C('QINIU').$v;
             }
         }
         $book['video']=array();
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
         $fid=I('get.f_id');
         if($book_number){
             $data=$this->share($book_number);
         }elseif ($user_id){
             $data=$this->share(0,$user_id);
         }elseif ($newshare){                
                 $data=$this->share(0,0,$newshare);
         }elseif ($fid){
             $data=$this->share(0,0,0,$fid);
         }
         else{
             $data=$this->share();
         }
         print_r($data);
         $this->list=$data['list'];
         $this->assign('page',$data['page']);
         $this->display();
     }

     public function MyOrderNo22(){
         $code  =date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
         $code .= randCodeM(22-strlen($code),1);
         return $code;
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
     

     
     public function bookCatalog($book_id){
         //$book_id=(int)I('request.book_id');
         //$boo=M('book')->field('book_number')->find($book_id);
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
      
     public function get_fid($cg_id){
         $r=M('category')->find($cg_id);                        
             if ($r['f_id']!=null){
                 $html .=$this->get_fid($r['f_id']);
             }
             $html .=$r['cg_id'].',';   
         return $html;
     }
     
     public function get_html($cg_id){
         $r = M('category')->field('cg_id,name,f_id')->where('cg_id='.$cg_id)->find();                 
         $html=$r['name'].',';
             if ($r['f_id']!=0){   
                 $html .=$this->get_html($r['f_id']);                                 
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
     public function bookTypeFind($cg_id)
     {
         // $items = M('category')->field('cg_id,name,imageurl,f_id')->select();
         $categoryList = self::get_parent($cg_id);
         return $categoryList;
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
         if (IS_POST && $_FILES['img']['tmp_name']){
             $imageurl=C('QINIU').$this->uploadF($_FILES['img']['tmp_name']);            
             if (!$imageurl){
                 $this->redirect('Admin/Book/bookEdit',array('book_id'=>$where['book_id']),0);
             }
             $this->del($image['imageurl']);
             $data=array('imageurl'=>$imageurl);
             $result=M('image')->where($where)->save($data);
             if ($a || $result){
                 //$this->clean_all();
                 $this->redirect('Admin/Book/bookEdit',array('book_id'=>$where['book_id']),3,'正在跳转，请稍候......');
             }
         }
         $this->img=$image;
         $this->display();
     }
     
     
     public  function  changeCategory(){        
         $book_id=I('post.book_id');
         $cg_id=I('post.cg_id');
         $old_cgid=I('post.old_cgid');
         if ($old_cgid){
             $data=M('book_tag')->where(array('book_id'=>$book_id,'cg_id'=>$old_cgid))->find();
             if ($data){
                 if ($data['cg_id']==$cg_id){
                     $this->ajaxReturn(202);
                 }else{
                     $result=M('book_tag')->where(array('id'=>$data['id']))->save(array('cg_id'=>$cg_id));
                     if ($result){
                         $msg='success';
                     }else {
                         $msg='';
                     }
                 }
             }else{
                 M('book_tag')->where(array('cg_id'=>$old_cgid))->delete();
                 $result=M('book_tag')->add(array('book_id'=>$book_id,'cg_id'=>$cg_id));
                 if ($result){
                     $msg='success';
                 }else {
                     $msg='';
                 }
             }             
         }else{
             M('book_tag')->where(array('cg_id'=>$old_cgid))->delete();
             $result=M('book_tag')->add(array('book_id'=>$book_id,'cg_id'=>$cg_id));
             if ($result){
                 $msg=$result;
             }else {
                 $msg='';
             } 
         }
         if ($msg){
             $this->ajaxReturn($result);
         }else {
             $this->ajaxReturn(0);
         }
         
     } 
     
     public  function  changeCategory1(){
        $book_id=I('post.book_id');
         $cg_id=I('post.cg_id');
         $old_cgid=I('post.old_cgid',0);
             $data=M('book_tag')->where(array('book_id'=>$book_id,'cg_id'=>$old_cgid))->find();
             if ($data){
                 if ($data['cg_id']==$cg_id){
                     $this->ajaxReturn(202);
                 }else{
                     $result=M('book_tag')->where(array('id'=>$data['id']))->save(array('cg_id'=>$cg_id));
                     if ($result){
                         $msg='success';
                     }else {
                         $msg='';
                     }
                 }
             }else{
                 M('book_tag')->where(array('cg_id'=>$old_cgid))->delete();
                 $result=M('book_tag')->add(array('book_id'=>$book_id,'cg_id'=>$cg_id));
                 if ($result){
                     $msg='success';
                 }else {
                     $msg='';
                 }
             }
             if ($msg){
                 $this->ajaxReturn($result);
             }else {
                 $this->ajaxReturn(0);
             }         
     }
     
     

     
} 
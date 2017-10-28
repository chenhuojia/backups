<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model\MongoModel;
use Think\Db\Driver\Mongo;
class BookController extends BaseController {

    public function index()
    { 
        //栏目
        $category=$this->category(); 
        $this->assign('category',$category);
        //筛选图书
        $user_id = I('get.user_id',0);
        $type = I('get.type',3);//1普通的书 0二手书 2分享的书 
        $lowprice = I('get.lowprice',''); 
        $highprice = I('get.highprice','');
        $key=I('get.order');
        $key=safe_replace($key);//过滤
        $sort=I('get.sort');  
        $sort=safe_replace($sort);//过滤
        if($type < 3){
            $map['o.type'] = $type;
        }
        if($key){ 
           if(!is_numeric($key)){
                 $this->error('排序ID错误！');
           }
           switch ($key) { 
                case '0': //默认
                    $listsort="o.book_id"." ".$sort;
                break;
                case '1'://人气
                   $listsort="d.click_num"." ".$sort;
                  break;                
               case '2'://销量 
                   $listsort="d.sell_num"." ".$sort;
                  break;
                case '3'://价格                    
                   $listsort="o.price"." ".$sort;
                     break;              
           }    
       } 
       else {
         $order="o.book_id";$sort="asc";
         $listsort=$order." ".$sort;            
       }
        
        if(trim($lowprice) !== '' && trim($highprice) !== ''){
           $map['o.price']=array('between',array($lowprice,$highprice));
        }
        $where['o.type'] = $type;
        $count=M('book')->alias('o')
               ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
               ->join('LEFT JOIN kts_book_add as d ON d.book_number = o.book_number')
               ->where($where)->count();
        $page=new \Think\Page($count,5);
        // 通过匹配信息查询出相关书籍
        $bookList = M('book')->alias('o')
        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
        ->join('LEFT JOIN kts_book_add as d ON d.book_number = o.book_number')
        ->where($map)
        ->order($listsort)
        ->group('o.book_id')
        ->field('o.book_id,o.name,o.author,o.price,o.username as share_user,o.imageurl as user_img,o.user_id,o.type,r.publish_price')
        ->limit(16)->select();
        foreach ( $bookList as $key => $value) {
           $bookList[$key]['book_img'] = M('image')->where(array('book_id'=>$bookList[$key]['book_id'],'type'=>1,'is_delete'=>1))->getField('imageurl');
        }
        $this->page=$page->show();
        $this->assign('bookList',$bookList);
        $this->display();
    }
     /**
     * 获取栏目的值
     */
    public function category()
    {   
        header("Content-type: text/html;charset=utf-8");
        $one = M('category')->where(array('f_id'=>0,'is_show'=>1))->field("cg_id,is_show,name")->select();
        foreach ($one as $key => $value) {
            $one[$key]['two'] = M('category')->where(array('f_id'=>$one[$key]['cg_id'],'is_show'=>1))->field("cg_id,is_show,name")->select();
            foreach ($one[$key]['two'] as $k => $v) {
                $one[$key]['two'][$k]['three'] = M('category')->where(array('f_id'=>$one[$key]['two'][$k]['cg_id'],'is_show'=>1))->field("cg_id,is_show,name")->select();
            }
        }
        return $one;
    }


     /**
     * 筛选图书
     */
    public function booklists()
    {   
        $user_id = I('get.user_id',0);
        $type = I('get.type',3);//1普通的书 0二手书 2分享的书 
        $lowprice = I('get.lowprice',''); 
        $highprice = I('get.highprice','');
        $key=I('get.order');
        $key=safe_replace($key);//过滤
        $sort=I('get.sort');  
        $sort=safe_replace($sort);//过滤
        if($type < 3){
            $map['o.type'] = $type;
        }
        if($key){ 
           if(!is_numeric($key)){
                 $this->error('排序ID错误！');
           }
           switch ($key) { 
                case '0': //默认
                    $listsort="o.book_id"." ".$sort;
                break;
                case '1'://人气
                   $listsort="d.click_num"." ".$sort;
                  break;                
               case '2'://销量 
                   $listsort="d.sell_num"." ".$sort;
                  break;
                case '3'://价格                    
                   $listsort="o.price"." ".$sort;
                     break;              
           }    
       } 
       else {
         $order="o.book_id";$sort="asc";
         $listsort=$order." ".$sort;            
       }
        
        if(trim($lowprice) !== '' && trim($highprice) !== ''){
           $map['o.price']=array('between',array($lowprice,$highprice));
        }
        $where['o.type'] = $type;
        $count=M('book')->alias('o')
               ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
               ->join('LEFT JOIN kts_book_add as d ON d.book_number = o.book_number')
               ->where($where)->count();
        $page=new \Think\Page($count,5);
        // 通过匹配信息查询出相关书籍
        $bookList = M('book')->alias('o')
        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
        ->join('LEFT JOIN kts_book_add as d ON d.book_number = o.book_number')
        ->where($map)
        ->order($listsort)
        ->group('o.book_id')
        ->field('o.book_id,o.name,o.author,o.price,o.username as share_user,o.user_id,o.type')
        ->limit($skip,$take)->select();
        var_dump($bookList) ;
        die;
        $this->page=$page->show();
        $this->list=$bookList;
        $this->display();
    }
    

    public function db(){

        
    }
    
} 
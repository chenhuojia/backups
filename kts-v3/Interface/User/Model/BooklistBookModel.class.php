<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class BooklistBookModel extends Model{
   
    // 自动验证
    protected $_validate=array(     
      array('book_id','require','请选择图书'),
      array('book_id','','图书已经存在！',1,'unique',1),
      array('booklist_id','require','请选择书单',1),
     );
   // 自动完成
    protected $_auto=array(
        array('addtime','time',3,'function'),
        array('book_name','addBookName',3,'callback','book_name'),
        array('book_cover','addBookName',3,'callback','book_cover'),
    );
    
   protected function checkBook(){
        $book_id=I('post.book_id');
        return M()->execute('select book_id from kts_book where book_id ='.$book_id);      
    }
    
   protected function addBookName($data,$field){
       $return="";
       $book_id=I('post.book_id');
       $name=M()->query('select name,cover_img from kts_book where book_id ='.$book_id);
       switch($field){
           case "book_name":
               $return = $name[0]['name'];
               break;
           case "book_cover":
               $return = $name[0]['cover_img'];
               break;
       }
       return $return;
   }
   

   /**
    * 添加图书
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @param unknown $book_id
    * @return number[]|string[]***/
   public function addBookListBook($user_id,$booklist_id,$book_id){
       $total=1;
       if (empty($book_id) || empty($booklist_id)) return array('code'=>300,'msg'=>'参数有误');
       if (!is_array($book_id)){
           $book_id='('.$book_id.')';
       }else{
           $total=count($book_id);
           $book_id='('.implode(',', $book_id).')';
       }       
       $data=M()->query('select b.book_id,b.book_name,b.cover_img as book_cover,b.author as book_author,a.press as book_press,a.publish_price as book_price from kts_book_share as b left join kts_book_attr as a on b.book_id = a.book_id where b.is_show = 1 and b.book_id in '.$book_id);
       if (empty($data)) return array('code'=>300,'msg'=>'图书不存在');
       $is_have=M()->query('select book_id from kts_booklist_book where booklist_id = '.$booklist_id.' and book_id in '.$book_id);
       $deleteTotal=0;
       if (!empty($is_have)){
           $deleteTotal=count($is_have);
           $delete='(';
           foreach ($is_have as $k=>$v){
               $delete .=$v['book_id'].',';
           }           
           $delete=substr($delete,0,-1).')';
           M()->execute('delete from kts_booklist_book where book_id in '.$delete.' and booklist_id='.$booklist_id.' and user_id = '.$user_id);
       }
       $lastTotal=$deleteTotal?($total-$deleteTotal):$total;
       M()->execute('update kts_booklist set books_num = '."books_num+$lastTotal".' where id ='.$booklist_id.' and user_id = '.$user_id);
       $sql= 'insert into `kts_booklist_book` (`id`,`booklist_id`,`book_id`,`book_cover`,`book_name`,`book_author`,`book_price`,`book_press`,`user_id`,`addtime`) values ';
       foreach ($data as $k=>$v){
           $sql .='( null,'.$booklist_id.','.$v['book_id'].','."'{$v['book_cover']}'".','."'{$v['book_name']}'".','."'{$v['book_author']}'".','."'{$v['book_price']}'".','."'{$v['book_press']}'".','.$user_id.','.time().'),';
       }
       $sql=substr($sql,0,-1);
       return array('code'=>200,'msg'=>$sql);
   }
   

   /**
    * 移除图书
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @param unknown $book_id
    * @return number[]|string[]***/
   public function delBookListBook($user_id,$booklist_id,$book_id){
       if (empty($book_id) || empty($booklist_id)) return array('code'=>300,'msg'=>'参数有误');
       $count=count($book_id);
       if (!is_array($book_id)){
           $book_id='('.$book_id.')';
       }else{
           $book_id='('.implode(',', $book_id).')';
       }  
       $result=M()->execute('delete from kts_booklist_book where book_id in '.$book_id.' and booklist_id = '.$booklist_id);
       if ($result){
           M()->execute('update kts_booklist set books_num = '."books_num-$count".' where id ='.$booklist_id.' and user_id = '.$user_id);
       }
       return array('code'=>200,'msg'=>'success');
   }
   
   /**
    * 书单图书
    * @param unknown $booklist_id
    * @param unknown $skip
    * @param unknown $take
    * @return number[]|string[]
    * ***/
   public function bookListBooks($booklist_id,$skip,$take){
       $data=$this
       ->field('booklist_id,book_id,book_cover,book_name,book_author,book_price,book_press,user_id')
       ->where(array('booklist_id'=>$booklist_id,'is_show'=>1))
       ->limit($skip,$take)
       ->order('addtime desc')
       ->select();
       if ($data){
           foreach ($data as $k=>$v){
               $data[$k]['book_cover'] = C("QINIU_IMG_PATH").$v['book_cover'];
               $data[$k]['book_type']=2;
               $user= \User\Util\Util::GetUserAvatrAndNick($v['user_id']);
               $data[$k]['user_name']=$user['name'];
               $data[$k]['user_avatar']=$user['avatar'];
               
           }
           return array('code'=>200,'msg'=>$data);
       }
       return array('code'=>300,'msg'=>'书单暂时没有书');
   }
}

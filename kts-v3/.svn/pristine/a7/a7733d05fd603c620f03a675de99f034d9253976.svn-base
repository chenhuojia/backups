<?php
namespace User\Logic;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class BookLogic extends Model{
    
   //添加书架与上架书籍
    public function addBookshelfBook($user_id,$book_id,$type,$addr,$lon,$lat,$press='未知')
    {
        $shelfAddr = M('bookshelf')->field('shelf_id')->where(array('user_id'=>$user_id,'addr'=>$addr,'type'=>$type))->find();
        if(empty($shelfAddr)){
            $geohash=self::addGeohash($lon,$lat);
            $shelf_id = M('bookshelf')->add(array('user_id'=>$user_id,'longitude'=>$lon,'latitude'=>$lat,'geohash'=>$geohash,'addr'=>$addr,'type'=>$type,'addtime'=>time()));
            if(empty($shelf_id)) return 0;
        }else{
            $shelf_id = $shelfAddr['shelf_id'];
        }
        $book = M('book')->where(array('user_id'=>$user_id,'book_id'=>$book_id))->find();
        if(empty($book)) { return 0;}
        $shelfBook = M('bookshelf_book')->where(array('user_id'=>$user_id,'book_id'=>$book_id))->count(1);
        if(empty($shelfBook)){
             M('bookshelf_book')->add(array('user_id'=>$user_id,'book_id'=>$book_id,'shelf_id'=>$shelf_id,'type'=>$book['type'],'book_name'=>$book['name'],'author'=>$book['author'],'press'=>$press,'price'=>$book['price'],'cover_img'=>$book['cover_img'],'addtime'=>time()));
              M('bookshelf')->where(array('shelf_id'=>$shelf_id))->setInc('book_sums',1); // 书本数加1
             return 1;
        }else{
             return 0;
        }
        
    }
   


}

<?php
/**
 * 图书管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;
class BookActionController extends AdminController {

    /**
     * 修改图书功能
     * **/
    public function saveBook(){
        $book_id=I('post.book_id');
        $type=I('post.type');
        $status=I('post.status');
        $first=I('post.father_id',0);
        $second=I('post.class_id',0);
        $third=I('post.fid',0);
        $first_count=count($first);
        $second_count=count($second);
        $third_count=count($third);
        $where=array('book_id'=>$book_id);
        if ($status){
            M('book_recommend')->add($where);
        }
        if ($first_count == $second_count && $second_count == $third_count){
          M('book_tag')->where($where)->delete();
          foreach ($first as $k=>$v){
              M('book_tag')->add(array('book_id'=>$book_id,'first'=>$v,'second'=>$second[$k],'cg_id'=>$third[$k]));
          } 
        }
        $data=array(
            'publish_price'=>I('post.publish_price'),
            'format'=>I('post.format'),
            'edition'=>I('post.edition'),
            'impression'=>I('post.impression'),
            'page'=>I('post.page'),
            'words'=>I('post.words'),
            'applicable_age'=>I('post.age'),
            'language'=>I('post.language'),
            'binding'=>I('post.binding'),
            'paper'=>I('post.paper'),
            'author_desc'=>I('post.author_desc'),
            'introduce'=>I('post.introduce'),
            'author_area'=>I('post.author_area'),
        );
        M('book_attr')->where($where)->save($data);
        M('catalog')->where($where)->setField('catalog',I('post.catalog'));
        if($type==1){
            M('book')->where($where)->setField('price',I('post.price'));
            M('book_inventory')->where($where)->setField('inventory',I('post.total'));
            $this->success('修改成功',U('Admin/Book/bookList'));
        }elseif($type==0){
            M('book_old')->where($where)->save(array('description'=>I('post.description'),'shipping_price'=>I('post.shipping_price')));
            $this->success('修改成功',U('Admin/Book/oldBookList'));
        }elseif ($type==2){
            $this->success('修改成功',U('Admin/Book/shareList'));
        }

    }
    
    /**
     * 添加图书
     * **/
    
    public function AddBook(){
        if (!$cover=I('post.cover')) $this->error('请上传封面图片');
        $copyright=I('post.copyright');
        $type=I('post.type');
        $book_number=I('post.book_number');
        if(!$third=I('post.fid')) $this->error('请选择分类');
        if (!$first=I('post.father_id')) $this->error('请选择分类');
        if(!$second=I('post.class_id')) $this->error('请选择分类');
        if (!$press=I('post.press')) $this->error('请填写出版社');
        if (!$publish_time=I('post.publish_time')) $this->error('请填写出版时间');
        if (!$publish_price=I('post.publish_price')) $this->error('请填写定价');
        $book_name= I('post.book_name');
        if ($book_name =="" || $book_name ==null){
            $this->error('请填写书名');
        }
        $book_number=I('post.book_number');
        if ($book_number =="" || $book_number ==null){
            $this->error('请填写书号');
        }
        $author=I('post.author');
        if ($author=="" || $author ==null){
            $this->error('请填写作者');
        }
        $other=I('post.file');
        $others=0;
        if (!empty($other)){
            $others=implode(';',$other);
        }
        $book=array(
            'name'=>$book_name,
            'author'=>$author,
            'book_number'=>$book_number,
            'type'=>$type,
            'cover_img'=>$cover,
            'time'=>$_SERVER['REQUEST_TIME'],
            'price'=>$publish_price,
        );
        if ($type==2){
            if (!$user_id=I('post.user_id')) $this->error('请选择上传用户');
            $ret=M('book')->where(array('book_number'=>$book_number,'user_id'=>$user_id))->find();
            if ($ret){
                $this->error('该用户已经分享过这本书了');
            }
            $user=M('user')->field('*')->where(array('user_id'=>$user_id))->find();
            $book['user_id']=$user_id;
            $book_id=M('book')->add($book);
            if (!$book_id){
                $this->error('添加失败');
            }
            $share=array(
                'book_id'=>$book_id,
                'book_number'=>$book_number,
                'share_time'=>$_SERVER['REQUEST_TIME'],
                'user_id'=>$user_id,
                'user_name'=>$user['name'],
                'cover_img'=>$cover,
                'book_name'=>$book_name,
                'author'=>$author,
            );
            M('book_share')->add($share);
        }elseif($type==1){
            if (!$price=I('post.price')) $this->error('请填写出售价格');
            if (!$shop_id=I('post.shop')) $this->error('请选择店铺');
            if (!$total=I('post.total')) $this->error('请填写库存');
            $shop=M('shop')->where(array('shop_id'=>$shop_id))->find();
            $book['price']=$price;
            $book['user_id']=$user['user_id'];
            $book_id=M('book')->add($book);
            if (!$book_id){
                $this->error('添加失败');
            }
            M('book_inventory')->add(array('book_id'=>$book_id,'inventory'=>$total));
            M('shop')->where(array('shop_id'=>$shop_id))->setInc('book_num');
            M('shop_books')->add(array('book_id'=>$book_id,'book_number'=>$book_number,'shop_id'=>$shop_id,'shop_logo'=>$shop['shop_logo'],'addtime'=>$_SERVER['REQUEST_TIME']));
        }elseif($type==0){
            if (!$desc=I('post.description')) $this->error('请选择新旧程度');
            if (!$user_id=I('post.user_id')) $this->error('请选择上传用户');
            $shipping_price=I('post.shipping_price');
            $user=M('user')->where(array('user_id'=>$user_id))->find();
            $book['user_id']=$user_id;
            $book_id=M('book')->add($book);
            if (!$book_id){
                $this->error('添加失败');
            }
            $old_book=array(
                'book_id'=>$book_id,
                'book_number'=>$book_number,
                'description'=>$desc,
                'shipping_price'=>$shipping_price,
                'sell_time'=>$_SERVER['REQUEST_TIME'],
                'user_id'=>$user_id,
                'user_name'=>$user['name'],
                'author'=>$author,
                'book_name'=>$book_name,
                'cover_img'=>$cover,
            );
            M('book_old')->add($old_book);
        }
        $format=I('post.format');
        if(strpos($format, '开')){
            $format=$format;
        }else {
            $format=$format.'开';
        }
        $status=I('post.status');
        if ($status==2){
            M('book_recommend')->add(array('book_id'=>$book_id));
        }
        self::addCgId($book_id, $first, $second, $third);
        $attr=array(
            'book_id'=>$book_id,
            'type'=>$type,
            'press'=>$press,
            'publish_time'=>strtotime($publish_time),
            'impression'=>I('post.impression'),
            'page'=>I('post.page'),
            'edition'=>I('post.edition'),
            'words'=>I('post.words'),
            'paper'=>I('post.paper'),
            'format'=>$format,
            'binding'=>I('post.binding'),
            'language'=>I('post.language'),
            'introduce'=>I('post.introduce'),
            'author_desc'=>I('post.author_desc'),
            'author_area'=>I('post.author_area'),
            'applicable_age'=>I('post.age'),
            'copyright'=>$copyright,
            'other'=>$others,
            'publish_price'=>$publish_price,
        );
        $video=I('post.video',0);
        $title=I('post.title',0);
        if ($video && $title){
            $attr['video_title']=$title;
            $attr['video']=$video;
        }
        $address=I('post.address',0);
        $longitude=I('post.longitude',0);
        $latitude=I('post.latitude',0);
        if ($address && $longitude && $latitude){
            $attr['address']=$address;
            $attr['longitude']=$longitude;
            $attr['latitude']=$latitude;
        }
        if (count(array_filter($attr))>1){
            $at=M('book_attr')->add($attr);
        }
        $catalog=array(
            'book_id'=>$book_id,
            'book_number'=>$book_number,
            'description'=>I('post.catalog'),
        );
        if (count(array_filter($catalog))>2){
            $ca=M('catalog')->add($catalog);
        }
        
        if($type==1){
            $this->success('添加成功',U('Admin/Book/bookList'));
        }elseif($type==0){
            $this->success('添加成功',U('Admin/Book/oldBookList'));
        }elseif ($type==2){
            $this->success('添加成功',U('Admin/Book/shareList'));
        }
        
    }
    
    
    private function addCgId($book_id=1,$first=array(1),$second=array(2),$third=array(3)){
        $first_count=count($first);
        $second_count=count($second);
        $third_count=count($third);
        if ($first_count == $second_count && $second_count == $third_count){
            foreach ($first as $k=>$v){
                M('book_tag')->add(array('book_id'=>$book_id,'first'=>$v,'second'=>$second[$k],'cg_id'=>$third[$k]));
            }
        }
    }

    
} 
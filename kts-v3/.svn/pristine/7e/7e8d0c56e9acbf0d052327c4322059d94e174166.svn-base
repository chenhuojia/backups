<?php
/**
 * 视频管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;

class AjaxActionBookController extends AdminController {
    
    /**
     * 新书删除
     * **/
    public function bookDel(){
        $book_id=I('book_id');
        $type=M('book')->find($book_id);
        if ($type['type']==2){
             
        }
        $data=M('book')->where(array('book_id'=>$book_id))->save(array('isdelete'=>1));
        if($data){
            $this->clean_all();
            $this->success('删除成功',U('Admin/Book/bookList'));
        }else {
            $this->error('删除失败');
        }
    }
    
    /**
     * 增加库存
     * **/
    public function bookInc(){
        $book_id=I('post.book_id');
        $type=I('post.book_type');
        $data=M('book_inventory')->where(array('book_id'=>$book_id))->setInc('inventory');
        if ($data){
            //$this->clean_all();
            $ret=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
            if ($ret){
                $this->ajaxReturn($ret);
            }
        }
    }

    /**
     * 减少库存
     * **/
    public function bookReduce(){
        $book_id=I('post.book_id');
        $type=I('post.book_type');
        $data=M('book_inventory')->where(array('book_id'=>$book_id))->setDec('inventory');
        if ($data){
            //$this->clean_all();
            $ret=M('book_inventory')->where(array('book_id'=>$book_id))->getField('inventory');
            if ($ret){
                $this->ajaxReturn($ret);
            }
        }
    }
    /**
     * 修改库存
     * **/
    public function bookTotal(){
        $book_id=I('post.book_id');
        $type=I('post.book_type');
        $value=I('post.value');
        $data=M('book_inventory')->where(array('book_id'=>$book_id))->save(array('inventory'=>$value));
        if ($data){
            //$this->clean_all();
            $this->ajaxReturn("success");
        }
    }
    /**
     * 修改出售价格
     * **/
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
    /**
     * 更改推荐
     * **/
    public function changeState(){
        $book_id=I('get.book_id');
        $data=M('book_recommend')->where(array('book_id'=>$book_id))->find();
        if ($data){
            $result=M('book_recommend')->where(array('book_id'=>$book_id))->delete();
        }else{
            $result=M('book_recommend')->add(array('book_id'=>$book_id));
        }
        if ($result){
            $this->clean_all();
            $this->ajaxReturn('true');
        }else{
            $this->ajaxReturn('false');
        }
    }
    /**
     * 通过书号获取图书
     * **/
    public function getBookApi(){
        $book_number=trim(I('post.book_number'));
        $data=$this->httpsRequest("https://api.douban.com/v2/book/isbn/:".$book_number);
        $data=json_decode($data);
        if ($data->code==6000){
            $data=$this->httpsRequest("http://feedback.api.juhe.cn/ISBN?sub=".$book_number."&key=235e71165bff8a0fa48de6fa79ae636f");
            $data=json_decode($data);
            if ($data->result->author!=""){
                $author=$data->result->author;
                $area=preg_split("/[(\s)]/", $author);
                $area[0]=trim($area[0],'[]');
                $price=str_replace('元', '', $data->result->price);
                $data=array(
                    'pubdate'=>$data->result->pubdate,
                    'binding'=>$data->result->binding,
                    'pages'=>$data->result->pages,
                    'images'=>$data->result->images_large,
                    'publisher'=>$data->result->publisher,
                    'introduce'=>$data->result->summary,
                    'book_name'=>$data->result->title,
                    'price'=>$price,
                );
                if (count($area)==1){
                    $data['author']=$area[0];
                    $data['author_area']="中国";
                }else{
                    $data['author']=$area[1];
                    $data['author_area']=$area[0];
                }
            }else {
                $data=NUll;
            }
             
        }else{
            $author=$data->author;
            $area=preg_split("/[(\s)]/", $author[0]);
            $price=str_replace('元', '', $data->price);
            $area[0]=trim($area[0],'[]');
            $data=array(
                'pubdate'=>$data->pubdate,
                'binding'=>$data->binding,
                'pages'=>$data->pages,
                'images'=>$data->images,
                'publisher'=>$data->publisher,
                'introduce'=>$data->summary,
                'book_name'=>$data->title,
                'price'=>$price,
                'catalog'=>$data->catalog,
                'author_desc'=>$data->author_intro,
            );
            if (count($area)==1){
                $data['author']=$area[0];
                $data['author_area']="中国";
            }else{
                $data['author']=$area[1];
                $data['author_area']=$area[0];
            }
        }
        if ($data){
            $this->ajaxReturn($data);
        }else {
            $this->ajaxReturn(false);
        } 
    }
    
    /**
     * 父级联返回的栏目类型
     */
    public function childTypeList()
    {
        $father_id = I('post.father_id');
        $where['f_id'] = $father_id;
        $result = M('category')->where($where)->field('cg_id,name,imageurl,f_id')->select();
        $this->ajaxReturn($result,"JSON");
    }
} 
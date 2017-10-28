<?php
namespace User\Controller;

use Common\Controller\ApiController;
use User\Util\Util;
class BookListController extends ApiController
{   
    /**
     * 我建立的书单
     * ***/
    public function myBookList(){
        $user_id=session('user_id')?session('user_id'):I('post.user_id',0);
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=D('booklist')->myBookList($user_id,$skip,$take);       
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);         
        $this->myApiPrint(300,$data['msg']);
    }
    
    /**
     * 新建书单 
     * ***/
    public function addBookList(){
        $booklist=D('booklist');
        if (!$booklist->create()){
            $this->myApiPrint(300,$booklist->getError());
        }else{
           $booklist->add();
           $this->myApiPrint(200,'success');
        } 
    }
    
    /**
     * 编辑书单
     * ***/
    public function editBookList(){
        $user_id=session('user_id');
        $booklist_id=I('post.booklist_id',0);
        $type=I('post.type',1);
        $booklist=D('booklist');
        $data=D('booklist')->editBooklist($user_id,$booklist_id,$type);       
        $this->myApiPrint($data['code'],$data['msg']);         
    }
    
    /**
     * 删除书单
     * ***/
    public function delBookList(){
        $user_id=session('user_id');
        $booklist_id=I('post.id');
        if ($user_id && $booklist_id){
            $result=M('booklist')->where(array('id'=>$booklist_id,'user_id'=>$user_id))->delete();
            if ($result) $this->myApiPrint(200,'success');
        }
        $this->myApiPrint(300,'fail');
    }
    
    /**
     * 书单介绍
     * ***/
    public function bookListDesc(){
        $user_id=session('user_id')?session('user_id'):0;
        $booklist_id=I('post.booklist_id');
        $data=D('booklist')->bookListDesc($user_id,$booklist_id);
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);         
        $this->myApiPrint(300,$data['msg']);
    }    
    
    /**
     * 书单详情
     * ***/
    public function bookListDet(){
        $user_id=session('user_id')?session('user_id'):0;
        $booklist_id=I('post.booklist_id');
        $data=D('booklist')->bookListDet($booklist_id,$user_id);
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);         
        $this->myApiPrint(300,$data['msg']);
    }    

    /**
     * 书单图书
     * ***/
    public function bookListBooks(){
        $booklist_id=I('post.booklist_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $booklist=D('booklist_book');
        $data=$booklist->bookListBooks($booklist_id,$skip,$take);
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);         
        $this->myApiPrint(300,$data['msg']);
    }    
    
    /**
     * 添加书单评论
     * ***/
    public function addBookListDiscuss(){
        $booklist=D('booklist_discuss');
        $booklist_id=I('post.booklist_id');
        if (!$booklist->create()){
            $this->myApiPrint(300,$booklist->getError());
        }else{
           $booklist->add();
           M('booklist')->where(array('id'=>$booklist_id))->setInc('discuss_num');
           $this->myApiPrint(200,'success');
        } 
    }
    
    /**
     * 回复书单评论
     * ***/
    public function addBookListDiscussReply(){
        $booklist=D('booklist_discuss_reply');
        $booklist_id=I('post.discuss_id');
        $f_id=I('post.f_id',0);
        if (!$booklist->create()){
            $this->myApiPrint(300,$booklist->getError());
        }else{
            $booklist->add();
            if ($f_id){
                M('booklist_discuss_reply')->where(array('id'=>$f_id))->setInc('discuss_num');
            }else{
                M('booklist_discuss')->where(array('booklist_id'=>$booklist_id))->setInc('discuss_num');
            } 
            $this->myApiPrint(200,'success');
        }
    }
    
    /**
     * 书单评论点赞
     * ***/
    public function addBookListDiscussApprove(){
        $booklist=D('booklist_discuss_approve');
        $booklist_id=I('post.discuss_id');
        if (!$booklist->create()){
            $this->myApiPrint(300,$booklist->getError());
        }else{
            if ($booklist->checkApprove(session('user_id'),$booklist_id)){
                $this->myApiPrint(300,'你已经点赞过了');
            } 
            $booklist->add();
            M('booklist_discuss')->where(array('id'=>$booklist_id))->setInc('approve_num');
            $this->myApiPrint(200,'success');
        }
    }
    
    /**
     * 书单回复评论点赞
     * ***/
    public function addBookListDiscussReylyApprove(){
        $booklist=D('booklist_discuss_reply_approve');
        $booklist_id=I('post.reply_id');
        if (!$booklist->create()){
            $this->myApiPrint(300,$booklist->getError());
        }else{
            if ($booklist->checkApprove(session('user_id'),$booklist_id)){
                $this->myApiPrint(300,'你已经点赞过了');
            }
            $booklist->add();
            M('booklist_discuss_reply')->where(array('id'=>$booklist_id))->setInc('approve_num');
            $this->myApiPrint(200,'success');
        }
    }
    
    /**
     * 书单评论
     * ***/
    public function bookListDiscuss(){
        $user_id=session('user_id')?session('user_id'):0;
        $booklist_id=I('post.booklist_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=D('booklist_discuss')->bookListDiscuss($booklist_id,$skip,$take,$user_id);
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);
        $this->myApiPrint(300,$data['msg']);
    }
        
    /**
     * 回复书单评论列表
     * ***/
    public function bookListDiscussReply(){
        //$user_id=session('user_id');
        $booklist_id=I('post.discuss_id');
        $skip=I('post.skip',0);
        $take=I('post.take',10);
        $data=D('booklist_discuss_reply')->bookListDiscussReply($booklist_id,$skip,$take);
        if ($data['code']==200) $this->myApiPrint(200,'success',$data['msg']);
        $this->myApiPrint(300,$data['msg']);
    }
    
    /**
     * 添加图书
     * ***/
    public function addBookListBook(){
        $user_id=session('user_id');
        $booklist_id=I('post.booklist_id',0);
        $book_id=I('post.book_id');
        $book_id=json_decode($book_id,true);
        if (empty($book_id)) $this->myApiPrint(300,'请选择图书');
        sort($book_id);
        $booklist=D('booklist_book');
        $data=$booklist->addBookListBook($user_id,$booklist_id,$book_id);
        if ($data['code']==300) $this->myApiPrint(300,$data['msg']);
        M()->execute($data['msg']);
        $this->myApiPrint(200,'success');
    }
    
    /**
     * 移除图书
     * ***/
    public function delBookListBook(){
        $user_id=session('user_id');
        $booklist_id=I('post.booklist_id',0);
        $book_id=$_POST['book_id'];
        $book_id=json_decode($book_id,true);
        if (empty($book_id)) $this->myApiPrint(300,'请选择图书');
        sort($book_id);
        $booklist=D('booklist_book');
        $data=$booklist->delBookListBook($user_id,$booklist_id,$book_id);
        if ($data['code']==300) $this->myApiPrint(300,$data['msg']);
        $this->myApiPrint(200,'success');
    }
    
    /**
     * 收藏||取消收藏书单
     * ***/
    public function bookListCollect(){
        $user_id=session('user_id');
        $booklist_id=I('post.booklist_id',0);        
        $booklist=D('booklist');
        $data=$booklist->bookListCollect($user_id,$booklist_id);
        if ($data['code']==300) $this->myApiPrint(300,$data['msg']);
        $this->myApiPrint(200,'success');
    }
    
    /**
     * 书单分享
     * ***/
    public function bookListShare(){
        $user_id=session('user_id');
        $booklist_id=I('post.booklist_id',0);
        $shareto=I('post.shareto','微信');
        $booklist=D('booklist');
        $data=$booklist->bookListShare($user_id,$booklist_id,$shareto);
        if ($data['code']==300) $this->myApiPrint(300,$data['msg']);
        $this->myApiPrint(200,'success');
    }
}


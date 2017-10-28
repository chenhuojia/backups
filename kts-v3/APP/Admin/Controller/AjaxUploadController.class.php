<?php
/**
 * 视频管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;

class AjaxUploadController extends AdminController {
    
    private function image_return($url){
        $data=array(
            'image_url'=>C('QINIU').$url,
            'val'=>$url,
        );
        return $data;
    }
    
    /**
     * 新增图片
     * **/
    public function image_upload(){
       $type=I('get.type',0);
       if ($type=='book'){
           $data=self::uploadF($_FILES['img']['tmp_name'],'book_');
       }elseif($type=='video'){
           $data=self::uploadvideo($_FILES['imge']['tmp_name'],'book_');
       }elseif($type=='change'){
           $data=self::uploadF($_FILES['changeimage']['tmp_name'],'book_');
       }else{
           $data=self::uploadF($_FILES['img']['tmp_name'],'qiushu_');
       }
       if ($data){
           $this->ajaxReturn(self::image_return($data));
       }
       $this->ajaxReturn(0);
    }
    
    /**
     * 更改图片
     * **/
    public function changeBookImage(){
        $type=I('get.type',0);
        $book_id=I('get.book_id',0);
        $oldurl=I('get.old');        
        $url=self::uploadF($_FILES['changeimage']['tmp_name'],'book_');
        if ($type=='cover'){
            $data=M('book')->where(array('book_id'=>$book_id))->setField('cover_img',$url);
        }elseif ($type=='copyright'){
            $data=M('book_attr')->where(array('book_id'=>$book_id))->setField('copyright',$url);
        }elseif ($type=='other'){
            $ret=M('book_attr')->where(array('book_id'=>$book_id))->getField('other');
            $new=str_replace($oldurl,$url,$ret);          
            $data=M('book_attr')->where(array('book_id'=>$book_id))->setField('other',$new);
        }
       if ($data){
           $this->ajaxReturn(self::image_return($url));
       }
       $this->ajaxReturn(0);
    }

    /**
     * 新增图片
     * **/
    public function add_image(){
        $book_id=I('get.book_id',0);
        $url=self::uploadF($_FILES['imgs']['tmp_name'],'book_');
        $ret=M('book_attr')->where(array('book_id'=>$book_id))->getField('other');
        $new=$ret.";".$url;
        $data=M('book_attr')->where(array('book_id'=>$book_id))->setField('other',$new);
        if ($data){
            $this->ajaxReturn(self::image_return($url));
        }
        $this->ajaxReturn(0);
    }
    
    /**
     * 删除图片
     * **/
    public function DelBookImage(){
        $book_id=I('post.book_id',0);
        $oldurl=I('post.old');
        $ret=M('book_attr')->where(array('book_id'=>$book_id))->getField('other');
        $img=explode(';',$ret);
        foreach ($img as $k=>$v){
            if ($v==$oldurl){
                unset($img[$k]);
            }
        }
        $new=implode(';',$img);
        $data=M('book_attr')->where(array('book_id'=>$book_id))->setField('other',$new);
        if ($data){
            $this->ajaxReturn(1);
        }
        $this->ajaxReturn(0);
        
    }
    
    
    /**
     * 上传文件
     */
    public function upload(){
        import("@.ORG.UploadFile");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     C('IMG_SITE_PREFIX').'Public/Upload/category/'; // 设置附件上传根目录
        $upload->savePath  =     ""; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if($info) {// 上传错误提示错误信息
            return $upload->rootPath.$info['imageurl']['savepath'].$info['imageurl']['savename'];
        }
    }
    
} 
<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;
use think\Session;
use Think\Upload;


class AjaxController extends Controller {
    
 
    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      'Public/Upload/blog'; // 设置附件上传根目录
        $upload->savePath  =      '/images/'; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->uploadOne($_FILES['img']);
        if(!$info) {
            $this->ajaxReturn($upload->getError(),';;',100);
        }else{
            $this->ajaxReturn($upload->rootPath.$info['savepath'].$info['savename']);
        }
    }

    /**
     * 隐藏博客文章
     * **/
    public function stopArticle(){
        $art_id=I('post.art_id');
        if (empty($art_id)) $this->ajaxReturn(0);
        $result=M('article')->where(array('art_id'=>$art_id))->setField('is_show',0);
        $this->ajaxReturn($result);
    }
    
    /**
     * 显示博客文章
     * **/
    public function startArticle(){
        $art_id=I('post.art_id');
        if (empty($art_id)) $this->ajaxReturn(0);
        $result=M('article')->where(array('art_id'=>$art_id))->setField('is_show',1);
        $this->ajaxReturn($result);
    }
    
    /**
     * 删除博客文章
     * **/
    public function delArticle(){
        $art_id=I('post.art_id');
        if (empty($art_id)) $this->ajaxReturn(0);
        $result=M('article')->delete($art_id);
        $this->ajaxReturn($result);
    }
    
    /**
     * 批量删除博客文章
     * **/
    public function delAllArtile(){
        $data=$_POST['data'];
        $data=json_decode($data,true);
        if (empty($data)) $this->ajaxReturn(0);        
        $data=self::quickSort($data);
        $art_id='('.join(',',$data).')';       
        $result=M()->execute('delete from bk_article  where art_id in '.$art_id);
        $this->ajaxReturn($result);
    }
    
  /**
   * 快速排序
   * @param unknown $arr
   * @return boolean|unknown***/ 
  private function quickSort($arr) {
         if(!is_array($arr)) return false;
        //递归出口:数组长度为1，直接返回数组
        $length=count($arr);
        if($length<=1) return $arr;
        //数组元素有多个,则定义两个空数组
        $left=$right=array();
        //使用for循环进行遍历，把第一个元素当做比较的对象
        for($i=1;$i<$length;$i++)
        {
            //判断当前元素的大小
            if($arr[$i]<$arr[0]){
                $left[]=$arr[$i];
            }else{
                $right[]=$arr[$i];
            }
        }
        
        //递归调用
        $left=$this->quickSort($left);        
        $right=$this->quickSort($right);
        //将所有的结果合并
        return array_merge($left,array($arr[0]),$right);
    }
    
    /**
     * 添加分类
     * **/
    public function categoryAdd(){
        $category=D('category');
        if ($category->create()){
            $this->ajaxReturn($category->add());
        }else{
            $this->ajaxReturn($category->getError());
        }
    }
    
    /**
     * 删除单个分类
     * **/
    public function delCategory(){
        $art_id=I('post.cate_id');      
        if (empty($art_id)) $this->ajaxReturn($art_id);
        $result=M('category')->delete($art_id);
        $this->ajaxReturn($result);
    }
    
    /**
     * 批量删除分类
     * **/
    public function delAllCategory(){
        $data=$_POST['data'];       
        $data=json_decode($data,true);
        if (empty($data)) $this->ajaxReturn(0);
        $data=self::quickSort($data);
        $art_id='('.join(',',$data).')';
        $result=M()->execute('delete from bk_category  where cate_id in '.$art_id);
        $this->ajaxReturn($result);
    }
    
    /**
     * 退出
     * ***/
    public function letOut(){
        session('admin',null);
        $this->success('退出成功');
    }
}
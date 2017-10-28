<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;



class CategoryController extends CommonController {
   
    public function articleList(){         
        $category=D('category','Logic');
        $data=$category->getList();
        $this->page=$data['page']->show();
        $this->list=$data['data'];
        $this->display('article-class');
       
    }



    public function categoryEdit(){
        $cate_id=I('get.cate_id');
        $category=D('category');
        if (IS_POST){            
            if ($category->create()){
                $category->where('cate_id = '.$cate_id)->save();
                $this->redirect('Category/articleList',array(),2,'修改成功');
                
            }else{
                $this->error($category->getError());
            }
        }        
        $data=$category->find($cate_id);
        $list=M()->query('select cate_id,name,english from bk_category where is_show=1');
        $this->list=$list;
        print_r($list);
        $this->data=$data;
        $this->display('article-class-edit');   
    }
    

}
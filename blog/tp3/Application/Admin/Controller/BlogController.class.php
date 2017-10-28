<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Common\Controller\CommonController;
use think\Session;


class BlogController extends CommonController {
   

    public function article(){
        $search=I('get.searchName');
        $datamin=I('get.datamin');
        $datamax=I('get.datamax');        
        $articleList=D('article','Logic');
        if (empty($search) && empty($datamin) && empty($datamax)){
            $data=$articleList->getList();
        }else{
            $data=$articleList->getSearchList($search,$datamin,$datamax);
        } 
        $this->page=$data['page']->show();
        $this->list=$data['data'];
        $this->display();
    }
    
    
    public function add(){        
        if (IS_POST){
          $artic=D('article');
          if (!$artic->create()){ // 创建数据对象
              // 如果创建失败 表示验证没有通过 输出错误提示信息
              $this->error($artic->getError());
          }else{
              // 验证通过 写入新增数据
              $art_id=$artic->add();
              if ($cover=I('post.cover')){
                  M('article_images')->add(array('art_id'=>$art_id,'image_url'=>$cover));
              }
              if ($art_id){$this->success('success');}
              
          }
        }       
        $this->category=M('category')->select();
        $this->display('form_basic');    
    }
    
    public function edit(){
        $art_id=I('get.art_id',0);           
        $data=M('article')->find($art_id);
        $this->data=$data;
        $this->category=M('category')->select();
        $this->display('edit');
    }
    
    public function edit2(){
        $art_id=I('get.art_id',0);
        if (IS_POST){
            $artic=D('article');
            if (!$artic->create()){ // 创建数据对象
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($artic->getError());
            }else{
                // 验证通过 写入新增数据
                $art=$artic->where(array('art_id'=>$art_id))->save();
                if ($cover=I('post.cover')){
                    M('article_images')->add(array('art_id'=>$art_id,'image_url'=>$cover));
                }
                if ($art){$this->success('success');}
                $this->error('fail');
            }
        }
    }
}
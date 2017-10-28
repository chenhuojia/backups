<?php
namespace Admin\Logic;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
use Think\Model;
use Think\Page;
class ArticleLogic  extends Model{
    
    public static function getList($list=10){
        $article=M('article');
        $count=$article->count();
        $Page=new Page($count,$list);
        $data=$article->alias('a')
        ->join('left join bk_category c on a.cate_id=c.cate_id')
        ->field('a.*,c.name,c.english')
        ->limit($Page->firstRow,$Page->listRows)
        ->select();
        return array('page'=>$Page,'data'=>$data);     
    }
    
    public static function getSearchList($search,$datamin,$datamax,$list=10){
        if (!empty($search)){
            $where='a.title like '."'%$search%'".' or c.name like '."'%$search%' ".' or c.english like '."'%$search%' ";
        }else{
            $where = '';
        }
        if (!empty($datamin) && !empty($datamax)){
            $datamin=strtotime($datamin);
            $datamax=strtotime($datamax);
            if ($where){$where .=' and ';}
            $where .= '  a.addtime > '.$datamin.' and a.addtime < '.$datamax;
        }elseif (!empty($datamin) && empty($datamax)){
            $datamin=strtotime($datamin);
            if ($where){$where .=' and ';}
            $where .= '   a.addtime > '.$datamin;
        }elseif (!empty($datamax) && empty($datamin)){
            $datamax=strtotime($datamax);
            if ($where){$where .=' and ';}
            $where .= '  a.addtime < '.$datamax;
        } 
        $article=M('article');
        $count=$data=$article->alias('a')
        ->join('left join bk_category c on a.cate_id=c.cate_id')
        ->where($where)
        ->count();
        $Page=new Page($count,$list);
        $data=$article->alias('a')
        ->join('left join bk_category c on a.cate_id=c.cate_id')
        ->where($where)
        ->field('a.*,c.name,c.english')
        ->limit($Page->firstRow,$Page->listRows)
        ->select();
        return array('page'=>$Page,'data'=>$data);
    }
}
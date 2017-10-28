<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016-7-22
 * Time: 14:36
 */
namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\MinsuBundle\Entity\Msg;
use Acme\MinsuBundle\Entity\ShopApply;
use Acme\MinsuBundle\Entity\Shop;
use Acme\MinsuBundle\Common\CommonController;

class ShopController extends CommonController
{   
    private $url='http://oezcwek9o.bkt.clouddn.com/';
    
     
    /**
     * @Route("/shopApplyList",name="_shop_apply_list")
     * @Template("AcmeMinsuBundle:Shop:shopApplyList.html.twig")
     */
    public function shopApplyList(){
    
        //$shop_apply=new ShopApply();
        if($_POST){
            $is_checked=$_POST['is_checked'];
           if ($is_checked==3){
                $data=$this->conn()->createQueryBuilder()
                    ->select("*")
                    ->from("msk_shop_apply")
                    ->execute()
                    ->fetchAll();
            }else{
                $where='is_checked='.$is_checked;
                $data=$this->conn()->createQueryBuilder()
                    ->select("*")
                    ->from("msk_shop_apply")
                    ->where($where)
                    ->execute()
                    ->fetchAll();
            }  
        }else{
            $data=$this->conn()->createQueryBuilder()
                ->select("*")
                ->from("msk_shop_apply")
                ->execute()
                ->fetchAll();
        } 
        
        return array('shop'=>$data);
    }
    
    
    
    /**
     * @Route("/agreeShopApply",name="_agree_shop_apply")
     */
    public function agreeShopApply(){
      $id=$_POST['id'];
      $is_checked=$_POST['state'];
      $reason=$_POST['rtest'];
      if ($reason!=""){
          $msg=$reason;
      }else{
          $msg='同意';
      }
      $shop=$this->conn()->createQueryBuilder()
      ->select('*')
      ->from('msk_shop_apply')
      ->where("id=$id")
      ->execute()->fetch();
      if ($shop){
          $this->conn()->createQueryBuilder()
              ->update('msk_shop_apply')
              ->set('reply_time',time())
              ->set('is_checked',$is_checked)
              ->set('reply',"'$msg'")
              ->where("id=$id")
              ->execute();
          $arr=array(
              'user_id'         =>  $shop['user_id'],
              'shop_name'       =>  $shop['shop_name'],
              'shop_logo'       =>  $shop['shop_logo'],
              'shop_address'    =>  $shop['shop_address'],
              'create_time'     =>  time(),
              'shop_apply_id'   =>  $id,
          );
         $data=$this->conn()->insert('msk_shop',$arr);
      }
      
      return new JsonResponse($data);  
    }
    
    
    /**
     * @Route("/shopApplyDetail",name="shopApplyDetail_")
     * @Template("AcmeMinsuBundle:Shop:ShopApplyDetail.html.twig")
     */  
    public function shopApplyDetail(){
        $apply_id=isset($_GET['apply_id'])?$_GET['apply_id']:0;
        $shop_id=isset($_GET['shop_id'])?$_GET['shop_id']:0;
        if ($shop_id){
            $data=$this->conn()->createQueryBuilder()
                ->select("s.*,m.nickname")
                ->from('msk_shop','p')
                ->leftJoin('p','msk_shop_apply','s','p.shop_apply_id=s.id')
                ->leftJoin('p','msk_member_info','m','p.user_id=m.member_id')
                ->where('p.shop_id='.$shop_id)
                ->execute()
                ->fetch();
        }elseif ($apply_id){
            $data=$this->conn()->createQueryBuilder()
            ->select("p.*,m.nickname")
            ->from('msk_shop_apply','p')
            ->leftJoin('p','msk_member_info','m','p.user_id=m.member_id')
            ->where('p.id='.$apply_id)
            ->execute()
            ->fetch();
        }
        $data['shop_logo']=$this->url.$data['shop_logo'];
        $data['business_license']=$this->url.$data['business_license'];
        $data['id_card1']=$this->url.$data['id_card1'];
        $data['id_card2']=$this->url.$data['id_card2'];
        $data['id_card3']=$this->url.$data['id_card3'];
        return array('apply'=>$data);
    }
    
    /**
     * @Route("/shopDetail",name="shopDetail_")
     * @Template("AcmeMinsuBundle:Shop:ShopDetail.html.twig")
     */
    public function shopDetail(){
        $apply_id=$_GET['shop_id'];
        $data=$this->conn()->createQueryBuilder()
        ->select("p.*,m.nickname")
        ->from('msk_shop_apply','p')
        ->leftJoin('p','msk_member_info','m','p.user_id=m.member_id')
        ->where('p.id='.$apply_id)
        ->execute()
        ->fetch();
        print_r($data);
        return array('apply'=>$data);
    }
    
    
    /**
     * @Route("/shopList",name="shopList_")
     * @Template("AcmeMinsuBundle:Shop:ShopList.html.twig")
     */
    public function shopList(){
        $page  = isset($_GET['page'])?$_GET['page']:0;        
        $where='';
        if ($_POST){
            $searchText=$_POST['searchText'];
            $searchType=$_POST['searchType'];
            if ($searchType!=999){
                if ($searchType=='nickname'){
                    $where=" and m.nickname like '%$searchText%'";
                }else $where=" and p.$searchType like '%$searchText%'";
            } 
        }
        if (empty($page)) {
            $page = 1;
        }
        $pageSize = $this->getParameter('pagesize');
        $totalNumRes= $this->conn()->createQueryBuilder()
            ->select('count(p.shop_id) as total')
            ->from('msk_shop','p')
            ->where('p.is_show=1'.$where)
            ->execute()->fetch();
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
        $prePage = $page - 1;
        $nextPage = $page + 1;
        $data=$this->conn()->createQueryBuilder()
               ->select('p.*,m.nickname')
               ->from('msk_shop','p')
               ->leftJoin('p','msk_member_info','m','p.user_id=m.member_id')
               ->orderBy('p.createtime','desc')
               ->where('p.is_show=1'.$where)
               ->setFirstResult($startPage)
               ->setMaxResults($pageSize)
               ->execute()
               ->fetchAll();
        foreach ($data as $k=>$v){
            $data[$k]['shop_logo']=$this->url.$v['shop_logo'];
        }
        return array(
            'shop'=>$data,
            'gg'=>self::pageHtml($totalPage,'shopList',$page,$prePage,$nextPage),
        );
    }
    
    /**
     * @Route("/delShop",name="delShop_") 
     **/
    public function delShop(){
        $shop_id=$_POST['shop_id'];
        $em = $this->getDoctrine()->getManager();
        $shop = $em->getRepository('AcmeMinsuBundle:Shop')
            ->find($shop_id);
        if ($shop){
           $shop->setIs_show(0);
           $em->flush();
           return new JsonResponse(1);
        } 
    }
    
    
    /**
     * @Route("/shopGoods",name="shopGoods_")
     * @Template("AcmeMinsuBundle:Shop:GoodsList.html.twig")
     **/
    public function shopGoods(){
        $page  = isset($_GET['page'])?$_GET['page']:0;
        $shop_id  = isset($_GET['shop_id'])?$_GET['shop_id']:0;
        if (empty($page)) {
            $page = 1;
        }
        $where=array();
        $find='s.shop_id='.$shop_id;
        $pageSize = $this->getParameter('pagesize');
        if ($_POST){  
            $cat_id=isset($_POST['cat_id'])?$_POST['cat_id']:0;
            $is_on_sale=isset($_POST['is_on_sale'])?$_POST['is_on_sale']:0;
            $is_new=isset($_POST['is_new'])?$_POST['is_new']:0;
            $keyword=isset($_POST['keyword'])?$_POST['keyword']:0;
            if ($cat_id){
                $where['c.id']=$cat_id;
            }if ($is_on_sale){
                if ($is_on_sale==1) $where['p.is_on_sale']=1;
                if ($is_on_sale==2) $where['p.is_on_sale']=0;
            }
            if ($is_new){
                if ($is_new==1) $where['p.is_new']=1;
                if ($is_new==2) $where['p.is_recommend']=1;
            }if ($keyword){
                $where['p.keywords']="like '%$keyword%'";
            }
            
            if (!empty($where)){
                
                foreach ($where as $k=>$v){
                  if ($k=='p.keywords'){
                      $find .=' and '.$k.' '.$v;  
                  }else{
                      $find .=' and '.$k.'='.$v;
                  }  
                }
            }
            if ($find){
                $totalNumRes=$this->conn()->createQueryBuilder()
                ->select('count(p.goods_id) as total')
                ->from('msk_shop_goods', 's' )
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id')
                ->where($find)
                ->groupBy('s.goods_id')
                ->execute()->fetch();
                $totalNum = $totalNumRes['total'];
                $totalPage = ceil($totalNum / $pageSize);
                if ($totalPage != 0 && $page > $totalPage) {
                    $page = $totalPage;
                }
                $startPage = ($page - 1) * $pageSize;
                 
                $prePage = $page - 1;
                $nextPage = $page + 1;
                $data=$this->conn()->createQueryBuilder()
                    ->select('p.*,c.*')
                    ->from('msk_shop_goods','s')
                    ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                    ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id') 
                    ->where($find)
                    ->groupBy('s.goods_id')
                    ->setFirstResult($startPage)
                    ->setMaxResults($pageSize)
                    ->execute()
                    ->fetchAll();
            }else{
                $totalNumRes=$this->conn()->createQueryBuilder()
                    ->select('count(p.goods_id) as total')
                    ->from('msk_shop_goods','s')
                    ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                    ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id')
                    ->groupBy('s.goods_id')
                    ->execute()->fetch();
                $totalNum = $totalNumRes['total'];
                $totalPage = ceil($totalNum / $pageSize);
                if ($totalPage != 0 && $page > $totalPage) {
                    $page = $totalPage;
                }
                $startPage = ($page - 1) * $pageSize;
                 
                $prePage = $page - 1;
                $nextPage = $page + 1;
                $data=$this->conn()->createQueryBuilder()
                    ->select('p.*,c.*')
                    ->from('msk_shop_goods','s')
                    ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                    ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id')
                    ->setFirstResult($startPage)
                    ->setMaxResults($pageSize)
                    ->execute()
                    ->fetchAll();
            }             
        }else{
            $totalNumRes=$this->conn()->createQueryBuilder()
                ->select('count(p.goods_id) as total')
                ->from('msk_shop_goods','s')
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->where($find)
                ->groupBy('s.goods_id')
                ->execute()->fetch();
            $totalNum = $totalNumRes['total'];
            $totalPage = ceil($totalNum / $pageSize);
            if ($totalPage != 0 && $page > $totalPage) {
                $page = $totalPage;
            }
            $startPage = ($page - 1) * $pageSize;
             
            $prePage = $page - 1;
            $nextPage = $page + 1;
            $data=$this->conn()->createQueryBuilder()
                ->select('p.*,c.*')
                ->from('msk_shop_goods','s')
                ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id') 
                ->where($find)
                ->groupBy('s.goods_id')
                ->setFirstResult($startPage)
                ->setMaxResults($pageSize)
                ->execute()
                ->fetchAll();
        } 
        $cate=$this->conn()->createQueryBuilder()
              ->select('p.*')
              ->from('msk_shop_category','p') 
              ->where('shop_id='.$shop_id)           
              ->execute()
              ->fetchAll();
        $shop=$this->conn()->createQueryBuilder()
            ->select('p.*')
            ->from('msk_shop','p')
            ->where('shop_id='.$shop_id)
            ->execute()
            ->fetch();
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
         
        $prePage = $page - 1;
        $nextPage = $page + 1;
        return array(
            'goods'=>$data,
            'cate'=>$cate,
            'totalPage' => $totalPage,
            'page' => $page,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'where'=>$where,
            'shop'=>$shop
        );
    }
    
    
    /**
     * @Route("/shopcategory",name="shopcategory_")
     * @Template("AcmeMinsuBundle:Shop:goodsCategoryList.html.twig")
     **/
    public function shopcategory(Request $request){
        $shop_id=$request->get('shop_id');
        $shop=array();
        if ($_POST){
            $searchType=$request->get('searchType');
            $searcheText=$request->get('searchText');
            if ($searchType=='shop_name'){
                $where="s.shop_name like '%$searcheText%'";
            }elseif ($searchType=='cate_name'){
                $where="p.name like '%$searcheText%'";
            } 
        }
        if ($shop_id){
            $data=$this->conn()->createQueryBuilder()
            ->select('p.*,s.shop_name')
            ->from('msk_shop_category','p')
            ->leftJoin('p','msk_shop','s','p.shop_id=s.shop_id')
            ->where('p.shop_id='.$shop_id)
            ->execute()
            ->fetchAll();
        }else{
            $data=$this->conn()->createQueryBuilder()
            ->select('p.*,s.shop_name')
            ->from('msk_shop_category','p')
            ->leftJoin('p','msk_shop','s','p.shop_id=s.shop_id')
            ->execute()
            ->fetchAll();          
        } 
        $shop=$this->conn()->createQueryBuilder()
        ->select('*')
        ->from('msk_shop')
        ->where('is_show=1')
        ->execute()
        ->fetchAll();
        return array(
            'data'=>$data,
            'shop_id'=>$shop_id,
            'shop'=>$shop,
        );
    }
    
    
    /**
     * @Route("/shopcategorygoods",name="shopcategorygoods_")
     * @Template("AcmeMinsuBundle:Shop:GoodsList.html.twig")
     **/
    public function shopcategorygoods(Request $request){
        $page  = isset($_GET['page'])?$_GET['page']:0;
        $shop_id=$request->get('shop_id');
        $cat_id=$request->get('cat_id');
        if (empty($page)) {
            $page = 1;
        }
        $totalNumRes=$this->conn()->createQueryBuilder()
                    ->select('count(p.goods_id) as total')
                    ->from('msk_shop_goods','s')
                    ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
                    ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id')
                    ->where('s.shop_id='.$shop_id,'s.shop_cat_id='.$cat_id)
                    ->execute()->fetch();
        $pageSize = $this->getParameter('pagesize');
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
         
        $prePage = $page - 1;
        $nextPage = $page + 1;
        $data=$this->conn()->createQueryBuilder()
            ->select('p.*,c.*')
            ->from('msk_shop_goods','s')
            ->leftJoin('s','msk_mall_goods','p','s.goods_id=p.goods_id')
            ->leftJoin('s','msk_shop_category','c','s.shop_cat_id=c.id')
            ->where('s.shop_id='.$shop_id,'s.shop_cat_id='.$cat_id)
            ->setFirstResult($startPage)
            ->setMaxResults($pageSize)
            ->execute()
            ->fetchAll();
        $cate=$this->conn()->createQueryBuilder()
            ->select('p.*')
            ->from('msk_shop_category','p')
            ->where('shop_id='.$shop_id)
            ->execute()
            ->fetchAll();
        $shop=$this->conn()->createQueryBuilder()
            ->select('p.*')
            ->from('msk_shop','p')
            ->where('shop_id='.$shop_id)
            ->execute()
            ->fetch();
        return array(
            'goods'=>$data,
            'cate'=>$cate,
            'totalPage' => $totalPage,
            'page' => $page,
            'prePage' => $prePage,
            'nextPage' => $nextPage,
            'shop'=>$shop
        );
    }
    
    /**
     * @Route("/getPage",name="getPage_")
     **/
    public function getPage(Request $request){
        $url=$this->generateUrl('getPage_');
        $page=self::pageHtml(9,$d,1, 0, 1);
        return new JsonResponse($page);
    }
    
	 protected function pageHtml($totalPage,$url,$page,$prePage,$nextPage,$type=1){
	     $html ="<div class='page-dis'><div class='meneame'><a href=".$url."?page=1&type=".$type.">首页</a>";
	     $html .="<a href=".$url."?page=$prePage&type=$type>< </a>";
	     if($totalPage >= 7){
	         if($page <= 4){
	             for($i=1;$i<7;$i++){
	                 $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
	             }
	         }elseif ($page > ($totalPage - 4)){
	             for($i=$totalPage-7;$i<$totalPage;$i++){
	                 $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
	             }
	         }else{
	             for($i=($page-3);$i<($page+3);$i++){
	                 $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
	             }
	         }
	     }else{
	         if ($totalPage!=0){
	             for($i=1;$i<=$totalPage;$i++){
	                 $html .="<a href=".$url."?page=$i&type=$type>$i</a>";
	             }
	         }
	     }
	     $html .="<a href=".$url."?page=$nextPage&type=$type>></a>";
	     $html .="<a href=".$url."?page=$totalPage&type=$type>尾页</a></div></div>";
	     return $html;
	 }
    
    
    /**
     * @Route("/AddCategory",name="AddCategory_")
     * @Template("AcmeMinsuBundle:Shop:AddCategory.html.twig")
     */
    public function AddCategory(Request $request){
        $shop_id=$request->get('shop_id');
        $cate_id=$request->get('cate_id');
        $category=array();
        if($_POST){
            $name=$request->get('name');
            $parent_id=$request->get('parent_id');
            $shop_id=$request->get('shop_id');
            /* if (!empty($_FILES['logo']['tmp_name'])){
               $url=$this->QiniuUpload($_FILES['logo']['tmp_name'],'category');
            }  */
            $data=array(
                'name'=>$name,
                'parent_id'=>$parent_id,
                'shop_id'=>$shop_id,
            );
            $tmp=$this->conn()->insert('msk_shop_category',$data);
            if ($tmp){
                $this->conn()->createQueryBuilder()->update('msk_shop')
                ->set('category_sum','category_sum+1')
                ->where('shop_id='.$shop_id)
                ->execute();
                //$go=$this->generateUrl();
                return $this->redirectToRoute('shopcategory_',array('shop_id'=>$shop_id));
            }
        }
        if ($cate_id){
            $category=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_shop_category')
                ->where('id='.$cate_id)
                ->execute()
                ->fetch();
        }
        if ($shop_id){
            $cate=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_shop_category')
                ->where('shop_id='.$shop_id)
                ->execute()
                ->fetchAll();
            $this->conn()->createQueryBuilder()->update('msk_shop')
                 ->set('category_sum','category_sum+1')
                 ->where('shop_id='.$shop_id)
                 ->execute();
            return array('cate'=>$cate,'shop_id'=>$shop_id,'shop'=>'','category'=>$category);
        }else{
            $shop=$this->conn()->createQueryBuilder()
                ->select('*')
                ->from('msk_shop')
                ->where('is_show=1')
                ->execute()
                ->fetchAll();
            return array('cate'=>'','shop'=>$shop,'category'=>'');
        }          
    }
    
    /**
     * @Route("/getcategory",name="getcategory_")
     **/
    public function getcategory(){
        $shop_id=$_POST['shop_id'];
        $data=$this->conn()->createQueryBuilder()->select('*')->from('msk_shop_category')
              ->where('shop_id='.$shop_id)
              ->execute()
              ->fetchAll();
        return new JsonResponse(array('code'=>200,'result'=>$data));
        
    }
    
    
    /**
     * @Route("/delcategory",name="delcategory_")
     **/
    public function delcategory(){
        $id=$_POST['id'];
        $data=$this->conn()->createQueryBuilder()->select('*')->from('msk_shop_category')
            ->where('id='.$id)
            ->execute()
            ->fetch();
        if ($data){
            $a=$this->conn()->createQueryBuilder()
               ->delete('msk_shop_category')
                ->where('id='.$id)
                ->execute();
            $b=$this->conn()->createQueryBuilder()
                ->update('msk_shop')
                ->where('shop_id='.$data['shop_id'])
                ->set('category_sum','category_sum-1')
                ->execute();
            if ($a && $b) $tmp=1;
            else $tmp=-1;
        }else  $tmp=-1;
        return new JsonResponse($tmp);
    }
}






























<?php

namespace Acme\MinsuBundle\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\CPost;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
//use Symfony\Component\Translation\IdentityTranslator;



class GrouppostController extends Controller{

    private $qiniu='http://ogm9hltgr.bkt.clouddn.com/';
    
    
    /**
     * 义工
     * @Route("/grouppost",name="group_post")
     * @Template("AcmeMinsuBundle:lvyoubang:grouppost.html.twig")
     */
    public function grouppostAction(){
        if (isset($_POST['gscid'])){
            // 			echo $_POST['gscid'];exit();
            $gid =isset($_POST['gscid'])?$_POST['gscid']:0;
            // 			$where ="m.groupId=$gid";
            if ($gid =="9999999999"){
                $ss ='9999999999';
                $where ='m.groupId is not null';}
                else {
                    $where ="m.groupId=$gid";
                    	
                    $ss=$gid;
                }
        }
        else {
            $ss ='9999999999';
            $where ='m.groupId is not null';
        }
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $page =isset($_GET['page'])?$_GET['page']:1;
        $pageSize = $this->getParameter('pagesize');
        $totalNumRes= $conn->createQueryBuilder()
        ->select('count(m.id) as total')
        ->from('msk_community_post','m')
        ->where($where)
        ->execute()->fetch();
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
        $prePage = $page - 1;
        $nextPage = $page + 1;
        	
    
    
    
        $query = $conn->createQueryBuilder ()
        ->select ( "m.*,mm.groupName,mm.img,mm.dscp,mm.followNum,FROM_UNIXTIME(m.addTime, '%Y-%m-%d %H:%i:%s') NewAddTime,
					mm.postNum,mber.avatar,mmi.nickname,h.homestay_name" )
    					->from ( 'msk_community_post', 'm' )
    					->leftJoin('m','msk_group','mm','mm.id=m.groupId')
    					->leftJoin('m','msk_homestay','h','h.id=m.homestay_id')
    					->leftJoin('m','msk_member','mber','mber.id=m.memberId')
    					->leftJoin('m','msk_member_info','mmi','mmi.member_id=m.memberId')
    					->where("$where")
    					->setMaxResults($pageSize)
    					->setFirstResult($startPage)
    					->orderBy("m.addtime","DESC")
    					    ->execute ();
    					    $groupList = $query->fetchAll ();
    					    for($i=0;$i<count($groupList);$i++){
    					    $groupArray=array();
    					    $memberidd =$groupList[$i]['memberId'];
    					        $avatarPath=$this->qiniu;
    					        $commid = $groupList[$i]['id'];
    					        $groupList[$i]['avatar_url'] = $avatarPath .$groupList[$i]['avatar'];
    
    					            //查询贴图片
    					            $group_comm_img_list = $conn->createQueryBuilder ()
    					            ->select ( "m.*" )
    					            ->from ( 'msk_group_img', 'm' )
    					            ->where("m.groupId=$commid")
    					            ->execute ()->fetch();
    					            if($group_comm_img_list){
    					                $img=explode(';', $group_comm_img_list['imageName']);
    					                for($j=0;$j<count($img);$j++){
    					                    $groupArray[$j] =$this->qiniu.$img[$j];
    					                }
    					                $groupList[$i]['groupPostImg']=$groupArray;
    					            }
    					            else{
    					                $groupList[$i]['groupPostImg']=array();
    					            }
    					    }
    					    return array(
    					        'v'=>$groupList,
    					        'ss'=>$ss,
    					        'gg'=>self::pageHtml($totalPage,'grouppost',$page,$prePage,$nextPage),
    
    					    );
    
    }
    
    
    
    public function AddUrlfuc($groupList){
    
        $UrlPath=$this->container->getParameter('app_group_path');
    
        for($i=0;$i<count($groupList);$i++){
    
            $groupList[$i]['img_url'] = $UrlPath .$groupList[$i]['img'];
        }
        return $groupList;
    }
    
    /**
     * @Route("/changeGPS", name="changeGPS_")
     */
    public function changeGPSAction(Request $request)
    {
        $state = $request->get('state');
        $id = $request->get('id');
    
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $post = $conn->createQueryBuilder()
        ->update('msk_community_post', 'p')
        ->set('p.state', ':state')
        ->where("p.id = :id")
        ->setParameters(array('state' => $state, 'id' => $id))
        ->execute();
    
        return new Response(1);
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
     * 导游
     * @Route("/admindaoyou",name="admin_dao_you")
     * @Template("AcmeMinsuBundle:lvyoubang:yedaoyou.html.twig")
     */
    public function admindaoyou(Request $request){
        $conn = $this->getDoctrine ()->getManager ()->getConnection ();
        $avatarPath=$this->qiniu;
        $orderlist="type=2";
        $page=$request->get('page',1);
        $pageSize = $this->getParameter('pagesize');
        $totalNumRes= $conn->createQueryBuilder()
        ->select('count(guide_id) as total')
        ->from('msk_guide')
        ->where($orderlist)
        ->execute()->fetch();
        $totalNum = $totalNumRes['total'];
        $totalPage = ceil($totalNum / $pageSize);
        if ($totalPage != 0 && $page > $totalPage) {
            $page = $totalPage;
        }
        $startPage = ($page - 1) * $pageSize;
        $prePage = $page - 1;
        $nextPage = $page + 1;
    
        $data = $conn->createQueryBuilder()
        ->select('p.title', 'p.introduction','p.state','p.member_id','p.guide_id','p.add_time','p.image_url','m.avatar','e.nickname')
        ->from('msk_guide', 'p')
        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where($orderlist)
        ->setFirstResult($startPage)
        ->setMaxResults($pageSize)
        ->orderBy('p.add_time', 'desc')
        ->execute()->fetchAll();
        $tmp=array();
        if ($data){
            foreach ($data as $k=>$v){
                $comment=$conn->createQueryBuilder()
                ->select("count(comment_id) as total,avg(service_quality) as avge")
                ->from("msk_guide_comment")
                ->where('guide_id='.$v['guide_id'],'pid=0','kind=0')
                ->execute()
                ->fetch();
                $collect=$conn->createQueryBuilder()->select('count(id) as total')
                ->from(' msk_guide_collect')->where('guide_id='.$v['guide_id'],'is_upvote=1')->execute()->fetch();
                $tmp[$k]['id']=$v['guide_id'];
                $tmp[$k]['state']=$v['state'];
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['add_time']);
                $tmp[$k]['avatar'] = $avatarPath . $v['avatar'];
                $tmp[$k]['nickname'] =$v['nickname'];
                $tmp[$k]['content'] =$v['introduction'];
                $img=explode(';',$v['image_url']);
                $tmp[$k]['img'] = $avatarPath.$img[0];
                $tmp[$k]['comment_count']=$comment['total'];
                $tmp[$k]['like_count']=$collect['total'];
            }
        }
    
        return array(
            'data'=>$tmp,
            'gg'=>self::pageHtml($totalPage,'admindaoyou',$page,$prePage,$nextPage),
        );
    
    }
    
    /**
     * @Route("/changedaoyou", name="changedaoyou_")
     */
    public function changedaoyouAction(Request $request)
    {
        $state = $request->get('state');
        $id = $request->get('id');
         
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $post = $conn->createQueryBuilder()
        ->update('msk_guide', 'p')
        ->set('p.state', ':state')
        ->where("p.guide_id = :id")
        ->setParameters(array('state' => $state, 'id' => $id))
        ->execute();
    
        return new Response(1);
    }
    
    

    /**
     * @Route("/adminaddyigong", name="adminaddyigong_")
     *   @Template("AcmeMinsuBundle:lvyoubang:addyigong.html.twig")
     */
    public function adminaddyigongAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $homestay=$conn->createQueryBuilder()
        ->select('id,homestay_name')
        ->from('msk_homestay')
        ->where('is_manage=1','state=1')
        ->execute()
        ->fetchAll();
        
        return array(
            'homestay'=>$homestay,
        );
         
    }
    
    /**
     * @Route("/saveyigongdata", name="save_yigong_data")
     *
     */
    public function saveyigongdataAction(Request $request)
    {   
    
    $manager= $this->getDoctrine()->getManager();
    $conn = $manager->getConnection();
    if (empty($_FILES['imageUpload']['tmp_name'])) $this->Send(300,'请插入封面');
     
    $homestay_id=$request->get('homestay_id');
    $title=$request->get('title');
    $homestay=$conn->createQueryBuilder()
    ->select("*")
    ->from('msk_homestay')
    ->where('id='.$homestay_id)
    ->execute()->fetch();
     
     
    $post = new CPost();
    $post->setGroupId(20);
    $post->setMemberId($homestay['member_id']);
    $post->setContent($request->get('editorValue'));
    $post->setLikeNum(0);
    $post->setDiscussNum(0);
    $post->setAddtime(time());
    $post->setTitle($title);
    $post->setLongitude($request->get('longitude'));
    $post->setLatitude($request->get('latitude'));
    $post->setAddress($request->get('addr'));
    $post->setAtt($request->get('att',0));
    $post->setHomestayId($homestay_id);
    $manager->persist($post);
    $manager->flush();
    $commid = $post->getId();
    $cover_img=self::QiniuUpload($_FILES['imageUpload']['tmp_name'], 'yigong');
    $image=$request->get('otherimage',0);
    if ($image){
        $imgstr=implode(";", $image);
        $img=$cover_img.';'.$imgstr;
    }else{
        $img=$cover_img;
    }
    $vid='';
    $im=array(
        'groupId'=>$commid,
        'memberId'=>$homestay['member_id'],
        'imgType'=>0,
        'imageName'=>$img,
        'video'=>$vid,
        'addTime'=>time(),
    );
    if($commid){
        $d=$conn->insert('msk_group_img',$im);
         //self::addmynote($this->user_id,3,$commid,$title,$cover_img);
         //self::addmynotecount($this->user_id,0,0,1,0,0);
        if ($d) return $this->redirectToRoute('group_post');
    }
        return $this->redirectToRoute('adminaddyigong_');
    }


    /**
     * 七牛上传
     * **/
    protected  function QiniuUpload($filename=0,$prefix=0){
        require '../vendor/php-sdk/autoload.php';
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
        $bucket = 'minsu2';
        $auth = new Auth($accessKey, $secretKey);
        $token = $auth->uploadToken($bucket);
        $filePath = $filename;
        $key = $prefix."_".time().mt_rand(1, 100).'.jpg';
        $uploadMgr = new UploadManager();
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            return $ret['key'];
        }
    }
    
    /**
     * 驴友帮推荐列表
     * @Route("/lvyoubangrec", name="lvyoubangrec_")
     * @Template("AcmeMinsuBundle:lvyoubang:recommend.html.twig")
     */
    public function lvyoubangrec(){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $type=isset($_POST['State'])?$_POST['State']:0;
        $where="";
        if ($type){
            switch ($type){
                case 1:
                    $where="type=1";
                    break;
                case 2:
                    $where="type=2";
                    break;
                case 3:
                    $where="type=3";
                    break;
                case 4:
                    $where="type=4";
                    break;
                case 5:
                    $where="type=5";
                    break;
            }
        }
        $data=$conn->createQueryBuilder()
              ->select('*')
              ->from('msk_tour_pal_rec')
              ->where('is_rec=1',$where)
              ->orderBy('sort','desc')
              ->execute()
              ->fetchAll();
        return array(
            'data'=>$data,
        );
    }
    
    /**
     * 驴友帮推荐删除
     * @Route("/delLvyoubangRec", name="del_lvyoubang_rec_")    
     */
    public function delLvyoubangRec(Request $request){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $rec_id=$request->get('rec_id');
        $data=$conn->createQueryBuilder()
        ->delete('msk_tour_pal_rec')
        ->where('id='.$rec_id)
        ->execute();
        if ($data) return new Response(1);
        return new Response(2);
    }
    
    /**
     * 驴友帮推荐排序
     * @Route("/ajaxchangelvyoubangrecstate", name="ajax_change_lvyoubang_rec_state_")
     */
    public function ajaxchangelvyoubangrecstate(Request $request){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $id=$request->get('id');
        $val=$request->get('val');
        $data=$conn->createQueryBuilder()
        ->update('msk_tour_pal_rec')
        ->set('sort',$val)
        ->where('id='.$id)
        ->execute();
        if ($data) return new Response(1);
        return new Response(2);
    }
    
    /**
     * 添加驴友帮推荐
     * @Route("/addlvyoubangrec", name="addllvyoubangrec_")
     * @Template("AcmeMinsuBundle:lvyoubang:recommendInfo.html.twig")
     */
    public function addlvyoubangrec(Request $request){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();        
        if ($_POST){
            $type=$request->get('type',1);
            $for_id=$request->get('for_id');
            $sort=$request->get('sort');
            switch ($type){
                case 1:
                    $data =  $conn->createQueryBuilder()
                    ->select("m.id,m.member_id,m.homestay_name as title,m.addtime,m.image_url,meb.avatar,mmi.nickname")
                    ->from('msk_homestay', 'm')
                    ->leftJoin('m', 'msk_member', 'meb', 'meb.id=m.member_id')
                    ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
                    ->where('m.id='.$for_id)
                    ->execute()
                    ->fetch();
                    if ($data){
                        $img=explode(';',$data['image_url']);
                        $data['image_url']=$img[0];
                    }
                    break;
                case 2:
                    $data =  $conn->createQueryBuilder()
                    ->select("m.id,m.member_id,m.travel_title as title,m.addtime,meb.avatar,mmi.nickname")
                    ->from('msk_travel_note', 'm')
                    ->leftJoin('m', 'msk_member', 'meb', 'meb.id=m.member_id')
                    ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
                    ->where('m.id='.$for_id)
                    ->execute()
                    ->fetch();
                    if ($data){
                        $img=$conn->createQueryBuilder()
                        ->select('*')
                        ->from('msk_travel_note_images')
                        ->where('travel_note_id='.$for_id,'is_default=1')
                        ->execute()
                        ->fetch();
                        $data['image_url']=$img['travel_note_image'];
                    }
                    break;
                case 3:
                    $data =  $conn->createQueryBuilder()
                    ->select("m.id,m.memberId as member_id,m.title,m.addTime as addtime,meb.avatar,mmi.nickname")
                    ->from('msk_community_post', 'm')
                    ->leftJoin('m', 'msk_member', 'meb', 'meb.id=m.memberId')
                    ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
                    ->where('m.id='.$for_id)
                    ->execute()
                    ->fetch();
                    if ($data){
                        $img=$conn->createQueryBuilder()
                        ->select('*')
                        ->from('msk_group_img')
                        ->where('groupId='.$for_id,'imgType=0')
                        ->execute()
                        ->fetch();
                        $imgs=explode(';',$img['imageName']);
                        $data['image_url']=$imgs[0];
                    }
                    break;
                case 4:
                    $data =  $conn->createQueryBuilder()
                    ->select("m.guide_id as id ,m.member_id,m.title,m.add_time as addtime,image_url,meb.avatar,mmi.nickname")
                    ->from('msk_guide', 'm')
                    ->leftJoin('m', 'msk_member', 'meb', 'meb.id=m.member_id')
                    ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
                    ->where('m.guide_id='.$for_id)
                    ->execute()
                    ->fetch();
                    if ($data){
                        $imgs=explode(';',$data['image_url']);
                        $data['image_url']=$imgs[0];
                    }
                    break;
                case 5:
                    $data =  $conn->createQueryBuilder()
                    ->select("m.tour_id as id ,m.member_id,m.tour_title as title,m.addtime,m.imgurl as image_url,meb.avatar,mmi.nickname")
                    ->from('msk_group_tour', 'm')
                    ->leftJoin('m', 'msk_member', 'meb', 'meb.id=m.member_id')
                    ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
                    ->where('m.tour_id='.$for_id)
                    ->execute()
                    ->fetch();
                    break;
            }
            if ($data){
                $arr=array(
                    'for_id'=>$data['id'],
                    'title'=>$data['title'],
                    'image_url'=>$data['image_url'],
                    'addtime'=>$data['addtime'],
					'member_id'=>$data['member_id'],
					'nickname'=>$data['nickname'],
 					'avatar'=>isset($data['avatar'])?$data['avatar']:0,
					'type'=>$type,
				    'sort'=>$sort,
                );
				$a=$conn->insert('msk_tour_pal_rec',$arr);
            }
           return $this->redirectToRoute('lvyoubangrec_');
        }
        
    }
    
    
    /**
     * 添加驴友帮推荐
     * @Route("/ajaxgetlvyoubang", name="ajaxgetlvyoubang_")
     *
     */
    public function ajaxgetlvyoubang(Request $request)
    {

        $type   = $request->get('type',3);
        //$where='';
        switch ($type){
            case 1:                
                $data=self::gethomestayList();
                break;
            case 2:
                $data=self::getjingdianList();
                break;
            case 3:
                $data=self::getCPostList();
                break;
            case 4:
                $data=self::getdaoyouList();
                break;
            case 5:
                $data=self::getlvyoutuanList();
                break;
            default:
                break;
        }
        //if ($data) return new Response($data);
        return new JsonResponse($data);
        
    }

    
    /**
     * 民宿列表
     * **/
    private function gethomestayList(){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data =  $conn->createQueryBuilder()
        ->select("m.id,m.member_id,m.homestay_name as title,m.addtime,mmi.nickname")
        ->from('msk_homestay', 'm')
        ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
        ->where('m.state>0')
        ->orderBy("m.addtime ,'DESC'")
        ->execute()
        ->fetchAll();
    
        return $data;
    }
    
    /**
     * 景点列表
     * **/
    private function getjingdianList(){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data =  $conn->createQueryBuilder()
        ->select("m.id,m.member_id,m.travel_title as title,m.addtime,mmi.nickname")
        ->from('msk_travel_note', 'm')
        ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
        ->where('m.state=0')
        ->orderBy("m.addtime ,'DESC'")
        ->execute()
        ->fetchAll();
    
        return $data;
    }
    
    /**
     * 义工帖子列表
     * **/
    private function getCPostList(){
		$manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
            $groupList =  $conn->createQueryBuilder()
            ->select("m.id,m.memberId as member_id,m.title,m.addTime as addtime,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->where('state=0')
            ->orderBy("m.addTime ,'DESC'")
            ->execute()
            ->fetchAll();
        
        return $groupList;
    }
    
    
    
    /**
     * 驴友帮导游列表
     * **/
    private function getdaoyouList(){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = $conn->createQueryBuilder()
        ->select('p.title', 'p.member_id','p.guide_id as id','p.add_time','p.image_url','m.avatar','e.nickname')
        ->from('msk_guide', 'p')
        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where('p.type=2')
        ->orderBy('p.add_time', 'desc')
        ->execute()->fetchAll();
        return  $data;
    }
    
    /**
     * 驴友帮驴友团列表
     * **/
    private function getlvyoutuanList(){
        $manager= $this->getDoctrine()->getManager();
        $conn = $manager->getConnection();
        $data = $conn->createQueryBuilder()
        ->select('p.tour_id as id', 'p.imgurl as imageurl','p.member_id','p.tour_title as title','p.addtime','m.avatar','e.nickname')
        ->from('msk_group_tour', 'p')
        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where('p.type=1')
        ->orderBy('p.addtime', 'desc')
        ->execute()->fetchAll();
        
        return $data;
    }
    
}

















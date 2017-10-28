<?php
/**
 * Created by Zend Studio
 * User: Administrator
 * Date: 2016-5-30
 * Time: 14:08
 */
namespace Acme\MinsuBundle\Api;
use Acme\MinsuBundle\Common\CommonController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Symfony\Component\HttpFoundation\Request;

class apiTourPalController extends CommonController
{

    
    /**
     * 旅友帮下评论列表
     * @Route("/apiTourPalComment",name="_apiTourPalComment")
     */
    public function apiTourPalComment(Request $request){
        $type=$request->get('type',1);
        $id=$request->get('id');
        $page=$request->get('page',0);
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        switch ($type){
            case 1:
                $data=self::getHomestay($id,$avatarPath,$page);
                break;
            case 2:
                $data=self::getJingDian($id,$avatarPath,$page);
                break;
            case 3:
                $data=self::getyigong($id,$avatarPath,$page);
                break;
            case 4:
                $data=self::getdaoyou($id,$avatarPath,$page);
                break;
            case 5:
                $data=self::getlvyoutuan($id,$avatarPath);
                break;
            default:
                $this->Send(300,'非法操作!');
                break;
        }
        if ($data['total']>0) {$this->Send(200,'success',$data);}
        $this->Send(202,'暂无数据');
       
    }
    
    //民宿
     protected function getHomestay($id,$path,$page){
         $total=$this->conn()->createQueryBuilder()
                 ->select('count(id) as total')
                 ->from('msk_homestay_share_eval')
                 ->where('state=0','pid=0','homestay_id='.$id)
                 ->execute()
                 ->fetch();
         $data['total']=$total['total'];
         $data['list']=$this->conn()->createQueryBuilder()
                  ->select('p.id,p.homestay_id as class_id,p.member_id,p.eval,p.grade,m.avatar,e.nickname,p.addtime')
                  ->from('msk_homestay_share_eval','p')
                  ->leftJoin('p','msk_member','m','p.member_id=m.id')
                  ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
                  ->where('p.state=0','p.pid=0','p.homestay_id='.$id)
                  ->orderBy('p.addtime','desc')
                  ->setFirstResult($page)
                  ->setMaxResults(10)
                  ->execute()
                  ->fetchAll();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['avatar']=$path.$v['avatar'];
            $data['list'][$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data['list'][$k]['children']=self::getHomestayChild($v['id'],$path);
        }
        return $data;
    }
    
    //民宿
    protected function getHomestayChild($id,$path){
        $data=$data=$this->conn()->createQueryBuilder()
              ->select('p.id,p.pid,p.homestay_id as class_id,p.member_id,p.eval,p.grade,m.avatar,e.nickname,p.addtime,p.reply_id')
              ->from('msk_homestay_share_eval','p')
              ->leftJoin('p','msk_member','m','p.member_id=m.id')
              ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
              ->where('p.state=0','p.pid='.$id)
              ->execute()
              ->fetchAll();
        foreach ($data as $k=> $v ){
            $user=$this->conn()->createQueryBuilder()->select('p.avatar,e.nickname')
                    ->from('msk_member','p')
                    ->leftJoin('p','msk_member_info','e','p.id=e.member_id')
                    ->where('p.id='.$v['reply_id'])->execute()->fetch();
            $data[$k]['avatar']=$path.$v['avatar'];
            $data[$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data[$k]['reply_name']=$user['nickname'];
            $data[$k]['reply_avatar']=$path.$user['avatar'];
        }     
        return $data;
    }
    
    
    //景点
    protected function getJingDian($id,$path,$page){
        $total=$this->conn()->createQueryBuilder()
            ->select('count(p.id) as total')
            ->from('msk_post_discuss','p')
            ->where('p.discussParentId=0','p.comPostId='.$id)
            ->execute()
            ->fetch();
        $data['total']=$total['total'];
        $data['list']=$this->conn()->createQueryBuilder()
                ->select('p.id,p.comPostId as class_id,p.memberId as member_id,p.discuss as eval,p.grade,m.avatar,e.nickname,p.addtime,p.discussNum as total')
                ->from('msk_post_discuss','p')
                ->leftJoin('p','msk_member','m','p.memberId=m.id')
                ->leftJoin('p','msk_member_info','e','p.memberId=e.member_id')
                ->where('p.discussParentId=0','p.comPostId='.$id)
                ->orderBy('p.addtime','desc')
                ->setFirstResult($page)
                ->setMaxResults(10)
                ->execute()
                ->fetchAll();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['avatar']=$path.$v['avatar'];
            $data['list'][$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data['list'][$k]['children']=self::getJingDianChild($v['id'],$path);
        }
        return $data;
    }
    
    //景点
    protected function getJingDianChild($id,$path){
        $data=$this->conn()->createQueryBuilder()
                ->select('p.id,p.discussParentId as pid,p.comPostId as class_id,p.memberId as member_id,p.discuss as eval,p.grade,m.avatar,e.nickname,p.addtime,p.discussNum as total,p.reply_id')
                ->from('msk_post_discuss','p')
                ->leftJoin('p','msk_member','m','p.memberId=m.id')
                ->leftJoin('p','msk_member_info','e','p.memberId=e.member_id')
                ->where('p.discussParentId='.$id)
                ->orderBy('p.addtime','desc')
                ->execute()
                ->fetchAll();
        foreach ($data as $k=> $v ){
           $user=$this->conn()->createQueryBuilder()->select('p.avatar,e.nickname')
            ->from('msk_member','p')
            ->leftJoin('p','msk_member_info','e','p.id=e.member_id')
            ->where('p.id='.$v['reply_id'])->execute()->fetch();
            $data[$k]['avatar']=$path.$v['avatar'];
            $data[$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data[$k]['reply_name']=$user['nickname'];
            $data[$k]['reply_avatar']=$path.$user['avatar'];
        }
        return $data;
    }
    
    
    //义工
    protected function getyigong($id,$path,$page){
        $total=$this->conn()->createQueryBuilder()
            ->select('count(p.id) as total')
            ->from('msk_tour_pal_comment','p')
            ->where('type=2','p.fid=0','p.for_id='.$id)
            ->execute()
            ->fetch();
        $data['total']=$total['total'];
        $data['list']=$this->conn()->createQueryBuilder()
            ->select('p.id,p.for_id as class_id,p.member_id,p.content as eval,p.grade,m.avatar,e.nickname,p.addtime')
            ->from('msk_tour_pal_comment','p')
            ->leftJoin('p','msk_member','m','p.member_id=m.id')
            ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
            ->where('type=2','p.fid=0','p.for_id='.$id)
            ->orderBy('p.addtime','desc')
            ->setFirstResult($page)
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['avatar']=$path.$v['avatar'];
            $data['list'][$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data['list'][$k]['children']=self::getyigongChild($v['id'],$path);
        }
        return $data;
    }
    
    //义工
    protected function getyigongChild($id,$path){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.id,p.fid as pid,p.for_id as class_id,p.member_id,p.content as eval,p.grade,m.avatar,e.nickname,p.addtime,p.reply_id')
        ->from('msk_tour_pal_comment','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
        ->where('p.type=2','p.fid='.$id)
        ->orderBy('p.addtime','desc')
        ->execute()
        ->fetchAll();
        foreach ($data as $k=> $v ){
            $user=$this->conn()->createQueryBuilder()->select('p.avatar,e.nickname')
            ->from('msk_member','p')
            ->leftJoin('p','msk_member_info','e','p.id=e.member_id')
            ->where('p.id='.$v['reply_id'])->execute()->fetch();
            $data[$k]['avatar']=$path.$v['avatar'];
            $data[$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data[$k]['reply_name']=$user['nickname'];
            $data[$k]['reply_avatar']=$path.$user['avatar'];
        }
        return $data;
    }
    
    //导游
    protected function getdaoyou($id,$path,$page){
        $total=$this->conn()->createQueryBuilder()
        ->select('count(p.comment_id) as total')
        ->from('msk_guide_comment','p')
        ->where('p.pid=0','p.guide_id='.$id)
        ->execute()
        ->fetch();
        $data['total']=$total['total'];
        $data['list']=$this->conn()->createQueryBuilder()
        ->select('p.comment_id as id,p.guide_id as class_id,p.comment_user as member_id,p.content as eval,p.service_quality as grade,p.imageurl as avatar,p.username as nickname,p.addtime')
        ->from('msk_guide_comment','p')
        ->where('p.pid=0','p.guide_id='.$id)
        ->orderBy('p.addtime','desc')
        ->setFirstResult($page)
        ->setMaxResults(10)
        ->execute()
        ->fetchAll();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data['list'][$k]['children']=self::getdaoyouChild($v['id'],$path);
        }
        return $data;
    }
    
    //导游
    protected function getdaoyouChild($id,$path){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.comment_id as id,p.pid,p.guide_id as class_id,p.comment_user as member_id,p.content as eval,p.service_quality as grade,p.imageurl as avatar,p.username as nickname,p.addtime,p.reply_id')
        ->from('msk_guide_comment','p')
        ->where('p.pid='.$id)
        ->orderBy('p.addtime','desc')
        ->execute()
        ->fetchAll();
        foreach ($data as $k=> $v ){
            $user=$this->conn()->createQueryBuilder()->select('p.avatar,e.nickname')
            ->from('msk_member','p')
            ->leftJoin('p','msk_member_info','e','p.id=e.member_id')
            ->where('p.id='.$v['reply_id'])->execute()->fetch();
            $data[$k]['avatar']=$v['avatar'];
            $data[$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data[$k]['reply_name']=$user['nickname'];
            $data[$k]['reply_avatar']=$path.$user['avatar'];
        }
        return $data;
    }
    
    
    
    //驴友团
    protected function getlvyoutuan($id,$path,$page){
        $total=$this->conn()->createQueryBuilder()
        ->select('count(p.id) as total')
        ->from('msk_tour_pal_comment','p')
        ->where('type=1','p.fid=0','p.for_id='.$id)
        ->execute()
        ->fetch();
        $data['total']=$total['total'];
        $data['list']=$this->conn()->createQueryBuilder()
        ->select('p.id,p.for_id as class_id,p.member_id,p.content as eval,p.grade,m.avatar,e.nickname,p.addtime')
        ->from('msk_tour_pal_comment','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
        ->where('type=1','p.fid=0','p.for_id='.$id)
        ->orderBy('p.addtime','desc')
        ->setFirstResult($page)
        ->setMaxResults(10)
        ->execute()
        ->fetchAll();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['avatar']=$path.$v['avatar'];
            $data['list'][$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data['list'][$k]['children']=self::getlvyoutuanChild($v['id'],$path);
        }
        return $data;
    }
    
     //驴友团
    protected function getlvyoutuanChild($id,$path){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.id,p.fid as pid,p.for_id as class_id,p.member_id,p.content as eval,p.grade,m.avatar,e.nickname,p.addtime,p.reply_id')
        ->from('msk_tour_pal_comment','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->leftJoin('p','msk_member_info','e','p.member_id=e.member_id')
        ->where('p.type=1','p.fid='.$id)
        ->orderBy('p.addtime','desc')
        ->execute()
        ->fetchAll();
        foreach ($data as $k=> $v ){
            $user=$this->conn()->createQueryBuilder()->select('p.avatar,e.nickname')
            ->from('msk_member','p')
            ->leftJoin('p','msk_member_info','e','p.id=e.member_id')
            ->where('p.id='.$v['reply_id'])->execute()->fetch();
            $data[$k]['avatar']=$path.$v['avatar'];
            $data[$k]['addtime']=date('m月d日 H:i',$v['addtime']);
            $data[$k]['reply_name']=$user['nickname'];
            $data[$k]['reply_avatar']=$path.$user['avatar'];
        }
        return $data;
    }
    
    
    /**
     * 驴友帮下的详情
     * @Route("/apiGroupTourDetails", name="apiGroupTourDetails_")
     */
    public function apiGroupTourDetails(Request $request){
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        $type=$request->get('type',1);
        $id=$request->get('id');
        $user_id=$request->get('member_id',0);
        switch ($type){
            case 1:
                $data=self::getHomestayDetail($id,$avatarPath,$user_id);
                break;
            case 2:
                $data=self::getSenseDetail($id,$avatarPath,$user_id);
                break;
            case 3:
                $data=self::getYiGongDetail($id,$avatarPath,$user_id);
                break;
            case 4:
                $data=self::getDaoYouDetail($id,$avatarPath,$user_id);
                break;
            case 5:
                $data=self::getGroupTourDetail($id,$avatarPath,$user_id);
                break;
            default:
                $this->Send(300,'非法操作');
                break;
        }
        if ($data)  $this->Send(200,'success',$data);
        $this->Send(300,'数据不存在！');
    
    }
    
    //旅友帮民宿
    protected function getHomestayDetail($id,$UrlPath,$user_id=0){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.id,p.member_id,p.homestay_title as title ,p.homestay_addr as address,p.longitude,p.latitude,m.avatar,p.image_url,p.video_url')
        ->from('msk_homestay','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->where('p.id='.$id)
        ->execute()->fetch();
        if (empty($data)) return false;
        $data['content']=$data['title'];
        $data['avatar']=$UrlPath.$data['avatar'];
        $img=explode(';',$data['image_url']);
        $data['cover_image']=$UrlPath.$img[0];
        $data['image']=array();
        if (count($img)>1){
            foreach ($img as $v){
                $data['image'][]=$UrlPath.$v;
            }
        }
        if ($data['video_url']){
            $vid=explode(';',$data['video_url']);
            $data['video_cover']=$UrlPath.$vid[0];
            $data['video_url']=$UrlPath.$vid[1];
        }else{
            $data['video_cover']=0;
            $data['video_url']=0;
        }
        $data['att']=' ';
        $data['is_collect']=0;
        $data['is_upvote']=0;
        if ($user_id){
            //收藏
            $collect=$this->conn()->createQueryBuilder()->select('*')
            ->from('msk_homestay_collect')->where('homestay_id='.$id,'member_id='.$user_id,'is_collect=1')->execute()->fetch();
            if ($collect) {$data['is_collect']=1;}
            else  {$data['is_collect']=0;}
            //点赞
            $upvote=$this->conn()->createQueryBuilder()->select('*')
            ->from('msk_homestay_upvote')->where('homestay_id='.$id,'member_id='.$user_id,'upvote=1')->execute()->fetch();
            if ($upvote){ $data['is_upvote']=1;}
            else{ $data['is_upvote']=0;}
        }
        $total=$this->conn()->createQueryBuilder()->select('count(id) as total')
        ->from('msk_homestay_upvote')->where('homestay_id='.$id,'upvote=1')->execute()->fetch();
        $data['upvote_total']=$total['total'];
        return $data;
    }
    
    //旅友帮景点
    protected function getSenseDetail($id,$UrlPath,$user_id=0){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.id,p.member_id,p.travel_title as title,p.travel_content as content,p.addr as address,p.longitude,p.latitude,m.avatar')
        ->from('msk_travel_note','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->where('p.id='.$id)
        ->execute()->fetch();
        if (empty($data)) return false;
        $data['avatar']=$UrlPath.$data['avatar'];
        $img=$this->conn()->createQueryBuilder()->select('*')
        ->from('msk_travel_note_images')->where('travel_note_id='.$id)->execute()->fetchAll();
        $data['image']=array();
        $data['video_cover']=0;
        $data['video']=0;
        foreach ($img as $k=>$v){
            if ($v['is_default']==1)
            {
                $data['cover_image']=$UrlPath.$v['travel_note_image'];
            }
            if ($v['is_default']==2)
            {
                $data['video_cover']=$UrlPath.$v['travel_note_image'];
            }
            if ($v['is_default']==3)
            {
                $data['video_url']=$UrlPath.$v['travel_note_image'];
            }
            if($v['is_default']==0)
            {
                $data['image'][]=$UrlPath.$v['travel_note_image'];
            }
        }
        $data['att']=' ';
        $data['is_collect']=0;
        $data['is_upvote']=0;
        if ($user_id){
            //收藏/点赞
            $collect=$this->conn()->createQueryBuilder()->select('*,count(id) as total')
            ->from('msk_follow_collect')->where('travel_note_id='.$id,'member_id='.$user_id)->execute()->fetch();
            if ($collect['is_collect']==1) $data['is_collect']=1;
            else  $data['is_collect']=0;
            if ($collect['is_upvote']==1) $data['is_upvote']=1;
            else  $data['is_upvote']=0;
        }
        $total=$this->conn()->createQueryBuilder()->select('count(id) as total')
        ->from('msk_follow_collect')->where('travel_note_id='.$id,'is_upvote=1')->execute()->fetch();
        $data['upvote_total']=$total['total'];
        return $data;
    }
    
    //旅友帮义工
    protected function getYiGongDetail($id,$UrlPath,$user_id=0)
    {
        $conn= $this->conn();
        $groupList =$conn->createQueryBuilder()
        ->select('p.id,p.memberId as member_id,p.title,p.content,p.longitude,p.latitude,p.address,p.att,m.avatar')
        ->from('msk_community_post','p')
        ->leftJoin('p','msk_member','m','m.id=p.memberId')
        ->where("p.id=$id")
        ->andWhere("p.state=0")
        ->setMaxResults(10)
        ->execute()
        ->fetch();
        if (empty($groupList)) return false;
        $groupList['avatar'] = $UrlPath. $groupList['avatar'];
        // 查询贴图片
        $queryImg = $conn->createQueryBuilder()
        ->select("m.*")
        ->from('msk_group_img', 'm')
        ->where("m.groupId=$id")
        ->execute()->fetch();
        if ($queryImg['imageName']){
            $img=explode(';',$queryImg['imageName']);
            $groupList['cover_image']=$UrlPath.$img[0];
            if (count($img)>1){
                foreach ($img as $k=>$v){
                    $groupList['image'][]=$UrlPath.$v;
                }
            }else  $groupList['image']=array();
        }else {
            $groupList['cover_image']=0;
            $groupList['image']=array();
        }
        if ($queryImg['video'])
        {
            $video=explode(';',$queryImg['video']);
            $groupList['video_cover']=$UrlPath.$video[0];
            $groupList['video']=$UrlPath.$video[1];
        }else{
            $groupList['video_cover']=0;
            $groupList['video']=0;
        }
        $groupList['is_collect'] = 0;
        $groupList['is_upvote'] = 0;
        if ($user_id){
            //收藏
            $queryCollect = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_community_collect', 'm')
            ->where("m.comPostId=$id")
            ->andWhere("m.member_id=$user_id")
            ->andWhere("m.is_collect=1")
            ->execute()
            ->fetch();
            if ($queryCollect) {
                $groupList['is_collect'] = 1;
            } else {
                $groupList['is_collect'] = 0;
            }
            //点赞
             
            $atr=$conn->createQueryBuilder()->select('*')->from('msk_cpost_like')->where('PId='.$id,'memberId='.$user_id)->execute()->fetch();
            if ($atr) {
                $groupList['is_upvote'] = 1;
            } else {
                $groupList['is_upvote'] = 0;
            }
        }
        $total=$conn->createQueryBuilder()->select('count(id) as total')->from('msk_cpost_like')->where('PId='.$id)->execute()->fetch();
        $data['upvote_total']=$total['total'];
        return $groupList;
    }
    
    //旅友帮导游
    protected function getDaoYouDetail($id,$UrlPath,$user_id=0){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.guide_id as id,p.member_id,p.title,p.introduction as content,p.addr as address,p.longitude,p.latitude,p.language as att,p.image_url,p.video_url,m.avatar')
        ->from('msk_guide','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->where('type=2','p.guide_id='.$id)
        ->execute()->fetch();
        if (empty($data)) return false;
        $data['avatar']=$UrlPath.$data['avatar'];
        $img=explode(';',$data['image_url']);
        $data['cover_image']=$UrlPath.$img[0];
        $data['image']=array();
        if (count($img)>1){
            foreach ($img as $v){
                $data['image'][]=$UrlPath.$v;
            }
        }
        if ($data['video_url']){
            $vid=explode(';',$data['video_url']);
            $data['video_cover']=$UrlPath.$vid[0];
            $data['video_url']=$UrlPath.$vid[1];
        }else{
            $data['video_cover']=0;
            $data['video_url']=0;
        }
        $data['is_collect']=0;
        $data['is_upvote']=0;
        if ($user_id){
            //收藏/点赞
            $collect=$this->conn()->createQueryBuilder()->select('*')
            ->from(' msk_guide_collect')->where('guide_id='.$id,'member_id='.$user_id)->execute()->fetch();
            if ($collect['is_collect']==1) $data['is_collect']=1;
            else  $data['is_collect']=0;
            if ($collect['is_upvote']==1) $data['is_upvote']=1;
            else  $data['is_upvote']=0;
        }
        $total=$this->conn()->createQueryBuilder()->select('count(*) as total')
        ->from(' msk_guide_collect')->where('guide_id='.$id)->execute()->fetch();
        $data['upvote_total']=$total['total'];
        return $data;
    }
    
    
    //驴友帮驴友团
    protected function getGroupTourDetail($id,$UrlPath,$user_id=0){
        $data=$this->conn()->createQueryBuilder()
        ->select('p.tour_id as id,p.member_id,p.address,p.longitude,p.latitude,p.tour_title as title,p.booking_notice as content,p.imgurl as cover_image,p.video as video_url,m.avatar')
        ->from('msk_group_tour','p')
        ->leftJoin('p','msk_member','m','p.member_id=m.id')
        ->where('p.tour_id='.$id)
        ->execute()->fetch();
        if (empty($data)) return false;
        $data['avatar']=$UrlPath.$data['avatar'];
        $img=explode(';',$data['image_url']);
        $data['cover_image']=$UrlPath.$data['cover_image'];
        $data['image']=array();
        if (count($img)>1){
            foreach ($img as $v){
                $data['image'][]=$UrlPath.$v;
            }
        }
        if ($data['video_url']){
            $vid=explode(';',$data['video_url']);
            $data['video_cover']=$UrlPath.$vid[0];
            $data['video_url']=$UrlPath.$vid[1];
        }else{
            $data['video_cover']=0;
            $data['video_url']=0;
        }
        $data['att']='';
        $data['is_collect']=0;
        $data['is_upvote']=0;
        if ($user_id){
            //收藏/点赞
            $collect=$this->conn()->createQueryBuilder()->select('*')
            ->from('msk_group_tour_collect')->where('tour_id='.$id,'member_id='.$user_id)->execute()->fetch();
            if ($collect['is_collect']==1) $data['is_collect']=1;
            else  $data['is_collect']=0;
            if ($collect['is_upvote']==1) $data['is_upvote']=1;
            else  $data['is_upvote']=0;
        }
        $total=$this->conn()->createQueryBuilder()->select('count(*) as total')
        ->from('msk_group_tour_collect')->where('tour_id='.$id)->execute()->fetch();
        $data['upvote_total']=$total['total'];
        return $data;
    }
    
    
    /**
     * 驴友帮导游发帖
     * @Route("apiGuidePosts", name="apiGuidePosts_")
     */
    public function apiGuidePostsAction(Request $request)
    {
        self::validationToken($request->get('token'));
        $manager = $this->getDoctrine()->getManager();
        //$group_id=$request->get('group_id');
        //$group_id=20;
        //if (empty($group_id)) $this->Send(300,'分组不存在');
        //$this->Send($_POST);
        if (!($cover_img=$request->get('cover_image'))) $this->Send(300,'请插入封面');
        $longitude=$request->get('longitude');
        $latitude=$request->get('latitude');
        $video_cover=$request->get('video_cover',0);
        $video=$request->get('video',0);
        $image = $request->get('image',0);
        $title=$request->get('title');
        $addGeohash=self::addGeohash($longitude,$latitude);
        $post = new Guide();
        $post->setMemberId($this->user_id);
        $post->setIntroduction($request->get('content'));
        $post->setAddtime(time());
        $post->setTitle($title);
        $post->setLongitude($longitude);
        $post->setLatitude($latitude);
        $post->setAddr($request->get('address'));
        $post->setLanguage($request->get('language'));
        $post->setType(2);
        $post->setGeohash($addGeohash);
        if ($image){ $img=$cover_img.';'.$image;}
        else   $img=$cover_img;
        if ($video_cover)  $vid=$video_cover.';'.$video;
        else $vid='';
        $post->setImage_url($img);
        $post->setVideo_url($vid);
        $manager->persist($post);
        $manager->flush();
        $id = $post->getGuideId();
        if ($id) {
            self::addmynote($this->user_id,5,$id,$title,$cover_img);
            self::addmynotecount($this->user_id,0,0,0,1,0);
            $this->Send(200,'success');
        }
        $this->Send(300,'发表失败');
    }
    
    
    /**
     * 驴友帮驴友团发帖
     * @Route("apiGroupTourPosts", name="apiGroupTourPosts_")
     */
    public function apiGroupTourPostsAction(Request $request)
    {
        self::validationToken($request->get('token'));
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST)?$_POST:'';
        if (!($cover_img=$request->get('cover_image'))) $this->Send(300,'请插入封面');
        $triparr=json_decode($data['triparr'],true);
        $route=json_decode($data['route'],true);
        if (!$route)$this->Send(300,'设置路线',$route);
        $longitude=$data['longitude'];
        $latitude=$data['latitude'];
        $video_cover=isset($data['video_cover'])?$data['video_cover']:0;
        $video=$data['video'];
        $addGeohash=self::addGeohash($longitude,$latitude);
        $user=$this->conn()->createQueryBuilder()
        ->select('p.avatar,m.nickname')
        ->from('msk_member','p')
        ->leftJoin('p','msk_member_info','m','m.member_id=p.id')
        ->where('p.id='.$this->user_id)
        ->execute()->fetch();
        $Tour = new GroupTour();
        $Tour->setMemberId($this->user_id);
        $Tour->setLongitude($data['longitude']);
        $Tour->setLatitude($data['latitude']);
        $Tour->setGeohash($addGeohash);
        $Tour->setAddress($data['address']);
        $Tour->setTourTitle($data['tour_title']);
        $Tour->setImgurl($data['cover_image']);
        $Tour->setPeriod($data['period']);
        $Tour->setMemberName($user['nickname']);
        $Tour->setMemberAvatar($user['avatar']);
        $Tour->setStartingTime($data['starting_time']);
        $Tour->setStartingPlace($data['starting_place']);
        $Tour->setServicePrice($data['service_price']);
        $Tour->setBookingNotice($data['content']);
        $Tour->setPlanned($data['planned']);
        $Tour->setDestination($data['destination']);
        $Tour->setAdultPrice($data['adult_price']);
        $Tour->setTelphone($data['telphone']);
        $Tour->setaddtime(time());
        $Tour->setEndtime($data['end_time']);
        if ($video_cover) $Tour->setVideo($video_cover.';'.$video);
        $manager->persist($Tour);
        $manager->flush();
        $id = $Tour->getTourId();
        //$this->Send(200,'success',$id);
        if ($id) {
            self::addmynote($this->user_id,5,$id,$data['tour_title'],$data['cover_image']);
            self::addmynotecount($this->user_id,0,0,0,0,1);
            foreach($route as $v){
                $data=array(
                    'tour_id'=>$id,
                    'longitude'=>$v['longitude'],
                    'latitude'=>$v['latitude'],
                    'site'=>$v['site'],
                );
                $this->conn()->insert('msk_group_tour_detail',$data);
            }
            if ($triparr){
                foreach ($triparr as $v){
                    $data=array(
                        'tour_id'=>$id,
                        'title'=>$v['title'],
                        'longitude'=>$v['longitude'],
                        'latitude'=>$v['latitude'],
                        'num'=>$v['num'],
                    );
                    $this->conn()->insert('msk_group_tour_trip',$data);
                }
            }
            $this->Send(200,'success');
        }
        $this->Send(300,'发表失败');
    }
    
    
    /**
     * 旅友帮下的评论
     * @Route("apiAddTourPalComment", name="apiAddTourPalComment_")
     */
    public function apiAddTourPalComment(Request $request){
        $token=$request->get('token');
        $user_id=$this->validationToken($token);
        if(is_array($user_id)) $this->Send($user_id);
        $rank=$request->get('grade',4);
        $class_id=$request->get('class_id');
        $content=$request->get('content');
        $id=$request->get('id',0);
        $type=$request->get('type',1);
        switch ($type){
            case 1:
                $res=self::addhome($user_id,$class_id,$content,$rank,$id);
                break;
            case 2:
                $res=self::addjingdian($user_id,$class_id,$content,$rank,$id);
                break;
            case 3:
                $res=self::addyigong($user_id,$class_id,$content,$rank,$id);
                break;
            case 4:
                $res=self::addDaoyou($user_id,$class_id,$content,$rank,$id);
                break;
            case 5:
                $res=self::addlvyoutuan($user_id,$class_id,$content,$rank,$id);
                break;
            default:
                $this->Send(300,'非法操作');
                break;
        }
        if ($res) $this->Send(200,'评论成功');
        $this->Send(300,'评论失败');
    }
    
    //景点
    protected function addjingdian($user_id,$class_id,$content,$rank,$id){
        if ($id){
            $parent=$this->conn()->createQueryBuilder()
            ->select("*")->from('msk_post_discuss')->where('id='.$id)->execute()->fetch();
            $data=array(
                'memberId'=>$user_id,
                'comPostId'=>$class_id,
                'discuss'=>$content,
                'grade'=>$rank,
                'type'=>1,
                'discussParentId'  =>$id,
                'reply_id'=>$parent['memberId'],
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
            if ($parent['discussParentId']>0){
                $this->conn()->createQueryBuilder()->update('msk_post_discuss')
                ->set('discussNum','discussNum+1')
                ->where('id='.$parent['discussParentId'])
                ->execute();
            }else{
                $this->conn()->createQueryBuilder()->update('msk_post_discuss')
                ->set('discussNum','discussNum+1')
                ->where('id='.$id)
                ->execute();
            }
    
        }else {
            $data=array(
                'memberId'=>$user_id,
                'comPostId'=>$class_id,
                'discuss'=>$content,
                'grade'=>$rank,
                'type'=>1,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }
        return $this->conn()->insert('msk_post_discuss',$data);
    }
    
    //民宿
    protected function addhome($user_id,$class_id,$content,$rank,$id){
        if ($id){
            $parent=$this->conn()->createQueryBuilder()
            ->select("*")->from('msk_homestay_share_eval')->where('id='.$id)->execute()->fetch();
            $data=array(
                'member_id'=>$user_id,
                'homestay_id'=>$class_id,
                'eval'=>$content,
                'grade'=>$rank,
                'state'=>0,
                'pid'  =>$id,
                'reply_id'=>$parent['member_id'],
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }else {
            $data=array(
                'member_id'=>$user_id,
                'homestay_id'=>$class_id,
                'eval'=>$content,
                'grade'=>$rank,
                'state'=>0,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }
        return $this->conn()->insert('msk_homestay_share_eval',$data);
    }
    
    
    //义工
    protected function addyigong($user_id,$class_id,$content,$rank,$id){
        if ($id){
            $parent=$this->conn()->createQueryBuilder()
            ->select("*")->from('msk_tour_pal_comment')->where('id='.$id)->execute()->fetch();
            $data=array(
                'member_id'=>$user_id,
                'fid'=>$id,
                'for_id'=>$class_id,
                'content'=>$content,
                'grade'=>$rank,
                'type'=>2,
                'reply_id'=>$parent['member_id'],
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }else {
            $data=array(
                'member_id'=>$user_id,
                'for_id'=>$class_id,
                'content'=>$content,
                'grade'=>$rank,
                'type'=>2,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }
        return $this->conn()->insert('msk_tour_pal_comment',$data);
    }
    
    //导游
    protected function addDaoyou($user_id,$class_id,$content,$rank,$id){
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        $user=$this->conn()->createQueryBuilder()
        ->select("p.avatar,m.nickname")->from('msk_member','p')
        ->leftJoin('p','msk_member_info','m','p.id=m.member_id')
        ->where('p.id='.$user_id)->execute()->fetch();
        if ($id){
            $parent=$this->conn()->createQueryBuilder()
            ->select("*")->from('msk_guide_comment')->where('comment_id='.$id)->execute()->fetch();
            $data=array(
                'comment_user'=>$user_id,
                'username'=>$user['nickname'],
                'imageurl'=>$avatarPath.$user['avatar'],
                'guide_id'=>$class_id,
                'content'=>$content,
                'kind'=>0,
                'service_quality'=>$rank,
                'pid'  =>$id,
                'reply_id'=>$parent['comment_user'],
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }else {
            $data=array(
                'comment_user'=>$user_id,
                'username'=>$user['nickname'],
                'imageurl'=>$avatarPath.$user['avatar'],
                'guide_id'=>$class_id,
                'content'=>$content,
                'kind'=>0,
                'service_quality'=>$rank,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }
        return $this->conn()->insert('msk_guide_comment',$data);
    }
    
    
    //驴友团
    protected function addlvyoutuan($user_id,$class_id,$content,$rank,$id){
        if ($id){
            $parent=$this->conn()->createQueryBuilder()
            ->select("*")->from('msk_tour_pal_comment')->where('id='.$id)->execute()->fetch();
            $data=array(
                'member_id'=>$user_id,
                'fid'=>$id,
                'for_id'=>$class_id,
                'content'=>$content,
                'grade'=>$rank,
                'reply_id'=>$parent['member_id'],
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }else {
            $data=array(
                'member_id'=>$user_id,
                'for_id'=>$class_id,
                'content'=>$content,
                'grade'=>$rank,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
        }
        return $this->conn()->insert('msk_tour_pal_comment',$data);
    }
    
    
    
    /**
     * 旅友帮下帖子的点赞
     * @Route("apiAddTourPalLike", name="apiAddTourPalLike_")
     */
    public function apiAddTourPalLike(Request $request){
        $type=$request->get('type',1);
        $token=$request->get('token');
        $id=$request->get('id');
        $user_id=$this->validationToken($token);
        if(is_array($user_id)) $this->Send($user_id);
        switch ($type){
            case 1:
                $data=self::addhomelike($user_id,$id);
                break;
            case 2:
                $data=self::addjingdianlike($user_id,$id);
                break;
            case 3:
                $data=self::addyigonglike($user_id,$id);
                break;
            case 4:
                $data=self::addDaoyoulike($user_id,$id);
                break;
            case 5:
                $data=self::addlvyoutuanlike($user_id,$id);
                break;
            default:
                $this->Send(300,'非法操作！');
                break;
        }
        if ($data) $this->Send(200,'success');
        $this->Send(300,'fail');
    }
    
    //民宿
    protected function addhomelike($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_homestay_upvote')
        ->where('member_id='.$user_id,'homestay_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['upvote']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_homestay_upvote')
                ->set('upvote',0)->set('addtime',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'homestay_id='.$id)
                ->execute();
            }elseif($home['upvote']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_homestay_upvote')
                ->set('upvote',1)->set('addtime',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'homestay_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'homestay_id'=>$id,
                'upvote'=>1,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_homestay_upvote',$arr);
        }
        return $data;
    }
    
    //景点
    protected function addjingdianlike($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_follow_collect')
        ->where('member_id='.$user_id,'travel_note_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_upvote']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_follow_collect')
                ->set('is_upvote',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'travel_note_id='.$id)
                ->execute();
            }elseif($home['is_upvote']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_follow_collect')
                ->set('is_upvote',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'travel_note_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'travel_note_id'=>$id,
                'is_upvote'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_follow_collect',$arr);
        }
        return $data;
    }
    
    
    //义工
    protected function addyigonglike($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_cpost_like')
        ->where('memberId='.$user_id,'pId='.$id)->execute()->fetch();
        if ($home){
            $data=$this->conn()->createQueryBuilder()->delete('msk_cpost_like')
            ->where('memberId='.$user_id,'pId='.$id)
            ->execute();
        }else{
            $arr=array(
                'memberId'=>$user_id,
                'pId'=>$id,
                'addtime'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_cpost_like',$arr);
        }
        return $data;
    }
    
    //导游
    protected function addDaoyoulike($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_guide_collect')
        ->where('member_id='.$user_id,'guide_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_upvote']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_guide_collect')
                ->set('is_upvote',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'guide_id='.$id)
                ->execute();
            }elseif($home['is_upvote']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_guide_collect')
                ->set('is_upvote',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'guide_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'guide_id'=>$id,
                'is_upvote'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_guide_collect',$arr);
        }
        return $data;
    }
    
    
    //驴友团
    protected function addlvyoutuanlike($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_group_tour_collect')
        ->where('member_id='.$user_id,'tour_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_upvote']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_group_tour_collect')
                ->set('is_upvote',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'tour_id='.$id)
                ->execute();
            }elseif($home['is_upvote']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_group_tour_collect')
                ->set('is_upvote',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'tour_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'tour_id'=>$id,
                'is_upvote'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_group_tour_collect',$arr);
        }
        return $data;
    }
    
    /**
     * 旅友帮下帖子的收藏
     * @Route("apiAddTourPalCollect", name="apiAddTourPalCollect_")
     */
    public function apiAddTourPalCollect(Request $request){
        $type=$request->get('type',1);
        $token=$request->get('token');
        $id=$request->get('id');
        $user_id=$this->validationToken($token);
        if(is_array($user_id)) $this->Send($user_id);
        switch ($type){
            case 1:
                $data=self::addhomecollect($user_id,$id);
                break;
            case 2:
                $data=self::addjingdiancollect($user_id,$id);
                break;
            case 3:
                $data=self::addyigongcollect($user_id,$id);
                break;
            case 4:
                $data=self::addDaoyoucollect($user_id,$id);
                break;
            case 5:
                $data=self::addlvyoutuancollect($user_id,$id);
                break;
            default:
                $this->Send(300,'非法操作！');
                break;
        }
        if ($data) $this->Send(200,'success');
        $this->Send(300,'fail');
    }
    
    //民宿
    protected function addhomecollect($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_homestay_collect')
        ->where('member_id='.$user_id,'homestay_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_collect']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_homestay_collect')
                ->set('is_collect',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'homestay_id='.$id)
                ->execute();
            }elseif($home['is_collect']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_homestay_collect')
                ->set('is_collect',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'homestay_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'homestay_id'=>$id,
                'is_collect'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_homestay_collect',$arr);
        }
        return $data;
    }
    
    //景点
    protected function addjingdiancollect($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_follow_collect')
        ->where('member_id='.$user_id,'travel_note_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_collect']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_follow_collect')
                ->set('is_collect',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'travel_note_id='.$id)
                ->execute();
            }elseif($home['is_collect']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_follow_collect')
                ->set('is_collect',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'travel_note_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'travel_note_id'=>$id,
                'is_collect'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_follow_collect',$arr);
        }
        return $data;
    }
    
    
    //义工
    protected function addyigongcollect($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_community_collect')
        ->where('member_id='.$user_id,'comPostId='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_collect']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_community_collect')
                ->set('is_collect',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'comPostId='.$id)
                ->execute();
            }elseif($home['is_collect']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_community_collect')
                ->set('is_collect',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'comPostId='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'comPostId'=>$id,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_community_collect',$arr);
        }
        return $data;
    }
    
    //导游
    protected function addDaoyoucollect($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_guide_collect')
        ->where('member_id='.$user_id,'guide_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_collect']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_guide_collect')
                ->set('is_collect',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'guide_id='.$id)
                ->execute();
            }elseif($home['is_collect']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_guide_collect')
                ->set('is_collect',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'guide_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'guide_id'=>$id,
                'is_collect'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_guide_collect',$arr);
        }
        return $data;
    }
    
    
    //驴友团
    protected function addlvyoutuancollect($user_id,$id){
        $home=$this->conn()->createQueryBuilder()
        ->select("*")->from('msk_group_tour_collect')
        ->where('member_id='.$user_id,'tour_id='.$id)->execute()->fetch();
        if ($home){
            if ($home['is_collect']==1){
                $data=$this->conn()->createQueryBuilder()->update('msk_group_tour_collect')
                ->set('is_collect',0)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'tour_id='.$id)
                ->execute();
            }elseif($home['is_collect']==0){
                $data= $this->conn()->createQueryBuilder()->update('msk_group_tour_collect')
                ->set('is_collect',1)->set('add_time',$_SERVER['REQUEST_TIME'])
                ->where('member_id='.$user_id,'tour_id='.$id)
                ->execute();
            }
        }else{
            $arr=array(
                'member_id'=>$user_id,
                'tour_id'=>$id,
                'is_collect'=>1,
                'add_time'=>$_SERVER['REQUEST_TIME'],
            );
            $data=$this->conn()->insert('msk_group_tour_collect',$arr);
        }
        return $data;
    }
    
    

    /**
     * 驴友帮下的搜索帖子
     * @Route("apiTravelersGroupSearch",name="api_travelers_group_search")
     */
    public function apiTravelersGroupSearchAction(Request $request)
    {
        $offset = $request->get('offset',0);
        $search = $request->get('search','');
        $type   = $request->get('type',1);
        //$where='';
        $avatarPath = $this->getParameter('app_qiniu_imgurl');
        switch ($type){
            case 1:
                $orderlist = "p.is_rec = 1 ";
                if(trim($search) !== ''){
                    $orderlist.= ' and p.title like '.'"%'.$search.'%"'.' ';
                }
                $data=self::getTourPalRecList($orderlist,$offset,$avatarPath);
                break;
            case 2:
                $orderlist = "p.state=1";
                if(trim($search) !== ''){
                    $orderlist.= ' and p.homestay_title like '.'"%'.$search.'%"'.' ';
                }
                $data=self::gethomestayList($orderlist,$offset,$avatarPath);
                break;
            case 3:
                $orderlist = "c.is_default = 1 and p.state = 0 ";
                if(trim($search) !== ''){
                    $orderlist.= ' and p.travel_title like '.'"%'.$search.'%"'.' ';
                }
                $data=self::getjingdianList($orderlist,$offset,$avatarPath);
                break;
            case 4:
                $orderlist = "m.state=0";
                $address = $request->get('address','');
                $longitude = $request->get('longitude',0);
                $latitude = $request->get('latitude',0);
                $where=0;
                if(trim($search) !== ''){
                    $orderlist.= ' and m.title like '.'"%'.$search.'%"'.' ';
                }elseif(trim($address) !== ''){
                    $orderlist.= ' and m.address like '.'"%'.$address.'%"'.' ';
                }elseif (trim($longitude) && trim($latitude)){
                    $squares=self::getnearBy($longitude,$latitude);
                    $orderlist .="  and latitude<>0 and latitude>{$squares['right-bottom']['lat']} and latitude<{$squares['left-top']['lat']} and longitude>{$squares['left-top']['lng']} and longitude<{$squares['right-bottom']['lng']}";
                    $where="(($longitude - longitude)*($longitude - longitude) + ($latitude -latitude) * ($latitude - latitude))"." ,'ASC'";
                }
                //if ($type) $where='groupId='.$type;
                $data=self::getCPostList($orderlist,$offset,$avatarPath,$where);
                break;
            case 5:
                $orderlist = "p.type = 1 ";
                if(trim($search) !== ''){
                    $orderlist.= ' and p.title like '.'"%'.$search.'%"'.' ';
                }
                $data=self::getdaoyouList($orderlist,$offset,$avatarPath);
                break;
            case 6:
                $orderlist = "p.state > 0 ";
                if(trim($search) !== ''){
                    $orderlist.= ' and p.tour_title like '.'"%'.$search.'%"'.' ';
                }
                $data=self::getlvyoutuanList($orderlist,$offset,$avatarPath);
                break;
            default:
                $this->Send(300,'非法操作');
                break;
        }
        if(empty($data)) $this->Send(202,'暂无数据');
        $this->Send(200,'success',$data);
    }
    
    
    /**
     * 驴友帮推荐列表
     * **/
    protected function getTourPalRecList($orderlist,$offset,$avatarPath,$where=''){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.id','p.for_id', 'p.member_id','p.title','p.addtime','p.avatar','p.nickname','p.image_url','p.type')
        ->from('msk_tour_pal_rec', 'p')
        ->where($orderlist)
        ->setFirstResult($offset)
        ->setMaxResults(10)
        ->orderBy('p.addtime','DESC')
        ->execute()->fetchAll();
        $tmp=array();
        if ($data){
            foreach ($data as $k=>$v){
                if ($v['type']==1){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_homestay_share_eval")
                    ->where('state=0','homestay_id='.$v['for_id'],'pid=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==2){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_post_discuss")
                    ->where('type=1','comPostId='.$v['for_id'],'discussParentId=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==3){
                    $comment=$conn->createQueryBuilder()
                    ->select("discussNum as total")
                    ->from("msk_community_post")
                    ->where('id='.$v['for_id'])
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==4){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(comment_id) as total,avg(service_quality) as avge")
                    ->from("msk_guide_comment")
                    ->where('guide_id='.$v['for_id'],'pid=0','kind=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==5){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_tour_pal_comment")
                    ->where('is_show=1 and type=1 ','for_id='.$v['for_id'],'fid=0')
                    ->execute()
                    ->fetch();
                }
    
                $tmp[$k]['id']=$v['for_id'];
                $tmp[$k]['type']=$v['type'];
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['addtime']);
                $tmp[$k]['avatar'] = $avatarPath . $v['avatar'];
                $tmp[$k]['nickname'] =$v['nickname'];
                $tmp[$k]['img'] = $avatarPath.$v['image_url'];
                $tmp[$k]['comment_count']=$comment['total'];
            }
        }
        return $tmp;
    }
    

    
    
    /**
     * 义工帖子列表
     * **/
    protected function getCPostList($orderlist,$offset,$avatarPath,$where=0){
        if ($where){
            $groupList = $this->conn()->createQueryBuilder()
            ->select("m.id,m.groupId as gid,m.memberId,m.longitude,m.latitude,m.title,m.content,m.discussNum,m.addTime ,mber.avatar,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->where($orderlist)
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->orderBy($where)
            ->execute()
            ->fetchAll();
        }else{
            $groupList = $this->conn()->createQueryBuilder()
            ->select("m.id,m.groupId as gid,m.memberId,m.title,m.content,m.discussNum,m.addTime ,mber.avatar,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->where($orderlist)
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->orderBy("m.addTime ,'DESC'")
            ->execute()
            ->fetchAll();
        } 
        $tmp=array();
        if ($groupList){
            for ($i = 0; $i < count($groupList); $i ++) {
                $commid = $groupList[$i]['id'];
                $tmp[$i]['id']=$commid;
                $tmp[$i]['type']=3;
                $tmp[$i]['member_id']=$groupList[$i]['memberId'];
                $tmp[$i]['title']=$groupList[$i]['title'];
                $tmp[$i]['addtime']=date('m月d日  H:i',$groupList[$i]['addTime']);
                $tmp[$i]['avatar'] = $avatarPath . $groupList[$i]['avatar'];
                $tmp[$i]['nickname'] =$groupList[$i]['nickname'];
                if ($where){
                    $tmp[$i]['longitude'] =$groupList[$i]['longitude'];
                    $tmp[$i]['latitude'] =$groupList[$i]['latitude'];
                }
                // 查询贴图片
                $group_comm_img_list = $this->conn()->createQueryBuilder()
                ->select("m.*")
                ->from('msk_group_img', 'm')
                ->where("m.groupId=$commid")
                ->execute()->fetch();
                if ($group_comm_img_list) {
                    $group_comm_img_list=explode(';',$group_comm_img_list['imageName']);
                    foreach ($group_comm_img_list as $k=>$v){
                        $groupArray[$k] = $avatarPath. $v;
                    }                    
                    $tmp[$i]['img'] = $groupArray[0];
                } else {
                    $tmp[$i]['img'] = '';
                }
                $tmp[$i]['comment_count']=$groupList[$i]['discussNum'];
            }
        }
        return $tmp;
    }
    
    
    
    /**
     * 驴友帮导游列表
     * **/
    protected function getdaoyouList($orderlist,$offset,$avatarPath,$where=''){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.title', 'p.member_id','p.guide_id','p.add_time','p.image_url','m.avatar','e.nickname')
        ->from('msk_guide', 'p')
        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where($orderlist)
        ->setFirstResult($offset)
        ->setMaxResults(10)
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
                $tmp[$k]['id']=$v['guide_id'];
                $tmp[$k]['type']=4;
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['add_time']);
                $tmp[$k]['avatar'] = $avatarPath . $v['avatar'];
                $tmp[$k]['nickname'] =$v['nickname'];
                $img=explode(';',$v['image_url']);
                $tmp[$k]['img'] = $avatarPath.$img[0];
                $tmp[$k]['comment_count']=$comment['total'];
            }
        }
        return $tmp;
    }
    
    /**
     * 驴友帮驴友团列表
     * **/
    protected function getlvyoutuanList($orderlist,$offset,$avatarPath,$where=''){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.tour_id', 'p.imgurl','p.member_id','p.tour_title','p.addtime','m.avatar','e.nickname')
        ->from('msk_group_tour', 'p')
        ->leftjoin('p', 'msk_member', 'm', 'p.member_id = m.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where($orderlist)
        ->setFirstResult($offset)
        ->setMaxResults(10)
        ->orderBy('p.addtime', 'desc')
        ->execute()->fetchAll();
        $tmp=array();
        if ($data){
            foreach ($data as $k=>$v){
                $comment=$conn->createQueryBuilder()
                ->select("count(id) as total,avg(grade) as avge")
                ->from("msk_tour_pal_comment")
                ->where('is_show=1 and type=1 ','for_id='.$v['tour_id'],'fid=0')
                ->execute()
                ->fetch();
                $tmp[$k]['id']=$v['tour_id'];
                $tmp[$k]['type']=5;
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['tour_title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['addtime']);
                $tmp[$k]['avatar'] = $avatarPath . $v['avatar'];
                $tmp[$k]['nickname'] =$v['nickname'];
                $tmp[$k]['img'] = $avatarPath.$v['imgurl'];
                $tmp[$k]['comment_count']=$comment['total'];
            }
        }
        return $tmp;
    }
    
    /**
     * 附近算法
     * **/
    private function getnearBy($lng,$lat){
        $distance = 1;//范围（单位千米）
        //$lat = 113.873643;
        //$lng = 22.573969;
        define('EARTH_RADIUS', 6371);//地球半径，平均半径为6371km
        $dlng = 2 * asin(sin($distance / (2 * EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);
        $dlat = $distance/EARTH_RADIUS;
        $dlat = rad2deg($dlat);
        $squares = array('left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
        return $squares;
    }
    
    

}
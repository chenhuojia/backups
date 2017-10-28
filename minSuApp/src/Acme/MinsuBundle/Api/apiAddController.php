<?php
/**
 * Created by Zend Studio
 * User: Administrator
 * Date: 2016-5-30
 * Time: 14:08
 */
namespace Acme\MinsuBundle\Api;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Acme\MinsuBundle\Entity\Homestay;
use Acme\MinsuBundle\Entity\ShopApply;
use Acme\MinsuBundle\Entity\MallGoods;
use Acme\MinsuBundle\Entity\UserAddress;
use Acme\MinsuBundle\Entity\TravelNote;
use Acme\MinsuBundle\Entity\TravelNoteImage;
use Acme\MinsuBundle\Entity\CPost;
use Acme\MinsuBundle\Entity\Guide;
use Acme\MinsuBundle\Entity\GroupTour;
use Acme\MinsuBundle\Common\CommonController;
include '../vender/server-sdk-php-master/API/rongcloud.php';
class apiAddController extends CommonController
{
    

   /*   public function apiAddShare(Request $request){
        
    }
     */
    
    /**
     * 分享民宿/上传民宿
     * @Route("/apiAddHomeStay",name="_api_add_homeStay")
     */
    public function apiAddHomeStayAction()
    {
        $data = isset($_POST) ? $_POST : '';
        self::validationToken($data['token']);
        //$this->Send($_SESSION);
        if (empty($data))
        {
            $this->Send(300,'参数有误');
        } else {
            $manager = $this->getDoctrine()->getManager();
            $man = isset($data['is_manage']) ? $data['is_manage'] : 0;
            if ($man == 1) {
                //判断该用户已经认证过民宿了
                $member=$this->IsMemberToAuthenticate($data['member_id']);
                if($member) $this->Send(300,'该用户已经申请认证过了');
                $statee = 0;
            } else {
                $statee = 1;
            }
            $data['bottom_price'] =isset($data['bottom_price'])?$data['bottom_price']:0;
            $data['homestay_phone'] =isset($data['homestay_phone'])?$data['homestay_phone']:0;
            $data['geohash'] =self::addGeohash($data['longitude'],$data['latitude']);
            $data['homestay_name']=isset($data['homestay_name'])?$data['homestay_name']:$data['homestay_title'];
            $hsdata = new Homestay();
            $hsdata->setMember_id($this->user_id);
            $hsdata->setHomestay_name($data['homestay_name']);
            $hsdata->setHomestay_title($data['homestay_title']);
            $hsdata->setHomestay_type_id($data['homestay_type_id']);
            $hsdata->setBottom_price($data['bottom_price']);
            $hsdata->setHomestay_phone($data['homestay_phone']);
            $hsdata->setHomestay_addr($data['homestay_addr']);
            $hsdata->setDscp(isset($data['dscp']) ? $data['dscp'] : null);
            $hsdata->setaddtime(time());
            $hsdata->setRepast(isset($data['repast']) ? $data['repast'] : 0);
            $hsdata->setInvoice(isset($data['invoice']) ? $data['invoice'] : 0);
            $hsdata->setReception_time(isset($data['reception_time']) ? $data['reception_time'] : null);
            $hsdata->setIs_manage($man);
            $hsdata->setState($statee);
            // new fileds
            $hsdata->setUpload_ip($_SERVER['REMOTE_ADDR']);
            $hsdata->setUpload_terminal(isset($data['upload_terminal']) ? $data['upload_terminal'] : 0);
            $hsdata->setLongitude(isset($data['longitude']) ? $data['longitude'] : null);
            $hsdata->setLatitude(isset($data['latitude']) ? $data['latitude'] : null);
            $hsdata->setGeohash(isset($data['geohash']) ? $data['geohash'] : "");
            $hsdata->setProvince(isset($data['province']) ? $data['province'] : null);
            $hsdata->setCity(isset($data['city']) ? $data['city'] : null);
            $hsdata->setDistrict(isset($data['district']) ? $data['district'] : null);
            if($data['image_url'])  $hsdata->setImageUrl($data['image_url']);
            if($data['video_url'])  $hsdata->setVideoUrl($data['video_cover_url'].';'.$data['video_url']);
            $manager->persist($hsdata);
            $manager->flush();
            $bool = $hsdata->getId();
            if(is_numeric($bool)){
                self::addmynotecount($this->user_id,1,0,0,0,0);
                if($data['image_url'])  $img=explode(';',$data['image_url']);
                else $img=array(0);
                self::addmynote($this->user_id,1,$bool,$data['homestay_name'],$img[0]);
                $point=self::getpoint("民宿");
                $b=self::addMemberPoints($this->user_id,$point['val'],$point['title']."({$data['homestay_name']})",0);
                $this->Send(200,'success');
            }else {
                $this->Send(300,'fail');
            }
        }
    }

     
    /**
     * 分享景点
     * @Route("apiUploadTravelNote", name="apiUploadTravelNote_")
     */
    public function apiUploadTravelNoteAction(Request $request)
    {   
        self::validationToken($request->get('token'));
        if (!$coverImg = $request->get('cover_img')) $this->Send(300,'not found cover image');
        if (!$title = $request->get('title')) $this->Send(300,'not found title!');
        if (!$content = $request->get('content')) $this->Send(300,'not found content!');
        $contentImg = $request->get('content_img');
        $travel_id = $request->get('travel_id',0);
        $homestay_id = $request->get('homestay_id',0);
        $addr = $request->get('addr');
        $homestay = $request->get('homestay');
        $ip = $_SERVER['REMOTE_ADDR'];
        $longitude = $request->get('longitude');
        $latitude = $request->get('latitude');
        $province = $request->get('province');
        $city = $request->get('city');
        $district = $request->get('district');
        $video = $request->get('video');
        $video_cover = $request->get('video_cover');
        try {
            $travelNote = new TravelNote();
            $travelNote->setMemberId($this->user_id);
            $travelNote->setTravelTitle($title);
            $travelNote->setTravelContent($content);
            $travelNote->setUploadIp("$ip");
            $travelNote->setPay_money(0);
            $travelNote->setMark_travel_id($travel_id);
            $travelNote->setState(0);
            $travelNote->setAddtime(time());
            if ($addr)      $travelNote->setAddr($addr);
            if ($longitude) $travelNote->setLongitude($longitude);
            if ($latitude)  $travelNote->setLatitude($latitude);
            if ($province)  $travelNote->setProvince($province);
            if ($city)      $travelNote->setCity($city);
            if ($district)  $travelNote->setDistrict($district);
            if ($homestay_id)
            {
                $travelNote->setRecommendHomestay($homestay);
                $travelNote->setHomestay_id($homestay_id);
                 
            }else{
                $travelNote->setHomestay_id(0);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($travelNote);
            $em->flush();
            $travelNoteId = $travelNote->getId();
            $point=self::getpoint("景点");
            self::addmynote($this->user_id,2,$travelNoteId,$title,$coverImg);
            self::addmynotecount($this->user_id,0,1,0,0,0);
            $b=self::addMemberPoints($this->user_id,$point['val'],$point['title']."($title)",0);
            $conn = $em->getConnection();
            $contentImg = json_decode($contentImg,true);
            if ($contentImg){
                $contentImgLength = count($contentImg);
                for ($i = 0; $i < $contentImgLength; $i++) {
                    try {
                        $travelNoteImage = new TravelNoteImage();
                        $travelNoteImage->setTravelNoteId($travelNoteId);
                        $travelNoteImage->setTravelNoteImage("$contentImg[$i]");
                        $travelNoteImage->setIsDefault('0');
                        $travelNoteImage->setTravelNoteImageSort($i);
                        $travelNoteImage->setAddTime(time());
                        $em->persist($travelNoteImage);
                        $em->flush();
                    } catch (Exception $e) {
                        $this->Send(300,$e->getMessage());
                    }
                }
            }
            if ($video){
                try {
                    $travelNoteImage = new TravelNoteImage();
                    $travelNoteImage->setTravelNoteId($travelNoteId);
                    $travelNoteImage->setTravelNoteImage("$video");
                    $travelNoteImage->setIsDefault('3');
                    $travelNoteImage->setTravelNoteImageSort(0);
                    $travelNoteImage->setAddTime(time());
                    $em->persist($travelNoteImage);
                    $em->flush();
                     
                    $travelNoteImage = new TravelNoteImage();
                    $travelNoteImage->setTravelNoteId($travelNoteId);
                    $travelNoteImage->setTravelNoteImage("$video_cover");
                    $travelNoteImage->setIsDefault('2');
                    $travelNoteImage->setTravelNoteImageSort(0);
                    $travelNoteImage->setAddTime(time());
                    $em->persist($travelNoteImage);
                    $em->flush();
                } catch (Exception $e) {
                    return new JsonResponse($this->failMassageReturnAction($e->getMessage()));
                }
            }
            try {
                $travelNoteImage = new TravelNoteImage();
                $travelNoteImage->setTravelNoteId($travelNoteId);
                $travelNoteImage->setTravelNoteImage("$coverImg");
                $travelNoteImage->setIsDefault('1');
                $travelNoteImage->setTravelNoteImageSort(0);
                $travelNoteImage->setAddTime(time());
                $em->persist($travelNoteImage);
                $em->flush();
                $this->Send(200,'success');
            } catch (Exception $e) {
                $this->Send(300,$e->getMessage());
            }
        } catch(Exception $e) {
            $this->Send(300,$e->getMessage());
        }
    }

    /**
     * 驴友帮义工发帖
     * @Route("apiPublishPosts", name="apiPublishPosts_")
     */
    public function apiPublishPostsAction(Request $request)
    {   
        self::validationToken($request->get('token'));
        $manager = $this->getDoctrine()->getManager();
        //$group_id=$request->get('group_id');
        $group_id=20;
        //if (empty($group_id)) $this->Send(300,'分组不存在');
        if (!$request->get('cover_image')) $this->Send(300,'请插入封面');
       // $data=self::FindGroupName($group_id);
        $home=0;
        //if ($data['groupName']=="义工") {
        $home=self::IsMemberToAuthenticate($this->user_id);
        if (empty($home)) $this->Send(300,'你还没有开通民宿，请先开通民宿后再来找义工');
        //}
        $title=$request->get('title');
        $post = new CPost();
        $post->setGroupId($group_id);
        $post->setMemberId($this->user_id);
        $post->setContent($request->get('content'));
        $post->setLikeNum(0);
        $post->setDiscussNum(0);
        $post->setAddtime(time());
        $post->setTitle($title);
        $post->setLongitude($request->get('longitude'));
        $post->setLatitude($request->get('latitude'));
        $post->setAddress($request->get('address'));
        $post->setAtt($request->get('att',0));
        $post->setHomestayId($home);
        $manager->persist($post);
        $manager->flush();
        $commid = $post->getId();
        $cover_img=$request->get('cover_image');
        $image=$request->get('image',0);
        $video_cover=$request->get('video_cover');
        $video=$request->get('video');
        if ($image)  $img=$cover_img.';'.$image;
        else $img=$cover_img;
        if ($video_cover) $vid=$video_cover.';'.$video;
        else $vid='';
        $im=array(
            'groupId'=>$commid,
            'memberId'=>$this->user_id,
            'imgType'=>0,
            'imageName'=>$img,
            'video'=>$vid,
            'addTime'=>time(),
        );
        if($commid){
            $d=$this->conn()->insert('msk_group_img',$im);
            self::addmynote($this->user_id,3,$commid,$title,$cover_img);
            self::addmynotecount($this->user_id,0,0,1,0,0);
            if ($d) $this->Send(200,'success');
        }
        $this->Send(300,'发表失败');   
    }
    
    /**
     * 添加民宿评论
     * @Route("/apiAddHomeStayComment", name="apiAddHomeStayComment_")
     */
    public function apiAddHomeStayComment(Request $request){
        $token=$request->get('token');
        $user_id=$this->validationToken($token);
        if(is_array($user_id)) return new JsonResponse($user_id);
        $rank=$request->get('rank');
        $homestay_id=$request->get('homestay_id');
        $content=$request->get('content');
        if (!$content) return new JsonResponse(array('status'=>0,"error"=>1,'msg'=>'评论内容不能为空'));
        if (!$homestay_id) return new JsonResponse(array('status'=>0,"error"=>1,'msg'=>'民宿不存在'));
        // $user=$this->conn()->createQueryBuilder()
        //      ->select('p.avatar,m.nickname')
        //      ->from('msk_member','p')
        //      ->leftjoin('p','msk_member_info','m','p.id=m.member_id')
        //      ->where('p.id='.$user_id)
        //      ->execute()->fetch();
        // $data=array(
        //     'user_id'=>$user_id,
        //     'user_name'=>$user['nickname'],
        //     'avator'=>$user['avatar'],
        //     'homestay_id'=>$homestay_id,
        //     'content'=>$content,
        //     'rank'=>$rank,
        //     'addtime'=>time(),
        // );
        // $res=$this->conn()->insert('msk_homestay_comment',$data);
        $data=array(
            'member_id'=>$user_id,
            'homestay_id'=>$homestay_id,
            'eval'=>$content,
            'grade'=>$rank,
            'state'=>0,
            'addtime'=>time(),
        );
        $res=$this->conn()->insert('msk_homestay_share_eval',$data);
        if ($res) $this->Send(200,'评论成功');
        $this->Send(300,'评论失败');
    }

    

    
    /**
     * 分享景点
     * @Route("apiqqq", name="apiqqq_")
     */
    public function apiqqq(){
        //self::addmynotecount(1,0,0,1,0,0);
        $redis = new \redis();
        $redis->connect('127.0.0.1', 6379);
        $redis->set('myname','ikodota');
        $a=$redis->get('myname');
        $this->Send($a);
    }
}   
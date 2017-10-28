<?php
namespace Acme\MinsuBundle\Api;

use Acme\MinsuBundle\Entity\Homestay;
use Acme\MinsuBundle\Entity\Images;
use Acme\MinsuBundle\Entity\Room;
use Acme\MinsuBundle\Entity\CPost;
use Acme\MinsuBundle\Entity\GroupImg;
use Acme\MinsuBundle\Entity\RoomServerRalation;
use Acme\MinsuBundle\Entity\PostDiscuss;
use Acme\MinsuBundle\Entity\MemberLikePost;
use Acme\MinsuBundle\Entity\MemberAttentGroup;
use Acme\MinsuBundle\Entity\Msg;
use Acme\MinsuBundle\Entity\RestockOfHomestay;
use Acme\MinsuBundle\Entity\PointsGoodsOrder;
use Acme\MinsuBundle\Entity\BuyerCoupon;
use Acme\MinsuBundle\Entity\HShareEval;
use Acme\MinsuBundle\Entity\MemberLikeDiscuss;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query\AST\Functions\CurrentDateFunction;
use Acme\MinsuBundle\Entity\RoomServerRelation;
use Symfony\Component\Validator\Constraints\Currency;

class apiHomestayController extends apiOrderController
{

    public function __construct(){
        
    }
    
    
    


    
    
    
    
    /**
     * @Route("/jsonText",name="jsonText_")
     */
    public function jsonTextAction()
    {
        $data['image'] = '[{"imgArr":"a1","is_default":"6","imgDscp":"000"},{"imgArr":"a1","is_default":"3","imgDscp":"000"}]';
        // print_r(json_decode($data['img']));$data['img'][0]['imgArr'];exit();
        
        $a = $this->is_json($data['image']);
        if ($a) {
            echo '1';
        } else {
            echo '0';
        }
        exit();
        print_r($data['image']);
        $data['img'][0]['imgArr'];
    }

    /**
     * @Route("/aapiAddHomeStay",name="_aapi_add_homeStay")
     */
/*     public function apiAddHomeStayAction()
    {
        $data = isset($_POST) ? $_POST : '';
        if (empty($data)) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            $manager = $this->getDoctrine()->getManager();
            
            $man = isset($data['is_manage']) ? $data['is_manage'] : 0;
            
            if ($man == 1) {
                
                $statee = 0;
            } else {
                $statee = 1;
            }
            $data['bottom_price'] =isset($data['bottom_price'])?$data['bottom_price']:0;
            $data['homestay_phone'] =isset($data['homestay_phone'])?$data['homestay_phone']:0;
            $data['geohash'] =$this->addGeohash($data['longitude'],$data['latitude']);

            $hsdata = new Homestay();
            $hsdata->setMember_id($data['member_id']);
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
            $hsdata->setUpload_ip(isset($data['upload_ip']) ? $data['upload_ip'] : 0);
            $hsdata->setUpload_terminal(isset($data['upload_terminal']) ? $data['upload_terminal'] : 0);
            $hsdata->setLongitude(isset($data['longitude']) ? $data['longitude'] : null);
            $hsdata->setLatitude(isset($data['latitude']) ? $data['latitude'] : null);
            $hsdata->setGeohash(isset($data['geohash']) ? $data['geohash'] : "");
            $hsdata->setProvince(isset($data['province']) ? $data['province'] : null);
            $hsdata->setCity(isset($data['city']) ? $data['city'] : null);
            $hsdata->setDistrict(isset($data['district']) ? $data['district'] : null);
            if($data['image_url']) $hsdata->setImageUrl($data['image_url']); 
            if($data['video_url'])  $hsdata->setVideoUrl($data['video_cover_url'].';'.$data['video_url']);  
            $manager->persist($hsdata);
            $manager->flush();
            $bool = $hsdata->getId();
            if(is_numeric($bool)){
                $point=self::getpoint("民宿");
                $b=self::addMemberPoints($memberId,$point['val'],$point['title']."({$data['homestay_name']})",0);
                return new JsonResponse(array('code'=>200,'msg'=>'分享成功','result'=>''));
            }else {
                return new JsonResponse(array('code'=>300,'msg'=>'分享错误','result'=>''));
            }
        } */

            // 上传图片
            // && is_array($data['image'])
           // $isJson = $this->is_json($data['image']);

            
        //     if (isset($data['image']) && $data['member_id'] > 0 && $isJson) {
        //         $manager->persist($hsdata);
        //         $manager->flush();
        //         $homestay_room_id = $hsdata->getId();
                
        //         // S民宿相关图片
        //         $ItemName = 'HomeStay';
        //         $imgbool = $this->imagePost($data['member_id'], $data['image'], $homestay_room_id, $ItemName, $imgTpe = '0');
                
        //         if (! $imgbool) {
        //             $message['status'] = 0;
        //             $message['error'] = 1;
        //             $message['message'] = 'UploadImage Error!';
        //             return new JsonResponse($message);
        //         }
        //         // E民宿相关图片
                
        //         /**
        //          * ***********Start 上传的身份证与民宿出租证明******************
        //          */
        //         $isma = isset($data['is_manage']) ? $data['is_manage'] : 0;
        //         if ($isma == '1') {
        //             $pOSItemName = "Poster";
        //             $posimgbool = $this->imagePost($data['member_id'], $_POST['PosterImage'], $homestay_room_id, $pOSItemName, $imgTpe = '2');
        //             if (! $posimgbool) {
        //                 $message['status'] = 0;
        //                 $message['error'] = 1;
        //                 $message['message'] = 'UploadImage Error 002!';
        //                 return new JsonResponse($message);
        //             }
        //         }
        //     /**
        //      * ************End 上传的身份证与民宿出租证明***************
        //      */
        //     } else {
        //         $message['status'] = 0;
        //         $message['error'] = 1;
        //         $message['message'] = 'No Image!';
        //         return new JsonResponse($message);
        //     }
            
        //     $message['status'] = 1;
        //     $message['error'] = 0;
        //     $message['message'] = 'Add Success!';
        //     $message['homesaty_id'] = $homestay_room_id;
        
        // return new JsonResponse($message);
    //}

    function is_json($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
    
    // Start----------上传民宿视频Video------------------
    /**
     * @Route("/testuploadVideoFile",name="testuploadVideoFile_")
     */
    public function testuploadVideoFileAction()
    {
        $data = isset($_POST) ? $_POST : '';
        if (empty($data)) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
            return new JsonResponse($message);
        }
        
        if (isset($data['video'])) {
            $ItemFile = 'Video';
            $videobool = $this->imagePost($data['member_id'], $data['video'], $data['homestayid'], $ItemFile, $imgTpe = '3');
            if (! $videobool) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Upload video Error!';
                return new JsonResponse($message);
            }
        }
        $message['status'] = 1;
        $message['error'] = 0;
        $message['message'] = 'video Success ';
        
        return new JsonResponse($message);
    }

    /**
     * @Route("/apiUploadVideoFile",name="api_upload_video_file")
     */
    public function apiUploadVideoFileAction()
    {
        
        // if(isset($data['video'])){
        // $ItemFile ='Video';
        // $videobool =$this->imagePost($data['member_id'], $data['video'],$homestay_room_id,$ItemFile,$imgTpe='3');
        // if(!$videobool){
        // $message['status'] = 0;
        // $message['error'] = 1;
        // $message['message'] = 'Upload video Error!';
        // return new JsonResponse($message);
        // }
        
        // }
        $ItemFile = 'Video';
        $imgTpe = '3';
        
        $savePath = $this->container->getParameter('cachePath');
        // $data =array('last'=>20,
        // 'member_id'=>8,
        // 'video'=>'AAAAHGZ0eXBtcDQyAAAAAW1wNDFtcDQyaXNvbQAAC+Btb292AAAAbG12aGQAAAAA',
        // 'homestay_id'=>'4444',
        // );
        
        $data = isset($_POST) ? $_POST : '';
        if (empty($data)) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            $memberid = $data['member_id'];
            $homeid = $data['homestay_id'];
            $videoText = $data['video'];
            $last = $data['last'];
            if (! is_dir($savePath))
                mkdir($savePath);
            
            $checkFile = $savePath . $memberid . "_" . "$homeid" . ".txt";
            if ($last == "10") {
                
                if (file_exists($checkFile)) {
                    $file = fopen($savePath . $memberid . "_" . "$homeid" . ".txt", 'a+'); // 继续写入
                    fwrite($file, $videoText);
                    fclose($file);
                } else { // 不存在首次写入
                    $file = fopen($savePath . $memberid . "_" . "$homeid" . ".txt", 'w+');
                    fwrite($file, $videoText);
                    fclose($file);
                }
                
                $message['message'] = 'Write Success!';
            }
            
            if ($last == "20") {
                
                if (file_exists($checkFile)) {
                    $file = fopen($savePath . $memberid . "_" . "$homeid" . ".txt", 'a+'); // 继续写入
                    fwrite($file, $videoText);
                    
                    header("content-type:text/html;charset=utf-8");
                    $conn = file_get_contents($checkFile);
                    $conn = str_replace("rn", "<br/>", file_get_contents($checkFile));
                    // echo $conn;exit();
                    
                    // $videoDataText ='[{"imgArr":"'.$conn.'"}]';
                    
                    // $arrat['imgArr']=$conn;
                    $arry = array(
                        array(
                            'imgArr' => $conn
                        )
                    );
                    $videoDataText = json_encode($arry);
                    
                    // echo $videoDataText;exit();
                    
                    $ItemFile = 'Video';
                    $videobool = $this->imagePost($memberid, $videoDataText, $homeid, $ItemFile, $imgTpe = '3');
                    
                    if (! $videobool) {
                        $message['status'] = 0;
                        $message['error'] = 1;
                        $message['message'] = 'Upload video Error!';
                        return new JsonResponse($message);
                    }
                    
                    fclose($file);
                    
                    // unlink($checkFile); //删除文件
                } else {
                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['message'] = 'video file no exists!';
                    return new JsonResponse($message);
                }
                
                $message['message'] = 'video Success ';
            }
            
            if ($last != "10" && $last != "20") {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Upload video Error!';
                return new JsonResponse($message);
            }
            
            $message['status'] = 1;
            $message['error'] = 0;
        }
        return new JsonResponse($message);
    }
    // End------------上传民宿视频Video------------------
    
    // 民宿类型
    /**
     * @Route("/apiGetHomeStayType",name="apiGetHomeStayType_")
     */
    public function apiGetHomeStayTypeAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select('m.*')
            ->from('msk_homestay_type', 'm')
            ->execute();
        $data = $query->fetchAll();
        
        // print_r($data); exit;
        return new JsonResponse($data);
    }
    
    // 首页民宿数据接口
    /*
     * @Route("/apiGetHomeStay",name="apiGetHomeStay_")
     */
    /*
     * public function apiGetHomeStayAction() {
     * $conn = $this->getDoctrine()->getManager()->getConnection();
     * $query = $conn->createQueryBuilder()
     * ->select(
     * 'm.id,m.homestay_name,m.homestay_title,m.bottom_price,m.homestay_addr,mm.id memberid,
     * mm.avatar,mm.is_owner')
     * ->from('msk_homestay', 'm')
     * // ->leftjoin('m','msk_images','img','m.id=img.homestay_room_id')
     * ->leftjoin('m','msk_member','mm','m.member_id=mm.id')
     * ->where('m.state=1') //1闂傚倸鍊搁崐椋庢閿熺姴纾婚柛娑卞枤閳瑰秹鏌ц箛姘兼綈鐎规洘鐓￠弻銊╂偆閸屾稑顏�
     * ->andWhere('mm.member_state=1')//1闂備浇顕х�涒晠顢欓弽顓為棷妞ゆ牜鍋涚粻顖炴煟閹达絽袚闁绘挻娲橀幈銊ノ熼悡搴′粯闂佽楠忕紞渚�寮婚埄鍐╁闂傚牊鍐婚幏宄扳攽鐎ｎ剙绁﹂柣搴秵閸犳寮查幖浣圭叆闁绘洖鍊圭�氾拷
     * //->andWhere('img.is_default=1')//1濠电姵顔栭崰妤冩暜濡ゅ啰鐭欓柟鐑樸仜閿熸枻绠撳畷濂稿Ψ椤旇姤娅嶅┑鐘垫暩婵敻鎳濇ィ鍐╁�峰┑鐘叉处閻撴洟鏌熼悙顒夋當闁哥姵宀稿鎶芥晸閿燂拷
     * //->andWhere('img.img_type=0')//0婵犵數濮甸鏍窗濡ゅ嫭鎳岄梻浣规偠閸斿酣寮繝姘ワ拷浣糕枎閹惧啿鍞ㄥ銈嗘尵閸嬬喖宕愰悙鐑樷拺闁告稑锕ョ�垫瑩鏌涢幇顔间壕婵綇鎷�
     * ->orderBy("m.addTime","DESC")
     * ->execute();
     * $data = $query->fetchAll();
     *
     * $Path =$this->container->getParameter('app_image_path');
     * $noneImg =$this->container->getParameter('none_img_homestay');
     * $Avaterpath =$this->container->getParameter('app_avater_path');
     *
     * $proName ="HomeStay";
     *
     * //闂傚倸鍊风粈渚�骞栭銈嗗仏妞ゆ劧绠戠壕鍧楁煙缂併垹娅橀柡浣割儐娣囧﹪濡堕崟顓＄獥濠电偞鎹侀崺鏍ㄧ┍婵犲浂鏁嶆繛鎴濆船閸斿矂姊洪崨濠呭婵炲樊鍙冨璇测槈閵忕姈銊╂煏婢跺牆鍔楅柟绋垮暞缁绘繈鍩涢敓钘夘吋閸℃浼�
     * for ($i=0 ;$i<count($data);$i++){
     * $id =$data[$i]['id'];
     * $avatar=$data[$i]['avatar'];
     * $memberid =$data[$i]['memberid'];
     * //
     * $queryImg = $conn->createQueryBuilder()
     * ->select('m.*')
     * ->from('msk_images', 'm')
     * ->where("m.homestay_room_id=$id")
     * ->andWhere('m.is_default=1')//1濠电姵顔栭崰妤冩暜濡ゅ啰鐭欓柟鐑樸仜閿熸枻绠撳畷濂稿Ψ椤旇姤娅嶅┑鐘垫暩婵敻鎳濇ィ鍐╁�峰┑鐘叉处閻撴洟鏌熼悙顒夋當闁哥姵宀稿鎶芥晸閿燂拷
     * ->andWhere('m.img_type=0')//0婵犵數濮甸鏍窗濡ゅ嫭鎳岄梻浣规偠閸斿酣寮繝姘ワ拷浣糕枎閹惧啿鍞ㄥ銈嗘尵閸嬬喖宕愰悙鐑樷拺闁告稑锕ョ�垫瑩鏌涢幇顔间壕婵綇鎷�
     * ->execute();
     * $Imgdata = $queryImg->fetchAll();
     * //濠电姵顔栭崰妤冩暜濡ゅ啰鐭欓柟鐑樸仜閿熸枻绠撳畷濂稿Ψ椤旇姤娅嶇紓鍌氬�烽悞锕傗�﹂崶顒�鐓濋柡鍐ㄧ墛閸嬶綁鏌涢妷鎴濆暟妤犲洨绱撴担绋夸喊闁瑰嚖鎷�
     * if($Imgdata){
     * $imgArray =current($Imgdata);
     *
     * $data[$i]['homeStayDefultImg']=$Path.$proName.'/'.$memberid.'/'.$imgArray['goods_image'];}else {
     *
     * $data[$i]['homeStayDefultImg']=$Path.$noneImg;
     * }
     *
     * //闂傚倸鍊烽懗鍫曞磿閻㈢纾婚柟鎹愵嚙缁�澶岋拷骞垮劚椤︻垶寮伴妷锔剧闁瑰鍋熼幊鍐磼閸撲礁浠遍柡灞稿墲瀵板嫮锟斤綆浜炴禒鎾⒑閸涘﹥鎯堥柟鍑ゆ嫹
     * if($avatar){
     * $data[$i]['avatar']=$Avaterpath.$memberid.'/'.$avatar;
     * }else{
     * $data[$i]['avatar']=$Avaterpath.$noneImg;
     * }
     * }
     * // $data['homeStayDefultImg']='';
     *
     * return new JsonResponse($data);
     * }
     */
    /**
     * START开通民宿状态
     */
    
    // 开通民宿
    /**
     * @Route("/apiOwnerApprove",name="api_apiOwnerApprove_")
     */
    public function apiOwnerApproveAction()
    {
        $data = isset($_POST) ? $_POST : '';
        if (empty($data)) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            $isJson = $this->is_json($data['PosterImage']);
            
            $ItemName = 'Poster';
            
            if ($isJson) {
                $homestayId = $data['homesaty_id'];
                $imgbool = $this->imagePost($data['member_id'], $data['PosterImage'], $homestayId, $ItemName, $imgTpe = '2');
                if (! $imgbool) {
                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['message'] = 'UploadImage Error!';
                    return new JsonResponse($message);
                }
            } else {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'No Image!';
                return new JsonResponse($message);
            }
            
            $message['status'] = 1;
            $message['error'] = 0;
            $message['message'] = 'Upload Success!';
        }
        return new JsonResponse($message);
    }
    
    // 开通民宿状态接口
    /**
     * @Route("/apiHomeStayStatus",name="apiHomeStayStatus_")
     */
    public function apiHomeStayStatusAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $memberId = isset($_POST['member_id']) ? $_POST['member_id'] : 0;
        if (empty($memberId) || $memberId == 0) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            if ($memberId > 0) {
                $query = $conn->createQueryBuilder()
                    ->select('m.*')
                    ->from('msk_homestay', 'm')
                    ->where('m.is_manage=1')
                    ->
                // 1自主经营的房间
                andWhere("m.member_id=$memberId")
                    ->execute();
                $data = $query->fetchAll();
                
                if (! $data) {
                    $status = 50;
                } else {
                    $strArr = current($data);
                    $state = $strArr['state']; // 0未审核1通过审核2审核不通过
                    
                    $homeid = $strArr['id'];
                    $check_in_time = $strArr['check_in_time'];
                    
                    $homeName = $strArr['homestay_name'];
                    
                    $queryroom = $conn->createQueryBuilder()
                        ->select('m.*')
                        ->from('msk_room', 'm')
                        ->where("m.homestay_id=$homeid")
                        ->execute();
                    $dataroom = $queryroom->fetchAll();
                    
                    $numberroom = count($dataroom);
                    
                    if ($state == 0) {
                        $status = 20;
                    } else 
                        if ($state == 1) {
                            
                            if (empty($check_in_time) || $check_in_time == null) {
                                $status = 60;
                                $message['homesaty_id'] = $homeid;
                                $message['homestay_name'] = $homeName;
                            } elseif ($numberroom == 0) {
                                $status = 70;
                                $message['homesaty_id'] = $homeid;
                                $message['homestay_name'] = $homeName;
                            } else {
                                
                                $where = "FROM_UNIXTIME(m.add_time, '%Y-%m-%d')  = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d') ";
                                
                                $query_income = $conn->createQueryBuilder()
                                    ->select('SUM(m.income) Total')
                                    ->from('msk_my_wallet', 'm')
                                    ->where('m.member_id = :id')
                                    ->andWhere($where)
                                    ->andWhere("m.income<>0")
                                    ->setParameter('id', $memberId)
                                    ->orderBy('m.add_time', 'DESC')
                                    ->execute();
                                $di = current($query_income->fetchAll());
                                
                                $query_order = $conn->createQueryBuilder()
                                    ->select('count(*) c_order')
                                    ->from('msk_homestay', 'h')
                                    ->leftJoin('h', 'msk_order', 'm', 'h.id=m.homestay_id')
                                    ->where('h.member_id = :id')
                                    ->andWhere($where)
                                    ->setParameter('id', $memberId)
                                    ->execute();
                                $co = current($query_order->fetchAll());
                                
                                $status = 30;
                                
                                $di['Total'] == null ? $message['my_to_income'] = "0" : $message['my_to_income'] = $di['Total'];
                                $message['my_to_order'] = $co['c_order'];
                                $message['homesaty_id'] = $homeid;
                                $message['homestay_name'] = $homeName;
                                $this->updateMemberOwner($memberId);
                            }
                        } else 
                            if ($state == 2) {
                                $status = 40;
                                $message['refuse_reason'] = $strArr['refuse_reason'];
                                $message['id'] = $homeid;
                                $message['homestay_name'] = $homeName;
                            }
                }
                
                $message['status'] = $status;
                $message['error'] = 0;
                $message['message'] = 'Request Success!';
            }
        }
        
        return new JsonResponse($message);
    }

    public function updateMemberOwner($memberId)
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $umi = $conn->createQueryBuilder()
            ->update('msk_member', 'm')
            ->set('m.is_owner', 1)
            ->where("m.id =$memberId")
            ->execute();
        
        return $umi;
    }

    /**
     * @Route("/apiDealRule",name="api_deal_rule")
     */
    public function apiDealRuleAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $homeId = isset($_POST['homestay_id']) ? $_POST['homestay_id'] : '';
        
        $check_in_time = isset($_POST['check_in_time']) ? $_POST['check_in_time'] : '';
        
        $check_out_time = isset($_POST['check_out_time']) ? $_POST['check_out_time'] : '';
        
        $reception_time = isset($_POST['reception_time']) ? $_POST['reception_time'] : '';
        
        $invoice = isset($_POST['invoice']) ? $_POST['invoice'] : '';
        
        $least_day = isset($_POST['least_day']) ? $_POST['least_day'] : '';
        
        $ban_event = isset($_POST['ban_event']) ? $_POST['ban_event'] : '';
        
        $restock = isset($_POST['restock']) ? $_POST['restock'] : '';
        
        // echo $check_out_time."--------------".$ban_event; exit();
        
        if (empty($homeId) || $homeId == '' || empty($check_in_time) || $check_in_time == '' || empty($check_out_time) || $check_out_time == '' || empty($reception_time) || $reception_time == '' || empty($invoice) || $invoice == '' || empty($least_day) || $least_day == '' || empty($ban_event) || $ban_event == '' || empty($restock) || $restock == '') {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            $umi = $conn->createQueryBuilder()
                ->update('msk_homestay', 'm')
                ->set('m.check_in_time', "'$check_in_time'")
                ->set('m.check_out_time', "'$check_out_time'")
                ->set('m.reception_time', "'$reception_time'")
                ->set('m.invoice', $invoice)
                ->set('m.least_day', $least_day)
                ->set('m.ban_event', "'$ban_event'")
                ->where("m.id =$homeId")
                ->execute();
            
            if (strpos($restock, ',') === false) { // 使用绝对等于
                                                   // 不包含
                $restock_array = $restock;
            } else {
                // 包含
                $restock_array = explode(",", $restock);
            }
            
            // $restock_array = explode(",", $restock);
            
            $manager = $this->getDoctrine()->getManager();
            
            for ($i = 0; $i < count($restock_array); $i ++) {
                
                $stock_id = $restock_array[$i];
                
                if ($stock_id != '') {
                    
                    $restockHome = new RestockOfHomestay();
                    $restockHome->setHomesId($homeId);
                    $restockHome->setRestockId($stock_id);
                    $manager->persist($restockHome);
                    $manager->flush();
                }
            }
            
            if ($umi > 0) {
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "update Success!";
            } else {
                
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "update Error!";
            }
        }
        
        return new JsonResponse($message);
    }

    /**
     * 退订政策
     * @Route("/apiRestock",name="api_restock")
     */
    public function apiRestockAction()
    {
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : '';
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        if (empty($memberId) || $memberId == '') {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            $query = $conn->createQueryBuilder()
                ->select('m.*')
                ->from('msk_restock', 'm')
                ->execute();
            $message = $query->fetchAll();
        }
        return new JsonResponse($message);
    }

    /**
     * 重新认证
     * @Route("/apiRestartOwnerApprove",name="api_restart_owner_approve")
     */
    public function apiRestartOwnerApproveAction()
    {
        $homeId = isset($_POST['homestay_id']) ? $_POST['homestay_id'] : '';
        
        if (empty($homeId) || $homeId == '') {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            // 闂傚倸鍊风粈渚�骞夐敍鍕殰闁绘劕顕粻楣冩煥閻旇袚缂佺粯鐩畷銊╊敇閵忊晪鎷峰Δ浣典簻妞ゆ劧绲跨粻鐐搭殽閻愬樊鍎忛柍瑙勫灴楠炲洭顢楁担鍦降
            $hbool = $this->deleteHomeStay("msk_homestay", "msk_homestay.id=:id", $params = array(
                'id' => $homeId
            ));
            
            if ($hbool) {
                // 闂傚倸鍊风粈渚�骞夐敍鍕殰闁绘劕顕粻楣冩煥閻旇袚缂佺粯鐩畷銊╊敇閵忕姴鍙婇柣搴ゎ潐濞叉牠鎮ラ悡搴ｆ殾婵犲﹤妫Σ璇差渻閵堝繒绉堕柟鍑ゆ嫹
                $manager = $this->getDoctrine()->getManager();
                $sql = "DELETE FROM msk_images WHERE msk_images.homestay_room_id=:id and msk_images.img_type<>1";
                $stmt = $manager->getConnection()->prepare($sql);
                $paramss = array(
                    'id' => $homeId
                );
                $stmt->execute($paramss);
            } else {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Data Error!'; // 闂傚倷娴囧畷鐢稿磻閻愮數鐭欓煫鍥ㄧ☉缁�澶愬箹濞ｎ剙濡煎鍛攽椤旂瓔鐒炬繛澶嬬洴瀵憡绗熼敓浠嬪蓟閿濆憘鏃堟晸娴犲鍋嬮柛鈩冪懅閻牓鏌ㄩ弴鐐诧拷鍝ョ不瑜版帗鍊垫繛鎴烆伆閹寸姳鐒婃い鎰╁�楃壕鍏间繆閵堝繑瀚瑰銈忕畵娴滃爼寮幘缁樺殟闁靛／锟藉銊╂⒑閹稿海顣茬紒缁樼箞閺佹挻绂掔�ｅ灚鏅滈梺绯曞墲閿氶柣婵呭嵆濮婃椽妫冨☉杈�嗛梻鍌氬鐎氼剟鍩㈤幘璇查唶闁哄洨鍠撻崢鐢告⒑鐠嬪骸鍟崯鐐烘倵濮樼偓瀚�
                return new JsonResponse($message);
            }
            
            $message['status'] = 1;
            $message['error'] = 0;
            $message['message'] = 'Request Success!';
        }
        return new JsonResponse($message);
    }
    
    // 闂傚倸鍊风粈渚�骞夐敓鐘拷鍐幢濡炴洖鎼灒缁绢參鏀辩�氳绻涢崼婵堜虎濞存粍澹嗛敓鑺ヮ問閸ｎ噣宕戞繝鍥х畺鐟滄柨鐣烽悡搴樻斀闁割偅绋戞禍锟�
    public function deleteHomeStay($table, $where, $params)
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $id = current(array_keys($params));
        $sendId = $params['id'];
        $query = $conn->createQueryBuilder()
            ->select("m.*")
            ->from($table, 'm')
            ->where("m.$id=$sendId")
            ->execute();
        $data = $query->fetchAll();
        
        if ($data) {
            
            $manager = $this->getDoctrine()->getManager();
            $sql = "DELETE FROM $table WHERE $where";
            $stmt = $manager->getConnection()->prepare($sql);
            $stmt->execute($params);
        } else {
            return false;
        }
        return true;
    }

    /**
     * 民宿管理接口
     * Winson - 20160829
     * @Route("/apihomeManage",name="api_home_anage")
     */
    public function apihomeManageAction()
    {
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : '';
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        if (empty($memberId) || $memberId == '') {
            $message = null;
            $Rdata = null;
            $data['status'] = 0;
            $data['error'] = 1;
            $data['message'] = 'Parameters Error!';
        } else {
            
            $query = $conn->createQueryBuilder()
                ->select('m.id,m.member_id,m.homestay_name,m.homestay_title,homestay_type_id
						,m.bottom_price,m.homestay_phone,m.homestay_addr,m.dscp,m.longitude,m.latitude,m.province,m.city,m.district')
                ->from('msk_homestay', 'm')
                ->where("m.member_id=$memberId")
                ->andWhere('m.state=1')
                ->andWhere("m.is_manage=1")
                ->execute();
            $message = $query->fetchAll();
            
            $Path = $this->container->getParameter('app_image_path');
            $noneImg = $this->container->getParameter('none_img_homestay');
            
            $proName = "HomeStay";
            
            $proName_room = "Room";
            
            $proName_video = "Video";
            
            for ($i = 0; $i < count($message); $i ++) {
                $id = isset($message[$i]['id'])?($message[$i]['id']):0;
                $memberid = $message[$i]['member_id'];
                //
                $queryImg = $conn->createQueryBuilder()
                    ->select('m.goods_image,m.is_default,m.img_dscp')
                    ->from('msk_images', 'm')
                    ->where("m.homestay_room_id=$id")
                    ->andWhere('m.img_type=0')
                    ->execute();
                $Imgdata = $queryImg->fetchAll();
                
                // Video
                
                $queryVideo = $conn->createQueryBuilder()
                    ->select('m.goods_image,m.is_default,m.img_dscp')
                    ->from('msk_images', 'm')
                    ->where("m.homestay_room_id=$id")
                    ->andWhere('m.img_type=3')
                    ->execute();
                $video_data = $queryVideo->fetchAll();
                
                $video_current = current($video_data);
                
                if ($Imgdata) {
                    
                    for ($k = 0; $k < count($Imgdata); $k ++) {
                        
                        // $imgArray =current($Imgdata);
                        
                        $Imgdata[$k]['img_url'] = $Path . $proName . '/' . $memberid . '/' . $Imgdata[$k]['goods_image'];
                    }
                } else {
                    
                    $data['status'] = 0;
                    $data['error'] = 1;
                    $data['message'] = 'Data Error!';
                }
                
                if ($video_current) {
                    
                    $video_url = $Path . $proName_video . '/' . $memberid . '/' . $video_current['goods_image'];
                } else {
                    $video_url = '';
                }
                // print_r($Imgdata);exit();
                
                $message[$i]['homestayImg'] = $Imgdata;
                $message[$i]['homestayVideo'] = $video_url;
            }
            
            // 查询房间信息
            $queryR = $conn->createQueryBuilder()
                ->select('m.*')
                ->from('msk_room', 'm')
                ->where("m.homestay_id=$id")
                ->andWhere('m.state=1')
                ->execute();
            $Rdata = $queryR->fetchAll();
            
            if ($Rdata) {
                
                for ($r = 0; $r < count($Rdata); $r ++) {
                    
                    $rid = $Rdata[$r]['id'];
                    
                    // 查询房间图片
                    $queryRImg = $conn->createQueryBuilder()
                        ->select('m.goods_image,m.is_default,m.img_dscp')
                        ->from('msk_images', 'm')
                        ->where("m.homestay_room_id=$rid")
                        ->andWhere('m.img_type=1')
                        ->execute();
                    $RImgdata = $queryRImg->fetchAll();
                    
                    for ($r1 = 0; $r1 < count($RImgdata); $r1 ++) {
                        
                        $RImgdata[$r1]['img_url'] = $Path . $proName_room . '/' . $memberId . '/' . $RImgdata[$r1]['goods_image'];
                    }
                    
                    $Rdata[$r]['roomImg'] = $RImgdata;
                    
                    // 房间服务
                    $querySer = $conn->createQueryBuilder()
                        ->select('m.room_server_id')
                        ->from('msk_room_server_relation', 'm')
                        ->where("m.room_id=$rid")
                        ->execute();
                    $Serdata = $querySer->fetchAll();
                    
                    for ($s = 0; $s < count($Serdata); $s ++) {
                        
                        $Serdata[$s] = $Serdata[$s]['room_server_id'];
                    }
                    
                    $Rdata[$r]['room_server_id'] = $Serdata;
                }
            } else {
                
                $data['status'] = 0;
                $data['error'] = 1;
                $data['message'] = 'Data Error!';
            }
        }
        
        $data['homeStay'] = $message;
        $data['room'] = $Rdata;
        return new JsonResponse($data);
    }

    /**
     * 交易规则管理
     * 20160829 - Winson
     * @Route("/apiRuleManage",name="api_rule_manage")
     */
    public function apiRuleManageAction()
    {
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : '';
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        if (empty($memberId) || $memberId == '') {
            $messages['status'] = 0;
            $messages['error'] = 1;
            $messages['message'] = 'Parameters Error!';
        } else {
            
            $query = $conn->createQueryBuilder()
                ->select('m.id homestayId,m.invoice,m.check_in_time, m.check_out_time, m.reception_time,m.least_day,m.ban_event')
                ->from('msk_homestay', 'm')
                ->where("m.member_id=$memberId")
                ->andWhere('m.state=1')
                ->andWhere("m.is_manage=1")
                ->execute();
            $data = $query->fetchAll();
            
            $messages = current($data);
            
            $id = $messages['homestayId'];
            
            // echo $id;exit();
            
            $restock_query = $conn->createQueryBuilder()
                ->select('m.restockId')
                ->from('msk_restock_of_homestay', 'm')
                ->where("m.homesId=$id")
                ->execute();
            $restock_data = $restock_query->fetchAll();
            
            $messages['restock'] = $restock_data;
        }
        
        return new JsonResponse($messages);
    }

    /**
     * END开通民宿状态
     */
    /**
     * ************************************************************************************************************
     */
    /**
     * START 房间API
     */
    
    // 房间服务
    /**
     * @Route("/apiGetRoomServer",name="api_get_room_server")
     */
    public function apiGetRoomServerAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.id,m.server_name")
            ->from('msk_room_server', 'm')
            ->orderBy("m.sort", "DESC")
            ->execute();
        $data = $query->fetchAll();
        return new JsonResponse($data);
    }

    /**
     * @Route("/apiAddRoom",name="api_add_room")
     */
    public function apiAddRoomAction()
    {
        // $imgbool =$this->imagePost($data['member_id'], $data['image'],$homestay_room_id,$ItemName,$imgTpe='0');
        $data = isset($_POST) ? $_POST : '';
        if (empty($data)) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        } else {
            
            isset($data['member_id']) ? $memberid = $data['member_id'] : $memberid;
            
            $manager = $this->getDoctrine()->getManager();
            
            $room = new Room();
            $room->setHomestay_id($data['homestay_id']);
            $room->setRoom_title($data['room_title']);
            $room->setRoom_price($data['room_price']);
            $room->setRoom_single_bed($data['room_single_bed']);
            $room->setRoom_double_bed($data['room_double_bed']);
            $room->setRoom_num($data['room_num']);
            $room->setRoom_hall($data['room_hall']);
            $room->setRoom_kitchen($data['room_kitchen']);
            $room->setRoom_toilet($data['room_toilet']);
            $room->setRoom_balcony($data['room_balcony']);
            $room->setAddtime(time());
            $room->setRepast($data['repast']);
            $room->setCash($data['cash']);
            $room->setState($data['state']);
            
            $isJson = $this->is_json(isset($data['roomImg']) ? $data['roomImg'] : 0);
            
            if ($memberid > 0 && $isJson) {
                
                $manager->persist($room);
                $manager->flush();
                $homestay_room_id = $room->getId();
                
                $ItemName = 'Room';
                // $imgbool =$this->imagePost($data['member_id'], $imageArray,$homestay_room_id);
                $imgID = $this->imagePost($data['member_id'], $data['roomImg'], $homestay_room_id, $ItemName, $imgTpe = '1'); // 0homestay1room2poster
                
                if (! $imgID) {
                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['message'] = 'UploadImage Error!';
                    return new JsonResponse($message);
                }
                
                // 添加关系
                $serverArr = explode(',', $data['room_server_id']);
                
                for ($s = 0; $s < count($serverArr); $s ++) {
                    $serverID = $serverArr[$s];
                    $roomsR = new RoomServerRelation();
                    $roomsR->setRoom_id($homestay_room_id);
                    $roomsR->setRoom_server_id($serverID);
                    $manager->persist($roomsR);
                    $manager->flush();
                }
            } else {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'No Image!';
                return new JsonResponse($message);
            }
            
            $message['status'] = 1;
            $message['error'] = 0;
            $message['message'] = 'Add Success!';
        }
        
        return new JsonResponse($message);
    }

    /**
     * END 上传房间
     */
    
    // 热门城市
    /**
     * @Route("/apiHotCity",name="api_hot_city")
     */
    public function apiHotCityAction()
    {
        $fileSavePath = $this->container->getParameter('hotcitypath');
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $a = $conn->createQueryBuilder()
            ->select("m.*,ma.area_name")
            ->from('msk_hot_city', 'm')
            ->leftjoin('m', 'msk_area', 'ma', 'm.area_id=ma.area_id')
            ->orderBy("m.sort", "DESC")
            ->execute();
        $HotCL = $a->fetchAll();
        
        for ($v = 0; $v < count($HotCL); $v ++) {
            $HotCL[$v]['img_url'] = $fileSavePath . $HotCL[$v]['img'];
        }
        
        return new JsonResponse($HotCL);
    }
    
    // 全部城市
    /**
     * @Route("/apiAllCity",name="api_all_city")
     */
    public function apiAllCityAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $a = $conn->createQueryBuilder()
            ->select("m.area_id,m.area_name")
            ->from('msk_area', 'm')
            ->Where("area_deep=2")
            ->execute();
        $HotCL = $a->fetchAll();
        
        return new JsonResponse($HotCL);
    }

    /**
     * 积分明细
     * 20160830 -Winson
     * @Route("/apiGetMemberPoints",name="api_get_member_points_")
     */
    public function apiGetMemberPointsAction()
    {
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        // $a =explode("-", "-5000");
        
        if ($memberId > 0) {
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            $query = $conn->createQueryBuilder()
                ->select("m.pl_memberid,m.pl_points,m.pl_desc,m.pl_eng
				, FROM_UNIXTIME(m.pl_addtime, '%Y-%m-%d %H:%i:%s') NewAddTime")
                ->
            // mm.member_points total_points
            from('msk_points', 'm')
                ->
            // ->leftjoin('m','msk_member','mm','m.pl_memberid=mm.id')
            where("m.pl_memberid=$memberId")
                ->orderBy("m.pl_addtime", "DESC")
                ->execute();
            $data = $query->fetchAll();
        } 

        else {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['msg'] = 'Parameters Error!';
        }
        return new JsonResponse($data);
    }

    
    /**
     * 积分商品列表
     * 20160831 -Winson
     * @Route("/apiMemberPointsGoods",name="api__member_points_goods")
     */
    public function apiMemberPointsGoodsAction()
    {
        $url = $this->container->getParameter('app_points_goods_path');
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.id,m.goods_name,m.goods_points,m.quantity,CONCAT('$url',m.goods_images)  goods_images,m.state")
            ->from('msk_points_goods', 'm')
            ->orderBy("m.sort", "DESC")
            ->execute();
        $data = $query->fetchAll();
        
        return new JsonResponse($data);
    }

    /**
     * 积分兑换商品
     * 20160831 -Winson
     * @Route("/apiMemberOrderGoods",name="api_member_order_goods")
     */
    public function apiMemberOrderGoodsAction()
    {
        $goods_id = isset($_POST['goods_id']) ? $_POST['goods_id'] : 0;
        
        $buyer_name = isset($_POST['buyer_name']) ? $_POST['buyer_name'] : "";
        
        $phone = isset($_POST['phone']) ? $_POST['phone'] : 0;
        
        $addr = isset($_POST['addr']) ? $_POST['addr'] : "";
        
        $member_id = isset($_POST['member_id']) ? $_POST['member_id'] : 0;
        
        if ($goods_id > 0 && $buyer_name != "" && $phone > 0 && $addr != "" && $member_id > 0) {
            
            $manager = $this->getDoctrine()->getManager();
            
            $order = new PointsGoodsOrder();
            
            $order->setBuyer_name($buyer_name);
            
            $order->setAddr($addr);
            
            $order->setPhone($phone);
            
            $order->setGoods_id($goods_id);
            
            $order->setMember_id($member_id);
            
            $order->setAddtime(time());
            
            try {
                $manager->persist($order);
                $manager->flush();
                
                $bool = $this->setQuantity($goods_id);
                
                if ($bool) {
                    
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] = "Add Success!";
                } else {
                    
                    $message['status'] = 0;
                    $message['error'] = 1;
                    $message['msg'] = "Quantity Error!";
                }
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Add Error!";
                
                return new JsonResponse($message);
            }
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }

    public function setQuantity($goods_id)
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $bool = $conn->createQueryBuilder()
            ->update('msk_points_goods', 'm')
            ->set('m.quantity', 'm.quantity-1')
            ->where("m.id =$goods_id")
            ->execute();
        
        return $bool;
    }


    
    // 濠电姷鏁搁崑鐐哄垂閸洖绠规い鎰剁畱缁狀垰顪冪�ｎ亜鍤遍柟宄板槻閳绘捇宕归鐣屼壕濠电姵顔栭崰鏍敋椤撶倣锝夊箛閻楀牆娈濋梺姹囧灮椤ｄ粙寮搁弽銊х瘈闁汇垽娼у皬闂佺厧鍟块悥濂稿箖濞嗘挻鏅搁柨鐕傛嫹
    /**
     * @Route("/apiUploadImgTest",name="apiUploadImgTest___")
     */
    public function apiUploadImgTest()
    {
        $data = isset($_POST) ? $_POST : '';
        $imageStr = base64_decode($data['imgArr']);
        $memberId = $data['member_id'];
        
        if ($imageStr) {
            $fileName = uniqid();
            $fileType = "jpg";
            
            $ItemName = 'HomeStay';
            
            $savePath = 'bundles/msk/upload/member/';
            
            $imagesName = $fileName . '.' . $fileType;
        }
        
        if (! is_dir($savePath))
            mkdir($savePath);
        if (! is_dir($savePath . $ItemName . '/'))
            mkdir($savePath . $ItemName . '/');
        if (! is_dir($savePath . $ItemName . '/' . $memberId . '/'))
            mkdir($savePath . $ItemName . '/' . $memberId . '/');
        
        $IMGPATH = $savePath . $ItemName . '/' . $memberId . '/';
        
        file_put_contents("$IMGPATH$fileName.$fileType", $imageStr);
        
        $message['status'] = 1;
        $message['error'] = 0;
        $message['message'] = 'Add Success!';
        return new JsonResponse($message);
    }
    
  
    public function getHRImg1($hid, $imgType)
    {
        if ($imgType == 0) {
            $FileName = 'HomeStay';
        } elseif ($imgType == 1) {
            $FileName = 'Room';
        } elseif ($imgType == 2) {
            $FileName = 'Poster';
        } elseif ($imgType == 3) {
            $FileName = 'Video';
        }
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.goods_image,m.member_id,m.img_dscp,m.is_default")
            ->from('msk_images', 'm')
            ->where("m.homestay_room_id = $hid")
            ->andWhere("m.img_type=$imgType")
            ->orderBy('m.is_default', 'DESC')
            ->execute();
        $data = $query->fetchAll();
        
        $url = $this->container->getParameter('app_image_path');
        for ($i = 0; $i < count($data); $i ++) {
            $memberId = $data[$i]['member_id'];
            $goodImg = $data[$i]['goods_image'];
            $data[$i]['img_url'] = $url . $FileName . '/' . $memberId . '/' . $goodImg;
        }
        
        return $data;
    }
    
    // 传视频
    /**
     * @Route("apiVideoPost",name="api_test_img_post")
     */
    public function apiVideotPostAction()
    {
        $manager = $this->getDoctrine()->getManager();
        
        // $ext_arr = array("mp4","jpg");
        
        if (empty($_FILES) == false && isset($_POST['member_id']) && isset($_POST['homestay_id']) && $_POST['member_id'] != "" && $_POST['member_id'] != 0 && isset($_POST['homestay_id']) != 0) {
            // 判断检查
            // if($_FILES["file"]["size"] > 20971520){
            // exit("对不起，您上传的文件超过了20M。");
            // }
            if ($_FILES["videofile"]["error"] > 0) {
                // exit("文件上传发生错误：".$_FILES["file"]["error"]);
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = $_FILES["videofile"]["error"];
                return new JsonResponse($message);
            }
            
            $savePath = $this->container->getParameter('memberPath') . "Video/";
            
            $memberid = $_POST['member_id'];
            
            if (! is_dir($savePath))
                mkdir($savePath);
            if (! is_dir($savePath . $memberid . '/'))
                mkdir($savePath . $memberid . '/');
                
                // 获得文件扩展名
            $temp_arr = explode(".", $_FILES["videofile"]["name"]);
            $file_ext = array_pop($temp_arr);
            $file_ext = trim($file_ext);
            $file_ext = strtolower($file_ext);
            // 检查扩展名
            // if (in_array($file_ext, $ext_arr) == false) {
            // exit("上传文件扩展名是不允许的扩展名。");
            // }
            
            $unname = uniqid();
            $new_name = $unname . "." . $file_ext;
            // 将文件移动到存储目录下
            move_uploaded_file($_FILES["videofile"]["tmp_name"], $savePath . $memberid . '/' . $new_name);
            
            // 封面图
            $temp_arr01 = explode(".", $_FILES["imgesfile"]["name"]);
            $file_ext01 = array_pop($temp_arr01);
            $file_ext01 = trim($file_ext01);
            $file_ext01 = strtolower($file_ext01);
            
            $new_name01 = $unname . '.jpg';
            
            move_uploaded_file($_FILES["imgesfile"]["tmp_name"], $savePath . $memberid . '/' . $new_name01);
            
            $imgType = "3";
            /**
             * ***********S数据写入*********************
             */
            $images = new Images();
            $images->setHomestay_room_id($_POST['homestay_id']);
            $images->setMember_id($memberid);
            $images->setImg_type($imgType); // 0homestay1room2poster3video
            $images->setGoods_image($new_name);
            $images->setImg_dscp("");
            $images->setGoods_image_sort(0);
            $images->setIs_default(0);
            $images->setAdd_time(time());
            try {
                $manager->persist($images);
                $manager->flush();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Upload Success!";
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Upload Error!";
                return new JsonResponse($message);
            }
        
        /**
         * ***********E数据写入*********************
         */
            
            // echo "文件上传成功！";
            // exit;
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
        exit();
        
        if (isset($_POST['img']) && isset($_POST['member_id']) && isset($_POST['homestay_id']) && $_POST['member_id'] != "" && $_POST['member_id'] != 0 && isset($_POST['homestay_id']) != 0) {
            $isJson = $this->is_json($_POST['img']);
            
            $imageArray = json_decode($_POST['img'], true);
            // if(!$imageArray){
            // $message['msg']="no img";
            // }else{
            // $message['msg']="has img";
            // }
            
            if ($imageArray && $isJson) {
                // $manager = $this->getDoctrine()->getManager();
                foreach ($imageArray as $imageStr) {
                    
                    $imageStr = str_replace(' ', '', $imageStr);
                    $imageStr = str_replace('<', '', $imageStr);
                    $imageStr = str_replace('>', '', $imageStr);
                    $fileName = uniqid();
                    $fileType = "mp4";
                    
                    $memberid = $_POST['member_id'];
                    
                    $savePath = $this->container->getParameter('memberPath') . "Video/";
                    if (! is_dir($savePath))
                        mkdir($savePath);
                    if (! is_dir($savePath . $memberid . '/'))
                        mkdir($savePath . $memberid . '/');
                    
                    $binaryStr = pack("H*", $imageStr);
                    $file = fopen($savePath . $memberid . '/' . $fileName . '.' . $fileType, "w+");
                    fwrite($file, $binaryStr);
                    fclose($file);
                    
                    $fileProName = $fileName . '.' . $fileType;
                    $imgType = "3";
                    /**
                     * ***********S数据写入*********************
                     */
                    $images = new Images();
                    $images->setHomestay_room_id($_POST['homestay_id']);
                    $images->setMember_id($memberid);
                    $images->setImg_type($imgType); // 0homestay1room2poster3video
                    $images->setGoods_image($fileProName);
                    $images->setImg_dscp("");
                    $images->setGoods_image_sort(0);
                    $images->setIs_default(0);
                    $images->setAdd_time(time());
                    try {
                        $manager->persist($images);
                        $manager->flush();
                        $message['status'] = 1;
                        $message['error'] = 0;
                        $message['msg'] = "Upload Success!";
                    } catch (Exception $e) {
                        $message['status'] = 0;
                        $message['error'] = 1;
                        $message['msg'] = "Upload Error!";
                        return new JsonResponse($message);
                    }
                
                /**
                 * ***********E数据写入*********************
                 */
                } // foreach
            } else {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Video Error!";
                return new JsonResponse($message);
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
        // echo $message;
    }

    /**
     * *****************S社区**********************
     */
    /**
     * @Route("apiGroupData",name="api_group_data")
     */
    public function apiGroupDataAction()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_group', 'm')
            ->orderBy("m.sort", "DESC")
            ->execute();
        $groupList = $query->fetchAll();
        
        $reslut = $this->AddUrlfuc($groupList);
        
        return new JsonResponse($reslut);
    }

    /**
     * @Route("apiAttentGroup",name="api_attent_group")
     */
    public function apiAttentGroupAction()
    {
        $memberid = isset($_POST['member_id']) ? $_POST['member_id'] : 0;
        if ($memberid > 0) {
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            $query = $conn->createQueryBuilder()
                ->select("m.memberId,m.groupId,g.groupName,g.dscp,g.img")
                ->from('msk_member_attent_group', 'm')
                ->leftJoin('m', 'msk_group', 'g', 'm.groupId=g.id')
                ->
            // ->leftJoin('g','msk_member','mm','m.memberId=mm.id')
            // ->leftJoin('mm','msk_member_info','mi','mm.id=mi.member_id')
            where("m.memberId=$memberid")
                ->orderBy("g.sort", "DESC")
                ->execute();
            $groupList = $query->fetchAll();
            
            $reslut = $this->AddUrlfuc($groupList);
        } else {
            $reslut['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($reslut);
    }

    /**
     * @Route("apiAddGroupPost",name="api_add_group_post")
     */
    public function apiAddGroupPostAction()
    {
        $manager = $this->getDoctrine()->getManager();        
        $data = isset($_POST) ? $_POST : '';        
        $isJson = $this->is_json(isset($data['groupImg']) ? $data['groupImg'] : 0);
        if ($data != '' && isset($_POST['content']) && isset($_POST['memberId']) && $_POST['memberId'] > 0 && isset($_POST['gId']) && $_POST['gId'] > 0 && isset($_POST['title'])) {
            $post = new CPost();
            $post->setGroupId($data['gId']);
            $post->setMemberId($data['memberId']);
            $post->setContent($data['content']);
            $post->setLikeNum(0);
            $post->setDiscussNum(0);
            $post->setAddtime(time());
            $post->setTitle($data['title']); 
            try {
                $manager->persist($post);
                $manager->flush();
                $message['code'] = 200;
                $message['msg'] = "Add Success!";
                $message['result'] = '';
                $commid = $post->getId();
            } catch (Exception $e) {
                $message['code'] = 300;
                $message['msg'] = "Add Error!";
                $message['result'] = "";
                return new JsonResponse($message);
            }    
            $data['groupImg'] = isset($data['groupImg']) ? $data['groupImg'] : 0;
            if ($data['groupImg']) {
                $point=self::getpoint("帖子");
                $b=self::addMemberPoints($memberId,$point['val'],$point['title']."({$data['title']})",0);
                $imgID = $this->imagePost($data['memberId'], $data['groupImg'], $commid, "Group", 0);
                if (! $imgID) {
                    $message['code'] = 300;
                    $message['msg'] = 'Upload Image Error!';
                    $message['result'] = '';
                    return new JsonResponse($message);
                }
            }
        } else {
            $message['code'] = 300;
            $message['msg'] = "Parameters Error!";
            $message['result'] = '';
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
    }

    /**
     * @Route("apiAddGroupIndex",name="api_add_group_index")
     */
    public function apiAddGroupIndexAction()
    {
        $gid = isset($_POST['gId']) ? $_POST['gId'] : 0;
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        
        // && $memberid>0
        if ($gid > 0) {
            
            if ($gid == "9999999999") {
                $where = 'm.groupId is not null';
            } else {
                $where = "m.groupId=$gid";
            }
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            $query = $conn->createQueryBuilder()
                ->select("m.*,mm.groupName,mm.dscp,mm.img,mm.followNum,
					mm.postNum,mber.avatar,mmi.nickname")
                ->from('msk_community_post', 'm')
                ->leftJoin('m', 'msk_group', 'mm', 'mm.id=m.groupId')
                ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
                ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
                ->where("$where")
                ->andWhere("m.state=0")
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy("m.addtime", "DESC")
                ->execute();
            $groupList = $query->fetchAll();
            
            for ($i = 0; $i < count($groupList); $i ++) {
                $groupArray = array();
                
                $avatarPath = $this->getParameter('app_qiniu_imgurl');
                
                $commid = $groupList[$i]['id'];
                $groupList[$i]['img'] = $avatarPath . $groupList[$i]['img'];
                $groupList[$i]['avatar_url'] = $avatarPath . $groupList[$i]['avatar'];
                
                // 统计户评论量
                // $post_query_diss = $conn->createQueryBuilder ()
                // ->select ( "m.*" )
                // ->from ( 'msk_post_discuss', 'm' )
                // ->where("m.comPostId=$commid")
                // ->andWhere('m.type=0')
                // ->execute ();
                // $group_comm_post_diss = $post_query_diss->fetchAll ();
                
                // if($group_comm_post_diss){
                // $gcp = count($group_comm_post_diss);
                // $groupList[$i]['discussNum'] = strval($gcp);
                // }
                
                // 统计点赞数量
                // $post_query_like = $conn->createQueryBuilder ()
                // ->select ( "m.*" )
                // ->from ( 'msk_cpost_like', 'm' )
                // ->where("m.pId=$commid")
                // ->execute ();
                // $group_comm_post_like_num = $post_query_like->fetchAll ();
                
                // if($group_comm_post_like_num){
                // $glike =count($group_comm_post_like_num);
                // $groupList[$i]['likeNum'] = strval($glike);
                // }
                
                // 查询该会员是否点赞
                if ($memberid > 0) {
                    $querylike = $conn->createQueryBuilder()
                        ->select("m.*")
                        ->from('msk_cpost_like', 'm')
                        ->where("m.pId=$commid")
                        ->andWhere("m.memberId=$memberid")
                        ->execute();
                    $group_comm_post_like = $querylike->fetchAll();
                    
                    if ($group_comm_post_like) {
                        $groupList[$i]['Islike'] = 1;
                    } else {
                        $groupList[$i]['Islike'] = 0;
                    }
                } else {
                    $groupList[$i]['Islike'] = 0;
                }
                
                // 查询贴图片
                $queryImg = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_group_img', 'm')
                    ->where("m.groupId=$commid")
                    ->execute();
                $group_comm_img_list = $queryImg->fetchAll();
                
                if ($group_comm_img_list) {
                    
                    for ($j = 0; $j < count($group_comm_img_list); $j ++) {
                        
                        $groupPath =$this->getParameter('app_qiniu_imgurl');
                        
                        $groupArray[$j] = $groupPath . $group_comm_img_list[$j]['imageName'];
                    }
                    
                    $groupList[$i]['groupPostImg'] = $groupArray;
                } else {
                    $groupList[$i]['groupPostImg'] = array();
                }
                
                // 查找是否已关注
                $queryAttent = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_member_attent_group', 'm')
                    ->where("m.groupId=$gid")
                    ->andWhere("m.memberId=$memberid")
                    ->execute();
                $group_att = $queryAttent->fetchAll();
                
                if ($group_att) {
                    
                    $groupList[$i]['isAttent'] = 1;
                } else {
                    
                    $groupList[$i]['isAttent'] = 0;
                }
            }
            
            $reslut = $this->AddUrlfuc($groupList);
            
            // 统计更新贴数量
            $this->sumpostnumber($gid);
        } else {
            
            $reslut['status'] = 0;
            $reslut['error'] = 1;
            $reslut['msg'] = "Parameters Error!";
            return new JsonResponse($reslut);
        }
        
        return new JsonResponse($reslut);
    }

    /**
     * @Route("apiHotGroupIndex",name="api_hot_group_index")
     */
    public function apiHotGroupIndexAction()
    {
        $gid = isset($_POST['gId']) ? $_POST['gId'] : 0;
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        
        // && $memberid>0
        if ($gid > 0) {
            
            if ($gid == "9999999999") {
                $where = 'm.groupId is not null';
            } else {
                $where = "m.groupId=$gid";
            }
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            $query = $conn->createQueryBuilder()
                ->select("m.*,mm.groupName,mm.dscp,mm.img,mm.followNum,
					mm.postNum,mber.avatar,mmi.nickname")
                ->from('msk_community_post', 'm')
                ->leftJoin('m', 'msk_group', 'mm', 'mm.id=m.groupId')
                ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
                ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
                ->where("$where")
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->addOrderBy('m.likeNum', 'DESC')
                ->addOrderBy('m.discussNum', 'DESC')
                ->execute();
            $groupList = $query->fetchAll();
            
            // print_r($groupList);exit();
            
            for ($i = 0; $i < count($groupList); $i ++) {
                $groupArray = array();
                $commid = $groupList[$i]['id'];
                $groupList[$i]['img'] = $this->container->getParameter('app_qiniu_imgurl') . $groupList[$i]['img'];
                $groupList[$i]['avatar_url'] =$this->container->getParameter('app_qiniu_imgurl'). $groupList[$i]['avatar'];
                
                // 查询该会员是否点赞
                if ($memberid > 0) {
                    $querylike = $conn->createQueryBuilder()
                        ->select("m.*")
                        ->from('msk_cpost_like', 'm')
                        ->where("m.pId=$commid")
                        ->andWhere("m.memberId=$memberid")
                        ->execute();
                    $group_comm_post_like = $querylike->fetchAll();
                    
                    if ($group_comm_post_like) {
                        $groupList[$i]['Islike'] = 1;
                    } else {
                        $groupList[$i]['Islike'] = 0;
                    }
                } else {
                    $groupList[$i]['Islike'] = 0;
                }
                
                // 查询贴图片
                $queryImg = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_group_img', 'm')
                    ->where("m.groupId=$commid")
                    ->execute();
                $group_comm_img_list = $queryImg->fetchAll();
                
                if ($group_comm_img_list) {
                    
                    for ($j = 0; $j < count($group_comm_img_list); $j ++) {
                        $groupArray[$j] = $this->container->getParameter('app_qiniu_imgurl') . $group_comm_img_list[$j]['imageName'];
                    }
                    
                    $groupList[$i]['groupPostImg'] = $groupArray;
                } else {
                    $groupList[$i]['groupPostImg'] = array();
                }
                
                // 查找是否已关注
                $queryAttent = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_member_attent_group', 'm')
                    ->where("m.groupId=$gid")
                    ->andWhere("m.memberId=$memberid")
                    ->execute();
                $group_att = $queryAttent->fetchAll();
                
                if ($group_att) {
                    
                    $groupList[$i]['isAttent'] = 1;
                } else {
                    
                    $groupList[$i]['isAttent'] = 0;
                }
            }
            
            $reslut = $this->AddUrlfuc($groupList);
            
            // 统计更新贴数量
            $this->sumpostnumber($gid);
        } else {
            
            $reslut['status'] = 0;
            $reslut['error'] = 1;
            $reslut['msg'] = "Parameters Error!";
            return new JsonResponse($reslut);
        }
        
        return new JsonResponse($reslut);
    }
    
    // 关注小组
    /**
     * @Route("apiFollowGroup",name="api_follow_group")
     */
    public function apiFollowGroupAction()
    {
        $manager = $this->getDoctrine()->getManager();
        // Parameters
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $groupId = isset($_POST['groupId']) ? $_POST['groupId'] : 0;
        
        if ($memberId > 0 && $groupId > 0) {
            
            $mag = new MemberAttentGroup();
            $mag->setGroupId($groupId);
            $mag->setMemberId($memberId);
            
            try {
                $manager->persist($mag);
                $manager->flush();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Add Success!";
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Add Error!";
                return new JsonResponse($message);
            }
            
            // 关注统计
            $this->sumGroupfollow($groupId);
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
    }
    
    // 取消关注
    /**
     * @Route("apiUnfollowGroup",name="api_unfollow_group")
     */
    public function apiUnfollowGroup()
    {
        $manager = $this->getDoctrine()->getManager();
        // Parameters
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $groupId = isset($_POST['groupId']) ? $_POST['groupId'] : 0;
        
        if ($memberId > 0 && $groupId > 0) {
            
            // $mag =new MemberAttentGroup();
            // $mag->setGroupId($groupId);
            // $mag->setMemberId($memberId);
            
            $st = $manager->getRepository("AcmeMinsuBundle:MemberAttentGroup");
            $st_data = $st->findOneBy(array(
                'memberId' => $memberId,
                'groupId' => $groupId
            ));
            
            try {
                $manager->beginTransaction();
                $manager->remove($st_data);
                $manager->flush();
                $manager->commit();
                
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "delete Success!";
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "delete Error!";
                return new JsonResponse($message);
            }
            
            // 关注统计
            $this->sumGroupfollow($groupId);
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
    }
    // 广告图
    /**
     * @Route("apiAdvsGroup",name="api_advs_group")
     */
    public function apiAdvsGroup()
    {
        // 3square
        $url = $this->container->getParameter('advs_img_square_path_url');
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $queryAdvs = $conn->createQueryBuilder()
            ->select("m.advs_is_default,m.advs_admin_id,advs_image_path,m.advs_text")
            ->from('msk_advs_images', 'm')
            ->where("m.advs_image_sort_id=3")
            ->execute();
        $data = $queryAdvs->fetchAll();
        
        if ($data) {
            
            for ($i = 0; $i < count($data); $i ++) {
                
                $data[$i]['img_url'] = $url . $data[$i]['advs_admin_id'] . '/' . $data[$i]['advs_image_path'];
            }
        }
        
        return new JsonResponse($data);
    }
    
    // 活跃用户
    /**
     * @Route("apiActiveMember",name="api_act_member_")
     */
    public function apiActiveMember()
    {
        // 3square
        $url = $this->container->getParameter('app_avater_path');
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $queryAdvs = $conn->createQueryBuilder()
            ->select("m.memberId,COUNT(m.memberId) Sort,mm.avatar,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_member', 'mm', 'm.memberId=mm.id')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'm.memberId=mmi.member_id')
            ->where('mm.member_state=1')
            ->groupBy("m.memberId")
            ->orderBy("Sort", "DESC")
            ->setMaxResults(10)
            ->setFirstResult(0)
            ->execute();
        $data = $queryAdvs->fetchAll();
        
        if ($data) {
            
            for ($i = 0; $i < count($data); $i ++) {
                
                $data[$i]['avatar_url'] = $url . $data[$i]['memberId'] . '/' . $data[$i]['avatar'];
            }
        }
        
        return new JsonResponse($data);
    }
    
    // 点赞功能接口
    /**
     * @Route("apiMemberLike",name="api_member_like")
     */
    public function apiMemberLike()
    {
        $manager = $this->getDoctrine()->getManager();
        $pid = isset($_POST['pId']) ? $_POST['pId'] : 0;
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        
        if ($memberid > 0 && $pid > 0) {
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            
            $query = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_cpost_like', 'm')
                ->where("m.pId=$pid")
                ->andWhere("m.memberId=$memberid")
                ->execute();
            $like = $query->fetchAll();
            
            if ($like) {
                
                $st = $manager->getRepository("AcmeMinsuBundle:MemberLikePost");
                $st_data = $st->findOneBy(array(
                    'memberId' => $memberid,
                    'pId' => $pid
                ));
                
                $manager->beginTransaction();
                $manager->remove($st_data);
                $manager->flush();
                $manager->commit();
                
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Unlike Success!";
            } else {
                $mag = new MemberLikePost();
                $mag->setPId($pid);
                $mag->setMemberId($memberid);
                
                $manager->persist($mag);
                $manager->flush();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Like Success!";
            }
            
            // 统计更新点赞数量
            $this->sumpostlikenumber($pid);
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }
    
    // 评论
    /**
     * @Route("apiMemberDiscuss",name="api_member_discuss")
     */
    public function apiMemberDiscuss()
    {
        $manager = $this->getDoctrine()->getManager();
        $pid = isset($_POST['pId']) ? $_POST['pId'] : 0;
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $dId = isset($_POST['dId']) ? $_POST['dId'] : 0;
        $discuss = isset($_POST['discuss']) ? $_POST['discuss'] : "";
        $type = isset($_POST['type']) ? $_POST['type'] : 0;
        $grade = isset($_POST['grade']) ? $_POST['grade'] : 5;
        if ($memberid > 0 && $pid > 0 && $discuss && $grade) {
            
            $mag = new PostDiscuss();
            $mag->setMemberId($memberid);
            $mag->setDiscussParentId($dId);
            $mag->setAddtime(time());
            $mag->setComPostId($pid);
            $mag->setDiscuss($discuss);
            $mag->setType($type);
            $mag->setGrade($grade);
            
            try {
                $manager->persist($mag);
                $manager->flush();
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Add Success!";
                
                $newid = $mag->getId();
                if ($dId !== 0) {
                    // 父级评论+1
                    $conn = $manager->getConnection();
                    $conn->createQueryBuilder()
                        ->update('msk_post_discuss', 'm')
                        ->set('m.discussNum', "m.discussNum+1")
                        ->where("m.id =$dId")
                        ->execute();
                }
                // 更新统计评论
                $this->sumpostdissnumber($pid, $type);
            } catch (Exception $e) {
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Add Error!";
                return new JsonResponse($message);
            }
            
            // 添加信息消息
            $this->addmsg($memberid, $type, $newid);
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }
    
    // 贴评论
    /**
     * @Route("apiDiscussList",name="api_discuss_list")
     */
    public function apiDiscussList()
    {
        $pId = isset($_POST['pId']) ? $_POST['pId'] : 0;
        $type = isset($_POST['type']) ? $_POST['type'] : 0;
        if ($pId > 0) {
            
            $url = $this->container->getParameter('app_avater_path');
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            
            $query = $conn->createQueryBuilder()
                ->select("m.*,mm.avatar,mi.nickname")
                ->from('msk_post_discuss', 'm')
                ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
                ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
                ->where("m.comPostId=$pId")
                ->andWhere("m.type=$type")
                ->orderBy("m.addtime", "DESC")
                ->execute();
            
            $message = $query->fetchAll();
            
            for ($i = 0; $i < count($message); $i ++) {
                
                $disParentId = $message[$i]['discussParentId'];
                
                $DisavatarName = $message[$i]['avatar'];
                
                $DisMemberId = $message[$i]['memberId'];
                
                $message[$i]['avatar_url'] = $url . $DisMemberId . '/' . $DisavatarName;
                
                if ($disParentId > 0) {
                    
                    $queryPar = $conn->createQueryBuilder()
                        ->select("m.id parentId,m.memberId,m.discuss,mm.avatar,mi.nickname")
                        ->from('msk_post_discuss', 'm')
                        ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
                        ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
                        ->where("m.id=$disParentId")
                        ->execute();
                    
                    $data = $queryPar->fetchAll();
                    
                    $parentData = current($data);
                    
                    $avatarName = $parentData['avatar'];
                    
                    $parentMemberId = $parentData['memberId'];
                    
                    $parentData['avatar_url'] = $url . $parentMemberId . '/' . $avatarName;
                    
                    $message[$i]['parentData'] = $parentData;
                } else {
                    $message[$i]['parentData'] = "";
                }
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }

    /**
     * V2
     * @Route("apiDiscussListV2",name="api_discuss_listV2")
     */
    public function apiDiscussListV2()
    {
        $pId = isset($_POST['pId']) ? $_POST['pId'] : 0;
        $type = isset($_POST['type']) ? $_POST['type'] : 0;
        if ($pId > 0) {
            
            $url = $this->container->getParameter('app_avater_path');
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            
            $query = $conn->createQueryBuilder()
                ->select("m.*,mm.avatar,mi.nickname")
                ->from('msk_post_discuss', 'm')
                ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
                ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
                ->where("m.comPostId=$pId")
                ->andWhere("m.type=$type")
                ->orderBy("m.addtime", "DESC")
                ->execute();
            
            $message = $query->fetchAll();
            
            for ($i = 0; $i < count($message); $i ++) {
                
                $disParentId = $message[$i]['discussParentId'];
                
                $DisavatarName = $message[$i]['avatar'];
                
                $DisMemberId = $message[$i]['memberId'];
                
                $message[$i]['avatar_url'] = $url . $DisMemberId . '/' . $DisavatarName;
                
                if ($disParentId > 0) {
                    
                    $queryPar = $conn->createQueryBuilder()
                        ->select("m.id parentId,m.memberId,m.discuss,mm.avatar,mi.nickname,m.discussParentId")
                        ->from('msk_post_discuss', 'm')
                        ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
                        ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
                        ->where("m.id=$disParentId")
                        ->execute();
                    
                    $data = $queryPar->fetchAll();
                    
                    $parentData = current($data);
                    
                    $avatarName = $parentData['avatar'];
                    
                    $parentMemberId = $parentData['memberId'];
                    
                    $parId = $parentData['discussParentId'];
                    
                    if ($parId > 0) {
                        
                        $resData = $this->getDiscussList($parId);
                        
                        // print_r($resData);exit();
                        
                        // $parIddd01 = $resData['discussParentId'];
                        
                        // echo $parIddd01;exit();
                        // while ($parIddd01>0){}
                        // if($parIddd01>0){
                        
                        // }
                        
                        $parentData['parentData'] = $resData;
                    }
                    
                    $parentData['avatar_url'] = $url . $parentMemberId . '/' . $avatarName;
                    
                    $message[$i]['parentData'] = $parentData;
                } else {
                    $message[$i]['parentData'] = "";
                }
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }

    public function getDiscussList($disParentId)
    {
        $ddata = array();
        $url = $this->container->getParameter('app_avater_path');
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $queryPar = $conn->createQueryBuilder()
            ->select("m.id parentId,m.memberId,m.discuss,mm.avatar,mi.nickname,m.discussParentId")
            ->from('msk_post_discuss', 'm')
            ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
            ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
            ->where("m.id=$disParentId")
            ->execute();
        
        $data = $queryPar->fetchAll();
        
        $parentData = current($data);
        
        $avatarName = $parentData['avatar'];
        
        $parentMemberId = $parentData['memberId'];
        
        $parId = $parentData['discussParentId'];
        
        $parentData['avatar_url'] = $url . $parentMemberId . '/' . $avatarName;
        
        if ($parId > 0) {
            
            // $this->getDiscussList($parId);
            
            // array_push($parentData, $this->getDiscussList($parId));
            
            $parentData['parentData'] = $this->getDiscussList($parId);
            
            return $parentData;
        } else {
            return $parentData;
        }
        // $parentData['parId'] =$parId;
        
        // $message = $parentData ;
        
        return $parentData;
    }

    /**
     * V2
     * @Route("apiShHomeStayEval",name="api_sh_homeStayEval")
     */
    public function apiShHomeStayEval()
    {
        $manager = $this->getDoctrine()->getManager();
        $data = isset($_POST) ? $_POST : '';
        
        $par = array(
            'homestay_id',
            'member_id',
            'grade',
            'eval'
        );
        
        $parBoo = $this->checkKeyForArr($par, $data);
        
        if ($parBoo) {
            $she = new HShareEval();
            
            $she->setHomestayId($data['homestay_id']);
            $she->setMemberId($data['member_id']);
            $she->setGrade($data['grade']);
            $she->setEval($data['eval']);
            $she->setAddtime(time());
            
            try {
                $manager->persist($she);
                $manager->flush();
                
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Success";
            } catch (Exception $e) {
                
                $message['status'] = 0;
                $message['error'] = $e->getMessage();
                $message['message'] = 'Error!';
                $manager->rollback();
                $manager->close();
                return new JsonResponse($message);
            }
        } 

        else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($message);
    }

    /**
     * V2
     * @Route("apiShHomeStayEvalList",name="api_sh_homeStayEval_list")
     */
    public function apiShHomeStayEvalList()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $hid = isset($_POST['homestay_id']) ? $_POST['homestay_id'] : 0;
        
        $url = $this->container->getParameter('app_avater_path');
        
        if ($hid > 0) {
            $query = $conn->createQueryBuilder()
                ->select("m.*,mber.avatar,mmi.nickname")
                ->from('msk_homestay_share_eval', 'm')
                ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.member_id')
                ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.member_id')
                ->where("m.homestay_id=$hid")
                ->andWhere('m.state=0')
                ->execute();
            $message = $query->fetchAll();
            
            if ($message) {
                
                for ($i = 0; $i < count($message); $i ++) {
                    
                    $message[$i]['avatar_url'] = $url . $message[$i]['member_id'] . '/' . $message[$i]['avatar'];
                }
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($message);
    }

    /**
     * 删除帖子
     * @Route("apiMemberDeletep",name="api_member_delete_p")
     */
    public function apiMemberDeletep()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $pid = isset($_POST['pid']) ? $_POST['pid'] : 0;
        $memberid = isset($_POST['memberid']) ? $_POST['memberid'] : 0;
        if ($pid > 0 && $memberid > 0) {
            $conn->createQueryBuilder()
                ->update('msk_community_post', 'm')
                ->set('m.state', 2)
                ->where("m.id =$pid")
                ->andWhere("m.memberId=$memberid")
                ->execute();
            
            $message['status'] = 1;
            $message['error'] = 0;
            $message['msg'] = "update Success!";
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($message);
    }

    /**
     * *****************E社区**********************
     */
    
    // 外加社区图片
    public function AddUrlfuc($groupList)
    {
        $UrlPath = $this->getParameter('app_qiniu_imgurl');
        
        for ($i = 0; $i < count($groupList); $i ++) {
            
            $groupList[$i]['img_url'] = $groupList[$i]['img'];
        }
        return $groupList;
    }
    
    // 统计组关注
    public function sumGroupfollow($groupId)
    {
        
        // 统计数据
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        // Select
        $queryAttent = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_member_attent_group', 'm')
            ->where("m.groupId=$groupId")
            ->execute();
        $group_att = $queryAttent->fetchAll();
        
        if (count($group_att) > 0) {
            
            $num = count($group_att);
        } else {
            $num = 0;
        }
        
        // Save
        $conn->createQueryBuilder()
            ->update('msk_group', 'm')
            ->set('m.followNum', $num)
            ->where("m.id =$groupId")
            ->execute();
    }
    
    // 统计发帖数量关注
    public function sumpostnumber($groupId)
    {
        
        // 统计数据
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        // Select
        $queryAttent = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_community_post', 'm')
            ->where("m.groupId=$groupId")
            ->execute();
        $group_att = $queryAttent->fetchAll();
        
        if (count($group_att) > 0) {
            
            $num = count($group_att);
        } else {
            $num = 0;
        }
        
        // Save
        $conn->createQueryBuilder()
            ->update('msk_group', 'm')
            ->set('m.postNum', $num)
            ->where("m.id =$groupId")
            ->execute();
    }
    
    // 统计评论数量
    public function sumpostdissnumber($pId, $type)
    {
        
        // 统计数据
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        // Select
        $queryAttent = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_post_discuss', 'm')
            ->where("m.comPostId=$pId")
            ->andWhere("m.type=$type")
            ->execute();
        $group_att = $queryAttent->fetchAll();
        
        if (count($group_att) > 0) {
            
            $num = count($group_att);
        } else {
            $num = 0;
        }
        
        // Save
        $conn->createQueryBuilder()
            ->update('msk_community_post', 'm')
            ->set('m.discussNum', $num)
            ->where("m.id =$pId")
            ->execute();
    }
    
    // 统计点赞数量
    public function sumpostlikenumber($pId)
    {
        
        // 统计数据
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        // Select
        $queryAttent = $conn->createQueryBuilder()
            ->select("m.*")
            ->from('msk_cpost_like', 'm')
            ->where("m.pId=$pId")
            ->execute();
        $group_att = $queryAttent->fetchAll();
        
        if (count($group_att) > 0) {
            
            $num = count($group_att);
        } else {
            $num = 0;
        }
        
        // Save
        $conn->createQueryBuilder()
            ->update('msk_community_post', 'm')
            ->set('m.likeNum', $num)
            ->where("m.id =$pId")
            ->execute();
    }
    
    // 经纬度查询范围民宿
    /**
     * @Route("apiLonLatHomMap",name="api_lon_lat_map_hom")
     */
    public function apiLonLatHomMap()
    {
        
        $lat = isset($_POST['lat']) ? $_POST['lat'] : 0;
        $lon = isset($_POST['lon']) ? $_POST['lon'] : 0;
        if ($lat > 0 && $lon) {
            $limt = 20;
            $mill = 0.1; // 1度约为111公里
            $sql = '
				select m.id,m.member_id,m.homestay_name,m.longitude,m.is_manage,m.latitude,m.homestay_title,m.bottom_price,m.image_url from msk_homestay m where m.state=1 and
				m.latitude > ' . $lat . '-' . $mill . ' and latitude < ' . $lat . '+' . $mill . ' and longitude > ' . $lon . '-' . $mill . ' and longitude < ' . $lon . '+' . $mill . '
				order by ACOS(SIN((' . $lat . ' * 3.1415) / 180 ) *SIN((m.latitude * 3.1415) / 180 ) +COS((' . $lat . ' * 3.1415) / 180 ) * COS((m.latitude * 3.1415) / 180 ) *COS((' . $lon . '* 3.1415) / 180 - (m.longitude * 3.1415) / 180 ) ) * 6380 asc limit ' . $limt . '
		    ';
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            $conn = $em->getConnection();
            $Path = $this->container->getParameter('app_qiniu_imgurl');
            if (count($data) > 0) {
                // 读取图片
                foreach ($data as $key => $value) {
                    $img= explode(';',$data[$key]['image_url']);
                    if(is_array($img)){
                        foreach ($img as $k => $v) {
                             $data[$key]['homestayImg'][] = $this->getParameter('app_qiniu_imgurl').$img[$k];
                        }
                    }
                    $data[$key]['default_img'] = $data[$key]['homestayImg'][0];
                    unset($data[$key]['image_url']);
                    unset($data[$key]['homestayImg']);
                }
                
            }
        } else {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['msg'] = "Parameters Error!";
            return new JsonResponse($data);
        }
        
        return new JsonResponse($data);
    }
    
    // 经纬度查询范围景点
    /**
     * @Route("apiLonLatScenicMap",name="api_lon_lat_map_scen")
     */
    public function apiLonLatScenMap()
    {
        $lat = isset($_POST['lat']) ? $_POST['lat'] : 0;
        $lon = isset($_POST['lon']) ? $_POST['lon'] : 0;
        if ($lat > 0 && $lon) {
            
            $limt = 20;
            
            $mill = 0.1; // 1度约为111公里
            
            $sql = '
				select m.id,m.member_id,m.travel_title,m.travel_content,m.longitude,m.latitude,m.pay_money from msk_travel_note m where m.state=0 and
				m.latitude > ' . $lat . '-' . $mill . ' and latitude < ' . $lat . '+' . $mill . ' and longitude > ' . $lon . '-' . $mill . ' and longitude < ' . $lon . '+' . $mill . '
				order by ACOS(SIN((' . $lat . ' * 3.1415) / 180 ) *SIN((m.latitude * 3.1415) / 180 ) +COS((' . $lat . ' * 3.1415) / 180 ) * COS((m.latitude * 3.1415) / 180 ) *COS((' . $lon . '* 3.1415) / 180 - (m.longitude * 3.1415) / 180 ) ) * 6380 asc limit ' . $limt . '
		';
            
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
            $data = $stmt->fetchAll();
            
            $conn = $em->getConnection();
            $Path = $this->container->getParameter('app_image_path');
            if (count($data) > 0) {
                
                for ($i = 0; $i < count($data); $i ++) {
                    $id = $data[$i]['id'];
                    
                    $query = $conn->createQueryBuilder()
                        ->select("m.*")
                        ->from('msk_travel_note_images', 'm')
                        ->andWhere("m.travel_note_id=$id")
                        ->andWhere("m.is_default=1")
                        ->execute();
                    $img = $query->fetchAll();
                    
                    $Resimg = current($img);
                    
                    $data[$i]['travelImg'] = $this->getParameter('app_qiniu_imgurl'). $Resimg['travel_note_image'];
                }
            }
        } else {
            $data['status'] = 0;
            $data['error'] = 1;
            $data['msg'] = "Parameters Error!";
            return new JsonResponse($data);
        }
        
        return new JsonResponse($data);
    }
    
    // 发送系统信息
    public function addmsg($memberid, $type, $pid)
    {
        if ($type == 0) {
            $type = 2;
        } elseif ($type == 1) {
            $type = 3;
        }
        
        $manager = $this->getDoctrine()->getManager();
        $msg = new Msg();
        $msg->setMemberid($memberid);
        $msg->setMsg(null);
        $msg->setUrl(null);
        $msg->setIs_read(0);
        $msg->setType($type);
        $msg->setCpid($pid);
        $msg->setAddtime(time());
        
        try {
            $manager->persist($msg);
            $manager->flush();
            $message['status'] = 1;
            $message['error'] = 0;
            $message['msg'] = "Add Success!";
        } catch (Exception $e) {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Add Error!";
            return new JsonResponse($message);
        }
    }
    
    // apiIndexMsgCount
    /**
     * @Route("apiIndexMsgCount",name="api_index_Msg_Count")
     */
    public function apiIndexMsgCount()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $mid = isset($_POST['memberid']) ? $_POST['memberid'] : 0;
        if ($mid > 0) {
            
            // $query = $conn->createQueryBuilder ()
            // ->select ( "m.id,m.type,count(m.id) number,m.cpid,pd.comPostId,cp.memberId" )
            // ->from ( 'msk_msg', 'm' )
            // ->leftJoin('m' ,'msk_post_discuss' ,'pd','m.cpid=pd.id')
            // ->leftJoin('pd','msk_community_post','cp','pd.comPostId=cp.id')
            // ->where("cp.memberId=$mid")
            // ->andWhere("m.is_read=0")
            // ->groupBy("type")
            // ->execute ();
            // $memberList = $query->fetchAll ();
            
            // 社区
            $queryCp_ = $conn->createQueryBuilder()
                ->select("m.*,cp.id pid,msg.id msgid, msg.msg,msg.is_read,cp.memberId")
                ->
            // 评论
            from('msk_post_discuss', 'm')
                ->
            // 贴
            leftJoin('m', 'msk_community_post', 'cp', 'm.comPostId=cp.id')
                ->
            // 信息
            leftJoin('m', 'msk_msg', 'msg', 'm.id=msg.cpid')
                ->where("cp.memberId=$mid")
                ->andWhere('m.type=0')
                ->
            // 社区
            andWhere('cp.state=0')
                ->andWhere("m.memberId<>$mid")
                ->execute();
            $group_resDat_ = $queryCp_->fetchAll();
            
            $gnumber = 0;
            for ($g = 0; $g < count($group_resDat_); $g ++) {
                
                $msgid = $group_resDat_[$g]['msgid'];
                
                $isread = $group_resDat_[$g]['is_read'];
                
                if ($isread == 0 && $isread != '' && $msgid != '') {
                    $gnumber = $gnumber + 1;
                }
            }
            
            // echo $gnumber;exit();
            
            // 游记
            $querytt_ = $conn->createQueryBuilder()
                ->select("m.*,cp.id pid,msg.id msgid, msg.msg,msg.is_read,cp.member_id")
                ->
            // 评论
            from('msk_post_discuss', 'm')
                ->
            // 贴
            leftJoin('m', 'msk_travel_note', 'cp', 'm.comPostId=cp.id')
                ->
            // 信息
            leftJoin('m', 'msk_msg', 'msg', 'm.id=msg.cpid')
                ->where("cp.member_id=$mid")
                ->andWhere('m.type=1')
                ->
            // 0社区1游记
            andWhere('cp.state=0')
                ->andWhere("m.memberId<>$mid")
                ->execute();
            $tt_resDat_ = $querytt_->fetchAll();
            
            // print_r($tt_resDat_);
            
            $tnumber = 0;
            
            for ($t = 0; $t < count($tt_resDat_); $t ++) {
                
                $tmsgid = $tt_resDat_[$t]['msgid'];
                
                $tisread = $tt_resDat_[$t]['is_read'];
                
                if ($tisread == 0 && $tisread != '' && $tmsgid != '') {
                    $tnumber = $tnumber + 1;
                }
            }
            
            // echo $tnumber;exit();
            
            $memberList[0]['type'] = "4";
            $memberList[0]['number'] = strval($tnumber + $gnumber);
            
            $memberList[1]['type'] = "2";
            $memberList[1]['number'] = strval($gnumber);
            
            $memberList[2]['type'] = "3";
            $memberList[2]['number'] = strval($tnumber);
            
            // 官方消息
            $querysyy = $conn->createQueryBuilder()
                ->select("m.id,m.type,count(m.id) number")
                ->from('msk_msg', 'm')
                ->andWhere("m.type=0")
                ->groupBy("type")
                ->execute();
            $gfList = $querysyy->fetchAll();
            
            // 系统消息
            $querys = $conn->createQueryBuilder()
                ->select("m.id,m.type,m.memberid")
                ->from('msk_msg', 'm')
                ->andWhere("m.type=1")
                ->
            // ->groupBy("type")
            execute();
            $sys = $querys->fetchAll();
            
            $number = 0;
            
            for ($i = 0; $i < count($sys); $i ++) {
                
                $memStr = $sys[$i]['memberid'];
                
                // 统计没有阅读 系统消息 type =1 的数量
                $bool = $this->memberRead($memStr, $mid);
                
                if (! $bool) {
                    
                    $number = $number + 1;
                }
            }
            // echo $number ;exit();
            
            $querys_ysy = $conn->createQueryBuilder()
                ->select("m.id,m.type,m.memberid")
                ->from('msk_msg', 'm')
                ->andWhere("m.type=1")
                ->groupBy("type")
                ->execute();
            $sys_ = $querys_ysy->fetchAll();
            
            $sys_[0]['number'] = "$number";
            
            $sysList = $sys_;
            
            $message = array_merge($memberList, $gfList, $sysList);
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }
    
    // apiMsgList
    /**
     * @Route("apiMsgList",name="api_msg_list")
     */
    public function apiMsgList()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $mid = isset($_POST['memberid']) ? $_POST['memberid'] : 0;
        
        $type = isset($_POST['type']) ? $_POST['type'] : - 1;
        
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        
        if ($type > - 1) {
            
            $query = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_msg', 'm')
                ->andWhere("m.type=$type")
                ->setMaxResults(10)
                ->setFirstResult($offset)
                ->orderBy('m.addtime', 'DESC')
                ->execute();
            
            $list = $query->fetchAll();
            
            // print_r($list);exit();
            
            $url = $this->container->getParameter('app_avater_path');
            
            if ($type == 0 || $type == 1) {
                
                // 判断该用户是否已阅读
                for ($i = 0; $i < count($list); $i ++) {
                    
                    $memberStr = $list[$i]['memberid'];
                    
                    $bool = $this->memberRead($memberStr, $mid);
                    
                    if ($bool) {
                        $list[$i]['is_read'] = "1";
                    } else {
                        $list[$i]['is_read'] = "0";
                    }
                }
                
                $message = $list;
            } elseif ($type == 2 || $type == 3) {
                
                if ($type == 2) { // 社区
                    $query2 = $conn->createQueryBuilder()
                        ->select("m.id msgId,m.addtime,m.is_read,mcp.discuss,mcp.id did,mcp.type,mcp.discussParentId,cp.content,cp.memberId pmid,mcp.memberId,mcp.comPostId pId,mm.avatar,mmi.nickname")
                        ->from('msk_msg', 'm')
                        ->leftJoin('m', 'msk_post_discuss', 'mcp', 'm.cpid=mcp.id')
                        ->
                    // join头像
                    leftJoin('mcp', 'msk_member', 'mm', 'mcp.memberId=mm.id')
                        ->
                    // join昵称
                    leftJoin('mcp', 'msk_member_info', 'mmi', 'mcp.memberId=member_id')
                        ->
                    // join贴内容
                    leftJoin('mcp', 'msk_community_post', 'cp', 'mcp.comPostId=cp.id')
                        ->where("m.type=$type")
                        ->andWhere("cp.memberId=$mid")
                        ->andWhere("mcp.memberId<>$mid")
                        ->setMaxResults(10)
                        ->setFirstResult($offset)
                        ->orderBy('m.addtime', 'DESC')
                        ->execute();
                } elseif ($type == 3) {
                    
                    $query2 = $conn->createQueryBuilder()
                        ->select("m.id msgId,m.addtime,m.is_read,mcp.id did,mcp.discuss,mcp.type,mcp.discussParentId,cp.travel_title content,cp.member_id pmid,mcp.memberId,mcp.comPostId pId,mm.avatar,mmi.nickname")
                        ->from('msk_msg', 'm')
                        ->leftJoin('m', 'msk_post_discuss', 'mcp', 'm.cpid=mcp.id')
                        ->
                    // join头像
                    leftJoin('mcp', 'msk_member', 'mm', 'mcp.memberId=mm.id')
                        ->
                    // join昵称
                    leftJoin('mcp', 'msk_member_info', 'mmi', 'mcp.memberId=member_id')
                        ->
                    // join贴内容
                    leftJoin('mcp', 'msk_travel_note', 'cp', 'mcp.comPostId=cp.id')
                        ->where("m.type=$type")
                        ->andWhere("cp.member_id=$mid")
                        ->andWhere("mcp.memberId<>$mid")
                        ->setMaxResults(10)
                        ->setFirstResult($offset)
                        ->orderBy('m.addtime', 'DESC')
                        ->execute();
                }
                $sqlist = $query2->fetchAll();
                
                /**
                 * ********************头像&回复**************************************************
                 */
                for ($i = 0; $i < count($sqlist); $i ++) {
                    
                    $disParentId = $sqlist[$i]['discussParentId'];
                    
                    $DisavatarName = $sqlist[$i]['avatar'];
                    
                    $DisMemberId = $sqlist[$i]['memberId'];
                    
                    $sqlist[$i]['avatar_url'] = $this->getParameter('app_qiniu_imgurl').$DisavatarName;
                    
                    if ($disParentId > 0) {
                        
                        $queryPar = $conn->createQueryBuilder()
                            ->select("m.id parentId,m.memberId,m.discuss,mm.avatar,mi.nickname")
                            ->from('msk_post_discuss', 'm')
                            ->leftJoin("m", "msk_member", "mm", 'm.memberId=mm.id')
                            ->leftjoin('m', 'msk_member_info', 'mi', 'm.memberId=mi.member_id')
                            ->where("m.id=$disParentId")
                            ->execute();
                        
                        $data = $queryPar->fetchAll();
                        
                        $parentData = current($data);
                        
                        $avatarName = $parentData['avatar'];
                        
                        $parentMemberId = $parentData['memberId'];
                        
                        $parentData['avatar_url'] = $this->getParameter('app_qiniu_imgurl'). $avatarName;
                        
                        $sqlist[$i]['parentData'] = $parentData;
                    } else {
                        $sqlist[$i]['parentData'] = "";
                    }
                }
                
                // print_r($sqlist);exit();
                $message = $sqlist;
            
            /**
             * ************************头像&回复**********************************************
             */
            }
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        
        return new JsonResponse($message);
    }

    /**
     * @Route("apiReadState",name="api_iread_state")
     */
    public function apiReadState()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $msgid = isset($_POST['msgid']) ? $_POST['msgid'] : 0;
        $memberid = isset($_POST['memberid']) ? $_POST['memberid'] : 0;
        $type = isset($_POST['type']) ? $_POST['type'] : - 1;
        
        if ($msgid > 0 && $memberid > 0 && $type > - 1) {
            
            if ($type == 0 || $type == 1) {
                
                $queryPar = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_msg', 'm')
                    ->where("m.id=$msgid")
                    ->execute();
                
                $data = $queryPar->fetchAll();
                
                // print_r($data);exit();
                
                for ($i = 0; $i < count($data); $i ++) {
                    
                    $str = $data[$i]['memberid'];
                    
                    $upmembstr = $str . $memberid . ',';
                    
                    $bool = $this->memberRead($str, $memberid);
                    
                    if (! $bool) {
                        
                        $umi = $conn->createQueryBuilder()
                            ->update('msk_msg', 'm')
                            ->set('m.memberid', "'$upmembstr'")
                            ->where("m.id =$msgid")
                            ->execute();
                        
                        if ($umi > 0) {
                            $message['status'] = 1;
                            $message['error'] = 0;
                            $message['msg'] = "update Success!";
                        } else {
                            
                            $message['status'] = 0;
                            $message['error'] = 1;
                            $message['msg'] = "update Error!";
                        }
                    } else {
                        
                        $message['status'] = 0;
                        $message['error'] = 1;
                        $message['msg'] = "data  is read!";
                    }
                }
            } else {
                
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Parameters Error!";
            }
            // elseif ($type ==2 || $type ==3){
            
            // $q = $conn->createQueryBuilder ()
            // ->update ( 'msk_msg', 'm' )
            // ->set ( 'm.is_read', 1 )
            // ->where ( "m.id =$msgid" )
            // ->andWhere("m.memberId=$memberid")
            // ->andWhere("m.type=$type")
            // ->execute();
            
            // if($q>0){
            // $message['status'] = 1;
            // $message['error'] = 0;
            // $message['msg'] ="update Success!";
            
            // }else{
            
            // $message['status'] = 0;
            // $message['error'] = 1;
            // $message['msg'] ="update Error!";
            // }
            
            // }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($message);
    }
    
    // 社区/游记 改已读状态
    /**
     * @Route("apiGTReadState",name="api_g_t_iread_state")
     */
    public function apiGTReadState()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $memberid = isset($_POST['memberid']) ? $_POST['memberid'] : 0;
        
        $type = isset($_POST['type']) ? $_POST['type'] : 1;
        
        if ($memberid > 0 && $type > 1) {
            
            if ($type == 2) { // 社区
                
                $queryCp_ = $conn->createQueryBuilder()
                    ->select("m.*,cp.id pid,msg.id msgid, msg.msg,msg.is_read,cp.memberId,msg.type msgtype")
                    ->
                // 评论
                from('msk_post_discuss', 'm')
                    ->
                // 贴
                leftJoin('m', 'msk_community_post', 'cp', 'm.comPostId=cp.id')
                    ->
                // 信息
                leftJoin('m', 'msk_msg', 'msg', 'm.id=msg.cpid')
                    ->where("cp.memberId=$memberid")
                    ->andWhere("msg.type=$type")
                    ->andWhere('cp.state=0')
                    ->andWhere('msg.is_read=0')
                    ->execute();
                $group_resDat_ = $queryCp_->fetchAll();
            } elseif ($type == 3) {
                $queryCp_ = $conn->createQueryBuilder()
                    ->select("m.*,cp.id pid,msg.id msgid, msg.msg,msg.is_read,cp.member_id,msg.type msgtype")
                    ->
                // 评论
                from('msk_post_discuss', 'm')
                    ->
                // 贴
                leftJoin('m', 'msk_travel_note', 'cp', 'm.comPostId=cp.id')
                    ->
                // 信息
                leftJoin('m', 'msk_msg', 'msg', 'm.id=msg.cpid')
                    ->where("cp.member_id=$memberid")
                    ->andWhere("msg.type=$type")
                    ->andWhere('cp.state=0')
                    ->andWhere('msg.is_read=0')
                    ->execute();
                $group_resDat_ = $queryCp_->fetchAll();
            } else {
                
                $message['status'] = 0;
                $message['error'] = 1;
                $message['msg'] = "Parameters Error!";
            }
            
            // print_r($group_resDat_);exit();
            
            for ($i = 0; $i < count($group_resDat_); $i ++) {
                
                $msid = $group_resDat_[$i]['msgid'];
                
                if ($msid != '') {
                    
                    $umi = $conn->createQueryBuilder()
                        ->update('msk_msg', 'm')
                        ->set('m.is_read', 1)
                        ->where("m.id =$msid")
                        ->execute();
                    
                    if ($umi > 0) {
                        $message['status'] = 1;
                        $message['error'] = 0;
                        $message['msg'] = "update Success!";
                    } else {
                        
                        $message['status'] = 0;
                        $message['error'] = 1;
                        $message['msg'] = "update Error!";
                    }
                }
            }
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
        }
        
        return new JsonResponse($message);
    }

    /**
     * 优惠券
     * @Route("apiCouponList",name="_api_coupon_list")
     */
    public function apiCouponList()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $query_ = $conn->createQueryBuilder()
            ->select("m.coupon_value,m.convert_points,m.min_amount,m.coupon_dscp,m.qouta_day")
            ->from('msk_coupon', 'm')
            ->where('m.state =1')
            ->orderBy('m.sort', 'DESC')
            ->execute();
        $message = $query_->fetchAll();
        
        return new JsonResponse($message);
    }

    /**
     * 兑换优惠券
     * @Route("apiBuyCoupon",name="_api_buy_coupon_")
     */
    public function apiBuyCoupon()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $conn = $manager->getConnection();
        
        $data = isset($_POST) ? $_POST : '';
        
        // echo $data['restock_text'];exit();
        
        $par = array(
            'buyer_id',
            'convert_points',
            'coupon_value',
            'min_amount',
            'coupon_dscp',
            'deadline'
        );
        
        $parBool = $this->checkKeyForArr($par, $data);
        
        if ($parBool > 0 && $data != "") {
            
            $parameters = array(
                'buyer_id' => $data['buyer_id'],
                'coupon_value' => $data['coupon_value'],
                'min_amount' => $data['min_amount']
            );
            
            /* 检查一样的优惠券 是否已购买 */
            $query = $conn->createQueryBuilder()
                ->select('m.*')
                ->from('msk_buyer_coupon', 'm')
                ->where('m.buyer_id = :buyer_id')
                ->andWhere('m.convert_points = 0')
                ->andWhere('m.coupon_value = :coupon_value')
                ->andWhere('m.min_amount = :min_amount')
                ->setParameters($parameters)
                ->execute();
            $hasCoupon = $query->fetchAll();
            
            // print_r($hasCoupon);exit();
            if ($hasCoupon) {
                
                $message['status'] = 0;
                $message['error'] = 1;
                $message['message'] = 'Exist';
            } else {
                
                $bc = new BuyerCoupon();
                
                $bc->setBuyerId($data['buyer_id']);
                $bc->setConvertPoints($data['convert_points']);
                $bc->setCouponValue($data['coupon_value']);
                $bc->setMinAmount($data['min_amount']);
                $bc->setCouponDscp($data['coupon_dscp']);
                $bc->setDeadline($data['deadline']);
                $bc->setAddTime(time());
                $bc->setState(0);
                
                try {
                    $manager->persist($bc);
                    $manager->flush();
                    
                    $message['status'] = 1;
                    $message['error'] = 0;
                    $message['msg'] = "Success";
                } catch (Exception $e) {
                    
                    $message['status'] = 0;
                    $message['error'] = $e->getMessage();
                    $message['message'] = 'Error!';
                    $manager->rollback();
                    $manager->close();
                    return new JsonResponse($message);
                }
            }
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        
        return new JsonResponse($message);
    }

    /**
     * 购买者优惠券列表
     * @Route("apiBuyerCouponList",name="_api_buyer_coupon_list_")
     */
    public function apiBuyerCouponList()
    {
        $manager = $this->getDoctrine()->getManager();
        
        $conn = $manager->getConnection();
        
        $data = isset($_POST) ? $_POST : '';
        
        // echo $data['restock_text'];exit();
        
        $par = array(
            'buyer_id'
        );
        
        $parBool = $this->checkKeyForArr($par, $data);
        
        if ($parBool > 0 && $data != "") {
            
            $query = $conn->createQueryBuilder()
                ->select('m.id,m.convert_points,m.coupon_value,m.min_amount,m.coupon_dscp,m.deadline,m.state')
                ->from('msk_buyer_coupon', 'm')
                ->where('m.buyer_id = :buyer_id')
                ->setParameter('buyer_id', $data['buyer_id'])
                ->execute();
            $message = $query->fetchAll();
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        
        return new JsonResponse($message);
    }

    /**
     * 优惠券改状态
     * @Route("apiCouponChangeStatus",name="api_oupon_change_status")
     */
    public function apiCouponChangeStatus()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        
        $data = isset($_POST) ? $_POST : '';
        
        $par = array(
            'b_coupon_id'
        );
        
        $parBool = $this->checkKeyForArr($par, $data);
        
        if ($parBool > 0 && $data != "") {
            
            $umi = $conn->createQueryBuilder()
                ->update('msk_buyer_coupon', 'm')
                ->set('m.state', 1)
                ->where("m.id =:id")
                ->setParameter('id', $data['b_coupon_id'])
                ->execute();
            
            if ($umi) {
                
                $message = $this->msg_succ();
            } else {
                $message = $this->msg_error();
            }
        } else {
            
            $message['status'] = 0;
            $message['error'] = 1;
            $message['message'] = 'Parameters Error!';
        }
        
        return new JsonResponse($message);
    }

    public function memberRead($memberStr, $memberid)
    {
        $arr = explode("-", $memberStr);
        
        if (isset($arr[1])) {
            
            $arr_mid = explode(",", $arr[1]);
            
            if (in_array($memberid, $arr_mid)) {
                // return new JsonResponse(1);
                
                return true;
            } else {
                // return new JsonResponse(0);
                return false;
            }
        } else {
            // return new JsonResponse(0);
            return false;
        }
    }

    function checkKeyForArr($par, $arr)
    {
        $s = 0;
        for ($i = 0; $i < count($par); $i ++) {
            
            $key = $par[$i];
            
            if (key_exists($key, $arr)) {
                $s ++;
            } else {
                $s = 0;
                break;
            }
        }
        
        return $s;
    }

    /**
     * 推荐民宿与景点
     * @Route("apiGetRecHomeStay", name="apiGetRecHomeStay_")
     */
    public function apiGetRecHomeStayAction()
    {
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
            ->select('p.id', 'p.member_id', 'p.homestay_name', 'p.homestay_title', 'p.homestay_addr', 'p.bottom_price', 'p.sort', 'p.addtime', 'p.city', 'p.is_manage', 'c.avatar', 'c.is_owner', 'p.image_url','p.video_url')
            ->from('msk_index_recommend', 'mhr')
            ->leftJoin('mhr', 'msk_homestay', 'p', 'mhr.rec_id=p.id')
            ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
            ->where("p.state = 1")
            ->andWhere("mhr.type = 0")
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->addOrderBy('p.sort', 'DESC')
            ->addOrderBy('p.addtime', 'DESC')
            ->execute();
        $roomImgData = $roomImgQuery->fetchAll();

        if ($roomImgData) {
            $dataLenth = count($roomImgData);
            for ($i = 0; $i < $dataLenth; $i ++) {
                $avatar = $this->getParameter('app_qiniu_imgurl'). $roomImgData[$i]['avatar'];
                $img=explode(";",$roomImgData[$i]['image_url']);
                $homestaDefaultImg = $this->getParameter('app_qiniu_imgurl'). $img[0];
                unset($roomImgData[$i]['goods_image']);
                unset($roomImgData[$i]['avatar']);
                $roomImgData[$i]['avatar'] = $avatar;
                $roomImgData[$i]['homeStayDefultImg'] = $homestaDefaultImg;
                array_push($data, $roomImgData[$i]);
                if (empty($roomImgData[$i]['video_url'])){
                    $data[$i]['is_have_video'] = '1';
                }else{
                    $data[$i]['is_have_video'] = '0';
                }
            };

        }
            // 游记
            
        $travelDataQry = $conn->createQueryBuilder()
            ->select('p.travel_title', 'p.id', 'c.travel_note_image', 'p.pay_money', 'p.longitude', 'p.latitude', 'p.city')
            ->from('msk_index_recommend', 'mhr')
            ->leftjoin('mhr', 'msk_travel_note', 'p', 'mhr.rec_id=p.id')
            ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
            ->where('c.is_default = 1')
            ->andWhere('p.state = 0')
            ->andWhere('mhr.type=1')
            ->orderBy('p.addtime', 'desc')
            ->execute();
            $data_tra = $travelDataQry->fetchAll();
            
            $travelDataResLenth = count($data_tra);
            for ($i = 0; $i < $travelDataResLenth; $i ++) {
                $data_tra[$i]['travel_note_cover_image'] = $this->getParameter('app_qiniu_imgurl'). $data_tra[$i]['travel_note_image'];
                unset($data_tra[$i]['travel_note_image']);
            }
            
            $res = array();
            
            $res['homestay'] = $data;
            
            $res['travel'] = $data_tra;
            return new JsonResponse($res);
    }
    
    // 广新添加 2016-10-28
    /**
     * 驴友帮下的小组展示
     * @Route("apiTravelersGroup",name="apiTravelersGroup")
     */
    public function apiTravelersGroup()
    {
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.id as group_id,m.groupName")
            ->from('msk_group', 'm')
            ->where("m.belong =1")
            ->orderBy("m.sort", "DESC")
            ->execute()->fetchAll();
        if (! empty($query)) {
            array_unshift($query,array('group_id'=>0,'groupName'=>'全部'));
            $this->Send(200,'success',$query);
        } else {
           $this->Send(202,'暂无数据');
        }
    }

    /**
     * 驴友帮下的最热和最新评论
     * @Route("apiTravelersGroupIndex",name="api_travelers_group_index")
     */
    public function apiTravelersGroupIndexAction()
    {
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        $kind = isset($_POST['kind']) ? $_POST['kind'] : 0; // 最热，最新
        if ($kind == 0) {
            $orderRule = "m.addTime";
        } else {
            $orderRule = "m.likeNum";
        }
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $groupList = $conn->createQueryBuilder()
            ->select("m.*,mm.groupName,mm.dscp,mm.img,mm.followNum,
									mm.postNum,mm.id as gid,mber.avatar,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_group', 'mm', 'mm.id=m.groupId')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->where("mm.belong =1")
            ->andWhere("m.state=0")
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->orderBy($orderRule,'desc')
            ->execute()
           
            ->fetchAll();
       
        for ($i = 0; $i < count($groupList); $i ++) {
            $groupArray = array();
            
            $avatarPath = $this->getParameter('app_qiniu_imgurl');
            $commid = $groupList[$i]['id'];
            $groupList[$i]['avatar_url'] = $avatarPath . $groupList[$i]['avatar'];
            $gid = $groupList[$i]['gid'];
            // 统计户评论量
            // $post_query_diss = $conn->createQueryBuilder ()
            // ->select ( "m.*" )
            // ->from ( 'msk_post_discuss', 'm' )
            // ->where("m.comPostId=$commid")
            // ->andWhere('m.type=0')
            // ->execute ();
            // $group_comm_post_diss = $post_query_diss->fetchAll ();
            
            // if($group_comm_post_diss){
            // $gcp = count($group_comm_post_diss);
            // $groupList[$i]['discussNum'] = strval($gcp);
            // }
            
            // 统计点赞数量
            // $post_query_like = $conn->createQueryBuilder ()
            // ->select ( "m.*" )
            // ->from ( 'msk_cpost_like', 'm' )
            // ->where("m.pId=$commid")
            // ->execute ();
            // $group_comm_post_like_num = $post_query_like->fetchAll ();
            
            // if($group_comm_post_like_num){
            // $glike =count($group_comm_post_like_num);
            // $groupList[$i]['likeNum'] = strval($glike);
            // }
            
            // 统计更新贴数量
            $this->sumpostnumber($gid);
            // 查询该会员是否点赞
            if ($memberid > 0) {
                $querylike = $conn->createQueryBuilder()
                    ->select("m.*")
                    ->from('msk_cpost_like', 'm')
                    ->where("m.pId=$commid")
                    ->andWhere("m.memberId=$memberid")
                    ->execute();
                $group_comm_post_like = $querylike->fetchAll();
                
                if ($group_comm_post_like) {
                    $groupList[$i]['Islike'] = 1;
                } else {
                    $groupList[$i]['Islike'] = 0;
                }
            } else {
                $groupList[$i]['Islike'] = 0;
            }
            
            // 查询贴图片
            $queryImg = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_group_img', 'm')
                ->where("m.groupId=$commid")
                ->execute();
            $group_comm_img_list = $queryImg->fetchAll();
            
            if ($group_comm_img_list) {
                
                for ($j = 0; $j < count($group_comm_img_list); $j ++) {
                    $groupArray[$j] = $this->getParameter('app_qiniu_imgurl') . $group_comm_img_list[$j]['imageName'];
                }
                
                $groupList[$i]['groupPostImg'] = $groupArray;
            } else {
                $groupList[$i]['groupPostImg'] = array();
            }
            
            // 查找是否已关注
            $queryAttent = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_member_attent_group', 'm')
                ->where("m.groupId=$gid")
                ->andWhere("m.memberId=$memberid")
                ->execute();
            $group_att = $queryAttent->fetchAll();
            
            if ($group_att) {
                
                $groupList[$i]['isAttent'] = 1;
            } else {
                
                $groupList[$i]['isAttent'] = 0;
            }
        }
        
        $reslut = $this->AddUrlfuc($groupList);
        
        if (! empty($reslut)) {
            $massage['status'] = '1';
            $massage['error'] = '0';
            $massage['massage'] = $reslut;
            ;
            return new JsonResponse($massage);
        } else {
            
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }
    }

    /**
     * 驴友帮下的人气排行
     * @Route("apiTravelersGroupPopular",name="api_travelers_group_pupular")
     */
    public function apiTravelersGroupPopularAction()
    {
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $offset = isset($_POST['offset']) ? $_POST['offset'] : 0;
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $groupList = $conn->createQueryBuilder()
            ->select("p.memberId as to_member_id,count(p.memberId) as postNum, m.groupName,mber.avatar,mmi.nickname")
            ->from('msk_community_post', 'p')
            ->leftjoin('p', 'msk_group', 'm', 'p.groupId = m.id')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=p.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=p.memberId')
            ->where("m.belong =1")
            ->orderBy('postNum', 'DESC')
            ->groupBy("p.memberId")
            ->setMaxResults(10)
            ->setFirstResult($offset)
            ->execute()
            ->fetchAll();
        for ($i = 0; $i < count($groupList); $i ++) {
            
            $to_member_id = $groupList[$i]['to_member_id'];
            $app_qiniu_imgurl = $this->getParameter('app_qiniu_imgurl');
            $groupList[$i]['avatar'] = $app_qiniu_imgurl . $groupList[$i]['avatar'];
            // 查找是否已关注
            $queryAttent = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_member_relation', 'm')
                ->where("m.to_member_id=$to_member_id")
                ->andWhere("m.from_member_id=$memberid")
                ->andWhere("m.relation_type=1")
                ->execute();
            $group_att = $queryAttent->fetchAll();
            
            if ($group_att) {
                
                $groupList[$i]['isAttent'] = 1;
            } else {
                
                $groupList[$i]['isAttent'] = 0;
            }
        }
        if (! empty($groupList)) {
            $massage['status'] = '1';
            $massage['error'] = '0';
            $massage['massage'] = $groupList;
            ;
            return new JsonResponse($massage);
        } else {
            
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }
    }


    public function searchTravelersGroup($sort)
    {
        $orderRule = "m.$sort, 'DESC'";
        $query = array();
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("m.id,m.groupId as gid,m.memberId,m.title,m.content")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_group', 'p', 'p.id=m.groupId')
            ->where("p.belong =1")
            ->andWhere("m.state =0")
            ->orderBy($orderRule)
            ->execute()
            ->fetch();
        return $query;
    }

    
    
    
    
    
    /**
     * 驴友帮推荐展示
     * @Route("apiTravelersGroupRecIndex",name="apiTravelersGroupRecIndex")
     */
    public function apiTravelersGroupRecIndex()
    {
        $data[] = $this->searchTravelersGroup('discussNum');
        $data[] = $this->searchTravelersGroup('addTime');
        $data[] = $this->searchTravelersGroup('sort');
        if (! empty($data)) {
            
            $massage['code'] = '200';
            $massage['msg'] = 'success';
            $massage['result'] = $data;
            return new JsonResponse($massage);
        } else {
            
            $massage['code'] = '300';
            $massage['msg'] = 'not found data!';
            $massage['result'] = '';
            return new JsonResponse($massage);
        }
        
        return new JsonResponse($reslut);
    }

    /**
     * 时间、热度搜索父级评论
     * @Route("apiDiscussListSearch",name="apiDiscussListSearch")
     */
    public function apiDiscussListSearch()
    {
        $memberid = isset($_POST['memberId']) ? $_POST['memberId'] : 0; // 0时间、1热度
        $kind = isset($_POST['kind']) ? $_POST['kind'] : 0;
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $commid = isset($_POST['commid']) ? $_POST['commid'] : 0;
        if ($kind == 0) {
            $orderRule = "m.addtime, 'DESC'";
        } else {
            $orderRule = "m.discussNum, 'DESC'";
        }
        $conn = $this->getDoctrine()
        ->getManager()
        ->getConnection();
        $groupList = $conn->createQueryBuilder()
            ->select("m.*, mber.avatar,mmi.nickname")
            ->from('msk_post_discuss', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->
        // ->where("m.id =$commid")
        where("m.comPostId=$commid")
            ->andWhere("m.discussParentId=0")
            ->andWhere("m.type=0")
            ->orderBy($orderRule)
            ->setFirstResult($page)
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
        // var_dump($groupList);die;
        for ($i = 0; $i < count($groupList); $i ++) {
            // $avatarPath=$this->container->getParameter('app_avater_path') .$groupList['memberId'].'/';
            // $groupList['avatar_url'] = $avatarPath .$groupList['avatar'];
            $discuss_id = $groupList[$i]['id'];
            // 查询该会员是否点赞
            $querylike = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_discuss_like', 'm')
                ->where("m.discuss_id= $discuss_id")
                ->andWhere("m.memberId= $memberid")
                ->execute()
                ->fetch();
            if ($querylike) {
                $groupList[$i]['Islike'] = 1;
            } else {
                $groupList[$i]['Islike'] = 0;
            }
        }
        if (! empty($groupList)) {
            $massage['status'] = '1';
            $massage['error'] = '0';
            $massage['massage'] = $groupList;
            return new JsonResponse($massage);
        } else {
            
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = 'not found data!';
            return new JsonResponse($massage);
        }
        
        return new JsonResponse($reslut);
    }

    
    /**
     * 帖子评论
     * @Route("apiPostComments",name="apiPostComments_")
     */
    public function apiPostComments()
    {
        $commid = isset($_POST['commid']) ? $_POST['commid'] : 0;
        $conn=self::conn();
        $data = $conn->createQueryBuilder()
            ->select("m.*, mber.avatar,mmi.nickname")
            ->from('msk_post_discuss', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->where("m.comPostId=$commid",'m.discussParentId=0','m.type=0')
            ->orderBy('m.addtime','desc')
            ->setFirstResult(0)
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
        foreach ($data as $k=>$v){
            $data[$k]['children']=self::getsub($v['id']);
        }
        
       $this->Send(200,'success',$data);
    }
    
    protected function getsub($id,&$result = array()){
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $arr = $conn->createQueryBuilder()
                ->select("m.*, mber.avatar,mmi.nickname")
                ->from('msk_post_discuss', 'm')
                ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
                ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
                ->where("m.discussParentId=$id")
                ->orderBy('m.addtime','desc')
                ->setFirstResult(0)
                ->setMaxResults(10)
                ->execute()
                ->fetchAll();          
            if(empty($arr)){
                return array();
            }
            foreach ($arr as $cm) {
                $thisArr=&$result[];
                $cm["children"] = $this->getsub($cm["id"],$thisArr);
                $thisArr = $cm;
            }		
        return $result;
    }
    
    
    /**
     * 从父级评论调到二级评论列表栏目
     * @Route("apiDiscussSecondList",name="apiDiscussSecondList")
     */
    public function apiDiscussSecondList()
    {
        $memberId = isset($_POST['memberId']) ? $_POST['memberId'] : 0;
        $commid = isset($_POST['commid']) ? $_POST['commid'] : 0;
        $page = isset($_POST['page']) ? $_POST['page'] : 0;
        $discuss_id = isset($_POST['discuss_id']) ? $_POST['discuss_id'] : 0;
        
        $conn = $this->getDoctrine()
            ->getManager()
            ->getConnection();
        // 查询帖子与一级讨论
        $groupList = $conn->createQueryBuilder()
            ->select("m.id,m.groupId as gid,m.memberId,m.title,m.content,mber.avatar,mmi.nickname")
            ->from('msk_community_post', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->
        // ->where("m.id =$commid")
        where("m.id=$commid")
            ->andWhere("m.state=0")
            ->execute()
            ->fetch();
        $groupList['avatar']=$this->getParameter('app_qiniu_imgurl').$groupList['avatar'];
        // 查询一级的讨论内容
        $onelist = $conn->createQueryBuilder()
            ->select("m.discuss,m.addtime as addTime")
            ->from('msk_post_discuss', 'm')
            ->where("m.id=$discuss_id")
            ->andWhere("m.type=0")
            ->execute()
            ->fetch();
        $groupList['discuss'] = $onelist['discuss'];
        $groupList['addTime'] = $onelist['addTime'];
        $groupList['list'] = $conn->createQueryBuilder()
            ->select("m.*,mber.avatar,mmi.nickname")
            ->from('msk_post_discuss', 'm')
            ->leftJoin('m', 'msk_member', 'mber', 'mber.id=m.memberId')
            ->leftJoin('m', 'msk_member_info', 'mmi', 'mmi.member_id=m.memberId')
            ->
        // ->where("m.id =$commid")
        where("m.discussParentId=$discuss_id")
            ->andWhere("m.type=0")
            ->orderBy('m.addtime', 'DESC')
            ->setFirstResult($page)
            ->setMaxResults(10)
            ->execute()
            ->fetchAll();
        for ($i = 0; $i < count($groupList['list']); $i ++) {
            // $avatarPath=$this->container->getParameter('app_avater_path') .$groupList['memberId'].'/';
            $groupList['list'][$i]['avatar'] = $this->getParameter('app_qiniu_imgurl').$groupList['list'][$i]['avatar'];
            $discuss_id = $groupList['list'][$i]['id'];
            // 查询该会员是否点赞
            $querylike = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_discuss_like', 'm')
                ->where("m.discuss_id= $discuss_id")
                ->andWhere("m.memberId= $memberId")
                ->execute()
                ->fetch();
            if ($querylike) {
                $groupList['list'][$i]['Islike'] = 1;
            } else {
                $groupList['list'][$i]['Islike'] = 0;
            }
        }
        if (! empty($groupList['list'])) {
            $massage['status'] = '1';
            $massage['error'] = '0';
            $massage['massage'] = $groupList;
            return new JsonResponse($massage);
        } else {
            
            $massage['status'] = '0';
            $massage['error'] = '1';
            $massage['massage'] = $groupList;
            return new JsonResponse($massage);
        }
        
        return new JsonResponse($reslut);
    }
    
    // 讨论列表的点赞功能接口
    /**
     * @Route("apiMemberDiscussLike",name="api_member_discuss_like")
     */
    public function apiMemberDiscussLike()
    {
        $manager = $this->getDoctrine()->getManager();
        $pid = isset($_POST['discuss_id']) ? $_POST['discuss_id'] : 0;
        $token = isset($_POST['token']) ? $_POST['token'] : 0;
        $memberid = $this->validationToken($token);
        if (is_array($memberid))
            return new JsonResponse($memberid);
        if ($memberid > 0 && $pid > 0) {
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            
            $query = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_discuss_like', 'm')
                ->where("m.discuss_id=$pid")
                ->andWhere("m.memberId=$memberid")
                ->execute();
            $like = $query->fetchAll();
            
            if ($like) {
                return new JsonResponse(array(
                    'status' => 0,
                    'error' => 1,
                    'msg' => 'you liked it'
                ));
            } else {
                $mag = new MemberLikeDiscuss();
                $mag->setDiscussId($pid);
                $mag->setMemberId($memberid);
                
                $manager->persist($mag);
                $manager->flush();
                
                // 点赞+1
                $conn = $manager->getConnection();
                $conn->createQueryBuilder()
                    ->update('msk_post_discuss', 'm')
                    ->set('m.likeNum', "m.likeNum+1")
                    ->where("m.id =$pid")
                    ->execute();
                
                $message['status'] = 1;
                $message['error'] = 0;
                $message['msg'] = "Like Success!";
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
    }
    
    // 帖子收藏功能接口
    /**
     * @Route("apiMemberCommunityCollect",name="apiMemberCommunityCollect_")
     */
    public function apiMemberCommunityCollect(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $token = $request->get('token', 0);
        $comPostId = $request->get('comPostId', 0);
        $memberid = $this->validationToken($token);
        if (is_array($memberid))
            return new JsonResponse($memberid);
        if ($memberid > 0 && $comPostId > 0) {
            
            $conn = $this->getDoctrine()
                ->getManager()
                ->getConnection();
            
            $query = $conn->createQueryBuilder()
                ->select("m.*")
                ->from('msk_community_collect', 'm')
                ->where("m.comPostId=$comPostId")
                ->andWhere("m.member_id=$memberid")
                ->execute();
            $collect = $query->fetch();
            if (empty($collect)) {
                $data = array(
                    'comPostId' => $comPostId,
                    'member_id' => $memberid,
                    'is_collect' => 1,
                    'add_time' => time()
                );
                $upb = $conn->insert('msk_community_collect', $data);
                return new JsonResponse(array(
                    'status' => 1,
                    'error' => 0,
                    'msg' => '收藏成功!',
                    'result' => ''
                ));
            } elseif ($collect['is_collect'] == 0) {
                $upb = $conn->createQueryBuilder()
                    ->update('msk_community_collect', 'm')
                    ->set('m.is_collect', "1")
                    ->where("m.member_id =$memberid")
                    ->andWhere("comPostId=$comPostId")
                    ->execute();
                return new JsonResponse(array(
                    'status' => 1,
                    'error' => 0,
                    'msg' => '收藏成功!',
                    'result' => ''
                ));
            } else {
                $upb = $conn->createQueryBuilder()
                    ->update('msk_community_collect', 'm')
                    ->set('m.is_collect', "0")
                    ->where("m.member_id =$memberid")
                    ->andWhere("comPostId=$comPostId")
                    ->execute();
                return new JsonResponse(array(
                    'status' => 1,
                    'error' => 0,
                    'msg' => '已取消收藏!',
                    'result' => ''
                ));
            }
        } else {
            $message['status'] = 0;
            $message['error'] = 1;
            $message['msg'] = "Parameters Error!";
            return new JsonResponse($message);
        }
        return new JsonResponse($message);
    }

    public function imagePost($memberId, $imageArray, $homestay_room_id, $ItemName, $imgTpe)
    {
        $manager = $this->getDoctrine()->getManager();
        if (! $imageArray) {
            return false;
        }
        $ims = json_decode($imageArray, true);
        for ($i = 0; $i < count($ims); $i ++) {
            $imagesName = $ims[$i]['imgArr'];
            if ($ItemName == "Group") {
                $images = new GroupImg();
                $images->setGroupId($homestay_room_id); // commid
                $images->setMemberId($memberId);
                $images->setImgType($imgTpe); // 0homestay1room2poster
                $images->setImageName($imagesName);
                $images->setSort(0);
                $images->setAddTime(time());
            } else {
                $images = new Images();
                $images->setHomestay_room_id($homestay_room_id);
                $images->setMember_id($memberId);
                $images->setImg_type($imgTpe); // 0homestay1room2poster
                $images->setGoods_image($imagesName);
                $images->setImg_dscp(isset($ims[$i]['imgDscp']) ? $ims[$i]['imgDscp'] : "");
                $images->setGoods_image_sort(0);
                $images->setIs_default(isset($ims[$i]['is_default']) ? $ims[$i]['is_default'] : 0);
                $images->setAdd_time(time());
            }
            try {
                $manager->persist($images);
                $manager->flush();
            } catch (Exception $e) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * 单个小组详情
     * @Route("apiGroupDetial",name="apiGroupDetial_")
     */
    public function apiGroupDetial(Request $request){
       $group_id=$request->get('group_id',0);
       $member_id=$request->get('member_id',0);
       if ($group_id==0) return new JsonResponse(array('code'=>300,'msg'=>'分组ID错误','result'=>''));
       $conn = $this->getDoctrine()
       ->getManager()
       ->getConnection();
       $data=$conn->createQueryBuilder()
             ->select('*')
             ->from('msk_group')
             ->where('id='.$group_id)
             ->execute()
             ->fetch(); 
      if ($data)
      {   $data['img']= $this->getParameter('app_qiniu_imgurl').$data['img'];
          if ($member_id)
          {
              $queryAttent = $conn->createQueryBuilder()
              ->select("m.*")
              ->from('msk_member_attent_group', 'm')
              ->where("m.groupId=$group_id")
              ->andWhere("m.memberId=$member_id")
              ->execute()->fetch();
              if ($queryAttent) $data['isAttent']=1;
              else  $data['isAttent']=0;
          }else{
              $data['isAttent']=0;
          } 
          return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
      }else {
          return new JsonResponse(array('code'=>300,'msg'=>'分组ID错误','result'=>''));
      }
      
    }
    
    
    /**
     * 旅游商品
     * @Route("apitravelgoods",name="apiapitravelgoods_")
     */
    public function apitravelgoods(){
        $data = array();
        $em = $this->getDoctrine()->getManager();
        $conn = $em->getConnection();
        $roomImgQuery = $conn->createQueryBuilder()
            ->select('')
            ->from('msk_index_recommend', 'mhr')
            ->leftJoin('mhr', 'msk_mall_goods', 'p', 'mhr.rec_id=p.goods_id')
            ->leftJoin('mhr', 'msk_mall_goods', 'p', 'mhr.rec_id=p.goods_id')
            ->where("p.state = 1")
            ->andWhere("mhr.type = 0")
            ->setFirstResult(0)
            ->setMaxResults(5)
            ->addOrderBy('p.sort', 'DESC')
            ->addOrderBy('p.addtime', 'DESC')
            ->execute();
    }

     /**
     * 民宿管理员下的民宿详情接口
     * Winson - 20160829
     * @Route("/apihomeInfoDetail",name="apihomeInfoDetail")
     */
    public function apihomeInfoDetailAction(Request $request)
    {
        $token = $request->get("token",0);
        $member_id = $this->validationToken($token);
        if(is_array($member_id)) return new JsonResponse($member_id);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
            ->select('m.id,m.member_id,m.homestay_name,m.homestay_title,homestay_type_id
                    ,m.bottom_price,m.homestay_phone,m.homestay_addr,m.dscp,m.longitude,m.latitude,m.province,m.city,m.district,m.image_url,m.video_url')
            ->from('msk_homestay', 'm')
            ->where("m.member_id=$member_id")
            ->andWhere('m.state=1')
            ->andWhere("m.is_manage=1")
            ->execute()
            ->fetch();
        if(!empty($data)){
            $img=explode(';',$data['image_url']);
            if(is_array($img)){
                foreach ($img as $key => $value) {
                     $data['homestayImg'][] = $this->getParameter('app_qiniu_imgurl').$img[$key];
                }
            }
            unset($data['image_url']);
            $video=explode(';',$data['video_url']);
            if ($data['video_url']){
                $data['video_url'] = $this->getParameter('app_qiniu_imgurl').$video[1];
                $data['video_cover_url'] = $this->getParameter('app_qiniu_imgurl').$video[0];
            }else{
                $data['video_url'] ="";
                $data['video_cover_url'] ="";
            }
             return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
          return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
       }
    }

    /**
     * 某一民宿的房间列表
     * Winson - 20160829
     * @Route("/apihomeRoomList",name="apihomeRoomList")
     */
    public function apihomeRoomListAction(Request $request)
    {
        $homestay_id = $request->get("homestay_id",0);
        $page =$request->get("page",0);
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data = $conn->createQueryBuilder()
            ->select('m.*')
            ->from('msk_room', 'm')
            ->where("m.homestay_id=$homestay_id")
            //->andWhere('m.state=1')
            ->setFirstResult($page)
             ->setMaxResults(10)
            ->execute()
            ->fetchAll();
        if($data){
            foreach ($data as $key => $value) {
                $rid = $data[$key]['id'];
                // 查询房间图片
                $data[$key]['roomImg'] = $conn->createQueryBuilder()
                    ->select('m.goods_image,m.is_default')
                    ->from('msk_images', 'm')
                    ->where("m.homestay_room_id=$rid")
                    ->andWhere('m.img_type=1')
                    ->execute()
                    ->fetchAll();
                $data[$key]['cover_image']=$this->getParameter('app_none_imgurl');
                foreach ($data[$key]['roomImg'] as $k => $v) {
                    if($v['is_default']==1){
                        $data[$key]['cover_image'] = $this->getParameter('app_qiniu_imgurl').$data[$key]['roomImg'][$k]['goods_image'];
                    }
                }
                unset($data[$key]['roomImg']);
                // 房间服务
                // $data[$key]['service'] = $conn->createQueryBuilder()
                //     ->select('m.room_server_id')
                //     ->from('msk_room_server_relation', 'm')
                //     ->where("m.room_id=$rid")
                //     ->execute()
                //     ->fetchAll();
            }
            return new JsonResponse(array('code'=>200,'msg'=>'success','result'=>$data));
        }else {
          return new JsonResponse(array('code'=>300,'msg'=>'暂无数据','result'=>''));
       }
    }
    
    

    
    
}

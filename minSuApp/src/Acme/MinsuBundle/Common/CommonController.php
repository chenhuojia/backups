<?php
namespace Acme\MinsuBundle\Common;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Acme\MinsuBundle\Entity\GroupImg;
use Acme\MinsuBundle\Entity\Coupon;
use Acme\MinsuBundle\Entity\MallOrder;
use Acme\MinsuBundle\Entity\Images;
use Acme\MinsuBundle\Entity\MemberPoints;
use Geo\Geohash;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;


class CommonController extends Controller
{
	protected $user_id = 0;    //用户id
	
	/*  public function __construct()
	{
	    $token=trim($_REQUEST['token']);
	    if(!empty($token) || $token !="0" || $token !=0)
	    {
	        $vToken = $this->myDecode($token);
	        $arrStr = explode('|',$vToken);
	        $time=(int)$arrStr[2];
	        if ($time+(24*60*60*30)<=time())  return  $this->Send(300,'token 过期了 请重新登录!');
	        $conn = $this->getDoctrine()->getManager()->getConnection();
	        $user = $conn->createQueryBuilder()->select('p.*')->from('msk_member', 'p')->where("p.token='$token'")->execute()->fetch();
	        if(!$user)  return $this->Send(300,'您的凭证不正确，请重新登录!');
	        else $this->user_id=(int)$arrStr[1];
	    }else
	        return  $this->Send(300,'登录凭证为空，请重新登录!');
	}  */
	
	/**
	 * 接口返回格式
	 * **/
	protected function Send($code=200,$msg='',$result=''){
	    echo json_encode(array('code'=>$code,'msg'=>$msg,'result'=>$result),JSON_ERROR_RECURSION);
	    die;   
	}
	
	 //验证token
	protected function validationToken($token='0')
	{	
		if(!empty($token) || $token !="0" || $token !=0){
	          $vToken = $this->myDecode($token);
	          $arrStr = explode('|',$vToken);
	          $time=(int)$arrStr[2];
	          if ($time+(24*60*60*30)<=time()){      
	             return array('status'=>0,'error'=>1,'msg'=>'token 过期了 请重新登录!');
	          }
	          $conn = $this->getDoctrine()->getManager()->getConnection();
		      $user = $conn->createQueryBuilder()->select('p.*')->from('msk_member', 'p')->where("p.token='$token'")->execute()->fetch();
	          if(!$user){
	            return array('status'=>0,'error'=>1,'msg'=>'您的凭证不正确，请重新登录!');
	          }else{
	          	$_SESSION = $user;
	          	$this->user_id=(int)$arrStr[1];
	          	return (int)$arrStr[1];
	          }
	    }else{
	        return array('status'=>0,'error'=>1,'msg'=>'登录凭证为空，请重新登录!');
	    }
	}
	
	//读取民宿图片
	protected function getHRImg($img){
		return explode(';', $img);
	}
	
	//校对数组
    protected function checkKeyForArr($par,$arr){
		$s  =0 ;
		for ($i=0 ;$i<count($par);$i++){	
			$key  =$par[$i] ;
			if (key_exists($key,$arr)){
				$s ++ ;
			}else{
				$s= 0; break ;
			}
		}
		return $s;
	}
	
	/**
	 * 获取民宿不重复订单号
	 * **/
   protected function _gen_order_sn()
	{
		/* 选择一个随机的方案 */
		mt_srand((double) microtime() * 1000000);
		$timestamp = time();
		$y = date('y', $timestamp);
		$z = date('z', $timestamp);
		$order_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$query = $conn->createQueryBuilder ()
		->select ("m.*" )->from ( 'msk_order', 'm' )->where("m.order_sn=$order_sn")->execute ();
		$orders = $query->fetch();
		if (empty($orders))		return $order_sn;
		return $this->_gen_order_sn();
	}
	
	
	protected function paypwd_decode($pwd){return 	str_replace("#P-c%?","2",$pwd);}
	
	
	protected function paypwd_encode($pwd){return 	str_replace("2","#P-c%?",$pwd);}
	
	/**
	 * 更改用户余额
	 * **/
	protected function changeTotalBalance($memberId){
		$conn = $this->getDoctrine ()->getManager ()->getConnection ();
		$query = $conn->createQueryBuilder ()
		->select ( "sum(m.income) - sum(m.expend) as total" )
		->from ( 'msk_my_wallet', 'm' )
		->where ( "m.member_id=$memberId" )
		->orderBy("m.add_time","DESC")
		->execute ();
		$data = current($query->fetchAll ());
		$total  =	$data['total'];
		$upb =	$conn->createQueryBuilder ()
    		->update ( 'msk_member', 'm' )
    		->set ('m.available_balance',"'$total'" )
    		->where('m.id = :id' )
    		->setParameter('id',$memberId)
    		->execute ();
		return $upb;
	}
	
	protected function time2string($second){
		$day = floor($second/(3600*24));
		$second = $second%(3600*24);
		$hour = floor($second/3600);
		$second = $second%3600;
		$minute = floor($second/60);
		$second = $second%60;
		return $day.'天'.$hour.'小时'.$minute.'分'.$second.'秒';
	}
	
	/**
	 * 简单对称加密算法之加密
	 * @param String $string 需要加密的字串
	 * @param String $skey 加密EKY
	 * @return String
	 */
	protected function myEncode($string = '')
	{
	    if(empty($string)) return '';
	    $strArr = str_split(base64_encode($string));
	    $strCount = count($strArr);
	    foreach (str_split('123456') as $key => $value)
	        $key < $strCount && $strArr[$key] .= $value;
	    return str_replace(array('+','/'), array('-','_'), join('', $strArr));
	}

	/**
	 * 简单对称加密算法之解密
	 * @param String $string 需要解密的字串
	 * @param String $skey 解密KEY
	 * @return String
	 */
	protected function myDecode($string = '')
	{
	    if(empty($string)) return '';
	    $strArr = str_split(str_replace(array('-','_'),array('+','/'),  $string), 2);
	    $strCount = count($strArr);
	    foreach (str_split('123456') as $key => $value)
	        $key <= $strCount && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
	    return base64_decode(join('', $strArr));
	}
	
	/**
	 * 获取商城不重复订单号 20位
	 * **/
    protected function get_order_sn()
    	{
    	    mt_srand((double) microtime() * 1000000);
    	    $timestamp = time();
    	    $y = date('ymdHis', $timestamp);
    	    $z = date('z', $timestamp);
    	    $order_sn = $y . str_pad($z, 3, '0', STR_PAD_LEFT) . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
    	    $conn = $this->getDoctrine ()->getManager ()->getConnection ();
    	    $query = $conn->createQueryBuilder ()
    	    ->select ("m.*" )->from ( 'msk_mall_order', 'm' )->where("m.order_sn=$order_sn")->execute ();
    	    $orders = $query->fetch();
    	    if (empty($orders))
    	    {
    	        return $order_sn;
    	    }
    	    return $this->get_order_sn();
    	}
	
   /**
    * 获取数据库对象
    * @return obj
    * **/
	protected  function conn(){
	    return $this->getDoctrine ()->getManager ()->getConnection ();
	}

	
	/**
	 * 获取商城生成订单统计信息
	 * @return array
	 * **/
	protected function get_order($user_id,$goods_id,$goods_num,$spec_id,$address_id,$coupon_id=0){
    	    $conn=$this->conn();
    	    if ($spec_id){
    	        $data['goods']=$conn->createQueryBuilder ()
                	    ->select('g.goods_id,g.goods_name,gs.name as spec_key,sg.id as spec_id,sg.key_name as spec_key_name,sg.store_count,g.market_price,sg.price,g.is_free_shipping,i.image_url')
                	    ->from('msk_mall_goods','g')
                	    ->leftJoin('g','msk_spec_goods_price','sg','g.goods_id=sg.goods_id')
                	    ->leftJoin('g','msk_goods_images','i','g.goods_id=i.goods_id')
                	    ->leftJoin('sg','msk_goods_spec','gs','sg.type_id=gs.id')
                	    ->where('g.goods_id='.$goods_id,'sg.id='.$spec_id,'i.type=1')
                	    ->execute()
                	    ->fetch();
    	    }else{
    	        $data['goods']=$conn->createQueryBuilder ()
        	        ->select('g.goods_id,g.goods_name,sg.`key` as spec_key,sg.id as spec_id,sg.key_name as spec_key_name,g.store_count,g.market_price,g.shop_price as price,g.is_free_shipping,i.image_url')
        	        ->from('msk_mall_goods','g')
        	        ->leftJoin('g','msk_spec_goods_price','sg','g.goods_id=sg.goods_id')
        	        ->leftJoin('g','msk_goods_images','i','g.goods_id=i.goods_id')
        	        ->where('g.goods_id='.$goods_id)
        	        ->execute()
        	        ->fetch();
    	    }
    	    if ($data['goods']['store_count'] <= $goods_num+1) return array('code'=>-1,'msg'=>'库存不足');
    	    $data['goods']['goods_num']=$goods_num;
    	    if ($data['goods']['is_free_shipping']==0){
    	        $stock= $conn->createQueryBuilder ()
                	        ->select("stock")
                	        ->from('msk_goods_shipping')
                	        ->where('goods_id='.$goods_id)
                	        ->execute()->fetch();
        	    $data['goods']['stock']=$stock['stock'];
    	    }else $data['goods']['stock']=0;	   
    	    
    	    if ($address_id){
    	        $data['userinfo']=$conn->createQueryBuilder ()
            	        ->select("address_id,consignee,city,address,mobile")
            	        ->from('msk_user_address')
            	        ->where('address_id='.$address_id)
            	        ->execute()->fetch();
            }else{
                $data['userinfo']=$conn->createQueryBuilder ()
                        ->select("address_id,consignee,city,address,mobile")
                        ->from('msk_user_address')
                        ->where('user_id='.$user_id)
                        ->execute()->fetchAll();
            } 
    	    $data['goods_price']=bcmul($goods_num,$data['goods']['price'],2);
    	    $data['total_amount']=bcadd($data['goods_price'],$data['goods']['stock'],2);
    	    if ($coupon_id){
    	       $coupon=$conn->createQueryBuilder ()
                        ->select("coupon_value,min_amount")
                        ->from('msk_buyer_coupon')
                        ->where('id='.$coupon_id,'state=0')
                        ->execute()
                        ->fetch();      
    	       if ($data['goods_price'] >= $coupon['min_amount']){
    	           $data['coupon_price']   =   $coupon['coupon_value'];
    	           $data['order_amount']   =   bcsub($data['total_amount'],$coupon['coupon_value'],2);
    	       }else{
    	           $data['coupon_price']=0;
    	           $data['order_amount']=$data['total_amount'];
    	       }
    	    }else{
    	        $data['coupon_price']=0;
    	        $data['order_amount']=$data['total_amount'];
    	    }
    	    return array('code'=>200,'msg'=>'success','result'=>$data);
	}
	
	
	/**
	 * 生成商城订单
	 * @return String
	 **/
	protected function addOrder($user_id,$goods_id,$spec_id,$order_sn,$order_info,$coupon_id=0){
	    $shop=$this->conn()->createQueryBuilder ()->select("shop_id")->from('msk_shop_goods')
	    ->where('goods_id='.$goods_id)->execute()->fetch();
	    $d=time();
	    $mallorder = new MallOrder();
 	    $mallorder->setOrderSn($order_sn);
	    $mallorder->setUserId($user_id);
	    $mallorder->setShopId($shop['shop_id']);
	    $mallorder->setConsignee($order_info['userinfo']['consignee']);
	    $mallorder->setCity($order_info['userinfo']['city']);
	    $mallorder->setAddress($order_info['userinfo']['address']);
	    $mallorder->setMobile($order_info['userinfo']['mobile']);
	    $mallorder->setGoodsPrice($order_info['goods_price']);
	    $mallorder->setGoodsNum($order_info['goods']['goods_num']);
	    $mallorder->setShippingPrice($order_info['goods']['stock']);
	    $mallorder->setCouponPrice($order_info['coupon_price']);
	    $mallorder->setOrderAmount($order_info['order_amount']);
	    $mallorder->setTotalAmount($order_info['total_amount']);  
	    $mallorder->setAddTime($d);
	    $mallorder->setTime_left(60*15);
	    $this->getDoctrine ()->getManager ()->persist($mallorder);
	    $this->getDoctrine ()->getManager ()->flush();
	    $order_id=$mallorder->getOrderId();
	    if ($order_id){
	        $goods=array(
	            'order_id'=>$order_id,
	            'goods_id'=>$goods_id,
	            'goods_name'=>$order_info['goods']['goods_name'],
	            'goods_num'=>$order_info['goods']['goods_num'],
	            'market_price'=>$order_info['goods']['market_price'],
	            'goods_price'=>$order_info['goods']['price'],
	            'spec_key'=>$order_info['goods']['spec_key'],
	            'goods_spec_price_id'=>$order_info['goods']['spec_id'],
	            'spec_key_name'=>$order_info['goods']['spec_key_name'],
	        );
	        $a=$this->conn()->insert('msk_mall_order_goods',$goods);
	        if ($coupon_id){
	            $b=$this->conn()->createQueryBuilder()
	            ->update('msk_buyer_coupon')
	            ->set('state',1)
	            ->where('id='.$coupon_id)
	            ->execute();
	        }
	        return $order_id.'|'.$d;
	    }
	}
	
	/**
	 *添加商城订单记录
	 **/
	protected function addorderlog($order_id,$user_id,$msg){
	    $order=$this->conn()->createQueryBuilder()
    	    ->select('order_status,shipping_status,pay_status')
    	    ->from('msk_mall_order')
    	    ->where('order_id='.$order_id)
    	    ->execute()
    	    ->fetch();
        $data=array(
            'order_id'=>$order_id,
            'action_user'=>$user_id,
            'order_status'=>$order['order_status'],
            'shipping_status'=>$order['shipping_status'],
            'pay_status'=>$order['pay_status'],
            'action_note'=>$msg,
            'log_time'=>time(),
        );
	    $a=$this->conn()->insert('msk_mall_order_action',$data);
	    return $a;
	}
	
	/**
	 * 更新商城商品数量
	 * **/
	protected function upgoods($goods_id,$goods_num,$spec_id){
	    $order=$this->conn()->createQueryBuilder()
	    ->select('p.store_count,gs.store_count as spec_count,p.sales_sum')
	    ->from('msk_mall_goods','p')
	    ->leftJoin('p','msk_spec_goods_price','gs','p.goods_id=gs.goods_id')
	    ->where('p.goods_id='.$goods_id,'gs.id='.$spec_id)
	    ->execute()
	    ->fetch();
	    $dec=$order['store_count']-$goods_num;
	    $a=$this->conn()->createQueryBuilder()
	    ->update('msk_mall_goods')
	    ->set('store_count',$dec)
	    ->set('sales_sum',$order['sales_sum']+$goods_num)
	    ->where('goods_id='.$goods_id)
	    ->execute();
	    $dec=$order['spec_count']-$goods_num;
	    $b=$this->conn()->createQueryBuilder()
	    ->update('msk_spec_goods_price')
	    ->set('store_count',$dec)
	    ->where('id='.$spec_id)
	    ->execute();
	    if ($a && $b){
	        return true;
	    }else{
	        return false;
	    }
	}

    /**
     *发送验证码信息
     **/
   protected function SendMessages($phone,$code,$type ='0',$api="https://webapi.sms.mob.com")
    {
        if($type ==0){
             $appkey = "13184cdc0d8d8";
        }else{
             $appkey = "1294186ee92da";
        }
        // 发送验证码
        $response = $this->postRequest($api . '/sms/verify', array(
            'appkey' => $appkey,
            'phone' => $phone,
            'zone' => '86',
            'code' => $code,
        ));

        return $response;
    }

    /**
     * 发起一个post请求到指定接口
     *
     * @param string $api 请求的接口
     * @param array $params post参数
     * @param int $timeout 超时时间
     * @return string 请求结果
     */
   protected function postRequest($api, array $params = array(), $timeout = 30)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        // 以返回的形式接收信息
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 设置为POST方式
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        // 不验证https证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
            'Accept: application/json',
        ));
        // 发送数据
        $response = curl_exec($ch);
        // 不要忘记释放资源
        curl_close($ch);
        return $response;
    }

    /**
     * 根据经纬度得出的值
     * @param [type] $longitude 经度
     * @param [type] $latitude  纬度
     */
    protected function addGeohash($longitude,$latitude)
    {   
    	require_once '../vendor/geohash.class.php';
		$geohash = new Geohash();
		//得到这点的hash值
		$hash = $geohash->encode($latitude, $longitude);
		return $hash;
    }

    
    /**
     * 积分新增
     * 20160830 -Winson
     */
    protected function addMemberPoints($memberId,$points,$desc,$eng)
    {
        $manager = $this->getDoctrine()->getManager();
        $conn=$manager->getConnection();
        if ($memberId != "" && $desc != "") {
            $hsdata = new MemberPoints();
            $hsdata->setPl_memberid($memberId);
            $hsdata->setPl_points($points);
            $hsdata->setPl_eng($eng);
            $hsdata->setPl_desc("$desc");
            $hsdata->setPl_addtime(time());
            try {
                $manager->persist($hsdata);
                $manager->flush();
                if ($hsdata->getId()) {
                    $data = $this->changeTotalPoints($memberId);
                    $total_points = $data['totalPoints'];
                    if ($total_points) {
                        $conn->createQueryBuilder()
                        ->update('msk_member', 'm')
                        ->set('m.member_points', $total_points)
                        ->where("m.id =$memberId")
                        ->execute();
                    }
                }
            } catch (Exception $e) {
                $manager->rollback();
                $manager->close();
                return false;
            }
        } else {
            return false;
        }
        return true;
    }
    
    /**
     * 积分配置查询
     * 20160830 -Winson
     */
    protected function getpoint($key){
        $manager = $this->getDoctrine()->getManager();
         
        $conn = $manager->getConnection ();
         
        $points=$conn->createQueryBuilder()->select('*')
        ->from('msk_points_config')
        ->where('disable=0','keyword='."'$key'")
        ->execute()->fetch();
        if ($points) return $points;
        else return false;
    }
    
    /**
     * 用户积分
     * 20160830 -Winson
     */
    protected function changeTotalPoints($memberId)
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $query = $conn->createQueryBuilder()
            ->select("sum(m.pl_points) - sum(m.pl_eng) as totalPoints")
            ->from('msk_points', 'm')
            ->where("m.pl_memberid=$memberId")
            ->orderBy("m.pl_addtime", "DESC")
            ->execute();
        $data = $query->fetchAll();
        return current($data);
    }
    
    
    /**
     * 添加订单提醒
     * 20160830 -Winson
     */
    protected function addOrdersRemind($member_id,$order_id,$type){
        $data=array(
            'member_id'=>$member_id,
            'order_id'=>$order_id,
            'add_time'=>time(),
            'read_time'=>0,
            'type'=>$type,//0跟团1导游2民宿3商品
            'is_read'=>0
        );
        return $this->conn()->insert('msk_order_remind',$data);
    }

    /**
     * 添加图片
     * 20160830 -Winson
     */
    protected function imagePost($memberId, $imageArray, $homestay_room_id, $ItemName, $imgTpe)
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
     * 七牛上传
     * **/
    protected function upload_qiniu($filename,$bucket='minsu2',$createName,$urlPrefix){
        require_once '../vendor/php-sdk/autoload.php';
        // 引入鉴权类
        require_once '../vendor/php-sdk/src/Qiniu/Auth.php';
        // 引入上传类
        require_once '../vendor/php-sdk/src/Qiniu/Storage/UploadManager.php' ;
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
        // 构建鉴权对象
        $auth = new Auth($accessKey,$secretKey,$createName);
        // 要上传的空间
        $bucket = $bucket;
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
        // 要上传文件的本地路径
        $filePath = $filename;
        // 上传到七牛后保存的文件名
        $key = $createName;
        // 初始化 UploadManager 对象并进行文件的上传
        $uploadMgr = new UploadManager();
        // 调用 UploadManager 的 putFile 方法进行文件的上传
        list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
        if ($err !== null) {
            return false;
        } else {
            return $urlPrefix.$ret['key'];
            //return 'http://od7pk0gpw.bkt.clouddn.com/'.$ret['key'];
        }
    }

    /**
	 * 求两个日期之间相差的天数
	 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
	 * @param string $day1
	 * @param string $day2
	 * @return number
	 */
	protected function diffBetweenTwoDays ($day1, $day2)
	{
	  $second1 = strtotime($day1);
	  $second2 = strtotime($day2);
	   
	  if ($second1 < $second2) {
	    $tmp = $second2;
	    $second2 = $second1;
	    $second1 = $tmp;
	  }
	  return ($second1 - $second2) / 86400;
	}

	/**
	 * 友好时间显示
	 * @param $time
	 * @return bool|string
	 */
	function friend_date($time)
	{
	    if (!$time)
	        return false;
	    $fdate = '';
	    $d = time() - intval($time);
	    $ld = $time - mktime(0, 0, 0, 0, 0, date('Y')); //得出年
	    $md = $time - mktime(0, 0, 0, date('m'), 0, date('Y')); //得出月
	    $byd = $time - mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')); //前天
	    $yd = $time - mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')); //昨天
	    $dd = $time - mktime(0, 0, 0, date('m'), date('d'), date('Y')); //今天
	    $td = $time - mktime(0, 0, 0, date('m'), date('d') + 1, date('Y')); //明天
	    $atd = $time - mktime(0, 0, 0, date('m'), date('d') + 2, date('Y')); //后天
	    if ($d == 0) {
	        $fdate = '刚刚';
	    } else {
	        switch ($d) {
	            case $d < $atd:
	                $fdate = date('Y年m月d日', $time);
	                break;
	            case $d < $td:
	                $fdate = '后天' . date('H:i', $time);
	                break;
	            case $d < 0:
	                $fdate = '明天' . date('H:i', $time);
	                break;
	            case $d < 60:
	                $fdate = $d . '秒前';
	                break;
	            case $d < 3600:
	                $fdate = floor($d / 60) . '分钟前';
	                break;
	            case $d < $dd:
	                $fdate = floor($d / 3600) . '小时前';
	                break;
	            case $d < $yd:
	                $fdate = '昨天' . date('H:i', $time);
	                break;
	            case $d < $byd:
	                $fdate = '前天' . date('H:i', $time);
	                break;
	            case $d < $md:
	                $fdate = date('m月d日 H:i', $time);
	                break;
	            case $d < $ld:
	                $fdate = date('m月d日', $time);
	                break;
	            default:
	                $fdate = date('Y年m月d日', $time);
	                break;
	        }
	    }
	    return $fdate;
	}
    
	
	//是否已经民宿认证
	protected function IsMemberToAuthenticate($member_id=0)
	{
	    if($member_id >0){
	        $conn = $this->getDoctrine()->getManager()->getConnection();
	        $data = $conn->createQueryBuilder ()
	        ->select('id')
	        ->from("msk_homestay" )
	        ->where( "member_id =".$member_id )
	        ->andWhere("is_manage=1")
	        ->execute()
	        ->fetch();
	        if(!empty($data)) return $data['id'];
	        else return false;
	    }
	    return false;
	}
	
	
    /**
     * 义工认证
     * **/
	protected function FindGroupName($group_id){
	    if (empty($group_id)){ $this->Send(300,'分组不存在2');}
	    $data=$this->conn()->createQueryBuilder()->select('*')->from('msk_group')->where('id='.$group_id)->execute()->fetch();
	    if (empty($data)) { $this->Send(300,'分组不存在3');}
	    return $data;
	}

	/**
     * 获取用户信息
     */
    protected function getMemberInfo($memberId)
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $user = $conn->createQueryBuilder()
            ->select("*")
            ->from('msk_member', 'm')
            ->where("m.id=$memberId")
            ->execute()
            ->fetch();
        $info = $conn->createQueryBuilder()
            ->select("p.nickname")
            ->from('msk_member_info', 'p')
            ->where("p.member_id=$memberId")
            ->execute()
            ->fetch();
        $user['nickname'] = $info['nickname'];
        return $user;
    }

    //融云
     protected function curl($action, $params, $contentType = 'urlencoded', $module = 'im', $httpMethod = 'POST')
    {
        switch ($module) {
            case 'im':
                $action = 'http://api.cn.ronghub.com' . $action . '.' . 'json';
                break;
            case 'sms':
                $action = 'http://api.sms.ronghub.com' . $action . '.json';
                break;
            default:
                $action = 'http://api.cn.ronghub.com' . $action . '.' . 'json';
        }
        $nonce = mt_rand();
        $timeStamp = time();
        $appSecret = 'KPQRVIj4HE';
        $appKey = 'n19jmcy5ni2x9';
        $sign = sha1($appSecret . $nonce . $timeStamp);
        $httpHeader = array(
            'RC-App-Key:'.$appKey,
            'RC-Nonce:'.$nonce,
            'RC-Timestamp:'.$timeStamp,
            'RC-Signature:'.$sign,
        );;
        $ch = curl_init();
        if ($httpMethod == 'POST' && $contentType == 'urlencoded') {
            $httpHeader[] = 'Content-Type:application/x-www-form-urlencoded';
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_query($params));
        }
        if ($httpMethod == 'POST' && $contentType == 'json') {
            $httpHeader[] = 'Content-Type:Application/json';
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        if ($httpMethod == 'GET' && $contentType == 'urlencoded') {
            $action .= strpos($action, '?') === false ? '?' : '&';
            $action .= $this->build_query($params);
        }
        curl_setopt($ch, CURLOPT_URL, $action);
        curl_setopt($ch, CURLOPT_POST, $httpMethod == 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpHeader);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //处理http证书问题
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ret = curl_exec($ch);
        if (false === $ret) {
            $ret = curl_errno($ch);
        }
        curl_close($ch);
        return $ret;
    }

    protected function build_query($formData, $numericPrefix = '', $argSeparator = '&', $prefixKey = '') {
        $str = '';
        foreach ($formData as $key => $val) {
            if (!is_array($val)) {
                $str .= $argSeparator;
                if ($prefixKey === '') {
                    if (is_int($key)) {
                        $str .= $numericPrefix;
                    }
                    $str .= urlencode($key) . '=' . urlencode($val);
                } else {
                    $str .= urlencode($prefixKey) . '=' . urlencode($val);
                }
            } else {
                if ($prefixKey == '') {
                    $prefixKey .= $key;
                }
                if (is_array($val[0])) {
                    $arr = array();
                    $arr[$key] = $val[0];
                    $str .= $argSeparator . http_build_query($arr);
                } else {
                    $str .= $argSeparator . $this->build_query($val, $numericPrefix, $argSeparator, $prefixKey);
                }
                $prefixKey = '';
            }
        }
        return substr($str, strlen($argSeparator));
    }
	
    
    protected function addmynote($user_id,$type,$note_id,$title,$img){
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $data=array(
            'member_id'=>$user_id,
            'type'=>$type,
            'note_id'=>$note_id,
            'title'=>$title,
            'image_url'=>$img,
            'addtime'=>$_SERVER['REQUEST_TIME'],
            'is_show'=>1,
        );
        return $conn->insert('msk_my_note',$data);
    }
    
    
    protected function addmynotecount($user_id,$minsu,$sense,$yigong,$daoyou,$lvyoutuan){
        $conn = $this->getDoctrine()->getManager()->getConnection();
        $conn->createQueryBuilder()
            ->update('msk_member_info')
            ->set('my_minsu','my_minsu+'.$minsu)
            ->set('my_sense','my_sense+'.$sense)
            ->set('my_yigong','my_yigong+'.$yigong)
            ->set('my_daoyou','my_daoyou+'.$daoyou)
            ->set('my_lvyoutuan','my_lvyoutuan+'.$lvyoutuan)
            ->where('member_id='.$user_id)
            ->execute();
    }
    
    


    /**
     * 驴友帮民宿列表
     * **/
    protected function gethomestayList($orderlist,$offset,$avatarPath,$where=''){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.id', 'p.member_id','p.homestay_title','p.addtime','p.city','p.is_manage','c.avatar','e.nickname','p.image_url,p.video_url')
        ->from('msk_homestay', 'p')
        ->leftjoin('p', 'msk_member', 'c', 'p.member_id = c.id')
        ->leftjoin('p', 'msk_member_info', 'e', 'p.member_id = e.member_id')
        ->where($orderlist)
        ->setFirstResult($offset)
        ->setMaxResults(10)
        ->orderBy('p.addtime','DESC')
        ->addOrderBy('p.sort', 'DESC')
        ->execute()->fetchAll();
        $tmp=array();
        if ($data){
            foreach ($data as $k=>$v){
                if ($v['is_manage']==1){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_order",'p')
                    ->leftJoin('p','msk_order_evaluation','m','p.order_sn=m.order_sn')
                    ->where('p.homestay_id='.$v['id'])
                    ->execute()
                    ->fetch();
                }else {
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_homestay_share_eval")
                    ->where('state=0','homestay_id='.$v['id'],'pid=0')
                    ->execute()
                    ->fetch();
                }
                $tmp[$k]['id']=$v['id'];
                $tmp[$k]['type']=1;
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['homestay_title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['addtime']);
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
     * 驴友帮景点列表
     * **/
    protected function getjingdianList($orderlist,$offset,$avatarPath,$where=''){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.travel_title', 'p.member_id','p.id','p.addtime','c.travel_note_image','m.avatar','e.nickname')
        ->from('msk_travel_note', 'p')
        ->leftjoin('p', 'msk_travel_note_images', 'c', 'p.id = c.travel_note_id')
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
                ->from("msk_post_discuss")
                ->where('type=1','comPostId='.$v['id'],'discussParentId=0')
                ->execute()
                ->fetch();
                $tmp[$k]['id']=$v['id'];
                $tmp[$k]['type']=2;
                $tmp[$k]['member_id']=$v['member_id'];
                $tmp[$k]['title']=$v['travel_title'];
                $tmp[$k]['addtime']=date('m月d日  H:i',$v['addtime']);
                $tmp[$k]['avatar'] = $avatarPath . $v['avatar'];
                $tmp[$k]['nickname'] =$v['nickname'];
                $tmp[$k]['img'] = $avatarPath.$v['travel_note_image'];
                $tmp[$k]['comment_count']=$comment['total'];
            }
        }
        return $tmp;
    }
    
    
    /**
     * 我的帖子
     * **/
    protected function mynotelist($where,$offset){
        $conn = $this->conn();
        $data = $conn->createQueryBuilder()
        ->select('p.id','p.note_id', 'p.member_id','p.title','p.addtime','e.avatar','m.nickname','p.image_url','p.type')
        ->from('msk_my_note', 'p')
        ->leftJoin('p','msk_member_info','m','p.member_id=m.member_id')
        ->leftJoin('p','msk_member','e','p.member_id=e.id')
        ->where($where)
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
                    ->where('state=0','homestay_id='.$v['note_id'],'pid=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==2){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_post_discuss")
                    ->where('type=1','comPostId='.$v['note_id'],'discussParentId=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==3){
                    $comment=$conn->createQueryBuilder()
                    ->select("discussNum as total")
                    ->from("msk_community_post")
                    ->where('id='.$v['note_id'])
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==4){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(comment_id) as total,avg(service_quality) as avge")
                    ->from("msk_guide_comment")
                    ->where('guide_id='.$v['note_id'],'pid=0','kind=0')
                    ->execute()
                    ->fetch();
                }
                if ($v['type']==5){
                    $comment=$conn->createQueryBuilder()
                    ->select("count(id) as total,avg(grade) as avge")
                    ->from("msk_tour_pal_comment")
                    ->where('is_show=1 and type=1 ','for_id='.$v['note_id'],'fid=0')
                    ->execute()
                    ->fetch();
                }
                $tmp[$k]['id']=$v['note_id'];
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


    function rsaVerify($prestr, $sign) {
        $sign = base64_decode($sign);
        $dir=dirname(__FILE__);
        $public_key= file_get_contents($dir.'/alipay_public_key.pem');
        $pkeyid = openssl_get_publickey($public_key);
        if ($pkeyid) {
            $verify = openssl_verify($prestr, $sign, $pkeyid);
            openssl_free_key($pkeyid);
        }
        if($verify == 1){
            return  1;
        }else{
            return 0;
        }
    }
    
    function createLinkstring($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
    
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
    
        return $arg;
    }
    
    /**
     * 排序
     * **/
    public function argSort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    //二维数组去重  
    function assoc_unique($arr, $key) {
        $tmp_arr = array();
        foreach ($arr as $k => $v) {
            if (in_array($v[$key], $tmp_arr)) {//搜索$v[$key]是否在$tmp_arr数组中存在，若存在返回true
                unset($arr[$k]);
            } else {
                $tmp_arr[] = $v[$key];
            }
        }
        sort($arr); //sort函数对数组进行排序
        return $arr;
    }
}
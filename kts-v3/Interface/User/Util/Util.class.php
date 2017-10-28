<?php
namespace User\Util;

class Util
{
   private static $avatar='avatar';
   private static $name='name';    
   private static $redis_avtar_DB=1;    
    /**
     *
     *
     * @param unknown $userid
     *            用户id
     * @param unknown $image
     * 保存头像和商店logo
     */
    public static function saveJpg($userid,$path)
    {
        $avatar =(crc32($userid)%1000).'/';
         $upload = new \Think\Upload( array(
        'maxSize'       =>  0, //上传的文件大小限制 (0-不做限制)
        'exts'          =>  array('png','jpg'), //允许上传的文件后缀
        'autoSub'       =>  false, //自动子目录保存文件
        'rootPath'      =>  $path, //保存根路径
        'savePath'      =>$avatar, //保存路径
        'saveName'      =>$userid.'', //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'       =>  '', //文件保存后缀，空则使用原后缀
        'replace'       =>  true, //存在同名是否覆盖
        'hash'          =>  true, //是否生成hash编码
        'callback'      =>  false, //检测文件是否存在回调，如果存在返回文件信息数组
    ));// 实例化上传类
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            return message(300,$upload->getError());
        }else{// 上传成功 获取上传文件信息
            return message(200, '上传成功');
        }
    }
    
     /**
      * 设置头像
      */
     public static function Set_Avtar($userid){
        $message= self::saveJpg($userid, './Public/avatar/');
         if($message['error']==200){
             $url=self::getAvtarUrl($userid,'/Public/avatar/','');
             GetRedisConn(self::$redis_avtar_DB)->hSet(''.$userid, self::$avatar, $url.'');
             return message(200,'设置成功');
         }
         return message(300,'设置失败');

     }
    /**
     * 设置 用户昵称
     */
    public static function SetUserNickName($userid,$name,$y=TRUE){
        if(GetRedisConn(self::$redis_avtar_DB)->sAdd('allnames',$name.'')){
            self::del($userid, $name);
           GetRedisConn(self::$redis_avtar_DB)->hSet(''.$userid, self::$name, $name.'');
           return true;
        }
       return false;
    }
    /**
     * 获取头像路径
     */
    private static function getAvtarUrl($userid,$path,$base)
    {
        $avatar =(crc32($userid)%1000).'/';
        $filename =$base.$path.$avatar.$userid.'.png';
        return is_file('.'.$path.$avatar.$userid.'.png')?$filename:$base.$path.'avatar.png';
    }
    
    /**
     * @param unknown $userid
     * @param unknown $nike
     * @param unknown $avatar
     * @return boolean
     * 设置用户头像和昵称
     */
    public static function  SetAll($userid,$nike,$avatar){
        GetRedisConn(self::$redis_avtar_DB)->multi();
        if(GetRedisConn(self::$redis_avtar_DB)->sAdd('allnames',$nike)){
        self::del($userid,$nike);
        GetRedisConn(self::$redis_avtar_DB)->HmSet(''.$userid,array(self::$name=>$nike.'', self::$avatar=>$avatar.''));
        GetRedisConn(self::$redis_avtar_DB)->exec();
        return true;
        }
        return false;
        
    }
  private static function del($userid,$nike){
        $old=GetRedisConn(self::$redis_avtar_DB)->hGet(''.$userid,self::$name);
        GetRedisConn()->sRemove('allnames',$old);
    }
    /**
     * 获取单个用户 头像和昵称
     */
    public static function GetUserAvatrAndNick($userid){
        $Info=GetRedisConn(self::$redis_avtar_DB)->hGetAll($userid);//TODO加过期
        if(empty($Info[self::$name])||empty($Info[self::$avatar])){
            $url=self::getAvtarUrl($userid,'/Public/avatar/','');
            if(empty($Info[self::$name])){
            $Info[self::$name]= 'kts_'.date('md').mt_rand();
            }
            self::SetAll($userid,$Info[self::$name], $url);
            $Info[self::$avatar]=C('WEB_PATH').$url;
            return $Info;
        }
       
        $Info[self::$avatar]= C('WEB_PATH').$Info[self::$avatar];
        return $Info;
    }

    //生成用户名
    public function addUserName()
    {    
        $name='kts_'.date('md').mt_rand();
        $user =M('user')->where(array('name'=>$name))->count(1);
        if($user){
            self::addUserName();
        }
        return $name;
    }

    /**
     * 获取 店铺 logo和名字
     */
    public static function GetShopLogoAndName($userid){
        GetRedisConn()->select(2);
    }
    /**
     * 保存店铺logo
     */
    public function saveShopAvtar($shopId, $image)
    {
        $Temavatar .= self::$avatar . base_convert(md5($shopId)) % 1000;
        $filename = $Temavatar . $shopId . 'shop.jpg';
        $f = fopen($filename, "a+");
        fclose($f);
    }

    /**
     * 获取Shoplogo路径
     */
    public static function getShopLogoUrl($shopId,$path,$base)
    {
         $avatar =(crc32($shopId)%1000).'/';
        $filename =$base.$path.$avatar.$shopId.'.jpg';
        return $filename;
    }

    
     
}
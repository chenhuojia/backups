<?php
/**
 * 后台公共类，做基础服务
 * 用户检查，权限检查，获取菜单
 */
namespace Common\Controller;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;
class AdminController extends BaseController
{
    /**
     * 初始化后台数据
     */
    public function _initialize()
    {
        BaseController::_initialize();

//        // 删除缓存
//        $cache = S('Category');
//        unset($cache);
//        S('Category',null);
//        //刷新栏目索引缓存开始
//        $CA=M('category');
//        $data = $CA->order("listorder ASC")->select();
//        $CategoryIds = array();
//        foreach ($data as $r) {
//            $CategoryIds[$r['cat_id']] = array(
//                'cat_id' => $r['cat_id'],
//                'parentid' => $r['parentid'],
//            );
//        }
//        cache("Category", $CategoryIds,'2');//暂时定为2秒
//        //刷新栏目索引缓存结束
        
        $module = strtolower(MODULE_NAME);
        $controller = strtolower(CONTROLLER_NAME);
        $action = strtolower(ACTION_NAME);
        //过滤不需要登录的操作
        $NOUser = array('login', 'logout','verify');
        // 过滤不需要检验权限的操作
        if (!in_array($action, $NOUser)) {
            $this->CheckUser();
            //首页不验证权限
            if ($module == 'admin' and $controller == 'index' and $action == 'index') {
            } else {
                //权限验证
                if(!authCheck(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,session('uid'))){
                    $this->addLog(MODULE_NAME."/".CONTROLLER_NAME."/".ACTION_NAME,2);// 记录操作日志
                    session('link', null);
                    $this->error('你没有权限!'.session('uid'));

                }
            }
        }
        //获取左边菜单
//        $this->getMenu();

    }

    //写入日志
    public function addLog( $doing,$status = 1 ) {
        $logData=M('system_action_log');
        $data = array();
        $data['ip']    = get_client_ip();
        $data['time']  = date("Y-m-d H:i:s");
        $data['user_name'] = session('userName');
        $data['user_id'] = session('uid');
        $data['module'] = MODULE_NAME;
        $data['action'] = ACTION_NAME;
        $data['querystring'] = $doing;
        $data['status'] = $status;
        $logData->data($data)->add();
    }

    //写入登录日志 by chen
    public function insert_log($userid,$username,$password,$status,$loginip,$login_location=''){
        $LoginLogData=M('system_login_log');
        $LogData = array();
        $LogData['user_id']=$userid;
        $LogData['user_name']=$username;
        $LogData['login_time']=date("Y-m-d H:i:s");
        $LogData['login_ip']=$loginip;
        $LogData['login_ip']=$login_location;
        $LogData['status']=$status;
        $LogData['log_password']=$password;
        $LoginLogData->data($LogData)->add();
    }

    /**
     * 检查用户相关信息
     * 是否登录,记录一些信息
     */
    public function CheckUser()
    {
        $this->uid = session('uid'); //用户id
        //检查是否登录
        if (isset ($this->uid)) {
            $this->userName = session('userName'); //用户名称
            $this->remark = session('remark'); //用户权限
            return true;
        } else {
            session('link', MODULE_NAME . '/' . CONTROLLER_NAME . '/' . ACTION_NAME);
            $this->redirect('Admin/User/Login');
        }
    }

    /**
     * 获取管理后台左边菜单
     * 不同的角色获取不同的菜单
     */
    public function getMenu()
    {
        $P = M('privileges');
        if ($this->privileges != 'ALL') {
            $privileges = explode(',', $this->privileges);
            foreach ($privileges as $k => $v) {
                if ($k == 0) {
                    $where = 'privilege_id = ' . $privileges [$k];
                } else {
                    $where .= ' or privilege_id = ' . $privileges [$k];
                }
            }
        }
        
        //获取角色所有的权限对应的菜单
        $menu = $P->field(true)->where($where)->order('sort')->select();
        $module_name = strtolower(MODULE_NAME);
        $controller_name = strtolower(CONTROLLER_NAME);
        $action_name = strtolower(ACTION_NAME);
        foreach ($menu as $key => $value) {
            //把数据库字段转小写
            $module_nameS = strtolower($value['module_name']);
            $controller_nameS = strtolower($value['controller_name']);
            $action_nameS = strtolower($value['action_name']);
            $menu[$key]['url'] = U($value['module_name'] . '/' . $value['controller_name'] . '/' . $value['action_name']);
            if ($module_name == $module_nameS and $controller_name == $controller_nameS and $action_name == $action_nameS) {
                // 给当前和上级菜单增加选中
                $menu [$key]['active'] = 'active';
                foreach ($menu as $k => $v) {
                    if ($menu[$k]['privilege_id'] == $menu[$key]['pid']) {
                        $menu[$k]['active'] = 'active';
                    }
                }
            }
        }
        $this->menu = $this->TreeArray($menu, 'privilege_id', 'pid', '0', 'sub', '1');
        // die(var_dump($this->menu));
    }

    /**
     * 公共的递归，支持多级遍历
     * @param array $Array 需要递归的数组
     * @param int $ID 数组id键名
     * @param string $PinNmae 上级id键名
     * @param int $Pid 开始的id
     * @param string $SubNmae 递归后下级的键名
     * @param string $menu 为1只显示菜单
     */
    public function TreeArray($Array, $Id = 'id', $PinNmae = 'pid', $Pid = '0', $SubNmae = 'sub', $menu = '0')
    {
        foreach ($Array as $k => $v) {
            if ($Pid) {
                if ($Array [$k] [$PinNmae] == $Pid) {
                    $Arr = $Array [$k];
                    $Arr [$SubNmae] = $this->TreeArray($Array, $Id, $PinNmae, $Array [$k] [$Id], $SubNmae, $menu);
                    //兼容原来的菜单列表
                    if ($menu) {
                        if ($Array [$k] ['is_show'] == 1) {
                            $arr[] = $Arr;
                        }
                    } else {
                        $arr[] = $Arr;
                    }
                }
            } else {
                if ($Array [$k] [$PinNmae] == $Pid) {
                    $Arr = $Array [$k];
                    $Arr [$SubNmae] = $this->TreeArray($Array, $Id, $PinNmae, $Array [$k] [$Id], $SubNmae, $menu);
                    //兼容原来的菜单列表
                    if ($menu) {
                        if ($Array [$k] ['is_show'] == 1) {
                            $arr[] = $Arr;
                        }
                    } else {
                        $arr[] = $Arr;
                    }
                }
            }
        }
        return $arr;
    }

    /**
     * ajax提示专用
     * @param $status 执行状态，默认为200，成功
     * @param $info 提示信息，默认为 ‘操作成功’
     * @param $url 跳转的url，默认为空
     */
    public function Reminder($status = 200, $info = '操作成功', $url = '')
    {
        $data = array(
            'code' => $status,
            'info' => $info,
            'url' => $url
        );

        $this->ajaxReturn($data);
    }
    
    /**
     * 分享列表
     * @param number $book_id
     * @param number $user_id
     * @return unknown**/
    public function share($book_number=0,$user_id=0,$newshare=0,$fid=0)
    {
        $searchKey = I('get.searchKey');
        if ($searchKey){
            $searchType = trim(I('get.searchType',0));
            switch($searchType)
            {
                case 1:
                    $searchType= 's.author';
                    break;
                case 2:
                    $searchType= 's.book_number';
                    break;
                default:
                    $searchType = 's.book_name';
                    break;
            }
            $where[$searchType] = array('like','%'.$searchKey.'%');
            $count=M('book_share')->alias('s')->where($where)->count();
        }
        if($newshare){
            date_default_timezone_set("Asia/Shanghai");
            $time=date("Y-m-d 00:00:00");
            $deadline=date("Y-m-d 24:00:00");
            $news=strtotime($time);
            $dead=strtotime($deadline);
            $where['s.share_time']=array(array('gt',$news),array('lt',$dead));
            $count=D('book_share')->alias('s')->where($where)->count();
        }elseif($book_number){
            $where['s.book_number']=$book_number;
            $count=M('book')->alias('s')->where(array('is_deletet'=>0,'book_number'=>$book_number))->count();
        }elseif($user_id){
            $where['s.user_id']=$user_id;
            $count=D('book_share')->where(array('user_id'=>$where['s.user_id'],'is_deletet'=>0))->count();
        }elseif ($fid){
            $where['ca.cg_id']=$fid;
            $count=M('book_tag')->where(array('cg_id'=>$fid))->count();
         }else{
            $count=M('book_share')->alias('s')->where(array('is_deletet'=>1))->count();
        }
        $page=new \Think\Page($count,10);
        $data['page']=$page->show();
        $where['s.is_show']=1;
        $order='s.share_time desc';
        $data['list']=M('book_share')->alias('s')
            ->join('left join kts_book_tag ca on ca.book_id =s.book_id')
            ->join('left join kts_category c on ca.cg_id =c.cg_id')
            ->field('s.*,c.name as category,c.cg_id as fid')
            ->group('s.share_id')
            ->where($where)->order($order)->limit($page->firstRow,$page->listRows)->select();
        foreach ($data['list'] as $k=>$v){
            $data['list'][$k]['cover_img']=C('QINIU').$v['cover_img'];            
            $data['list'][$k]['status']=1;
            $data['list'][$k]['likes']=M('book_likes')->getField('likes');
        }
        return $data;    
    }
           
    public function uploadFile($img=0,$book=0){
        import('Org.Net.UploadFile');
        $upload= new \UploadFile();
        $upload->maxSize = 31424871;
        $upload->allowExts =array('wmv','mp4','avi','jpg', 'gif', 'png', 'jpeg');
        if ($img){
            $upload->savePath='Public/Upload/User/'.date('Y-m-d').'/';
        }elseif ($book){
            $upload->savePath='Public/Upload/Book/image/'.date('Y-m-d').'/';
        }
        else {
            $upload->savePath='Public/Upload/Book/video/'.date('Y-m-d').'/';
        }
        $info =$upload->upload();
        $ret =$upload->getUploadFileInfo($info);
        if (!empty($ret)){
            foreach ($ret as $v){
                $data[]=C('IMG_SITE_PREFIX').$v['savepath'].$v['savename'];
            }
        }else {
            $data=$upload->getErrorMsg();
        }
        return $data;
    }
    
    
    protected  function clean_all(){
        $memcache=new \Memcache();
        $memcache->connect('127.0.0.1','11211',60);
        $memcache->flush();
    }
    
    
    protected function del($filename){
        require_once 'Public/qiniu/php-sdk/autoload.php';
    
        // 引入鉴权类
        require_once 'Public/qiniu/php-sdk/src/Qiniu/Auth.php';
    
        // 引入上传类
        require_once 'Public/qiniu/php-sdk/src/Qiniu/Storage/UploadManager.php' ;
    
        // 需要填写你的 Access Key 和 Secret Key
        $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
        $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
    
        // 构建鉴权对象
        $auth = new Auth($accessKey, $secretKey);
    
        // 要上传的空间
        $bucket = 'sjb-kts';
    
        // 生成上传 Token
        $token = $auth->uploadToken($bucket);
    
    
        // 上传到七牛后保存的文件名
        $key = $filename;
    
        // 初始化 UploadManager 对象并进行文件的上传
        $bucketMgr = new BucketManager($auth);
        // 调用 UploadManager 的 putFile 方法进行文件的上传
         $err = $bucketMgr->delete($bucket, $key);       
        if ($err !== null) {
            return false;
        } else {
            return true;
        }
     }   

     
     protected function uploadF($filename,$pix='book_'){
         require_once 'Public/qiniu/php-sdk/autoload.php';

         // 需要填写你的 Access Key 和 Secret Key
         $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
         $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
         
         // 构建鉴权对象
         $auth = new Auth($accessKey, $secretKey);
         
         // 要上传的空间
         $bucket = 'sjb-kts';
         
         // 生成上传 Token
         $token = $auth->uploadToken($bucket);
         
         // 要上传文件的本地路径
         $filePath = $filename;
         
         // 上传到七牛后保存的文件名
         $key = $pix.time().mt_rand(1, 100).'.png';
         
         // 初始化 UploadManager 对象并进行文件的上传
         $uploadMgr = new UploadManager();
         
         // 调用 UploadManager 的 putFile 方法进行文件的上传
         list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
         if ($err !== null) {
             return false;
         } else {
             return $ret['key'];
         }
     }
    
     protected function uploadM($filename,$pix){
         require_once 'Public/qiniu/php-sdk/autoload.php';
       
          
         // 需要填写你的 Access Key 和 Secret Key
         $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
         $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
          
         // 构建鉴权对象
         $auth = new Auth($accessKey, $secretKey);
          
         // 要上传的空间
         $bucket = 'sjb-kuaitushu';
          
         // 生成上传 Token
         $token = $auth->uploadToken($bucket);
          
         // 要上传文件的本地路径
         $filePath = $filename;
          
         // 上传到七牛后保存的文件名
         $key = $pix.time().mt_rand(1, 100).'.png';
          
         // 初始化 UploadManager 对象并进行文件的上传
         $uploadMgr = new UploadManager();
          
         // 调用 UploadManager 的 putFile 方法进行文件的上传
         list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
         if ($err !== null) {
             return false;
         } else {
             return $ret['key'];
         }
     }
     
     protected function uploadvideo($filename){
         require_once 'Public/qiniu/php-sdk/autoload.php';
          
         // 引入鉴权类
         require_once 'Public/qiniu/php-sdk/src/Qiniu/Auth.php';
          
         // 引入上传类
         require_once 'Public/qiniu/php-sdk/src/Qiniu/Storage/UploadManager.php' ;
          
         // 需要填写你的 Access Key 和 Secret Key
         $accessKey = 'exBmUBK-KJJOu8-HeiNRGwQVK47rW3lpW1bxnyRT';
         $secretKey = 'lA7ef2HpCkHWRp93ZrC2OaI3yNW_U2i93lQ2pKzC';
          
         // 构建鉴权对象
         $auth = new Auth($accessKey, $secretKey);
          
         // 要上传的空间
         $bucket = 'sjb-kts';
          
         // 生成上传 Token
         $token = $auth->uploadToken($bucket);
          
         // 要上传文件的本地路径
         $filePath = $filename;
          
         // 上传到七牛后保存的文件名
         $key = time().mt_rand(1, 100).'.mp4';
          
         // 初始化 UploadManager 对象并进行文件的上传
         $uploadMgr = new UploadManager();
          
         // 调用 UploadManager 的 putFile 方法进行文件的上传
         list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
         if ($err !== null) {
             return false;
         } else {
             return $ret['key'];
         }
     }
     
    
     function httpsRequest($url, $data = null ) {
         $curl = curl_init();
         curl_setopt($curl, CURLOPT_URL, $url);
         curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
         curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
         if (!empty($data)){
             curl_setopt($curl, CURLOPT_POST, 1);
             curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
         }
         curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
         $output = curl_exec($curl);
         curl_close($curl);
         return $output;
     }
      
}
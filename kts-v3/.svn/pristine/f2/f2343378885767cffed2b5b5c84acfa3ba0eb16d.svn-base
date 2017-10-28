<?php
/**
 * 短信管理
 * 推送管理
 * 反馈管理
 * @author qiu
 */
namespace Admin\Controller;
use Common\Controller\AdminController;
class InformationController extends AdminController
{
	
	/*
	 * 信息反馈管理--列表
	 * 
	*/
	public function feedback($n = '15')
	{
		$is_check = I('get.is_check');
        $searchName = I('get.searchName');
        $startTime = strtotime(I('get.startTime'));
        $endTime = strtotime(I('get.endTime'));
        echo $is_check;
        //过滤筛选条件
        if($is_check!='all'&& $is_check!=null)
        {
            $where['f.is_check']=$is_check;
        }
        if ($searchName)
        {
            $where['f.fd_content']=array('like','%'.$searchName.'%');
        }
        if ($endTime && $startTime)
        {
            $where['f.fd_time']=array('between',array($startTime,$endTime));
        }
		//查询数据
		$model = M('feedback');
		$count = $model->alias('f')->join('LEFT JOIN kts_user as uo on f.user_id = uo.user_id')
			->where($where)->count();
		$Page = new \Think\Page($count, $n);
		$this->Page = $Page->show();
		$this->list = $model->alias('f')->join('LEFT JOIN kts_user as uo on f.user_id = uo.user_id')
			->field('f.fd_id,f.user_id,f.fd_content,f.is_check,f.fd_time,uo.name,uo.phone')->where($where)->order('fd_id desc')
			->limit($Page->firstRow . ',' . $Page->listRows)->select();

     	$this->display();
	}

	/*
	 * 信息反馈管理--删除
	 * by qiu
	*/
	public function feedbackDel($message_id)
	{
		$DM = M('feedback');
		if ($DM->delete($message_id)) {
            $this->addLog('fd_id='.$message_id,1);// 记录操作日志
			$this->success('删除一条反馈信息成功', U('Admin/Information/feedback'));
		} else {
            $this->addLog('fb_id='.$message_id,0);// 记录操作日志
			$this->error('删除反馈信息错误', U('Admin/Information/feedback'));
		}
	}

	/*
	 * 信息反馈管理--处理
	 * by chen
	*/
	public function feedbackCheck($message_id)
	{
		$DM = M('feedback');
		$data['is_check'] =1;
		if ($DM->data($data)->where('fd_id = '.$message_id)->save()) {
            $this->addLog('is_check='.$data['is_check'].'&fd_id='.$message_id,1);// 记录操作日志
			$this->success('OK!');
		} else {
            $this->addLog('is_check='.$data['is_check'].'&fd_id='.$message_id,0);// 记录操作日志
			$this->error('错误!');
		}
	}
    
   /*
     * 消息推送管理--列表
     * 
    */
    public function messageList($n = '15')
    {
        $type = I('get.type');
        $state = I('get.state');
        $searchName = I('get.searchName');
        $startTime = strtotime(I('get.startTime'));
        $endTime = strtotime(I('get.endTime'));
        //过滤筛选条件
        if($type!='all' && $type!=null)
        {
            $map['type']=$type;
        }
        if($state!='all' && $state!=null)
        {
            $map['state']=$state;
        }
        if ($searchName)
        {
            $like = '%'.$searchName.'%';
            $map['_string'] = "(title like '$like')  OR ( content like '$like') ";
        }
        if ($endTime && $startTime)
        {
            $map['addtime']=array('between',array($startTime,$endTime));
        }
        //查询数据
        $model = M('message');
        $count = $model
            ->where($where)->count();
        $Page = new \Think\Page($count, $n);
        $this->Page = $Page->show();
        $this->list = $model->alias('f')
            ->field('message_id,type,title,content,addtime,state,click_time')->where($map)
            ->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->display();
    }

    /*
     * 消息信息管理--删除
     *
    */
    public function messageDel($message_id)
    {

        $DM = M('message');
        //state为3时代表删除
        $data['state'] =3;
        $message_id = $DM->where('message_id = '.$message_id)->save($data);
        if($message_id !== false){
            $this->addLog('message_id='.$message_id,1);// 记录操作日志
            $this->success('编辑成功', U('Admin/Information/messageList'));
        }else{
            $this->addLog('message_id='.$message_id,0);// 记录操作日志
            $this->error('编辑失败');
        }
    }

   /**
    * 消息详情管理
    * @param  [type] $message_id 消息的id
    * @param  [type] $content_id 对应的消息中其他表的id
    * @param  [type] $type 对应的消息中其他类型
    */
    public function messageDet($message_id,$content_id,$type)
    {   
        $me= M('message')
            ->field('message_id,type,title,content,addtime,state,click_time')->where('message_id='.$message_id)->find();
        switch ($type) {
           
            case '1'://查询图书相关
                $book=M('book')->alias('b')
                            ->join('left join kts_book_attr as att on b.book_id = att.book_id')
                            ->where(array('b.book_id'=>$content_id))
                            ->field('b.name as book_name,att.introduce,b.author,att.author_area,att.author_desc,b.user_id,b.price,att.publish_price,b.inventory,b.type')->find();

                $me['description'] = "书本名：".$book['book_name']."——作者：".$book['author']."——￥".$book['publish_price'];
                break;
            case '2'://查询公告相关
                
                break;
            
            
            default:
                # code...
                break;
        }
        $this->list=$me;
        $this->display();
    }

    /**
     * 修改信息
     * @param  [type] $message_id 消息的id
     */
    public function messageEdit($message_id)
    {   
         $me= M('message')
            ->field('message_id,type,title,content,addtime,state,click_time')->where('message_id='.$message_id)->find();
        $this->display();
    }
    /**
     * 添加消息
     * @return 成功与失败
     **/
    public function messageAdd()
    {
        
        if(IS_POST){

           $type = I('post.type');
           $book_id = I('post.rid');
           $title = I('post.title');
           $url = I('post.jumpurl');
           $data['type'] = I('post.type');
           $data['content'] = I('post.rid');
           $data['title'] = I('post.title');
           $data['addtime'] = time();
           $data['url'] = I('post.jumpurl');
           $note = M('message');
          if (!$note->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($note->getError());
            }
            $lastId=$note->add($data);
          if ($lastId) {
                $this->addLog('name='.$data['name'].'&message_id='.$lastId,1);// 记录操作日志
                $this->success('新增消息成功', U('Admin/Information/messageList'));
          }else{
                $this->addLog('name='.$data['name'].'&message_id='.$lastId,0);// 记录操作日志
                $this->error('新增消息失败');
         }
           
        }else{
            $this->display();

        }

        
        
    }
    /**
     * 查看信息信息推送有哪些人
     * @param  [type] $message_id 消息推送id
     * @return 消息推送的人的列表
     */
    public function messageClick($n = '15')
    {
        $message_id =I('get.message_id');
        $searchName = I('get.searchName');
        $startTime = strtotime(I('get.startTime'));
        $endTime = strtotime(I('get.endTime'));
        //过滤筛选条件
        if ($searchName)
        {
            $like = '%'.$searchName.'%';
            $where['_string'] = "(u.name like '$like')  OR ( u.phone like '$like') ";
        }
        if ($endTime && $startTime)
        {
            $where['us.read_time']=array('between',array($startTime,$endTime));
        }
        $where['m.message_id'] = $message_id;
        //查询数据
        $count = M('message')->alias('m')
                        ->join('left join kts_user_message as us on us.message_id = m.message_id')
                        ->join('left join kts_user as u on u.user_id = us.user_id')
                        ->join('left join kts_user_xq as x on x.user_id = us.user_id')
                        ->where($where)
                        ->count();
        $Page = new \Think\Page($count, $n);
        $this->Page = $Page->show();
        $listxq=M('message')->alias('m')
                        ->join('left join kts_user_message as us on us.message_id = m.message_id')
                        ->join('left join kts_user as u on u.user_id = us.user_id')
                        ->join('left join kts_user_xq as x on x.user_id = us.user_id')
                        ->where($where)
                        ->field('m.message_id,us.read_time,u.name,u.phone,x.imageurl')
                        ->limit($Page->firstRow . ',' . $Page->listRows)
                        ->select();
        $this->list=$listxq;
        $this->assign('message_id', $message_id);
        $this->display();
    }

    public function messagePost($n='15')
    {
        $message_id =I('get.message_id');
        $searchName = I('get.searchName');
        $groupType = strtotime(I('get.groupType'));
        //过滤筛选条件
        if ($searchName)
        {
            $like = '%'.$searchName.'%';
            $where['_string'] = "(u.name like '$like')  OR ( u.phone like '$like') ";
        }
        
        //查询数据
        $count = M('user')->alias('u')
                        ->join('left join kts_user_xq as x on x.user_id = u.user_id')
                        ->where($where)
                        ->count();
        $Page = new \Think\Page($count, $n);
        $this->Page = $Page->show();
        $listxq=M('user')->alias('u')
                        ->join('left join kts_user_xq as x on x.user_id = u.user_id')
                        ->where($where)
                        ->field('u.user_id,u.name,u.phone,x.imageurl')
                        ->limit($Page->firstRow . ',' . $Page->listRows)
                        ->select();
        $this->list=$listxq;
        $this->assign('message_id', $message_id);
        $this->display();
    }
    /**
     * 筛选相关联的信息类型
     * @param  [type] $type 类型
     * @return [type]       相关的数组
     */
    public function messageRele()
    {   
        $type = I('get.type');
        switch ($type)
        {
            case '1':
                $name = I('q', '');
                $page = I('page',0);
                $condition = array();
                if (!empty($name)) {
                    $condition['name'] = array('like', "%$name%");
                }
                $userLogic = M('book');
                $res=$userLogic
                                ->where($condition)
                                ->field('book_id,name')
                                ->limit($page . ',' . 100)
                                ->select();
                $resultStrArr = array();
                foreach ($res as $item) {
                    array_push($resultStrArr, json_encode(array('rid' => $item['book_id'], 'name' => $item['name'])));
                }
                

                break;
            case '2':
                $name = I('q', '');
                $page = I('page',0);
                $condition = array();
                if (!empty($name)) {
                    $condition['content'] = array('like', "%$name%");
                }
                $userLogic = M('note');
                $res=$userLogic
                                ->where($condition)
                                ->field('note_id,content')
                                ->limit($page . ',' . 100)
                                ->select();
                $resultStrArr = array();
                foreach ($res as $item) {
                    array_push($resultStrArr, json_encode(array('rid' => $item['note_id'], 'name' => $item['content'])));
                }
               

                break;
            
            default:
                # code...
                break;
        }
        //用\n换行符隔开的json
        exit(implode("\n", $resultStrArr));
    }

    /**
     * 返回模糊搜索数据
     */
    public function ajaxSearchbyname() {
        $name = I('q', '');
        $page = I('page',0);
        $condition = array();
        if (!empty($name)) {
            $condition['name'] = array('like', "%$name%");
        }
        $userLogic = M('book');
        $res=$userLogic
                        ->where($condition)
                        ->field('book_id,name')
                        ->limit($page . ',' . 100)
                        ->select();
        $resultStrArr = array();
        foreach ($res as $item) {
            array_push($resultStrArr, json_encode(array('rid' => $item['book_id'], 'name' => $item['name'])));
        }
        //用\n换行符隔开的json
        exit(implode("\n", $resultStrArr));
    }

    public function messagePush()
    {
        $message_id =I('post.message_id');
        $user = I('post.user_id');
        $data['message_id'] =$message_id;
        if($user)
        {
            foreach ($user as $key => $value)
            {
                $data['user_id']=$user[$key]['user_id'];
                $ispush = M('user_message')->where($data)->find();
                if($ispush==null){//判断是否已经发送过
                  M('user_message')->data($data)->add();
                  M('message')->where('message_id='.$message_id)->setInc('click_time',1);
                }
            }
            $this->addLog('user='."相关人员".'&message_id='.$message_id,1);// 记录操作日志
            $this->success('推送成功', U('Admin/Information/messageList'));

        }else
        {
          $this->error('未选择推送的相关人员！');
        }
    }





    /**
     * banner列表
     */
    public function bannerList($n = '15')
    {   
    	$count = M('banner')->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
          
        $this->banners = M('banner')   
               ->field('id,title,imageurl,url,desc,content,is_show,type')
               ->limit($Page->firstRow.','.$Page->listRows)
               ->select();
        $this->display();
    }

     /**
     * banner状态修改
     */
    public function changeStatus($method=null){
        $data['id'] = I('get.id',0);
        switch ( strtolower($method) ){
            case 'forbid'://禁用
                $data['is_show']    =  0;
                $msg = '已禁用';
                break;
            case 'resume'://启用
                $data['is_show']   =  1;
                $msg = '已启用';
                break;
            default:
                $this->error('参数非法');
        }

        if(M('banner')->where($where)->save($data)!==false ) {
            $this->success($msg);
        }else{
            $this->error('操作失败');
        }
    }
    /**
     * 上传文件 
     */
    public function upload(){
        //import("@.ORG.UploadFile"); 
        $upload = new \Think\Upload();// 实例化上传
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     C('IMG_SITE_PREFIX').'Public/Upload/banner/'; // 设置附件上传根目录
        $upload->savePath  =     ""; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if($info) {// 上传错误提示错误信息
            return $upload->rootPath.$info['imageurl']['savepath'].$info['imageurl']['savename'];
        }
    }
    /**
     * 编辑banner
     */
    public function bannerEdit($banner_id='0')
    {   

        if ($_FILES ){
            $_POST['imageurl']=$this->upload();
        } 
        if (IS_POST)
        {  
            if($_POST['imageurl']==null){
              $_POST['imageurl']=$_POST['imageurl1'];
            }
            $banner = M('banner');
            if (!$banner->create($_POST,2))
                $this->error($banner->getError());
            $banner_id = $banner->where('id = '.$banner_id)->save($data);
            if($config_id !== false){
                $this->addLog('imageurl='.I('imageurl').'&banner_Id='.$banner_id,1);// 记录操作日志
                $this->success('编辑成功', U('Admin/Information/bannerList'));
            }else{
                $this->addLog('imageurl='.I('imageurl').'&banner_id='.$banner_id,0);// 记录操作日志
                $this->error('编辑失败');
            }
        }
        else
        {
          
            //$where['config_type'] = 0;
            $where['id'] = $banner_id;
	        $this->banners = M('banner')   
	               ->field('id,title,imageurl,url,desc,content,is_show,type')
	               ->where($where)
	               ->find();
	        $this->display();
        }
    }

    /**
     * 新增设置
     */
    public function bannerAdd()
    {
        if ($_FILES ){
            $_POST['imageurl']=$this->upload();
        } 
        if (IS_POST)
        {   
            //$_POST['content'] = 1;
            $banner = M('banner');
            if (!$banner->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($config->getError());
            }
            $lastId=$banner->add($_POST);
            if ($lastId) {
                S('banner',null);
                $this->addLog('imageurl='.$_POST['imageurl'].'&banner_id='.$lastId,1);// 记录操作日志
                $this->success('新增成功',U('Admin/Information/bannerList'));
            }else{
                $this->addLog('imageurl='.$_POST['imageurl'].'&banner_id='.$lastId,0);// 记录操作日志
                $this->error('新增失败');
            }
        }
        else
        {
           
            $this->display();

        }
    }


    /**
     *地域列表
     */
    public function regionList($n = '15')
    {
        header("Content-type: text/html;charset=utf-8");
        $province_id = I('get.province_id');
        $city_id = I('get.city_id');
        $area_id = I('get.area_id');
        $name = I('get.name');
        //过滤筛选条件
        if($province_id!='all' && $province_id!=null)
        {
            $where['p.province_id']=$province_id;
        }
        if ($city_id!=0)
        {
            $where['c.city_id']=$city_id;
        }
        if ($area_id!=0)
        {
            $where['a.area_id']=$area_id;
        }
        if ($name)
        {
            $like = '%'.$name.'%';
            $where['_string'] = "(province like '$like')  OR ( city like '$like') OR ( area like '$like') ";
        }
        $where['p.province_id'] = array('neq',0);
        $count = M('province')->alias('p')
            ->join('left join kts_city as c on c.father = p.province_id')
            ->join('left join kts_area as a on a.father = c.city_id')
            ->where($where)->count();

       
        $Page = new \Think\Page($count,$n);
        $items = M('province')->alias('p')
            ->join('left join kts_city as c on p.province_id = c.father')
            ->join('left join kts_area as a on c.city_id = a.father')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->field('p.id as p_id,p.province,p.province_id,c.id as c_id,c.city,c.city_id,a.id as a_id,a.area,a.area_id')
            ->select();
        $this->list=$items;
        $this->Page = $Page->show();
        $this->provinceList = M('province')->field('id,province_id,province')->select();
        $this->display();

    }


    /**
     * 根据省的id获取城市的信息
     * @param $proId省id
     * @return mixed 城市信息
     */
    public function CityInfo($province_id)
    {
        $model = M("city");
        if (isset($province_id)) {
            $data = $model->field('id,city_id,city,code')->where(array('father' => $province_id))->select();
        } else {
            $data = $model->field('id,city_id,city,code')->select();
        }
        $this->ajaxReturn($data,"JSON");

    }

    /**
     * 根据城市Id获取区域的信息
     * @param $cityId 城市Id
     * @return mixed 区域信息
     */
    public function AreaInfo($city_id)
    {
        $model = M('area');
        if (isset($city_id)) {
            $data = $model->field('id,area_id,area')->where(array('father' => $city_id))->select();
        } else {
            $data = $model->field('id,area_id,area')->select();
        }

        $this->ajaxReturn($data,"JSON");
    }
    /**
     * 查询某省，某市，某区编码是否已经使用过了
     * @param  [string] $number   某省、某市，某区编码
     * @param  [int]    $position 区分省市区
     * @return mixed    地区信息    
     */
    public function checkNumber($number,$position)
    {
        switch ($position) {
            case '1':
                $data = M('province')->where('province_id='.$number)->find();
                if($data){
                    $data =0;
                }
                break;
            case '2':
                $data1 = M('city')->where('city_id='.$number)->find();
                if($data1){
                    $data =0;
                }
                break;
            
            default:
                $data2 = M('area')->where('area_id='.$number)->find();
                if($data2){
                    $data =0;
                }
                break;
        }
        $this->ajaxReturn($data,"JSON");
    }


  

    /**
     * 新增地域
     */
    public function regionAdd()
    {   

       
            $this->provinceList = M('province')->field('id,province_id,province')->select();
            $this->display(); 
       
    }

    /**
     * 新增图书类型
     */
    public function regionPost()
    {       
            C('TOKEN_ON',false);//指定页面无令牌表单验证
            $province_id = I('post.province_id');
            $city_id = I('post.city_id');
            $area_id = I('post.area_id');
            $name = I('post.name');
            $number = I("post.number");
            $code = I("post.code");
           
            if($province_id==0 && ($city_id==0||$city_id==null)){
                $data['province_id'] = $number;
                $data['province'] = $name;

                $province = M('province');
                if (!$province->create($_POST,1)){
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    $this->error($province->getError());
                }
                $province1_id=$province->add($data);

            }
            if($province_id !=0 && $city_id==0 ){
                $data1['city_id'] = $number;
                $data1['city'] = $name;
                $data1['father'] = $province_id;
                $data1['code'] = $code;
                $city = M('city');
                if (!$city->create($_POST,1)){
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    $this->error($city->getError());
                }
                $city1_id=$city->add($data1);

            }else
            {
                $data2['area_id'] = $number;
                $data2['area'] = $name;
                $data2['father'] = $city_id;
                $area = M('area');
                // if (!$area->create($_POST,1)){
                //     // 如果创建失败 表示验证没有通过 输出错误提示信息
                //     $this->error($area->getError());
                // }
                $area1_id=$area->add($data2);
            }

           
            if ($province1_id||$city1_id||$area1_id) {
                $this->addLog('name='.$_POST['name'].'&cg_id='.$lastId,1);// 记录操作日志
                //$this->success('新增成功', U('Admin/Information/regionList'));
                $result['status'] = 1 ;
                $this->ajaxReturn($result,"JSON");
            }else{
                $this->addLog('name='.$_POST['name'].'&cg_id='.$area1_id,0);// 记录操作日志
                //$this->error('新增失败');
                $result['status'] = 0 ;
                $this->ajaxReturn($result,"JSON");
            }
    }
    /**
     * 标识码列表
     */
    public function numberList()
    {
        $number= I('get.number','','trim');
        $province_id = I('post.province_id');
        $city_id = I('post.city_id');
      
        if($province_id==0 && $city_id==0){
            $sel1 = M('province')->where('province_id='.$number)->find();
        }
        if($province_id !=0 && $city_id==0 ){
           $sel2 = M('city')->where('city_id='.$number)->find();
        }
        if($province_id !=0 && $city_id!=0)
        {
            $sel3 = M('area')->where('area_id='.$number)->find();
        }

        if($sel1||$sel2||$sel3){
            $data['num'] =0;
            $data['msg'] ="该标识号已经存在，请重新填写";
        }else{
            $data['num'] =1;
            $data['msg'] ="";
        }
        
        $json_obj = json_encode($data);
        echo $json_obj;
       
        
    }

    /**
     * 修改地域信息
     */
    public function regionEdit()
    {   
        $province_id = I('get.province_id');
        $city_id = I('get.city_id');
        $area_id = I('get.area_id');
        // $data = M('province')->where('province_id='.$province_id)->field('id,province_id,province')->find();
        $this->provinceList = M('province')->field('id,province_id,province')->select();
        $this->cityList = M('city')->where('father='.$province_id)->field('id,city_id,city')->select();
        $this->areaList = M('area')->where('father='.$city_id)->field('id,area_id,area')->select();
        $this->display();
        
        
    }

   
    

}
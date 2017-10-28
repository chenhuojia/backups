<?php
/**
 * 社区小组管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;

class   NoteController extends AdminController {
    
    /**
     * 帖子列表
     */
    public function noteList($n = '15')
    {
        $groupType = I('get.groupType');
        $userName = I('get.userName');
        $startTime = strtotime(I('get.startTime'));
        $endTime = strtotime(I('get.endTime'));
        //过滤筛选条件
        if($groupType!='all'&& $groupType!=null)
        {
            $where['groups_id']=$groupType;
        }
        if ($userName)
        {
            $where['username']=array('like','%'.$userName.'%');
        }
        if ($endTime && $startTime)
        {
            $where['addtime']=array('between',array($startTime,$endTime));
        }
        $note = M('note')->alias('v1');
        $count = $note->where($where)->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $this->list = $note->alias('v1')
            ->join('left join kts_groups v2 on v1.groups_id = v2.group_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)->field('v1.note_id,v1.user_id,v1.username,v1.user_img,v1.addtime,v1.content,v1.is_show,v1.updtime,v1.click_number,v1.critics_number,v2.name as group_name')
            ->order('note_id')->select();
        //查询小区分组
        $m=M('groups');
        $where1['is_show']=1;
        $data=$m->field('group_id,name,is_show')->where($where1)->select();
        $this->info=$data;
        $this->display();
    }

    /**
     * 新增帖子
     */
    public function noteAdd()
    {
        if (IS_POST)
        {   
            $_POST['addtime'] = time();
            $_POST['user_id'] = 0;
            $_POST['username'] = '管理员';
            $_POST['user_img'] = 'http://img1.imgtn.bdimg.com/it/u=2974022307,154411162&fm=21&gp=0.jpg';
            $note = M('note');
            if (!$note->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($note->getError());
            }
            $lastId=$note->add($_POST);
            if ($lastId) {
                $this->addLog('name='.$_POST['name'].'&note_id='.$lastId,1);// 记录操作日志
                $this->success('新增帖子成功', U('Admin/note/noteList'));
            }else{
                $this->addLog('class_name='.$_POST['class_name'].'&note_id='.$lastId,0);// 记录操作日志
                $this->error('新增帖子失败');
            }
        }
        else
        {
           
            //查询小区分组
            $m=M('groups');
            $where1['is_show']=1;
            $data=$m->field('group_id,name,is_show')->where($where1)->select();
            $this->info=$data;
            $this->display();

        }
    }

    /**
     * 修改帖子信息
     */
    public function noteEdit($note_id='0')
    {
        if (IS_POST)
        {
            $_POST['addtime'] = time();
            $noteClass = M('note');
            if (!$noteClass->create($_POST,2)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($noteClass->getError());
            }
            // header("Content-type: text/html;charset=utf-8");
            // var_dump($_POST);
            // echo $note_id;
            
            $lastId=$noteClass->where('note_id = '.$note_id)->save($_POST);
            if ($lastId !== false) {
                $this->addLog('name='.$_POST['name'].'&note_id='.$lastId,1);// 记录操作日志
                $this->success('编辑帖子成功', U('Admin/note/noteList'));
            }else{
                $this->addLog('name='.$_POST['name'].'&note_id='.$lastId,0);// 记录操作日志
                $this->error('编辑帖子失败');
            }
        }
        else
        {
            $this->notemessage = M('note')->field('note_id,groups_id,user_id,username,user_img,addtime,content,is_show,updtime')->find($note_id);
            //var_dump( $this->notemessage);
            //查询小区分组
            $where1['is_show']=1;
            $this->info=M('groups')->field('group_id,name,is_show')->where($where1)->select();
            //var_dump($this->info);
            $this->display();
        }
    }

     /**
     * 查看更多
     */
    public function noteDet($note_id='0')
    {
        $note = M('note')->alias('v1');
        $where['note_id'] = $note_id;
        $this->note = $note->alias('v1')
            ->join('left join kts_groups v2 on v1.groups_id = v2.group_id')
            ->where($where)
            ->limit($Page->firstRow.','.$Page->listRows)
            ->field('v1.note_id,v1.user_id,v1.username,v1.user_img,v1.addtime,v1.content,v1.is_show,v1.updtime,v1.click_number,v1.critics_number,v2.name as group_name')
            ->order('note_id')
            ->find();
        //var_dump($this->note);
        if ($this->note)
            $this->display();
        else
            $this->error('找不到相关帖子信息');
    }

    /**
     * 删除帖子
     */
    public function noteDel($note_id='0')
    {       

            //$_POST['is_show'] = 0;
            $Data['is_show'] = 0;
            $note_id = $_GET['note_id'];
            $noteClass = M('note');
            //header("Content-type: text/html;charset=utf-8");
           // var_dump($Data);
            //echo $note_id;
            
            $lastId=$noteClass->where('note_id = '.$note_id)->save($Data);
            if ($lastId) {
                $this->addLog('name='.$_POST['name'].'&note_id='.$lastId,1);// 记录操作日志
                $this->success('删除帖子成功', U('Admin/note/noteList'));
            }else{
                $this->addLog('class_name='.$_POST['class_name'].'&note_id='.$lastId,0);// 记录操作日志
                $this->error('删除帖子失败');
            }
    }

     /**
     * 视频评论
     */
    public function noteEval($note_id)
    {
        $eval = M('note_comment');
        $where['e.note_id'] = $note_id;

        $count = $eval->alias('e')->where($where)->count();
        $Page = new \Think\Page($count,5);
        $data['page'] = $Page->show();

        $data['data'] = $eval->alias('e')
            ->where($where)->field('e.username,e.user_id,e.user_img,e.content,e.addtime')
            ->order('e.addtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        //var_dump($data['data']);
        //echo  $data['data']['addtime'];
        //$data['data']['addtime'] =  date('Y-m',$data['data']['addtime']);
        foreach($data['data'] as $k=>$v)
        {
            $data['data'][$k]['addtime'] = date('Y-m-d',$data['data'][$k]['addtime']);
            //echo  $data['data'][$k]['addtime'];

        }
        //var_dump($data['data']);
        $this->ajaxReturn($data);
    }

  

} 
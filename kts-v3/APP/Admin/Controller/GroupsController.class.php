<?php
/**
 * 社区小组管理
 */

namespace Admin\Controller;
use Common\Controller\AdminController;

class   GroupsController extends AdminController {

    /**
     * 社区小组类型列表
     */
    public function groupsTypeList($n = '15')
    {
        $className = I('get.name'); // 搜索条件
        if ($className)
        {
            $where['v1.name'] = array('like','%'.$className.'%');
        }

        $groupsType = M('groups')->alias('v1');
        $count = $groupsType->where($where)->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $this->list = $groupsType->alias('v1')->where($where)->field('v1.group_id,v1.name,v1.imageurl,v1.introduce,v1.is_show,v1.create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->display();
    }

    /**
     * 新增社区小组类型
     */
    public function groupsTypeAdd()
    {
        if (IS_POST)
        {   
            $_POST['create_time'] = time();
            $groupsType = M('groups');
            if (!$groupsType->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($groupsType->getError());
            }
            $lastId=$groupsType->add($_POST);
            if ($lastId) {
                $this->addLog('name='.$_POST['name'].'&group_id='.$lastId,1);// 记录操作日志
                $this->success('新增社区小组类型成功', U('Admin/groups/groupsTypeList'));
            }else{
                $this->addLog('class_name='.$_POST['class_name'].'&group_id='.$lastId,0);// 记录操作日志
                $this->error('新增社区小组类型失败');
            }
        }
        else
        {
           // $this->typeList = M('groups')->field('group_id,name')->select();
            $this->display();
        }
    }

    /**
     * 修改社区小组类型信息
     */
    public function groupsTypeEdit($group_id='0')
    {
        if (IS_POST)
        {
            $_POST['create_time'] = time();
            $groupsClass = M('groups');
            if (!$groupsClass->create($_POST,2)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($groupsClass->getError());
            }
            // header("Content-type: text/html;charset=utf-8");
            // var_dump($_POST);
            // echo $group_id;
            $lastId=$groupsClass->where('group_id = '.$group_id)->save($_POST);
            if ($lastId !== false) {
                $this->addLog('name='.$_POST['name'].'&group_id='.$lastId,1);// 记录操作日志
                $this->success('编辑社区小组类型成功', U('Admin/groups/groupsTypeList'));
            }else{
                $this->addLog('name='.$_POST['name'].'&group_id='.$lastId,0);// 记录操作日志
                $this->error('编辑社区小组类型失败');
            }
        }
        else
        {
            
            $this->classType = M('groups')->field('group_id,name,imageurl,introduce,is_show,create_time')->find($group_id);
            //var_dump( $this->classType);
            $this->display();
        }
    }

    /**
     * 删除社区小组类型
     */
    public function groupsTypeDel($class_id='0')
    {       

            //$_POST['is_show'] = 0;
            $Data['is_show'] = 0;
            $group_id = $_GET['group_id'];
            $groupsClass = M('groups');
            // header("Content-type: text/html;charset=utf-8");
            // var_dump($_POST);
            
            $lastId=$groupsClass->where('group_id = '.$group_id)->save($Data);
            if ($lastId) {
                $this->addLog('name='.$_POST['name'].'&group_id='.$lastId,1);// 记录操作日志
                $this->success('删除社区小组类型成功', U('Admin/groups/groupsTypeList'));
            }else{
                $this->addLog('class_name='.$_POST['class_name'].'&group_id='.$lastId,0);// 记录操作日志
                $this->error('删除社区小组类型失败');
            }
    }

   
     /**
     * 社区小组类型列表
     */
    public function groupsList($n = '15')
    {
        $className = I('get.name'); // 搜索条件
        if ($className)
        {
            $where['v1.name'] = array('like','%'.$className.'%');
        }
        $where['v1.is_show'] = 1;
        $groupsType = M('groups')->alias('v1');
        $count = $groupsType->where($where)->count();
        $Page = new \Think\Page($count,$n);
        $this->Page = $Page->show();
        $this->list = $groupsType->alias('v1')->where($where)->field('v1.group_id,v1.name,v1.imageurl,v1.introduce,v1.is_show,v1.follow_number,v1.create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->display();
    }

    /**
     * 用户关注某一组列表列表
     */
    public function groupsDet($group_id)
    {   

        $where['q.groups_id'] = $group_id;
        $count = M('user_groups')->alias('q')->where($where)->count();
        $Page = new \Think\Page($count,2);
        $this->Page = $Page->show();
        $this->list = M('user_groups')->alias('q')
        ->join('LEFT JOIN kts_groups v ON q.groups_id = v.group_id')
        ->join('LEFT JOIN kts_user s ON q.user_id = s.user_id')
        ->join('LEFT JOIN kts_user_xq x ON q.user_id = x.user_id')
        ->field('q.u_g_id,q.groups_id,q.addtime,s.name,s.phone,x.imageurl')
        ->where($where)->order('q.addtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
         $this->display();
        // if ($this->list)
        // {
        //     $this->display();
        // }
        // else
        // {
        //     $this->error('找不到相关关注人信息');
        // }
    }

    /**
     * 删除小组关注人
     */
    public function groupsDel($u_g_id='0')
    {       
            $u_g_id = $_GET['u_g_id'];
            $group_id = $_GET['group_id'];
            $groupsClass = M('user_groups');
            //$list = M("user_groups")->where($condition)->find();
            // header("Content-type: text/html;charset=utf-8");            
            $lastId=$groupsClass->where('u_g_id = '.$u_g_id)->delete(); 
            if ($lastId) {
                //关注人数减1
                M('groups')->where('group_id='.$group_id)->setDec('follow_number');
                $this->addLog('name='.$_POST['name'].'&u_g_id='.$lastId,1);// 记录操作日志
                $this->success('删除小组关注人成功', U('Admin/groups/groupsList'));
            }else{
                $this->addLog('name='.$_POST['name'].'&u_g_id='.$lastId,0);// 记录操作日志
                $this->error('删除小组关注人失败');
            }
    }

    /**
     * 新增小组关注
     */
    public function groupsAdd()
    {
        if (IS_POST)
        {   
            $_POST['addtime'] = time();
            $data['user_id'] =  $_POST['rid'];
            $data['groups_id'] =  $_POST['groups_id'];
           
            $groupsType = M('user_groups');
            if (!$groupsType->create($_POST,1)){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($groupsType->getError());
            }
            $exist =$groupsType->where($data)->select(); 
           
            if ($exist){
                 $this->error('该用户已经关注了该小组');
            }
            $lastId=$groupsType->add($_POST);
            if ($lastId) {
                //关注人数减1
                M('groups')->where('group_id='.$data['groups_id'])->setInc('follow_number');
                $this->addLog('groups_id='.$_POST['groups_id'].'&u_g_id='.$lastId,1);// 记录操作日志
                $this->success('新增小组关注人成功', U('Admin/groups/groupsList'));
            }else{
                $this->addLog('groups_id='.$_POST['groups_id'].'&u_g_id='.$lastId,0);// 记录操作日志
                $this->error('新增小组关注人失败');
            }
        }
        else
        {
            $where1['is_show']=1;
            $this->info=M('groups')->field('group_id,name,is_show')->where($where1)->select();
            //$this->userinfo=M('user')->field('user_id,name,type')->select();
            $this->display();
        }
    }



  

} 
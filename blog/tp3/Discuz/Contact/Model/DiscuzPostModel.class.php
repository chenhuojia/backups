<?php 
namespace Contact\Model;
use Think\Model;
/**
 * 基础model
 */
class DiscuzPostModel extends Model{
           
    protected $_validate = array(
        array('topic_id','checkTopic','请选择话题',1,'callback'),
        array('group_id','checkGroup','请选择分组',1,'callback'),
        array('title','require','请输入标题',1),
        array('content','require','请输入内容',1),
        array('introduction','require','请输入简介',1),
        array('user_id','checkUser','请先登陆',1,'callback')
    );
    
    protected $_auto = array (
        array('addtime','time',1,'function'),
        array('group_path','getPath',1,'callback'),
        array('user_id','getUserId',1,'callback'),
       
    );
    
    function getPath(){
        $str=I('post.topic_id').'|'.I('post.group_id');
        return $str;
    }
    
    function checkTopic(){
        $str=I('post.topic_id');
        if ($str <=0 ) return false;
        return true;
    }
    function checkGroup(){
        $str=I('post.group_id');
        if ($str <=0 ) return false;
        return true;
    }
    function checkUser(){
        $str=session('userInfo');
        if (empty($str)) return false;
        return true;
    }
    function getUserId(){
        return session('userInfo')['user_id'];
    }
}
<?php 
namespace FAQ\Model;
use Think\Model;
/**
 * 基础model
 */
class MessageWallModel extends Model{
           
    protected $_validate = array(
        array('content','require','请输入内容',1),
        array('user_id','checkUser','请先登陆',1,'callback')
    );
    
    protected $_auto = array (
        array('addtime','time',1,'function'),
        array('user_id','getUserId',1,'callback'),
    );    
    function checkUser(){
        $str=session('userInfo');
        if (empty($str)) return false;
        return true;
    }
    function getUserId(){
        return session('userInfo')['user_id'];
    }
}
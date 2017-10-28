<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class GroupsModel extends Model{
   
    // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    
    public function getAllData($map,$skip,$take)
    {

        $result = $this
              ->where($map)
              ->field('group_id,name,imageurl,introduce,is_show,follow_number,create_time')
              ->limit($skip,$take)
              ->select();
        return $result;
    }

    /**
     * 获取管理员权限列表
     */
    public function getAllData1(){
        $data=$this
            ->field('u.id,u.username,u.email,aga.group_id,ag.title,u.status,u.last_login_time')
            ->alias('aga')
            ->join('__USERS__ u ON aga.uid=u.id','RIGHT')
            ->join('__AUTH_GROUP__ ag ON aga.group_id=ag.id','LEFT')
            ->select();
        // 获取第一条数据
        $first=$data[0];
        $first['title']=array();
        $user_data[$first['id']]=$first;
        // 组合数组
        foreach ($data as $k => $v) {
            foreach ($user_data as $m => $n) {
                $uids=array_map(function($a){return $a['id'];}, $user_data);
                if (!in_array($v['id'], $uids)) {
                    $v['title']=array();
                    $user_data[$v['id']]=$v;
                }
            }
        }
        // 组合管理员title数组
        foreach ($user_data as $k => $v) {
            foreach ($data as $m => $n) {
                if ($n['id']==$k) {
                    $user_data[$k]['title'][]=$n['title'];
                }
            }
            $user_data[$k]['title']=implode('、', $user_data[$k]['title']);
        }
        // 管理组title数组用顿号连接
        return $user_data;

    }


}

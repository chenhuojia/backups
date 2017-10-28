<?php
namespace V1\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicTagModel extends Model{
   
    // 自动验证
    protected $_validate=array(
        array('tag_name','require','栏目名称必须填写！'), // 验证字段必填
        array('tag_describe','require','栏目内容必须填写！'), // 验证字段必填
     );
     // 自动完成
    protected $_auto=array(
        array('create_time','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    
    /**
     * 获取某条数据
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getOne($map)
    {
        $result=$this
            ->where($map)
            ->find();
        return $result;
    }

    /**
     * 所有的话题栏目
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function searchAllData($map,$orderlist,$skip,$take)
    {

        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag_recommend as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_tag_statistic as s ON t.tag_id = s.tag_id')
            ->where($map)
            ->field('t.tag_id,t.tag_name,t.create_time,s.discuss_number')
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
            $result[$key]['recommend'] = 1;//推荐
            if($result[$key]['recommend_id'] ==null){
                $result[$key]['recommend_id'] = 0;
                $result[$key]['recommend'] = 0;
            }
        }
        return $result;
    }

    /**
     * 某话题栏目下的详情
     * @param  [array] $map        [查询的值]
     * @param  [string] $user_id   [登录用户的id]
     * @return [array]             [数组]
     */
    public function getOneDetail($map)
    {

        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag_statistic as s ON t.tag_id = s.tag_id')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($map)
            ->field('t.tag_name,t.create_time,t.tag_describe,s.discuss_number,u.name,x.imageurl as user_image')
            ->find();
        return $result;
    }

    /**
     * 根据查询获取某值
     * @param  [string] $map  查询的数据
     * @param  [string] $value  匹配的值
     * @return [string]  相关的值
     */
    public function getValue($map,$value)
    {
        $result=$this
               ->where($map)
               ->getField($value);
        return $result;
    }

    /**
     * 添加数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
            // 验证不通过返回错误
            $returndata['status'] = 0;
            $returndata['data'] = $this->getError();
            return $returndata;
        }else{
            // 验证通过
            $result=$this->add($data);
            $returndata['status'] = 1;
            $returndata['data'] = $result;
            return $returndata;
        }
    }
   /**
     * 修改数据
     */
    public function editData($map,$data){
        // 对data数据进行验证
        if(!$data=$this->create($data)){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this
                ->where(array($map))
                ->save($data);
            return $result;
        }
    }

    


}

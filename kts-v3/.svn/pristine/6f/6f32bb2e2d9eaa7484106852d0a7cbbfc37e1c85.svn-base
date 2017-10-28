<?php
namespace Admin\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicCommentModel extends BaseModel{
   
    // 自动验证
    protected $_validate=array(
        array('content','require','内容必须填写！'), // 验证字段必填
     );
    // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );
    

    /**
     * 某话题下所有的评论
     * @param  [array] $map        [查询的值]
     * @param  [string] $orderlist [排序的值]
     * @param  [int] $skip         [跳过多少条]
     * @param  [int] $take         [获取多少条]
     * @return [array]             [数组]
     */
    public function getAllData($map,$orderlist,$skip,$take)
    {
        $result = $this->alias('c')
            ->join('LEFT JOIN kts_user as u ON c.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON c.user_id = x.user_id')       
            ->field('c.comment_id,c.topic_id,u.name,x.imageurl as user_image,c.content,c.addtime,c.is_show,c.fid')
            ->where($where)
            ->order($orderlist)
            ->limit($skip,$take)
            ->select();
        foreach ($result as $key => $value) {
            $result[$key]['f_username'] = "";//父类的用户名
            $result[$key]['f_content'] = "";//父类的回复
            if($result[$key]['fid'] >0){
                $where =array('c1.comment_id'=>$result[$key]['fid'],'c1.is_show'=>1);
                $data = $this->getFather($where);
                $result[$key]['f_username'] = $data['name'];//父类的用户名
                $result[$key]['f_content'] = $data['content'];//父类的回复
            }
        }
        return $result;
    }

    public function CommentLists($map,$orderlist='',$limit=10){
        $count=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_comment_statistic as c ON t.topic_id = c.topic_id')
            ->join('LEFT JOIN kts_topic_likes_statistic as l ON t.topic_id = l.topic_id')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->where($map)
            ->count();
        $page=new \Think\Page($count,$limit);
        // 获取分页数据
        $result=$this
            ->alias('t')
            ->join('LEFT JOIN kts_topic_tag as r ON t.tag_id = r.tag_id')
            ->join('LEFT JOIN kts_topic_comment_statistic as c ON t.topic_id = c.topic_id')
            ->join('LEFT JOIN kts_topic_likes_statistic as l ON t.topic_id = l.topic_id')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($map)
            ->field('t.topic_id,t.tag_id,t.addtime,t.content,t.is_show,u.name,x.imageurl as user_image,c.discuss_number,l.like_number,r.tag_name')
            ->order($orderlist)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $data=array(
            'list'=>$result,
            'page'=>$page->show()
            );
        return $data;
    }

   /**
     * 获取父类某条数据
     * @param  [string] $map  查询的数据
     * @return [array]       一维数组
     */
    public function getFather($map)
    {
        $result= $this->alias('c1')
            ->join('LEFT JOIN kts_user as u1 ON c1.user_id = u1.user_id')
            ->field('c1.content,u1.name')
            ->where($map)
            ->find();
        return $result;
    }

    

    


}

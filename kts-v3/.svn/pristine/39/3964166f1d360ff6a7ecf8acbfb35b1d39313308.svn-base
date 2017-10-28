<?php
namespace Admin\Model;
use Think\Model;
/**
 * ModelName
 */
class TopicModel extends BaseModel{
   
    // 自动验证
    protected $_validate=array(
        array('content','require','内容必须填写'), // 验证字段必填
     );
    // 自动完成
    protected $_auto=array(
        array('addtime','time',1,'function'), // 对date字段在新增的时候写入当前时间戳
    );

    public function TopicLists($where,$limit){
        $count=$this->alias('t')           
            ->where($where)
            ->count();
        $page=new \Think\Page($count,$limit);
        // 获取分页数据
        
        $result=$this->alias('t')
            ->where($where)   
            ->join('left join kts_topic_tag tt on t.tag_id = tt.tag_id ')
            ->field('t.*,tt.tag_name')        
            ->limit($page->firstRow.','.$page->listRows)
            ->order('addtime desc')
            ->select();
        foreach ($result as $k=>$v){
            if(M('topic_recommend')->where(array('type'=>1,'topic_id'=>$v['topic_id']))->find()){
                $result[$k]['is_rec']=1;
            }else{
                $result[$k]['is_rec']=0;
            } 
        }
        $data=array(
            'list'=>$result,
            'page'=>$page->show()
            );
        return $data;
    }

   /**
     * 某话题详情
     * @param  [array] $map        [查询的值]
     * @return [array]             [数组]
     */
    public function getOneDetail($map)
    {

         $data=$this->alias('t')     
            ->join('left join kts_topic_tag tt on t.tag_id=tt.tag_id')      
            ->where($map)
            ->field('t.*,tt.tag_name')            
            ->find();
        if ($data)
        {
            $img=explode(';',$data['imageurl']);
            foreach ($img as $k=>$v){
                $img[$k]=C('QINIU').$v;
            }
            $data['imageurl']=$img;
        }
         return $data;
    }

    /**
     * 添加数据
     */
    public function addData($data){
        // 对data数据进行验证
        if(!$data=$this->create()){
            // 验证不通过返回错误
            return false;
        }else{
            // 验证通过
            $result=$this->add($data);
            return $result;
        }
    }

    /**
     * 修改数据
     */
    public function editData($map,$data){
        // 对data数据进行验证
        if(!$data=$this->create()){
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

     /**
     * 修改状态数据
     */
    public function changeStatu($where,$data){

            $result=$this
                ->where($where)
                ->save($data);
            return $result;
        
    }


}

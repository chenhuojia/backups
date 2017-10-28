<?php
namespace Read\Model;
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

}

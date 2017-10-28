<?php
namespace Home\Logic;
use Think\Model;
use User\Util\Util;
/**
 * ModelName
 */
class BookLogic extends Model{
    
    //推荐视频
    public function recommendBookVideo($skip = 0,$take = 2)
    {     
          $data=M('video')
                ->where(array('is_show'=>1,'status'=>1))
                ->field('vid,book_id,user_id,title,url,img,read_num')
                ->order(array('vid'=>'desc'))
                ->limit($skip,$take)
                ->select();
          foreach ($data as &$value) {
            $userMes = \User\Util\Util::GetUserAvatrAndNick($value['user_id']);
            $value['user_name'] = $userMes['name'];
            $value['user_avatar'] = $userMes['avatar'];
            $value['img'] =  C('QINIU_IMG_PATH').$value['img'];
            $value['url'] =  C('QINIU_IMG_PATH').$value['url'];
          }
          return $data;
    }
   


}

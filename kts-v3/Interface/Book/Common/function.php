<?php
use Book\Util\Geohash;
function comment_answer($type = 0, $three_id, $three_fid, $auser_id = 0, $auser_name, $acontent, $atype = 0)
{
    $data = M("answer")->field("id,user_id,user_name,user_content")
        ->where(array(
        'type' => $type,
        'three_id' => $three_fid
    ))
        ->find();
    if (! $data)
        return 0;
    $arr = array(
        'fid' => $data['id'], // 父id
        'type' => $type, // 类型：0话题,1书评
        'three_id' => $three_id, // 话题评论|话题|书评id
        'user_id' => $data['user_id'], // 发表人id
        'user_name' => $data['user_name'], // 发表人的昵称
        'user_content' => $data['user_content'], // 发表人发表的内容
        'answer_user_id' => $auser_id, // 回复人id
        'answer_user_name' => $auser_name, // 回复人昵称
        'answer_content' => $acontent, // 回复人发的内容
        'answer_time' => time(), // 回复时间
        'answer_type' => $atype
    ) // 回复类型:0评论,1话题2书评
;
    $answer = M('answer')->add($arr);
    if ($answer)
        return 1;
    else
        return 0;
}

function answer($type = 0, $three_id = 0, $user_id = 0, $user_content, $user_name, $auser_id = 0, $auser_name, $acontent, $atype = 1)
{
    $arr = array(
        'fid' => 0, // 父id
        'type' => $type, // 类型：0话题,1书评
        'three_id' => $three_id, // 话题评论|话题|书评id
        'user_id' => $user_id, // 发表人id
        'user_name' => $user_name, // 发表人的昵称
        'user_content' => $user_content, // 发表人发表的内容
        'answer_user_id' => $auser_id, // 回复人id
        'answer_user_name' => $auser_name, // 回复人昵称
        'answer_content' => $acontent, // 回复人发的内容
        'answer_time' => time(), // 回复时间
        'answer_type' => $atype
    ) // 回复类型:0评论,1话题2书评
;
    $answer = M('answer')->add($arr);
    if ($answer)
        return 1;
    else
        return 0;
}

function integral_log($user_id, $title, $description, $amount, $is_inc = 1, $add = 1)
{
    $Integral = M('user');
    $Integral->startTrans();
    $data = $Integral->where(array(
        'user_id' => $user_id
    ))->find();
    if ($add) {
        $result = $Integral->where(array(
            'user_id' => $user_id
        ))->setInc('integral', $amount);
    } else {
        if ($data['integral'] < $amount)
            return array(
                'status' => - 1,
                'msg' => '你的可用积分为' . $data['integral']
            );
        $result = $Integral->where(array(
            'user_id' => $user_id
        ))->setDec('integral', $amount);
    }
    if ($result) {
        $arr = array(
            'user_id' => $user_id,
            'update_time' => time(),
            'amount' => $amount,
            'title' => $title,
            'before_change' => $data['integral'],
            'after_change' => ($data['integral'] + $amount),
            'description' => $description,
            'is_inc' => $is_inc
        );
        $data = M('integral_xq')->add($arr);
    }
    if ($data) {
        $Integral->commit();
        return 1;
    } else {
        $Integral->rollback();
        return 0;
    }
}

function add_book_log($user_id, $descripte, $identify, $book_id, $book_number)
{
    $integration = M('integration')->field('source,number')
        ->where(array(
        'identify' => 'book',
        'is_deleted' => 0
    ))
        ->find();
    if (empty($integration))
        return 0;
    $Integral = M('user');
    $book = M('book')->where(array(
        'book_number' => $book_number,
        'book_id' => array(
            'neq',
            $book_id
        )
    ))->find();
    if (empty($book)) {
        $integration['number'] = $integration['number'];
    } else {
        $integration['number'] = 30;
        if ($book['type'] == 1) {
            $uploader = M('shop')->where(array(
                'shop_id' => $book['shop_id']
            ))->getField('user_id');
            if ($uploader == $user_id) {
                $integration['number'] = 0;
            }
        } else {
            if ($book['user_id'] == $user_id) {
                $integration['number'] = 0;
            }
        }
    }

    if ($integration['number'] > 0) {
        $Integral->startTrans();
        $data = $Integral->where(array(
            'user_id' => $user_id
        ))->find();
        $result = $Integral->where(array(
            'user_id' => $user_id
        ))->setInc('integral', $integration['number']);       
        $after = $integration['number'] + $data['integral'];
        if ($result) {
            $arr = array(
                'user_id' => $user_id,
                'update_time' => time(),
                'amount' => $integration['number'],
                'title' => $integration['source'],
                'description' => date('Y-m-d') . '上传图书获取到' . $integration['number'],
                'is_inc' => 1,
                'before_change' => $data['integral'],
                'after_change' => $after
            );
            $detail = M('integral_xq')->add($arr);
        }
        if ($detail) {
            $Integral->commit();
            return 1;
        } else {
            $Integral->rollback();
            return 0;
        }
    }
    return 1;
}
     
  function getUserAvater($user_id){
      $userMes = \User\Util\Util::GetUserAvatrAndNick($user_id);
      return $userMes;
  } 

  
  /**
   * 根据经纬度得出的值
   * @param [type] $longitude 经度
   * @param [type] $latitude  纬度
   */
  function addGeohash($longitude,$latitude)
  {
      $geohash = new Geohash();
      //得到这点的hash值
      $hash = $geohash->encode($latitude, $longitude);
      return $hash;
  }
  
?>
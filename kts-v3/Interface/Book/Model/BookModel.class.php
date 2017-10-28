<?php
namespace Book\Model;
use Think\Model;
/**
 */
class BookModel extends Model{


    /***
     * 新书检测
     * @param  array data
     * @param  int shop_id
     * @return array
     * **/
    public function checkData($data,$shop_id){
        if (empty($data)) return array('error'=>300,'msg'=>'参数不存在');
        if (empty($shop_id)) return array('error'=>300,'msg'=>'店铺不存在');
        $arr=array(
            'cover_img'=>'封面',                   //封面
            'copyright'=>'版权',                  //版权
            //'other'=>'其他图片',      //其他图片
            'book_name'=>'书名',                          //书名
            'author'=>'作者/编者',                 //作者/编者
            'author_area'=>'作者地区',                          //作者地区
            'language'=>'语种',                             //语种
            'publish_place'=>'出版地',                        //出版地
            'press'=>'出版社',                          //出版社
            'time'=>'时间',
            'book_number'=>'书号',                //书号
            'clc'=>'中图法类目号',                               //中图法类目号
            'cate_id'=>'图书分类',                          //图书分类
            'tag'=>'自定义分类',                     //自定义分类
            'edition'=>'版次',                               //版次
            'impression'=>'印次',                            //印次
            'words'=>'字数',                             //字数
            'page'=>'页数',                                //页数
            'format'=>'开本',                           //开本
            'sheet'=>'印张',                            //印张
            'paper'=>'纸张',                           //纸张
            'binding'=>'装帧',                          //装帧
            'price'=>'定价',                         //定价
            'desc'=>'简介',
            'catalog'=>'目录',                       //目录
            'video'=>'视频',
            'applicable_age'=>'适用年龄',                       //适用年龄
            'address'=>'定位',                   //定位
            'longitude'=>'纬度',                   //纬度
            'latitude'=>'经度',                       //经度
            'sell_price'=>'售价',                     //售价
            'inventory'=>'库存',                      //库存
            //'shipping_price'=>22,                    //邮费
            // 'extent'=>'九成新',                       //新旧程度
        );
        foreach ($data as $k=>$v){
            if($k=='time'){
                if (empty($v['common_era'])) return array('error'=>300,'msg'=>'公元必填');
                if (empty($v['lunar_calendar'])) return array('error'=>300,'msg'=>'农历必填');
                if (empty($v['calendar'])) return array('error'=>300,'msg'=>'纪年必填');
            }
            if($k=='desc'){
                if (empty($v['book_desc'])) return array('error'=>300,'msg'=>'图书简介必填');
                // if (empty($v['video']))  return array('error'=>300,'msg'=>'简介视频地址必填');
                //if (empty($v['video_cover'])) return array('error'=>300,'msg'=>'简介视频封面必填');
                //if (empty($v['picture'])) return array('error'=>300,'msg'=>'简介图片必填');
            }
            if($k=='video'){
                if (empty($v['cover'])) return array('error'=>300,'msg'=>'多媒体封面必填');
                if (empty($v['url'])) return array('error'=>300,'msg'=>'多媒体地址必填');
            }
            if (empty($v)){
                if ((empty(array_key_exists($k,$arr)) && $k != 'translator') || (empty(array_key_exists($k,$arr)) && $k !='other')){
                    if (array_key_exists($k,$arr)){
                        return array('error'=>300,'msg'=>$arr[$k].'必填');
                    }else{
                        return array('error'=>300,'msg'=>'参数不全');
                    }  
                }
            }
        }
        $cover=explode('=',$data['cover_img']);
        $book=array(
            'name'=>$data['book_name'],
            'author'=>$data['author'],
            'book_number'=>$data['book_number'],
            'price'=>$data['sell_price'],
            'type'=>1,
            'time'=>$_SERVER['REQUEST_TIME'],
            'cover_img'=>$cover[0],
            'cover_explain'=>isset($cover[1])?$cover[1]:'gg',
            'shop_id'=>$shop_id,
            'time'=>$_SERVER['REQUEST_TIME'],
            'category_path'=>$data['cate_id'],
            'press'=>$data['press'],
        );
        return array('error'=>200,'msg'=>$book);
    }
}

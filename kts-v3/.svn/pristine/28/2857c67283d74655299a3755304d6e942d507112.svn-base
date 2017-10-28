<?php
namespace Shop\Logic;
/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
use Think\Model;
use Common\Geo\Geohash;
class BookLogic  extends Model{

    /**
     * 修改新书
     * @param json data
     * @return boolean
     */
    public function editNewBook($data,$book_id,$shop_id){ 
        $bookMode=M('book');      
        $book=self::checkCommon($data);     
        if ($book['code']==300) return array('code'=>300,'msg'=>$book['msg']);
        $is_show=$bookMode->where(array('book_id'=>$book_id))->getField('isdelete');
        if ($is_show == 1){
            $bookMode->where(array('book_id'=>$book_id))->setField('isdelete',0);
            M('shop_books')->where(array('book_id'=>$book_id))->save(array('is_show'=>0,'addtime'=>$_SERVER['REQUEST_TIME']));
            M('shop')->where(array('shop_id'=>$shop_id))->setInc('on_book');
        }
        $books=$bookMode->where(array('book_id'=>$book_id))->save($book['data']);
        self::saveCommon($data,$book_id,$book['data']['cover_explain']);        
        M('book_inventory')->where(array('book_id'=>$book_id))->setField('inventroy',$data['inventory']);
        return array('code'=>200,'msg'=>'success');
    }
    
    
    private function  checkCommon($data){
        $copyright=$data['copyright'];
        $book_number=$data['book_number'];
        $book_name= $data['book_name'];
        $price=$data['price'];
        $press=$data['press'];
        $cate_id=$data['cate_id'];
        $author=$data['author'];
        $inventory=$data['inventory'];
        if (!$cover=$data['cover_img']) return array('code'=>300,'msg'=>'请上传封面');
        if ($book_name =="" || $book_name ==null){ return array('code'=>300,'msg'=>'请填写书名');}
        if ($book_number =="" || $book_number ==null){ return array('code'=>300,'msg'=>'请填写书号');}
        if ($author=="" || $author ==null){ return array('code'=>300,'msg'=>'请填写作者');}
        if (!$price) return array('code'=>300,'msg'=>'请上传价格');
        if (!$press) return array('code'=>300,'msg'=>'请上传出版社');
        if (!$cate_id) return array('code'=>300,'msg'=>'请选择分类');
        $inventory=isset($data['inventory'])?$data['inventory']:0;
        if (empty($inventory)){ return array('code'=>300, 'msg'=>'请输入库存');};
        $price=isset($data['sell_price'])?$data['sell_price']:0;
        if (empty($price)){ return array('code'=>300, 'msg'=>'请输入售价');};
        $cover=explode('=',$cover);
        $book=array(
            'name'=>$book_name,
            'author'=>$author,
            'book_number'=>$book_number,
            'price'=>$price,
            'cover_img'=>$cover[0],
            'cover_explain'=>isset($cover[1])?$cover[1]:'gg',
            'time'=>$_SERVER['REQUEST_TIME'],
            'category_path'=>$cate_id,
            'press'=>$press,
        );
        return array('code'=>200,'data'=>$book);
    }
    
   private function saveCommon($data,$book_id,$cover_explain,$type=1){
        if (isset($data['tag']) && !empty($data['tag'])){
            $tag=explode('>',$data['tag']);
            $t=array(
                'one'=>$tag[0],
                'two'=>isset($tag[1])?$tag[1]:'',
                'three'=>isset($tag[2])?$tag[2]:'',
            );
            $id=M('book_tag')->where('book_id='.$book_id)->save($t);
        }
        $att=array(
            'type'=>$type,
            'press'=>$data['press'],
            'cover_explain'=>$cover_explain,
            'publishing_place'=>isset($data['publish_place'])?$data['publish_place']:'',
            'publish_time'=>date('Y-m-d',strtotime($data['time']['common_era'])),
            'lunar_calendar'=>$data['time']['lunar_calendar'],
            'calendar'=>$data['time']['calendar'],
            'publish_price'=>$data['price'],
            'edition'=>isset($data['edition'])?$data['edition']:0,
            'impression'=>isset($data['impression'])?$data['impression']:0,
            'page'=>isset($data['page'])?$data['page']:0,
            'words'=>isset($data['words'])?$data['words']:0,
            'format'=>isset($data['format'])?$data['format']:0,
            'sheet'=>isset($data['sheet'])?$data['sheet']:0,
            'clc'=>isset($data['clc'])?$data['clc']:0,
            'paper'=>isset($data['paper'])?$data['paper']:0,
            'binding'=>isset($data['binding'])?$data['binding']:0,
            'language'=>isset($data['language'])?$data['language']:0,
            'introduce'=>isset($data['desc']['book_desc'])?$data['desc']['book_desc']:0,
            'desc_images'=>isset($data['desc']['picture'])?$data['desc']['picture']:0,
            'author_area'=>isset($data['author_area'])?$data['author_area']:1,
            'author_desc'=>isset($data['author_desc'])?$data['author_desc']:0,
            'applicable_age'=>isset($data['applicable_age'])?$data['applicable_age']:0,
            'copyright'=>$data['copyright'],
            'other'=>isset($data['other'])?$data['other']:null,
            'translator'=>isset($data['translator'])?$data['translator']:'',
            'cate_name'=>isset($data['cate_name'])?$data['cate_name']:'',                              
            );         
        if (isset($data['desc']['video']) && !empty($data['desc']['video_cover'])){
           $att['desc_video']=$data['desc']['video_cover'].';'.$data['desc']['video'];
        }
        if (isset($data['video']) && !empty($data['video'])){
            $att['video']=$data['video']['cover'].';'.$data['video']['url'];
        }
        if (isset($data['address']) && !empty($data['address'])){
            $att['address']=$data['address'];
            $att['longitude']=$data['longitude'];
            $att['latitude']=$data['latitude'];
            $att['geohash']=self::addGeohash($data['longitude'],$data['latitude']);
        }
        if (isset($data['catalog']) && !empty($data['catalog'])){
            $att['catalog']=$data['catalog'];
        }
       
       return  M('book_attr')->where(array('book_id'=>$book_id))->save($att);
    }
    /**
     * 根据经纬度得出的值
     * @param [type] $longitude 经度
     * @param [type] $latitude  纬度
     */
    public function addGeohash($longitude,$latitude)
    {
        import("Common.Vendor.geohash");
        $geohash = new Geohash();
        //得到这点的hash值
        $hash = $geohash->encode($latitude, $longitude);
        return $hash;
    }
}
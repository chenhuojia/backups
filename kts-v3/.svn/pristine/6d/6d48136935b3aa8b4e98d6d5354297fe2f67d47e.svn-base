<?php
namespace User\Model;
use Think\Model;
/**
 * ModelName
 */
class BooklistModel extends Model{
   
    // 自动验证
    protected $_validate=array(     
     
      array('name','require','请填写书单名称',1),
     );
   // 自动完成
    protected $_auto=array(
        array('addtime','addtime',3,'callback'),
        array('user_id','checkuser',3,'callback'),
        array('introduct','introduct',3,'callback'),
        array('cover','addCover',3,'callback'),
    );
    
   protected function checkuser(){
       return session('user_id');
   }

   protected function addtime(){
       return date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
   }
   
   protected function introduct(){
       return I('post.introduct','');
   }
   protected function addCover(){
       return I('post.cover','');
   }
   /**
    * 我建立的书单
    * 
    * ***/
   public function myBookList($user_id,$skip,$take){
       $data=$this->field('id as booklist_id,cover,name,books_num')
       ->where(array('user_id'=>$user_id,'is_show'=>1))
       ->order('addtime desc')
       ->limit($skip,$take)
       ->select();
       if ($data){
           foreach ($data as $k=>$v){
               $data[$k]['cover'] = C("QINIU_IMG_PATH").$v['cover'];
           }
           return array('code'=>200,'msg'=>$data);
       }
       return array('code'=>300,'msg'=>'您暂时还没建立书单~');
   }
   

   /**
    * 书单介绍
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @return number[]|string[]
    * ***/
   public function bookListDesc($user_id,$booklist_id){
       $data=$this
       ->field('id as booklist_id,cover,name,introduct,user_id')
       ->where(array('id'=>$booklist_id,'is_show'=>1))
       ->find();
       if ($data){
           $user= \User\Util\Util::GetUserAvatrAndNick($data['user_id']);
           $data['introduct']=$data['introduct']?$data['introduct']:'';
           $data['user_name']=$user['name'];
           $data['user_avatar']=$user['avatar'];
           $data['cover'] = C("QINIU_IMG_PATH").$data['cover'];
           return array('code'=>200,'msg'=>$data);
       }
       return array('code'=>300,'msg'=>'还没建立书单~');
   }
   
   /**
    * 书单详情
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @return number[]|number[]|string[]
    * ***/
   public function bookListDet($booklist_id,$user_id=0){
       $data=$this
       ->field('id as booklist_id,cover,name,introduct,books_num,collect_num,discuss_num,share_num,user_id')
       ->where(array('id'=>$booklist_id,'is_show'=>1))
       ->find();
       if ($data){
           $user= \User\Util\Util::GetUserAvatrAndNick($data['user_id']);
           $data['user_name']=$user['name'];
           $data['user_avatar']=$user['avatar'];
           $data['cover'] = C("QINIU_IMG_PATH").$data['cover'];
           $data['is_collect']=0;
           $data['is_collect']=0;
           $data['is_collect']=0;
           if ($user_id){
               $collect=M()->execute('select * from kts_booklist_collect where user_id='.$user_id.' and is_collect =1 and booklist_id = '.$booklist_id);
               $share=M()->execute('select * from kts_booklist_share where user_id='.$user_id.' and booklist_id = '.$booklist_id);
               $discuss=M()->execute('select * from kts_booklist_discuss where user_id='.$user_id.' and is_show =1 and booklist_id = '.$booklist_id);
               $data['is_collect']=$collect?1:0;
               $data['is_share']=$share?1:0;
               $data['is_discuss']=$discuss?1:0;
           }
          return array('code'=>200,'msg'=>$data);
       }
       return array('code'=>300,'msg'=>'您暂时还没建立书单~');
   }
     
   /**
    * 编辑书单
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @param unknown $type
    * @return number[]|string[]
    * ***/
   public function editBooklist($user_id,$booklist_id,$type){
       $data=$this->where(array('id'=>$booklist_id,'user_id'=>$user_id))->find();
       if ($data){
           switch ($type){
               case 1:
                   $cover=I('post.cover',0);
                   if (empty($cover)) return array('code'=>300,'msg'=>'请上传封面');
                   $this->where(array('id'=>$booklist_id,'user_id'=>$user_id))->setField('cover',$cover);
                   return array('code'=>200,'msg'=>'success');
                   break;
               case 2:
                   $name=I('post.name',0);
                   if (empty($name)) return array('code'=>300,'msg'=>'请填写书单名称');
                   $this->where(array('id'=>$booklist_id,'user_id'=>$user_id))->setField('name',$name);
                   return array('code'=>200,'msg'=>'success');
                   break;
               case 3:
                   $name=I('post.introduct',0);
                   if (empty($name)) return array('code'=>300,'msg'=>'请填写书单描述');
                   $this->where(array('id'=>$booklist_id,'user_id'=>$user_id))->setField('introduct',$name);
                   return array('code'=>200,'msg'=>'success');
                   break;
               case 4:
                   $introduct=I('post.introduct',0);
                   $name=I('post.name',0);
                   $cover=I('post.cover',0);
                   if (empty($name)) return array('code'=>300,'msg'=>'请填写书单名');
                   $this->where(array('id'=>$booklist_id,'user_id'=>$user_id))->save(array('name'=>$name,'introduct'=>$introduct,'cover'=>$cover));
                   return array('code'=>200,'msg'=>'success');
                   break;
               default:
                   return array('code'=>300,'msg'=>'参数有误');
                   break;
           }
       }
       return array('code'=>300,'msg'=>'书单不存在');
   }

   /**
    * 收藏||取消收藏书单
    * @param unknown $user_id
    * @param unknown $booklist_id
    * @return number[]|string[]***/
   public function bookListCollect($user_id,$booklist_id){
       if ($this->find($booklist_id)){
           $data=M('booklist_collect')->where(array('user_id'=>$user_id,'booklist_id'=>$booklist_id))->find();          
           if ($data){
               if ($data['is_collect']==1){
                   M()->execute('update kts_booklist_collect set is_collect = 0 , addtime ='.$_SERVER['REQUEST_TIME'].' where user_id ='.$user_id.' and booklist_id ='.$booklist_id);
                   $this->where(array('booklist_id'=>$booklist_id,'user_id'=>$user_id))->setDec('collect_num');
               }else{
                   M()->execute('update kts_booklist_collect set is_collect = 1 , addtime ='.$_SERVER['REQUEST_TIME'].' where user_id ='.$user_id.' and booklist_id ='.$booklist_id);
                   $this->where(array('booklist_id'=>$booklist_id,'user_id'=>$user_id))->setInc('collect_num');
               }
           }else{
               M('booklist_collect')->add(array('booklist_id'=>$booklist_id,'user_id'=>$user_id,'addtime'=>$_SERVER['REQUEST_TIME']));
               $this->where(array('booklist_id'=>$booklist_id,'user_id'=>$user_id))->setInc('collect_num');             
           }
           return array('code'=>200,'msg'=>'success');
       }
       return array('code'=>300,'msg'=>'书单不存在');
   }

   /**
    * 分享书单
    * @param string  $user_id
    * @param string  $booklist_id
    * @param string  $shareto
    * @return number[]|string[]***/
   public function bookListShare($user_id,$booklist_id,$shareto){
       if ($this->find($booklist_id)){      
           M('booklist_share')->add(array('booklist_id'=>$booklist_id,'user_id'=>$user_id,'addtime'=>$_SERVER['REQUEST_TIME'],'shareto'=>$shareto));
           $this->where(array('booklist_id'=>$booklist_id,'user_id'=>$user_id))->setInc('share_num');           
           return array('code'=>200,'msg'=>'success');
       }
       return array('code'=>300,'msg'=>'书单不存在');
   }

}

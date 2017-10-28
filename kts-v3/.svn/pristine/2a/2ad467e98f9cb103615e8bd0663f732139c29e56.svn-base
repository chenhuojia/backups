<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function header()
    {
        $keyword=M('config')->where("config_field= 'KeyWords' and config_type=5")->getField('config_value');
        $description=M('config')->where("config_field= 'Description' and config_type=5")->getField('config_value');
        $title ="士加堡快图书-首页";
        $this->assign('title',$title);
        $this->assign('keyword',$keyword);
        $this->assign('description',$description);
        $user = session('user');
        var_dump($user);
        $this->assign('user',$user);
        $this->display();
        
    }
    public function index(){
        // $user = session('user');
        // var_dump($user);
        $user_id = (session('user')['user_id']>0) ? session('user')['user_id'] : 0;
        $banner=M("banner")->where(array('type'=>1,'is_show'=>1))->limit(3)->select();    
        $this->assign('banner',$banner);
        //推荐的四本书
        $recommend=$this->recommendBook();    
        $this->assign('recommend',$recommend);
        //一二级栏目
        $categoryOne=$this->categoryOne();    
        $this->assign('categoryOne',$categoryOne);
        //栏目书籍展示
        /*gfdgd*/
        //热门书评
        $hotCommentLists=$this->hotCommentLists($take=3,$user_id);    
        $this->assign('hotCommentLists',$hotCommentLists);
        //活跃用户
        $activeUsers = $this->ActiveUsers(13,$user_id);
        $this->assign('activeUsers',$activeUsers);

        $this->display();
    }

    public function recommendBook()
    {
       $data =M("book_recommend")
             ->limit(4)
             ->select();
       foreach ($data as $key => $value) {
            $data[$key]['imageurl'] = M('image')->where(array('book_id'=>$data[$key]['book_id'],'type'=>1))->getField('imageurl');
        }
        return $data;
    }

    /**
     * 获取一二级栏目的值
     */
    public function categoryOne()
    {   
        header("Content-type: text/html;charset=utf-8");
        $one = M('category')->where(array('f_id'=>0,'is_show'=>1))->select();
        foreach ($one as $key => $value) {
            $one[$key]['two'] = M('category')->where(array('f_id'=>$one[$key]['cg_id'],'is_show'=>1))->select();
        }
        // var_dump($one);
        // die;
        return $one;
    }

     /**
     * 获取第三级上的对应栏目的所有书籍
     */
    public function categoryBook()
    {   
        header("Content-type: text/html;charset=utf-8");
        $one_id = I('get.one_id');//分类的第一个id
        $two_id = I('get.two_id',0);//分类的第二个id,当为0表示推荐
        if($two_id==0){
           // $one1=M('book_recommend')->alias('d')
           //      ->join('LEFT JOIN kts_book_tag as t ON t.book_id = d.book_id')
           //      ->field('d.cg_id')
           //      ->select();//查到第二级
           // foreach ($one1 as $k => $v) {
           //     $one1=M('category')->where(array('f_id'=>$one1[$k]['cg_id'],'is_show'=>1))->select();
           // }
        }
        $one = M('category')->where(array('f_id'=>$cg_id,'is_show'=>1))->select();
        foreach ($one as $key => $value) {
            $where['t.cg_id'] = $one[$key]['cg_id'];
            $one[$key]['booklist'] = M('book')->alias('o')
                        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
                        ->join('LEFT JOIN kts_book_tag as t ON t.book_id = o.book_id')
                        ->where($where)
                        ->group('o.book_id')
                        ->field('o.book_id,o.name,o.author,o.price,u.name as share_user,o.user_id,o.type')
                        ->limit(8)->select();
                        }
        $this->list=$items;
        $this->display();
    }



    /**
     * 搜索图书
     */
    public function searchBook()
    {   
        $user_id = I('get.user_id',0);
        $type = I('get.type',0);//0书名、1作者、2出版社
        $message = I('get.message','');
        switch ($type)
        {
            case 0; //书名
                $where['o.name'] = array('like','%'.$message.'%'); 
                break;
            case 1: //作者
                $where['o.author'] = array('like','%'.$message.'%');
                break;
            case 2: //出版社
                $where['r.press'] = array('like','%'.$message.'%');
                break;
            default:
                $this->error('请选择正确的排序选项');
        }
        $count=M('book')->alias('o')
               ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
               ->where($where)->count();
        $page=new \Think\Page($count,5);
        // 通过匹配信息查询出相关书籍
        $bookList = M('book')->alias('o')
        ->join('LEFT JOIN kts_image as s ON s.book_id = o.book_id')
        ->join('LEFT JOIN kts_book_attr as r ON r.book_id = o.book_id')
        ->join('LEFT JOIN kts_old_book as p ON p.book_id = o.book_id')
        ->where($where)
        ->group('o.book_id')
        ->field('o.book_id,o.name,o.author,o.price,o.name as share_user,o.user_id,o.type,s.imageurl,p.description')
        ->limit($page->firstRow.','.$page->listRows)->select();
        // var_dump($bookList) ;
        // die;
        foreach ($bookList as $key => $value) {
            $bookList[$key]['kind'] = $bookList[$key]['type'];
            if($bookList[$key]['user_id'] == $user_id)
            {
                $bookList[$key]['kind'] =2;//kind为2代表是该用户分享
            }
            if($bookList[$key]['description']==null)
            {
                $bookList[$key]['description'] = "";
            }
            if($bookList[$key]['share_user']==null)
            {
                $bookList[$key]['share_user'] = "";
            }
            
        }
        $this->page=$page->show();
        $this->list=$bookList;
        $this->display();
    }

    /**
     *发表话题活跃用户列表
     */
    public function ActiveUsers($take=13,$user_id=0)
    {   
        $where['t.is_show'] = 1;  
        $result=M('Topic')
            ->alias('t')
            ->join('LEFT JOIN kts_user as u ON t.user_id = u.user_id')
            ->join('LEFT JOIN kts_user_xq as x ON t.user_id = x.user_id')
            ->where($where)
            ->field('t.user_id as follow_user,u.name,x.imageurl as user_image,SUM(t.is_show) as total_topic')
            ->group('t.user_id')
            ->order('total_topic desc')
            ->limit($take)
            ->select();
        foreach ($result as $key => $value) {
          $result[$key]['is_follow'] = 0;
          //查询某话题下某用户已经关注
          $likesmap =array('user_id' =>$user_id,'follow_user'=>$result[$key]['follow_user']);
          $is_follow = D('topic_follow')->where($likesmap)->find();
          if($is_follow){
            $result[$key]['is_follow'] = 1;
          }
        }
        //var_dump($result);die;
        return $result;
    }

    /**
     * 热门书评相关展示
     */
    public function hotCommentLists($take=3,$user_id)
    {
        $map['t.is_show'] = 1;
        $map['t.fid'] = 0;//父级评论
        $va=M('book_comment')
                ->alias('t')
                ->join('LEFT JOIN kts_book as b ON t.book_id = b.book_id')
                ->join('LEFT JOIN kts_comment_click as l ON t.comment_id = l.comment_id')
                ->where($map)
                ->field('t.comment_id,t.book_id,t.comment_time,t.content,t.grade,t.sums,b.name,b.author,t.username,t.imageurl as user_image,l.sum as likes')
                ->order('t.sums desc')
                ->limit($take)
                ->select();
        foreach ($va as $key => $value) {
          $va[$key]['is_like'] = 0;
          //查询某书评下某用户已经点赞
          $likesmap =array('user_id' =>$user_id,'comment_id'=>$va[$key]['comment_id']);
          $is_like = M('book_comment_click')->where($likesmap)->find();
          if($is_like){
            $va[$key]['is_like'] = 1;
          }
          //查询某书评下的第一张图片
          $imagemap =array('book_id'=>$va[$key]['book_id'],'type'=>1);
          $va[$key]['book_image'] = M('image')->where($imagemap)->getField('imageurl');
          if($va[$key]['book_image']==null){
            $va[$key]['book_image'] ="";
          } 
         }
        return $va;          
       
    }

}
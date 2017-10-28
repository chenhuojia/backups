<?php
namespace weixin\index\controller;
use think\controller;
use weixin\index\common\controller\common;
use think\Config;
use think\Db;
use weixin\index\model\Music;
use weixin\index\controller\Template;
class Index extends common
{
    public function index()
    {  
        define("TOKEN",'chenhuojia');       
        $this->responseMsg();exit();
        if(!$this->checkSignature()){ exit;}
        $request = request();
        $echoStr = $request->get('echostr');
        if($echoStr){
            echo $echoStr;
            exit;
        }else{
            $this->responseMsg();
        } 
        
    }
    
    public function indexTest()
    {
        define("TOKEN",'chenhuojiajia'); 
        //$test_access_token=self::check_test_access_token();
        $this->responseMsg();
        /* if(!$this->checkSignature()){ exit;}
        $request = request();
        $echoStr = $request->get('echostr');
        if($echoStr){
            echo $echoStr;
            exit;
        }else{
            $this->responseMsg();
        } */
    
    }

    
    public function delMenu(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$this->test_access_token;
        $result=httpsRequest($url);
        return $result;
    }
    
    public function addMenu(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->test_access_token;
        $data=array(
            'button'=>array(
                array(
                    'name'=>'我的简历',
                    'sub_button'=>array(
                        array(
                            'type'=>'view',
                            'name'=>'博客',
                            'url'=>'https://chenhuojia.xin',                            
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'论坛',
                            'url'=>'https://chenhuojia.xin/discuz.php/Index',
                        ),
                        array(
                             'type'=>'click',
                             'name'=>'来首歌吧',
                             'key'=>'MUSIC'
                        ),
                    ),
                ),
                array(
                    'name'=> '发送位置',
                    'type'=> 'location_select',
                    'key'=> 'rselfmenu_2_0'
                )
            ),
        );
        //return print_r(json_decode($str,true));
        $data=json_encode($data,JSON_UNESCAPED_UNICODE);
        
        $result=httpsRequest($url,$data);
        return $result;
    }
    
    public function getMenu(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$this->test_access_token;
        $result=httpsRequest($url);
        return $result;
    }
    
    public function createMenu(){
        $url='https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token='.$this->test_access_token;
        $result=httpsRequest($url,$str);
        return $result;
    }
    
    public function getoppenId(){
        $url=' https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=OPENID&lang=zh_CN';
    }
    

 //自动回复消息的功能
  public function responseMsg(){ 
    //接收微信服务器过来的XML文档,因为PHP不识别XML文档数据，所以 输入流接收
    $postStr = file_get_contents("php://input");  
    if (!empty($postStr)){
      libxml_disable_entity_loader(true); //验证xml数据的有效性
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //读取xml，把xml转成对象 $postObj
      $msgType = $postObj->MsgType; //消息类型
      if($msgType =='text'){
        $keyword  = trim($postObj->Content);  //文本消息的内容
        if( $keyword == '音乐' || $keyword == 'music' ){  //判断文本消息的内容
          $music=new Music();         
          $data=$music->getRandData();
          $Title       = $data['name']; //音乐标题
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo Template::responseMusic($postObj, $Title, $Description, $MusicUrl);exit;
        }elseif($keyword == '陈伙佳'){
          $contentStr = "是你大爷";
          echo template::responseText($postObj, $contentStr);exit;
        }else{
            /* $content=httpsRequest('http://op.juhe.cn/robot/index?info='.$keyword.'&key=您申请到的APPKEY');
            if ($content->error_code == 0){
                $contentStr=$content->result->text;
            }else{
                $contentStr='你是傻逼';
            }  */ 
          $keyarr=explode('|',$keyword);          
          if (count($keyarr) > 1){
              $tmp=array('key'=>'9cb411bc04a84bff87156f76d93fa113');
              $tmp['info']=$keyarr[0];
              $tmp['loc']=$keyarr[1];             
              $tmp['userid']=mt_rand(1,200);
              $content=http_curl('http://www.tuling123.com/openapi/api?',$tmp);              
          }else{
              $content=httpsRequest('http://www.tuling123.com/openapi/api?key=9cb411bc04a84bff87156f76d93fa113&info='.$keyword);
              //$content=http_curl('http://www.tuling123.com/openapi/api?',$tmp);          
          }
          $content=json_decode($content,true);
          if (preg_match('/.*新闻.*/', $keyword)){
             if ($content['code']==302000){
                 $article=array();
                 foreach ($content['list'] as $K=>$v){
                     if ($K >= 5){
                         break;
                     }else{
                         $article[]=$v;
                     }
                 }
                 echo template::responseTuLingNews($postObj, $article);exit;
             }elseif ($content['code']==200000){
                 $article=array(
                     array(
                         'article'=>$content['text'],
                         'source'=>'今日新闻',
                         'icon'=>'https://chenhuojia.xin/Public/images/3.jpg',
                         'datailurl'=>$content['url'],
                     )
                 );
                 echo template::responseTuLingNews($postObj, $article);exit;
             }elseif ($content['code']==100000){
                 echo template::responseText($postObj,'网络有误,抱歉！');exit;
             }
          }
          $contentStr=isset($content['url'])?$content['url']:$content['text'];
          echo template::responseText($postObj, $contentStr);exit;
        }
      }elseif($msgType == 'event'){  //判断消息类型是否是事件
        $event = (string)$postObj->Event; //事件类型
        $method = 'receive' . $event;
        //判断对应事件接收方法是否存在于当前对象中
        if( method_exists($this,$method) ){
          //如果存在，则根据事件类型调用对应方法
          echo $this->$method($postObj); exit;
        }
      }elseif($msgType == 'music'){  
          $music=new Music();
          $data=$music->getRandData();
          $Title       = $data['name']; //音乐标题
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo template::responseMusic($postObj, $Title, $Description, $MusicUrl);exit;
      }
    }else{
      exit("");
    }
  }

  //接收菜单点击事件
  public function receiveCLICK($postObj){
    $key = (string)$postObj->EventKey; //接收当前点击菜单按钮的key值 
    if($key == 'MUSIC' ){
      $music=new Music();                   
          $data=$music->getRandData();
          $Title       = $data['name']; //音乐标题
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo template::responseMusic($postObj, $Title, $Description, $MusicUrl);
    }
  }

  //接收关注事件
  public function receivesubscribe($postObj){
    //把当前用户的openid存入数据库

    //发送欢迎信息
    $contentStr = "欢迎关注:发送音乐可以播放歌曲;发送XXX城市天气可以查找该城市的天气;发送笑话可以查看笑话;发送讲个故事可以听讲故事;发送个人姓名可以吉凶查询;发送新闻可以查看今日新闻;发送查询快递+快递号可以查看快递等功能";
    echo template::responseText($postObj, $contentStr);
  }

  //接收取消关注事件
  public function receiveunsubscribe($postObj){
      
  }
    //只能做一些业务逻辑操作，帐号解绑等功能
 

  public function text(){
      $keyword='新闻';
      $content=httpsRequest('http://www.tuling123.com/openapi/api?key=9cb411bc04a84bff87156f76d93fa113&info='.$keyword);
      $content=json_decode($content,true);
      if (preg_match('/.*新闻.*/', $keyword)){
         if ($content['code']==302000){
             $article=array();
             foreach ($content['list'] as $K=>$v){
                 if ($K >= 7){
                     break;
                 }else{
                     $article[]=$v;
                 }
             }
             echo template::responseTuLingNews($postObj, $article);exit;
         }elseif ($content['code']==200000){
             $article=array(
                 array(
                     'article'=>$content['text'],
                     'source'=>'今日新闻',
                     'icon'=>'https://chenhuojia.xin/Public/images/3.jpg',
                     'datailurl'=>$content['url'],
                 )
             );
             echo template::responseTuLingNews($postObj, $article);exit;
         }elseif ($content['code']==100000){
             echo template::responseText($postObj,'网络有误,抱歉！');exit;
         }
      }
  }
    
}

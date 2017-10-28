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
                    'name'=>'鎴戠殑绠�鍘�',
                    'sub_button'=>array(
                        array(
                            'type'=>'view',
                            'name'=>'鍗氬',
                            'url'=>'https://chenhuojia.xin',                            
                        ),
                        array(
                            'type'=>'view',
                            'name'=>'璁哄潧',
                            'url'=>'https://chenhuojia.xin/discuz.php/Index',
                        ),
                        array(
                             'type'=>'click',
                             'name'=>'鏉ラ姝屽惂',
                             'key'=>'MUSIC'
                        ),
                    ),
                ),
                array(
                    'name'=> '鍙戦�佷綅缃�',
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
    

 //鑷姩鍥炲娑堟伅鐨勫姛鑳�
  public function responseMsg(){ 
    //鎺ユ敹寰俊鏈嶅姟鍣ㄨ繃鏉ョ殑XML鏂囨。,鍥犱负PHP涓嶈瘑鍒玐ML鏂囨。鏁版嵁锛屾墍浠� 杈撳叆娴佹帴鏀�
    $postStr = file_get_contents("php://input");  
    if (!empty($postStr)){
      libxml_disable_entity_loader(true); //楠岃瘉xml鏁版嵁鐨勬湁鏁堟��
      $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //璇诲彇xml锛屾妸xml杞垚瀵硅薄 $postObj
      $msgType = $postObj->MsgType; //娑堟伅绫诲瀷
      if($msgType =='text'){
        $keyword  = trim($postObj->Content);  //鏂囨湰娑堟伅鐨勫唴瀹�
        if( $keyword == '闊充箰' || $keyword == 'music' ){  //鍒ゆ柇鏂囨湰娑堟伅鐨勫唴瀹�
          $music=new Music();         
          $data=$music->getRandData();
          $Title       = $data['name']; //闊充箰鏍囬
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo Template::responseMusic($postObj, $Title, $Description, $MusicUrl);exit;
        }elseif($keyword == '闄堜紮浣�'){
          $contentStr = "鏄綘澶х埛";
          echo template::responseText($postObj, $contentStr);exit;
        }else{
            /* $content=httpsRequest('http://op.juhe.cn/robot/index?info='.$keyword.'&key=鎮ㄧ敵璇峰埌鐨凙PPKEY');
            if ($content->error_code == 0){
                $contentStr=$content->result->text;
            }else{
                $contentStr='浣犳槸鍌婚��';
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
          if (preg_match('/.*鏂伴椈.*/', $keyword)){
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
                         'source'=>'浠婃棩鏂伴椈',
                         'icon'=>'https://chenhuojia.xin/Public/images/3.jpg',
                         'datailurl'=>$content['url'],
                     )
                 );
                 echo template::responseTuLingNews($postObj, $article);exit;
             }elseif ($content['code']==100000){
                 echo template::responseText($postObj,'缃戠粶鏈夎,鎶辨瓑锛�');exit;
             }
          }
          $contentStr=isset($content['url'])?$content['url']:$content['text'];
          echo template::responseText($postObj, $contentStr);exit;
        }
      }elseif($msgType == 'event'){  //鍒ゆ柇娑堟伅绫诲瀷鏄惁鏄簨浠�
        $event = (string)$postObj->Event; //浜嬩欢绫诲瀷
        $method = 'receive' . $event;
        //鍒ゆ柇瀵瑰簲浜嬩欢鎺ユ敹鏂规硶鏄惁瀛樺湪浜庡綋鍓嶅璞′腑
        if( method_exists($this,$method) ){
          //濡傛灉瀛樺湪锛屽垯鏍规嵁浜嬩欢绫诲瀷璋冪敤瀵瑰簲鏂规硶
          echo $this->$method($postObj); exit;
        }
      }elseif($msgType == 'music'){  
          $music=new Music();
          $data=$music->getRandData();
          $Title       = $data['name']; //闊充箰鏍囬
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo template::responseMusic($postObj, $Title, $Description, $MusicUrl);exit;
      }
    }else{
      exit("");
    }
  }

  //鎺ユ敹鑿滃崟鐐瑰嚮浜嬩欢
  public function receiveCLICK($postObj){
    $key = (string)$postObj->EventKey; //鎺ユ敹褰撳墠鐐瑰嚮鑿滃崟鎸夐挳鐨刱ey鍊� 
    if($key == 'MUSIC' ){
      $music=new Music();                   
          $data=$music->getRandData();
          $Title       = $data['name']; //闊充箰鏍囬
          $Description = $data['description'];
          $MusicUrl    = $data['url'];
          echo template::responseMusic($postObj, $Title, $Description, $MusicUrl);
    }
  }

  //鎺ユ敹鍏虫敞浜嬩欢
  public function receivesubscribe($postObj){
    //鎶婂綋鍓嶇敤鎴风殑openid瀛樺叆鏁版嵁搴�

    //鍙戦�佹杩庝俊鎭�
    $contentStr = "娆㈣繋鍏虫敞:鍙戦�侀煶涔愬彲浠ユ挱鏀炬瓕鏇�;鍙戦�乆XX鍩庡競澶╂皵鍙互鏌ユ壘璇ュ煄甯傜殑澶╂皵;鍙戦�佺瑧璇濆彲浠ユ煡鐪嬬瑧璇�;鍙戦�佽涓晠浜嬪彲浠ュ惉璁叉晠浜�;鍙戦�佷釜浜哄鍚嶅彲浠ュ悏鍑舵煡璇�;鍙戦�佹柊闂诲彲浠ユ煡鐪嬩粖鏃ユ柊闂�;鍙戦�佹煡璇㈠揩閫�+蹇�掑彿鍙互鏌ョ湅蹇�掔瓑鍔熻兘";
    echo template::responseText($postObj, $contentStr);
  }

  //鎺ユ敹鍙栨秷鍏虫敞浜嬩欢
  public function receiveunsubscribe($postObj){
      
  }
    //鍙兘鍋氫竴浜涗笟鍔￠�昏緫鎿嶄綔锛屽笎鍙疯В缁戠瓑鍔熻兘
 

  public function text(){
      $keyword='鏂伴椈';
      $content=httpsRequest('http://www.tuling123.com/openapi/api?key=9cb411bc04a84bff87156f76d93fa113&info='.$keyword);
      $content=json_decode($content,true);
      if (preg_match('/.*鏂伴椈.*/', $keyword)){
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
                     'source'=>'浠婃棩鏂伴椈',
                     'icon'=>'https://chenhuojia.xin/Public/images/3.jpg',
                     'datailurl'=>$content['url'],
                 )
             );
             echo template::responseTuLingNews($postObj, $article);exit;
         }elseif ($content['code']==100000){
             echo template::responseText($postObj,'缃戠粶鏈夎,鎶辨瓑锛�');exit;
         }
      }
  }
    
}

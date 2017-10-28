<?php
namespace weixin\index\controller;
use think\controller;
class Template extends controller{
    
    /*
     * @function 回复音乐
     * @param   object     $postObj     微信发送过来的xml文档[对象]
     * @param   string     $Title       音乐标题
     * @param   string     $Description 音乐相关描述
     * @param   string     $MusicUrl    有损音乐的地址
     * @param   string     $HQMusicUrl  无损音乐的地址[默认是有损音乐的地址]
     */
    public static function responseMusic($postObj, $Title, $Description, $MusicUrl, $HQMusicUrl=null){
        $musicTpl = "<xml>
                  <ToUserName><![CDATA[%s]]></ToUserName>
                  <FromUserName><![CDATA[%s]]></FromUserName>
                  <CreateTime>%s</CreateTime>
                  <MsgType><![CDATA[music]]></MsgType>
                  <Music>
                  <Title><![CDATA[%s]]></Title>
                  <Description><![CDATA[%s]]></Description>
                  <MusicUrl><![CDATA[%s]]></MusicUrl>
                  <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
                  </Music>
                </xml>";
        if( $HQMusicUrl == null ){
            $HQMusicUrl = $MusicUrl;
        }
        return sprintf($musicTpl,$postObj->FromUserName,$postObj->ToUserName,time(),$Title,$Description, $MusicUrl, $HQMusicUrl);
    }
    
    

    /*
     * @function 回复文本
     * @param    object     $postObj     微信服务器发送过来的xml[对象]
     * @param    string     $contentStr  要回复的内容
     * @return   string
     */
    public static  function responseText($postObj, $contentStr){
        //文本消息的模板
        $textTpl = "<xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%s</CreateTime>
                 <MsgType><![CDATA[text]]></MsgType>
                 <Content><![CDATA[%s]]></Content>
                </xml>";           
        $contentStr = mb_substr($contentStr,0,600,'utf-8');
        return sprintf($textTpl,$postObj->FromUserName,$postObj->ToUserName,time(),$contentStr);
    }

    /*
     * @function 回复图片
     */
    public static function responseImage(){
    
    }
    
    /*
     * @function 回复图文
     */
    public static function responseNews($postObj,$article){
            $toUser = $postObj->FromUserName;
            $fromUser = $postObj->ToUserName;
			$template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($article)."</ArticleCount>
						<Articles>";
			foreach($article as $k=>$v){
				$template .="<item>
							<Title><![CDATA[".$v['title']."]]></Title> 
							<Description><![CDATA[".$v['description']."]]></Description>
							<PicUrl><![CDATA[".$v['picUrl']."]]></PicUrl>
							<Url><![CDATA[".$v['url']."]]></Url>
							</item>";
			}
			
			$template .="</Articles>
						</xml> ";
			return  sprintf($template,$toUser,$fromUser,time(),'news');
    }
    
    /*
     * @function 回复图文
     */
    public static function responseTuLingNews($postObj=0,$article){
         $toUser = $postObj->FromUserName;
         $fromUser = $postObj->ToUserName;
        $template = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<ArticleCount>".count($article)."</ArticleCount>
						<Articles>";
        foreach($article as $k=>$v){           
            $template .="<item>
							<Title><![CDATA[".$v['article']."]]></Title>
							<Description><![CDATA[".$v['source']."]]></Description>
							<PicUrl><![CDATA[".$v['icon']."]]></PicUrl>
							<Url><![CDATA[".$v['detailurl']."]]></Url>
							</item>";
        }
       
        $template .="</Articles>
                     </xml> ";
        
        return  sprintf($template,$toUser,$fromUser,time(),'news');
    }
    
    /*
     * @function 回复视频
     */
    public static function responseVideo($postObj,$mediaid,$title,$desc){
        $videoTpl="<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Video>
                <MediaId><![CDATA[%s]]></MediaId>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                </Video> 
                </xml>";
      return sprintf($videoTpl,$postObj->ToUserName,$postObj->FromUserName, time(), $mediaid,$title,$desc);
    }
    
    /*
     * @function 回复图片
     */
    public static function responsePic($postObj,$mediaid){
        $picTpl="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[image]]></MsgType>
                    <Image>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Image>
                    </xml>";
        return sprintf($picTpl,$postObj->ToUserName,$postObj->FromUserName,time(),$mediaid);
    }
    
    /*
     * @function 回复语音消息
     */
    public static function responseVoice($postObj,$mediaid){
        $voiceTpl="<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[voice]]></MsgType>
                    <Voice>
                    <MediaId><![CDATA[%s]]></MediaId>
                    </Voice>
                 </xml>";
        return sprintf($voiceTpl,$postObj->ToUserName,$postObj->FromUserName,time(),$mediaid);
    }
}
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf8">
<title>陈伙佳博客</title>

<link href="/Public/css/style.css" rel="stylesheet">
<link href="/Public/css/animation.css" rel="stylesheet">

<!-- 返回顶部调用 begin<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"> -->
<link href="/Public/css/lrtk.css" rel="stylesheet" />

<script type="text/javascript" src="/Public/js/jquery-1.7.1.js"></script>
<script type="text/javascript" src="/Public/js/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/js.js"></script>
<link rel="shortcut icon" type="image/x-icon" href="/Public/images/logo2.png" />
<!-- Modernizr JS -->
<script src="/Public/js/modernizr-2.6.2.min.js"></script>
<script src=" https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<!-- 返回顶部调用 end-->

<!--[if lt IE 9]>
<script src="js/modernizr.js"></script>
<![endif]-->
<style type="text/css">
 #nav ul li{position:relative;float:left;}
 #nav dl{position:absolute;margin-top:40px;display:none;z-index:100;}
 #nav dl dd{cursor:pointer;margin-bottom:3px;padding:0 20px;box-shadow:0px 1px 0px rgba(255,255,255,.1), inset 0px 1px 1px rgba(0,0,0,.7);border-radius:6px;}
 #nav dl dd:hover{background:rgba(20, 20, 20, 0.8);}
</style>
</head>
<body>
<div class="topnav">
<?php if($_SESSION['userInfo']){echo "欢迎你！ ".$_SESSION['userInfo']['name']."<a href=\"javascript:logout();\">退出</a>";}?>
</div>
<script>
		function logout(){
			$.ajax({
				url:"<?php echo U('Home/Login/logout');?>",
				data:'',
				type:"post",
				datatype:'json',
				success:function(e){
					window.location.reload(); 
					
				}
			})
		}

 $(function(){	
	 $('#nav ul li').hover(	 
			 function () {				    
				    $(this).find('dl').stop(true,true).slideDown().siblings().stop(true,true).find('dl').hide();
				  },
			  function () {
				
			    $(this).find('dl').stop(true,true).slideUp().siblings().stop(true,true).find('dl').hide();
			  }
		);
 })
//alert(location.href.split('#')[0]);
//alert('<?php echo ($data["jssdk"]["url"]); ?>');
/* wx.config({
    debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo ($data["jssdk"]["appId"]); ?>', // 必填，公众号的唯一标识
    timestamp:'<?php echo ($data["jssdk"]["timestamp"]); ?>' , // 必填，生成签名的时间戳
    nonceStr: '<?php echo ($data["jssdk"]["nonceStr"]); ?>', // 必填，生成签名的随机串
    signature: '<?php echo ($data["jssdk"]["signature"]); ?>',// 必填，签名，见附录1
    jsApiList: [
				'onMenuShareTimeline',
				'onMenuShareQQ',
				'onMenuShareWeibo',
				'onMenuShareQZone',
                ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
}); 
 wx.checkJsApi({
	    jsApiList: ['onMenuShareTimeline'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
	    success: function(res) {
	    	//cosnole.log(res);
	        
	    }
	}); */
 
</script>
<header>
  <nav id="nav">
    <ul>
      <li><a href="<?php echo U('/Index/Index');?>" >网站首页</a></li>
      <?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li> 
      		<a href="<?php echo U('Home/Category/index',array('id'=>$v['cate_id']));?>"  title="<?php echo ($v["english"]); ?>"><?php echo ($v["name"]); ?></a>     		
      		<dl>
      		 <!-- 
      		  <dd>dssdjkds<dd>
      		  <dd>dssdjkds<dd>
      		  <dd>dssdjkds<dd> -->
      		</dl>
      	</li><?php endforeach; endif; else: echo "" ;endif; ?> 
        <li><a href="/discuz.php/Index" >论坛</a></li>
        <li><a href="<?php echo U('Index/resume');?>" >个人简历</a></li>
        <li><a href="#" onclick='face_age(this)'>人脸年龄识别</a></li>    
        <li><a href="#" onclick='see_visit(this)' ii=0 >查看隐私</a></li>
        <input type='file' name='face' id='face' style='display:none;'></input>
    </ul>   
    <script src="/Public/js/silder.js"></script><!--获取当前页导航 高亮显示标题--> 
    <script src="/Public/js/ajaxfileupload.js"></script>
    <script type='text/javascript'>
    	function see_visit(obj){
    		var state=$(obj).attr('ii');   		
    		if(state==0){
    			var name=prompt('Who is she?');
        		if(name ==null || name ==" " || name == undefined || name !='家裕'){
        			return false;
        		}        		
    			state=$(obj).attr('ii',1);
    			$('#erweima').show();
    		}else{
    			state=$(obj).attr('ii',0);
    			$('#erweima').hide();	
    		}
    		
    	}
    
    	function face_age(obj){
    		$('#face').click();
    	}
    	$(document).on("change","#face",function(){
    		$.ajaxFileUpload
            (
                {
                    url: "<?php echo U('Home/Index/face_age');?>", //用于文件上传的服务器端请求地址
                    secureuri: false, //是否需要安全协议，一般设置为false
                    fileElementId: 'face', //文件上传域的ID
                    dataType: 'json', //返回值类型 一般设置为json
                    success: function (e, status)  //服务器成功响应处理函数
                    {	
                       if(e.code==200){
                    	   if(e.data.errno==0){
                    		   if(e.data.age[0] > 0){
                    			   alert('系统目测你'+e.data.age[0]+'岁');
                    			   return true;
                    		   }
                    		   alert('没检测到头像 上传一张清晰点的吧');
                           }else{
                        	   alert('你的照片太模糊或者没检测到头像 上传一张清晰点的吧'); 
                           }
                       }else{
                    	   alert(e.msg);
                       }
                       return true;
                    },
                    error: function (data, status, e)//服务器响应失败处理函数
                    {
                        alert(e);
                    }
                }
            )
            return false;
    		
    	}) ;
    	
    </script>
  	<style>
		#u_i ol{float:right;}
    	#u_i li{float:left;margin:5px 5px;}
		#erweima{
			position:absolute;
			z-index:1000;
			margin-left: 40%;
    		margin-top: 5%;
			display:none;
		}
		
    </style>
    <?php if(empty($_SESSION['userInfo'])):?>
  	<div id='u_i'>
  	  <ol>
  	  <li id='login' ><a href='javascript:;'>登录</a></li>
       <li id='registered' ><a href='javascript:;'>注册</a></li> 
       <li ><a href="https://chenhuojia.xyz/public/weixin.php/index/weixin/login">微信登录</a></li>
       <li><a href="#" onclick="toLogins()">QQ登录</a></li>
       <p style="clear:both;"></p>
  	  </ol>
  	</div> 
  	<?php endif;?>
  </nav>
</header>

<!--header end-->
<div id="mainbody">
<div id='erweima'>
	 <img src="http://qr.topscan.com/api.php?bg=f3f3f3&fg=ff0000&gc=222222&el=l&w=200&m=10&text=<?php echo ($private_url); ?>"/>
</div>
  <div class="info">
  		 <?php if(is_array($banner)): $i = 0; $__LIST__ = $banner;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><figure> 
  			<img src="<?php echo ($v["image_url"]); ?>"  alt="Panama Hat">
    			<figcaption><strong><?php echo ($v["title"]); ?></strong><?php echo ($v["introduce"]); ?></figcaption>
  		  	</figure><?php endforeach; endif; else: echo "" ;endif; ?>
	<?php if($_SESSION['userInfo']!= null): ?><div class="card">
	      <h1>我的名片</h1>
	      <p>网名：<?php echo ($_SESSION['userInfo']['english']); ?> | <?php echo ($_SESSION['userInfo']['name']); ?></p>
	      <p>职业：<?php echo ($_SESSION['userInfo']['profession']); ?></p>
	      <p>电话：<?php echo ($_SESSION['userInfo']['phone']); ?></p>	      
	      <p>Email：<?php echo ($_SESSION['userInfo']['email']); ?></p>
	      <?php if($_SESSION['userInfo']['is_admin']== 1): ?><p><a href="#">添加</a></p><?php endif; ?>
	      <ul class="linkmore">
	        <li><a href="http://chenhuojia.xin/public/weixin.php/index/weixin/login" class="talk" title="给我留言"></a></li>
	        <li><a href="/" class="address" title="联系地址"></a></li>
	        <li><a href="/" class="email" title="给我写信"></a></li>
	        <li><a href="/" class="photos" title="生活照片"></a></li>
	        <li><a href="/" class="heart" title="关注我"></a></li>
	      </ul>
	    </div>
    <?php else: ?>
    	<div class="card">
	      <h1>我的名片</h1>
	      <p>网名：陈伙佳</p>
	      <p>职业：专注PHP开发</p>
	      <p>电话：13725474374</p>	      
	      <p>Email：1126089253@qq.com</p>
	      <ul class="linkmore">
	        <li><a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx8ea258c0833e4e0c&redirect_uri=http%3A%2F%2Fchenhuojia.xin%2Fpublic%2Fweixin.php%2FIndex%2FWeixin%2Fcallback&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect" class="talk" title="给我留言"></a></li>
	        <li><a href="/" class="address" title="联系地址"></a></li>
	        <li><a href="/" class="email" title="给我写信"></a></li>
	        <li><a href="/" class="photos" title="生活照片"></a></li>
	        <li><a href="/" class="heart" title="关注我"></a></li>
	      </ul>
	    </div>
    
    	<!-- <div class="card">
	      <h1 id='login'  style="cursor:pointer"><a href='javascript:;'>登录</a></h1>
	      <h1 id='registered' style="cursor:pointer"><a href='javascript:;'>注册</a></h1> 
	      <h1 style="cursor:pointer"><a href="http://chenhuojia.xin/public/weixin.php/index/weixin/login">微信登录</a></h1>
	          
  		  <h1 style="cursor:pointer"><a href="#" onclick="toLogins()">QQ登录</a></h1>
  		</div> --><?php endif; ?>
    <style>
    	#logutfrom{
    		display:none;
			position: absolute;
    		height:0;
    		margin:50px 275px;
    		z-index:20;
    		background-color:red;
    	}
    	#logutfrom form{
			background-color:#fff;
    	}
    </style>
    
  <script>

  
  	$(function(){
  	 
  		$('#login').click(function(){
  			
  			window.location.href="<?php echo U('Home/Login/login');?>"; 
  	  		/* var name=prompt("请输入您的名字","");  	  		
  	  		if(name == "" || name == undefined || name == null){
  	  			alert('用户名为空');
  	  			return false;
  	  			}
  	  		var password=prompt("请输入您的密码","");
  	  		if(password == "" || password == undefined || password == null){
  	  			alert('密码为空');
	  				return false;
	  			}
  	  	  	$.ajax({
  	  	  		url:"<?php echo U('Home/Login/ajaxlogin');?>",
  	  	  		type:'post',
  	  	  		data:'name='+name+'&password='+password,
  	  	  		datatype:'json',
  	  	  		success:function(e){
  	  	  			if(e==1){
  						window.location.reload(); 
  	  	  			}
  	  	  		}
  	  	  	}) */
  	  	})
  	})
  	
  	$(function(){
  		$('#registered').click(function(){
  			window.location.href="<?php echo U('Home/Login/register');?>"; 
  	  		/* var name=prompt("请输入您的名字",""); 	  		
  	  		if(name == "" || name == undefined || name == null){
  	  			alert('用户名为空');
  	  			return false;
  	  			}
  	  		var password=prompt("请输入您的密码","");
  	  		if(password == "" || password == undefined || password == null){
  	  			alert('密码为空');
	  				return false;
	  			}
  	  	  	$.ajax({
  	  	  		url:"<?php echo U('Home/Login/ajaxRegistered');?>",
  	  	  		type:'post',
  	  	  		data:'name='+name+'&password='+password,
  	  	  		datatype:'json',
  	  	  		success:function(e){
  	  	  			console.log(e);
  	  	  			if(e==1){
  						window.location.reload(); 
  	  	  			}
  	  	  		}
  	  	  	}) */
  	  	})	
  	})
  	
  	function approve(obj){
  		var _this=$(obj);
  		var art_id=_this.attr('id');
  		var val=_this.html(); 
  		   val= Number(val) +1;
 			$.ajax({
 				url:"<?php echo U('Home/Ajax/approve');?>",
 				type:'post',
 				data:'art_id='+art_id,
 				datatype:'json',
 				success:function(e){
 					if(e==0){
 						alert('点赞失败');
 					}if(e==1){
 						_this.html(val);
 					}if(e==2){
 						alert('数据不对');
 					}if(e==3){
 						alert('今天点赞已经上限了 请明天再点吧');
 					}if(e==4){
 						alert('你今天已点赞了, 请明天再点吧');
 					}
 				}
 			})
 	}
  	function getLocation()
  	  {
  	  if (navigator.geolocation)
  	    {
  	    navigator.geolocation.getCurrentPosition(showPosition);
  	    }
  	  else{console.log("Geolocation is not supported by this browser.");}
  	  }
  	function showPosition(position)
  	  {
  	  	console.log(position);
  	  }
  	getLocation();
  	
	 function toLogins()
 	  {
 	    //以下为按钮点击事件的逻辑。注意这里要重新打开窗口
 	    //否则后面跳转到QQ登录，授权页面时会直接缩小当前浏览器的窗口，而不是打开新窗口
 	    var A=window.open("oauth/index.php","TencentLogin", "width=450,height=320,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1");
 	  } 
  </script>
 	
 </div>
  <!--info end-->

  <div class="blank"></div>
  <div class="blogs">
    <?php if($data["ra"] != null): ?><ul class="bloglist">
	      	<?php if(is_array($data["ra"])): $i = 0; $__LIST__ = $data["ra"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
			        <div class="arrow_box">
			          <div class="ti"></div>
			          <!--三角形-->
			          <div class="ci"></div>
			          <!--圆形-->
			          <h2 class="title"><a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>" target="_blank"><?php echo ($v["title"]); ?></a></h2>
			          <ul class="textinfo">
			            <a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><img src="<?php echo ($v["image_url"]); ?>"></a>
			            <a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><p><?php echo ($v["introduction"]); ?></p></a>
			          </ul>
			          <ul class="details">
			            <li class="likes"><a href="javascript:" id="<?php echo ($v["art_id"]); ?>" onclick="approve(this)"><?php echo ($v["approve_total"]); ?></a></li>
			            <li class="comments"><a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><?php echo ($v["discuss_total"]); ?></a></li>
			            <li class="icon-time"><a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><?php echo ($v["addtime"]); ?></a></li>
			          </ul>
			        </div>
			        <!--arrow_box end--> 
	      		</li><?php endforeach; endif; else: echo "" ;endif; ?>
	    </ul><?php endif; ?>
    <!--bloglist end-->
    <aside>
      <div class="tuijian">
        <h2>推荐文章</h2>
        <?php if($data["ral"] != null): ?><ol>
	          	<?php if(is_array($data["ral"])): $k = 0; $__LIST__ = $data["ral"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><li><span><strong><?php echo ($k); ?></strong></span><a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	        </ol><?php endif; ?>  
      </div>
      <div class="toppic">
        <h2>图文并茂</h2>
        <ul>
          <li><a href="/"><img src="/Public/picture/k01.jpg">腐女不可怕，就怕腐女会画画！
            <p>伤不起</p>
            </a></li>
          <li><a href="/"><img src="/Public/picture/k02.jpg">问前任，你还爱我吗？无限戳中泪点~
            <p>感兴趣</p>
            </a></li>
          <li><a href="/"><img src="/Public/picture/k03.jpg">世上所谓幸福，就是一个笨蛋遇到一个傻瓜。
            <p>喜欢</p>
            </a></li>
        </ul>
      </div>
      <div class="clicks">
        <h2>热门点击</h2>
        <?php if($data["hot"] != null): ?><ol>
	            <?php if(is_array($data["hot"])): $k = 0; $__LIST__ = $data["hot"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><li><span><a href="<?php echo U('Home/Category/index',array('id'=>$v['cate_id']));?>"><?php echo ($v["cate_name"]); ?></a></span><a href="<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>"><?php echo ($v["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
	        </ol><?php endif; ?>
      </div>
      <div class="search">
        <form class="searchform" method="get" action="#">
          <input type="text" name="s" value="Search" onfocus="this.value=''" onblur="this.value='Search'">
        </form>
      </div>
      <div class="viny">
       <dl>
          <dt class="art"><img src="/Public/picture/artwork.png" alt="专辑"></dt>
          <dd class="icon-song"><span></span>南方姑娘</dd>
          <dd class="icon-artist"><span></span>歌手：赵雷</dd>
          <dd class="icon-album"><span></span>所属专辑：《赵小雷》</dd>
          <dd class="icon-like"><span></span><a href="/">喜欢</a></dd>
          <dd class="music">
            <audio src="//music.163.com/style/swf/widget.swf?sid=202373&type=2&auto=1&width=320&height=66" controls></audio>
          </dd>
          <!--也可以添加loop属性 音频加载到末尾时，会重新播放-->
        </dl>
      </div>
    </aside>
  </div>
  <!--blogs end--> 
</div>
<script>
</script>
<!--mainbody end-->
<footer>
  <div class="footer-mid">
    <div class="links">
      <h2>友情链接</h2>
      <ul>
        <li><a href="#">个人博客</a></li>
        <li><a href="https://segmentfault.com/">SegmentFault</a></li>
      </ul>
    </div>
    <div class="visitors">
      <h2>最新评论</h2>
      <?php if($discuss != null): if(is_array($discuss)): $i = 0; $__LIST__ = $discuss;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><dl>
		        <dt><img src="<?php echo ($v["avatar"]); ?>">
		        <dt>
		        <dd><?php echo ($v["name"]); ?>
		          <time><?php echo ($v["addtime"]); ?></time>
		        </dd>
		        <dd>在 <a href="#<?php echo ($v["art_id"]); ?>" class="title"><?php echo ($v["art_title"]); ?> </a>中评论：</dd>
		        <dd><?php echo ($v["content"]); ?></dd>
	      	</dl><?php endforeach; endif; else: echo "" ;endif; endif; ?>
    </div>
  </div>
  <div class="footer-bottom">
    <a href="http://www.miitbeian.gov.cn"><p>粤ICP备17003133号</p></a>
  </div>
</footer>
<!-- jQuery仿腾讯回顶部和建议 代码开始 -->
<div id="tbox"> 
	<a id="togbook" href="<?php echo U('Index/chat');?>"></a> 
	<a id="gotop" href="javascript:void(0)"></a> 
</div>
<!-- 代码结束 -->
</body>
</html>
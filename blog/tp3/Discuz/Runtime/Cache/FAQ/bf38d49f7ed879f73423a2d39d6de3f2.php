<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
        <!--[if lt IE 7]> <html class="lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
        <!--[if IE 7]>    <html class="lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
        <!--[if IE 8]>    <html class="lt-ie9" lang="en-US"> <![endif]-->
        <!--[if gt IE 8]><!--> <html lang="en-US"> <!--<![endif]-->
        <head>
                <!-- META TAGS -->
                <meta charset="UTF-8" />
                <meta name="viewport" content="width=device-width, initial-scale=1.0">

                <title>Knowledge Base Theme</title>

              <link rel="shortcut icon" type="image/x-icon" href="/Public/images/logo2.png" />


                

                <!-- Style Sheet-->
                <link rel="stylesheet" href="/Public/discuz/css/style.css"/>
                <link rel='stylesheet' id='bootstrap-css-css'  href='/Public/discuz/css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='responsive-css-css'  href='/Public/discuz/css/responsive5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='pretty-photo-css-css'  href='/Public/discuz/js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
                <link rel='stylesheet' id='main-css-css'  href='/Public/discuz/css/main5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='custom-css-css'  href='/Public/discuz/css/custom5152.html?ver=1.0' type='text/css' media='all' />
				
				<!-- script -->
                <script type='text/javascript' src='/Public/discuz/js/jquery-1.8.3.min.js'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='/Public/discuz/js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='/Public/discuz/js/jflickrfeed.js'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src='/Public/discuz/js/custom5152.js?ver=1.0'></script>

                <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
                <!--[if lt IE 9]>
                <script src="js/html5.js"></script></script>
                <![endif]-->
				<div id='link'></div>
				<style>
					#color{float:right;margin-top:-39px;width:150px;margin-right:-175px;color:#c1cad1;cursor:pointer;}
					#color dd{display:none;cursor:pointer;margin-left:1px;list-style:none;padding:10px 6px;}
				</style>
        </head>
        
               <body>
                <!-- Start of Header -->
                <div class="header-wrapper">
                        <header>
                                <div class="container">
                                        <div class="logo-container">
                                                <!-- Website Logo -->
                                                <a href="index.html"  title="Knowledge Base Theme">
                                                        <img src="/Public/images/logo2.png" alt="Mr.chen">
                                                </a>
                                        </div>
                                        <!-- Start of Main Navigation -->
                                        <nav class="main-nav">
                                                <div class="menu-top-menu-container">
                                                        <ul id="menu-top-menu" class="clearfix">
                                                                <li class="current-menu-item"><a href="<?php echo U('Index/index/index');?>">首页</a></li>
                                                                <li><a href="/index.php">博客</a></li>
                                                                <li><a href="#">留言版</a>
                                                                	<ul class="sub-menu">
                                                                                <li><a href="<?php echo U('FAQ/Index/index');?>">留言墙</a></li>
                                                                                <li><a href="javascript:;" onclick="add();">向我留言</a></li>     
                                                                        </ul>
                                                                </li>                                                                
                                                                <!-- <li><a href="#">More</a>
                                                                        <ul class="sub-menu">
                                                                                <li><a href="<?php echo U('More/Index/full');?>">Full Width</a></li>
                                                                                <li><a href="<?php echo U('More/Index/elements');?>">Elements</a></li>
                                                                                <li><a href="<?php echo U('More/Index/page');?>">Sample Page</a></li>
                                                                        </ul>
                                                                </li> -->
                                                                <li><a href="<?php echo U('Contact/Index/index');?>">发布话题</a></li>
                                                                 <li>
                                                                 <?php if($_SESSION['userInfo']!= null): ?>欢迎你！ <?php echo ($Think['session']['userInfo']['name']); ?><a href="javascript:logout();">退出</a>
                                                                   <?php else: ?>
                                                                   		<a href="#">user</a>
	                                                                   	<ul class="sub-menu">
                                                                               <li><a href="https://www.chenhuojia.xin/index.php/Login/login">登录</a></li>
                                                                               <li><a href="https://www.chenhuojia.xin/index.php/Login/register">注册</a></li>     
		                                                                </ul><?php endif; ?>
                                                                </li>                                                        		
                                                        </ul>
                                                         <dl id="color">背景颜色                                                         
                                                             <dd id='blue'>Blue Skin</dd>
                                                             <dd id='green'>Green Skin</dd>
                                                             <dd id='red'>Red Skin</dd>
                                                             <dd id='default'>Default Skin</dd>                         
                                                         </dl>
                                                </div>
                                        </nav>
                                        <!-- End of Main Navigation -->

                                </div>
                        </header>
                </div>
                <!-- End of Header -->
                   <script>
                   $('#color dd').css({background:'#3b4348'})
                   $('#color').hover(                		
               		   function(){
               			   $('#color dd').stop(true);
               			   $('#color dd').slideDown('slow');
               		   },
               		   function(){
               			   $('#color dd').stop(true);
               			   $('#color dd').slideUp('slow');
               		   	}
                	)
                	$('#color dd').hover(
              			function(){
                  			  $(this).css({background:'#292e32'})
                  		   },
                  		function(){
                  			 $(this).css({background:'#3b4348'})
                  		}
                	);	   
              		$('#blue').click(function(){
              			var link="<link rel='stylesheet' id='blue-skin-css'  href='/Public/discuz/css/blue-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#red').click(function(){
              			var link="<link rel='stylesheet' id='red-skin-css'  href='/Public/discuz/css/red-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#green').click(function(){
              			var link="  <link rel='stylesheet' id='green-skin-css'  href='/Public/discuz/css/green-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#default').click(function(){
              			var link="<link rel='stylesheet' id='default-skin-css'  href='/Public/discuz/css/default-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		
              		function add(){
              			 var content=prompt("Please enter your name","")
              			  if (content!=null && content!="")
              			    {
              			    	$.ajax({
              			    		url:"<?php echo U('FAQ/Index/add');?>",
              			    		type:'post',
              			    		data:'content='+content,
              			    		datatype:'json',
              			    		success:function(e){
              			    			if(e==1){
              			    				alert('留言成功');
              			    				location.reload();
              			    			}else{
              			    				alert(e);
              			    			}
              			    		},
              			    		error:function(){
              			    			alert('网络有误 请稍后再试');
              			    		}
              			    	})
              			    }
              		}
              		
            		function logout(){
            			$.ajax({
            				url:"https://www.chenhuojia.xin/index.php/Login/logout",
            				data:'',
            				type:"post",
            				datatype:'json',
            				success:function(e){
            					window.location.reload(); 
            					
            				}
            			})
            		}
              	</script>
 
    <link href="/Public/admin2/css/style.css?v=4.1.0" rel="stylesheet">
<body class="gray-bg">

    <div class="row">
        <div class="col-sm-12">
            <div class="wrapper wrapper-content animated fadeInUp">
                <ul class="notes">
                    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
	                        <div>
	                            <small><?php echo (date("Y年m月d日  下午g:i",$v["addtime"])); ?> (星期<?php echo ($v["week"]); ?>)</small>
	                            <h4><?php echo ($v["name"]); ?></h4>
	                            <p><?php echo ($v["content"]); ?></p>
	                            <!--<a href="pin_board.html#"><i class="fa fa-trash-o "></i></a>-->
	                        </div>
                    	</li><?php endforeach; endif; else: echo "" ;endif; ?>                
                </ul>
            </div>
        </div>
    </div>
</body>

</html>


                <!-- Start of Footer -->
                <footer id="footer-wrapper">
                        <div id="footer" class="container">
                                <div class="row">

                                        <div class="span3">
                                                <section class="widget">
                                                        <h3 class="title">How it works</h3>
                                                        <div class="textwidget">
                                                                <p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. </p>
                                                                <p>Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. </p>
                                                        </div>
                                                </section>
                                        </div>

                                        <div class="span3">
                                                <section class="widget"><h3 class="title">Categories</h3>
                                                        <ul>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet,">Advanced Techniques</a> </li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet,">Designing in WordPress</a></li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet,">Server &amp; Database</a></li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet, ">Theme Development</a></li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet,">Website Dev</a></li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet,">WordPress for Beginners</a></li>
                                                                <li><a href="#" title="Lorem ipsum dolor sit amet, ">WordPress Plugins</a></li>
                                                        </ul>
                                                </section>
                                        </div>

                                        <div class="span3">
                                                <section class="widget">
                                                        <h3 class="title">Latest Tweets</h3>
                                                        <div id="twitter_update_list">
                                                                <ul>
                                                                        <li>No Tweets loaded !</li>
                                                                </ul>
                                                        </div>
                                           
                                                </section>
                                        </div>

                                        <div class="span3">
                                                <section class="widget">
                                                        <h3 class="title">Flickr Photos</h3>
                                                        <div class="flickr-photos" id="basicuse">
                                                        </div>
                                                </section>
                                        </div>

                                </div>
                        </div>
                        <!-- end of #footer -->

                        <!-- Footer Bottom -->
                        <div id="footer-bottom-wrapper">
                                <div id="footer-bottom" class="container">
                                        <div class="row">
                                                <div class="span6">
                                                        <p class="copyright">
                                                                Copyright © 2016. Mr.chen
                                                        </p>
                                                </div>
                                                <div class="span6">
                                                        <!-- Social Navigation -->
                                                        <ul class="social-nav clearfix">
                                                                <li class="linkedin"><a target="_blank" href="#"></a></li>
                                                                <li class="stumble"><a target="_blank" href="#"></a></li>
                                                                <li class="google"><a target="_blank" href="#"></a></li>
                                                                <li class="deviantart"><a target="_blank" href="#"></a></li>
                                                                <li class="flickr"><a target="_blank" href="#"></a></li>
                                                                <li class="skype"><a target="_blank" href="skype:#?call"></a></li>
                                                                <li class="rss"><a target="_blank" href="#"></a></li>
                                                                <li class="twitter"><a target="_blank" href="#"></a></li>
                                                                <li class="facebook"><a target="_blank" href="#"></a></li>
                                                        </ul>
                                                </div>
                                        </div>
                                </div>
                        </div>
                        <!-- End of Footer Bottom -->

                </footer>
                <!-- End of Footer -->

                <a href="#top" id="scroll-top"></a>

                <!-- script -->
                <script type='text/javascript' src='/Public/discuz/js/jquery-1.8.3.min.js'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='/Public/discuz/js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='/Public/discuz/js/jflickrfeed.js'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='/Public/discuz/js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src='/Public/discuz/js/custom5152.js?ver=1.0'></script>

        </body>
</html>
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


<!-- Start of Search Wrapper -->
<div class="search-area-wrapper">
        <div class="search-area container">
                <h3 class="search-header">Have a Question?</h3>
                <p class="search-tag-line">If you have any question you can ask below or enter what you are looking for!</p>

                 <form id="search-form" class="search-form clearfix" method="get" action="<?php echo U('More/Index/full');?>" autocomplete="off">
                        <input class="search-term required" type="text" id="s" name="title" placeholder="Type your search terms here" title="* Please enter a search term!" />
                        <input class="search-btn" type="submit" value="Search" />
                        <div id="search-error-container"></div>
                </form>
        </div>
</div>
<!-- End of Search Wrapper -->
<div class="copyrights">Collect from <a href="http://www.cssmoban.com/" title="模板之家">模板之家</a></div>

<!-- Start of Page Container -->
<div class="page-container">
     <div class="container">
         <div class="row">
             <!-- start of page content -->
				<div class="span8 page-content">
			       <!-- Basic Home Page Template -->
			                   <div class="row separator">
			                    	<?php if(is_array($data["tag"])): $i = 0; $__LIST__ = $data["tag"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><section class="span4 articles-list">
			                                    <h3><a href="<?php echo U('Category/Index/index',array('group_id'=>$v['group_id']));?>"><?php echo ($v["name"]); ?></a></h3>
			                                    <?php if($v['child'] != null): ?><ul class="articles">
				                                            	<?php if(is_array($v["child"])): $i = 0; $__LIST__ = $v["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li class="article-entry standard">
				                                                    	<h4><a href="<?php echo U('Category/CategoryChild/index',array('group_id'=>$vv['group_id']));?>"><?php echo ($vv["name"]); ?></a></h4>
				                                            			<span class="article-meta"><?php echo (date("d F , Y",$vv["addtime"])); ?></span>
	                                                                    <span class="like-count"><?php echo ($vv["post_num"]); ?>post</span>
				                                            		</li><?php endforeach; endif; else: echo "" ;endif; ?> 
				                                     </ul><?php endif; ?> 
			                             </section><?php endforeach; endif; else: echo "" ;endif; ?>
			                   </div>
			           </div>
            <!-- end of page content -->

			<!-- start of sidebar -->
			<aside class="span4 page-sidebar">
			
			        <section class="widget">
			                <div class="support-widget">
			                        <h3 class="title">Support</h3>
			                        <p class="intro">Need more support? If you did not found an answer, contact us for further help.</p>
			                </div>
			        </section>
			
			        <section class="widget">
			                <div class="quick-links-widget">
			                        <h3 class="title">Quick Links</h3>
			                        <ul id="menu-quick-links" class="menu clearfix">
			                                <li><a href="index-2.html">Home</a></li>
			                                <li><a href="articles-list.html">Articles List</a></li>
			                                <li><a href="faq.html">FAQs</a></li>
			                                <li><a href="contact.html">Contact</a></li>
			                        </ul>
			                </div>
			        </section>
			
			        <section class="widget">
			                <h3 class="title">Tags</h3>
			                <div class="tagcloud">
			                		<?php if(is_array($data["tags"])): $i = 0; $__LIST__ = $data["tags"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Category/CategoryChild/index',array('group_id'=>$v['group_id']));?>" class="btn btn-mini"><?php echo ($v["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
			                </div>
			        </section>
			
			</aside>
			<!-- end of sidebar -->
                </div>
        </div>
</div>
<!-- End of Page Container -->


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
                                                        <script src="http://twitterjs.googlecode.com/svn/trunk/src/twitter.min.js" type="text/javascript"></script>
                                                        <script type="text/javascript" >
                                                                getTwitters("twitter_update_list", {
                                                                        id: "960development",
                                                                        count: 3,
                                                                        enableLinks: true,
                                                                        ignoreReplies: true,
                                                                        clearContents: true,
                                                                        template: "%text% <span>%time%</span>"
                                                                });
                                                        </script>
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
                                                                Copyright © 2013. All Rights Reserved by KnowledgeBase.Collect from <a href="http://www.cssmoban.com/" title="网页模板" target="_blank">网页模板</a>
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

                

        </body>
</html>
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

             <link rel="shortcut icon" type="image/x-icon" href="/dsd/tp3/Public/images/logo2.png" />


                

                <!-- Style Sheet-->
                <link rel="stylesheet" href="/dsd/tp3/Public/discuz/css/style.css"/>
                <link rel='stylesheet' id='bootstrap-css-css'  href='/dsd/tp3/Public/discuz/css/bootstrap5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='responsive-css-css'  href='/dsd/tp3/Public/discuz/css/responsive5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='pretty-photo-css-css'  href='/dsd/tp3/Public/discuz/js/prettyphoto/prettyPhotoaeb9.css?ver=3.1.4' type='text/css' media='all' />
                <link rel='stylesheet' id='main-css-css'  href='/dsd/tp3/Public/discuz/css/main5152.css?ver=1.0' type='text/css' media='all' />
                <link rel='stylesheet' id='custom-css-css'  href='/dsd/tp3/Public/discuz/css/custom5152.html?ver=1.0' type='text/css' media='all' />
				
				<!-- script -->
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery-1.8.3.min.js'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jflickrfeed.js'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/custom5152.js?ver=1.0'></script>

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
                                                        <img src="/dsd/tp3/Public/images/logo2.png" alt="Mr.chen">
                                                </a>
                                        </div>
                                        <!-- Start of Main Navigation -->
                                        <nav class="main-nav">
                                                <div class="menu-top-menu-container">
                                                        <ul id="menu-top-menu" class="clearfix">
                                                                <li class="current-menu-item"><a href="<?php echo U('Index/index/index');?>">首页</a></li>
                                                                <li><a href="/index.php">博客</a></li>
                                                                <li><a href="<?php echo U('FAQ/Index/index');?>">向我留言</a></li>                                                                
                                                                <!-- <li><a href="#">More</a>
                                                                        <ul class="sub-menu">
                                                                                <li><a href="<?php echo U('More/Index/full');?>">Full Width</a></li>
                                                                                <li><a href="<?php echo U('More/Index/elements');?>">Elements</a></li>
                                                                                <li><a href="<?php echo U('More/Index/page');?>">Sample Page</a></li>
                                                                        </ul>
                                                                </li> -->
                                                                <li><a href="<?php echo U('Contact/Index/index');?>">发布话题</a></li>                                                        		
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
              			var link="<link rel='stylesheet' id='blue-skin-css'  href='/dsd/tp3/Public/discuz/css/blue-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#red').click(function(){
              			var link="<link rel='stylesheet' id='red-skin-css'  href='/dsd/tp3/Public/discuz/css/red-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#green').click(function(){
              			var link="  <link rel='stylesheet' id='green-skin-css'  href='/dsd/tp3/Public/discuz/css/green-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
              		$('#default').click(function(){
              			var link="<link rel='stylesheet' id='default-skin-css'  href='/dsd/tp3/Public/discuz/css/default-skin5152.css?ver=1.0' type='text/css' media='all' />";
              			$('#link').html(link);
              		})
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

<!-- Start of Page Container -->
<div class="page-container">
    <div class="container">
       <div class="row">
			<!-- start of page content -->
            <div class="span8 main-listing">
					<article class="format-standard type-post hentry clearfix">
						<?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><h3 class="post-title">
		                			<a href="<?php echo U('Detial/Index/index',array('post_id'=>$v['id']));?>"><?php echo ($v["title"]); ?></a>
		        				</h3>
			        			<div class="post-meta clearfix">
			        				<span class="name"><a href="<?php echo U('More/Index/full',array('user_id'=>$v['user_id']));?>" title="View all posts in Server &amp; Database"><?php echo ($v["user_name"]); ?></a></span>
			                		<span class="date"><?php echo (date("d F , Y  H:i:s ",$v['addtime'])); ?></span>
					                <span class="category"><a href="<?php echo U('Category/Index/index',array('group_id'=>$v['topic_id']));?>" title="View all posts in Server &amp; Database"><?php echo ($v["name"]); ?></a></span>
					                <span class="comments"><a href="<?php echo U('Detial/Index/index',array('post_id'=>$v['id']));?>" title="Comment on Integrating WordPress with Your Website"><?php echo ($v["discuss_num"]); ?> Comments</a></span>
					                <span class="like-count"><?php echo ($v["like_num"]); ?></span>
			        			</div><!-- end of post meta -->
	                        <p><?php echo ($v["introduction"]); ?><a class="readmore-link" href="<?php echo U('Detial/Index/index',array('post_id'=>$v['id']));?>">Read more</a></p><?php endforeach; endif; else: echo "" ;endif; ?>
                     </article>                                    
                       <div id="pagination">
                            <div class="green-black"><?php echo ($data["page"]); ?></div>	
                       </div>

            </div>
<!-- end of page content -->
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

                <!-- script -->
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery-1.8.3.min.js'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.easing.1.34e44.js?ver=1.3'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/prettyphoto/jquery.prettyPhotoaeb9.js?ver=3.1.4'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.liveSearchd5f7.js?ver=2.0'></script>
				<script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jflickrfeed.js'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.formd471.js?ver=3.18'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/jquery.validate.minfc6b.js?ver=1.10.0'></script>
                <script type='text/javascript' src='/dsd/tp3/Public/discuz/js/custom5152.js?ver=1.0'></script>

        </body>
</html>
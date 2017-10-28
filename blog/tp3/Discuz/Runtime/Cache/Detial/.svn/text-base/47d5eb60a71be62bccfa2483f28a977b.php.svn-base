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

                <form id="search-form" class="search-form clearfix" method="get" action="#" autocomplete="off">
                        <input class="search-term required" type="text" id="s" name="s" placeholder="Type your search terms here" title="* Please enter a search term!" />
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
                        <div class="span8 page-content">

                                <ul class="breadcrumb">
                                        <li><a href="#">Knowledge Base Theme</a><span class="divider">/</span></li>
                                        <li><a href="#" title="View all posts in <?php echo ($data["data"]["name"]); ?>"><?php echo ($data["data"]["name"]); ?></a> <span class="divider">/</span></li>
                                        <li class="active">Integrating WordPress with Your Website</li>
                                </ul>

                                <article class=" type-post format-standard hentry clearfix">

                                        <h1 class="post-title"><a href="#"><?php echo ($data["data"]["title"]); ?></a></h1>

                                        <div class="post-meta clearfix">
                                                <span class="date"><?php echo (date("Y-m-d  H:i:s",$data["data"]["addtime"])); ?></span>
                                                <span class="category"><a href="<?php echo U('Category/Index/index',array('group_id'=> $data['data']['topic_id']));?>" title="View all posts in <?php echo ($data["data"]["name"]); ?>"><?php echo ($data["data"]["name"]); ?></a></span>
                                                <span class="comments"><a href="#" title="Comment on Integrating WordPress with Your Website"><?php echo ($data["data"]["discuss_num"]); ?> Comments</a></span>
                                                <span class="like-count"><?php echo ($data["data"]["like_num"]); ?></span>
                                        </div><!-- end of post meta -->

                                        <p><?php echo ($data["data"]["introduction"]); ?></p>
                                        
                                        <h3>The Benefits</h3>

                                        <p><?php echo (htmlspecialchars_decode($data["data"]["content"])); ?></p>

                                </article>

                                <div class="like-btn">

                                        <form id="like-it-form" action="#" method="post">
                                                <span class="like-it "><?php echo ($data["data"]["like_num"]); ?></span>
                                                <input type="hidden" name="post_id" value="99">
                                                <input type="hidden" name="action" value="like_it">
                                        </form>

                                        <span class="tags">
                                                <strong>Tags:&nbsp;&nbsp;</strong>
                                                <?php if(is_array($data["tags"])): $i = 0; $__LIST__ = $data["tags"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Category/CategoryChild/index',array('group_id'=>$v['group_id']));?>" rel="tag"><?php echo ($v["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>                                                                
                                        </span>

                                </div>

                                <section id="comments">
                                        <h3 id="comments-title">(<?php echo ($data["data"]["discuss_num"]); ?>) Comments</h3>

                                        <ol class="commentlist">
                                              <?php if(is_array($data["discuss"])): $i = 0; $__LIST__ = $data["discuss"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="comment even thread-even depth-1" id="li-comment-2">
                                                        <article id="comment-2">
                                                                <a href="#">
                                                                   <img alt="" src="<?php echo ($v["avatar"]); ?>" class="avatar avatar-60 photo" height="60" width="60">
                                                                </a>
                                                                <div class="comment-meta">
                                                                        <h5 class="author">
                                                                                <cite class="fn">
                                                                                        <a href="#" rel="external nofollow" class="url"><?php echo ($v["name"]); ?></a>
                                                                                </cite>
                                                                                - <a class="comment-reply-link" href="javascript:;" onclick="ajaxAddDiscuss(this)" discuss_id="<?php echo ($v["id"]); ?>" type='2'>Reply</a>
                                                                        </h5>
                                                                        <p class="date">
                                                                                <a href="#">
                                                                                        <time ><?php echo ($v["addtime"]); ?></time>
                                                                                </a>
                                                                        </p>
                                                                </div><!-- end .comment-meta -->
                                                                <div class="comment-body">
                                                                        <p><?php echo ($v["content"]); ?></p>                                                                       
                                                                </div><!-- end of comment-body -->
                                                        </article><!-- end of comment -->
                                                        <?php if($v['child'] != null): ?><ul class="children">
                                                                <?php if(is_array($v['child'])): $i = 0; $__LIST__ = $v['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li class="comment byuser comment-author-saqib-sarwar bypostauthor odd alt depth-2" id="li-comment-3">
                                                                        <article id="comment-3">
                                                                                <a href="#">
                                                                                        <img alt="" src="<?php echo ($vv["avatar"]); ?>" class="avatar avatar-60 photo" height="60" width="60">
                                                                                </a>
                                                                                <div class="comment-meta">
                                                                                        <h5 class="author">
                                                                                        		<?php if($vv["name"] == $vv.reply.name ): ?><cite class="fn" ><?php echo ($vv["name"]); ?> </cite>
                                                                                        		<?php else: ?>
                                                                                        			<cite class="fn" ><?php echo ($vv["name"]); ?> 回复   <?php echo ($vv["reply"]["name"]); ?></cite><?php endif; ?>
                                                                                                
                                                                                                - <a class="comment-reply-link" href="javascript:;" onclick="ajaxAddDiscuss(this)" discuss_id="<?php echo ($vv["id"]); ?>" type='3'>Reply</a>
                                                                                        </h5>
                                                                                        <p class="date">
                                                                                                <a href="#"> 
                                                                                                	<time ><?php echo ($vv["addtime"]); ?></time>
                                                                                                </a>
                                                                                        </p>
                                                                                </div><!-- end .comment-meta -->
                                                                                <div class="comment-body">
                                                                                        <p><?php echo ($vv["content"]); ?></p>
                                                                                </div><!-- end of comment-body -->
                                                                        </article><!-- end of comment -->
                                                                	</li><?php endforeach; endif; else: echo "" ;endif; ?>
                                                        	</ul><?php endif; ?>
                                                </li><?php endforeach; endif; else: echo "" ;endif; ?>         
                                        </ol>

                                        <div id="respond">
                                                <h3>Add Comment</h3>
                                                <div class="cancel-comment-reply">
                                                        <a rel="nofollow" id="cancel-comment-reply-link" href="#" style="display:none;">Click here to cancel reply.</a>
                                                </div>
                                                <form action="#" method="post" id="commentform">                                                      
                                                        <div>
                                                              <label for="comment">Comment</label>
                                                              <textarea class="span8" name="comment" id="comment" cols="58" rows="10"></textarea>
                                                        </div>                                                     
                                                        <div>
                                                                <input class="btn" name="submit" type="button" id="submit"  value="Submit Comment">
                                                        </div>
                                                </form>

                                        </div>

                                </section><!-- end of comments -->
	
							<script>
								$('#submit').on('click',function(){
									var content= $('#comment').val();
									if(content !=" " || content !=null || content !=undefined || content !=""){
										if(content.length>0){
											$.ajax({
												url:"<?php echo U('Detial/Ajax/addDiscuss');?>",
												type:'post',
												data:'content='+content+"&post_id="+<?php echo ($data["data"]["id"]); ?>,
												datatype:'json',
												success:function(e){
													if(e==3){
														if(window.confirm('请登陆')){
															self.location="/index.php/Login/login"; 
														}else{
															return false;
														}
													}
													var	html=addDiscuss(e.data.id,e.data.avatar,e.data.name,e.data.addtime,e.data.content);
													$('.commentlist').prepend(html);	
													$('#comment').val(" ");
												}
											})
										}else{
											alert('请输入内容');
											return false;
										}
									}
								})
								
								function ajaxAddDiscuss(obj){
									var content= prompt("请填写评论");
									var _this=$(obj);
									var discuss_id=_this.attr('discuss_id');
									var type=_this.attr('type');
									if(content !="" || content !=null || content !=undefined){
										if(content.length>0){
											$.ajax({
												url:"<?php echo U('Detial/Ajax/addDiscuss');?>",
												type:'post',
												data:'content='+content+"&discuss_id="+discuss_id+"&post_id="+<?php echo ($data["data"]["id"]); ?>,
												datatype:'json',
												success:function(e){
													console.log(e.data.name,e.data.replay_name);
													var	html=addlastdisuss(e.data.id,e.data.avatar,e.data.name,e.data.addtime,e.data.content,e.data.replay_name);
													if(e.state==1 && type==2){
														_this.parent().parent().parent().parent().find('.children').prepend(html);
													}if(e.state==1 && type==3){
														_this.parent().parent().parent().parent().parent().prepend(html);
													}if(e==3){
														alert('请先登陆');
														return false;
													}if(e==2){
														alert('数据有误');
														return false;
													}if(e==0){
														alert('网络错误');
														return false;
													}	
													
												}
											})
										}
									} 
								}
								
								//一级
								function addDiscuss(id,avatar,name,addtime,content){
									html ="<li class=\"comment even thread-even depth-1\" id=\"li-comment-2\">";
									html +="<article id=\"comment-2\">";
                                    html +="<a href=\"#\">";
                                    html +="<img alt=\"\" src="+avatar+" class=\"avatar avatar-60 photo\" height=\"60\" width=\"60\">";
                                    html +="</a>";
                                    html +="<div class=\"comment-meta\">";
                                    html +="<h5 class=\"author\">"; 
                                    html +="<cite class=\"fn\">";
                                    html +="<a href=\"#\" rel=\"external nofollow\" class=\"url\">"+name+"</a>";
                                    html +="</cite>";        
                                    html +="- <a class=\"comment-reply-link\" href=\"javascript:;\" onclick=\"ajaxAddDiscuss(this)\" discuss_id="+id+" type='2'>Reply</a></h5>";        
                                    html +="<p class=\"date\">";                
                                    html +="<a href=\"#\"><time >"+addtime+"</time></a></p>";                        
                                    html +="</div><!-- end .comment-meta -->";                                
                                    html +="<div class=\"comment-body\">";                        
                                    html +="<p>"+content+"</p>";                        
                                    html +="</div><!-- end of comment-body -->";
                                    html +="</article><!-- end of comment -->";
                                    html +="<ul class=\"children\"></ul></li>";                                   
                                    return html;
								}
								
								//二级
								
								function addlastdisuss(id,avatar,name,addtime,content,replay_name,type=3){
								   html  ="<li class=\"comment byuser comment-author-saqib-sarwar bypostauthor odd alt depth-2\" id=\"li-comment-3\">";    	
                                   html +=" <article id=\"comment-3\">";
                                   html +="<a href=\"#\">";         
                                   html +="<img alt=\"\" src="+avatar+" class=\"avatar avatar-60 photo\" height=\"60\" width=\"60\"></a>";                 
                                   html +="<div class=\"comment-meta\">";
                                   html +="<h5 class=\"author\">";                                   
                                   html +="<cite class=\"fn\">";
								   if(name == replay_name){
									   html +=' '+name; 
                                   }else{
                                	   html +=' '+name+"回复  "+replay_name;  
                                   }                                  
                                   html +="</cite>";        
                                   html +="- <a class=\"comment-reply-link\" href=\"javascript:;\" onclick=\"ajaxAddDiscuss(this)\" discuss_id="+id+" type="+type+">Reply</a></h5>";        
                                   html +="<p class=\"date\">";
                                   html +="<a href=\"#\"><time >"+addtime+"</time></a></p>";                        
                                   html +="</div><!-- end .comment-meta -->";                                
                                   html +="<div class=\"comment-body\">";                        
                                   html +="<p>"+content+"</p>";                        
                                   html +="</div><!-- end of comment-body -->";
                                   html +="</article><!-- end of comment --></li>";
                            	
                                   return html;
								}
								
							</script>
	
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
                                        <h3 class="title">Featured Articles</h3>
                                        <ul class="articles">
                                                <li class="article-entry standard">
                                                        <h4><a href="single.html">Integrating WordPress with Your Website</a></h4>
                                                        <span class="article-meta">25 Feb, 2013 in <a href="#" title="View all posts in Server &amp; Database">Server &amp; Database</a></span>
                                                        <span class="like-count">66</span>
                                                </li>
                                                <li class="article-entry standard">
                                                        <h4><a href="single.html">WordPress Site Maintenance</a></h4>
                                                        <span class="article-meta">24 Feb, 2013 in <a href="#" title="View all posts in Website Dev">Website Dev</a></span>
                                                        <span class="like-count">15</span>
                                                </li>
                                                <li class="article-entry video">
                                                        <h4><a href="single.html">Meta Tags in WordPress</a></h4>
                                                        <span class="article-meta">23 Feb, 2013 in <a href="#" title="View all posts in Website Dev">Website Dev</a></span>
                                                        <span class="like-count">8</span>
                                                </li>
                                                <li class="article-entry image">
                                                        <h4><a href="single.html">WordPress in Your Language</a></h4>
                                                        <span class="article-meta">22 Feb, 2013 in <a href="#" title="View all posts in Advanced Techniques">Advanced Techniques</a></span>
                                                        <span class="like-count">6</span>
                                                </li>
                                        </ul>
                                </section>



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

                                <section class="widget">
                                        <h3 class="title">Recent Comments</h3>
                                        <ul id="recentcomments">
                                                <li class="recentcomments"><a href="#" rel="external nofollow" class="url">John Doe</a> on <a href="#">Integrating WordPress with Your Website</a></li>
                                                <li class="recentcomments">saqib sarwar on <a href="#">Integrating WordPress with Your Website</a></li>
                                                <li class="recentcomments"><a href="#" rel="external nofollow" class="url">John Doe</a> on <a href="#">Integrating WordPress with Your Website</a></li>
                                                <li class="recentcomments"><a href="#" rel="external nofollow" class="url">Mr WordPress</a> on <a href="#">Installing WordPress</a></li>
                                        </ul>
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
<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Mr.Chen</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Free HTML5 Template by FREEHTML5" />
	<meta name="keywords" content="free html5, free template, free bootstrap, html5, css3, mobile first, responsive" />

  	<!-- Facebook and Twitter integration -->
	<meta property="og:title" content=""/>
	<meta property="og:image" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:site_name" content=""/>
	<meta property="og:description" content=""/>
	<meta name="twitter:title" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:url" content="" />
	<meta name="twitter:card" content="" />

	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" type="image/x-icon" href="/Public/images/logo2.png" />
	<!-- Google Fonts -->
	<link href='http://fonts.googleapis.com/css?family=Playfair+Display:400,700,400italic|Roboto:400,300,700' rel='stylesheet' type='text/css'>
	<!-- Animate -->
	<link rel="stylesheet" href="/Public/css/animate.css">
	<!-- Icomoon -->
	<link rel="stylesheet" href="/Public/css/icomoon.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="/Public/css/bootstrap.css">

	<link rel="stylesheet" href="/Public/css/styles.css">


	<!-- Modernizr JS -->
	<script src="/Public/js/modernizr-2.6.2.min.js"></script>
	<!-- FOR IE9 below -->
	<!--[if lt IE 9]>
	<script src="js/respond.min.js"></script>
	<![endif]-->
		<style>
	
		.head-pic{
		    width:40px;
		    height:40px;    
		}
		ul li{list-style-type:none;}
		.cm{
		    position:relative;
		    top:0px;
		    left:40px;
		    top:-40px;
		    width:600px;
		}
		
		.cm-header{
		    padding-left:5px;
		}
		
		.cm-content{
		    padding-left:5px;
		}
		
		.cm-footer{
		    padding-bottom:15px;
		    text-align:right;
		    border-bottom: 1px dotted #CCC;
		}
		
		.comment-reply{
		    text-decoration:none;
		    color:gray;
		    font-size: 14px;
		}
		.children{
			padding-left:50px;
		}
		.div-txt-submit{
			float:right;margin-right:265px;
		}
	</style>
		<script>
			function discuss(obj){
				var _this=$(obj);
				var content=0;
				var discuss_id=_this.attr('parent_id');
				var art_id=<?php echo ($data["detial"]["art_id"]); ?>;
				var total=$('.comment-num').find('span').html();
				var newtotal=Number(total)+1;
				var type=_this.attr('type');
				if(discuss_id>0){
					content=prompt("请输入内容","");
				}
				if(discuss_id==0){
					content=$('.txt-commit').val();
				}if(discuss_id<0){
					alert('非法操作!');
					return false;
				}
				
				if(content == "" || content == undefined || content == null){
						alert('请输入内容!');
						return false;
				}else{					
					$.ajax({
						url:"<?php echo U('/Ajax/discuss');?>",
						type:'post',
						data:'content='+content+'&discuss_id='+discuss_id+'&art_id='+art_id,
						datatype:'json',
						success:function(e){
							console.log(e)
							if(e==0){
		 						alert('点赞失败');
		 					}if(e.state==1 && type==3){		 					
		 						var html=getHtml(e.data.id,e.data.avatar,e.data.name,e.data.addtime,e.data.content);
		 						 _this.parent().parent().parent().parent().parent().prepend(html);
		 					}if(e.state==1 && type==2){
		 						var html=getHtml(e.data.id,e.data.avatar,e.data.name,e.data.addtime,e.data.content,2);
		 						 _this.parent().parent().parent().parent().find('.children').prepend(html);
		 					}if(e.state==1 && type==1){
		 						$('.comment-num').find('span').html(newtotal);
		 						$('.txt-commit').val(" ");
		 						var html=getHtml(e.data.id,e.data.avatar,e.data.name,e.data.addtime,e.data.content,1);
		 						 $('.comment-ul').prepend(html);
		 					}
		 					if(e==2){
		 						alert('请先登录');
		 					}
						},
						error:function(){
							alert('网络有误  请稍后再玩');
						}
					}) 
				}
			}
		
		//添加评论
		function  getHtml(id,avatar,name=0,addtime,content,type=3){
			 html="<li comment_id="+id+">";                   
			 html+="<div>";
			 html+="<div>";
			 html+="<img class=\"head-pic\"  src="+avatar+" alt=\"\">";             
			 html+="</div>";       
			 html+="<div class=\"cm\">";       
			 html+="<div class=\"cm-header\">";           			
			 if(name){
				 html+="<span>"+name+"</span>";    
			 }else{
				 html+="<span>游客</span>";    
			 } 			
			 html+="  <span>"+addtime+"</span>"           
			 html+="</div>";          
			 html+="<div class=\"cm-content\">";           
			 html+="<p>"+content+"</p>";                           
			 html+="</div>";           
			 html+="<div class=\"cm-footer\">";
			 if(type==2){
				 html+="<a class=\"comment-reply\" type='3' parent_id="+id+"  href=\"javascript:;\" onclick=\"discuss(this);\">回复</a>";                
				 html+="</div></div></div></li>"; 
			 }
			 if(type==1){
				 html+="<a class=\"comment-reply\" type='2' parent_id="+id+"  href=\"javascript:;\" onclick=\"discuss(this);\">回复</a>";                
				 html+="</div></div></div><ul class=\"children\"></ul></li>"; 
			 }else{
				 html+="<a class=\"comment-reply\" type="+type+" parent_id="+id+"  href=\"javascript:;\" onclick=\"discuss(this);\">回复</a>";                
				 html+="</div></div></div></li>"; 
			 }           
			 return html;
		}
		
	  	function approve(obj){
	  		var _this=$(obj);
	  		var art_id=<?php echo ($data["detial"]["art_id"]); ?>;
	 			$.ajax({
	 				url:"<?php echo U('Home/Ajax/approve');?>",
	 				type:'post',
	 				data:'art_id='+art_id,
	 				datatype:'json',
	 				success:function(e){
	 					if(e==0){
	 						alert('点赞失败');
	 					}if(e==1){
	 						_this.find('img').attr('src','/Public/images/click2.jpg');
	 					}if(e==2){
	 						alert('数据不对');
	 					}if(e==3){
	 						alert('今天点赞已经上限了 请明天再点吧');
	 					}if(e==4){
	 						alert('你今天已点赞了 请明天再点吧');
	 					}

	 				}
	 			})
	 	}
	</script>
	</head>
	<body>
	<div id="fh5co-offcanvas">
		<a href="#" class="fh5co-close-offcanvas js-fh5co-close-offcanvas"><span><i class="icon-cross3"></i> <span>Close</span></span></a>
		<div class="fh5co-bio">
			<figure>
				<img src="<?php echo ($_SESSION['userInfo']['avatar']); ?>" alt="Free HTML5 Bootstrap Template" class="img-responsive">
			</figure>
			<h3 class="heading">About Me</h3>
			<h2><?php echo ($_SESSION['userInfo']['name']); ?></h2>
			<p></p>
		</div>

		<div class="fh5co-menu">
			<div class="fh5co-box">
				<h3 class="heading">Categories</h3>
				<ul>
					<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Home/Category/index',array('id'=>$v['cate_id']));?>"><?php echo ($v["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="fh5co-box">
				<h3 class="heading">Search</h3>
				<form action="#">
					<div class="form-group">
						<input type="text" class="form-control" placeholder="Type a keyword">
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- END #fh5co-offcanvas -->
	<header id="fh5co-header">
		
		<div class="container-fluid">

			<div class="row">
				<a href="#" class="js-fh5co-nav-toggle fh5co-nav-toggle"><i></i></a>
				<?php if($data["is_approve"] == 0): ?><ul class="fh5co-social">
						<li><a href="javascript:;" onclick="approve(this);"><img src="/Public/images/click.jpg" style='widtg:50px;height:50px;'></a></li>					
					</ul>
				<?php else: ?>
					<ul class="fh5co-social">
						<li><a href="javascript:;" onclick="approve(this);"><img src="/Public/images/click2.jpg" style='widtg:50px;height:50px;'></a></li>					
					</ul><?php endif; ?>
				<div class="col-lg-12 col-md-12 text-center">
					<h1 id="fh5co-logo"><a href="/index.php">首页</a></h1>
					
				</div>

			</div>
		
		</div>

	</header>
	<!-- END #fh5co-header -->
	<div class="container-fluid">	
		<div class="row fh5co-post-entry single-entry">
			<article class="col-lg-8 col-lg-offset-2 col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
				<figure class="animate-box">
					<img src="<?php echo ($data["img"]["image_url"]); ?>" alt="Image" class="img-responsive">
				</figure>
				<h2 class="fh5co-article-title animate-box"><a href="single.html"><?php echo ($data["detial"]["title"]); ?></a></h2>
				<span class="fh5co-meta fh5co-date animate-box"><?php echo (date("Y-m-d H:i:s",$data["detial"]["addtime"])); ?></span>
				<div class="col-lg-12 col-lg-offset-0 col-md-12 col-md-offset-0 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0 text-left content-article">
					<div class="row">
						<div class="col-lg-8 cp-r animate-box">
							<p><?php echo ($data["detial"]["introduction"]); ?></p>
						</div>					
					</div>
					<div class="row rp-b">
						<div class="col-md-12 cp-l animate-box">
 							<p><?php echo (htmlspecialchars_decode($data["detial"]["content"])); ?></p>
						</div>
					</div>
					<div class="row">
						<div class="comment-filed">
							  <!--发表评论区begin-->
							  <div>
							    <div class="comment-num">
							        <span><?php echo ($data["detial"]["discuss_total"]); ?></span>条评论
							    </div>
							    <div>
							        <div>
							        <textarea class="txt-commit" replyid="0" style="margin: 0px; width: 904px; height: 131px;"></textarea>
							        </div>
							        <div class="div-txt-submit">
							            <a class="comment-submit" parent_id="0" type="1" href="javascript:;" onclick="discuss(this);"><span style=''>发表评论</span></a>
							        </div>      
							    </div>
							  </div>
							  <!--发表评论区end-->
							
							  <!--评论列表显示区begin-->
							    <!-- <?php echo ($commentlist); ?> -->
							    <div class="comment-filed-list" >
							        <div><span>全部评论</span></div>				
							        <div class="comment-list" >
							            <!--一级评论列表begin-->
							            <ul class="comment-ul">     
							                <?php if(is_array($data["discuss"])): $i = 0; $__LIST__ = $data["discuss"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?><li comment_id="<?php echo ($data["id"]); ?>">                   
							                    <div >
							                        <div>
							                            <img class="head-pic"  src="<?php echo ($data["avatar"]); ?>" alt="">  
							                        </div>
							                        <div class="cm">
							                            <div class="cm-header">
							                            <span><?php if($data['name'] != null): echo ($data["name"]); else: ?>游客<?php endif; ?></span>
							                            <span><?php echo ($data["addtime"]); ?></span>
							                            </div>
							                            <div class="cm-content">
							                                <p>
							                                    <?php echo ($data["content"]); ?>
							                                </p>
							                            </div>
							                            <div class="cm-footer">
							                                <a class="comment-reply" parent_id="<?php echo ($data['id']); ?>" type="2" href="javascript:" onclick="discuss(this)">回复</a>                     
							                            </div>  
							                        </div>                                                              
							                    </div>
							
							                    <!--二级评论begin-->
							                    <ul class="children">
							                       <?php if(is_array($data["child"])): $i = 0; $__LIST__ = $data["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($i % 2 );++$i;?><li comment_id="<?php echo ($child["id"]); ?>">                  
							                            <div >
							                                <div>
							                                    <img class="head-pic"  src="<?php echo ($child["avatar"]); ?>" alt=""> 
							                                </div>
							                                <div class="cm">
							                                    <div  class="cm-header">
							                                    <span><?php if($child['name'] != null): echo ($child["name"]); else: ?>游客<?php endif; ?>  回复  <?php if($child['reply'] != null): echo ($child["reply"]["name"]); else: ?>游客<?php endif; ?></span>
							                                    <span><?php echo ($child["addtime"]); ?></span>
							                                    </div>
							                                    <div class="cm-content">
							                                        <p>
							                                           <?php echo ($child["content"]); ?>
							                                        </p>
							                                    </div>
							                                    <div class="cm-footer">                             
							                                        <a class="comment-reply" replyswitch="off" type="3" parent_id="<?php echo ($child['parent_id']); ?>"  href="javascript:;" onclick="discuss(this);">回复</a>
							                                    </div>  
							                                </div>                                                              
							                            </div>							                            
							                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
							                    </ul>   
							                    <!--二级评论end-->
							
							                </li><?php endforeach; endif; else: echo "" ;endif; ?>                                                                     
							            </ul>
							            <!--一级评论列表end-->
							        </div>      
							    </div>
							  <!--评论列表显示区end-->							 
							</div>  
    					</div>
					</div>
				</div>
			</article>
		</div>
	</div>

	<footer id="fh5co-footer">
		<p><small>&copy; 2016. Mr.Chen </small></p>
	</footer>
	
	<!-- jQuery -->
	<script src="/Public/js/jquery.min.js"></script>
	<!-- jQuery Easing -->
	<script src="/Public/js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="/Public/js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="/Public/js/jquery.waypoints.min.js"></script>
	<!-- Main JS -->
	<script src="/Public/js/main.js"></script>

	</body>
</html>
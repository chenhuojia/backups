<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
	<head>
		<title>Home</title>
		<link href="/Public/css/stylee.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" type="image/x-icon" href="/Public/images/logo2.png" />
		<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
		</script>
		<link rel="stylesheet" type="text/css" href=" /Public/css/page.css" /> 
		<!----webfonts---->
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text/css'>
		<!----//webfonts---->
		<!-- Global CSS for the page and tiles -->
  		<link rel="stylesheet" href="/Public/css/main.css">
  		<!-- //Global CSS for the page and tiles -->
		<!---start-click-drop-down-menu----->
		<script src="/Public/js/jquery.min.js"></script>
        <!----start-dropdown--->
         <script type="text/javascript">
			var $ = jQuery.noConflict();
				$(function() {
					$('#activator').click(function(){
						$('#box').animate({'top':'0px'},500);
					});
					$('#boxclose').click(function(){
					$('#box').animate({'top':'-700px'},500);
					});
				});
				$(document).ready(function(){
				//Hide (Collapse) the toggle containers on load
				$(".toggle_container").hide(); 
				//Switch the "Open" and "Close" state per click then slide up/down (depending on open/close state)
				$(".trigger").click(function(){
					$(this).toggleClass("active").next().slideToggle("slow");
						return false; //Prevent the browser jump to the link anchor
				});
									
			});
		</script>
        <!----//End-dropdown--->
		<!---//End-click-drop-down-menu----->
	</head>
	<body>
		<!---start-wrap---->
			<!---start-header---->
			<div class="header">
				<div class="wrap">
				<div class="logo">
					<a href="<?php echo U('index/index/');?>"><img src="/Public/images/logo2.png" title="Mr.chen" /></a>
				</div>
				<div class="nav-icon">
					 <a href="#" class="right_bt" id="activator"><span> </span> </a>
				</div>
				 <div class="box" id="box">
					 <div class="box_content">        					                         
						<div class="box_content_center">
						 	<div class="form_content">
								<div class="menu_box_list">
									<ul>
										<li><a href="/index.php"><span>home</span></a></li>
										<?php if(is_array($nav)): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Home/Category/index',array('id'=>$v['cate_id']));?>"><span><?php echo ($v["name"]); ?></span></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
										<li><a href="/discuz.php/Index"><span>论坛</span></a></li>										
										<div class="clear"> </div>
									</ul>
								</div>
								<a class="boxclose" id="boxclose"> <span> </span></a>
							</div>                                  
						</div> 	
					</div> 
				</div>       	  
				<div class="top-searchbar">
					<form>
						<input type="text" /><input type="submit" value="" />
					</form>
				</div>
				<div class="userinfo">
					<div class="user">
						<ul>
							<?php if($_SESSION['userInfo']!= null): ?><li><a href="#"><img src="<?php echo ($_SESSION['userInfo']['avatar']); ?>" style="width:50px;" title="user-name" /><span><?php echo ($_SESSION['userInfo']['english']); ?> | <?php echo ($_SESSION['userInfo']['name']); ?></span></a>   <a href="javascript:logout();"> 退出</a></li>					
							<?php else: ?>
								<li><a href="#"><img src="/Public/images/timg.jpg" title="user-name" style="width:50px;"/><span>游客</span></a></li>
								<li><a href="<?php echo U('Home/Login/login');?>"><span>登录</span></a></li>
								<li><a href="<?php echo U('Home/Login/register');?>"><span>注册</span></a></li><?php endif; ?>
						</ul>
					</div>
				</div>
				<div class="clear"> </div>
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
			</script>
			</div>
		</div>
		<!---//End-header---->
        <div class="copyrights">Collect from <a href="http://www.cssmoban.com/" >��ҵ��վģ��</a></div>
		<!---start-content---->
		<div class="content">
			<div class="wrap">
			 <div id="main" role="main">
			      <ul id="tiles">
			        <!-- These are our grid blocks -->
			        <?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li onClick="location.href='<?php echo U('Home/Detial/detial',array('id'=>$v['art_id']));?>';">
				        	
				        	<div class="post-info">
				        		<div class="post-basic-info">
					        		<h3><a href="#"><?php echo ($v["title"]); ?></a></h3>
					        		<span><a href="#"><label> </label><?php echo ($v["addtime"]); ?></a></span>
					        		<p><?php echo ($v["introduction"]); ?></p>
				        		</div>
				        		<div class="post-info-rate-share">
			        				<span>评论:<?php echo ($v["discuss_total"]); ?></span>			        			
			        				<span>点赞:<?php echo ($v["approve_total"]); ?> </span>
				        			
				        		</div>
				        	</div>
			        	</li><?php endforeach; endif; else: echo "" ;endif; ?>
			        <!-- End of grid blocks -->
			      </ul>
			    </div>
			</div>
		</div>
		<div class="green-black"><?php echo ($data["page"]); ?></div>		
		<!---//End-content---->
		<!----wookmark-scripts---->
		  <script src="/Public/js/jquery.imagesloaded.js"></script>
		  <script src="/Public/js/jquery.wookmark.js"></script>
		  <script type="text/javascript">
		    (function ($){
		      var $tiles = $('#tiles'),
		          $handler = $('li', $tiles),
		          $main = $('#main'),
		          $window = $(window),
		          $document = $(document),
		          options = {
		            autoResize: true, // This will auto-update the layout when the browser window is resized.
		            container: $main, // Optional, used for some extra CSS styling
		            offset: 20, // Optional, the distance between grid items
		            itemWidth:280 // Optional, the width of a grid item
		          };
		      /**
		       * Reinitializes the wookmark handler after all images have loaded
		       */
		      function applyLayout() {
		        $tiles.imagesLoaded(function() {
		          // Destroy the old handler
		          if ($handler.wookmarkInstance) {
		            $handler.wookmarkInstance.clear();
		          }
		
		          // Create a new layout handler.
		          $handler = $('li', $tiles);
		          $handler.wookmark(options);
		        });
		      }
		      /**
		       * When scrolled all the way to the bottom, add more tiles
		       */
		      function onScroll() {
		        // Check if we're within 100 pixels of the bottom edge of the broser window.
		        var winHeight = window.innerHeight ? window.innerHeight : $window.height(), // iphone fix
		            closeToBottom = ($window.scrollTop() + winHeight > $document.height() - 100);
		
		        if (closeToBottom) {
		          // Get the first then items from the grid, clone them, and add them to the bottom of the grid
		          var $items = $('li', $tiles),
		              $firstTen = $items.slice(0, 10);
		          $tiles.append($firstTen.clone());
		
		          applyLayout();
		        }
		      };
		
		      // Call the layout function for the first time
		      applyLayout();
		
		      
		      //
		      
		      
		    })(jQuery);
		  </script>
		<!----//wookmark-scripts---->
		<!----start-footer--->
		<div class="footer">
			
		</div>
		<!----//End-footer--->
		<!---//End-wrap---->
	</body>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="./favicon.ico" mce_href="./favicon.ico" type="image/x-icon">
    <title><?php echo C('SITE_TITLE');?>-<?php echo C('SITE_VERSION');?></title>
    <link href="/kts-v3/Public/static/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <script src="/kts-v3/Public/static/bootstrap3/js/jquery.min.js"></script>
    <script src="/kts-v3/Public/static/bootstrap3/js/bootstrap.min.js"></script>
    <!--[if IE]><script src="/kts-v3/Public/static/html5.js"></script><![endif]-->
    <style>
        body {
            padding-top: 50px;
        }
        .sub-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        .navbar-fixed-top {
            border: 0;
        }
        .sidebar {
            display: none;
        }
        @media (min-width: 768px) {
            .sidebar {
                position: fixed;
                top: 51px;
                bottom: 0;
                left: 0;
                z-index: 1000;
                display: block;
                padding: 20px;
                overflow-x: hidden;
                overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
                background-color: #f5f5f5;
                border-right: 1px solid #eee;
            }
        }
        .nav-sidebar {
            margin-right: -21px; /* 20px padding + 1px border */
            margin-bottom: 20px;
            margin-left: -20px;
        }
        .nav-sidebar > li > a {
            padding-right: 20px;
            padding-left: 20px;
        }
        .nav-sidebar > .active > a,
        .nav-sidebar > .active > a:hover,
        .nav-sidebar > .active > a:focus {
            color: #fff;
            background-color: #428bca;
        }
        .main {
            padding: 20px;
        }
        @media (min-width: 768px) {
            .main {
                padding-right: 40px;
                padding-left: 40px;
            }
        }
        .navbar-fixed-bottom, .navbar-fixed-top{z-index:999;}
        /* --  分页样式  -- */
        .page{font-size:14px;font-family:'微软雅黑';height:55px;width:100%;padding-top:10px;line-height: 40px;}
        .page>a,.page>span{padding:5px 10px;margin-right:10px;display:block;float:left;background: #fff;border:1px solid #ddd;color:#337ab7;line-height: 20px;}
        .page>span{border:none;color:#969696;}
        .page>span.current{background:#337ab7;border:1px solid #337ab7;color:#fff;}
        /* --  其他样式  -- */
        #editor_id *,#edui_fixedlayer *{box-sizing: content-box;}
        .nav-sidebar>li.dropdown{border-bottom: 1px solid #ccc;}
        .btn{transition: all 0.3s ease-in-out 0s;}
        .btn.btn-outline{color: #563d7c; border-color: #563d7c;}
        .tab-content{padding:10px 0px;}
        table.table tr td{vertical-align: middle;}
    </style>
</head>

<body>

<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">MINI</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="javascript:alert('好好学习，天天向上');"><?php echo C('SITE_TITLE');?>-<?php echo C('SITE_VERSION');?>后台管理</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="javascript:void(0);">Hi：<?php echo ($userName); ?>(<?php echo ($remark); ?>)</a>
                <li><a href="<?php echo U('Admin/User/Logout');?>">注销登录</a></li>
            </ul>
            <!--<form class="navbar-form navbar-right">-->
                <!--<input type="text" class="form-control" placeholder="Search...">-->
            <!--</form>-->
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
   
        <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar" id="contorlNav">
                <?php if(authCheck('Admin/System/userList,Admin/User/ownerList,Admin/Store/storeList',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">系统设置<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Integral/IntegralList',$uid)): ?><li><a href="<?php echo U('Admin/Integral/integralList');?>">积分设置</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/System/userList',$uid)): ?><li><a href="<?php echo U('Admin/System/userList');?>">站点SEO</a></li><?php endif; ?>
                             <?php if(authCheck('Admin/Auth/accessList',$uid)): ?><li><a href="<?php echo U('Admin/Auth/accessList');?>">权限列表</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Auth/accessAdd',$uid)): ?><li><a href="<?php echo U('Admin/Auth/accessAdd');?>">添加权限</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Auth/groupList',$uid)): ?><li><a href="<?php echo U('Admin/Auth/groupList');?>">角色管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Auth/modulesList',$uid)): ?><li><a href="<?php echo U('Admin/Auth/modulesList');?>">模块管理</a></li><?php endif; ?> 
                            <?php if(authCheck('Admin/System/userList',$uid)): ?><li><a href="<?php echo U('Admin/System/userList');?>">相关统计</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Databasce/export',$uid)): ?><li><a href="<?php echo U('Admin/Databasce/index?type=export');?>">数据备份</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Databasce/index?type=import',$uid)): ?><li><a href="<?php echo U('Admin/Databasce/index?type=import');?>">数据恢复</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/ActionLog/index',$uid)): ?><li><a href="<?php echo U('Admin/ActionLog/index');?>">日志管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/System/settingList',$uid)): ?><li><a href="<?php echo U('Admin/System/settingList');?>">其他设置</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(authCheck('Admin/Student/stuList,Admin/Student/stuAdd',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">积分管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Integral/integralList',$uid)): ?><li><a href="<?php echo U('Admin/Integral/integralList');?>">积分列表</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Integral/integrationAdd',$uid)): ?><li><a href="<?php echo U('Admin/Integral/integrationAdd');?>">添加积分</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                 <?php if(authCheck('Admin/Coupon/couponList,Admin/Coupon/couponList',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">优惠卷管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Coupon/couponList',$uid)): ?><li><a href="<?php echo U('Admin/Coupon/couponList?all=1');?>">优惠卷列表</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
               <?php if(authCheck('Admin/Student/stuList,Admin/Student/stuAdd',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">用户管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/User/userList',$uid)): ?><li><a href="<?php echo U('Admin/User/userList');?>">用户列表</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/User/userAdd',$uid)): ?><li><a href="<?php echo U('Admin/User/userAdd');?>">添加用户</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(authCheck('Admin/System/CheckCity,Admin/System/system,Admin/System/ClearData,Admin/Cron/index,Admin/Databasce/index?type=export,Admin/Databasce/index?type=import,Admin/ActionLog/index',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">订单管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Orders/ordersList',$uid)): ?><li class="bt1"><a href="<?php echo U('Admin/Orders/ordersList');?>">订单列表</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Orders/ordersReply',$uid)): ?><li><a href="<?php echo U('Admin/Orders/ordersReply');?>">订单评论</a></li><?php endif; ?>                          
                            
                        </ul>
                    </li><?php endif; ?>
                <?php if(authCheck('Admin/Book/BookList,Admin/Book/BookAdd,Admin/Book/BookEdit',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">产品管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Book/categoryList',$uid)): ?><li><a href="<?php echo U('Admin/Book/categoryList');?>">类别管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Book/shareList',$uid)): ?><li><a href="<?php echo U('Admin/Book/shareList');?>">产品分享</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Book/oldBookList',$uid)): ?><li><a href="<?php echo U('Admin/Book/oldBookList');?>">二手书管理</a></li><?php endif; ?>
                             <?php if(authCheck('Admin/Book/bookList',$uid)): ?><li><a href="<?php echo U('Admin/Book/bookList');?>">新书管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Book/collectList',$uid)): ?><li><a href="<?php echo U('Admin/Book/collectList');?>">收藏管理</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(authCheck('Admin/Video/videoTypeList,Admin/Video/videoTypeAdd,Admin/Video/videoList,Admin/Video/videoAdd',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">阅读会管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Topic/tagList',$uid)): ?><li><a href="<?php echo U('Admin/Topic/tagList');?>">栏目小组管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Topic/topicList',$uid)): ?><li><a href="<?php echo U('Admin/Topic/topicList');?>">帖子管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/BookComment/bDList',$uid)): ?><li><a href="<?php echo U('Admin/BookComment/bDList');?>">书评管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Topic/recList',$uid)): ?><li><a href="<?php echo U('Admin/Topic/recList');?>">推荐管理</a></li><?php endif; ?>
                             <?php if(authCheck('Admin/Topic/topicList',$uid)): ?><li><a href="<?php echo U('Admin/groups/groupsList');?>">关注管理</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <?php if(authCheck('Admin/Teacher/teaList,Admin/Teacher/teaAdd',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">信息管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Information/regionList',$uid)): ?><li><a href="<?php echo U('Admin/Information/regionList');?>">地域管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Teacher/teaAdd',$uid)): ?><li><a href="<?php echo U('Admin/Teacher/teaAdd');?>">支付管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/SMS/smsList',$uid)): ?><li><a href="<?php echo U('Admin/SMS/smsList');?>">短信管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Information/messageList',$uid)): ?><li><a href="<?php echo U('Admin/Information/messageList');?>">推送消息</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Information/feedback',$uid)): ?><li><a href="<?php echo U('Admin/Information/feedback');?>">反馈管理</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Teacher/teaAdd',$uid)): ?><li><a href="<?php echo U('Admin/Teacher/teaAdd');?>">筛选关键词</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Information/bannerList',$uid)): ?><li><a href="<?php echo U('Admin/Information/bannerList');?>">banner管理</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?>
                <!-- <?php if(authCheck('Admin/Student/stuList,Admin/Student/stuAdd',$uid)): ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">学员管理<span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <?php if(authCheck('Admin/Student/stuList',$uid)): ?><li><a href="<?php echo U('Admin/Student/stuList');?>">学员列表</a></li><?php endif; ?>
                            <?php if(authCheck('Admin/Student/stuAdd',$uid)): ?><li><a href="<?php echo U('Admin/Student/stuAdd');?>">学员注册</a></li><?php endif; ?>
                        </ul>
                    </li><?php endif; ?> -->
                <!--<li>-->
                    <!--<a href="#myModal" title="Login" data-toggle="modal">修改管理员密码</a>-->
                <!--</li>-->
            </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<link rel="stylesheet" href="/kts-v3/Public/static/flowplayer/skin/functional.css">
<script src="/kts-v3/Public/static/flowplayer/flowplayer.min.js"></script>
<link href="/kts-v3/Public/static/start-rating/star-rating.min.css" media="all" rel="stylesheet" type="text/css"/>
<script src="/kts-v3/Public/static/start-rating/star-rating.min.js" type="text/javascript"></script>
<style type="text/css">
.nav-tabs img{display: none;}
</style>
<ol class="breadcrumb">
    <li><?php echo C('CONTROL_MENU');?></li>
    <li>图书设置</li>
    <li class="active"><a href="<?php echo U('Admin/Book/bookList');?>"> 图书列表</a></li>
    <li>图书详情</li>
</ol>
<div class="page-header">
    <h1><?php echo ($book['attr']['name']); ?><small><?php echo ($book['attr']['author']); ?></small></h1>
</div>

<div class="row">
    <div class="col-md-9" role="main">
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#des" aria-controls="des" role="tab" data-toggle="tab">图书详情</a></li>
            <li role="presentation"><a href="#note" aria-controls="note" role="tab" data-toggle="tab">目录 </a></li>            
            <li role="presentation"><a href="#an" id="tab_an" aria-controls="an" role="tab" data-toggle="tab">点赞 <img id="tag_img_an" src="/kts-v3/Public/loading.gif"></a></li>
            <li ><a href="<?php echo U('Admin/BookComment/book');?>&book_id=<?php echo ($_GET['book_id']); ?>"> 评 论 </a></li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="des">
            <style>
                .sc{position: relative;overflow: hidden;height:550px;margin-bottom:20px;}
                #img{width: 10000px;position: absolute;height: 100%;margin:0;padding:0;}
        		#img li{list-style: none;width: 500px;float: left;padding-right: 0;cursor:pointer;}
                #img img{width: 100%;}
				.sc span{position: absolute;width:50px;background:#00E5EE;
						opacity:0.6;border-radius:25px;
						z-index:10;cursor:pointer;font-size:36px;height:50px;top:150px;text-align:center;}
				#left{left:0;
						}
				#right{right:0;}
        	</style>
                <div class="col-md-9">
                    <div class="jumbotron">
                        <div class="sc">
                            <?php if($book['image'] != null): ?><span id='left'>&lt;</span>                        
                            <ul id='img'>
                                <?php if(is_array($book['image'])): $i = 0; $__LIST__ = $book['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li img_id=""><img src="<?php echo ($v); ?>" ></li><?php endforeach; endif; else: echo "" ;endif; ?>                                
                                <p style="clear:both"></p>
                            </ul>
                            <span id='right'>&gt;</span><?php endif; ?> 
                        </div>
                        
                    	<?php if(!empty($book['video'])): ?><div data-ratio="0.6" class="flowplayer">
								<video data-title="<?php echo ($book['video'][0]['title']); ?>">
									<source type="video/flash" src="<?php echo ($book['video'][0]['url']); ?>">
								</video>
							</div>
	                        <p class="text-warning"><i class="glyphicon glyphicon-warning-sign"></i> 视频支持格式为mp4、flv格式，swf格式不兼容</p>
	                       <input type="button" id="delVideo"  class="btn btn-info" value="删除视频" vid="<?php echo ($book['video'][0]['vid']); ?>"/><?php endif; ?>
                    </div>
                </div>
                <script type="text/javascript">
                	$('#delVideo').click(function(){
                		var src=$(this).attr('vid');
                			if(src!=null){
                				$.ajax({
                					type:"post",
                					url:"<?php echo U('Admin/video/videoDel');?>",
                					data:"vid="+src,
                					datatype:"json",
                					success:function(s){
                						console.log(s)
                					}
                				})
                			}	
                		console.log(src);
                	})
                                
                	$(function(){
                		var length=$('#img').find('img').length;
                		var width=$('#img').find('img').width();
                		var height=$('#img').find('img').eq(0).height();
                		//$('#img').css({'margin-left':'-'+width});
                		var content=$('#img').html();
                		$('.sc span').hide();
                		$('.sc').width(width);
                		$('.sc').height(height);
                		
                        var html="";
                        var a=0;
                        var t=null;
                        function show(){
                            if (a>=length-1) {
                            	a=0;
                            }
                            else{
                        		a++;
                            }                            
                           // console.log(a);
                            $('#img').stop(true).animate({ 
                                "left":'-'+width*a+"px",
                                },2000,'linear')
                        
                        }                      
                        t=setInterval(show,4000);
                        $('#img').hover(
                            function(){                            	
                                clearInterval(t);
                            },
                            function(){                            	
                            	t=setInterval(show,4000);
                            }
                            ) 
						$('#left').hover(function(){
							clearInterval(t);
						},function(){
							t=setInterval(show,4000);
						})
                            
                        $('#left').click(function(){
                        	if(a==0){
                        		a=length-1;
                        	}
                        	a--;
                        	if(length!=1){
                        		$('#img').css({'left':"-"+width*a+"px"});
                        	}
							
                        	
                        })
                        
                        $('#right').hover(function(){
							clearInterval(t);
						},function(){
							t=setInterval(show,4000);
						})
                            
                        $('#right').click(function(){
                        	if(a>=length-1){
                        		a=0;
                        	}
                        	a++;
                        	if(length!=1){
								$('#img').css({'left':"-"+width*a+"px"});
                        	}
                        }) 
                        
                        $('.sc').hover(function(){
                        	$('.sc span').show();
                        },function(){
                        	$('.sc span').hide();
                        })
                      	$('#img').find('li').click(function(){
                      		window.location.href="<?php echo U('Admin/Book/imageEdit');?>"+"&book_id="+<?php echo ($book['attr']['book_id']); ?>+"&img_id="+$(this).attr('img_id');
                      	})
                	})
                                                
                </script>
                <div class="col-md-3">
                    <ul class="list-group">
                    	<?php if(!empty($book['attr']['old_book'])): ?><li class="list-group-item">
                            图书类型：					<?php echo ($book["attr"]["old_book"]); ?>
                        	</li>
                        	<li class="list-group-item">
                            成色：					<?php echo ($book["attr"]["old_desc"]["description"]); ?>
                        	</li>
                        	<li class="list-group-item">
                            邮费：					<?php echo ($book["attr"]["old_desc"]["shipping_price"]); ?>
                        	</li><?php endif; ?>
						 <?php if(!empty($book['attr']['share'])): ?><li class="list-group-item">
                            图书类型：					分享书
                        	</li>
                        	<li class="list-group-item">
                            其他分享人：					<?php if(is_array($book['attr']['share'])): $i = 0; $__LIST__ = $book['attr']['share'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Admin/User/userDet',array('user_id'=>$v['user_id']));?>"><?php echo ($v["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                        	</li><?php endif; ?>
						<?php if(!empty($book['attr']['shop'])): ?><li class="list-group-item">
	                            图书类型：					新书
	                        	</li>
	                        	<li class="list-group-item">
	                            上架店铺：					
	                            	<a href="<?php echo U('Admin/User/userDet',array('user_id'=>$book['attr']['shop_id']));?>"><?php echo ($book['attr']['shop']['shop_name']); ?></a>   	
	                        	</li>
                        	<?php else: ?>
	                        	<li class="list-group-item">
	                           上传者：					<a href="<?php echo U('Admin/User/userDet',array('user_id'=>$book['attr']['user_id']));?>"><?php echo ($book["attr"]["user_name"]); ?></a>
	                        	</li><?php endif; ?>							
                        <li class="list-group-item">
                            书号：				<?php echo ($book["attr"]["book_number"]); ?>
                        </li>
                         <li class="list-group-item">
                            定价：				&yen; <?php echo ($book["attr"]["publish_price"]); ?>
                        </li>             
                        <li class="list-group-item">
                           类别：               	     <?php if(is_array($book["attr"]["category"])): $i = 0; $__LIST__ = $book["attr"]["category"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i; echo ($v["name"]); ?>&nbsp;&nbsp;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?>
                      				
                        </li>
                        <li class="list-group-item">
                          出版社 ：				<?php echo ($book["attr"]["press"]); ?>
                        </li>
                        <li class="list-group-item">
                         出版时间：				<?php echo (date("Y-m-d",$book["attr"]["publish_time"])); ?>
                        </li> 
                        <li class="list-group-item">
                         页数：				<?php echo ($book["attr"]["page"]); ?>
                        </li>
                        <li class="list-group-item">
                         字数：				<?php echo ($book["attr"]["words"]); ?>
                        </li>
                        <li class="list-group-item">
                        语种：				<?php echo ($book["attr"]["language"]); ?>
                        </li>                                                                                             
                        <li class="list-group-item">
                    作者 :                (<?php echo ($book['attr']['author_area']); ?>)<?php echo ($book['attr']['author']); ?>                           
                        </li>
                        <li class="list-group-item">
                        作者简介：				<?php echo ($book["attr"]["author_desc"]); ?>
                        </li> 
                         <li class="list-group-item">
                        定位：				<?php echo ($book["attr"]["address"]); ?>
                        </li>
                        <li class="list-group-item">
                            <span class="badge"><?php echo ($book["attr"]["comment"]); ?></span>
                            评论数
                        </li>
                         <li class="list-group-item">
                         适用年龄                  			<span class="badge"><?php echo ($book["attr"]["applicable_age"]); ?></span>                          
                        </li>                                           
                    </ul>
                </div>

            </div>
	            <div role="tabpanel" class="tab-pane fade" id="note" >
		            <div style="margin:10px 20px 30px 0px;">
	             		   <P>简介：</P><p style="text-indent: 2em;"><?php echo ($book["attr"]["introduce"]); ?></p>		  
	        		</div>
	                <div class="col-md-12" >
						 <p>目录：</p><p style="text-indent: 2em;"><?php echo ($book['attr']['description']); ?></p>
	                </div>
	            </div>
	            <div role="tabpanel" class="tab-pane fade" id="an"></div>
	            <div role="tabpanel" class="tab-pane fade" id="eval"></div>          
        </div>
        
    </div>
</div>
<script type="text/javascript">
  	var loadObj = "<p class=\"text-center\"><img src=\"/kts-v3/Public/loading.gif\"> loading...</p>";
    var quUrl = "<?php echo U('Admin/Attr/questionList?book_id='.$book['attr']['book_id'].'&type='.$book['attr']['type']);?>";
    var evalUrl = "<?php echo U('Admin/Attr/commentList?book_id='.$book['attr']['book_id']);?>";
    function loadFAQ()
    {
        //问题
        $.getJSON(quUrl,function(data){
            var showHtmL = "<div class=\"panel-group\" role=\"tablist\" aria-multiselectable=\"true\">";
            var template = $('#templateCLICK').html();
            if(data.attr.length == 0){
                $('#an').html('暂无点赞');
            }
            else{
                $(data.attr).each(function (i, o) {
                	 showHtmL += template.replace(/\[headImg\]/g, o.user_img).replace(/\[nickName\]/g, o.username).replace(/\[time\]/g, o.addtime)
                     .replace(/\[user_id\]/g, o.user_id)
                });
                showHtmL += "</div><div class='page'>"+data.page+"</div>";
                $('#an').html(showHtmL);
                var as = $('.page a');
                as.bind('click',function(){
                    quUrl =$(this).attr('href');
                    $("#tag_img_an").show();
                    setTimeout(loadFAQ,1000);
                    return false;
                });
            }
                    $("#tag_img_an").hide();
                }
        );
    }
    $('#tab_an').on('shown.bs.tab', function (e) {
        $("#tag_img_an").show();
        setTimeout(loadFAQ,1000);
    });

    // 加载评论
    function loadEval(){
        $.getJSON(evalUrl,function(data){
            var showHtmL = "<div class=\"panel-group\" role=\"tablist\" aria-multiselectable=\"true\">";
            var template = $('#templateEVAL').html();
            if(data.comment.length == 0){
                $('#eval').html('暂无评论');
            }
            else{
            	console.log(data);
                 $(data.comment).each(function (i, o) {                	 
                	 showHtmL += template.replace(/\[headImg\]/g,o.head_img).replace(/\[nickName\]/g, o.username).replace(/\[time\]/g, o.comment_time)
                     .replace(/\[stu_id\]/g,o.user_id).replace(/\[content\]/g,o.content)
                     .replace(/\[num\]/g,o.grade).replace(/comment_id=0/g, 'comment_id='+o.comment_id)
                     .replace(/user_id=0/g, 'user_id='+o.user_id).replace(/\[total\]/g,o.comment_total)
                     .replace(/\[click\]/g, o.click_total);   	
                	
                });
                showHtmL += "</div><div class='page'>"+data.page+"</div>"; 
                
                $('#eval').html(showHtmL);
                
                $(".input-id").rating({ // 初始化评星插件
                    showCaption:false,
                    showClear:false,
                    readonly:true
                });
                var as = $('.page a');
                as.bind('click',function(){
                    evalUrl =$(this).attr('href');
                    $("#tag_img_eval").show();
                    setTimeout(loadEval,1000);
                    return false;
                });
            }
            $("#tag_img_eval").hide();
        });
    }

    $('#tab_eval').on('shown.bs.tab', function (e){
        $("#tag_img_eval").show();
        setTimeout(loadEval,1000);
    }); 
      
    function check(arr){
    	var showHtmL = "<div class=\"panel-group\" role=\"tablist\" aria-multiselectable=\"true\">";
    	var template = $('#templateEVAL').html();
    	if(arr.length!=0){
    		check(arr);
    	}
   	 
    }
    
</script>

<script type="text/x-template" id="templateCLICK">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="media">
                <div class="media-left">
                    <img class="media-object" style="width:35px;height:35px;" src="[headImg]" alt="...">
                </div>
                <div class="media-body">
                    <p class="media-heading">[nickName]（ID:[user_id]）<font class="text-muted small">[time]</font>                    
                </div>
            </div>
        </div>
    </div>
</script>

<!-- 评论模板 -->
<script type="text/x-template" id="templateEVAL">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="media">
                <div class="media-left">
                    <img class="media-object" style="width:35px;height:35px;" src="[headImg]" alt="...">
                </div>
                <div class="media-body">
                    <p class="media-heading">[nickName]（ID:[stu_id]）<font class="text-muted small">[time]</font>
                        <input class="input-id" type="number" value="[num]" class="rating" min=0 max=5 step=0.5 data-size="xs" ></p>
                    <div class="col-sm-10" style="padding-left: 0;">
                        [content]
                    </div>			
					<a href="<?php echo U('Admin/Attr/commentDel?comment_id=0');?>">删除</a>	
					<a href="<?php echo U('Admin/Attr/evalList?comment_id=0');?>">回复</a>			
                </div>								
            </div>
			<div style="">
			<p style="margin-top:10px; float:right;"><a href="<?php echo U('Admin/Attr/evalList?comment_id=0');?>">共[total]评论&nbsp;</a>&nbsp;共[click]点赞</p>	
			</div>
        </div>
    </div>
</script>
<script type="text/javascript">
    function showYesOrNo(msg, url) {
        if (confirm(msg)) {
            window.location = url;
        }
    }
    var contorlNavSHOW = $(".breadcrumb li");
    var cnsTxt =  contorlNavSHOW.eq(1).text();
    $("#contorlNav>.dropdown>a").each(function(i,o){
        if($(o).text() == cnsTxt){
            $(o).parent().attr('class','active dropdown');
        }
    });
    function upPwd()
    {
        document.getElementById('formUpPwd').submit();
    }
    

    /**
     * POST 提交异步请求
     * @param url 调用的url地址
     * @param options json参数
     * @param success 成功后的回调函数
     * @param failed 失败后的回调函数
     */
    function ajaxCall(url, options, success, failed) {
        $.ajax({
            type: 'POST',
            url: url, // 请求的url地址
            dataType: 'json', // 返回格式为json
            data: options,
            success: success,
            error: failed
        });
    }

    /*
     *  AJAX 失败公共方法
     */
    function failed() {
        alert("数据链接超时！");
    }

    Date.prototype.Format = function(fmt)
    {
        var o = {
            "M+" : this.getMonth()+1,                 //月份
            "d+" : this.getDate(),                    //日
            "h+" : this.getHours(),                   //小时
            "m+" : this.getMinutes(),                 //分
            "s+" : this.getSeconds(),                 //秒
            "q+" : Math.floor((this.getMonth()+3)/3), //季度
            "S"  : this.getMilliseconds()             //毫秒
        };
        if(/(y+)/.test(fmt))
            fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
        for(var k in o)
            if(new RegExp("("+ k +")").test(fmt))
                fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
        return fmt;
    }
</script>
<!-- 模态框（Modal） -->
<!--<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">-->
    <!--<div class="modal-dialog modal-md">-->
        <!--<div class="modal-content">-->
            <!--<div class="modal-header">-->
                <!--<button type="button" class="close"-->
                        <!--data-dismiss="modal" aria-hidden="true">-->
                    <!--&times;-->
                <!--</button>-->
                <!--<div class="modal-title" id="myModalLabel">-->
                    <!--<h2 class="form-signin-heading">修改管理员密码&nbsp;&nbsp;<font id="pub_modal_show_msg_body" style="font-size: 14px;font-style: normal;color:#ff8000;"></font></h2>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="modal-body">-->
                <!--<form class="form-horizontal" id="formUpPwd" name="formUpPwd" action="<?php echo U('Admin/User/upPwd');?>" method="post">-->
                    <!--<div class="form-group">-->
                        <!--<label for="inputOldPasswordUser" class="col-sm-2 control-label">旧密码：</label>-->
                        <!--<div class="col-sm-10">-->
                            <!--<input type="password" class="form-control" name="opwd" id="inputOldPasswordUser" placeholder="密码" required="">-->
                        <!--</div>-->
                    <!--</div>-->
                    <!--<div class="form-group">-->
                        <!--<label for="inputPasswordUser" class="col-sm-2 control-label">新密码：</label>-->
                        <!--<div class="col-sm-10">-->
                            <!--<input type="password" class="form-control" name="npwd" id="inputPasswordUser" placeholder="密码" required="">-->
                        <!--</div>-->
                    <!--</div>-->
                <!--</form>-->
            <!--</div>-->
            <!--<div class="modal-footer text-center">-->
                <!--<button onclick="upPwd()" value="保存" class="btn btn-primary btnTrans" style="width:140px;margin:0px auto;display: block;" id="modal_login" data-no="login">保 存</button>-->
            <!--</div>-->
        <!--</div>-->
    <!--</div>-->
<!--</div>-->
<p class="text-center" style="border-top: 1px solid #ccc;margin-top:120px;"><br><br><?php echo C('SITE_TITLE');?>(<?php echo C('SITE_VERSION');?>)<br>copyright&copy;2015  版权所有 </p>
</div><!-- main over -->
</div><!-- row over -->
</div><!-- container-fluid over -->
</body>
</html>
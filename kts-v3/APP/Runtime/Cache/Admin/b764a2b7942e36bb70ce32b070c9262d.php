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
<script type="text/javascript" src="/kts-v3/Public/static/bt.typeahead/bootstrap3-typeahead.js"></script>
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/kts-v3/Public/static/Validform/ajaxfileupload.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=2AFGg6DCpGbCg48f2mOFNnGLAh02ZQHU"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //创建和初始化地图函数：
            function initMap(){
                createMap();//创建地图
                setMapEvent();//设置地图事件
                addMapControl();//向地图添加控件
            }

            //创建地图函数：
            function createMap(){
                var map = new BMap.Map("dituContent");//在百度地图容器中创建一个地图                
                var point = new BMap.Point(<?php if($data['longitude']==0 || $data['latitude']==0 ){ echo '113.327647'.','.'23.125164';} else { echo $data['longitude'].','.$data['latitude']; }?>);//定义一个中心点坐标
                map.centerAndZoom(point,14);//设定地图的中心点和坐标并将地图显示在地图容器中
                window.map = map;//将map变量存储在全局
            }

            //地图事件设置函数：
            function setMapEvent(){
                map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
                map.enableScrollWheelZoom();//启用地图滚轮放大缩小
                map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
                map.enableKeyboard();//启用键盘上下左右键移动地图
            }

            //地图控件添加函数：
            function addMapControl(){
                //向地图中添加缩放控件
                var ctrl_nav = new BMap.NavigationControl({anchor:BMAP_ANCHOR_TOP_LEFT,type:BMAP_NAVIGATION_CONTROL_LARGE});
                map.addControl(ctrl_nav);
                //向地图中添加缩略图控件
                var ctrl_ove = new BMap.OverviewMapControl({anchor:BMAP_ANCHOR_BOTTOM_RIGHT,isOpen:1});
                map.addControl(ctrl_ove);
                //向地图中添加比例尺控件
                var ctrl_sca = new BMap.ScaleControl({anchor:BMAP_ANCHOR_BOTTOM_LEFT});
                map.addControl(ctrl_sca);
            }


            initMap();//创建和初始化地图
            $("#address").blur(function () {
                var addr = $(this).val();
                addr = jQuery.trim(addr);
                var city = jQuery.trim($("#c-select").text());
                var point = new BMap.Point(113.327664,23.124602);
                map.centerAndZoom(point,12);
                // 创建地址解析器实例
                var myGeo = new BMap.Geocoder();
                /*alert(addr);*/
                // 将地址解析结果显示在地图上,并调整地图视野
                myGeo.getPoint(addr, function(point){
                    if (point) {
                        map.centerAndZoom(point, 16);
                        map.addOverlay(new BMap.Marker(point));
                    }else{
                        alert("您选择地址没有解析到结果!");
                    }
                }, "北京");
            })

            $(window).keydown(function(event){
            	
                if(event.keyCode == 13) {
                    var addr = $("#address").val();
                    addr = jQuery.trim(addr);
                    var city = jQuery.trim($("#c-select").text());
                    var point = new BMap.Point(116.331398,39.897445);
                    map.centerAndZoom(point,12);
                    // 创建地址解析器实例
                    var myGeo = new BMap.Geocoder();
                    /*alert(addr);*/
                    // 将地址解析结果显示在地图上,并调整地图视野
                   
                    myGeo.getPoint(addr, function(point){
                    	 console.log(point);
                        if (point) {
                            map.centerAndZoom(point, 16);
                            map.addOverlay(new BMap.Marker(point));
                        }else{
                            alert("您选择地址没有解析到结果!");
                        }
                    }, "北京");
                }
            });
            
            map.addEventListener("click",function(e){
                $("#longitude").val(e.point.lng);
                $("#latitude").val(e.point.lat);
                var gc = new BMap.Geocoder();
                //获取地址的数据地址
                var pt = e.point;
                gc.getLocation(pt, function(rs){
                	var addComp = rs.addressComponents;
                	address = addComp.province +  addComp.city + addComp.district + addComp.street + addComp.streetNumber;
                	console.log($("#address").val(address));
                
                });
           });
        });
    </script>
<style type="text/css">
    .form-horizontal{width:850px;}
    .form-horizontal>.form-group{border-top: 1px solid #ccc;padding-top:20px;margin-left:15px;margin-right:0px;}
    .form-horizontal>.form-group:first-child{border-top:none;}
    .form-group>.col-sm-2{width: 110px;}
    .form-group>.col-sm-10{width: 700px;}
    select.form-control{width: auto !important;}
   /* #more{display:none;} */
	.col-sm-5 select{float:left;margin-left:20px;}
    #image{overflow: hidden;}
    #image ul{list-style:none;padding:0;margin: 0;}
    #image li{cursor: pointer;}
    #image li img{width: 100%;height: 100%;}
    #otimg li{float:left;list-style:none;padding-left:5px;padding-right:5px;}
</style>
<ol class="breadcrumb">
    <li><?php echo C('CONTROL_MENU');?></li>
    <li>图书管理</li>
    <li class="active"><a href="<?php echo U('Admin/Book/bookList');?>"> 图书列表</a></li>
    <li class="active"><a href="<?php echo U('Admin/Book/shareList');?>">分享列表</a></li>
</ol>
<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="javascript:void(0);">添加图书</a></li>
</ul>
<form action="<?php echo U('Admin/BookAction/saveBook');?>" class="form-horizontal" method="post" enctype ="multipart/form-data">
	<input type='hidden' value="<?php echo ($_GET['book_id']); ?>" name='book_id'>
	<input type='hidden' value="<?php echo ($_GET['type']); ?>" name='type'>
	<?php if(is_array($data['f_id'])): $k = 0; $__LIST__ = $data['f_id'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><div class="form-group">
	        <label for="father_id" class="col-sm-2 control-label">图书类型</label>
	        <div class="col-sm-2">      
	            <select name="father_id[]" id="father_id" class="form-control father_id">
	                <option value="9999">请选择</option>                
	                <?php if(is_array($book)): $i = 0; $__LIST__ = $book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if($vo['cg_id'] == $v['first'] ): ?><option value="<?php echo ($vo["cg_id"]); ?>" selected="true"><?php echo ($vo["name"]); ?></option>
	                    <?php else: ?>
	                    	<option value="<?php echo ($vo["cg_id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
	            </select>
        	</div>        	 
	        <div class="col-sm-5">
		        	<select name="class_id[]" id="class_id" class="form-control class_id" >
		                <option value="9999">请选择</option>	               		                            
		                <?php if(is_array($fid[$k-1][0] )): $i = 0; $__LIST__ = $fid[$k-1][0] ;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sec): $mod = ($i % 2 );++$i; if($sec['cg_id'] == $v['second'] ): ?><option value="<?php echo ($sec["cg_id"]); ?>" selected="true"><?php echo ($sec["name"]); ?></option>
	                    	<?php else: ?>
		                    	<option value="<?php echo ($sec["cg_id"]); ?>"><?php echo ($sec["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		            </select>
		        	<select name="fid[]" id="class_id2" class="form-control class_id2" cg_id="<?php echo ($fid[$k-1][2]); ?>">
		                <option value="9999">请选择</option>                
			                <?php if(is_array($fid[$k-1][1])): $i = 0; $__LIST__ = $fid[$k-1][1];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$third): $mod = ($i % 2 );++$i; if($third['cg_id'] == $v['cg_id']): ?><option value="<?php echo ($third["cg_id"]); ?>" selected="true"><?php echo ($third["name"]); ?></option>
			                    <?php else: ?>
			                    	<option value="<?php echo ($third["cg_id"]); ?>"><?php echo ($third["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
			            </select>
		    	</div>
		</div><?php endforeach; endif; else: echo "" ;endif; ?> 
    <div id="add"></div>
    <button type="button" onclick="add()">添加分类</button>
    <script> 
    	var last='';
    	function add(){
    		html ="<div class=\"form-group\" style='margin-left:15px;'>";
            html +='<label for="father_id" class="col-sm-2 control-label">图书类型</label>';
            html +='<div class="col-sm-2">';      
            html +='<select name="father_id[]" id="father_id" class="form-control father_id">';
            html +='<option value="9999">请选择</option>';                
            	 	<?php if(is_array($book)): $i = 0; $__LIST__ = $book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>html +='<option value="'+<?php echo ($vo["cg_id"]); ?>+'"><?php echo ($vo["name"]); ?></option>';<?php endforeach; endif; else: echo "" ;endif; ?>
            html +='</select>';
            html +='</div>';
            html +='<div class="col-sm-5"></div>';
            html +='</div>';
            $('#add').append(html);
    	}
    	
        $('#add').on('change','.father_id',function(){
            var objectModel = {};
            var value = $(this).val();
            objectModel['father_id'] =value;
            var _this=$(this);
            if(value !=null & value !=''){
            	$.ajax({
                    cache:false,
                    type:"POST",
                    url:"<?php echo U('Admin/Book/childTypeList');?>",
                    dataType:"json",
                    data:objectModel,
                    success:function(data){
                    	if(data.length>0){
                            var count = data.length;
                            var i = 0;
                            var b="<select name=\"class_id[]\" id=\"class_id\" class=\"form-control class_id\">";
                                b +="<option value='9999'>请选择</option>";
                            for(i=0;i<count;i++){
                                b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
                            }
                            b +="</select>"+"<div class=\"Validform_checktip\"></div>";
                            b +="<div id='last'></div>";
                            _this.parent().siblings(".col-sm-5").html(b);
                    	}else{
                    		$("#add .class_id").remove();
                    	}                    
                    }
                });
            }; 
        });
        
        $('#add').on('change','.class_id',function(){
        	var objectModel = {};
            var value = $(this).val();        
            objectModel['father_id'] =value;
            var _this=$(this);
            if(value !=null && value !=''){
            	$.ajax({
                    cache:false,
                    type:"POST",
                    url:"<?php echo U('Admin/Book/childTypeList');?>",
                    dataType:"json",
                    data:objectModel,
                    success:function(data){
                    	if(data.length>0){               		
                            var count = data.length;
                            var i = 0;
                            var b="<select name=\"fid[]\" id=\"class_id2\" class=\"form-control class_id2\" cg_id='"+last+"'>";
                                b +="<option value='9999'>请选择</option>";
                            for(i=0;i<count;i++){
                                b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
                            }
                            b +="</select>";                        
                            _this.siblings('#last').html(b);
                    	}
                    }
                });
            }
        })	
        
    	   $('.father_id').change(function(){
    	        var objectModel = {};
    	        var value = $(this).val();
    	        var type = $(this).attr('id');
    	        objectModel[type] =value;
    	        var _this=$(this);
    	        if(value !=null & value !=''){
    	        	$.ajax({
    	                cache:false,
    	                type:"POST",
    	                url:"<?php echo U('Admin/Book/childTypeList');?>",
    	                dataType:"json",
    	                data:objectModel,
    	                success:function(data){
    	                	if(data.length>0){
    	                        var count = data.length;
    	                        var i = 0;
    	                        _this.parent().siblings('.col-sm-5').find('.class_id').html('');
    	                        _this.parent().siblings('.col-sm-5').find('.class_id').css({'display':'block'});
    	                        _this.parent().siblings('.col-sm-5').find('.class_id2').css({'display':'none'});
    	                        var b ="<option value='9999'>请选择</option>";
    	                        for(i=0;i<count;i++){                       	
    	                            b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
    	                        }
    	                        _this.parent().siblings('.col-sm-5').find('.class_id').html(b);
    	                	}else{
    	                		_this.parent().siblings('.col-sm-5').find('.class_id').css({'display':'none'});
    	                		_this.parent().siblings('.col-sm-5').find('.class_id2').css({'display':'none'});
    	                	}                   
    	                }
    	            });
    	        }; 
    	    });
    	    
    	    $('.class_id').change(function(){
    	    	var objectModel = {};
    	        var value = $(this).val();        
    	        objectModel['father_id'] =value;
    	        var _this=$(this);
    	        if(value !=null && value !=''){
    	        	$.ajax({
    	                cache:false,
    	                type:"POST",
    	                url:"<?php echo U('Admin/Book/childTypeList');?>",
    	                dataType:"json",
    	                data:objectModel,
    	                success:function(data){
    	                	if(data.length>0){
    	                		_this.siblings(".class_id2").html('');
    	                		_this.siblings('.class_id2').css({'display':'block'});
    	                        var count = data.length;
    	                        var i = 0;
    	                        var b ="<option value='9999'>请选择</option>";
    	                        for(i=0;i<count;i++){
    	                            b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
    	                        }
    	                        _this.siblings(".class_id2").html(b);
    	                	}else{
    	                		_this.siblings('.class_id2').css({'display':'none'});
    	                	}
    	                }
    	            });
    	        }
    	    })		    	    
    </script>
    <style>
    	#last{margin-top:-55px;margin-left:100px;}
    </style>
 <input type="file"  id='changeimg' name='changeimage' style="display:none"/>
 <div class="form-group">
        <label class="col-sm-2 control-label">封面</label>
        <div class="col-sm-10" id='img'>
        	<div class='cover'>
        		<img src="<?php echo ($data['cover_img']); ?>" val='2351' style="width:70px;height:70px;cursor:pointer" >
        		<p style="cursor:pointer" class='change' >更改</p>
        	</div>       	
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">版面</label>
        <div class="col-sm-10" id='img'>
        	<div class='copyright'>
        		<img src="<?php echo ($data['copyright']); ?>" val='2351' style="width:70px;height:70px;cursor:pointer" >
        		<p style="cursor:pointer" class='change'>更改</p>
        	</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">其他图片</label>
        <div class="col-sm-10" >        	
           		<ul id='otimg'>
	           		<?php if(!empty($image)): if(is_array($image)): $i = 0; $__LIST__ = $image;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class='other'>
		       					<img src="<?php echo ($v["url"]); ?>" style="width:70px;height:70px;cursor:pointer" val='<?php echo ($v["name"]); ?>' >
		   						<p style="cursor:pointer" class='change'>更改</p>
		   						<p style="cursor:pointer" class='del'>删除</p>
		       				</li><?php endforeach; endif; else: echo "" ;endif; endif; ?>         		
           		</ul>
        </div>
        
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Ad图片</label>
        <div class="col-sm-10" id='otimg'>        	
        	 <p style="cursor:pointer" id='addother'>添加</p>
           	 <input type="file"  id='addotherimg' name='imgs' style="display:none"/>
        </div>
    </div>
    <script type="text/javascript">
    	var pix='';
    	var _this='';
    	var oldurl='';
    	$(document).on('click','.change',function(){
    		 _this=$(this);
    		 pix = $(this).parent().attr('class');
    		 oldurl=_this.prev().attr('val');
    		$('#changeimg').click();
    	})
    	
    	$(document).on('change','#changeimg',function(){
    		$.ajaxFileUpload({
    			url:"<?php echo U('Admin/AjaxUpload/changeBookImage',array('book_id'=>$_GET['book_id']));?>"+"&type="+pix+"&old="+oldurl,
    			secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'changeimg', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  
    			{
                	console.log(data);
                	if(data != 0){
                		_this.prev().attr('src',data.image_url);
                    	_this.prev().attr('val',data.val);
                	}
    			}
    		})
    	})
    
    	$(document).on('click','.del',function(){
    		 _this=$(this);
    		 pix = $(this).parent().attr('class');
    		 oldurl=_this.prev().prev().attr('val');
    		 $.ajax({
    			 url:"<?php echo U('Admin/AjaxUpload/DelBookImage');?>",
     			 type: 'post',
     			 data: "book_id=<?php echo ($_GET['book_id']); ?>"+"&old="+oldurl,
                 dataType: 'json', 
                 success: function (e)  
     			{
                 	console.log(e);
                 	if(e==1){
                 		_this.parent().remove();
                 	}
     			} 
    		 });
    	})
    	
    	$(document).on('change','#changeimg',function(){
    		$.ajaxFileUpload({
    			url:"<?php echo U('Admin/AjaxUpload/changeBookImage',array('book_id'=>$_GET['book_id']));?>"+"&type="+pix+"&old="+oldurl,
    			secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'changeimg', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  
    			{
                	console.log(data);
                	if(data != 0){
                		_this.prev().attr('src',data.image_url);
                    	_this.prev().attr('val',data.val);
                	}
    			}
    		})
    	})
    	
    	$('#addother').click(function(){
    		 _this=$(this);    		 
    		$('#addotherimg').click();
    	})
    	
    	$(document).on('change','#addotherimg',function(){
    		$.ajaxFileUpload({
    			url:"<?php echo U('Admin/AjaxUpload/add_image',array('book_id'=>$_GET['book_id']));?>",
    			secureuri: false, //是否需要安全协议，一般设置为false
                fileElementId: 'addotherimg', //文件上传域的ID
                dataType: 'json', //返回值类型 一般设置为json
                success: function (data, status)  
    			{
                	console.log(data);
                	if(data != 0){
                		var html  = "<li class='other'>";
       					html += "<img src="+data.image_url+" style=\"width:70px;height:70px;cursor:pointer\" val="+data.val+" >";
       					html += "<p style=\"cursor:pointer\" class='change'>更改</p>";
       					html += "<p style=\"cursor:pointer\" class='del'>删除</p></li>";
                		$('#otimg').append(html);
                	}
    			}
    		})
    	})
    
        $('#image').find('ul>li').click(function(){
        	window.location.href="<?php echo U('Admin/Book/imageEdit');?>"+"&img_id="+$(this).attr('img_id')+"&book_id="+<?php echo ($data['book_id']); ?>;
        })
        function upImage() {
	        var myImage = _editor.getDialog("insertimage");
	        myImage.open();
    	}
        var _editor = UE.getEditor('myEditor');
    	_editor.ready(function () {
    	    //设置编辑器不可用
    	    //_editor.setDisabled();
    	    //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
    	    _editor.hide();
    	    //侦听图片上传
    	    _editor.addListener('beforeInsertImage', function (t, arg) {
    	    	var html="";
    			var input="";
    	        $.each(arg,function(i,o){
    	            	html +="<li><img src='"+o.src+"' style=\"width:300px;height:100px;\"></li>";
    	            	input +="<input value='"+o.src+"' type=\"text\" name=\"file[]\" style=\"display:none;\">";
    	        });
    	       	$("#image ul").append(html);
    	        $("#image").append(input);
        		})
    	});
    </script>

<!--     <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">图片宽度</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="tea_name" autocomplete="off" name="width" data-provide="typeahead" datatype="s" nullmsg="请输入书名" />
        </div>
    </div>
    <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">图片高度</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="tea_name" autocomplete="off" name="height" data-provide="typeahead" datatype="s" nullmsg="请输入书名" />
        </div>
    </div> -->
 <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">是否推荐</label>
        	<select name="status"  class="form-control" id='state'>
        		<option value="0">不推荐</option>
        		<?php if($data['status'] == 1): ?><option value="1" selected>推荐</option>
        		<?php else: ?>
        			<option value="1">推荐</option><?php endif; ?>
                              
                                   
            </select>
    </div>   
    <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">图书名称</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="tea_name" value="<?php echo ($data['book_name']); ?>" disabled autocomplete="off" name="book_name" data-provide="typeahead" datatype="s" nullmsg="请输入书名" />
        </div>
    </div>
    <div class="form-group">
        <label for="video_title" class="col-sm-2 control-label">书号</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($data['book_number']); ?>" id="video_title" name="book_number" disabled />
            <div class="Validform_checktip">标题长度应在20个字以内</div>
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">作者</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($data['author']); ?>" id="link" disabled name="author" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">出版社</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($data['press']); ?>" id="link" disabled name="press" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">出版时间</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($data['publish_time']); ?>" id="link" disabled name="publish_time" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">定价</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($data["publish_price"]); ?>" id="link"  name="publish_price" />
        </div>
    </div>
    <?php if(!empty($total)): ?><div class="form-group">
	        <label for="link" class="col-sm-2 control-label">库存</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($total); ?>" id="link"  name="total" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">售价</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data["price"]); ?>" id="link"  name="price" />
	        </div>
	    </div><?php endif; ?>
    <?php if(!empty($old)): ?><div class="form-group">
        <label for="link" class="col-sm-2 control-label">新旧程度</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($old["description"]); ?>" id="link"  name="description" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">出售人</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($old["user"]); ?>" id="link"  disabled name="user" />
        </div>
    </div>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">运费</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="<?php echo ($old["shipping_price"]); ?>" id="link"  name="shipping_price" />
        </div>
    </div><?php endif; ?>
    <div class="form-group">
        	<a href="javascript:more()">更多</a>
    </div>
    <div id='more' >
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">开本</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['format']); ?>" id="link" name="format" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">作者地区</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['author_area']); ?>" id="link" name="author_area" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">版次</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['edition']); ?>" id="link" name="edition" />
	        </div>
	    </div>	
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">印张</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['impression']); ?>" id="link" name="impression" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">页数</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['page']); ?>" id="link" name="page" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">字数</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['words']); ?>" id="link" name="words" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">适用年龄</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data['applicable_age']); ?>" id="link" name="age" />
	        </div>
	    </div>	    
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">语种</label>
	        <div class="col-sm-10">
	           <select name="language"  class="form-control" id='language'>                
	                <?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option ><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">装帧</label>
	        <div class="col-sm-10">
	           <select name="binding"  class="form-control" id='binding'>              
	                <?php if(is_array($binding)): $i = 0; $__LIST__ = $binding;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option ><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">纸张</label>
	        <div class="col-sm-10">
	           <select name="paper"  class="form-control" id='paper'>               
	                <?php if(is_array($paper)): $i = 0; $__LIST__ = $paper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>	    	    
	    <div class="form-group">
	        <label class="col-sm-2 control-label">作者简介</label>
	        <div class="col-sm-10">
	            <textarea id="videoDes" name="author_desc" class="form-control" ><?php echo ($data['author_desc']); ?></textarea>
	            <div class="Validform_checktip"></div>
	        </div>
	    </div>
	    <div class="form-group">
	        <label class="col-sm-2 control-label">内容简介</label>
	        <div class="col-sm-10">
	            <textarea id="videoDes" name="introduce" class="form-control" ><?php echo ($data['introduce']); ?></textarea>
	            <div class="Validform_checktip"></div>
	        </div>
	    </div>	    
	    <div class="form-group">
	        <label class="col-sm-2 control-label">目录</label>
	       		<div class="col-sm-10">
		            <textarea id="videoDes" name="catalog" class="form-control" style="margin: 0px -15px 0px 0px; padding: 0px; width: 677px; height: 349px;"><?php echo ($catalog); ?></textarea>
		            <div class="Validform_checktip"></div>
	        	</div>	        
	    </div> 
	    <div class="form-group" >
	        <label for="link" class="col-sm-2 control-label">地址</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="<?php echo ($data["address"]); ?>" id="address" name="address" disabled/>
	            <input class="form-control" type="hidden" value=""  id="longitude" name="longitude" />
	            <input class="form-control" type="hidden" value="" id="latitude" name="latitude" />
	            <div style="width:100%;height:300px;border:#ccc solid 1px;" id="dituContent"></div>
	        </div>
           
    	</div>   
	    <div class="form-group">
	    	<?php if(!empty($video)): ?><label class="col-sm-2 control-label">多媒体</label>
                <div data-ratio="0.6" class="flowplayer">
                    <video data-title="<?php echo ($video[0]['title']); ?>"  autoplay="autoplay" controls="controls" loop="loop">
                        <source type="video/mp4" src="<?php echo ($video[0]['url']); ?>">
                    </video>
                </div><?php endif; ?>
                <div class="col-sm-10">
	            <input type="button" onclick="addvideo()"  class="btn btn-info" value="添加视频"/>
	            <div class="Validform_checktip">支持jpg、gif、png、jpeg(4种格式)，大小不超过1M</div>
	            <input type="text" value="添加标题" name="title[]"/>
	            <input type="file" name="img" id="imga" style="display:none">
	        </div>
    	</div>    	
    </div>        
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">保存修改</button>
        </div>
    </div>
    <input type="text" id="tea_id" name="book_id" style="display: none;" value="<?php echo ($data['book_id']); ?>">
</form>
<link href="/kts-v3/Public/static/Validform/css/style.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/kts-v3/Public/static/Validform/Validform_v5.3.2_min.js"></script>
<script type="text/javascript">

	
	function more(){
			$('#more').css({'display':'block'})
	}
	function addvideo(){
		$('#imga').click();
	}
</script>
<script type="text/javascript" language="javascript">
   // var ue = UE.getEditor('editor');
 
    
    
   $.extend($.Datatype,{
        "z2-4" : /^[\u4E00-\u9FA5\uf900-\ufa2d]{2,4}$/
    });
    $(function(){
        $(".form-horizontal").Validform({
            tiptype:function(msg,o,cssctl){
                if(!o.obj.is("form")){
                    var objtip=o.obj.nextAll(".Validform_checktip");
                    cssctl(objtip,o.type);
                    objtip.text(msg);
                }
            },
            /*beforeCheck:function(curform){
                if(!confirm('确定提交本次操作吗？'))
                {
                    return false;
                }
            }*/
            });

    });

    $(function(){                        
        width=$('#image').find('img').width();
        height=$('#image').find('img').height();
        $('#image').width(width);
        $('#image').height(height);
        var length=$('#image').find('img').length;
        var html="";
        var a=0;
        var t=null;
        function show(){                                                       
            if (a>=length) {
                a=0;
            }
		$('#image').find('ul').find('li').stop().fadeOut(300).eq(a).fadeIn(300);
           	a++;
        }
        show();
        t=setInterval(show,4000);
        $('#image').find('ul').hover(
            function(){
                clearInterval(t);
            },
            function(){
            	t=setInterval(show,4000);
            }
            )
    })
    
 
    
</script>
<script type="text/plain" id="myEditor"></script>
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
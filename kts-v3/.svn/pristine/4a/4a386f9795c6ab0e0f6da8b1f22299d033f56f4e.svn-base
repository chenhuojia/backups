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
                var point = new BMap.Point(116.297766,39.929101);//定义一个中心点坐标
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
  /*  #more{display:none;}*/
    #showPress{z-index:5;position:absolute;width: 250px;left: 125px;top: 55px;overflow: hidden;}
    #showPress ul{padding: 0;margin: 0;font-size: 16px;background: #FFc;}
    #showPress li{list-style: none;background:#ccc;margin-bottom: 2px;text-align: center;cursor: pointer;}
     #showPress li:hover{background:red;}
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
<form action="<?php echo U('Admin/BookAction/AddBook');?>" class="form-horizontal" method="post" enctype ="multipart/form-data">
    <div class="form-group">
        <label for="father_id" class="col-sm-2 control-label">图书类型</label>
        <div class="col-sm-2">      
            <select name="father_id[]" id="father_id" class="form-control father_id">
                <option value="9999">请选择</option>                
                <?php if(is_array($book)): $i = 0; $__LIST__ = $book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["cg_id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="col-sm-5"></div>
    </div>
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
                    url:"<?php echo U('Admin/AjaxActionBook/childTypeList');?>",
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
                    url:"<?php echo U('Admin/AjaxActionBook/childTypeList');?>",
                    dataType:"json",
                    data:objectModel,
                    success:function(data){
                    	if(data.length>0){               		
                            var count = data.length;
                            var i = 0;
                            var b="<select name=\"fid[]\" id=\"class_id2\" class=\"form-control class_id2\" style='margin-left:15px;'>";
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
        if(value !=null & value !=''){
        	$.ajax({
                cache:false,
                type:"POST",
                url:"<?php echo U('Admin/AjaxActionBook/childTypeList');?>",
                dataType:"json",
                data:objectModel,
                success:function(data){
                	if(data.length>0){
                        var count = data.length;
                        var i = 0;
                        var b="<select name=\"class_id[]\" id=\"class_id\" class=\"form-control\">";
                            b +="<option value='9999'>请选择</option>";
                        for(i=0;i<count;i++){
                            b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
                        }
                        b +="</select>"+"<div class=\"Validform_checktip\"></div>";
                        b +="<div id='last'></div>";
                        $(".col-sm-5").html(b);
                	}else{
                		$("#class_id").remove();
                	}                    
                }
            });
        }; 
    });
    
    $('.col-sm-5').on('change','select',function(){
    	var objectModel = {};
        var value = $(this).val();        
        objectModel['father_id'] =value;
        if(value !=null && value !=''){
        	$.ajax({
                cache:false,
                type:"POST",
                url:"<?php echo U('Admin/AjaxActionBook/childTypeList');?>",
                dataType:"json",
                data:objectModel,
                success:function(data){
                	if(data.length>0){               		
                        var count = data.length;
                        var i = 0;
                        var b="<select name=\"fid[]\" id=\"class_id2\" class=\"form-control\" style='margin-left:15px;'>";
                            b +="<option value='9999'>请选择</option>";
                        for(i=0;i<count;i++){
                            b+="<option value='"+data[i].cg_id+"'>"+data[i].name+"</option>";
                        }
                        b +="</select>";
                        $("#last").html(b);
                	}
                }
            });
        }    
    })	         	    
    </script>
    <style>
    	#last{margin-top:-55px;margin-left:100px;}
    </style>
    <div class="form-group">
        <label class="col-sm-2 control-label">封面</label>
        <div class="col-sm-10" id='img'>
        	<input type="file"  name="img" id='cover' />
        	<div class='cover'>
        		
        	</div>       	
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">版面</label>
        <div class="col-sm-10" id='img'>
        	<input type="file" name="img" id='copyright'/>
        	<div class='copyright'>
        		
        	</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">其他图片</label>
        <div class="col-sm-10" id='img'>        	
            <input type="button" onclick="upImage()"  class="btn btn-info" value="上传other"/>
            <div class="Validform_checktip">支持jpg、gif、png、jpeg(4种格式)，大小不超过1M</div>  
            <div id='ims'>
            	<ul>
            		
            	</ul>
            </div>
        </div>
    </div>
    <script>
	    $(document).on("change",'#cover',function(){
	    	ajaxupload('cover');
	    })
	    
	    $(document).on("change",'#copyright',function(){
	    	ajaxupload('copyright');
	    })
	    
	    function ajaxupload(type){
	    	if(type=='cover'){
	    		var fileid='cover';
	    		var _this= $('.cover');
	    		var name='cover';
	      	  }if(type=='copyright'){
	      		var fileid='copyright';
	      		var _this=$('.copyright');
	      		var name='copyright';
	      	  }
	    	$.ajaxFileUpload
	        (
	            {
	                url: "<?php echo U('Admin/AjaxUpload/image_upload');?>"+"&type=book", //用于文件上传的服务器端请求地址
	                secureuri: false, //是否需要安全协议，一般设置为false
	                fileElementId: fileid, //文件上传域的ID
	                dataType: 'json', //返回值类型 一般设置为json
	                success: function (data, status)  //服务器成功响应处理函数
	                {	
	                	console.log(data, status);
	                    if(data!=0){
	                	   //var htmls=html(data.image_url,data.val);	                	 
	                	   var	html ="<div><img src="+data.image_url+" style=\"width:70px;height:70px;cursor:pointer\" >";
		                       	html +="<input type=\"text\" name=  "+name+"  value="+data.val+" style=\"display:none\">";
		                       	html +="<button onclick='del(this)'>删除</button>";
		                       	html +="</div>";
	                		 _this.html(html);
	                   }
	                },
	                error: function (data, status, e)//服务器响应失败处理函数
	                {
	                    alert(e);
	                }
	            }
	        )
	    }
	    
	    function del(obj){
	    	$(obj).parent().remove();
	    }
    </script>
    <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">是否推荐</label>
        	<select name="status"  class="form-control">
                <option value="1">不推荐</option>                
                 <option value="2">推荐</option>                   
            </select>
    </div>
    <div class="form-group">
        <label for="video_title" class="col-sm-2 control-label">书号</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="" id="video_title" name="book_number" datatype="*1-20" nullmsg="请输入书号！" errormsg="标题长度应在20个字以内" />
            <div class="Validform_checktip">标题长度应在20个字以内</div>
        </div>
    </div>
    <div class="form-group">
        <label for="tea_name" class="col-sm-2 control-label">图书名称</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="tea_name" autocomplete="off" name="book_name" data-provide="typeahead" datatype="s" nullmsg="请输入书名" />
        </div>
    </div>
    
    <script>
		$(function(){
		    $('#video_title').blur(function(){
		    	var value=$.trim($(this).val());
		    	var object={'book_number':value};
		    	if(value !=''|| value !=null){
		    		$.ajax({
		    			 type : "post",
		    			 async:false,
		    			 url : "<?php echo U('Admin/AjaxActionBook/getBookApi');?>",
		    			 data:object,
		    			 dataType : "json",
		    			 success : function(data){
		    				 console.log(data);
		    				if(typeof(data)=='object'){
		    					$('#tea_name').val(data.book_name);
		    					$('#author').val(data.author);
		    					$('#press').val(data.publisher);
		    					$('#pubdate').val(data.pubdate);
		    					$('#pages').val(data.pages);
		    					$('#publish_price').val(data.price);
		    					$('#catalog').val(data.catalog);
		    					$('#introduce').val(data.introduce);
		    					$('#author_intro').val(data.author_desc);
		    					$('#publish_price').val(data.price);
		    					if($("#author_area option[value='"+data.author_area+"']").val()){
		    						$("#author_area option[value='"+data.author_area+"']").attr("selected", true);
		    					}else{
		    						$("#author_area").append("<option selected>"+data.author_area+"</option>");
		    					}
		    					
		    					if($("#binding option[value='"+data.binding+"']").val()){
		    						$("#author_area option[value='"+data.binding+"']").attr("selected", true);
		    					}else{
		    						$("#binding").append("<option selected>"+data.binding+"</option>");
		    					}
		    					 
		    				}
		    	           	console.log(data) 
		    	         }
		    		})
		    	}
		    })
		})	
    </script>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">作者</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="" id="author" name="author" />
        </div>
    </div>
    <div class="form-group" style="position:relative;">
        <label for="link" class="col-sm-2 control-label">出版社</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="" id="press" name="press" />
        </div>
        <div id="showPress">

        </div>  
    </div>      	
   	<script type="text/javascript">
   		$('#press').keyup(function(){
   			val=$(this).val();
   			//console.log(val);
            if (val) {
                $.ajax({
                    type:"get",
                    datatype:"json",
                    url:"<?php echo U('Admin/Attr/findPress?searchkey=');?>"+val,
                    success:function(s){
                        var html ="<ul>";
                        $.each(s,function(i,v){
                            //console.log(v.name);
                            html +="<li>"+v.name+"</li>";
                        })
                        html +="</ul>";
                        $('#showPress').html(html);
                        $('#showPress').show();
                    }                
                }) 
            }  			 
   		})
	$('#showPress').hover(function(){
		$(this).show();
	},function(){
		$(this).hide();	
	})
   		
      $('#showPress').on('click','li',function(){
    	  var val=$(this).html();
    	  $('#press').val(val);
      })

   	</script>
    <div class="form-group">
        <label for="link" class="col-sm-2 control-label">出版时间</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="" id="pubdate" name="publish_time" />
        </div>
    </div>
   	<div class="form-group">
        <label for="link" class="col-sm-2 control-label">定价</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value="" id="publish_price" name="publish_price" />
        </div>
   	</div>
   	 <div class="form-group">
       	<label for="vi_long" class="col-sm-2 control-label">图书类型</label>
        <div class="col-sm-10">
           <select name="type"  class="form-control" id="type">                         	
                    <option value="1">新书</option>
                    <option value="0">二手书</option>
                    <option value="2">分享书</option>                     
            </select>
        </div>
    </div>
        <div class="form-group" id="price" >
        <label for="link" class="col-sm-2 control-label">出售价格</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" value=""  name="price" />
        </div>
     </div>
	    <div class="form-group" id="desc" style="display:none">
        	<label for="vi_long" class="col-sm-2 control-label">新旧程度</label>
	        <div class="col-sm-10">
	           <select name="description"  class="form-control">                            	
	                    <option>九成新</option>
	                    <option>六至八成新</option>
	                    <option>五成新以下</option>                   
	            </select>
	        </div>
	   	 </div>	   	 
	   	 <div class="form-group" id="shipping" style="display:none">
        	<label for="vi_long" class="col-sm-2 control-label">邮费</label>
	        <div class="col-sm-10">
	           <input type="text" name="shipping_price" value="0">
	        </div>
	   	 </div>
	   	 
	   	 <div class="form-group" id="user" style="display:none">
        	<label for="vi_long" class="col-sm-2 control-label">用户</label>
	        <div class="col-sm-10">
	           <select name="user_id"  class="form-control">                            	
	                    <option value="0">请选择</option>
	                    <?php if(is_array($user)): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["user_id"]); ?>"><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>                 
	            </select>
	        </div> 
	   	 </div>
	   	 <div class="form-group" id="shop">
        	<label for="vi_long" class="col-sm-2 control-label">店铺</label>
	        <div class="col-sm-10">
	           <select name="shop"  class="form-control">                            	
	                    <option value="0">请选择</option>
	                    <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["shop_id"]); ?>"><?php echo ($v["username"]); ?>--><?php echo ($v["shop_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>                 
	            </select>
	        </div> 
	   	 </div>
	   	 <div class="form-group" id='kucun'>
	        <label for="link" class="col-sm-2 control-label">库存</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="total" name="total" />
	        </div>
    	</div>		   	  	 
	   	 <script>
	   	 	$(function(){
	   	 		var	value=$('#type').val();
		   	 	$('#type').change(function(){
		   	 		value=$(this).val();
			   	 	if(value==0){
		   	 			$('#desc').css({display:'block'});
		   	 			$('#user').css({display:'block'});
		   	 		    $('#price').css({display:'none'});
		   	 		    $('#shipping').css({display:'block'});
		   	 		    $('#kucun').css({display:'none'});
		   	 		    $('#shop').css({display:'none'});
		   	 		}if(value==2){		   	 			
		   	 			$('#user').css({display:'block'});
		   	 			$('#desc').css({display:'none'});
		   	 		    $('#price').css({display:'none'});
		   	 		    $('#shipping').css({display:'none'});
		   	 		    $('#kucun').css({display:'none'});
		   	 			$('#shop').css({display:'none'});
		   	 		}if(value==1){
		   	 			$('#desc').css({display:'none'});
		   	 			$('#user').css({display:'none'});
		   	 		    $('#price').css({display:'block'});
		   	 		    $('#shipping').css({display:'none'});
		   	 		    $('#kucun').css({display:'block'});
		   	 			$('#shop').css({display:'block'});
		   	 		}
		   	 	})	   	 		
	   	 	})	   	 
	   	 </script>
    <div class="form-group">
        	<a href="javascript:more()">更多</a>
    </div>
    <div id='more' > 		
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">开本</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="format" name="format" />
	        </div>
	    </div>
	   <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">作者地区</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value=" " id="link" name="author_area" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">版次</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="edition" name="edition" />
	        </div>
	    </div>	
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">印张</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="impression" name="impression" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">页数</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="pages" name="page" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">字数</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="words" name="words" />
	        </div>
	    </div>
	    <div class="form-group">
	        <label for="link" class="col-sm-2 control-label">适用年龄</label>
	        <div class="col-sm-10">
	            <select name='age' class="form-control">
	            	<?php if(is_array($age)): $i = 0; $__LIST__ = $age;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value='<?php echo ($v["id"]); ?>'><?php echo ($v["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>	    
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">语种</label>
	        <div class="col-sm-10">
	           <select name="language"  class="form-control">                
	                <?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option ><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">装帧</label>
	        <div class="col-sm-10">
	           <select name="binding"  class="form-control" id="binding">              
	                <?php if(is_array($binding)): $i = 0; $__LIST__ = $binding;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v); ?>"><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">纸张</label>
	        <div class="col-sm-10">
	           <select name="paper"  class="form-control">               
	                <?php if(is_array($paper)): $i = 0; $__LIST__ = $paper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>	    	    
	    <div class="form-group">
	        <label class="col-sm-2 control-label">作者简介</label>
	        <div class="col-sm-10">
	            <textarea id="author_intro" name="author_desc" class="form-control" ></textarea>
	            <div class="Validform_checktip"></div>
	        </div>
	    </div>
	    <div class="form-group">
	        <label class="col-sm-2 control-label">内容简介</label>
	        <div class="col-sm-10">
	            <textarea id="introduce" name="introduce" class="form-control" ></textarea>
	            <div class="Validform_checktip"></div>
	        </div>
	    </div>	    
	    <div class="form-group">
	        <label class="col-sm-2 control-label">目录</label>
	       		<div class="col-sm-10">
		            <textarea id="catalog" name="catalog" class="form-control" style="margin: 0px -15px 0px 0px; padding: 0px; width: 677px; height: 349px;"></textarea>
		            <div class="Validform_checktip"></div>
	        	</div>	        
	    </div>
	    <div class="form-group" >
	        <label for="link" class="col-sm-2 control-label">地址</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="address" name="address" />
	            <input class="form-control" type="hidden" value=""  id="longitude" name="longitude" />
	            <input class="form-control" type="hidden" value="" id="latitude" name="latitude" />
	            <div style="width:100%;height:300px;border:#ccc solid 1px;" id="dituContent"></div>
	        </div>
           
    	</div>
	    <div class="form-group">
	        <label class="col-sm-2 control-label">多媒体</label>
	        <div class="col-sm-10" id='img'>
	            <input type="button" onclick="addvideo()"  class="btn btn-info" value="添加视频"/>
	            <div class="Validform_checktip">支持jpg、gif、png、jpeg(4种格式)，大小不超过1M</div>
	            <input type="text" placeholder="添加标题" name="title"/>
	            <input type="file" name="imge" id="imga" style="display:none">
	            <input type="text" name="video" id="video" style="display:none">
	        </div>
    	</div>
    </div>    
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-default">新增图书</button>
        </div>
    </div>
</form>
<link href="/kts-v3/Public/static/Validform/css/style.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/kts-v3/Public/static/Validform/Validform_v5.3.2_min.js"></script>
<script type="text/javascript">



function addvideo(){
	$('#imga').click();
}

$(document).on('change','#imga',function(){
	$.ajaxFileUpload
    (
        {
            url: "<?php echo U('Admin/AjaxUpload/image_upload');?>"+"&type=video", //用于文件上传的服务器端请求地址
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId:'imga', //文件上传域的ID
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data, status)  //服务器成功响应处理函数
            {	
            	console.log(data, status);
                if(data!=0){
            	   $('#video').val(data.val);	                	 
               }
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert(e);
            }
        }
    )
})



var _editor = UE.getEditor('myEditor');
	_editor.ready(function () {
	    //设置编辑器不可用
	    //_editor.setDisabled();
	    //隐藏编辑器，因为不会用到这个编辑器实例，所以要隐藏
	    _editor.hide();
	    //侦听图片上传
	    _editor.addListener('beforeInsertImage', function (t, arg) {
	    	console.log(arg);
	    	var html="";
			var input="";
	        $.each(arg,function(i,o){
	            	html +="<li  style=\"float:left;list-style:none\"><img src='"+o.src+"' width=\"100px\" height=\"100px\"></li>";
	            	input +="<input value='"+o.title+"' type=\"text\" name=\"file[]\" style=\"display:none;\">";
	        });
	       	$("#ims ul").append(html);
	        $("#ims").append(input);
    })
});
	function more(){
			$('#more').css({'display':'block'})
	}
</script>
<script type="text/javascript" language="javascript"> 
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
            beforeCheck:function(curform){
                if(!confirm('确定提交本次操作吗？'))
                {
                    return false;
                }
            }
        });

    });
    
	function upImage() {
        var myImage = _editor.getDialog("insertimage");
        myImage.open();
    }

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
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
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/ueditor.all.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/kts-v3/Public/static/ueditor1.4.3/lang/zh-cn/zh-cn.js"></script>
<style type="text/css">
    .form-horizontal{width:850px;}
    .form-horizontal>.form-group{border-top: 1px solid #ccc;padding-top:20px;margin-left:15px;margin-right:0px;}
    .form-horizontal>.form-group:first-child{border-top:none;}
    .form-group>.col-sm-2{width: 110px;}
    .form-group>.col-sm-10{width: 700px;}
	#header{display:none;}
	#change{cursor:pointer;}
</style>
<ol class="breadcrumb">
    <li><?php echo C('CONTROL_MENU');?></li>
    <li>用户管理</li>
    <li class="active"><a href="<?php echo U('Admin/User/userList');?>"> 用户列表</a></li>
    <li>用户详情</li>
</ol>
<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="javascript:void(0);">用户详情</a></li>
</ul>
<form action="<?php echo U('Admin/User/userEdit?user_id='.$user['user_id']);?>" class="form-horizontal" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="user_user" class="col-sm-2 control-label">头像</label>
        <?php if($user['imageurl'] !=null): ?><div id='change'><img style="width:150px;height:150px" src="<?php echo ($user['imageurl']); ?>"></div>
        	<input type="text" value="<?php echo ($user['imageurl']); ?>" style="display:none" name="oldimg">
        <?php else: ?>
        	<div id='change'><img style="width:150px;height:150px" src="Public/Upload/User/10016.gif"></div><?php endif; ?>
        <div class="col-sm-10">
            <input type="file" name="imageurl"  id="header" style="display:none"/>
        </div>        
    </div>
	 <div class="form-group">
        <label for="user_nickname" class="col-sm-2 control-label">用户昵称</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_nickname" autocomplete="off" value="<?php echo ($user["name"]); ?>" name="name" />
            <div for="tea_name" class="Validform_checktip">请输入昵称</div>
        </div>
    </div>
    <style>
    	#father_id{padding:0}
		#class_id{padding:0;margin:0}
    </style>
    <?php if($user['city'] == null): ?><div class="form-group">
	        <label for="father_id" class="col-sm-2 control-label">城市</label>
	        <div class="col-sm-2">      
	            <select name="father_id" id="father_id" class="form-control">
	            	<option value='9999'>请选择</option>              
	                <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["province_id"]); ?>"><?php echo ($vo["province"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	        <div class="col-sm-5">	        
	        </div>
	    </div>
	<?php else: ?>
	<div class="form-group">
        <label for="father_id" class="col-sm-2 control-label">城市</label>
        <div class="col-sm-2">      
            <select name="father_id" id="father_id" class="form-control">               
                <?php if(is_array($province)): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value='9999'>请选择</option>               	
                    <option value="<?php echo ($vo["province_id"]); ?>"><?php echo ($vo["province"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="col-sm-5">
    	</div>
	</div><?php endif; ?>
   	<div class="form-group">
        <label for="user_nickname" class="col-sm-2 control-label">QQ</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_nickname" autocomplete="off" value="<?php echo ($user["bind_qq"]); ?>" name="bind_qq" />
            
        </div>
    </div> 
    <div class="form-group">
        <label for="user_nickname" class="col-sm-2 control-label">微信</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_nickname" autocomplete="off" value="<?php echo ($user["bind_wechat"]); ?>" name="bind_wechat" />
            
        </div>
    </div>
  	<div class="form-group">
        <label for="user_nickname" class="col-sm-2 control-label">简介</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_nickname" autocomplete="off" value="<?php echo ($user["introduce"]); ?>" name="introduce" />
            
        </div>
    </div>
    <?php if($user["type"] == 1): ?><div class="form-group">
        <label for="user_nickname" class="col-sm-2 control-label">店铺名称</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_nickname" autocomplete="off" value="<?php echo ($user["shop_name"]); ?>" name="shop_name" />
            <input type="hidden" value="<?php echo ($user["type"]); ?>" name='type'>
        </div>
    </div><?php endif; ?>
    <div class="form-group">
        <label for="user_phone" class="col-sm-2 control-label">手机号</label>
        <div class="col-sm-10">
            <input class="form-control" type="text" id="user_phone" autocomplete="off" value="<?php echo ($user["phone"]); ?>" name="phone" datatype="m" nullmsg="请输入用户手机号" errormsg="请输入正确的手机号"/>
            <div class="Validform_checktip">请输入用户手机号</div>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">性别</label>
        <div class="col-sm-10">
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary <?php if(($user["sex"]) == "男"): ?>active<?php endif; ?>">
                    <input type="radio" name="sex" value="男" <?php if(($sex) == "男"): ?>checked<?php endif; ?>>男
                </label>
                <label class="btn btn-primary <?php if(($user["sex"]) == "女"): ?>active<?php endif; ?>">
                    <input type="radio" name="sex" value="女" <?php if(($sex) == "女"): ?>checked<?php endif; ?>>女
                </label>
                <label class="btn btn-primary <?php if(($user["sex"]) == "其他"): ?>active<?php endif; ?>">
                    <input type="radio" name="sex" value="其他" <?php if(($sex) == "其他"): ?>checked<?php endif; ?>>其他
                </label>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class= "col-sm-offset-2 col-sm-10">
            <button type="button" class="btn btn-default" id="edit">修改用户</button>
        </div>
    </div>
</form>
<link href="/kts-v3/Public/static/Validform/css/style.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="/kts-v3/Public/static/Validform/Validform_v5.3.2_min.js"></script>

<script type="text/javascript" language="javascript">
var provice="<?php echo ($user["father"]); ?>";
var city="<?php echo ($user["city"]); ?>";
if(provice){
	$("#father_id").val(provice).attr("selected",true);	
}

   $('#edit').click(function(){
	   window.location.href="<?php echo U('Admin/User/userEdit');?>&user_id=<?php echo $_GET['user_id'];?>"
   })

$(function(){
	$('#change').find('img').click(function(){
		$('#header').click();
		$('#header').change(function(){
			var file = this.files[0]; //选择上传的文件
			var r = new FileReader();
			r.readAsDataURL(file); //Base64
			$(r).load(function(){
			$('#change').html('');
			$('#change').html('<img src="'+ this.result +'" alt="" style="width:150px;height:150px"/>');
			});
		});
	})	
}) ;


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
    
    $(function(){
	    var objectModel = {};
	    var value = $('#father_id').val();
	    var type = $("#father_id").attr('id');
	    objectModel[type] =value;
	    if(value !=null & value !=''){
	    	$.ajax({
	            cache:false,
	            type:"POST",
	            url:"<?php echo U('Admin/Attr/getCity');?>",
	            dataType:"json",
	            data:objectModel,
	            success:function(data){
	            	if(data.length>0){
	                    var count = data.length;
	                    var i = 0;
	                    var b="<select name=\"class_id\" id=\"class_id\" class=\"form-control\">";
	                    for(i=0;i<count;i++){
	                        b+="<option value='"+data[i].city_id+"'>"+data[i].city+"</option>";
	                    }
	                    b +="</select>"+"<div class=\"Validform_checktip\"></div>";
	                    b +="<div id='last'></div>";
	                    $(".col-sm-5").html(b);
	                    if(city){
	                    	$("#class_id").val(city).attr("selected",true);
	                    }   
	            	}else{
	            		$("#class_id").remove();
	            	}                    
	            }
	        });
	    }; 

})

$('#father_id').change(function(){
    var objectModel = {};
    var value = $(this).val();
    var type = $(this).attr('id');
    objectModel[type] =value;
    if(value !=null & value !=''){
    	$.ajax({
            cache:false,
            type:"POST",
            url:"<?php echo U('Admin/Attr/getCity');?>",
            dataType:"json",
            data:objectModel,
            success:function(data){
            	if(data.length>0){
                    var count = data.length;
                    var i = 0;
                    var b="<select name=\"class_id\" id=\"class_id\" class=\"form-control\">";
                        b +="<option value=''>请选择</option>";
                    for(i=0;i<count;i++){
                        b+="<option value='"+data[i].city_id+"'>"+data[i].city+"</option>";
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
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
    <link href="/kts/Public/static/bootstrap3/css/bootstrap.min.css" rel="stylesheet">
    <script src="/kts/Public/static/bootstrap3/js/jquery.min.js"></script>
    <script src="/kts/Public/static/bootstrap3/js/bootstrap.min.js"></script>
    <!--[if IE]><script src="/kts/Public/static/html5.js"></script><![endif]-->
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
<link rel="stylesheet" type="text/css" href="/kts/Public/static/bt.datepicker/daterangepicker-bs3.css" />
<script type="text/javascript" src="/kts/Public/static/bt.datepicker/moment.js"></script>
<script type="text/javascript" src="/kts/Public/static/bt.datepicker/daterangepicker.js"></script>
<script type="text/javascript">
    var search_url="<?php echo U('Admin/book/bookList');?>";
    function search(){
        var searchKey = $("#searchKey").val();
        var searchType = $("#searchType").val();
        if (searchKey.length > 0)
            search_url = search_url + "&searchKey="+searchKey+ "&searchType="+searchType;

        window.location=search_url;
    }
    function changeSearchType(i,n)
    {
        $("#searchType").val(i);
        $("#searchType>font").text(n);
    }

    $(function(){
        var sd = "<?php echo I('get.searchType',0);?>";
        if(sd == 1){
            changeSearchType(1,'作者');
        }if(sd==0){
        	changeSearchType(0,'书名');
        }if(sd==2){
        	changeSearchType(2,'书号');
        }
    })
</script>

<style type="text/css">.table-responsive img{width:35px;height:35px;
    border:1px solid #337AB7;
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    border-radius: 50%;}
    .table-responsive{overflow:visible !important;}
	.jian{display:block;width:20px;height:26px;float:left;cursor:pointer;text-align:center;margin-top:9px;
			line-height:28px;background-color:#ddd;}
	.table-responsive input{text-align:center;float:left;margin-top:9px;} 
	.total{width:50px;}
	.price{width:100px;}
	.jia{display:block;width:20px;height:26px;float:left;cursor:pointer;text-align:center;margin-top:9px;
			line-height:28px;background-color:#ddd;}
</style>
<ol class="breadcrumb">
    <li><?php echo C('CONTROL_MENU');?></li>
    <li>新书管理</li>
    <li class="active"><a href="<?php echo U('Admin/Book/shareList');?>"> 分享列表</a></li>
    <li class="active"><a href="<?php echo U('Admin/Book/oldBookList');?>"> 二手书列表</a></li>
</ol>
<ul id="myTab" class="nav nav-tabs">
    <li class="active"><a href="javascript:void(0);">图书管理</a></li>
    <li><a href="<?php echo U('Admin/Book/bookAdd');?>">添加图书</a></li>
</ul>
<div class="tab-content">
    <div class="panel panel-warning">
        <div class="panel-heading"><i class="glyphicon glyphicon-warning-sign"></i> 小提示<font color="#006400"></font></div>
        <div class="panel-body">
            &nbsp;&nbsp;&nbsp;&nbsp;1、点击帐号可浏览详细信息<br>&nbsp;&nbsp;&nbsp;&nbsp;2、点击收藏数可以查看收藏详细
        </div>
    </div>
    <div class="form-inline" role="form">
        <div class="input-group">
            <div class="input-group-btn">
                <button type="button" id="searchType" name="searchType" value="0" class="btn btn-default dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false"><font>书名/作者</font><span class="caret"></span></button>
                <ul class="dropdown-menu">
                    <li><a href="javascript:changeSearchType(0,'书名');">书名</a></li>
                    <li><a href="javascript:changeSearchType(1,'作者');">作者</a></li>
                    <li><a href="javascript:changeSearchType(2,'书号');">书号</a></li>
                </ul>
            </div>
            <input type="text" id="searchKey" name="searchKey" placeholder="" value="" class="form-control"/>
            <span class="input-group-btn">
                <button type="button" onclick="search()" class="btn btn-primary">搜索</button>
            </span>
        </div>
    </div>
</div>
<div class="table-responsive" style="overflow: visible;">
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th>ID编号</th>
            <th>缩略图</th>
            <th>是否推荐</th>
            <th>书名/作者</th>
            <th>书号</th>
            <th>类型</th>            
            <th>分类</th>
            <th>数量</th>
            <th>价格</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if($list != false): if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$s): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($s["book_id"]); ?></td>
                <td><img src="<?php echo ($s["cover_img"]); ?>"></td>
                <td>
                	<?php if(($s["status"]) == "1"): ?><a href="javascript:" title="点击禁用" onclick="changeState(this)" value="<?php echo ($s["status"]); ?>" book_id="<?php echo ($s['book_id']); ?>"><span class="label label-success"><i class="glyphicon glyphicon-ok"></i></span></a>
                    <?php else: ?>
                    	<a href="javascript:" title="点击启用" onclick="changeState(this)" value="<?php echo ($s["status"]); ?>" book_id="<?php echo ($s['book_id']); ?>"><span class="label label-danger"><i class="glyphicon glyphicon-remove"></i></span></a><?php endif; ?>
                </td>
                <td width=350><a href="<?php echo U('Admin/Book/bookDet?book_id='.$s['book_id']);?>"><?php echo ($s["name"]); ?> /<?php echo ($s["author"]); ?></a></td>
                <td><?php echo ($s["book_number"]); ?></td>
                <td><?php echo ($s['type']?'新书':'二手书'); ?></td>
                <td><a href="<?php echo U('Admin/Book/bookList',array('f_id'=>$s['cg_id']));?>"><?php echo ($s["category"]); ?></a></td>
                 <td><span class='jian' book_id="<?php echo ($s["book_id"]); ?>" book_type="<?php echo ($s["type"]); ?>">-</span><input type="text" value="<?php echo ($s["total"]); ?>" class='total'></input><span class='jia' book_id="<?php echo ($s["book_id"]); ?>" book_type="<?php echo ($s["type"]); ?>">+</span></td>                 
                <td><input type="text" value="<?php echo ($s["price"]); ?>" book_id="<?php echo ($s["book_id"]); ?>" class="price" book_type="<?php echo ($s["type"]); ?>"></input></td>
                <td>
                    <div class="btn-group">
                        <a class="btn btn-info" href="javascript:" id="<?php echo ($s['book_id']); ?>">删除</a> 
                        <a class="btn btn-warning" href="<?php echo U('Admin/Book/bookEdit?book_id='.$s['book_id']);?>&type=<?php echo ($s['type']); ?>">编辑</a>
                    </div>
                    <div id="class_name<?php echo ($s["book_id"]); ?>" style="display: none;">
                        <?php if(is_array($videoClass)): $i = 0; $__LIST__ = $videoClass;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$c): $mod = ($i % 2 );++$i;?><span><?php if(in_array(($c["class_id"]), is_array($s['class_id'])?$s['class_id']:explode(',',$s['class_id']))): echo ($c["class_name"]); ?>&nbsp;&nbsp;<?php endif; ?></span><?php endforeach; endif; else: echo "" ;endif; ?>
                    </div>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; else: ?>
            <tr>
                <td colspan="5"><p style="width:80%;padding:10px;font-size:16px;color:#EC7A7A;text-align: center;"><i class="glyphicon glyphicon-search"></i> 没有找到匹配的数据！</p></td>
            </tr><?php endif; ?>
        </tbody>
    </table>
</div>
<div class="page"><?php echo ($page); ?></div>
<!-- 学员详细模态框（Modal） -->
<div class="modal fade" id="mybookModal" tabindex="-1" role="dialog" aria-labelledby="mybookModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="mybookModalLabel">用户信息</h4>
            </div>
            <div class="modal-body" id="myModalbookContent">
                <div class="panel panel-default">
                    <div class="panel-heading">个人信息</div>
                    <div class="panel-body">
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">最近登录信息</h3>
                    </div>
                    <div class="panel-body">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    
	function changeState(obj){
		var _this=$(obj);
		var state=_this.attr('value');
		var book_id=_this.attr('book_id');
		$.ajax({
			'type':"get",
			'datatyp':"json",
			'url':"<?php echo U('Admin/AjaxActionBook/changeState?book_id=');?>"+book_id,
			success:function(s){
				if(s){
					if(state==0){
						_this.html('');
						_this.attr('value',1);
						_this.html("<span class=\"label label-success\"><i class=\"glyphicon glyphicon-ok\"></i></span>");
					}if(state==1){
						_this.attr('value',0);
						_this.html('');
						_this.html("<span class=\"label label-danger\"><i class=\"glyphicon glyphicon-remove\"></i></span>");						
					}					
				}
			}
		})
	}

   $('.btn-info').click(function(){
	   if(confirm("你确定要删除吗？")){
		   url="<?php echo U('Admin/AjaxActionBook/bookDel?book_id=');?>"+$(this).attr('id');
		   $(this).attr('href',url)  
	   }else{
		   return false;
	   }	   	   
   })
   
   $(function(){
        $("[data-toggle='popover']").popover();
    }); 
    
   $('table').find('td').find('.jian').click(function(){
	   $book_id=$(this).attr('book_id');
	   $book_type=$(this).attr('book_type');
	   total=$(this).next();
	  $.ajax({
           cache:false,
           type:"POST",
           url:"<?php echo U('Admin/AjaxActionBook/bookReduce');?>",
           dataType:"json",
           data:"book_id="+$book_id+"&book_type="+$book_type,
           success:function(data){
        	   total.val(data);
           }
       });
   }) 
   
   $('table').find('td').find('.jia').click(function(){
	var   book_id=$(this).attr('book_id');
	var   book_type=$(this).attr('book_type');
	var   total=$(this).prev();
	   $.ajax({
           cache:false,
           type:"POST",
           url:"<?php echo U('Admin/AjaxActionBook/bookInc');?>",
           dataType:"json",
           data:"book_id="+book_id+"&book_type="+book_type,
           success:function(data){
        	   total.val(data)
           }
       });
   }) 
   
      $('table').find('td').find('.total').blur(function(){
		$book_id=$(this).prev().attr('book_id');
		$book_type=$(this).prev().attr('book_type');
		$value=$(this).val();
		_this=$(this);
		$.ajax({
	       cache:false,
	       type:"POST",
	       url:"<?php echo U('Admin/AjaxActionBook/bookTotal');?>",
	       dataType:"json",
	       data:"book_id="+$book_id+"&value="+$value+"&book_type="+$book_type,
	       success:function(data){
	    	   if(data=='success'){
	    		   _this.val($value); 
	    	   }
	       }
		});
   	 })
        	 
      $('table').find('td').find('.price').blur(function(){
		$book_id=$(this).attr('book_id');
		$book_type=$(this).attr('book_type');
		$value=$(this).val();
		_this=$(this);
		$.ajax({
	       cache:false,
	       type:"POST",
	       url:"<?php echo U('Admin/AjaxActionBook/bookPrice');?>",
	       dataType:"json",
	       data:"book_id="+$book_id+"&value="+$value+"&book_type="+$book_type,
	       success:function(data){
	    	   if(data=='success'){
	    		   if($value==parseInt($value)){
	    			   $value=$value+".00";
	    			   _this.val($value);
	    		   }else{
	    			   _this.val($value);
	    		   }
	    		    
	    	   }
	       }
		});
   	 })   	 
    
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
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
<script src="/kts/Public/static/Highcharts-4.0.3/highcharts.js"></script>
<script src="/kts/Public/static/Highcharts-4.0.3/modules/exporting.js"></script>
<style type="text/css">

     .bar {border:1px solid #999999; background:#FFFFFF;font-size:2px; width:89%; margin:2px 0 5px 0;padding:1px;overflow: hidden;}
     .bar_1 {border:1px dotted #999999; background:#FFFFFF; font-size:2px; width:89%; margin:2px 0 5px 0;padding:1px;overflow: hidden;}
     .barli_red{background:#ff6600; height:5px; margin:0px; padding:0;}
     .barli_blue{background:#0099FF; height:5px; margin:0px; padding:0;}
     .barli_green{background:#36b52a; height:5px; margin:0px; padding:0;}
     .barli_1{background:#999999; height:5px; margin:0px; padding:0;}
     .barli{background:#36b52a; height:5px; margin:0px; padding:0;}

</style>
<div class="panel panel-default">
    <div class="panel-body">
        <i class="glyphicon glyphicon-home" style="color:#696969;"></i>&nbsp;&nbsp;&nbsp;您好，<b><?php echo ($info["user_name"]); ?></b> [<?php echo ($info["remark"]); ?>]
            &nbsp;&nbsp;&nbsp;&nbsp;登录IP：<?php echo ($info["last_login_ip"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;时间：<?php echo ($info["last_login_time"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;
            位置：<?php echo ($info["last_location"]); ?>&nbsp;&nbsp;&nbsp;&nbsp;时区：<?php echo ($system["timezone"]); ?>
    </div>
</div>
<div class="panel panel-primary">
    <div class="panel-heading"><i class="glyphicon glyphicon-bell"></i> 待办事项</div>
    <div class="panel-body">
        <div class="col-xs-6">
            ** 管理员待处理项<br>
            新增用户：<a href="<?php echo U('Admin/User/userList?newuser=1');?>"><?php echo ($todo_info["user"]); ?></a> 个，
            新增新书：<a href="<?php echo U('Admin/Book/bookList?newbook=1');?>"><?php echo ($todo_info["book"]); ?></a>本，
            新增分享：<a href="<?php echo U('Admin/Book/shareList?newshare=1');?>"><?php echo ($todo_info["share"]); ?></a>本<br>
           新增二手书：<a href="<?php echo U('Admin/Book/oldBookList?newoldbook=1');?>"><?php echo ($todo_info["old_book"]); ?></a>本<br>
        </div>
        <div class="col-xs-6">
            ** 平台用户<br>
            苹果(IOS)：<a href="<?php echo U('Admin/User/userList?IOS=ios');?>"><?php echo ($user["IOS"]); ?></a> 个，
            安卓(Android)：<a href="<?php echo U('Admin/User/userList?Android=android');?>"><?php echo ($user["Android"]); ?></a> 个 <br/>
<!--             绑定车越汇统计人数：<a href="<?php echo U('Admin/User/ownerList?BindStore=0');?>"><?php echo ($owner_count["owner_bind_sys"]); ?></a> 位，
            未绑定车行统计人数：<a href="<?php echo U('Admin/User/ownerList?BindStore=-1');?>"><?php echo ($owner_count["owner_bind_null"]); ?></a> 位 <br/>
            行驶证待上传：<a href="<?php echo U('Admin/User/ownerList?licenseStatus=98');?>"><?php echo ($todo_info["carnoupNum"]); ?></a> 个，
            行驶证待审核：<a href="<?php echo U('Admin/User/ownerList?licenseStatus=99');?>"><?php echo ($todo_info["carnocheckNum"]); ?></a> 个，
            行驶证已审核：<a href="<?php echo U('Admin/User/ownerList?licenseStatus=1');?>"><?php echo ($todo_info["carcheckpassNum"]); ?></a> 个，
            行驶证已驳回：<a href="<?php echo U('Admin/User/ownerList?licenseStatus=0');?>"><?php echo ($todo_info["carcheckfailNum"]); ?></a> 个 -->
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> 会员信息&nbsp;&nbsp;<span>共<?php echo ($owner_count["ownernum"]); ?>位,已审<?php echo ($owner_count["ownerok"]); ?>位,未审：<?php echo ($owner_count["ownerno"]); ?>位</span></div>
            <div class="panel-body" style="height:300px;overflow-y: scroll;">
                <ul id="owner_info" class="list-group">
	                <li class="list-group-item">&nbsp;</li>
	                <li class="list-group-item">&nbsp;</li>
	                <li class="list-group-item">&nbsp;</li>
                </ul>
            </div>
            <div class="panel-footer"><img src="/kts/Public/loading.gif" id="img_owner_info" style="vertical-align: middle;"><span id="owner_info_footer"></span></div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading"><i class="glyphicon glyphicon-list"></i> 店铺信息&nbsp;&nbsp;<span>正常运营<?php echo ($store_info["count0"]); ?>家,关闭<?php echo ($store_info["count1"]); ?>家</span></div>
            <div class="panel-body" style="height:300px;overflow-y: scroll;">
                <ul id="store_info" class="list-group"><li class="list-group-item">&nbsp;</li><li class="list-group-item">&nbsp;</li><li class="list-group-item">&nbsp;</li></ul>
            </div>
            <div class="panel-footer"><img src="/kts/Public/loading.gif" id="img_store_info" style="vertical-align: middle;"><span id="store_info_footer"></span></div>
        </div>
    </div>
</div>
<div class="panel panel-info">
    <div class="panel-heading" style="height:55px;line-height:35px;">
        <i class="glyphicon glyphicon-align-left"> 订单记录</i>
        <div class="form-inline pull-right">
            <div class="form-group">
                <select id="bill_Select" class="form-control" onchange="Bill_info()">
                    <option value="chk">待付款</option>
                    <option value="use">已收货</option>
                    <option value="sub">待发货</option>
                    <option value="use">已收货</option>
                    <option value="use">待收货</option>
                    <option value="use">已收货</option>
                    <option value="yy">待评价</option>
                    <option value="yy">已评价</option>
                </select>
            </div>
            <a href="javascript:Bill_info();" class="btn btn-info">
                <i class="glyphicon glyphicon-refresh"></i>
                更新
            </a>
        </div>
    </div>
    <div class="panel-body">
        <div class="box-content" id="chart_line">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading" style="height:55px;line-height:35px;"><i class="glyphicon glyphicon-cog"></i> 网络使用状况</div>
            <div class="panel-body">
                <?php if(($net_state) != ""): echo ($net_state); ?>
                    <?php else: ?>
                    无法获取当前服务器网络使用信息<?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading" style="height:55px;line-height:35px;">
                <i class="glyphicon glyphicon-dashboard"></i> 油价接口
                <div class="form-inline pull-right">
                    <img src="/kts/Public/loading.gif" id="img_oil" style="vertical-align: middle;display: none;">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" id="oil_value" class="form-control" placeholder="请输入城市" aria-describedby="basic-addon-oil">
                            <a href="javascript:search_oil();" class="input-group-addon" id="basic-addon-oil"><i class="glyphicon glyphicon-search"></i></a>
                        </div>
                    </div>

                    <a href="javascript:upApi();" class="btn btn-info">
                        <i class="glyphicon glyphicon-refresh"></i>
                        更新
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <table width="80%">
                    <tr><td><label>上次更新时间：<font id="api_last_time">checking data</font>&nbsp;&nbsp;&nbsp;&nbsp;<font id="btn_upApi"></font></label></td>
                        <td align="right"><p id="s_oil_content"></p></td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-flash"></i> 系统信息</div>
            <div class="panel-body">
                管理员个数：<?php echo ($info["num"]); ?><br>
                服务器域名/IP地址：<?php echo ($_SERVER['SERVER_NAME']); ?>(<?php if(DIRECTORY_SEPARATOR == '/'): echo ($_SERVER['SERVER_ADDR']); else: echo @gethostbyname($_SERVER['SERVER_NAME']); endif; ?>) <br />
                服务器标识：<?php if($sysinfo['win_n'] != ''): echo ($sysinfo["win_n"]); else: echo @php_uname(); endif; ?> <br />
                服务器操作系统：<?php echo ($os["0"]); ?> &nbsp;内核版本：<?php if(DIRECTORY_SEPARATOR == '/'): echo ($os["2"]); else: echo ($os["1"]); endif; ?><br />
                服务器解译引擎：<?php echo ($_SERVER['SERVER_SOFTWARE']); ?> <br />
                服务器语言：<?php echo getenv("HTTP_ACCEPT_LANGUAGE");?> <br />
                服务器端口：<?php echo ($_SERVER['SERVER_PORT']); ?> <br />
                服务器主机名：<?php if(DIRECTORY_SEPARATOR == '/'): echo ($os["1"]); else: echo ($os["2"]); endif; ?> <br />
                管理员邮箱：<?php echo ($_SERVER['SERVER_ADMIN']); ?> <br />
                绝对路径：<?php echo SITE_DIR;?> <br />
                上传文件最大限制（upload_max_filesize）：<?php echo get_cfg_var('upload_max_filesize');?> <br />
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-flash"></i> 服务器实时数据</div>
            <div class="panel-body">
                <?php if(($sysinfo["sysReShow"]) == "show"): ?>服务器当前时间：<span><?php echo ($sysinfo["stime"]); ?></span> <br />
                    服务器已运行时间：<span><?php echo ($sysinfo["uptime"]); ?></span> <br />
                    总空间：<?php echo ($sysinfo["DiskTotal"]); ?>&nbsp;GB &nbsp;&nbsp;&nbsp;&nbsp;<span title="显示的是网站所在的目录的可用空间，非服务器上所有磁盘之可用空间！">可用空间</span>： <font color='#CC0000'><span><?php echo ($sysinfo["freeSpace"]); ?></span></font>&nbsp;GB<br />
                    CPU型号 [<?php echo ($sysinfo["cpu"]["num"]); ?>核]：<?php echo ($sysinfo["cpu"]["model"]); ?> <br />
                    内存使用状况：物理内存：共<font color='#CC0000'><?php echo ($sysinfo["TotalMemory"]); ?></font>, 已用<font color='#CC0000'><span><?php echo ($sysinfo["UsedMemory"]); ?></span></font>, 空闲<font color='#CC0000'><span><?php echo ($sysinfo["FreeMemory"]); ?></span></font>, 使用率<span><?php echo ($sysinfo["memPercent"]); ?></span> <br />
                    <div class="bar"><div class="barli_green" style="width:<?php echo ($sysinfo["memPercent"]); ?>">&nbsp;</div> </div>
                    <?php if($sysinfo['CachedMemory'] > 0): ?>Cache化内存为 <span><?php echo ($sysinfo["CachedMemory"]); ?></span>, 使用率<span><?php echo ($sysinfo["memCachedPercent"]); ?></span> %	| Buffers缓冲为  <span><?php echo ($sysinfo["Buffers"]); ?></span>
                        <div class="bar"><div class="barli_blue" style="width:<?php echo ($sysinfo["barmemCachedPercent"]); ?>">&nbsp;</div></div>
                        真实内存使用 <span><?php echo ($sysinfo["memRealUsed"]); ?></span>, 真实内存空闲<span><?php echo ($sysinfo["memRealFree"]); ?></span>, 使用率<span><?php echo ($sysinfo["memRealPercent"]); ?></span> %
                        <div class="bar_1"><div class="barli_1" style="width:<?php echo ($sysinfo["barmemRealPercent"]); ?>">&nbsp;</div></div><?php endif; ?>
                    <?php if($sysinfo['TotalSwap'] > 0): ?>SWAP区：共<?php echo ($sysinfo["TotalSwap"]); ?>, 已使用<span><?php echo ($sysinfo["swapUsed"]); ?></span>, 空闲<span><?php echo ($sysinfo["swapFree"]); ?></span>, 使用率<span><?php echo ($sysinfo["swapPercent"]); ?></span> %
                        <div class="bar"><div class="barli_red" style="width:<?php echo ($sysinfo["barswapPercent"]); ?>">&nbsp;</div> </div><?php endif; ?>
                    系统平均负载：<span><?php echo ($sysinfo["loadAvg"]); ?></span>
                    <?php else: ?>
                    无法获取当前服务器实时数据<?php endif; ?>
            </div>
        </div>
    </div>
</div>
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
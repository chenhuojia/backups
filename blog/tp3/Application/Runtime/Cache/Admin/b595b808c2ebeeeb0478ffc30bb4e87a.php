<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/favicon.ico" >
<LINK rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<script type="text/javascript" src="/Public/admin/lib/jquery.min.js"></script> 
<link href="/Public/admin/css/page.css" rel="stylesheet" type="text/css" />
<link href="/Public/admin/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/admin/lib/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/Public/admin/lib/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/admin/lib/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/admin/lib/Validform_v5.3.2.js"></script> 
<script type="text/javascript" src="/Public/admin/lib/layer1.8/layer.min.js"></script> 
<script type="text/javascript" src="/Public/admin/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/admin/js/H-ui.admin.js"></script> 
<script type="text/javascript" src="/Public/admin/js/H-ui.admin.doc.js"></script> 
<!--[if IE 7]>
<link href="lib/font-awesome/font-awesome-ie7.min.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
 ﻿<link href="/Public/admin/css/style.css" rel="stylesheet" type="text/css" />
<title>陈伙佳Admin</title>
</head>
<body>
<header class="Hui-header cl"> <a class="Hui-logo l" title="H-ui.admin v2.2" href="/">Admin</a> <span class="Hui-userbox"><span class="c-white">超级管理员：<?php echo ($_SESSION['admin']['name']); ?> <?php echo ($_SESSION['admin']['last_login_time']); ?></span> <a class="btn btn-danger radius ml-10" href="<?php echo U('Ajax/letOut');?>" title="退出"><i class="icon-off"></i> 退出</a></span> <a aria-hidden="false" class="Hui-nav-toggle" href="#"></a> </header>
<aside class="Hui-aside">
  <input runat="server" id="divScrollValue" type="hidden" value="" />
  <div class="menu_dropdown bk_2">
    <dl id="menu-user">
      <dt><i class="icon-user"></i> 用户中心<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Admin/User/userList');?>" href="javascript:;">用户管理</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-comments">
      <dt><i class="icon-comments"></i> 评论管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="http://h-ui.duoshuo.com/admin/" href="javascript:;">评论列表</a></li>
          <li><a _href="feedback-list.html" href="javascript:void(0)">意见反馈</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-comments">
      <dt><i class="icon-comments"></i> 博客管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Admin/Category/articleList');?>" href="javascript:void(0)">分类管理</a></li>
          <li><a _href="<?php echo U('Admin/Blog/article');?>" href="javascript:;">文章管理</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-article">
      <dt><i class="icon-edit"></i> 资讯管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Admin/Category/articleList');?>" href="javascript:void(0)">分类管理</a></li>
          <li><a _href="article-list.html" href="javascript:void(0)">资讯管理</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-admin">
      <dt><i class="icon-key"></i> 管理员管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Admin/System/adminRole');?>" href="javascript:void(0)">角色管理</a></li>
          <li><a _href="<?php echo U('Admin/System/adminPermission');?>" href="javascript:void(0)">权限管理</a></li>
          <li><a _href="<?php echo U('Admin/System/adminList');?>" href="javascript:void(0)">管理员列表</a></li>
        </ul>
      </dd>
    </dl>
    <dl id="menu-system">
      <dt><i class="icon-cogs"></i> 系统管理<i class="iconfont menu_dropdown-arrow">&#xf02a9;</i></dt>
      <dd>
        <ul>
          <li><a _href="<?php echo U('Admin/System/systemBase');?>" href="javascript:void(0)">基本设置</a></li>
          <li><a _href="<?php echo U('Admin/System/systemData');?>" href="javascript:void(0)">数据字典</a></li>
          <li><a _href="<?php echo U('Admin/System/systemShielding');?>" href="javascript:void(0)">屏蔽词</a></li>
          <li><a _href="<?php echo U('Admin/System/systemLog');?>" href="javascript:void(0)">系统日志</a></li>
        </ul>
      </dd>
    </dl>
  </div>
</aside>
<div class="dislpayArrow"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<section class="Hui-article-box">
  <div id="Hui-tabNav" class="Hui-tabNav">
    <div class="Hui-tabNav-wp">
      <ul id="min_title_list" class="acrossTab cl">
        <li class="active"><span title="我的桌面" data-href="welcome.html">我的桌面</span><em></em></li>
      </ul>
    </div>
    <div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="icon-step-backward"></i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="icon-step-forward"></i></a></div>
  </div>
  <div id="iframe_box" class="Hui-article">
    <div class="show_iframe">
      <div style="display:none" class="loading"></div>
      <iframe scrolling="yes" frameborder="0" src="<?php echo U('Admin/Index/welcome');?>"></iframe>
    </div>
  </div>
</section>


<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?080836300300be57b7f34f4b3e97d911";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F080836300300be57b7f34f4b3e97d911' type='text/javascript'%3E%3C/script%3E"));
</script>
</body>
</html>
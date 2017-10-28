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
<script type="text/javascript" src="/dsd/tp3/Public/admin/lib/jquery.min.js"></script> 
<link href="/dsd/tp3/Public/admin/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/dsd/tp3/Public/admin/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/dsd/tp3/Public/admin/lib/iconfont/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/dsd/tp3/Public/admin/lib/font-awesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/dsd/tp3/Public/admin/lib/jquery.min.js"></script> 
<script type="text/javascript" src="/dsd/tp3/Public/admin/lib/Validform_v5.3.2.js"></script> 
<script type="text/javascript" src="/dsd/tp3/Public/admin/lib/layer1.8/layer.min.js"></script> 
<script type="text/javascript" src="/dsd/tp3/Public/admin/js/H-ui.js"></script> 
<script type="text/javascript" src="/dsd/tp3/Public/admin/js/H-ui.admin.js"></script> 
<script type="text/javascript" src="/dsd/tp3/Public/admin/js/H-ui.admin.doc.js"></script> 
<!--[if IE 7]>
<link href="lib/font-awesome/font-awesome-ie7.min.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
 ﻿<title>基本设置</title>
</head>
<body>
<nav class="breadcrumb"><i class="iconfont">&#xf012b;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 基本设置  <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
<table class="table table-border table-bordered table-hover table-bg">
  <tbody>
    <tr>
      <th class="text-r" width="100">网站名称：</th><td><input type="text" id="website-title" placeholder="控制在25个字、50个字节以内" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">关键词：</th><td><input type="text" id="website-Keywords" placeholder="5个左右,8汉字以内,用英文,隔开" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">描述：</th><td><input type="text" id="website-description" placeholder="空制在80个汉字，160个字符以内" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">css、js、images路径配置：</th><td><input type="text" id="website-static" placeholder="默认为空，为相对路径" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">上传目录配置：</th><td><input type="text" id="website-uploadfile" placeholder="默认为uploadfile" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">底部版权信息：</th><td><input type="text" id="website-copyright" placeholder="&copy; 2014 H-ui.net" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r">备案号：</th><td><input type="text" id="website-icp" placeholder="京ICP备00000000号" value="" style="width:500px" class="input-text"></td>
    </tr>
    <tr>
      <th class="text-r"></th><td><button name="system-base-save" id="system-base-save" class="btn btn-success radius" type="submit"><i class="icon-ok"></i> 确定</button></td>
    </tr>
  </tbody>
</table>
</div>
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
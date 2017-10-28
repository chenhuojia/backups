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
 ﻿
<link href="/Public/admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<title>后台登录 - H-ui.admin v2.2</title>
<meta name="keywords" content="H-ui.admin v2.2,H-ui网站后台模版,后台模版下载,后台管理系统模版,HTML后台模版下载">
<meta name="description" content="H-ui.admin v2.2，是一款由国人开发的轻量级扁平化网站后台模板，完全免费开源的网站后台管理系统模版，适合中小型CMS后台系统。">
</head>
<body>
<input type="hidden" id="TenantId" name="TenantId" value="" />
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" action="" method="post">
      <div class="row cl">
        <label class="form-label col-3"><i class="iconfont">&#xf00ec;</i></label>
        <div class="formControls col-8">
          <input id="name" name="name" type="text" placeholder="账户" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <label class="form-label col-3"><i class="iconfont">&#xf00c9;</i></label>
        <div class="formControls col-8">
          <input id="password" name="password" type="password" placeholder="密码" class="input-text size-L">
        </div>
      </div>
      <div class="row cl">
        <div class="formControls col-8 col-offset-3">
          <input class="input-text size-L" type="text" name='verify' placeholder="验证码" id='verify'   onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
          <a href="javascript:;" onclick="changes(this)"><img src="<?php echo U('System/Verify');?>" onclick="change(this)" > 看不清，换一张</a> </div>
      </div>
      <div class="row">
        <div class="formControls col-8 col-offset-3">
          <label for="online">
            <input type="checkbox" name="online" id="online" value="">
            使我保持登录状态</label>
        </div>
      </div>
      <div class="row">
        <div class="formControls col-8 col-offset-3">
          <input name="" type="button" onclick="check()" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
    </form>
  </div>
</div>
<div class="footer">Copyright 你的公司名称 by H-ui.admin.V2.2</div>
<script type="text/javascript" src="/Public/admin/lib/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/admin/js/H-ui.js"></script> 
<script src="http://cdn.bootcss.com/blueimp-md5/1.1.0/js/md5.min.js"></script>  
<script>
	function change(obj){
		obj.src="<?php echo U('System/Verify');?>?"+Math.random();
	}
	function changes(obj){
		$(obj).find('img').attr('src',"<?php echo U('System/Verify');?>?"+Math.random());
	}
	function check(){
		var name=$('#name').val();
		var password=$('#password').val();
		var verify=$('#verify').val();
		if(name.length <= 0) {
			alert('请输入昵称');
			return false;
		}
		if(password.length <= 0) {
			alert('请输入密码');
			return false;
		}
		if(verify.length <= 0) {
			//alert('请输入验证码');
			//return false;
		}
		//console.log(md5(password));
		//$('#password').val(md5(password));
		$('.form-horizontal').submit();
	}
</script>

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
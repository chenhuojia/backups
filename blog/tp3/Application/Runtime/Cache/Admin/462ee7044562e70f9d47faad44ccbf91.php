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
 ﻿<script type="text/javascript" src="/Public/admin/lib/My97DatePicker/WdatePicker.js"></script> 
<title>用户管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="iconfont">&#xf012b;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span class="c-gray en">&gt;</span> 用户管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="text-c"> 日期范围：
    <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')||\'%y-%M-%d\'}'})" id="datemin" class="input-text Wdate" style="width:120px;">
    -
    <input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}',maxDate:'%y-%M-%d'})" id="datemax" class="input-text Wdate" style="width:120px;">
    <input type="text" class="input-text" style="width:250px" placeholder="输入会员名称、电话、邮箱" id="" name=""><button type="submit" class="btn btn-success" id="" name=""><i class="icon-search"></i> 搜用户</button>
  </div>
  <div class="cl pd-5 bg-1 bk-gray mt-20">
   <span class="l">
	   	<a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
	   		<i class="icon-trash"></i> 批量删除
	   	</a> 
	   	<a href="javascript:;" onclick="user_add('800','','添加用户','<?php echo U('Admin/User/userAdd');?>')" class="btn btn-primary radius">
	   		<i class="icon-plus"></i> 添加用户
	   	</a>
   	</span>
  </div>
  <table class="table table-border table-bordered table-hover table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th width="25"><input type="checkbox" name="" value=""></th>
        <th width="80">ID</th>
        <th width="100">用户名</th>
        <th width="40">性别</th>
        <th width="90">手机</th>
        <th width="150">邮箱</th>
        <th width="">地址</th>
        <th width="130">加入时间</th>
        <th width="130">最后登录时间</th>
        <th width="70">状态</th>
        <th width="100">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
	        <td><input type="checkbox" value="1" name=""></td>
	        <td><?php echo ($v["user_id"]); ?></td>
	        <td><u style="cursor:pointer" class="text-primary" onclick="user_show('10001','360','','张三','<?php echo U('Admin/User/userShow');?>')"><?php echo ($v['name']); ?></u></td>
	        <td>男</td>
	        <td><?php echo ($v['phone']); ?></td>
	        <td><?php echo ($v["email"]); ?></td>
	        <td class="text-l"><?php echo ($v["contact_address"]); ?></td>
	        <td><?php echo (date('Y-m-d H:i:s',$v["addtime"])); ?></td>
	        <td><?php echo (date('Y-m-d H:i:s',$v["login_time"])); ?></td>
	        <td class="user-status">
	        	<?php if(($v['is_show']) == "1"): ?><span class="label label-success radius">已启用</span>
	        	<?php else: ?>
	        		<span class="label label-fail radius">已关闭</span><?php endif; ?>
	        </td>
	        <td class="f-14 user-manage">
	       	 <a style="text-decoration:none" onClick="user_stop(this,'10001')" href="javascript:;" title="停用"><i class="icon-hand-down"></i></a>
	         <a title="编辑" href="javascript:;" onclick="user_edit('4','600','','编辑','<?php echo U('Admin/User/userAdd');?>')" class="ml-5" style="text-decoration:none"><i class="icon-edit"></i></a> 
	         <a style="text-decoration:none" class="ml-5" onClick="user_password_edit('10001','600','240','修改密码','<?php echo U('Admin/User/userPasswordEdit');?>')" href="javascript:;" title="修改密码"><i class="icon-key"></i></a>
	         <a title="删除" href="javascript:;" onclick="user_del(this,'1')" class="ml-5" style="text-decoration:none"><i class="icon-trash"></i></a></td>
      	</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
  </table>

</div>
<div class="green-black"><?php echo ($page); ?><div/>
<script type="text/javascript">
$('.table-sort').dataTable({
	"lengthMenu":false,//显示数量选择 
	"bFilter": false,//过滤功能
	"bPaginate": false,//翻页信息
	"bInfo": false,//数量信息
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,8,9]}// 制定列不参与排序
	]
});
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
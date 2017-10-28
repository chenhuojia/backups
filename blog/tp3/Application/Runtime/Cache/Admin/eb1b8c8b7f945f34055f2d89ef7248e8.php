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
<title>文章管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="iconfont">&#xf012b;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span class="c-gray en">&gt;</span> 用户管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <div class="text-c"> 日期范围：
    <input type="text" onfocus="WdatePicker({maxDate:'#F{$dp.$D(\'datemax\')}'})" id="datemin" class="input-text Wdate" style="width:120px;">
    -
    <input type="text" onfocus="WdatePicker({minDate:'#F{$dp.$D(\'datemin\')}'})" id="datemax" class="input-text Wdate" style="width:120px;">
    <input type="text" class="input-text" style="width:250px" placeholder="输入标题、分类名称" id="searchName" name="">
    <button type="submit" class="btn btn-success" id="search" name="">
    <i class="icon-search"></i> 搜用户</button>
  </div>
  <div class="cl pd-5 bg-1 bk-gray mt-20">
   	<span class="l">
	   <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
	   		<i class="icon-trash"></i> 批量删除
	   </a> 
	   <a href="<?php echo U('Admin/Blog/add');?>"  class="btn btn-primary radius">
	  	<i class="icon-plus"></i> 添加文章
	   </a>
  	</span>
  </div>
  <table class="table table-border table-bordered table-hover table-bg table-sort">
    <thead>
      <tr class="text-c">
        <th width="25"><input type="checkbox" name="" value="0" id='checkedAll'></th>
        <th >ID</th>
        <th >标题</th>
        <th >分类名称</th>
        <th >评论数</th>
        <th >点赞数</th>
        <th >发表时间</th>
        <th >状态</th>
        <th >操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
		        <td><input type="checkbox" value="<?php echo ($v["art_id"]); ?>" name="" class='checkbox'></td>
		        <td><?php echo ($v['art_id']); ?></td>
		        <!--<td><u style="cursor:pointer" class="text-primary" onclick="user_show('10001','360','','张三','<?php echo U('Admin/User/userShow');?>')">张三</u></td>-->
		        <td><?php echo ($v['title']); ?></td>
		        <td><?php echo ($v["name"]); ?> / <?php echo ($v["english"]); ?></td>
		        <td><?php echo ($v["discuss_total"]); ?></td>
		        <td><?php echo ($v["approve_total"]); ?></td>
		        <td><?php echo (date('Y-m-d H:i:s',$v['addtime'])); ?></td>
		        <td class="user-status"><?php if(($v['is_show']) == "1"): ?><span class="label label-success radius">已启用</span><?php else: ?> <span class="label label-fail radius">已关闭</span><?php endif; ?></td>
		        <td class="f-14 user-manage">
		        	<?php if(($v['is_show']) == "1"): ?><a style="text-decoration:none" onClick="article_stop(this,<?php echo ($v['art_id']); ?>)" href="javascript:;" title="停用">
			        		<i class="icon-hand-down"></i>
			        	</a>
		        	<?php else: ?>
		        		<a style="text-decoration:none" onClick="article_start(this,<?php echo ($v['art_id']); ?>)" href="javascript:;" title="启用">
			        		<i class="icon-hand-up"></i>
			        	</a><?php endif; ?>
			        <a title="编辑" href="javascript:;" onclick="user_edit('4','1024','700','编辑','<?php echo U('Admin/Blog/edit',array('art_id'=>$v['art_id']));?>')" class="ml-5" style="text-decoration:none">
			        	<i class="icon-edit"></i>
			        </a> 		         
			        <a title="删除" href="javascript:;" onclick="article_del(this,'1')" class="ml-5" style="text-decoration:none">
			        	<i class="icon-trash"></i>
			        </a>
			        <a title="查看" href="https://www.chenhuojia.xin/index.php/Detial/detial/id/<?php echo ($v['art_id']); ?>"  class="ml-5" style="text-decoration:none">
			        	<i class="icon-eye-open"></i>
			        </a>
		        </td>
      		</tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </tbody>
  </table>
</div>
<div class="green-black"><?php echo ($page); ?><div/>
<script>
	function article_stop(obj,art_id){
		var _this=$(obj);
		if(art_id <=0 ){
			return false;
		}else{
			$.ajax({
				url:"<?php echo U('Admin/Ajax/stopArticle');?>",
				type:'post',
				datatype:'json',
				data:'art_id='+art_id,
				success:function(e){					
					_this.parent().prev().html('<span class="label label-fail radius">已关闭</span>');
					_this.find('i').attr('class','icon-hand-up');
					_this.attr('onClick',"article_start(this,<?php echo ($v['art_id']); ?>)")
					alert('停用成功');					
				}
			})
		}
	}
	
	function article_start(obj,art_id){
		var _this=$(obj);
		if(art_id <=0 ){
			return false;
		}else{
			$.ajax({
				url:"<?php echo U('Admin/Ajax/startArticle');?>",
				type:'post',
				datatype:'json',
				data:'art_id='+art_id,
				success:function(e){					
					_this.parent().prev().html('<span class="label label-success radius">已启用</span>');
					console.log(_this.find('i').attr('class','icon-hand-down'));
					_this.attr('onClick',"article_stop(this,<?php echo ($v['art_id']); ?>)")
					alert('启用成功');
					
				}
			})
		}
	}
	
	function article_del(obj,art_id){
		var _this=$(obj);
		if(art_id <=0 ){
			return false;
		}else{
			$.ajax({
				url:"<?php echo U('Admin/Ajax/delArticle');?>",
				type:'post',
				datatype:'json',
				data:'art_id='+art_id,
				success:function(e){
					if(e==0){
						alert('删除失败');
					}if(e==1){
						_this.parent().parent().remove();
						alert('删除成功');
					}
				}
			})
		}
	}
	
	var arr=new Array();
	$("#checkedAll").click(function() {
		var checked=$(this).val();
		if(checked==0){
			$(this).val(1);
			$(".checkbox").each(function() {
				$(this).attr("checked", true);
				var result=arr.indexOf($(this).val());
				if(result == -1){
					arr.push($(this).val());
				}
			});
		}else { // 取消全选
			$(this).val(0);
			arr=[];
			$(".checkbox").each(function() {
				$(this).attr("checked", false);
			});
		} 
	});
	
	$(".checkbox").click(function(){
		var val=$(this).val();
		var result=arr.indexOf(val);
		var state=$(this).attr('checked');
		if(state=='checked'){
			if(result > -1){
				arr.splice(result,1)
			}
			$(this).attr("checked", false);
		}else{
			arr.push(val);
			$(this).attr('checked',true);
		}
	})
	/*批量删除*/
	function datadel(){
		layer.confirm('确认要删除吗？',function(index){
			if(arr.length == 0){
				layer.msg('请选择删除的文章!',1);
				return false;
			}else{
				var obj = JSON.stringify(arr);
				$.ajax({
					url:"<?php echo U('Ajax/delAllArtile');?>",
					type:'post',
					dataType: "json",
					data:'data='+obj,
					success:function(e){
						if(e > 0){
							layer.msg('删除文章成功!',1);
							location.reload();   
						}else{
							layer.msg('删除文章失败!',1);
						}
					}
				});
				

				
			}
		});
	}
	
	var datemin=0;
	var datemax=0;
	$('#datemin').focus(function(){
		datemin=$(this).val();
		console.log(datemin);
	});
	
	$('#datemax').focus(function(){
		datemax=$(this).val();
		console.log(datemax);
	});
	
	$('#search').on('click',function(){
		var searchName=$('#searchName').val();
		if(searchName.length == 0){
			searchName='0';
		}
		if(datemin.length !=0){
			searchName += '&&datamin='+datemin;
		}
		if(datemax.length !=0){
			searchName += '&&datamax='+datemax;
		}
		window.location.href="<?php echo U('Blog/article');?>?searchName="+searchName;
		console.log(searchName.length);
	})
  
	
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
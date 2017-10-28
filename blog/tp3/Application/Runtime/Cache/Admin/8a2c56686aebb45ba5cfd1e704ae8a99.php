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
 <title>分类管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="iconfont">&#xf012b;</i> 首页 <span class="c-gray en">&gt;</span> 资讯管理 <span class="c-gray en">&gt;</span> 分类管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20 text-c">
  <form class="Huiform" action="/" method="post">
    上级栏目： <span class="select-box" style="width:150px">
    <select class="select" id="sel_Sub" name="sel_Sub" onchange="">
      <option value="0">顶级分类</option>
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["cate_id"]); ?>"><?php echo ($v["name"]); ?> / <?php echo ($v["english"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    </select>
    </span>
    <input type="hidden" id="hid_ccid" value="">
    <input class="input-text" style="width:250px" type="text" value="" placeholder="输入分类" id="article-class-val">
    <button type="button" class="btn btn-success" id="" name="" onClick="category_add(this);">
    <i class="icon-plus"></i> 添加</button>
  </form>
  <div class="cl pd-5 bg-1 bk-gray mt-20">
   	<span class="l">
	   <a href="javascript:;" onclick="datadel()" class="btn btn-danger radius">
	   		<i class="icon-trash"></i> 批量删除
	   </a> 	   
  	</span>
  </div>
  <div class="article-class-list cl mt-20">
    <table class="table table-border table-bordered table-hover table-bg">
      <thead>
        <tr class="text-c">
          <th width="25"><input type="checkbox" name="" value="0" id='checkedAll'></th>
          <th >ID</th>
          <th >排序</th>
          <th>分类名称</th>
          <th >操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><tr class="text-c">
	          <td><input type="checkbox" value="<?php echo ($v["cate_id"]); ?>" name="" class='checkbox'></td>
	          <td><?php echo ($v["cate_id"]); ?></td>
	          <td><?php echo ($v["sort"]); ?></td>
	          <td class="text-l"><?php echo ($v["name"]); ?> / <?php echo ($v["english"]); ?></td>
	          <td class="f-14"><a title="编辑" href="javascript:;" onclick="article_class_edit('1','1000','560','分类编辑','<?php echo U('Admin/Category/categoryEdit',array('cate_id'=>$v['cate_id']));?>')" style="text-decoration:none"><i class="icon-edit"></i></a> 
	          <a title="删除" href="javascript:;" onclick="category_del(this,<?php echo ($v["cate_id"]); ?>)" class="ml-5" style="text-decoration:none"><i class="icon-trash"></i></a></td>
        	</tr><?php endforeach; endif; else: echo "" ;endif; ?>       
      </tbody>
    </table>
  </div>
  <div class="green-black"><?php echo ($page); ?><div/>
</div>
<script>
 function category_add(obj){
	 var parent_id=$('#sel_Sub').val();
	 var val=$(obj).prev().val();
	 if(val.length > 0){
		 $.ajax({
			 url:"<?php echo U('Admin/Ajax/categoryAdd');?>",
			 type:'post',
			 datatype:'json',
			 data:'name='+val+'&&parent_id='+parent_id,
			 success:function(e){
				 if(e){
					 	layer.msg('添加成功!',1);
						window.location.reload(); 
				 }
			 }
		 })
	 }else{
		 layer.msg('请填写分类名称',1);
	 }
 }

	
	function category_del(obj,art_id){
		var _this=$(obj);
		if(art_id <=0 ){
			return false;
		}else{
			$.ajax({
				url:"<?php echo U('Admin/Ajax/delCategory');?>",
				type:'post',
				datatype:'json',
				data:'cate_id='+art_id,
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
				layer.msg('请选择删除的分类!',1);
				return false;
			}else{
				var obj = JSON.stringify(arr);
				$.ajax({
					url:"<?php echo U('Ajax/delAllCategory');?>",
					type:'post',
					dataType: "json",
					data:'data='+obj,
					success:function(e){						
						if(e > 0){
							layer.msg('删除分类成功!',1);
							location.reload();   
						}else{
							layer.msg('删除分类失败!',1);
						}
					}
				});
			}
		});
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
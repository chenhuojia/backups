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
 ﻿</head>
<body>
<nav class="breadcrumb"><i class="iconfont">&#xf012b;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span class="c-gray en">&gt;</span> 用户管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="icon-refresh"></i></a></nav>
<div class="pd-20">
  <form action="">
  		<form action="{:U('Admin/BookAction/AddBook')}" class="form-horizontal" method="post" enctype ="multipart/form-data">
    <div class="form-group">
        <label for="father_id" class="col-sm-2 control-label">图书类型</label>
        <div class="col-sm-2">      
            <select name="father_id[]" id="father_id" class="form-control father_id">
                <option value="9999">请选择</option>                
                <?php if(is_array($book)): $i = 0; $__LIST__ = $book;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="{$vo.cg_id}">{$vo.name}</option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <div class="col-sm-5"></div>
    </div>
    <div id="add"></div>
    <button type="button" onclick="add()">添加分类</button>
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
	                    <?php if(is_array($user)): $i = 0; $__LIST__ = $user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="{$v.user_id}">{$v.name}</option><?php endforeach; endif; else: echo "" ;endif; ?>                 
	            </select>
	        </div> 
	   	 </div>
	   	 <div class="form-group" id="shop">
        	<label for="vi_long" class="col-sm-2 control-label">店铺</label>
	        <div class="col-sm-10">
	           <select name="shop"  class="form-control">                            	
	                    <option value="0">请选择</option>
	                    <?php if(is_array($shop)): $i = 0; $__LIST__ = $shop;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="{$v.shop_id}">{$v.username}-->{$v.shop_name}</option><?php endforeach; endif; else: echo "" ;endif; ?>                 
	            </select>
	        </div> 
	   	 </div>
	   	 <div class="form-group" id='kucun'>
	        <label for="link" class="col-sm-2 control-label">库存</label>
	        <div class="col-sm-10">
	            <input class="form-control" type="text" value="" id="total" name="total" />
	        </div>
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
	            	<?php if(is_array($age)): $i = 0; $__LIST__ = $age;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value='{$v.id}'>{$v.name}</option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>	    
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">语种</label>
	        <div class="col-sm-10">
	           <select name="language"  class="form-control">                
	                <?php if(is_array($lang)): $i = 0; $__LIST__ = $lang;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option >{$v}</option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">装帧</label>
	        <div class="col-sm-10">
	           <select name="binding"  class="form-control" id="binding">              
	                <?php if(is_array($binding)): $i = 0; $__LIST__ = $binding;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="{$v}">{$v}</option><?php endforeach; endif; else: echo "" ;endif; ?>
	            </select>
	        </div>
	    </div>
	    <div class="form-group">
        <label for="vi_long" class="col-sm-2 control-label">纸张</label>
	        <div class="col-sm-10">
	           <select name="paper"  class="form-control">               
	                <?php if(is_array($paper)): $i = 0; $__LIST__ = $paper;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option>{$v}</option><?php endforeach; endif; else: echo "" ;endif; ?>
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
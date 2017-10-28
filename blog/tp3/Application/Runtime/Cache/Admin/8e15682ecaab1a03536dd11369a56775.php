<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>添加文章</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/Public/admin2/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/Public/admin2/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin2/css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/Public/admin2/css/animate.css" rel="stylesheet">
    <link href="/Public/admin2/css/style.css?v=4.1.0" rel="stylesheet">
    <script type="text/javascript" src="/Public/js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="/Public/js/jquery.min.js"></script>
	<script type="text/javascript" src="/Public/ueditor1.4.3/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/Public/ueditor1.4.3/ueditor.all.js"></script>
    <script type="text/javascript" src="/Public/js/ajaxfileupload.js"></script>
    <!-- 实例化编辑器 -->
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">        
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>所有表单元素 <small>包括自定义样式的复选和单选按钮</small></h5>                        
                    </div>
                    <div class="ibox-content">
                        <form method="post"  class="form-horizontal">                           
                            <div class="form-group">
                                <label class="col-sm-2 control-label">标题</label>

                                <div class="col-sm-10">
                                    <input type="text" placeholder="请输入标题" class="form-control" name="title">
                                	<span class="help-block m-b-none" style="display:none">请输入标题</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>                                                       
                            <div class="form-group">
                                <label class="col-sm-2 control-label">分类</label>
                                <div class="col-sm-10">
                                    <div class="col-sm-4 m-l-n">
                                        <select class="form-control" name="cate_id">
                                        	<?php if(is_array($category)): $i = 0; $__LIST__ = $category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><option value="<?php echo ($v["cate_id"]); ?>"><?php echo ($v["name"]); ?> // <?php echo ($v["english"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <span class="help-block m-b-none" style="display:none">请选择分类</span>
                                    </div>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group has-success">
                                <label class="col-sm-2 control-label">简介</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="introduction">
                                    <span class="help-block m-b-none" style="display:none">请输入简介</span>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group has-error">
                                <label class="col-sm-2 control-label">内容</label>
                                <div class="col-sm-10">
                                    <script id="container" name="content" type="text/plain" style="height:500px"> 
  
    								</script>
    								<span class="help-block m-b-none" style="display:none">请输入内容</span>
                              </div>  
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-white"  onclick="upImage()" type="button">封面</button>
                                    <input type='file' name="img" id="upload" style="display:none"></input>
                                    <input type='text' name="cover" id="cover" style="display:none"></input>
                                    <img src="" style="width:100px;height:100px;display:none;" id='cove'>
                                </div>
                            </div>                           
                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary submit" type="submit">保存内容</button>
                                    <button class="btn btn-white" type="reset">取消</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
var ue = UE.getEditor('container');

function upImage() {
  $('#upload').click();  
}
$(document).on('change','#upload',function(){
	var val=$('#cover').val();
	ajaxFileUpload();
})
function ajaxFileUpload() {
    $.ajaxFileUpload
    (
        {
            url: "<?php echo U('Admin/Ajax/upload');?>", //用于文件上传的服务器端请求地址
            secureuri: false, //是否需要安全协议，一般设置为false
            fileElementId: 'upload', //文件上传域的ID
            dataType: 'json', //返回值类型 一般设置为json
            success: function (data, status)  //服务器成功响应处理函数
            {
            	$('#cover').val(data);
            	$('#cove').css({display:'block'}).attr('src',data);
            },
            error: function (data, status, e)//服务器响应失败处理函数
            {
                alert(e);
            }
        }
    )
}
</script>
<script>
$(function(){
    var ok1=false;
    var ok2=false;
    var ok3=false;
    var ok4=false;
    // 验证标题
    $('input[name="title"]').focus(function(){
    	$(this).next().css({'display':'block'});
    }).blur(function(){
        if($(this).val().length >= 3 && $(this).val().length <=50 && $(this).val()!=''){ 
        	$(this).next().css({'display':'none'});
            ok1=true;
        }else{
        	$(this).next().css({'display':'block'});
        }
         
    });
    //验证分类
    if($('select[name="cate_id"]').val() != ''){
    	$('select[name="cate_id"]').next().css({'display':'none'});
    	ok2=true;
    }else{
    	$('select[name="cate_id"]').next().css({'display':'block'});
    }    
    //验证简介
    $('input[name="introduction"]').focus(function(){
    	$(this).next().css({'display':'block'});
    }).blur(function(){
        if($(this).val()!=''){
        	$(this).next().css({'display':'none'});
            ok3=true;
        }else{
        	$(this).next().css({'display':'block'});
        }
    });

    //提交按钮,所有验证通过方可提交

    $('.submit').click(function(){
        if(ok1 && ok2 && ok3 ){
            $('form').submit();
        }else{
            return false;
        }
    });
     
});
</script>
</body>
</html>
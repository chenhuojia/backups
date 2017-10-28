<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>Detial</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/kts-v3/Public/other/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/kts-v3/Public/other/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/kts-v3/Public/other/css/animate.css" rel="stylesheet">
    <link href="/kts-v3/Public/other/css/style.css?v=4.1.0" rel="stylesheet">

</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content  animated fadeInRight">
        <div class="row">

            <div class="col-sm-6" style="left:25%;">
                <div class="ibox">
                    <div class="ibox-content text-center">
						<a href="<?php echo U('Admin/Book/bookDet',array('book_id'=>$data['book']['book_id']));?>">
		                     <img src="<?php echo ($data["book"]["cover_img"]); ?>" style="width:50px;height:50px;">  <span style="font-size:16px;margin-left:13px;"><?php echo ($data["book"]["name"]); ?></span>		                     
		                     <span style="display:block; margin-left:75px;margin-top:-15px;"><?php echo ($data["book"]["author"]); ?></span>
						</a>
        
                    </div>
					  
                </div>
				<?php if(is_array($data["list"])): $i = 0; $__LIST__ = $data["list"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><div class="social-feed-box">
	                    <div class="pull-right social-action dropdown">
	                        <button data-toggle="dropdown" class="dropdown-toggle btn-white">
	                            <i class="fa fa-angle-down"></i>
	                        </button>
	                        <ul class="dropdown-menu m-t-xs">
	                            <li><a href="#" onclick="show(<?php echo ($v["comment_id"]); ?>)">回复</a></li>
	                        </ul>
	                    </div>
	                    <div class="social-avatar">
	                        <a href="" class="pull-left">
	                            <img alt="image" src="<?php echo ($data["user_avatar"]); ?>">
	                        </a>
	                        <div class="media-body">
	                            <a href="#">
	                            			<?php echo ($v["username"]); ?>
	                                    </a>
	                            <small class="text-muted"><?php echo (date("Y-m-d H:i:s",$v["comment_time"])); ?></small>
	                        </div>
	                    </div>
	                    <div class="social-body">
	                    	
	                    	<h4><?php echo ($v["title"]); ?></h4>
	                        <p>
	                          	<?php echo ($v["content"]); ?>
	                        </p>
							<ul >
								<?php if(is_array($v["image"])): $i = 0; $__LIST__ = $v["image"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vv): $mod = ($i % 2 );++$i;?><li style='width:80px;height:80px; list-style:none;float:left'> <img src='<?php echo ($vv); ?>' style='width:80px;height:80px;'></li><?php endforeach; endif; else: echo "" ;endif; ?>
								<li style="clear:both;list-style:none;"></li>
							</ul>
	                        <div class="btn-group">
	                            <button class="btn btn-white btn-xs"><i class="fa fa-thumbs-up"></i><?php echo ($v["like_num"]); ?> 赞</button>
	                            <button class="btn btn-white btn-xs"><i class="fa fa-comments"></i><?php echo ($v["sums"]); ?>评论</button>
	                            <!--<button class="btn btn-white btn-xs"><i class="fa fa-share"></i> 分享</button>  -->
	                        </div>
	                    </div>
	                    <div class="social-footer">
	                        <?php if($v["child"] != null): if(is_array($v["child"])): $i = 0; $__LIST__ = $v["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vvv): $mod = ($i % 2 );++$i;?><div class="social-comment">
			                            <a href="" class="pull-left">
			                                <img alt="image" src="<?php echo ($vvv["user_avatar"]); ?>">
			                            </a>		                            
			                            <div class="media-body">
			                                <a href="#"><?php echo ($vvv["user_name"]); ?></a> 
			                                	<?php echo ($vvv["content"]); ?>
			                 					<a style='float:right;' onclick="show(<?php echo ($v["comment_id"]); ?>)">回复</a>
			                                <br/>
			                         
			                                <small class="text-muted"><?php echo (date("Y-m-d H:i:s",$v["addtime"])); ?></small>
			                            </div>
			                            
			                         </div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
	                        <style>
	                        	#show{position:absolute;z-index:20;left:200px;top:150px;display:none;}
	                        </style>
	                         <div class="social-comment" id='show'>
	                            <div class="media-body" >
	                                <textarea class="form-control" placeholder="填写评论..." id='text' style="margin: 0px; width: 529px; height: 102px;"></textarea>
	                            </div>
	                            <button id='sumbits'>提交</button>
	                            <button onclick="hide()">取消</button>
	                        </div>
	                        <?php if($data["comment_id"] != null): ?><div class="social-comment">
		                            <div class="media-body" >
		                                <textarea class="form-control" placeholder="填写评论..." id='texts' style="margin: 0px; width: 529px; height: 102px;"></textarea>
		                            </div>
		                            <button id='submit'>提交</button>
	                        	</div><?php endif; ?>
	                    </div>
	                </div><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="/kts-v3/Public/other/js/jquery.min.js?v=2.1.4"></script>
    <script src="/kts-v3/Public/other/js/bootstrap.min.js?v=3.3.6"></script>

	
    <!-- 自定义js -->
    <script src="/kts-v3/Public/other/js/content.js?v=1.0.0"></script>

<div class="gohome"><a class="animated bounceInUp" href="javascript:history.go(-1)" title="返回"><i class="fa fa-home"></i></a></div>;

<script>

	function show(id){
		$('#show').slideDown('normal');
		$('#sumbits').click(function(){
			submit(id)
		})
	}

	function hide(){
		$('#show').slideUp('normal');
	}
	
	function submit(id){
		var content=$('#text').val();
		if(id>0 && content){
			$.ajax({
				url:"<?php echo U('Admin/BookComment/Bookreply');?>",
				type:'post',
				data:'id='+id+'&content='+content+"&type=1",
				datatype:'json',
				success:function(e){
					console.log(e);
					if(e==1){
						 window.history.go(0);
					}
				}
			})
		}
	}
	
	$('#submit').click(function(){
		var content=$('#texts').val();
		var id=<?php echo ($data['comment_id']?$data['comment_id']:0); ?>;
		if(id>0 && content){
			$.ajax({
				url:"<?php echo U('Admin/BookComment/Bookreply');?>",
				type:'post',
				data:'id='+id+'&content='+content+"&type=1",
				datatype:'json',
				success:function(e){
					if(e==1){
						 window.history.go(0);
					}
				}
			})
		}
	})
		
	
</script>
    
    

</body>

</html>
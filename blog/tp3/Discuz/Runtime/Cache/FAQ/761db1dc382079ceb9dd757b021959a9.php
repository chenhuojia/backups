<?php if (!defined('THINK_PATH')) exit();?> 
    <link href="/Public/admin2/css/style.css?v=4.1.0" rel="stylesheet">
    <div class="row">
        <div class="col-sm-12">
            <div class="wrapper wrapper-content animated fadeInUp">
                <ul class="notes">
                	<?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li>
	                        <div>
	                            <small><?php echo (date("Y年m月d日  下午g:i",$v["addtime"])); ?> (星期<?php echo ($v["week"]); ?>)</small>
	                            <h4><?php echo ($v["name"]); ?></h4>
	                            <p><?php echo ($v["content"]); ?></p>
	                            <!--<a href="pin_board.html#"><i class="fa fa-trash-o "></i></a>-->
	                        </div>
                    	</li><?php endforeach; endif; else: echo "" ;endif; ?>                
                </ul>
            </div>
        </div>
    </div>
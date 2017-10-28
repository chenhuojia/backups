<?php defined('API') or exit('');?>
<!--欢迎页-->
<!--info start-->
<?php 
if(is_supper()){
echo "<a href='?act=notify'>通知</a>";
}else {
echo '通知';
}
?>
<div style="font-size: 18px;">
    <?php 
      $page=isset($_GET['page'])>0?$_GET['page']:1;
      $off=($page-1)*5;
      //查消息
      $uerid=session('id');
      //$sql="SELECT message.content,message.url,message.addtime,msgstatus.msgstatus FROM message,msgstatus WHERE message.id=msgstatus.msgid  AND msgstatus.userid=$uerid ORDER BY message.id DESC LIMIT $off,5";
      $sql="SELECT message.content,message.url,message.addtime,msgstatus.msgstatus FROM message,msgstatus WHERE message.id=msgstatus.msgid  AND msgstatus.userid=$uerid ORDER BY message.id DESC LIMIT $off,5";
    	$re=select($sql);
    foreach ((array)$re as $msg){
    $msg['addtime'] = date('Y-m-d h:i:s',$msg['addtime']);
    echo "<div class='info' style='font-size: 14px;'><a href='{$msg['url']}'>{$msg['addtime']}--{$msg['content']}</a></div>";
    }
    	
    	?>
	
	<div align="center"><a href="/index.php?page=<?php echo $page-1;?>">上一页 </a> <a href="/index.php?page=<?php echo $page+1;?>">下一页</a></div>
	<div
		style="font-size: 12px; position: absolute; bottom: 0; right: 20px; height: 20px; text-align: right;">
		can| qq : 673081689</div>
</div>
<!--欢迎页 end-->
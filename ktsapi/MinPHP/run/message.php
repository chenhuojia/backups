<?php defined('API') or exit('');?>
<?php 
$op = $_GET['op'];
$_VAL = I($_POST);
$te=$_VAL['content'];
$url=$_VAL['url'];
$addtime=time();
if(empty($te)&&strtolower($_SERVER['REQUEST_METHOD'])=='post'){
	echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>内容不能为空</div>';
    $op='';
}
if($op == 'add'){
M()->autocommit(false);
$sql="INSERT INTO message(message.content,message.url,message.addtime) VALUES('$te','$url','$addtime')";
$re=insert($sql);
$id=last_insert_id();
$find_user_sql="SELECT`user`.id FROM `user` WHERE `user`.`delete`=0";
$userids=select($find_user_sql);
send_msg($userids,$id);
if(!M()->errno){
	M()->commit();
	//go('/');
    go('/');
}else{
	M()->rollback();
	echo '<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> 添加失败</div>';
}
}
?>
<div style="border:1px solid #ddd">
    <div style="background:#f5f5f5;padding:20px;position:relative">
        <div>
            <form action="?act=notify&op=add" method="post">
                <div class="form-group">
                    <textarea class="form-control" rows="3"  name="content" required="required"><?php echo $te ;?></textarea>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" value='<?php echo $url;?>' name="url" placeholder="url">
                </div>
                <button class="btn btn-success">Submit</button>
            </form>
        </div>
    </div>
</div>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <link href="/Public/admin2/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Public/admin2/css/font-awesome.css?v=4.4.0" rel="stylesheet">
    <link href="/Public/admin2/css/animate.css" rel="stylesheet">
    <link href="/Public/admin2/css/style.css" rel="stylesheet">
    <link href="/Public/admin2/css/login.css" rel="stylesheet">
    <script src="/Public/js/jquery.min.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="/Public/images/logo2.png" />
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>
        if (window.top !== window.self) {
            //window.top.location = window.location;
        }
    </script>

</head>

<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-12">
                <form method="post" action=" ">
                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md">登录到H+后台主题UI框架</p>
                    <input type="text" class="form-control uname" name='name' placeholder="用户名" />
                    <input type="password" class="form-control pword m-b" placeholder="密码" />
                    <a href="javascript:forgetpwd();">忘记密码了？</a> <a href="<?php echo U('Login/phoneLogin');?>">短信登录</a>
                    <button class="btn btn-success btn-block" type='button' >登录</button>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                &copy; hAdmin
            </div>
        </div>
    </div>
</body>
<script>
	$('.btn-block').click(function(){
		
		var name=$('.uname').val();
		if(name == "" || name == undefined || name == null){
	  			alert('用户名为空');
	  			return false;
	  			}
	  		var password=$('.pword').val();
	  		if(password == "" || password == undefined || password == null){
	  			alert('密码为空');
  				return false;
  			}
	  	  	$.ajax({
	  	  		url:"<?php echo U('Home/Login/ajaxlogin');?>",
	  	  		type:'post',
	  	  		data:'name='+name+'&password='+password,
	  	  		datatype:'json',
	  	  		success:function(e){
	  	  			console.log(e);
	  	  			if(e==1){
	  	  				self.location=document.referrer
	  	  			}if(e==0){
	  	  				alert('密码错误!请重新登录');
	  	  				history.go(0);
	  	  			}
	  	  		}
	  	  	}) 
			
	})

</script>
</html>
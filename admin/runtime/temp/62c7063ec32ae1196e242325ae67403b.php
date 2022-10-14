<?php /*a:1:{s:62:"/www/wwwroot/www/admin/application/admin/view/login/index.html";i:1661769527;}*/ ?>
<!DOCTYPE html>
<html>
<head>
	<title>欣悦相约</title>
	<link rel="stylesheet" type="text/css" href="/static/admin/login/css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="https://www.layuicdn.com/layui/css/layui.css" />
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="/static/admin/login/img/wave.png">
	<div class="container">
		<div class="img">
			<img src="/static/admin/login/img/bg.svg">
		</div>
		<div class="login-content">
			<form id="loginform">
				<img src="/static/admin/login/img/avatar.svg">
				<h2 class="title">欣悦相约</h2>
           		<div class="input-div one">
           		   <div class="i">
           		   		<i class="fas fa-user"></i>
           		   </div>
           		   <div class="div">
           		   		<h5>用户名</h5>
           		   		<input type="text" name="username" id="username" class="input">
           		   </div>
           		</div>
           		<div class="input-div pass">
           		   <div class="i"> 
           		    	<i class="fas fa-lock"></i>
           		   </div>
           		   <div class="div">
           		    	<h5>密码</h5>
           		    	<input type="password" name="password" id="password" class="input">
            	   </div>
            	</div>
            	<!--<a href="#">忘记密码?</a>-->
            	<input type="button" id="login" class="btn" value="登 陆">
            </form>
        </div>
    </div>
    <script src="https://cdn.staticfile.org/jquery/2.0.0/jquery.min.js"></script>
    <script type="text/javascript" src="/static/admin/login/js/main.js"></script>
    <script src="https://www.layuicdn.com/layui/layui.js"></script>
    <script type="text/javascript" charset="utf-8">
        $("#login").click(function(){
            var username = $('#username').val();
            var password = $('#password').val();
            if(username == "" || username == null || username == undefined){
                layer.msg("管理员账号不能为空");
                return false;
            }      
            if(password == "" || password == null || password == undefined){
                layer.msg("管理员密码不能为空");
                return false;
            }    
            $.ajax({
                type: 'post',
                url: "<?php echo url('doLogin'); ?>",
                data:$("#loginform").serialize(),
                dataType:"json",
                success: function(data) {
                    if(data.code === 200){
                        layer.msg(data.msg, {
                          icon: 1,
                          time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                          location.reload();
                        });  
                        
                    }else{
                        layer.msg(data.msg, {
                          icon: 2,
                          time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                            
                        });
                    }  
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    layer.closeAll();
                    if (textStatus == "timeout") {
                        layer.msg('请求超时！', {
                          icon: 2,
                          time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                        });                      
                    } else {
                        layer.msg('服务器错误！', {
                          icon: 2,
                          time: 1500 //2秒关闭（如果不配置，默认是3秒）
                        }, function(){
                        });                         
                    }
                },                    
            });  
        });
    </script>   
</body>
</html>

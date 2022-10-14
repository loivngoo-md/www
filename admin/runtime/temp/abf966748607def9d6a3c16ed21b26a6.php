<?php /*a:4:{s:65:"/www/wwwroot/www/admin/application/admin/view/user/operation.html";i:1638604914;s:62:"/www/wwwroot/www/admin/application/admin/view/public/meta.html";i:1638604914;s:63:"/www/wwwroot/www/admin/application/admin/view/public/style.html";i:1638604914;s:68:"/www/wwwroot/www/admin/application/admin/view/public/onlyfooter.html";i:1638604914;}*/ ?>
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<title>密獾娱乐</title>
<link rel="icon" href="favicon.ico" type="image/ico">
<meta name="author" content="yinqi">

<link href="/static/admin/index/css/bootstrap.min.css" rel="stylesheet">
<link href="/static/admin/index/css/materialdesignicons.min.css" rel="stylesheet">
<link href="/static/admin/index/css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://www.layuicdn.com/layui/css/layui.css" />
<style type="text/css" media="all">
.layui-form-label {
    width: 100px;
}
.layui-layout{
    margin-top: 10px;
}
.sidebar-header {
    height: 70px;
}
.sidebar-header a {
    font-size: 30px;
    color:#fff;
    line-height: 68px;
}

</style>
</head>
  
<body data-logobg="color_8" data-sidebarbg="color_8">
<div class="layout-web">
  <div class="layout-container">
    <!--左侧导航-->

<main class="layui-layout">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4>
							<?php if($operation == 'add'): ?>添加管理员<?php else: ?>编辑管理员<?php endif; ?>
						</h4>
					</div>
					<div class="card-body">
						<form class="layui-form" id="loginfrom">
						    <input type="hidden" id="id" name="id" value="<?php echo isset($info['id'])?$info['id']:''; ?>">
							<div class="form-group">
								<label>
									管理员账号
								</label>
								<input class="form-control" type="text" id="username" name="username"
								placeholder="请输入管理员账号" <?php if($operation == 'edit'): ?>readonly="readonly"<?php endif; ?> value="<?php echo isset($info['username'])?$info['username']:''; ?>">
							</div>
							<div class="form-group">
								<label>
									手机号
								</label>
								<input class="form-control" type="text" id="phone" name="phone"
								placeholder="请输入手机号" value="<?php echo isset($info['phone'])?$info['phone']:''; ?>">
							</div>
							<div class="form-group">
								<label>
									管理员密码
								</label>
								<input class="form-control" type="text" id="password" name="password"
								placeholder="请输入管理员密码" value="">
							</div>
							<div class="form-group">
                                <label>
                                    管理员组
                                </label>
                                <select id="rid" name="rid">
                                  <option value="">请选择</option>
                                  <?php foreach($class as $key=>$vo): ?> 
                                      <option value="<?php echo htmlentities($vo['id']); ?>" <?php if($operation == 'edit'): if($vo['id'] == $info['rid']): ?> selected="" <?php endif; ?><?php endif; ?>><?php echo htmlentities($vo['name']); ?></option>
                                  <?php endforeach; ?>                                
                                </select>
							</div>							
							<div class="form-group">
								<button class="layui-btn" type="button" lay-submit lay-filter="save">
								    <?php if($operation == 'add'): ?>提交<?php else: ?>更新<?php endif; ?>	
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>
 </div>
</div>
<script type="text/javascript" src="/static/admin/index/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/index/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/admin/index/js/perfect-scrollbar.min.js"></script>
<script src="https://www.layuicdn.com/layui/layui.js"></script>
</body>
</html>
<script>

layui.use(['form'], function(){
  var layer = layui.layer
  ,form = layui.form;
   //监听提交
  form.on('submit(save)', function(data){
        var username = $('#username').val();
        var password = $('#password').val();
        var phone = $('#phone').val();
        var rid = $('#rid').val();
        if(username == "" || username == null || username == undefined){
            layer.msg("请输入管理员账号");
            return false;
        }  
        if(password == "" || password == null || password == undefined){
            layer.msg("请输入管理员密码");
            return false;
        } 
        if(phone == "" || phone == null || phone == undefined){
            layer.msg("请输入管理员手机号");
            return false;
        } 
        if(rid == "" || rid == null || rid == undefined){
            layer.msg("请选择管理员分组");
            return false;
        } 
        var loading = layer.load(0, {shade: false});
        $.ajax({
            type: 'post',
            url: "<?php echo url('doSave'); ?>",
            data:$("#loginfrom").serialize(),
            dataType:"json",
            success: function(data) {
                if(data.code === 200){
                    layer.msg(data.msg, {
                      icon: 1,
                      time: 1500 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        layer.closeAll();
                        window.parent.location.reload();
                        var index = parent.layer.getFrameIndex(window.name);
                        parent.layer.close(index);
                        
                    });                      
                }else{
                     layer.msg(data.msg);
                     return false;
                }  
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                layer.closeAll();
                if (textStatus == "timeout") {
                    layer.msg('请求超时！', {
                      icon: 2,
                      time: 1500 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        return false;
                    });                      
                } else {
                    layer.msg('服务器错误！', {
                      icon: 2,
                      time: 1500 //2秒关闭（如果不配置，默认是3秒）
                    }, function(){
                        return false;
                    });                         
                }
            },                    
        }); 
    });
});

</script>
<?php /*a:6:{s:73:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/index/index.html";i:1666002996;s:73:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/public/meta.html";i:1638604914;s:74:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/public/style.html";i:1638604914;s:73:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/public/menu.html";i:1662533153;s:73:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/public/head.html";i:1638604914;s:75:"/Users/loivngoo/lvn/php/www/admin/application/admin/view/public/footer.html";i:1638604914;}*/ ?>
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
    <aside class="layout-sidebar">
      <!-- logo -->
      <div id="logo" class="sidebar-header">
        <a href="<?php echo url('index/index'); ?>">蜜獾娱乐</a>
      </div>
      <div class="layout-sidebar-scroll"> 
        
        <nav class="sidebar-main">
          <ul class="nav nav-drawer">
            <li class="nav-item <?php if(Request::controller() == 'Index'): ?> active <?php endif; ?>"> <a href="<?php echo url('index/index'); ?>"><i class="mdi mdi-home"></i>首页</a></li>
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'Data'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-poll"></i>数据统计</a>
              <ul class="nav nav-subnav">
                <li><a href="<?php echo url('data/register'); ?>">注册统计</a></li>
                <li><a href="<?php echo url('data/recharge'); ?>">充值统计</a></li>
                <li><a href="<?php echo url('data/withdrawal'); ?>">提现统计</a></li>
              </ul>
            </li>   
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'Withdrawal' 
            || Request::controller() == 'Banks'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-bank"></i>财务管理</a>
              <ul class="nav nav-subnav">
                <li><a href="<?php echo url('withdrawal/index'); ?>">提现管理</a></li>
                <li><a href="<?php echo url('Banks/index'); ?>">银行管理</a></li>
              </ul>
            </li>     
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'Log' || Request::controller() == 'User' || Request::controller() == 'Role'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-account"></i>超管管理</a>
              <ul class="nav nav-subnav">
                <li> <a href="<?php echo url('user/index'); ?>">管理员管理</a></li>
                <li> <a href="<?php echo url('role/index'); ?>">管理组管理</a> </li>
                <li> <a href="<?php echo url('log/index'); ?>">日志管理</a> </li>
              </ul>
            </li>
            <li class="nav-item-has-subnav 
            <?php if(Request::controller()=='Member'
            || Request::controller() == 'Agent'): ?> active open <?php endif; ?>">
                <a href="javascript:void(0)"><i class="mdi mdi-account-multiple"></i>会员管理</a>
                <ul class="nav nav-subnav">
                     <li> <a href="<?php echo url('agent/index'); ?>">代理列表</a></li>
                     <li> <a href="<?php echo url('member/index'); ?>">会员列表</a></li>
                </ul>
            </li>            
            <li class="nav-item nav-item-has-subnav 
            <?php if(Request::controller() == 'Lotteryclass' 
            || Request::controller() == 'Lottery'
            || Request::controller() == 'Openlottery'
            || Request::controller() == 'Yulottery'
            || Request::controller() == 'Buylottery'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-gamepad-variant"></i>彩票管理</a>
              <ul class="nav nav-subnav">
                <li> <a href="<?php echo url('lotteryclass/index'); ?>">彩票分类</a></li>
                <li> <a href="<?php echo url('lottery/index'); ?>">彩票列表</a></li>
                <li> <a href="<?php echo url('openlottery/index'); ?>">开奖记录</a></li>
                <li> <a href="<?php echo url('buylottery/index'); ?>">投注记录</a></li>
                <li> <a href="<?php echo url('Yulottery/index'); ?>">预设开奖</a></li>
              </ul>
            </li>            
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'Video' || Request::controller() == 'Videoclass'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-video"></i>视频管理</a>
              <ul class="nav nav-subnav">
                <li> <a href="<?php echo url('videoclass/index'); ?>">视频分类</a> </li>
                <li> <a href="<?php echo url('video/index'); ?>">视频列表</a> </li>
              </ul>
            </li>
            
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'Xuanfei' || Request::controller() == 'Xuanfei'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-video"></i>选妃管理</a>
              <ul class="nav nav-subnav">
                <li> <a href="<?php echo url('xuanfei/xuanfeiaddress'); ?>">选妃地区</a> </li>
                <li> <a href="<?php echo url('xuanfeilist/xuanfeilist'); ?>">选妃列表</a> </li>
              </ul>
            </li>
            
            <li class="nav-item nav-item-has-subnav <?php if(Request::controller() == 'System' 
            || Request::controller() == 'Notice'
            || Request::controller() == 'Banner'): ?> active open <?php endif; ?>">
              <a href="javascript:void(0)"><i class="mdi mdi-settings"></i>系统管理</a>
              <ul class="nav nav-subnav">
                <li> <a href="<?php echo url('system/index'); ?>">基本配置</a> </li>
                <li> <a href="<?php echo url('notice/index'); ?>">公告配置</a> </li>
                <li> <a href="<?php echo url('banner/index'); ?>">广告配置</a> </li>
              </ul>
            </li>  
          </ul>
        </nav>
        
        <div class="sidebar-footer">
          <p class="copyright">Copyright &copy; 2019.  All rights <a href="http://www.bootstrapmb.com/">reserved</a>. </p>
        </div>
      </div>
      
    </aside>
    <!--End 左侧导航-->
    <!--头部信息-->
    <header class="layout-header">
      
      <nav class="navbar navbar-default">
        <div class="topbar">
          
          <div class="topbar-left">
            <div class="aside-toggler">
              <span class="toggler-bar"></span>
              <span class="toggler-bar"></span>
              <span class="toggler-bar"></span>
            </div>
            <!--<span class="navbar-page-title"> </span>-->
          </div>
          
          <ul class="topbar-right">
            <li class="dropdown dropdown-profile">
              <a href="javascript:void(0)" data-toggle="dropdown">
                <img class="img-avatar img-avatar-48 m-r-10" src="/static/admin/index/images/users/avatar.jpg" alt="管理员" />
                <span><?php echo htmlentities($userinfo['username']); ?>【<?php echo htmlentities($role['name']); ?>】<span class="caret"></span></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-right">
                <li> <a href="pages_profile.html"><i class="mdi mdi-account"></i> 个人信息</a> </li>
                <li> <a href="pages_edit_pwd.html"><i class="mdi mdi-lock-outline"></i> 修改密码</a> </li>
                <li> <a href="javascript:void(0)"><i class="mdi mdi-delete"></i> 清空缓存</a></li>
                <li class="divider"></li>
                <li> <a href="<?php echo url('base/Logout'); ?>"><i class="mdi mdi-logout-variant"></i> 退出登录</a> </li>
              </ul>
            </li>
            <!--切换主题配色-->
		    <li class="dropdown dropdown-skin">
			  <span data-toggle="dropdown" class="icon-palette"><i class="mdi mdi-palette"></i></span>
			  <ul class="dropdown-menu dropdown-menu-right" data-stopPropagation="true">
                <li class="drop-title"><p>主题</p></li>
                <li class="drop-skin-li clearfix">
                  <span class="inverse">
                    <input type="radio" name="site_theme" value="default" id="site_theme_1" checked>
                    <label for="site_theme_1"></label>
                  </span>
                  <span>
                    <input type="radio" name="site_theme" value="dark" id="site_theme_2">
                    <label for="site_theme_2"></label>
                  </span>
                  <span>
                    <input type="radio" name="site_theme" value="translucent" id="site_theme_3">
                    <label for="site_theme_3"></label>
                  </span>
                </li>
			    <li class="drop-title"><p>LOGO</p></li>
				<li class="drop-skin-li clearfix">
                  <span class="inverse">
                    <input type="radio" name="logo_bg" value="default" id="logo_bg_1" checked>
                    <label for="logo_bg_1"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_2" id="logo_bg_2">
                    <label for="logo_bg_2"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_3" id="logo_bg_3">
                    <label for="logo_bg_3"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_4" id="logo_bg_4">
                    <label for="logo_bg_4"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_5" id="logo_bg_5">
                    <label for="logo_bg_5"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_6" id="logo_bg_6">
                    <label for="logo_bg_6"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_7" id="logo_bg_7">
                    <label for="logo_bg_7"></label>
                  </span>
                  <span>
                    <input type="radio" name="logo_bg" value="color_8" id="logo_bg_8">
                    <label for="logo_bg_8"></label>
                  </span>
				</li>
				<li class="drop-title"><p>头部</p></li>
				<li class="drop-skin-li clearfix">
                  <span class="inverse">
                    <input type="radio" name="header_bg" value="default" id="header_bg_1" checked>
                    <label for="header_bg_1"></label>                      
                  </span>                                                    
                  <span>                                                     
                    <input type="radio" name="header_bg" value="color_2" id="header_bg_2">
                    <label for="header_bg_2"></label>                      
                  </span>                                                    
                  <span>                                                     
                    <input type="radio" name="header_bg" value="color_3" id="header_bg_3">
                    <label for="header_bg_3"></label>
                  </span>
                  <span>
                    <input type="radio" name="header_bg" value="color_4" id="header_bg_4">
                    <label for="header_bg_4"></label>                      
                  </span>                                                    
                  <span>                                                     
                    <input type="radio" name="header_bg" value="color_5" id="header_bg_5">
                    <label for="header_bg_5"></label>                      
                  </span>                                                    
                  <span>                                                     
                    <input type="radio" name="header_bg" value="color_6" id="header_bg_6">
                    <label for="header_bg_6"></label>                      
                  </span>                                                    
                  <span>                                                     
                    <input type="radio" name="header_bg" value="color_7" id="header_bg_7">
                    <label for="header_bg_7"></label>
                  </span>
                  <span>
                    <input type="radio" name="header_bg" value="color_8" id="header_bg_8">
                    <label for="header_bg_8"></label>
                  </span>
				</li>
				<li class="drop-title"><p>侧边栏</p></li>
				<li class="drop-skin-li clearfix">
                  <span class="inverse">
                    <input type="radio" name="sidebar_bg" value="default" id="sidebar_bg_1" checked>
                    <label for="sidebar_bg_1"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_2" id="sidebar_bg_2">
                    <label for="sidebar_bg_2"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_3" id="sidebar_bg_3">
                    <label for="sidebar_bg_3"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_4" id="sidebar_bg_4">
                    <label for="sidebar_bg_4"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_5" id="sidebar_bg_5">
                    <label for="sidebar_bg_5"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_6" id="sidebar_bg_6">
                    <label for="sidebar_bg_6"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_7" id="sidebar_bg_7">
                    <label for="sidebar_bg_7"></label>
                  </span>
                  <span>
                    <input type="radio" name="sidebar_bg" value="color_8" id="sidebar_bg_8">
                    <label for="sidebar_bg_8"></label>
                  </span>
				</li>
			  </ul>
			</li>
            <!--切换主题配色-->
          </ul>
          
        </div>
      </nav>
      
    </header>
    <!--End 头部信息-->
<!--页面主要内容-->
<main class="layout-content">
  
  
  <div class="container-fluid">

    
    <div class="row">
      
      <div class="col-lg-12"> 
        <div class="card">
          <div class="card-header">
            <h4>用户数据</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">用户总余额</p>
                      <p class="h3 text-white m-b-0">102,125.00</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-currency-cny fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-danger">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">用户总数</p>
                      <script src="../../controller/Index.php"></script>
                      <p class="h3 text-white m-b-0"><?php echo htmlentities($total_users); ?></p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-account fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">新增用户</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">首充人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
               <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">二充人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">三充人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">复充人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
            <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">精准用户</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">下注用户</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">充值人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">提现人数</p>
                      <p class="h3 text-white m-b-0"></p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              <script>

              </script>
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    
    
    
    
     <div class="row">
      
      <div class="col-lg-12"> 
        <div class="card">
          <div class="card-header">
            <h4>出入款数据</h4>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-primary">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">提存亏盈</p>
                      <p class="h3 text-white m-b-0">102,125.00</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-currency-cny fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-danger">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">充值金额</p>
                      <p class="h3 text-white m-b-0">920,000</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-account fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">提现金额</p>
                      <p class="h3 text-white m-b-0">34,005,000</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">首充人数</p>
                      <p class="h3 text-white m-b-0">150人</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
               <div class="col-sm-6 col-lg-3">
                <div class="card bg-success">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">人工充值</p>
                      <p class="h3 text-white m-b-0">34,005,000</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-arrow-down-bold fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">官方通道存/提</p>
                      <p class="h3 text-white m-b-0">153.00/23.00</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
              <div class="col-sm-6 col-lg-3">
                <div class="card bg-purple">
                  <div class="card-body clearfix">
                    <div class="pull-right">
                      <p class="h6 text-white m-t-0">第三方通道存/提</p>
                      <p class="h3 text-white m-b-0">153.00/23.00</p>
                    </div>
                    <div class="pull-left"> <span class="img-avatar img-avatar-48 bg-translucent"><i class="mdi mdi-comment-outline fa-1-5x"></i></span> </div>
                  </div>
                </div>
              </div>
              
           
              
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!--<div class="row">-->
      
    <!--  <div class="col-lg-12">-->
    <!--    <div class="card">-->
    <!--      <div class="card-header">-->
    <!--        <h4>项目信息</h4>-->
    <!--      </div>-->
    <!--      <div class="card-body">-->
    <!--        <div class="table-responsive">-->
    <!--          <table class="table table-hover">-->
    <!--            <thead>-->
    <!--              <tr>-->
    <!--                <th>#</th>-->
    <!--                <th>项目名称</th>-->
    <!--                <th>开始日期</th>-->
    <!--                <th>截止日期</th>-->
    <!--                <th>状态</th>-->
    <!--                <th>进度</th>-->
    <!--              </tr>-->
    <!--            </thead>-->
    <!--            <tbody>-->
    <!--              <tr>-->
    <!--                <td>1</td>-->
    <!--                <td>设计新主题</td>-->
    <!--                <td>10/02/2019</td>-->
    <!--                <td>12/05/2019</td>-->
    <!--                <td><span class="label label-warning">待定</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-warning" style="width: 45%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>2</td>-->
    <!--                <td>网站重新设计</td>-->
    <!--                <td>01/03/2019</td>-->
    <!--               <td>12/04/2019</td>-->
    <!--                <td><span class="label label-success">进行中</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-success" style="width: 30%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>3</td>-->
    <!--                <td>模型设计</td>-->
    <!--                 <td>10/10/2019</td>-->
    <!--                 <td>12/11/2019</td>-->
    <!--                <td><span class="label label-warning">待定</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-warning" style="width: 25%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>4</td>-->
    <!--                <td>后台管理系统模板设计</td>-->
    <!--                <td>25/01/2019</td>-->
    <!--                <td>09/05/2019</td>-->
    <!--                <td><span class="label label-success">进行中</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-success" style="width: 55%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>5</td>-->
    <!--                <td>前端设计</td>-->
    <!--                <td>10/10/2019</td>-->
    <!--                <td>12/12/2019</td>-->
    <!--                <td><span class="label label-danger">未开始</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-danger" style="width: 0%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>6</td>-->
    <!--                <td>桌面软件测试</td>-->
    <!--                <td>10/01/2019</td>-->
    <!--                <td>29/03/2019</td>-->
    <!--                <td><span class="label label-success">进行中</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-success" style="width: 75%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>7</td>-->
    <!--                <td>APP改版开发</td>-->
    <!--                <td>25/02/2019</td>-->
    <!--                <td>12/05/2019</td>-->
    <!--                <td><span class="label label-danger">暂停</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-danger" style="width: 15%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--              <tr>-->
    <!--                <td>8</td>-->
    <!--                <td>Logo设计</td>-->
    <!--                <td>10/02/2019</td>-->
    <!--                <td>01/03/2019</td>-->
    <!--                <td><span class="label label-warning">完成</span></td>-->
    <!--                <td>-->
    <!--                  <div class="progress progress-striped progress-sm">-->
    <!--                    <div class="progress-bar progress-bar-success" style="width: 100%;"></div>-->
    <!--                  </div>-->
    <!--                </td>-->
    <!--              </tr>-->
    <!--            </tbody>-->
    <!--          </table>-->
    <!--        </div>-->
    <!--      </div>-->
    <!--    </div>-->
    <!--  </div>-->
      
    <!--</div>-->
    
  </div>
  
</main>
<!--End 页面主要内容-->
  </div>
</div>

<script type="text/javascript" src="/static/admin/index/js/jquery.min.js"></script>
<script type="text/javascript" src="/static/admin/index/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/static/admin/index/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="/static/admin/index/js/main.js"></script>
<script src="https://www.layuicdn.com/layui/layui.js"></script>
<!--图表插件-->
<script type="text/javascript" src="/static/admin/index/js/Chart.js"></script>
</body>
</html>
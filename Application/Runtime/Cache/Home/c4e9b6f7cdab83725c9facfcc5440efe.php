<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>注册 学生</title>
<link href="/Public/css/public.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/home.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/Public/js/jquery.SuperSlide.2.1.1.js"></script>
</head>

<body>
<!--topbar-->
<div id="topbar">
    <div class="width">
        <div class="right">
        	<a href="">设为首页</a> |
            <?php if(!empty($user_id)): ?><a href="/member/">个人中心</a> |
            <a href="/member/logout">退出</a>
            <?php else: ?>
            <a href="/member/login">登录</a> |
            <a href="/member/register">注册</a><?php endif; ?>
         </div> 
     </div>
</div>

<!--header-->
<div id="header">
	<div class="width">
    	<div class="left logo"><a href="/"></a></div>
    </div>
</div>

<!--main-->
<div class="width">
    <div class="reg_form">
		<form id="" name="" method="post" action="/member/login">
			<div class="error" style="margin-left:90px;<?php if(empty($error_msg)): ?>display:none;<?php endif; ?>" id=""><?php echo ($error_msg); ?></div>
			<div class="reg_br">
				<div class="reg_l">账号：</div>
				<div class="reg_r">
					<input id="username" type="text" name="username" datatype="*" ajaxurl="/member/ajaxCheckUser" nullmsg="邮箱不能为空！" errormsg="请输入合法的邮箱地址！"/>
				</div>
				<div class="clear"></div>
			</div>
			
			<div class="reg_br">
				<div class="reg_l">密码：</div>
				<div class="reg_r"><input id="password" type="password" name="password" datatype="*6-20" nullmsg="密码不能为空！" errormsg="密码请输入6-16个字符"/></div>
				<div class="clear"></div>
			</div>
			<div class="reg_br" style="margin-top:20px;">
				<div class="reg_l">&nbsp;</div>
				<div style="float:left;"><input name="auto_login" value="1" type="checkbox" />下次自动登录</div>
				<div style="float:left;margin-left:70px;"><a href="/member/resetpass">忘记密码</a></div>
				<div class="clear"></div>
			</div>
			<div class="reg_br" style="margin-top:20px;">
				<div class="reg_l">&nbsp;</div>
				<div style="float:left;">
					<span><button id="button" type="submit" class="button_login" style="width:100px;">登录</button></span>
					<span style="margin-left:20px;"><button id="button" onclick="window.location.href='/member/register'" class="button_reg" style="width:100px;">注册</button></span>
				</div>
				<div class="clear"></div>
			</div>
		</form>
	</div>
   
</div>

<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script>
<link href="/Public/css/validform.css" rel="stylesheet" type="text/css" />
<script>
	$(
		function(){
   		$("form").Validform(
   			{tiptype:3}
   		);
    });
</script>

	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
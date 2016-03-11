<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($meta_title); ?></title>
<link href="/Public/css/index.css" rel="stylesheet" type="text/css" />
<?php echo ($loginCss); ?>
<script src="/Public/js/jquery.min.js" type="text/javascript"></script>
</head>
<body>
	<!--header-->
	<div id="header">
		<div style="width:1000px;">
			<a href="#"><div class="left logo"></div></a>
			<div class="left top_line"></div>
			<div class="left top_title">注册</div>
			<div class="right top_r">
				<div class="left"><a href="/">返回首页</a></div>
				<div class="left top_r_line"></div>
				<div class="left"><?php if(!empty($user_id)): ?><a href="/member/logout">退出</a><?php else: ?><a href="/member/login">马上登陆</a><?php endif; ?></div>
			</div>
		</div>
	</div>

<!--main-->
<div id="main_fram">	
			<div class="maindiv">
				<div class="main-center">
					<div id="form1" class="main1" margin-top:-5px;>
						<div class="userform" >
                        	<div class="ul" style="margin-top:-45px;">
							<div class="userlogin">用户登录</div>
                            </div>
						</div>
						<form id="login_form" action="/member/login" method="post">
						<div class="form_list" style="margin-top:25px;">
                        <div id="busybox">
                        	<?php if(!empty($error_msg)): ?><span><?php echo ($error_msg); ?></span>
							<div class="error_img"></div><?php endif; ?>
						</div>
							<div class="l_list1">	
								<input name="username" id="username"  datatype="*" ajaxurl="/member/ajaxCheckUser" nullmsg="请输入邮箱或手机号码" errormsg="请输入合法的邮箱或手机号码" type="text" placeholder="邮箱\手机号码" onfocus="this.placeholder=''" onblur="this.placeholder='邮箱\手机号码'"/>				  
						  </div>
							<div class="l_list2">
								<input name="password" id="password" type="password" datatype="*6-20" nullmsg="密码不能为空！" errormsg="密码请输入6-16个字符" placeholder="请输入密码" onfocus="this.placeholder=''" sucmsg="通过验证" onblur="this.placeholder='请输入密码'"/>							
							</div>
							<div style="font-size:15px;display:inline;line-height:30px">
								<div id="div1" style="margin-left:80px;float:left"><label><input type="checkbox" id="is_clause" name="auto_login" value="1" /> 下次自动登录</label></div>
                                <div id="div2" style="margin-left:290px;float:inherit;text-decoration:underline"><a href="/member/resetpass">忘记密码?</a></div>
							</div>
                            
							<div>
								<div class="btn_log" onclick="$('form').submit();">
									登录
								</div>
							</div>	
						</form>
                        <div style="font-size:15px;display:inline;">
                        	<div class="otherlog">其他登录方式：</div>
                        	<div class="other" style="float:inherit">
                        		<a href="/qqconnect/login"><div class="qq_button"></div></a>
								<a href="/weixinconnect/login"><div class="weixin_button"></div></a>
								<a href="/sinaconnect/login"><div class="weibo_button"></div></a>
                        	</div>
                        </div>
					</div>
				</div>
			</div>
		</div>	
	</div>
	

<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script>
<link href="/Public/css/validform.css" rel="stylesheet" type="text/css" />
<script>
	$(
		function(){
   		$("form").Validform({
		tiptype:function(msg,o,cssctl){
			var objtip=$("#busybox");
			cssctl(objtip,o.type);
			objtip.html(msg);
		}
	});
    });
    
</script>
<style>
	
.Validform_wrong {
    background: rgba(0, 0, 0, 0) url("/Public/images/error.png") no-repeat scroll left center;
    color: red;
    padding-left: 20px;
    white-space: nowrap;
}
.Validform_checktip {
    font-size: 12px;
    height: 20px;
    line-height: 20px;
    overflow: hidden;
    border: 1px solid #ffd398;
    height: 15px;
    line-height: 15px;
    margin-left: 78px;
    margin-top: -10px;
    min-width: 50px;
    padding: 5px 10px 5px 25px;
    position: relative;
    width: 245px;
}
</style>
	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
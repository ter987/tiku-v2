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
		<div id="third_fram">	
			<div class="thirddiv">
				<div class="third-center">
                		<form id="conform_form" action="" method="post">
						<div class="usertype">
						<span>您好,<font style="color:#09F">ss</font></span><br/>
                        <span>请选择您的用户类型</span>
                        </div>
													
							<div class="third" style="font-size:15px;display:inline;">
								<label>
								<div id="div1" style="margin-left:60px;float:left;">
                                <div class="student"></div><br/>
                                <input name="type" type="radio" value="" checked="" style="margin-left:70px;"/>
                                 </div>
                               </label>
                               <label>
                                <div id="div2" style="float:inherit;">
                                <div class="teacher"></div>
                                 <input name="type" type="radio" value="" style="margin-left:150px;"/>
                                </div>
                                </label>
							</div>
                            
							<div>
								<div class="btn_conf" onclick="$('form').submit();">
									确定
								</div>
							</div>
						</form> 
					</div>
				</div>
	</div>
	
           
	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
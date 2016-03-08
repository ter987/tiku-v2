<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo ($meta_title); ?></title>
<link href="/Public/css/index.css" rel="stylesheet" type="text/css" />
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
	<div id="main">
		<div class="main_bg">	
			<div class="main">
				<!-- <div class="left main_left">
					<div class="l_k_check">
						我是老师
						<div class="l_check"></div>
					</div>
					<div class="l_k1_1">
						我是学生
					</div>
					<div class="l_k2"></div>
				</div> -->
				<div class="left main_left">
					<div class="<?php if(($user_type) == "1"): ?>l_k_1<?php else: ?>l_k1_check<?php endif; ?>">
						我是老师
						<?php if(($user_type) == "2"): ?><div class="l_check"></div><?php endif; ?>
					</div>
					<div class="<?php if(($user_type) == "2"): ?>l_k_1<?php else: ?>l_k1_check<?php endif; ?>">
						我是学生
						<?php if(($user_type) == "1"): ?><div class="l_check"></div><?php endif; ?>
					</div>
					<div class="l_k2"></div>
				</div>
				<div class="left main-right">
					<div class="main_r">
						<div class="stu_nav">
							<div class="left s_nav blue_back">1.新用户注册</div>
							<div class="left s_nav blue_back">3.激活</div>
							<div class="left s_nav blue_back">4.注册成功</div>
						</div>
						<div style="width:680px;margin:60px auto;border-bottom:1px solid #666666;padding-bottom:25px;">
							<div style="width:500px;color:#1a1a1a;">
								<div class="" style="font-size:36px;color:#ff9c00;line-height:76px;margin:35px 0px;">	
									<div class="left true_img"></div> &nbsp;&nbsp;恭喜您！激活成功！
								</div>
								<div>
									您的邮箱账号是：<span style="font-weight:700;"><?php echo ($info["email"]); ?></span>
								</div>
								<div style="margin:10px 0px;">
									<div class="left" style="line-height:40px;">安全性：</div>
									<div class="left">
										<div class="aq_opt_c">弱</div>
										<div class="aq_opt">中</div>
										<div class="aq_opt">强</div>
									</div>
									<div style="clear:both;"></div>
								</div>
								<div style="color:#4c4c4c;">
									未绑手机，如果账号丢失，将加大找回密码的难度。马上 <a href="" style="text-decoration:underline;color:#1194F3;">绑定手机</a>
								</div>
							</div>
						</div>	
						<div style="width:640px;margin:40px auto;font-size:18px;padding:20px;">
							<div class="btn_1" style="margin:0px 50px;" onclick="window.location.href='/member/info'">完善个人信息</div>
							<div class="btn_2" onclick="window.location.href='/'">开始体验</div>
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
		</div>	
	</div>
	<!--footer-->
	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
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
<div id="main">
		<div class="main_bg">	
			<div class="main">
				<div class="left main_left">
					<div id="is_teacher" class="l_k_check">
						我是老师
						<div class="l_check is_tea"></div>
					</div>
					<div id="is_student" class="l_k1">
						我是学生
						<div class="l_check is_stu" style="display:none;"></div>
					</div>
					<div class="l_k2"></div>
				</div>
				<div class="left main-right">
					<!-- 注册用户 -->
					<div id="phone_form" class="main_r">
						<div class="stu_nav ph_li">
							<div class="left p_nav blue_back">1.新用户注册</div>
							<div class="left p_nav nav_left">2.注册成功</div>
						</div>
						<div class="stu_nav em_li" style="display:none;">
							<div class="left s_nav blue_back">1.新用户注册</div>
							<div class="left s_nav nav_left">2.激活</div>
							<div class="left s_nav">3.注册成功</div>
						</div>
						<div><?php echo ($error_msg); ?></div>
						<form id="add_form" action="/member/register" method="post" enctype="multipart/form-data">
						<input type="hidden" id="type" name="type" value="2" />
						<input type="hidden" name="is_mail" id="is_mail" value="0"/>
						<div class="form_list" style="margin-top:-5px;">
							<div class="l_list ph_li">
								<div class="left f_left">
									<div class="right"><i></i><span>手机号：</span></div>
								</div>
								<div class="left f_right"><input name="telphone" id="phone" type="text" datatype="m" ajaxurl="/member/ajaxCheckTelphone" nullmsg="请输入手机号码！" errormsg="请输入正确的手机号码！"  placeholder="请输入手机号" onfocus="this.placeholder=''" onblur="this.placeholder='请输入手机号'" /></div>
							</div>
							<div class="l_list em_li" style="display:none;">
								<div class="left f_left">
									<div class="right"><i></i><span>邮箱：</span></div>
								</div>
								<div class="left f_right"><input name="email" id="email" type="text" datatype="e"   ajaxurl="/member/ajaxcheckemail"  nullmsg="邮箱不能为空！" errormsg="请输入合法的邮箱地址！" placeholder="请输入邮箱" onfocus="this.placeholder=''" onblur="this.placeholder='请输入邮箱'"/></div>
							</div>
							<div class="l_list ph_li">
								<div class="left f_left">
									<div class="right"><i></i><span>昵称：</span></div>
								</div>
								<div class="left f_right"><input name="nick_name" id="nick_name" type="text" datatype="*6-20" ajaxurl="/member/ajaxchecknickname" nullmsg="昵称不能为空！" errormsg="该昵称不可用！"   placeholder="请输入昵称" onfocus="this.placeholder=''" onblur="this.placeholder='请输入昵称'"/></div>
							</div>
							<div class="l_list">
								<div class="left f_left">
									<div class="right"><i></i><span>密码：</span></div>
								</div>
								<div class="left f_right"><input name="password" id="password" type="password"  datatype="*6-20" nullmsg="密码不能为空！"  errormsg="密码请输入6-16个字符" placeholder="请输入密码" sucmsg="通过验证" onfocus="this.placeholder=''" onblur="this.placeholder='请输入密码'" sucmsg=""/>
									
								</div>
								<!-- <div class="passwordStrength">密码强度： <span>弱</span><span>中</span><span class="last">强</span></div>
								<div class="left right_img" style="display:none;"></div>
								<div class="left dialog_box" style="margin-left:-165px;display:none;">
									<span id="passwordTip">请输入密码</span>
									<div class="down_arrows"></div>
									<div class="error_img"></div>
								</div> -->
							</div>
							<div class="l_list">
								<div class="left f_left">
									<div class="right"><i></i><span>确认密码：</span></div>
								</div>
								<div class="left f_right"><input name="password1" id="password1" type="password" recheck="password" datatype="*6-20" nullmsg="密码不能为空！"  sucmsg="通过验证" placeholder="请再次输入密码" onfocus="this.placeholder=''" onblur="this.placeholder='请再次输入密码'"/></div>
							</div>
							<!-- <div class="l_list">
								<div class="left f_left">
									<div class="right"><i></i><span>验证码：</span></div>
								</div>
								<div class="left f_right" style="width:180px;">
									<input id="yzm" name="verify_code" datatype="*" type="text" style="width:128px;" ajaxurl="/member/ajaxCheckVerifyCode" placeholder="请输入图片验证码" onfocus="this.placeholder=''" onblur="this.placeholder='请输入图片验证码'"/>
								</div>
								<div class="left" style="margin-right:10px;"><img src="/member/verifyCode" style="width:150px;height:50px;" onclick="$(this).attr('src','/member/verifyCode?'+Math.random());"></div>
								<div class="left right_img" style="display:none;"></div>
							</div>-->
							<div class="l_list ph_li" style="height:73px;">
								<div class="left f_left">
									<div class="right"><i></i><span>手机激活码：</span></div>
								</div>
								<div class="left f_right" style="width:180px;">
									<input datatype="*" name="yanzhengma" type="text" style="width:128px;" placeholder="请输入激活码" sucmsg="通过验证"  ajaxurl="/member/ajaxCheckPvCode" onfocus="this.placeholder=''" onblur="this.placeholder='请输入激活码'"/>
								</div>
								<div class="left n_code" id="send_phone" onclick="send_vcode($(this))">获取短信激活码</div>
							</div>  
							<div style="margin-left:185px;font-size:15px;">
								<input type="checkbox" checked="" id="is_clause" name="clause[]" value="1" /> 我已阅读并同意 <a href="javascript:;" class="clause" style="color:#119cd0;">《哈学库使用条款》</a>
							</div>
							<div>
								<div class="left btn_sub" onclick="$('form').submit();">
									注册
								</div>
								<div class="left" style="margin-top:40px;margin-left:20px;">
									<a href="javascript:;" id="email_btn" class="ph_li" style="color:#119cd0;">使用邮箱注册？</a>
									<a href="javascript:;" id="phone_btn" class="em_li" style="display:none;color:#119cd0;">使用手机注册？</a>
								</div>
							</div>
						</div>	
						</form>
					</div>
				</div>
			</div>
		</div>	
	</div>
	
	<div id="clause_content" style="display:none;">
		<div class="" style="width:600px;height:250px">
			<div style="border-bottom:1px solid #CCCCCC;margin-bottom:5px;">
				<span style="font-size:18px;color:#1194f3;font-weight:bold;">哈学库使用条款</span> terms of use
			</div>
			<div style="width:600px;height:220px;overflow-y:auto;overflow-x:hidden;margin:5px 0px;">
			<p><span style="color:#1194f3;font-weight:bold;">1.哈学库使用条款的确认和接纳</span><br />
　　用户必须完全接收本使用条款的全部内容，并完成注册程序，才能成为哈学库的注册学员，享受哈学库的相关
服务。</p>
	  <p><span style="color:#1194f3;font-weight:bold;">2. 用户承担的责任</span><br />
　　(1) 自备上网所需要的设备，自行承担上网产生的各项费用。使用自己的电脑能够顺利地接入国际互联网，并
能访问本网站主页。<br />
　　(2) 提供详尽、准确的个人资料并及时更新个人资料。若用户提供任何错误、不实、过时或不完整的资料，或
者哈学库有合理理由怀疑用户所提供资料为错误、不实、过时或不完整，哈学库保留暂停或终止其注册学员资格的
权利。<br />
　　(3) 必须遵守中华人民共和国的法律、法规、规章、条例、以及其他具有法律效力的规范，不使用网络服务做
</p>
<p><span style="color:#1194f3;font-weight:bold;">1.哈学库使用条款的确认和接纳</span><br />
　　用户必须完全接收本使用条款的全部内容，并完成注册程序，才能成为哈学库的注册学员，享受哈学库的相关
服务。</p>
	  <p><span style="color:#1194f3;font-weight:bold;">2. 用户承担的责任</span><br />
　　(1) 自备上网所需要的设备，自行承担上网产生的各项费用。使用自己的电脑能够顺利地接入国际互联网，并
能访问本网站主页。<br />
　　(2) 提供详尽、准确的个人资料并及时更新个人资料。若用户提供任何错误、不实、过时或不完整的资料，或
者哈学库有合理理由怀疑用户所提供资料为错误、不实、过时或不完整，哈学库保留暂停或终止其注册学员资格的
权利。<br />
　　(3) 必须遵守中华人民共和国的法律、法规、规章、条例、以及其他具有法律效力的规范，不使用网络服务做
</p>
			</div>
			<!-- <div style="border-top:1px solid #CCCCCC;margin-top:5px;padding-top:20px;">	
				<div class="right btn_3">同意</div>
			</div> -->
		</div>
	</div>
<link href="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.js"></script>
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.js"></script>
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/passwordStrength-min.js"></script>
<link href="/Public/css/validform.css" rel="stylesheet" type="text/css" />

<script>
	$(
		function(){
   		$("form").Validform(
   			{
   				tiptype:3,
   				ignoreHidden:true,
   				usePlugin:{
				passwordstrength:{
					minLen:6,
					maxLen:20
				}
				}
		}
   			
   		);
    });
    var tin;
	function send_vcode(obj){
		if($('#phone').val()=='') return false;
		$.getJSON(
			'/member/ajaxSendPvCode',
			{telphone:$('#phone').val()},
			function(data){
				if(data.status=='errors'){
					alert(data.message);
				}else{
					obj.attr('onclick','');
					obj.html('重新获取(60)');
					tin = setInterval("timer()",1000);
				}
			}
			);
	    }
	    var sec = 60;
	   
		function timer(){
			sec = sec-1;
			if(sec==0){
			$('#send_phone').attr('onclick','send_vcode($(this));');
			$('#send_phone').val('获取验证码');
			clearInterval(tin);
			sec = 60;
		}else{
			var html = '重新获取('+sec+')';
			$('#send_phone').html(html);
			
		}
		
	}
</script>
<script src="/Public/js/reg.js" type="text/javascript"></script>
	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
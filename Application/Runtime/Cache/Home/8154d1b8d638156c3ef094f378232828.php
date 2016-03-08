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
<link href="/Public/css/user.css" rel="stylesheet" type="text/css" />
<!--main-->
<div class="width">
	<div class="b_top">
    	<!--left-->
        	<div class="uleft b_3">
            	<div class="face">
            		<span <?php if(empty($user_info["photo"])): ?>style="display: none;"<?php endif; ?>><img src="<?php echo ($user_info["photo"]); ?>" width="90" height="90"/></span>
                   <h3>身份：<?php if(($user_type) == "1"): ?>学生<?php else: ?>教师<?php endif; ?></h3>
                    <p><i></i><?php echo ($nick_name); ?></p>
                </div>
            	<ul>
                    <li class="ico01"><a href="./">个人主页</a></li>
                    <li class="ico02"><a href="/member/info">个人信息</a></li>
                    <li class="ico02"><a href="/member/myshijuan">我的试卷</a></li>
                    <!-- <li class="ico03"><a href="">在线测试</a></li>
                    <li class="ico04"><a href="">在线练习</a></li>
                    <li class="ico05"><a href="">学习进度</a></li>
                    <li class="ico06"><a href="">错题集</a></li>
                    <li class="ico07"><a href="">能力评估</a></li> -->
                    <li class="ico08"><a href="/member/mycollect">我的收藏</a></li>
                    <li class="ico08"><a href="/member/onlinetest">在线练习</a></li>
                    <?php if(($user_type) == "1"): ?><li class="ico08"><a href="/member/studentceping">在线测评</a></li><?php else: ?><li class="ico08"><a href="/member/teacherceping">在线测评</a></li><?php endif; ?>
                    <li class="ico08"><a href="/member/mynote">我的错题集</a></li>
                    <!-- <li class="ico09"><a href="">购买</a></li>
                    <li class="ico10"><a href="">我的老师</a></li>
                    <li class="ico11"><a href="">我的作业</a></li> -->
                </ul>
            </div>
        
        <!--right-->
        <div class="uright">
        	<h3><span>完善个人信息</span>个人信息</h3>
            
            <div class="page_content">
               <ul id="user">               
                    <li><span>头像设置：</span><a href="javascript:;" class="c_l" id="check_photo">更换图像</a></li>
                    <li id="photo_id"  <?php if(empty($user_info["photo"])): ?>style="display: none;"<?php endif; ?>><span>&nbsp;</span><a href=""><img src="<?php echo ($user_info["photo"]); ?>" width="90" height="90"/></a></li>
                    <li><span>昵称：</span><?php echo ($user_info["nick_name"]); ?></li>
                    <li><span>真实姓名：</span><font class="c_z"><?php if(!empty($$user_info["real_name"])): echo ($user_info["real_name"]); else: ?>暂无<?php endif; ?></font></li>
                    <li><span>密码：</span><a href="javascript:void(0);" class="c_l" id="change_a">修改密码</a>
                    	<div class="passwordBox" style="width:300px;float:left;display: none;">
							<ul>
								<li><input class="input8f8" name="password" id="password" placeholder="旧密码" type="password" ajaxurl="/member/ajaxCheckPassword" id="oldPassword"  datatype="*6-20" nullmsg="密码不能为空！" errormsg="密码请输入6-20个字符"></li>
								<li><input class="input8f8" placeholder="新密码"  name="new_password" type="password" id="newPassword"  datatype="*6-20" nullmsg="密码不能为空！" errormsg="密码请输入6-20个字符"></li>
								<li><input class="input8f8" placeholder="确认新密码" recheck="new_password" name="check_new_password" type="password" id="check_new_password" datatype="*6-20" nullmsg="密码不能为空！" ></li>
								<li><button class="btn btn3" id="savePass" style="line-height:25px;margin-right:20px;" >保存</button><a href="javascript:;" id="cancel">取消</a></li>
							</ul>
						</div>	
                    </li>
                    <li><span>手机：</span><a href="javascript:;" class="c_l" id="bind_phone">绑定</a></li>
                    <li class="cline"></li>
                    <?php if(!empty($user_info["email"])): ?><li><span>注册邮箱：</span><?php echo ($user_info["email"]); ?><a href="" class="c_z">（已验证）</a></li><?php endif; ?>
                    <!-- <li><span>地区：</span><font class="c_z">暂无</font></li>
                    <li><span>学校：</span><font class="c_z">暂无</font></li>
                    <li><span>任教科目：</span>语文</li> -->
               </ul>
           </div>
           
        </div>
    
    </div>
</div>

<div id="head_photo" style="display:none;">
	<div class="windown-choosePhotoContent" style="width:600px;height:300px" id="windown-content">
		<input type="hidden" id="phpto_hidden" />
		<div class="choosePhoto">
			<div class="changearea">
				<div class="img">
					<span>男</span>
					<ul>
						<?php if(is_array($menPhotos)): $i = 0; $__LIST__ = $menPhotos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li id="wp_li"><a href="javascript:;" class="photo_a"><img src="<?php echo ($vo); ?>" alt="" id="wp_img" height="80" width="80"></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
						
					</ul>
				</div>
				<div class="img">
					<span>女</span>
					<ul>
						<?php if(is_array($womenPhotos)): $i = 0; $__LIST__ = $womenPhotos;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li id="wp_li"><a href="javascript:;" class="photo_a"><img src="<?php echo ($vo); ?>" alt="" id="wp_img" height="80" width="80"></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			</div>
			<!-- <div class="upload">
				<span class="myPhoto"><object id="SWFUpload_0" type="application/x-shockwave-flash" data="/js/swfupload/swfupload.swf?preventswfcaching=1448804964502" class="swfupload" height="24" width="100"><param name="wmode" value="opaque"><param name="movie" value="/js/swfupload/swfupload.swf?preventswfcaching=1448804964502"><param name="quality" value="high"><param name="menu" value="false"><param name="allowScriptAccess" value="always"><param name="flashvars" value="movieName=SWFUpload_0&amp;uploadURL=%2FUser%2FuploadLogo&amp;useQueryString=false&amp;requeueOnError=false&amp;httpSuccess=&amp;assumeSuccessTimeout=0&amp;params=USERID%3D14488048361167825263%26amp%3BPHPSESSID%3Db5c5ef190adf3f3d5e7384a3e0757470&amp;filePostName=Filedata&amp;fileTypes=*.jpg%3B*.jpeg%3B*.gif%3B*.png&amp;fileTypesDescription=%E5%8F%AA%E5%85%81%E8%AE%B8%E4%B8%8A%E4%BC%A0%E5%9B%BE%E7%89%87&amp;fileSizeLimit=512KB&amp;fileUploadLimit=5&amp;fileQueueLimit=1&amp;debugEnabled=false&amp;buttonImageURL=%2Fimages%2Fmyht%2Fuploadtxt.jpg&amp;buttonWidth=100&amp;buttonHeight=24&amp;buttonText=&amp;buttonTextTopPadding=1&amp;buttonTextLeftPadding=1&amp;buttonTextStyle=&amp;buttonAction=-110&amp;buttonDisabled=false&amp;buttonCursor=-1"></object></span><a href="javascript:;" class="replace" onclick="change()">换一批</a>
				<div id="fsUploadProgress" style="height:0;display:none">
				</div>
			</div> -->
			<div class="clearBoth">
				<button class="btn btn3" id="phpto_btn" style="height:40px;float:right;margin:20px;">确认</button>
			</div>
		</div>
	</div>
</div>
<div id="phone_dialog" class="mbPaneltxt" style="display:none;">
	<div class="mbdelQues" style="width:400px;padding:20px 0 20px 25px">
		<form id="sjbd" method="post" action="/User/boundPhone">
			<ul>
				<li style="line-height:32px;margin:10px 0;"><label class="fl ft14"><span class="co_f43c5e">*</span>我的手机号：</label><input class="fl wh3 input666" style="height:30px;width:148px;border:1px solid #d8d8d8;float:left" id="phone" name="phone" type="text"><span class="onShow" id="phoneTip" style="margin:0;padding:0;background:transparent none repeat scroll 0 0"></span></li>
				<div class="clear;"style="padding:5px;"></div>
				<li style="line-height:32px"><input id="getPhoneCode" style="display:none;height:32px;line-height:30px;border:1px solid #d8d8d8;margin:0;margin-left:90px;padding:0 15px;font-size:14px" value="获取短信效验码" type="button">
				<div class="fl" id="defaultButton" style="cursor:pointer;   border:1px solid #d8d8d8;background:#eee;height:30px;line-height:30px;margin-top:10px;margin-left:90px;padding:0 15px;font-size:14px;font-family:'微软雅黑',Verdana,Geneva,sans-serif">
					获取短信效验码
				</div>
				<div id="phoneMsg" style="display:none;float:left">
				</div>
				</li>
				<div class="clear" style="padding:5px;">
				</div>
				<li style="line-height:32px"><label class="fl ft14"><span class="co_f43c5e">*</span>手机验证码：</label><input class="wh3 input666" id="duanxin" name="duanxin" style="height:30px;width:148px;border:1px solid #d8d8d8;float:left" type="text"><span class="onShow" style="margin:0;padding:0;background:transparent none repeat scroll 0 0" id="duanxinTip"></span></li>
				<div class="clear" style="padding:5px;">
				</div>
				<!-- <li style="line-height:32px"><label class="fl ft14"><span class="co_f43c5e">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</span>   验证码：</label><input class="yzm input929 fl" id="verify" name="verify" style="height:28px;width:70px;border:1px solid #d8d8d8" type="text"><span class="yzimg">  <img src="/Public/verify2/1448800321855" id="verifyImg" alt="验证码" style="margin-top:3px" height="28" width="80"></span><span style="vertical-align:top;font-family:'微软雅黑',Verdana,Geneva,sans-serif;margin-top:1px"><a href="javascript:;" onclick="fleshVerify()">看不清?换一�</a></span><span id="verifyTip" style="float:right"></span></li>
				<div class="clear" style="padding:5px;"> -->
				</div>
				<li><input id="changePhoneTijiao" value="提 交" class="btn btn3" style="margin-left:169px;height:40px;font-family:'微软雅黑',Verdana,Geneva,sans-serif;margin-top:1px" onclick="return $.formValidator.pageIsValid("1")" type="submit"></li>
			</ul>
		</form>
	</div>
</div>
<link href="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.js" type="text/javascript"></script>
<script src="/Public/js/dialog.js" type="text/javascript"></script>
<script>
	$('.photo_a').click(function(){
		$('#phpto_hidden').val($(this).find('img').attr('src'));
	});
	$('#phpto_btn').click(function(){
		if($('#phpto_hidden').val()!=''){
			$.getJSON(
			'/member/ajaxeditPhoto',
			{photo:$('#phpto_hidden').val()},
			function(data){
				if(data.status=='success'){
					$('#photo_id').show();
					$('#photo_id').find('img').attr('src',data.photo);
					$('.face').find('span').show();
					$('.face').find('span').find('img').attr('src',data.photo);
					$('#head_photo').dialog("close");
				}
			}
			);
		}
	});
	$('#change_a').click(function(){
		$(this).hide();
		$(this).next().show();
	});
	$('#cancel').click(function(){
		$('.passwordBox').hide();
		$('#change_a').show();
	});
	$('#savePass').click(function(){
		$.getJSON(
			'/member/ajaxResetPassword',
			{password:$('#password').val(),new_password:$('#newPassword').val(),check_new_password:$('#check_new_password').val()},
			function(data){
				if(data.status=='success'){
					alert('密码修改成功！');
					$('.passwordBox').hide();
					$('#change_a').show();
				}else{
					alert(data.info);
				}
			}
		);
	});
</script>
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script>
<link href="/Public/css/validform.css" rel="stylesheet" type="text/css" />
<script>
	$(
		function(){
   		$(".page_content").Validform(
   			{tiptype:3}
   		);
    });
</script>
<!--footer-->
	<div id="footer">
		<div class="footer">
			<div><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a> </div>  
			<div><span style="font-size:12px;">COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</span></div>
		</div>
	</div>
	
	
</body>
</html>
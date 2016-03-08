<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>哈学库（精品试卷）</title>
<link href="/Public/css/public.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/home.css" rel="stylesheet" type="text/css" />
<script src="/Public/js/jquery.min.js" type="text/javascript"></script>
<script>
$(function(){
	//subnav
	$(".menu li").hover(function(){
		$(".subnav",this).css("display","block");
		$(this).addClass("hover");
	},function(){
		$(".subnav",this).css("display","none");
		$(this).removeClass("hover");
	});
	//
	$(".menu li").hover(function(){
		$(".subnav1",this).css("display","block");
		$(this).addClass("hover");
	},function(){
		$(".subnav1",this).css("display","none");
		$(this).removeClass("hover");
	});
		//subnav
	$(".sj_ul li").hover(function(){
		$("dl",this).css("display","block");
		$(this).addClass("hover");
	},function(){
		$("dl",this).css("display","none");
		$(this).removeClass("hover");
	});
	//
	
})
function select_course(id){
	
}
</script>
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
        <ul class="left menu">
        		<li class="mid">|</li>
            	<li><a href="/">首页</a></li>
                <li class="mid">|</li>
                <li><a href="">题库</a>
                <div class="subnav">
                	<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a onclick="select_course(<?php echo ($vo["id"]); ?>);" style="cursor:pointer;"><?php echo ($vo["course_name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                </li>
                <li class="mid">|</li>
                <li><a href="">组卷</a>
                <div class="subnav1">
                	<a href="/hand/">手工组卷</a>
                	<a href="/smart/">智能组卷</a>
                </div>
                </li>
                <li class="mid">|</li>
                <li><a href="/ceping/">在线测评</a></li>
                <li class="mid">|</li>
                <li><a href="/onlinetest/">在线练习</a></li>
               	<!-- <li class="mid">|</li>
                <li><a href="">产品</a></li>
                <li class="mid">|</li>
                <li><a href="">论坛</a></li> -->
         </ul>
    </div>
</div>
<script>
function select_course(id){
	$.getJSON(
		'/index.php/home/tiku/ajaxSelectCourse',
		{id:id},
		function(data){
			//alert(data.id);
			if(data.id){
				window.location.href = '/tiku/';
			}
		}
		
	);
}
</script>
<div class="width">
	<div class="tk_bg_mm">
		<div class="tit">
		    <div class="right"><a href="">首页</a> >><a href="">找回密码</a></div>
		</div>
	</div>
</div>	
<div class="width">
	<div class="findPwd">
		<div>
			<div style="width:680px;height:68px;margin:40px auto 20px;padding-left:67px;">
				<div class="resetpass_a resetpass_act">
					验证手机号
				</div>
				<div class="resetpass_a">
					重置登录密码
				</div>
				<div class="resetpass_a" style="width:186px;padding-right:0">
					找回成功
				</div>
			</div>
		</div>
		<div style="line-height:80px;text-align:center;display:block;clear:both;font-size:16px;font-family:微软雅黑;padding-left:33px">
			请输入您绑定的手机号
		</div>
		<div class="txt1">
		<div class="userForm1">
			<form action="/member/resetpassPhone_two" method="post" name="findPwdform" id="findPwdform">
				<ul>
					<li><label>验证码：</label><input class="confirm" id="yzm" name="verify_code" type="text" datatype="*">
					<a href="javascript:;"><img id="verify" src="/member/verifyCode" alt="验证码" height="35" width="70" onclick="$(this).attr('src','/member/verifyCode?'+Math.random());"></a>
					<a href="javascript:;" id="change_code" onclick="$('#verify').attr('src','/member/verifyCode?'+Math.random());">换一换</a>
					</li>
					<li><label>手机号：</label><input class="wh5" id="phone" name="telphone" datatype="m" type="text" ajaxurl="/member/ajaxCheckUser"><input class="send_phone_button send_confirm_button" id="send_phone" value="获取验证码" onclick="send_vcode($(this))" type="button"></li>
					<li><label>手机验证码：</label><input class="wh5" id="yanzhengma"  datatype="*" name="yanzhengma" type="text" ajaxurl="/member/ajaxCheckPvCode"><span class="onShow" style="margin:0;padding:0;background:transparent none repeat scroll 0 0" id="yanzhengmaTip"></span></li>
					<li><input class="btn btn2" value="继续" style="padding:5px 20px;margin:20px 150px;" type="submit"></li>
				</ul>
			</form>
		</div>
	</div>
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
<script>
var tin;
function send_vcode(obj){
	if($('#yzm').val()=='' || $('#phone').val()=='') return false;
	$.post(
		'/member/ajaxCheckVerifyCode',
		{param:$('#yzm').val()},
		function(data){
			if(data.status=='y'){
				
				$.getJSON(
				'/member/ajaxSendPvCode',
				{telphone:$('#phone').val()},
				function(data){
					if(data.status=='error'){
						alert("验证码发送失败！");
					}else{
						obj.attr('onclick','');
						obj.val('重新获取(60)');
						tin = setInterval("timer()",1000);
					}
				}
				);
			}else{
				alert('验证码有误！');
				return false;
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
		$('#send_phone').val(html);
		
	}
	
}
</script>
<!--footer-->
<!--footer-->
<div id="footer">
	<div class="footer">
    	<div class="left"><a href="">关于我们</a> 丨 <a href="">隐私保护政策</a>  丨  <a href="">服务条款</a> 丨 <a href="">联系我们</a>   丨  
        <br />
        COPYRIGHT 2015  哈学库  版权所有   www.haxueku.com</div>
        <div class="right"></div>
    </div>
</div>

</body>
</html>
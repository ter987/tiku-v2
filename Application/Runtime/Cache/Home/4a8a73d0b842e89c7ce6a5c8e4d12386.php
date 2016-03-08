<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
<!--main-->
<div class="width">
	<div class="tk_bg">
		<!--标题-->
        <div class="tit"> <a href="">组卷</a> 〉<a href="">手工组卷</a></div>
        
        <div class="p10"> <h2>选择科目</h2><div class="line"></div></div>
        <form name="" id="f1" method="post" action="/hand/start">        
        <div class="p10">
        <select class="sel" name="course">
        	<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>">高中<?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        	</select>
        </div>
        
        <div class="p10"> <h2>选择试卷版式</h2><div class="line"></div></div>
        <div class="clear"></div>
        <div class="box1000 guideStep">
			<div class="navIcon">
				<ul>
					<li class="icon1"><label class="js_aaa"><span>随堂练习</span><font><input name="banshi" value="1" id="随堂练习" checked="checked" type="radio"></font><u>A4/B5竖版</u></label></li>
					<li class="icon1"><label class="js_aaa"><span>单元测试</span><font><input name="banshi" value="2" id="单元测试" type="radio"></font><u>A4/B5竖版</u></label></li>
					<li class="icon2"><label class="js_aaa"><span>期中考试卷</span><font><input name="banshi" value="3" id="期中考试卷" type="radio"></font><u>B4/A3横版</u></label></li>
					<li class="icon2"><label class="js_aaa"><span>期末考试卷</span><font><input name="banshi" value="4" id="期末考试卷" type="radio"></font><u>B4/A3横版</u></label></li>
					<li class="icon2"><label class="js_aaa"><span>高考模拟卷</span><font><input name="banshi" value="5" id="高考模拟卷" type="radio"></font><u>B4/A3横版</u></label></li>
				</ul>
			</div>
			<div class="guideBox" id="guideBox02">
				<div id="tipbar2" class="tipbar">
				</div>
				<div id="step2" class="tipbox">
					<ol class="progress">
						<li class="on"></li>
						<li></li>
					</ol>
					<div class="tipword">
					</div>
					<span onclick="hideTip0()" class="tipboxBtn" style="left:117px;"></span>
				</div>
			</div>
			<div class="clear"></div>
		</div>
        <div class="clear"></div>
        </form>
        <div class="zj_tit" onclick="$('#f1').submit()" style="cursor:pointer">选择试题</div>
    </div>
</div>

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
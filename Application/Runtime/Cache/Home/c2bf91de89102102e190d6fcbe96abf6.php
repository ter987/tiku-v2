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
<!--main-->
<div class="width">
    	
        <div class="p10"> <h2>测评试卷名称</h2><div class="line"></div></div>
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10">
            	<div class="left mr10">
            		<form method="post" action="" id="course_form">
	        		<select class="sel" id="course_select" name="course_select">
	        		<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($current_course) == $vo["id"]): ?>selected=""<?php endif; ?> >高中<?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
	        		</select>
	        		</form>
            	</div>
            	<form method="post" action="/ceping/xuanti" id="ceping_form" name="">
                <div class="left mr10">
					<input id="" class="wh1" type="text" name="title" value="<?php echo ($title); ?>">
				</div>
            </div>
        </div>
        
        <div class="clear"></div>
        <div class="mt20">
        <div class="p10">
         <h2>选择测评学生</h2><div class="line"></div></div>
        <div class="clear"></div>
        </div>
        
         <div class="mt20">
        	<div class="p10" style="height:35px;line-height: 35px;">
            	<input id="" class="wh1" type="text" name="student" value="" datatype="*" ajaxurl="/ceping/ajaxCheckStudent" nullmsg="不能为空！">
            </div>
        </div>
        
        <div class="clear"></div>
        <div class="mt20">
        	<div class="p10">
            	<div class="left mr10"><span class="button_3">添加新学生</span></div>
                <div class="left"><span class="button_3">从我的学生中选择</span></div>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10"> <h2>测评试卷信息</h2><div class="line"></div></div>	
        </div>

		<div class="clear"></div>
         
        <div class="mt20">
        	<div class="p10">
            	<div class="left"><span class="button_2">试题数量</span></div>
                <div class="left"><span class="button_4">—</span></div>
                <div class="left"><input type="text" value="0" name="shiti_num" id="shiti_num" style="line-height:30px;border:none; width:30px;height:30px;" class="ipt"></div>
                <div class="left"><span class="button_3">+</span></div>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10">
            	<div class="left"><span class="button_2">测评总分</span></div>
                <div class="left"><span class="button_4">—</span></div>
                <div class="left"><input type="text" value="0" name="ceshi_score" id="ceshi_score" style="line-height:30px;border:none; width:30px;height:30px;" class="ipt"></div>
                <div class="left"><span class="button_3">+</span></div>
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10">
            	<div class="left"><span class="button_2">测评时间</span></div>
                <div class="left"><span class="button_4">—</span></div>
                <div class="left"><input type="text" value="0" name="ceshi_time" id="ceshi_time" style="line-height:30px;border:none; width:30px;height:30px;" class="ipt"></div>
                <div class="left"><span class="button_3">+</span></div>
            </div>
        </div>
        
        <div class="clear"></div>
        
        
        <div class="mt10">
        	<div class="p10"> <h2>选择选题方式</h2><div class="line"></div></div>	
        </div>
        
        <div class="clear"></div>
        <div class="mt10">
        	<div class="p10"> 
            	<select class="left sel mr10"><option>手工选题</option></select>                
            </div>	
        </div>
        
        <div class="clear"></div>
        
                 <div class="zj_tit" style="cursor: pointer;">开始选题</div>
</form>
</div>
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script>
<link href="/Public/css/validform.css" rel="stylesheet" type="text/css" />
<script>
$(
	function(){
   	$("#ceping_form").Validform(
   		{tiptype:3}
   	);
});
$('#course_select').change(function() {
	$('#course_form').submit();
});
$('.button_3').click(function(){
	var obj = $(this).parent().parent().find('input[type=text]');
	var num = parseInt(obj.val())+1;
	obj.val(num);
	
	
});
$('.button_4').click(function(){
	var obj = $(this).parent().parent().find('input[type=text]');
	if(parseInt(obj.val())>0){
		var num = parseInt(obj.val())-1;
		obj.val(num);
		
	}
});
$('.ipt:input').blur(function() {
	if(isNaN($(this).val())){
		alert('请输入数字');
	}
});	
$('.zj_tit').click(function(){
	if(parseInt($('#ceshi_score').val())<1){
		alert('请输入测试总分');
		return false;
	}
	if(parseInt($('#shiti_num').val())<1){
		alert('请输入试题数量');
		return false;
	}
	if(parseInt($('#ceshi_time').val())<1){
		alert('请输入测试时间');
		return false;
	}
	$('#ceping_form').submit();
});
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
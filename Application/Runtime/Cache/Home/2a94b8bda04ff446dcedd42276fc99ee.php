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
<script type="text/javascript" src="/Public/js/jquery.SuperSlide.2.1.1.js"></script>

<!--main-->
<div class="index_banner">
  <div class="bd">
    <ul>
      <li style="background: transparent url(/Public/images/index_bg.gif) no-repeat scroll center 0px; display: none;"><a href="#" target="_blank"></a></li>
      <li style="background: transparent url(/Public/images/index_bg.gif) no-repeat scroll center 0px; display: none;"><a href="#" target="_blank"></a></li>
      <li style="background: rgb(196, 207, 235) url(/Public/images/3.jpg) no-repeat scroll center 0px; display: list-item; opacity: 0.943356;"><a href="#" target="_blank"></a></li>
      <li style="background: rgb(197, 237, 253) url(/Public/images/4.jpg) no-repeat scroll center 0px; display: none;"><a href="#" target="_blank"></a></li>
    </ul>
  </div>
  <div class="hd">
    <ul><li class="">1</li><li class="">2</li><li class="on">3</li><li class="">4</li></ul>
  </div>
  <div class="width">
    <!--第一屏-->
    <div class="login">
	<!-- 登录 -->
	<?php if(empty($user_id)): ?><div class="login_wz">
        <form method="post" action="/member/login">
          <input type="text" size="40" value="账号：" onblur="if(this.value=='')this.value=defaultValue" onfocus="if(this.value==defaultValue)this.value=''" class="ip" name="username" datatype="*" ajaxurl="/member/ajaxCheckUser" nullmsg="账号不能为空！" errormsg="请输入合法的账号！">
          <br>
          <input type="password" size="40" value="密码：" onblur="if(this.value=='')this.value=defaultValue" onfocus="if(this.value==defaultValue)this.value=''" class="ip" name="password" datatype="*6-20" nullmsg="密码不能为空！" errormsg="密码请输入6-16个字符">
          <br>
          <input type="checkbox" name="checkbox">
          下次自动登录
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/member/resetpass">忘记密码</a><br>
                    <input type="submit" size="40" value="登录" class="btn" name="submit">
                    <br>
                    <input type="button" size="40" value="注册" class="btn" onclick="window.location.href='/member/register'">
                    <br>
        </form>
      </div>
      <?php else: ?>
	  <!-- 登录结束 -->
	  <!-- 登陆后状态 -->
	  <div style="background:#c7dff6;margin-top:30px;padding:30px;">
		<div style="border-bottom:1px solid #999;line-height:50px;height:50px;">
			 欢迎您，<span style="color:#F43C5E;font-size:20px;"><?php echo ($nick_name); ?></span><?php if(($user_type) == "2"): ?>老师<?php else: ?>学生<?php endif; ?> <!-- 学生 -->
		</div>
		<div onclick="window.location.href='/member/'" style="cursor:pointer;width:200px;height:30px;line-height:30px;background:#F43C5E;text-align:center;color:#fff;font-size:18px;padding:5px 20px;margin-top:20px;">
			进入我的主页
		</div>
		<div>
			<div style="height:30px;line-height:30px;">
				<span style="float:left;"><a href="/member/info">修改密码</a></span>
				<!-- <span style="float:right;"><a style="color:#5487E3;" href="">激活学习卡</a></span> -->
				<div style="clear:both;"></div>
			</div>
			<!-- <div style="height:30px;line-height:30px;">
				<a href="">使用帮助</a>
			</div> -->
		</div> 
	  </div><?php endif; ?>
	   <!-- 登陆后状态结束 -->
    </div>
  </div>
</div>

	<script type="text/javascript">
		jQuery(".index_banner").slide({ titCell:".hd ul", mainCell:".bd ul", effect:"fade",  autoPlay:true, autoPage:true, trigger:"click" });
	</script>


<div class="width">
    
    <!--第二屏-->
    <div class="mt20">
    	<div class="w440 left">
        	<div class="subject"><span><a href=""></a></span>高中试卷汇总<i>Self Q&A </i> </div>
            
         <div class="notice" style="margin:0 auto">
		<div class="tab-hd">
				<ul class="tab-nav">
				  <li><a href="#" target="_blank">语文 </a></li>
				  <li><a href="#" target="_blank">数学</a></li>
				  <li><a href="#" target="_blank">英语</a></li>
				  <li><a href="#" target="_blank">化学</a></li>
				  <li><a href="#" target="_blank">物理</a></li>
                  <li><a href="#" target="_blank">生物</a></li>
                  <li><a href="#" target="_blank">物理</a></li>
                  <li><a href="#" target="_blank">历史</a></li>
                  <li><a href="#" target="_blank">地理</a></li>
                  <li><a href="#" target="_blank">政治</a></li>
				</ul>
		</div>
		<div class="tab-bd">
			<div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
			
            <div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真5454题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
            
			<div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真545题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
 
		</div>
	</div>
          <script type="text/javascript">jQuery(".notice").slide({ titCell:".tab-hd li", mainCell:".tab-bd",delayTime:0 });</script>                  
        </div>
        
        <div class="w500 left">

        	<div class="subject"><span><a href=""></a></span>初中试卷汇总<i>Self Q&A </i> </div>
            
         <div class="notice" style="margin:0 auto">
		<div class="tab-hd">
				<ul class="tab-nav">
				  <li><a href="#" target="_blank">语文 </a></li>
				  <li><a href="#" target="_blank">数学</a></li>
				  <li><a href="#" target="_blank">英语</a></li>
				  <li><a href="#" target="_blank">化学</a></li>
				  <li><a href="#" target="_blank">物理</a></li>
                  <li><a href="#" target="_blank">生物</a></li>
                  <li><a href="#" target="_blank">物理</a></li>
                  <li><a href="#" target="_blank">历史</a></li>
                  <li><a href="#" target="_blank">地理</a></li>
                  <li><a href="#" target="_blank">政治</a></li>
				</ul>
		</div>
		<div class="tab-bd">
			<div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
			
            <div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真5454题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
            
			<div class="tab-pal">
					<ul>
						<li><span>2015-07-22</span><a href="#">·[真545题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
						<li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
                        <li><span>2015-07-22</span><a href="#">·[真题]2015年高考上海市语文（带分析）</a></li>
					</ul>
			</div>
            

		</div>
	</div>
          <script type="text/javascript">jQuery(".notice").slide({ titCell:".tab-hd li", mainCell:".tab-bd",delayTime:0 });</script>
            
        </div>
    </div>
    
</div>

<div class="width">
	<div class="mt20">
    <div class="subject"><span><a href=""></a></span>最新资讯<i>Self Q&A </i> </div>
    <ul class="n_news">
    	<li> <div class="pic"><a href=""><img src="images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
        
        <li> <div class="pic"><a href=""><img src="images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
        
         <li> <div class="pic"><a href=""><img src="images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
        
         <li> <div class="pic"><a href=""><img src="/Public/images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
        
         <li> <div class="pic"><a href=""><img src="images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
        
         <li> <div class="pic"><a href=""><img src="images/add.gif" /></a></div>
        	<div class="text">
            	<div class="tt"><a href="">让历史说话   用史实发言</a></div>
            	<div class="inf">初中女生休学一年写23万字谍战小说</div>
            </div>
        </li>
     </ul>
        
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
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
	<div class="tk_bg">
		<!--标题-->
        	<div class="p10">
                   <h1 class="p10">
                   		<span class="left mr10">在线测评</span>
                        
                        <span class="right"><input type="submit" class="button_3" value="发起测评"  style="border:none;cursor: pointer;"/></span>
                   </h1>
                   
                   <div class="clear"></div>
                   
                   
                   <div class="mt20">
					   <div class="clear"></div>
					   <ul class="zxlx_ul">
					   	<?php if(is_array($tiku_data)): $key = 0; $__LIST__ = $tiku_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><li>
								<div class="x_icon"><?php echo ($key); ?></div>
								<div class="x_con">
									<p><?php echo (htmlspecialchars_decode($vo["content"])); ?></p>
									<?php if($vo['options']){ $options = json_decode($vo['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E'); ?>
			                    	<p>
			                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
			                    			<p ><label><?php echo $options_index[$k] ?>.<?php echo $val ?></label></p>
			                    		<?php } ?>
			                    		
			                    	</p>
			                    	<?php } ?>
			                    	
								</div>
						   </li><?php endforeach; endif; else: echo "" ;endif; ?>
						  
                   </div>
             </div>
        
        
    </div>
</div>

<!-- 代码 开始 -->
<!-- <div id="box">
	<span class="title">点击弹出</span>
	<span class="q_top">测评考试</span>
    <span class="q_bottom">问题反馈</span>
	<ul class="qq">
		<li class="hover"><a href="">1</a></li>
        <li><a href="">2</a></li>
        <li><a href="">3</a></li>
        <li><a href="">4</a></li>
	</ul>
	
</div> -->
<!-- 代码 结束 -->
<script>
	$('.button_3').click(function(){
		$.getJSON(
			'/ceping/start',
			{},
			function(data){
				if(data.status=='success'){
					alert("发起测评成功！");
					window.location.href='/member/';
				}else{
					alert('提交失败，请重试！');
				}
			}
		);
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
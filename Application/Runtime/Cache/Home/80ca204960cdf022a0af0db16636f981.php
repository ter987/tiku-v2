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
            
            <div class="jcj ">
            	<select class="sel" id="course_sel">
                	<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php if(($course_id) == $vo["id"]): ?>selected=""<?php endif; ?> value="<?php echo ($vo["id"]); ?>"><?php if(($vo["course_type"]) == "1"): ?>高中<?php else: ?>初中<?php endif; echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                	</select>
           	
            <div class="clear"></div>
            
            <div class="b_4 mt20">
            	<!--左侧-->
            	<!-- <div class="jspan1 left">
                	<ul class="jc_ul">
                    	<li class="jc_tit">知识点</li>
                        <li><a href="">全部</a></li>
                        <li><a href="">基础知识及语言表达</a></li>
                        <li><a href="">诗歌鉴赏</a></li>
                        <li><a href="">文言文阅读</a></li>
                        <li><a href="">现代文阅读</a></li>
                        <li><a href="">作文及写作</a></li>
                        <li><a href="">名句默写</a></li>
                        <li><a href="">其他</a></li>
                    </ul>
                
                </div>    -->             
                <!--右侧-->
                <div class="jspan2 left">
                	<ul class="jc_list">
                        <?php if(is_array($note_data)): $i = 0; $__LIST__ = $note_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                        	<p><?php echo (htmlspecialchars_decode($vo["content"])); ?></p>
                            <?php if($vo['options']){ $options = json_decode($vo['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E'); ?>
	                    	
	                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
	                    			<p><?php echo $options_index[$k] ?>.<?php echo $val ?></p>
	                    		<?php } ?>
	                    	
	                    	<?php } ?>
                            <!-- <div class="jcbtn1">分享</div> <div class="jcbtn">纠错</div> -->
                            <div class="jcbtn">纠错</div>
                            <p>	<span class="jc_btn">加强训练</span>
                            	<span class="jc_btn">下载试题</span>
                            </p>
                        </li><?php endforeach; endif; else: echo "" ;endif; ?>
                       
                	</ul>
                	<style>
                        	.current{
                        		background: #f43c5e none repeat scroll 0 0;
							    border: 1px solid #f28700;
							    color: #fff;
							    font-weight: bold;
							    padding: 6px 10px;
                        	}
                        </style>
					<div class="page">
						<?php echo ($page_show); ?>
					</div>
                </div>
                
              </div>  
              
            </div>
        </div>
    
    </div>
</div>
<script>
	$('#course_sel').change(function() {
		window.location.href = '/member/mynote?id='+$(this).val();
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
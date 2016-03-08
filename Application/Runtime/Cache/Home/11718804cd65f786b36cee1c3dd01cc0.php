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
<link href="/Public/css/teacher.css" rel="stylesheet" type="text/css" />

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
           <div class="quesList mt36">
			<h3><b>我的试卷</b></h3>
			<div>
				<form name="myexam" action="" method="post">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tbody>
					<tr>
						<th class="w5">
							试卷名称
						</th>
						<th class="w3">
							日期
						</th>
						<th class="w6" style="width:140px;">
							操作
						</th>
					</tr>
					<?php if(is_array($my_shijuan)): $i = 0; $__LIST__ = $my_shijuan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
						<td style="display:none">
							<?php echo ($vo["title"]); ?>
						</td>
						<td class="align-l allcheck">
							<input name="items[]" value="<?php echo ($vo["id"]); ?>" type="checkbox"><span><a href="/member/shijuandetail?id=<?php echo ($vo["id"]); ?>" style="margin-left:28px" target="_blank"><?php echo ($vo["title"]); ?></a></span>
						</td>
						<td>
							<?php echo (date("Y-m-d",$vo["create_time"])); ?>
						</td>
						<td class="handle">
							<span class="editor"><a href="/member/shijuandetail?id=<?php echo ($vo["id"]); ?>" style="margin-left:28px" target="_blank">编辑</a><font style="left:17px">编辑下载</font></span><span class="del" ><a href="javascript:;" id="<?php echo ($vo["id"]); ?>" class="deleteexam" style="margin-left:28px" examid="40457" >删除</a><font style="left:17px" >删除</font></span>
						</td>
					</tr><?php endforeach; endif; else: echo "" ;endif; ?>
					
					</tbody>
				</table>
			</div>
				<div class="allcheckBox">
					<div class="allcheck fl" style="margin-top:10px;">
						<label style="margin-top:4px"><input name="all_chose" id="CheckedAll" value="del_all" type="checkbox"><span>全选</span></label><label><input value="全部删除" type="submit"></label>
					</div>
					<style>
                        	.current{
                        		background: #f43c5e none repeat scroll 0 0;
							    border: 1px solid #f28700;
							    color: #fff;
							    font-weight: bold;
							    padding: 6px 10px;
                        	}
                        </style>
					<div class="fr">
						<div class="page">
							<?php echo ($page_show); ?>
						</div>
					</div>
					<div class="clear">
					</div>
				</div>
			</form>
		</div>
           
        </div>
    
    </div>
</div>
<script>
	$('.deleteexam').click(function(){
		$.getJSON(
			'/member/delShijuan',
			{id:$(this).attr('id')},
			function(data){
				if(data.status=='success'){
					location.reload();
				}else{
					alert('删除失败！');
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
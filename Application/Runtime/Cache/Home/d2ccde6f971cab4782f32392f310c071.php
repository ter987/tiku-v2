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
	<div class="tk_bg">
		<!--标题-->
		<div class="tit">
			<div class="left" style="color:#6159b1;font-size:30px;font-family:宋体;margin-left:20px;">试题详情</div>
		    <div class="right"><a href="/">题库</a> >><a href="/tiku/c<?php echo ($tiku_data["course_id"]); ?>/">高中<?php echo ($tiku_data["course_name"]); ?></a> >> <a href="">试题详情</a></div>
		</div>
        <div class="clear"></div>
    </div>
</div>
<div class="width">
	<div style="background:#ddf1fe;border:1px solid #e1e1e1;height:40px;line-height:40px;padding:0px 10px;">
		<div class="left">计算题</div>
		<div class="right"><a href="##">报错</a></div>
	</div>
	<div style="background:#fff;">
		<div style="padding:20px;">
			<p class="c_z"><b>来源：<?php echo (htmlspecialchars_decode($tiku_data["source_name"])); ?></b></p>
			<p><?php echo (htmlspecialchars_decode($tiku_data["content"])); ?></p>
			<p>
				<?php if($tiku_data['options']){ $options = json_decode($tiku_data['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E'); ?>
                    	<p>
                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
                    			<span style="float:left;line-height:18px;font-size:14px;padding-right:30px;" class="em2"><?php echo $options_index[$k] ?>.<?php echo $val ?></span>
                    		<?php } ?>
                    	</p>
                    	<?php } ?>
			</p>
		</div>	
	</div>
	<div class="detail">	
		<div class="jc_list_b">
			<div class="left mr10">
				<span class="jc_btn">加入试卷</span>
				<span class="jc_btn">查看解析</span>
				<span class="jc_btn">下载试题</span>
			</div>
			<div class="right mr10" style="font-size:12px;">
				<div class="left mr10">
					<span style="line-height:30px;">
						难度系数:<font class="c_z"><?php echo ($tiku_data["section"]); ?></font>
					</span>
				</div>
				<div class="left">浏览:</div>
				<div class="left mr10"><font class="c_z"><?php echo ($tiku_data["clicks"]); ?></font>次</div>
				<div class="left">评分:</div>
				<div class="left"><i class="dis score score3"></i></div>
		    </div>
		</div>
	</div>
</div>
<div class="width">
	<div style="background:#ddf1fe;border:1px solid #e1e1e1;height:40px;line-height:40px;padding:0px 10px;">
		<div class="left">解答</div>
	</div>
	<div style="background:#fff;padding:10px 20px;">
<!-- 		<div style="border-bottom:1px dashed #e1e1e1;padding:20px 0;">	
			<div class="left jc_hui mr10">考点</div>
			<div class="left" style="line-height:30px;">
				<span>共点力的平衡 滑动摩擦力、动摩擦因数、静摩擦力</span>
			</div>
			 <div class="clear"></div>
		</div> -->
		<div style="border-bottom:1px dashed #e1e1e1;padding:20px 0px;">	
			<div class="left jc_hui mr10">试题解析</div>
			<div class="left" style="line-height:30px;width:800px;">
				<p> <?php echo (htmlspecialchars_decode($tiku_data["analysis"])); ?></p>
			</div>
			 <div class="clear"></div>
		</div>
		<div style="padding:20px 0;">	
			<div class="left jc_hui mr10">答案</div>
			<div class="left" style="line-height:30px;">
				<span><?php echo (htmlspecialchars_decode($tiku_data["answer"])); ?></span>
			</div>
			 <div class="clear"></div>
		</div>
	</div>
</div>
<div class="width">
	<div style="background:#ddf1fe;border:1px solid #e1e1e1;height:40px;line-height:40px;padding:0px 10px;">
		<div class="left">试题推荐</div>
		<!-- <div class="right">
			<span>
				<font class="c_z">1</font>/5
			</span>
			<span>
				<a href="##">上一题</a>
			</span>
			<span>
				<a href="##">下一题</a>
			</span>
		</div> -->
	</div>
	<div style="background:#fff;">
		<div class="left" style="padding:20px;width:900px;">
			<p><?php echo (htmlspecialchars_decode($recommend["content"])); ?></p>
			
		</div>	
		<div class="left" style="margin-top:20px;font-size:12px;">
			<a target="_blank" href="/tiku/detail/<?php echo ($recommend["id"]); ?>.html">[详情]</a>
		</div>
		 <div class="clear"></div>
	</div>
</div>

<div class="rightNav">
	<div class="Nav_title">
		<a href="" class="add1">再加点题</a><a href="" class="add2">再加点题</a>
	</div>
	<div class="txt">
		<div class="fl" style="height:265px">
			<div>
				<a href="javascript:;" id="lock" rel="nofollow">点击收起</a>
			</div>
			<p>
				我的试卷
			</p>
			<div style="color:#1887E3;cursor:pointer;position:absolute;left:7px;bottom:46px" class="sdfr2">
				清空
			</div>
			<a style="" href="javascript:;" id="backTop">回顶</a>
		</div>
		<div class="fr" style="padding:15px 15px 46px">
			<div style="min-height:215px;margin-bottom:20px;margin-left:3px">
				<ul id="wo_de_shi_juan">
					<?php if(is_array($tiku_cart)): $i = 0; $__LIST__ = $tiku_cart;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><?php echo ($vo["type_name"]); ?>(<span><?php echo ($vo["num"]); ?></span>/<?php echo ($vo["num"]); ?>)</li><?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
				<?php if(($ceping) != "yes"): ?><div>
					<a href="/shijuan/" class="btn btn3" id="mbsaveQues" rel="nofollow">生成试卷</a>
					<div style="clear:both">
					</div>
				</div>
				<?php else: ?>
				<div>
					<a href="/ceping/exam" class="btn btn3" id="mbsaveQues" rel="nofollow">生成测评</a>
					<div style="clear:both">
					</div>
				</div><?php endif; ?>
			</div>
			<div style="color:#333;font-size:12px;margin:0 auto;text-align:center;width:96px">
				暂不支持迅雷下载
			</div>
			<div class="suggestion">
				<a href="" target="_blank">问题反馈</a>
			</div>
		</div>
	</div>
</div>


<!--收藏试卷-->
	<div id="sc_sj" style="background:#fff;display:none;">
		<div class="mbstowTag">
			<h3>收藏成功</h3>
			<form method="post" name="shoucang" action="">
				<input type="hidden" name="tiku_id" id="tiku_id"/>
				<ul>
					<li>
					<label>打标签：</label>
					<input class="wh6" type="text" name="tagName" value="">
					<u>添加多个标签时，请用英文逗号分隔。</u>
					</li>
					<!-- <li>
					<label>标签：</label>
					<div>
						<a href="javascript:;">易错题</a>
						<a href="javascript:;">经典题</a>
						<a href="javascript:;">好题</a>
					</div>
					</li> -->
					<li>
					<input class="btn2" type="button" value="确定">
					</li>
				</ul>
			</form>
		</div>
	</div>
<script>
		function add_tiku(id,obj){
		$.getJSON(
			'/tiku/ajaxAddTiku',
			{id:id},
			function(data){
				
				if(data.status=='success'){
					var tips = '';
					if(data.type_data != null){
						$.each(data.type_data,function(index,dom){
							//alert('s');
							tips += '<li>'+dom.type_name+'(<span>'+dom.num+'</span>/'+dom.num+')</li>';
						}
						
						);
					}else{
						tips = '';
					}
					
				}
				if(obj.html()=='加入试卷'){
					obj.html('移出试卷');
					obj.attr('class','jc_btn_check');
				}else{
					obj.html('加入试卷');
					obj.attr('class','jc_btn');
				}
				
				
				$('#wo_de_shi_juan').html(tips);
			}
		);
	}
	function dia_log(){
	this.init();
	this.check();
};
dia_log.prototype.init = function(){
	$("#parthead2").mouseover(function(){
		$(".mbquesBtn5").show();
	}).mouseleave(function(){
		$(".mbquesBtn5").hide();
	});	
	$(".questypebody").mouseover(function(){
		$(".quesopmenu").show();
	}).mouseleave(function(){
		$(".quesopmenu").hide();
	});
	$('#pui_title').mouseover(function(){
		$(".mbquesBtn1").show();
	});
	$('#pui_title').mouseleave(function(){
		$(".mbquesBtn1").hide();
	});
};
dia_log.prototype.check = function(){
	$("#mbanswerSheet").click(function(){
		$("#datika").dialog({
			title:"下载答题卡",
			width:"580",
			height:"auto",
			modal:true,
			create: function(){
			},
			beforeClose: function() {
				$('#datike').dialog("destroy");
			}
		});
	});
};

	$(".collect").click(function(){
		var tag = '';
		var obj = $(this);
		$.getJSON(
			'/member/ajaxCollect',
			{id:$(this).attr('name')},
			function(data){
				if(data.status=='notlogin'){
					window.location.href = '/member/login';
				}else if(data.status=='success' && data.action=='add'){
						obj.removeClass('jc_btn');
						obj.addClass('jc_btn_check');
						obj.html('取消收藏');
						$('.wh6').val(data.tag);
						$('#tiku_id').val(data.tiku_id);
						$("#sc_sj").dialog({
						title:"收藏试卷",
						width:"540",
						height:"auto",
						modal:true,
						create: function(){
							
						},
						beforeClose: function() {
							$('#datike').dialog("destroy");
						}
					});
				}else if(data.status=='success' && data.action=='delete'){
					obj.removeClass('jc_btn_check');
					obj.addClass('jc_btn');
					obj.html('收藏');
				}
			}
		);
		
	});
	

$(function(){
	new dia_log();
});
$('.btn2').click(function() {
	$.getJSON(
			'/member/ajaxAddTag',
			{id:$('#tiku_id').val(),tag:$('.wh6').val()},
			function(data){
				$( "#sc_sj" ).dialog( "close" );
			}
	);
});
$('.sdfr2').click(function(){
	$.getJSON(
			'/tiku/ajaxdelcart',
			{},
			function(data){
				if(data.status=='success'){
					location.reload();
				}
			}
	);
});
</script>

<link href="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.js" type="text/javascript"></script>
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
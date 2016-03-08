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
    	
        <div class="p10"> <h2>选择科目</h2><div class="line"></div></div>
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10">
        		<form method="post" action="" id="course_form">
        		<select class="sel" id="course_select" name="course_select">
        		<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($current_course) == $vo["id"]): ?>selected=""<?php endif; ?> >高中<?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
        		</select>
        		</form>
        	</div>
        </div>
        
        <div class="clear"></div>
        <div class="p10"> <h2>设置试题数量</h2><div class="line"></div></div>
        <div class="clear"></div>
        
        <div class="mt20">
        	<div class="p10">
            	
                <ul class="ch_ul quesTypes" >
                	
                	<li>
						
						<div style="height:30px;margin-left:15px;" id="<?php echo ($vo["id"]); ?>">
							<div style="padding:4px 0;float:left;">
								<button type="button" value="-" class="minus1" ></button>
							</div>
							<div style="float:left;">	
								<input value="0"  maxlength="3"  class="countInput"  type="text">
							</div>
							<div style="padding:4px 0;float:left;">	
								<button type="button" value="+" class="add" ></button>
							</div>
							<div class="clear"></div>
						</div>
                    </li>
                 
                    
                 </ul>
                 
            </div>
        </div>
        
        <div class="clear"></div>
        <div class="mt10">
        	<div class="p10"> <h2>设置考卷难度</h2><div class="line"></div></div>	
        </div>

		<div class="clear"></div>
         
        <div class="mt20" id="difficulty">
        	<h3 class="p10"><a href="javascript:;" class="c_z mr10" reg="1">容易</a>  丨  
            <a href="javascript:;" class="mr10" reg="2">较易</a>   丨 
            <a href="javascript:;" class="mr10" reg="3">一般</a>   丨  
            <a href="javascript:;" class="mr10" reg="4">较难</a>   丨  
            <a href="javascript:;" class="mr10" reg="5">困难</a>
            
            </h3>	
        </div>
        
        <div class="clear"></div>
        <div class="mt10">
        	<div class="p10"> <h2>选择考查范围</h2><div class="line"></div></div>	
        </div>
        
        <div class="clear"></div>
        <div class="mt10">
        	<div class="p10"> 
        		<form method="post" action="" id="zsd_form">
            	<select class="left sel mr10" id="zsd_select" name="zsd_select">
            		<option value="zsd" <?php if(($current_zsd) == "zsd"): ?>selected=""<?php endif; ?>>综合知识点</option>
            		<?php if(is_array($version_data)): $i = 0; $__LIST__ = $version_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($current_zsd) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["version_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            	</select>
            	</form>
            	<!-- <select class="left sel mr10"><option>人教版</option></select>
                <input type="submit" value="搜索" class="mr10 button_2" style="border:none;" />
                <input type="checkbox" class="mr10" />全选 -->
                
            </div>	
        </div>
        
        <div class="clear"></div>
		
		<div class="mt10">
        	<div class="p10"> <h2>请选择要考查的知识点</h2><div class="line"></div></div>	
        </div>
			<div class="knowledgeMenu guideStep">
				<!--      <div class="hasNav">-->
				<?php if(($zsd_select) == "1"): if(is_array($top_point)): $k = 0; $__LIST__ = $top_point;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="js_nav use <?php if(($k) == "1"): ?>onHover<?php endif; ?>" id="253">
					<h3 id="<?php echo ($vo["id"]); ?>" class="js_h3"><?php echo ($vo["point_name"]); ?></h3>
				</div><?php endforeach; endif; else: echo "" ;endif; ?>
				<?php else: ?>
				<?php if(is_array($top_point)): $k = 0; $__LIST__ = $top_point;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="js_nav use <?php if(($k) == "1"): ?>onHover<?php endif; ?>" id="253">
					<h3 id="<?php echo ($vo["id"]); ?>" class="js_h3"><?php echo ($vo["book_name"]); ?></h3>
				</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
			</div>
			<div style="height: auto; padding: 0px;" class="knowledgeTxtBox">
				<div class="knowledgeTxt guideStep">
					<h3><font>您已经选择了<b class="zs_num">0</b>个知识点。</font><a style="display: block;" href="javascript:;" class="btn btn9" id="all_zsd">选择全部知识点</a><a href="javascript:;" class="btn btn10" id="no_all_zsd" style="display: none;">取消全部知识点</a></h3>
					<?php if(($zsd_select) == "1"): if(is_array($second_point)): $k = 0; $__LIST__ = $second_point;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="checkboxBox" id="div_<?php echo ($vo["top_id"]); ?>" <?php if(($k) != "1"): ?>style="display:none;"<?php endif; ?> >
						<h4><label id="js_zs_top"><input  name="items_all[]" id="CheckedAll" value="<?php echo ($vo["top_name"]); ?>" class="js_1"  type="checkbox"><span id="top_point"><?php echo ($vo["top_name"]); ?></span></label></h4>
						
						<ul class="js_zhishidian3">
							<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class=""><label><input name="items[]" value="<?php echo ($v["id"]); ?>"  id="<?php echo ($v["id"]); ?>"  class="js_2" type="checkbox"><span><?php echo ($v["point_name"]); ?></span></label></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
						
					</div><?php endforeach; endif; else: echo "" ;endif; ?>
					<?php else: ?>
					<?php if(is_array($second_point)): $k = 0; $__LIST__ = $second_point;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="checkboxBox" id="div_<?php echo ($vo["top_id"]); ?>" <?php if(($k) != "1"): ?>style="display:none;"<?php endif; ?> >
						<h4><label id="js_zs_top"><input  name="items_all[]" id="CheckedAll" value="<?php echo ($vo["top_name"]); ?>" class="js_1"  type="checkbox"><span id="top_point"><?php echo ($vo["top_name"]); ?></span></label></h4>
						<?php if(is_array($vo["top_chapter"])): $i = 0; $__LIST__ = $vo["top_chapter"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><h5><?php echo ($vs["chapter_name"]); ?></h5>
						<ul class="js_zhishidian3">
							<?php if(is_array($vs["childs"])): $i = 0; $__LIST__ = $vs["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class=""><label><input name="items[]" value="<?php echo ($v["id"]); ?>"  id="<?php echo ($v["id"]); ?>"  class="js_2" type="checkbox"><span><?php echo ($v["chapter_name"]); ?></span></label></li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul><?php endforeach; endif; else: echo "" ;endif; ?>
					</div><?php endforeach; endif; else: echo "" ;endif; endif; ?>
					<form method="post" action="/onlinetest/start" id="start">
					<?php if(($zsd_select) == "1"): ?><input type="hidden" name="zsd_select" value="1" />
					<?php else: ?>
						<input type="hidden"  name="zsd_select" value="0" /><?php endif; ?>
					<div class="hasKnowledge">
						<h4>已选知识点（共<span class="zs_num">0</span>个）：</h4>
						<ul id="tag_box">
							
						</ul>
					</div>
					<div class="clear"></div>
					<div class="guideStep">
						
						<div class="clearBoth fl mb">
							
								<input type="hidden" name="shiti_num" value="0" id="shiti_num" />
							<input type="hidden" name="point_num" value="0" id="point_num" />
							<input type="hidden" id="difficulty_id" name="difficulty_id" value="1" />
							<a  id="submit_ajax" class="btn btn5" style="margin-left:270px;" >开始练习</a>
						</div>
						
					</div>
					</form>
				</div>
			</div>
			<div class="clear">
			</div>
		
		

</div>
<script>
	$('#course_select').change(function() {
		$('#course_form').submit();
	});
	$('#zsd_select').change(function() {
		$('#zsd_form').submit();
	});
	$('#difficulty').find('a').click(function(){
		$('.c_z').removeClass('c_z');
		$(this).addClass('c_z mr10');
		
		$('#difficulty_id').val($(this).attr('reg'));
	});
	$('.js_h3').click(function(){
		$('.onHover').removeClass('onHover');
		$(this).parent().addClass('onHover');
		$('#div_'+$(this).attr('id')).show();
		$('#div_'+$(this).attr('id')).siblings('.checkboxBox').hide();
	});
	$('.js_zhishidian3').find(':input').click(function(){
		if($(this).attr('checked')!='checked'){
			$(this).attr('checked',true);
			var add = '<li class="" id="'+$(this).val()+'"><a href="javascript:;" onclick="del_point($(this),'+$(this).val()+');">'+$(this).next().html()+'</a><input name="items_submit[]" value="'+$(this).val()+'" type="hidden"></li>';
			$('#tag_box').append(add);
			$('.zs_num').html($('#tag_box').find('li').length);
			$('#point_num').val($('#tag_box').find('li').length);
		}else{
			$(this).removeAttr('checked');
			$('#tag_box').find('li[id='+$(this).val()+']').remove();
			$('.zs_num').html($('#tag_box').find('li').length);
			$('#point_num').val($('#tag_box').find('li').length);
		}
	});
	$('div #CheckedAll').click(function(){
		if($(this).attr('checked')!='checked'){
			$(this).attr('checked',true);
			$(this).parent().parent().parent().find('li input').attr('checked',' ');
			//alert($(this).parent().parent().parent().find('li input:checked'));
			$(this).parent().parent().parent().find('li input:checked').each(function(dom){
				if($('#tag_box').find('li[id='+$(this).val()+']').length==0){
					var add = '<li class="" id="'+$(this).val()+'"><a href="javascript:;" onclick="del_point($(this),'+$(this).val()+');">'+$(this).next().html()+'</a><input name="items_submit[]" value="'+$(this).val()+'" type="hidden"></li>';
					$('#tag_box').append(add);
				}
			});
			$('.zs_num').html($('#tag_box').find('li').length);
			$('#point_num').val($('#tag_box').find('li').length);
		}else{
			$(this).removeAttr('checked');
			$(this).parent().parent().parent().find('li input').removeAttr('checked');
			
			$(this).parent().parent().parent().find('li input').each(function(dom){
				if($('#tag_box').find('li[id='+$(this).val()+']').length>0){
					$('#tag_box').find('li[id='+$(this).val()+']').remove();
				}
			});
			$('.zs_num').html($('#tag_box').find('li').length);
			$('#point_num').val($('#tag_box').find('li').length);
		}
	});
	function del_point(obj,id){
		obj.parent().remove();
		$('input[id='+id+']').removeAttr('checked');
		$('.zs_num').html($('#tag_box').find('li').length);
		$('#point_num').val($('#tag_box').find('li').length);
	}
	$('.add').click(function(){
		var obj = $(this).parent().parent().find('input[type=text]');
		var num = parseInt(obj.val())+1;
		obj.val(num);
		$('#shiti_num').val(num);
		
	});
	$('.minus1').click(function(){
		var obj = $(this).parent().parent().find('input[type=text]');
		if(parseInt(obj.val())>0){
			var num = parseInt(obj.val())-1;
			obj.val(num);
			$('#shiti_num').val(num);
		}
	});
	$('.countInput').blur(function() {
		if(parseInt($(this).val())=='NaN'){
			$(this).val(0);
		}else{
			$('#shiti_num').val(parseInt($(this).val()));
		}
	});
	$('#submit_ajax').click(function(){
		if(parseInt($('#shiti_num').val())<1) return false;
		if(parseInt($('#point_num').val())<1) return false;
		$('#start').submit();
		
	});
	$('#all_zsd').click(function(){
		$(this).hide();
		$('#no_all_zsd').show();
		$('.checkboxBox input').attr('checked',' ');
		
		//alert($(this).parent().parent().parent().find('li input:checked'));
		$(this).parent().parent().parent().find('li input:checked').each(function(dom){
			if($('#tag_box').find('li[id='+$(this).val()+']').length==0){
				var add = '<li class="" id="'+$(this).val()+'"><a href="javascript:;" onclick="del_point($(this),'+$(this).val()+');">'+$(this).next().html()+'</a><input name="items_submit[]" value="'+$(this).val()+'" type="hidden"></li>';
				$('#tag_box').append(add);
			}
		});
		$('.zs_num').html($('#tag_box').find('li').length);
		$('#point_num').val($('#tag_box').find('li').length)
	});
	$('#no_all_zsd').click(function(){
		$(this).hide();
		$('#all_zsd').show();

		$('.checkboxBox input').removeAttr('checked');
		
		$(this).parent().parent().parent().find('li input').each(function(dom){
			if($('#tag_box').find('li[id='+$(this).val()+']').length>0){
				$('#tag_box').find('li[id='+$(this).val()+']').remove();
			}
		});
		$('.zs_num').html($('#tag_box').find('li').length);
		$('#point_num').val($('#tag_box').find('li').length);
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
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
<script>
	/* 选择题型   **/
	function select_grade(c,id){
	    var href = window.location.href;
	    var reg = /jingpin\/(\w*)g(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/g\d+/g,'');
	    	}else{
	        	var loc = href.replace(/g\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/jingpin\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/jingpin\//g,'jingpin/'+c+id+str);
	    }
	    window.location.href = loc;
	}

	/* 选择题目特点 **/
	function select_feature(c,id){
	    var href = window.location.href;
	    var reg = /jingpin\/(\w*)f(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
		if(href.search(/r\d{1}/)!=-1){
			href = href.replace(/r\d{1}/,'');
		}
		if(id==0){
			href = href.replace(/a\d{1}/,'');
			href = href.replace(/w\d{1}/,'');
			href = href.replace(/y\d{4}/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/f\d+/g,'');
	    	}else{
	        	var loc = href.replace(/f\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/jingpin\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/jingpin\//g,'jingpin/'+c+id+str);
	    }
	    window.location.href = loc;
	}

	/* 选择试卷类型 **/
	function select_year(c,id){
	    var href = window.location.href;
	    var reg = /jingpin\/(\w*)y(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/y\d+/g,'');
	    	}else{
	        	var loc = href.replace(/y\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/jingpin\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/jingpin\//g,'jingpin/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	/* 选择地区 **/
	function select_province(c,id){
	    var href = window.location.href;
	    var reg = /jingpin\/(\w*)a(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/a\d+/g,'');
	    	}else{
	        	var loc = href.replace(/a\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/jingpin\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/jingpin\//g,'jingpin/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	
</script>
<!--main-->
<div class="width">
	<div class="tk_bg">
		<!--标题-->
        <div class="tit"><span> <a href="">题库</a> 〉<a href="">精品试卷</a></span>精品试卷</div>
        
        <!--小标-->
        <ul class="sj_ul">
        	<li style="width:194px;">&nbsp;&nbsp;&nbsp;&nbsp;科目：高中语文<div class="clear"></div>
                <dl class="sub_ul">
                      <dt><a href="">高中数学</a></dt>
                      <dt><a href="">高中数学</a></dt>
                </dl>
            </li>
            <li><a href="/tiku/">知识点选题</a></li>
            <li><a href="/tongbu/">章节选题</a></li>
            <li class="hover"><a href="/jingpin/">精品试卷</a></li>
            <!-- <li><a href="">我的题库</a></li> -->
        </ul>
        
        <div class="clear"></div>
        
        <!-- <div class="ss"><form><input type="button" class="btn"  value="搜索"/><input type="text" class="text" /></form></div> -->
        
        <!--快速搜索-->
        <div id="quick_goods" class="mt20">
            <ul>
                <li class="fixed"><strong>年级：</strong></li>
                <li>
                    <dl>
                       <dt <?php if(($grade) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_grade('g',0)">全部</a></dt>
                        <?php if(is_array($grade_data)): $i = 0; $__LIST__ = $grade_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($grade) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_grade('g',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
               		 </dl>
                </li>
            </ul>
            <div class="clear"></div>

            <ul>
                <li class="fixed"><strong>类型：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($feature_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature('f',0)">全部</a></dt>
                        <?php if(is_array($source_type)): $i = 0; $__LIST__ = $source_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($feature_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature('f',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["type_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div>
            
            <ul>
                <li class="fixed"><strong>年份：</strong></li>
                <li>
                    <dl>
                       <dt <?php if(($year) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_year('y',0)">全部</a></dt>
                        <?php if(is_array($year_data)): $i = 0; $__LIST__ = $year_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($year) == $vo["year"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_year('y',<?php echo ($vo["year"]); ?>)"><?php echo ($vo["year"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
               		 </dl>
                </li>
            </ul>
            <div class="clear"></div>
                                                                                         
            <ul>
                <li class="fixed"><strong>地区：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($province_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_province('a',0)">全部</a></dt>
                        <?php if(is_array($province_data)): $i = 0; $__LIST__ = $province_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($province_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_province('a',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["province_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div>
        </div>
        
        <div id="quick_order">
            <ul>
                <li><a href="">排序：</a></li>
                <li><a href="">最新<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                <li><a href="">难度<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                <li><a href="">使用频率<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                <li><a href="">好评<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
            </ul>
            <div class="clear"></div>
        </div>
        
        <!--题库列表-->
        <div class="clear"></div>
        <ul class="tk_ul">
        <?php if(!empty($source_data)): if(is_array($source_data)): $i = 0; $__LIST__ = $source_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
            	<div class="pic"><a href="/jingpin/detail/<?php echo ($vo["id"]); ?>.html" target="_blank"><img src="/Public/images/tk.gif" /></a></div>
        		<div class="text">
                	<p><a href="/jingpin/detail/<?php echo ($vo["id"]); ?>.html" target="_blank" class="t_d"><?php echo ($vo["source_name"]); ?></a></p>
					<p></p>
                    <p class="f14 c9 mt10">贡献者：109138518&nbsp;&nbsp;
                    上传时间：<?php echo (date('Y-m-d',$vo["update_time"])); ?>&nbsp;&nbsp;14：22：55&nbsp;&nbsp;浏览<?php echo ($vo["clicks"]); ?>次</p>
               </div>
               <div class="pf">
               		<div class="left mr10">评分：</div>
                    <div class="left score5 dis mr10"></div>
                    <div class="left">4.6分</div>
               </div>
           </li><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php else: ?>
        	<li style="background:#fff;">
                    <div style="padding:30px 0px;">
						<div class="jc_null">	
						</div>
					    <div class="jc_n_text">
							<p>抱歉，暂时没有符合条件的试卷，我们将会尽快补充内容。请更换筛选条件。</p>
						</div>
					</div>					
				</li><?php endif; ?>
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
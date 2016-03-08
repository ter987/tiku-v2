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
	function select_type(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)t(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/t\d+/g,'');
	    	}else{
	        	var loc = href.replace(/t\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	/* 选择难度   **/
	function select_difficulty(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)d(\d+)(\w*)(\/)/g;

		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/d\d+/g,'');
	    	}else{
	        	var loc = href.replace(/d\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    //alert(loc);
	    window.location.href = loc;
	}
	/* 选择题目特点 **/
	function select_feature(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)f(\d+)(\w*)(\/)/g;
		
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
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	/* 选择试卷类型 **/
	function select_wenli(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)w(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
	    if(href.search(reg)!=-1){
	    	if(id==0){
	    		var loc = href.replace(/w\d+/g,'');
	    	}else{
	        	var loc = href.replace(/w\d+/g,c+id);
	       }
	    }else{
	    	var str = '';
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	/* 选择试卷类型 **/
	function select_year(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)y(\d+)(\w*)(\/)/g;
		
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
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	/* 选择地区 **/
	function select_province(c,id){
	    var href = window.location.href;
	    var reg = /tiku\/(\w*)a(\d+)(\w*)(\/)/g;
		
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
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+c+id+str);
	    }
	    window.location.href = loc;
	}
	
</script>
<script src="/Public/js/menu.js" type="text/javascript"></script>
<!--main-->
<div class="width">
	<div class="tk_bg">
		<!--标题-->
        <div class="tit"><a href="##">题库</a> 〉<a href="">精品试卷</a> </div>
        
        <!--小标-->
        <ul class="sj_ul">
        	<li style="width:194px;">&nbsp;&nbsp;&nbsp;&nbsp;科目：高中语文<div class="clear"></div>
                <dl class="sub_ul">
                      <dt><a href="">高中数学</a></dt>
                      <dt><a href="">高中数学</a></dt>
                </dl>
            </li>
            <li  class="hover"><a href="/tiku/">知识点选题</a></li>
            <li><a href="/tongbu/">章节选题</a></li>
            <li><a href="/jingpin/">精品试卷</a></li>
            <!-- <li><a href="">我的题库</a></li> -->
        </ul>
        
        <div class="clear"></div>
        
        <!-- <div class="ss"><form><input type="button" class="btn"  value="搜索"/><input type="text" class="text" /></form></div> -->
        
        <!--快速搜索-->
        <div id="quick_goods" class="mt20">
            <ul>
                <li class="fixed"><strong id="dsdsd">题型：</strong></li>
                <li>
                    <dl> 
                        <dt <?php if(($type_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_type('t',0)">全部</a></dt>
                        <?php if(is_array($tiku_type)): $i = 0; $__LIST__ = $tiku_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt  <?php if(($type_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_type('t',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["type_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
               		 </dl>
                </li>
            </ul>
            <div class="clear"></div>
                           
            <ul>
                <li class="fixed"><strong>难度：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($difficulty_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_difficulty('d',0)">全部</a></dt>
                        <?php if(is_array($tiku_difficulty)): $i = 0; $__LIST__ = $tiku_difficulty;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($difficulty_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_difficulty('d',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["section"]); ?>(<?php echo ($vo["description"]); ?>)</a></dt><?php endforeach; endif; else: echo "" ;endif; ?>

                    </dl>
                </li>
            </ul>
            <div class="clear"></div>
            
            <ul>
                <li class="fixed"><strong>试卷类型：</strong></li>
                <li>
                    <dl>                              
                        <dt <?php if(($feature_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature('f',0)">全部</a></dt>
                        <?php if(is_array($source_type)): $i = 0; $__LIST__ = $source_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($feature_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature('f',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["type_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div>
            <?php if(!empty($year_data)): ?><ul>
                <li class="fixed"><strong>年份：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($year) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_year('y',0)">全部</a></dt>
                        <?php if(is_array($year_data)): $i = 0; $__LIST__ = $year_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($year) == $vo["year"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_year('y',<?php echo ($vo["year"]); ?>)"><?php echo ($vo["year"]); ?>年</a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div><?php endif; ?>  
<!--             <?php if(!empty($tiku_feature_type)): ?><ul>
                <li class="fixed"><strong>试卷类型：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($feature_type_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature_type('r',0)">全部</a></dt>
                        <?php if(is_array($tiku_feature_type)): $i = 0; $__LIST__ = $tiku_feature_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($feature_type_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_feature_type('r',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["type_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div><?php endif; ?>         -->                                                                      
            <?php if(!empty($province_data)): ?><ul>
                <li class="fixed"><strong>地区：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($province_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_province('a',0)">全部</a></dt>
                        <?php if(is_array($province_data)): $i = 0; $__LIST__ = $province_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dt <?php if(($province_id) == $vo["id"]): ?>class="hover"<?php endif; ?>><a href="javascript:select_province('a',<?php echo ($vo["id"]); ?>)"><?php echo ($vo["province_name"]); ?></a></dt><?php endforeach; endif; else: echo "" ;endif; ?>
                    </dl>
                </li>
            </ul>
            <div class="clear"></div><?php endif; ?>
            <?php if(($wenli) == "1"): ?><ul>
                <li class="fixed"><strong>文理：</strong></li>
                <li>
                    <dl>
                        <dt <?php if(($wenli_id) == ""): ?>class="hover"<?php endif; ?>><a href="javascript:select_wenli('w',0)">全部</a></dt>
                        <dt <?php if(($wenli_id) == "1"): ?>class="hover"<?php endif; ?>><a href="javascript:select_wenli('w',1)">理数</a></dt>
                        <dt <?php if(($wenli_id) == "2"): ?>class="hover"<?php endif; ?>><a href="javascript:select_wenli('w',2)">文数</a></dt>
                        
                    </dl>
                </li>
            </ul>
            <div class="clear"></div><?php endif; ?> 
        </div>
        
    </div>
</div>


<div class="width">
	<div class="b_4">
    	<!--left-->
        <div class="tk_left mr10">
			<h3>知识点</h3>
            <ul class="zsd_ul ce">
            	<?php if(is_array($points)): $i = 0; $__LIST__ = $points;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
					<a class="xz" href="##" ><i class="ce_jia">+</i> <l id="<?php echo ($vo["id"]); ?>"><?php echo ($vo["point_name"]); ?></l></a>
					<?php if(!empty($vo["childs"])): ?><ul class="er">
						<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="e_li">
							<a href="##" "><i class="ce_jia">+</i><l id="<?php echo ($v["id"]); ?>">&nbsp;<?php echo ($v["point_name"]); ?></l></a>
							<?php if(!empty($vo["childs"])): ?><ul class="thr">
								<?php if(is_array($v["childs"])): $i = 0; $__LIST__ = $v["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><li> <a href="##" ><l id="<?php echo ($vs["id"]); ?>"><?php echo ($vs["point_name"]); ?></l></a> </li><?php endforeach; endif; else: echo "" ;endif; ?>
								
								<div class="clear"></div>
							</ul><?php endif; ?>
						</li><?php endforeach; endif; else: echo "" ;endif; ?>
					
					</ul><?php endif; ?>
				</li><?php endforeach; endif; else: echo "" ;endif; ?>	
                
				<div class="clear"></div>
            </ul>
        </div>
        
        <!--right-->
        <div class="tk_right">
                <div class="mt10 b_4">
                   <div class="dx left mr10">
                   	<form>
                    	<input type="checkbox" />过滤使用过的题目
                        <input type="checkbox" />收藏的试题
                    </form>
                   </div>
                   
				   <div class="left">
                    <ul class="qs_ul">
                        <li><a href="">排序：</a></li>
                        <li><a href="">最新<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                        <li><a href="">难度<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                        <li><a href="">使用频率<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                        <li><a href="">好评<img src="/Public/images/ico_down.gif" width="7" height="8"></a></li>
                    </ul>
                    <div class="clear"></div>
        	     </div>
               </div>
               <style>
               	.em2 img{
               		vertical-align: middle;
               	}
               </style>
               <div class="clear"></div>
               <ul class="tk_tm_ul">
               	<?php if(!empty($tiku_data)): if(is_array($tiku_data)): $i = 0; $__LIST__ = $tiku_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li>
                	<p class="c_z"><b>来源：<?php echo ($vo["source_name"]); ?></b></p>
                    <p><?php echo (htmlspecialchars_decode($vo["content"])); ?></p>
                    
                    	<?php if($vo['options']){ $options = json_decode($vo['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E'); ?>
                    	<p>
                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
                    			<span style="float:left;line-height:18px;font-size:14px;padding-right:30px;" class="em2"><?php echo $options_index[$k] ?>.<?php echo $val ?></span>
                    		<?php } ?>
                    	</p>
                    	<?php } ?>
                    
                     <!-- <div class="jcbtn1">纠错</div> -->
                            <div class="jc_list_b">
                                <div class="left mr10">
                                    <span class="jc_btn" id="<?php echo ($vo["id"]); ?>"  onclick="add_tiku(<?php echo ($vo["id"]); ?>,$(this))" style="cursor: pointer;">加入试卷</span>
                                    <a target="_blank" href="/tiku/detail/<?php echo ($vo["id"]); ?>.html"><span class="jc_btn">查看解析</span></a>
                                    <!-- <span class="jc_btn">下载试题</span> -->
                                    <span class="jc_btn collect" id="c_<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["id"]); ?>"  style="cursor: pointer;">收藏</span>
                                    <!-- <span class="jc_btn2">点赞</span>
                                    <span class="jc_btn2">分享</span> -->
                                </div>
                                
                                <div class="right mr10">
                                	<div class="left mr10">
                                        <span style="line-height:30px;">
                                            难度系数:<font class="c_z"><?php if($vo["difficulty_id"] == 1): ?>1.00-0.86<?php elseif($vo["difficulty_id"] == 2): ?>0.85-0.71<?php elseif($vo["difficulty_id"] == 3): ?> 0.7-0.56 <?php elseif($vo["difficulty_id"] == 4): ?>0.55-0.41<?php elseif($vo["difficulty_id"] == 5): ?>0.40-0.26<?php endif; ?></font>
                                        </span>
                                    </div>
                                	<div class="left">评分:</div>
                                    <div class="left"><i class="dis score score3"></i></div>
                               </div>
                           </div>
                           
               </li><?php endforeach; endif; else: echo "" ;endif; ?>
               <?php else: ?>
               <li style="background:#fff;">
                    <div style="padding:30px 0px;">
						<div class="jc_null">	
						</div>
					    <div class="jc_n_text">
							<p>抱歉，暂时没有符合条件的试题，我们将会尽快补充内容。请更换筛选条件进行选题。</p>
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
               <div class="page" style="width:768px;">
					<?php echo ($page_show); ?>
				</div>
        </div>
    
    </div>
</div>


<script type="text/javascript">
$('.tk_left').find('l').click(
	function(){
		var id = $(this).attr('id');
		var href = window.location.href;
	    var reg = /tiku\/(\w*)p(\d+)(\w*)(\/)/g;
		
		if(href.search(/\?p=\d+/)!=-1){
			href = href.replace(/\?p=\d+/,'');
		}
		if(href.search(/r\d{1}/)!=-1){
			href = href.replace(/r\d{1}/,'');
		}
	    if(href.search(reg)!=-1){
	        var loc = href.replace(/p\d+/g,'p'+id);

	    }else{
	    	var str = '';
	    	if(href.search(/tiku\/\w+/g)==-1){
	    		str = '/';
	    	}
	        var loc = href.replace(/tiku\//g,'tiku/'+'p'+id+str);
	    }
	    window.location.href = loc;
	}
);
$(
	function(){
		var pointid = <?php echo ($point_id); ?>;
		if(pointid!='null'){
			$('.tk_left').find("l[id=<?php echo ($point_id); ?>]").attr('style','color: rgb(244, 60, 94);');
			$('.tk_left').find("l[id=<?php echo ($point_id); ?>]").parent().parent().parent().show();
			$('.tk_left').find("l[id=<?php echo ($point_id); ?>]").parent().parent().parent().parent().parent().show();
		}
		//alert(<?php echo ($tikus_in_cart); ?>);
		var carts = <?php echo ($tikus_in_cart); ?>;;
		$.each(carts,function(index,dom){
			$(".tk_tm_ul").find("span[id="+dom.id+"]").html('移出试卷');
			$(".tk_tm_ul").find("span[id="+dom.id+"]").attr('class','jc_btn_check');
		});
		//alert(<?php echo ($tikus_in_collect); ?>);
		<?php if(!empty($user_id)): ?>var collect = <?php echo ($tikus_in_collect); ?>;
		$.each(collect,function(index,dom){
			$(".tk_tm_ul").find("span[id=c_"+dom.tiku_id+"]").html('取消收藏');
			$(".tk_tm_ul").find("span[id=c_"+dom.tiku_id+"]").attr('class','jc_btn_check collect');
		});<?php endif; ?>
	}
);
</script>

<!-- 右侧 试卷选项-->

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
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
			<div class="left" style="color:#6159b1;font-size:30px;font-family:宋体;margin-left:20px;">手工组卷</div>
		    <div class="right"><a href="">题库</a> >><a href="">手工组卷</a></div>
		</div>
        <div class="clear"></div>
    </div>
</div>
<div class="width" id="neirong">
	<div id="pui_root">
		<div id="pui_main">
			<div id="pui_head">
				<div id="pui_title" >
					<div class="pui_titlemenu">
						<a class="amendquestype mbquesBtn1" style="margin:6px 0;display:none">修改</a>
					</div>
					<div id="pui_maintitle" title="试卷主标题" style="padding:5px 70px 5px 0">
						<?php echo ($shijuan["title"]); ?>
					</div>
				</div>
				<div class="totalScore">
					<font>满分:</font><span id="zongfen_1"><?php echo ($score); ?></span>
					<div class="clear">
					</div>
				</div>
				<div style="text-align:center;padding:10px 0">
					班级：___________&nbsp;&nbsp;姓名：___________&nbsp;&nbsp;考号：___________&nbsp;&nbsp;
				</div>
			</div>
			
			<div id="pui_body">
				<?php if(!empty($first_juan)): ?><div class="paperpart" id="paperpart1">
					<div id="parthead3" class="parthead">
						<div class="partmenu">
							<a class="amendquestype mbquesBtn3" style="display:none;">修改</a>
						</div>
						<div id="partheadbox2" class="partheadbox">
							<div id="partname1" class="partname">
								<?php echo ($first_juan["t_title"]); ?>
							</div>
							<div id="partnote1" class="partnote">
								<?php echo ($first_juan["note"]); ?>
							</div>
						</div>
					</div>
					<div class="partbody" id="part2">
						<?php if(is_array($first_juan["shiti"])): $k = 0; $__LIST__ = $first_juan["shiti"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="questype" id="questype2_1" t_id="22">
							<div class="questypehead" id="questypehead2_1">
								<div class="questypemenu" style="">
									<span style="display: none;" juanNo="1" shitiId="<?php echo ($k); ?>"> </span>
									<a class="amendquestype mbquesBtn4" style="display: none;">修改</a>
									<a style="display: none;" class="amendquestype typemovedn">下移</a>
									<a style="display: none;" class="amendquestype typemoveup">上移</a>
								</div>
								<div class="questypeheadbox" id="questypeheadbox2_1">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
									<tr>
										<td>
											<div class="questypetitle">
												<span class="questypeindex"><b><?php echo ($vo["order_char"]); ?>、</b></span><span id="juan_1_<?php echo ($k); ?>" class="questypename"><?php echo ($vo["t_title"]); ?></span>
											</div>
										</td>
									</tr>
									</tbody>
									</table>
								</div>
							</div>
							<style>
			               	.em2 img{
			               		vertical-align: middle;
			               	}
			               </style>
							<div class="questypebody">
								<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><ul class="dragsort-ver">
									
									<li class="ajax_li">
									<div class="quesbox">
										<div style="display:none;" class="quesopmenu">
											<a style="display:none;" class="score">设定分值</a>
											<a class="icon3" id="c_<?php echo ($v["id"]); ?>" name="<?php echo ($v["id"]); ?>">收藏试题</a>
											<a class="answer">答案</a>
											<a class="del">删除</a>
											<a class="moveup">上移</a>
											<a class="movedn">下移</a>
										</div>
										<div class="quesdiv">
											<div>
												<span style="background-color:#fff" class="quesindex">
													<b><?php echo ($v["order_char"]); ?>.</b>
												</span>
												<span class="tips"></span>
												<span class="title_css">
													<?php echo (htmlspecialchars_decode($v["content"])); ?>
													<?php if($v['options']){ $options = json_decode($v['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D',4=>'E'); ?>
							                    	<p>
							                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
							                    			<span style="float:left;line-height:18px;font-size:14px;padding-right:30px;" class="em2"><?php echo $options_index[$k] ?>.<?php echo $val ?></span>
							                    		<?php } ?>
							                    	</p>
							                    	<?php } ?>
												</span>
											</div>
											<div style="display:none" class="quesTxt quesTxt2 quesTxtfno">
												<ul>
													<li><font>考点</font>
													<div class="fl">
														<a href="javascript:;">材料作文</a>
													</div>
													</li>
												</ul>
												<ul>
												</ul>
											</div>
										</div>
									</div>
									</li>
									
								</ul><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div><?php endif; ?>
				
				<?php if(!empty($second_juan)): ?><div class="paperpart" id="paperpart2">
					<div id="parthead2" class="parthead">
						<div class="partmenu">
							<a class="amendquestype mbquesBtn5" style="display:none;">修改</a>
						</div>
						<div id="partheadbox2" class="partheadbox">
							<div id="partname2" class="partname">
								<?php echo ($second_juan["t_title"]); ?>
							</div>
							<div id="partnote2" class="partnote">
								<?php echo ($second_juan["note"]); ?>
							</div>
						</div>
					</div>
					<div class="partbody" id="part2">
						<?php if(is_array($second_juan["shiti"])): $k = 0; $__LIST__ = $second_juan["shiti"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="questype" id="questype2_1" t_id="22">
							<div class="questypehead" id="questypehead2_1">
								<div style="" class="questypemenu">
									<span style="display: none;" juanNo="2" shitiId="<?php echo ($k); ?>"> </span>
									<a style="display: none;" class="amendquestype mbquesBtn4">修改</a>
									<a class="amendquestype typemovedn" style="display: none;">下移</a>
									<a class="amendquestype typemoveup" style="display: none;">上移</a>
								</div>
								<div class="questypeheadbox" id="questypeheadbox2_1">
									<table border="0" cellpadding="0" cellspacing="0" width="100%">
									<tbody>
									<tr>
										<td>
											<div class="questypetitle">
												<span class="questypeindex"><b><?php echo ($vo["order_char"]); ?>、</b></span><span id="juan_2_<?php echo ($k); ?>" class="questypename"><?php echo ($vo["t_title"]); ?></span>
											</div>
										</td>
									</tr>
									</tbody>
									</table>
								</div>
							</div>
							<div class="questypebody">
								<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><ul class="dragsort-ver">
									
									<li class="ajax_li">
									<div class="quesbox">
										<div style="display:none;" class="quesopmenu">
											<a style="display:none;" class="score">设定分值</a>
											<a class="icon3" id="c_<?php echo ($v["id"]); ?>" name="<?php echo ($v["id"]); ?>">收藏试题</a>
											<a class="answer">答案</a>
											<a class="del">删除</a>
											<a class="moveup">上移</a>
											<a class="movedn">下移</a>
										</div>
										<div class="quesdiv">
											<div>
												<span style="background-color:#fff" class="quesindex">
													<b><?php echo ($v["order_char"]); ?>.</b>
												</span>
												<span class="tips"></span>
												<span class="title_css">
													<?php echo (htmlspecialchars_decode($v["content"])); ?>
												</span>
											</div>
											<div style="display:none" class="quesTxt quesTxt2 quesTxtfno">
												<ul>
													<li><font>考点</font>
													<div class="fl">
														<a href="javascript:;">材料作文</a>
													</div>
													</li>
												</ul>
												<ul>
												</ul>
											</div>
										</div>
									</div>
									</li>
									
								</ul><?php endforeach; endif; else: echo "" ;endif; ?>
							</div>
						</div><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div><?php endif; ?>
				
			</div>
		</div>
	</div>
	<div class="clear">
	</div>
</div>
<iframe id="down_iframe" src="" style="display:none;"></iframe>
<!-- 右侧 试卷选项-->
<div class="rightNav">
	<div class="Nav_title">
		<a href="/tiku/" class="add1">再加点题</a><a href="" class="add2">再加点题</a>
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
				<div>
					<a href="javascript:;" class="btn btn3" id="mbsaveQues" rel="nofollow">保存试卷</a>
					<a href="javascript:;" class="btn btn3" id="mbdownQues" rel="nofollow">下载试卷</a>
					<a href="javascript:;" class="btn btn3" id="mbanswerSheet" rel="nofollow">下载答题卡</a>
					<div style="clear:both">
					</div>
				</div>
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
<!-- 下载答题卡弹层 -->
	<div id="datika" class="mbPaneltxt" style="overflow:hidden;display:none;">
		<div class="mbprintTxt">
			<form action="" method="post" name="myformd">
				<ul class="mt5">
					<li>
						<div class="tit">
							<span>格式</span>
						</div>
						<div class="fl">
							<label>
								<input name="doctype" value="doc" checked type="radio">
								<span>word2003</span>
								<font class="save_title_doc">高中语文随堂练习-20151029_yitiku.doc</font>
							</label>
							<label>
								<input name="doctype" value="docx" type="radio">
								<span>word2007/2010</span>
								<font class="save_title_docx">高中语文随堂练习-20151029_yitiku.docx</font>
							</label>
						</div>
					</li>
					<li>
						<div class="tit">
							<span>模板</span>
						</div>
						<label class="A4"><input name="model" value="A4" checked type="radio"><span>普通表格</span></label>
						<label class="B4"><input name="model" value="A3" type="radio"><span>标准答题卡</span></label>
						<label class="A3"><input name="model" value="B5" type="radio"><span>密集型答题卡</span></label>
					</li>
					<li><input value="&nbsp;下&nbsp;&nbsp;载&nbsp;" class=" btn2 mt load" style="margin-left:240px;margin-top:10px" id="xiazai" type="button"></li>
					<li style="text-align:center;padding-top:5px;color:#f43c5e">小提示：为保证试卷的正常下载，建议使用chrome浏览器、或IE8以上浏览器</li>
				</ul>
			</form>
		</div>
	</div>
	<!-- 保存试卷-->
	<div id="save_sj" style="background:#fff;display:none;">
		<div class="mbquesTxt" style="width:390px; height:220px;">
			<ul class="mt">
				<li><label>主标题：</label><textarea name="save_title" class="wh2"><?php echo ($shijuan_title); ?></textarea></li>
				<li><label>副标题：</label><textarea name="save_title1" class="wh2"><?php echo ($shijuan_subtitle); ?></textarea></li>
				<li><input type="button" class=" btn2 fine_save_paper" id="save_shijuan" value="保 存"></li>
			</ul>
		</div>
	</div>
	<!-- 下载试卷-->
	<div id="down_sj" style="background:#fff;display:none;">
		<div class="mbquesTxt" style="width:390px; height:220px;>
			<ul class="mt">
				<li><label>主标题：</label><textarea name="save_title" class="wh2"><?php echo ($shijuan_title); ?></textarea></li>
				<li><label>副标题：</label><textarea name="save_title1" class="wh2"><?php echo ($shijuan_subtitle); ?></textarea></li>
				<li><input type="button" class="btn2 fine_save_paper" id="download_shijuan" value="下一步"></li>
			</ul>
		</div>
	</div>
	<!-- 收藏试卷-->
	<div id="sc_sj" style="background:#fff;display:none;">
		<div class="mbstowTag">
			<h3>收藏成功</h3>
			<form method="post" name="shoucang" action="">
				<input type="hidden" name="tiku_id" id="tiku_id"/>
				<ul>
					<li>
					<label>打标签：</label>
					<input class="wh6" type="text" name="tagName" value="高中语文现代文阅读">
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
					<input class="btn2" id="addtag" type="button" value="确定">
					</li>
				</ul>
			</form>
		</div>
	</div>
	
	<!-- 保存 -->
	<div id="is_save" style="display:none;">
		<div class="mbquesTxt" style="width:380px; height:80px;">
			<div style="margin:30px 80px;width:260px;"><div class="succeed"></div><div style="float:left;width:220px;">保存试卷成功!您可以在个人主页中查看已保存的试卷。</div></div>
		</div>
	</div>
	<!-- 再次保存 -->
	<div id="is_save2" style="display:none;">
		<div class="mbquesTxt" style="width:280px; height:80px;">
			<div style="margin:30px 80px;"><div class="succeed"></div><div style="float:left;">试卷更新成功!</div></div>
		</div>
	</div>
	
	<!-- 下载试卷 第二步 -->
	<div id="down_sj2" style="display:none">
		<div class="mbprintTxt">
			<form action="" method="post" name="myformd">
				<ul class="mt5">
					<li>
					<div class="tit">
						<span>格式</span>
					</div>
					<div class="fl" id="doc_type">
						<!-- <label><input class="wp_hover" name="doctype" value="doc" checked type="radio"><span>word2003</span><font class="save_title_doc"><?php echo ($shijuan["title"]); ?>.doc</font>
						<div class="wp_value" style="text-align:center;padding-top:5px;color:#f43c5e;font-size:12px;text-indent:94px">
							为保证试卷显示正常，建议使用 Microsoft Office Word 打开下载的试卷
						</div>
						</label> --><label><input id="wp_hover1" name="doctype" value="docx" type="radio" checked=""><span>word2007/2010</span><font class="save_title_docx"><?php echo ($shijuan["title"]); ?>.docx</font>
						<div class="wp_value1" style="text-align:center;padding-top:5px;color:#f43c5e;font-size:12px;display:none;text-indent:94px">
							为保证试卷显示正常，建议使用 Microsoft Office Word 打开下载的试卷
						</div>
						</label><!-- <label><input class="wp_display" name="doctype" value="pdf" type="radio"><span>pdf</span><font class="save_title_pdf">高中语文随堂练习-20151129_yitiku.docx_yitiku.pdf</font></label> -->
					</div>
					</li>
					<li id="shijuan_model">
						<div class="tit">
							<span>模板</span>
						</div>
						<label class="A4"><input name="model" value="A4" checked="" type="radio"><span>A4纸竖排</span></label>
						<label class="A3"><input name="model" value="A3" type="radio"><span>A3纸横排</span></label>
						<label class="A4"><input name="model" value="B5" type="radio"><span>B5纸竖排</span></label>
						<label class="B4" style="margin:0px;"><input name="model" value="B4" type="radio"><span>B4纸横排</span></label>
					</li>
					<li id="answer_order">
						<div class="tit">
							<span>答案</span>
						</div>
						<label><input checked name="answer_order" style="margin-left:15px" value="2" type="radio"><span>试题跟答案解析分离</span></label>
						<label style="margin-left:67px"><input name="answer_order" value="1" type="radio"><span>试题后面紧跟答案解析</span></label>
					</li>
					<li><input value="&nbsp;下&nbsp;&nbsp;载&nbsp;"  class="btn btn2 mt load" style="margin-left:240px;margin-top:10px" id="xiazaishijuan" type="button"></li>
					<li style="text-align:center;padding-top:5px;color:#f43c5e">小提示：为保证试卷的正常下载，建议使用chrome浏览器、或IE8以上浏览器</li>
				</ul>
			</form>
		</div>
	</div>
	
	<!-- 修改主标题 -->
	<div id="modify1" style="background:#fff;display:none;">
		<div class="mbquesTxt" style="width:380px; height:220px;">
			<ul class="mt">
				<li><label>主标题：</label>
					<textarea name="ShijuanTitle" id="ShijuanTitle" class="wh4"><?php echo ($shijuan["title"]); ?></textarea>
				</li>
				<li><input type="button" class="btn2 fine_save_paper" id="EditShijuanTitle" value="确定"></li>
			</ul>
		</div>
	</div>
	<!-- 修改卷标题 -->
	<div id="modify3" style="background:#fff;display:none;">
		<div class="mbquesTxt" style="width:380px; height:220px;">
			<ul class="mt">
				<li><label>第I卷标题：</label>
					<textarea name="save_title" class="wh2" id="FirstjuanTitle"><?php echo ($first_juan["t_title"]); ?></textarea>
				</li>
				<li><label>第I卷注释：</label>
					<textarea name="save_title" class="wh2" id="FirstjuanNote"><?php echo ($first_juan["note"]); ?></textarea>
				</li>
				<li><input type="button" class="btn2 fine_save_paper" id="EditFirstjuanTitle" value="确定"></li>
			</ul>
		</div>
	</div>
	<!-- 修改题型 -->
	<div id="modify4" style="background:#fff;display:none;">
		<div class="mbquesTxt" style="width:380px; height:220px;">
			<input type="hidden" id="juan_no" />
			<input type="hidden" id="shiti_no" />
			<ul class="mt">
				<li><label>题型名称：</label>
					<textarea name="tixing_name" id="tixing_name" class="wh4">第II卷（非选择题）</textarea>
				</li>
				<li><label>小题分值：</label>
					<input class="wh02" type="text" value="" id="xiaoti_score" style="margin-right:160px;" name="fenzhi_xuanze">
				</li>
				<li><input type="button" class="btn2 fine_save_paper" id="EditXiaotiScore" value="确定"></li>
			</ul>
		</div>
	</div>

<link href="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
<script src="/Public/js/jquery-ui-1.11.2.custom/jquery-ui.js" type="text/javascript"></script>
<script src="/Public/js/menu.js" type="text/javascript"></script>
<script src="/Public/js/dialog.js" type="text/javascript"></script>
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
$('#save_shijuan').click(function(){
	$.getJSON(
		'/shijuan/ajaxSave',
		'',
		function(data){
			if(data.status=='success'){
				
			}
		}
	);
	
	$('#save_sj').dialog("close");
});
$("#mbsaveQues").click(function(){
	$.getJSON(
		'/shijuan/ajacCheckIsSaved',
		'',
		function(data){
			if(data.status=='yes'){
				$.getJSON(
					'/shijuan/ajaxSave',
					'',
					function(data){
						if(data.status=='success'){
							$("#is_save2").dialog({
							title:"保存试卷",
							width:"430",
							height:"auto",
							modal:true,
							create: function(){
							},
							});
						}
					}
				);
			}else{
				$("#save_sj").dialog({
				title:"保存试卷",
				width:"430",
				height:"auto",
				modal:true,
				create: function(){
				},
				beforeClose: function() {
					$('#save_sj').dialog("destroy");
				}
				});
			}
		}
	);
	
});
$('#download_shijuan').click(function(){
	$.getJSON(
		'/shijuan/ajaxSave',
		'',
		function(data){
			if(data.status=='success'){
				$('#down_sj').dialog("destroy");
				$("#down_sj2").dialog({
					title:"下载试卷",
					width:"660",
					height:"auto",
					modal:true,
					create: function(){
					},
					beforeClose: function() {
						$('#down_sj2').dialog("destroy");
					}
				});
			}
		}
	);
	

});
$('#xiazaishijuan').click(function(){
	var doctype = $('#doc_type').find("input:checked").val();
	var shijuan_model = $('#shijuan_model').find("input:checked").val();
	var answer_order = $('#answer_order').find("input:checked").val();
	$.getJSON(
		'/shijuan/ajaxDownload',
		{doctype:doctype,shijuan_model:shijuan_model,answer_order:answer_order},
		function(data){
			if(data.status=='success'){
				$('#down_iframe').attr('src','/shijuan/createtoword');
			}
		}
	);
	
	$('#down_sj2').dialog("close");
});
$("#mbdownQues").click(function(){
	$.getJSON(
		'/shijuan/ajacCheckIsSaved',
		'',
		function(data){
			if(data.status=='yes'){
				$("#down_sj2").dialog({
					title:"下载试卷",
					width:"660",
					height:"auto",
					modal:true,
					create: function(){
					},
					beforeClose: function() {
						$('#down_sj2').dialog("destroy");
					}
				});
			}else{
				$("#down_sj").dialog({
					title:"下载试卷",
					width:"430",
					height:"auto",
					modal:true,
					create: function(){
					},
					beforeClose: function() {
						$('#down_sj').dialog("destroy");
					}
				});
			}
			}
		);
	
});
</script>
<script>
$(".icon3").click(function(){
	var tag = '';
		var obj = $(this);
		$.getJSON(
			'/member/ajaxCollect',
			{id:$(this).attr('name')},
			function(data){
				if(data.status=='notlogin'){
					window.location.href = '/member/login';
				}else if(data.status=='success' && data.action=='add'){
						obj.removeClass('icon3');
						obj.addClass('overicon3');
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
					obj.removeClass('overicon3');
					obj.addClass('icon3');
					obj.html('收藏');
				}
			}
		);
});
</script>
<script>
$('#addtag').click(function() {
	$.getJSON(
			'/member/ajaxAddTag',
			{id:$('#tiku_id').val(),tag:$('.wh6').val()},
			function(data){
				$( "#sc_sj" ).dialog( "close" );
			}
	);
});
</script>
<script>
//修改试卷标题
$('#EditShijuanTitle').click(function(){
	$.getJSON(
			'/shijuan/ajaxEditShijuanTitle',
			{title:$('#ShijuanTitle').val()},
			function(data){
				if(data.status=='success'){
					$('#pui_maintitle').html(data.title);
					$( "#modify1" ).dialog( "close" );
				}
			}
	);
});
</script>
<script>
$('#EditFirstjuanTitle').click(function(){
	$.getJSON(
			'/shijuan/ajaxFirstjuanTitle',
			{title:$('#FirstjuanTitle').val(),note:$('#FirstjuanNote').val()},
			function(data){
				if(data.status=='success'){
					$('#partname1').html(data.title);
					$('#partnote1').html(data.note);
					$( "#modify3" ).dialog( "close" );
				}
			}
	);
});
	$(".mbquesBtn4").click(function(){
		$('#tixing_name').val($(this).parent().parent().find('.questypename').html());
		$('#xiaoti_score').val('');
		$('#juan_no').val($(this).prev().attr('juanNo'));
		$('#shiti_no').val($(this).prev().attr('shitiId'));
		$("#modify4").dialog({
			title:"试卷设置",
			width:"400",
			height:"auto",
			modal:true,
			create: function(){
			},
			beforeClose: function() {
				$('#modify4').dialog("destroy");
			}
		});
	});
	$('#EditXiaotiScore').click(function(){
		$.getJSON(
			'/shijuan/ajaxEditXiaotiScore',
			{juan_no:$('#juan_no').val(),shiti_no:$('#shiti_no').val(),xiaoti_score:$('#xiaoti_score').val()},
			function(data){
				if(data.status=='success'){
					var id = 'juan_'+data.juan_no+'_'+data.shiti_no;
					$('#'+id).html(data.title);
					$('#zongfen_1').html(data.score);
					$('#modify4').dialog("destroy");
				}
			}
	);
	});
</script>
<script>
$(function(){
	//subnav1
	$(".menu li").hover(function(){
		$(".subnav1",this).css("display","block");
		$(this).addClass("hover");
	},function(){
		$(".subnav1",this).css("display","none");
		$(this).removeClass("hover");
	});
	//
	$.each(<?php echo ($tikus_in_collect); ?>,function(index,dom){
		$("#neirong").find("a[id=c_"+dom.tiku_id+"]").html('取消收藏');
		$("#neirong").find("a[id=c_"+dom.tiku_id+"]").attr('class','overicon3');
	});
})
</script>
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
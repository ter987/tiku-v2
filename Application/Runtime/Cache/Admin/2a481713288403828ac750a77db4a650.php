<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<script type="text/javascript" src="lib/PIE_IE678.js"></script>
<![endif]-->
<link href="/Public/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/lib/Hui-iconfont/1.0.1/iconfont.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="/Public/lib/zTree/v3/css/zTreeStyle/zTreeStyle.css" type="text/css">
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>产品分类</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 知识点管理  <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<table class="table">
	<tr>
		<td width="200" class="va-t">
			<ul >
				<select style="width: 200px;text-align: center;" onchange="select_course(this.value);">
					<option value="0">-----选择科目-----</option>
					<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>">高中<?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</ul>
			
			<ul id="treeDemo" class="ztree" style="-moz-user-select: none;">

			
			</ul></td>
		<td class="va-t"><IFRAME ID="testIframe" Name="testIframe" FRAMEBORDER=0 SCROLLING=AUTO width=100%  height=390px SRC="/index.php/admin/tikupoint/add"></IFRAME></td>
	</tr>
</table>
<script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/lib/layer/1.9.3/layer.js"></script> 
<script type="text/javascript" src="/Public/lib/zTree/v3/js/jquery.ztree.all-3.5.min.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.admin.js"></script> 
<script type="text/javascript">


function select_course(id){
	
	$.getJSON(
		'/index.php/admin/tikupoint/ajaxGetPointsByCourseId',
		{course_id:id},
		function(data){
			if(data.status=='success'){
				var zs ='';
				$.each(data.data,
					function(index,item){
						zs += '<li treenode="" hidefocus="true" tabindex="0" class="level0" id="treeDemo_1"><a title="一级分类" style="" target="_blank" onclick="" treenode_a="" class="level0" id="treeDemo_1_a"><span style="" class="button ico_open" treenode_ico="" title="" id="treeDemo_1_ico"></span><span id="treeDemo_1_span"'+" onclick=\"$('IFRAME').attr('SRC','/index.php/admin/tikupoint/edit/id/"+item.id+"')\">"+item.point_name+'</span></a>';
						//alert(zNodes);return false;
						if(item.childs){
							zs += '<ul style="display:block" class="level0 " id="treeDemo_1_ul">';
							$.each(item.childs,
							function(i,t){
								zs += "<li treenode=\"\"  hidefocus=\"true\" tabindex=\"0\" class=\"level1\" id=\"treeDemo_2\"><a href=\"javascript:return false;\" title=\"二级分类\" style=\"\"  onclick=\"\" treenode_a=\"\" class=\"level1\" id=\"treeDemo_2_a\"><span treenode_switch=\"\" onclick=\"$(this).parent().parent().find('ul').attr('style','display:block;');$(this).removeClass().addClass('button level1 switch noline_open')\" class=\"button level1 switch noline_close\" title=\"\" id=\"treeDemo_2_switch\"></span><span style=\"\" class=\"button ico_close\" treenode_ico=\"\" title=\"\" id=\"treeDemo_2_ico\"></span><span id=\"treeDemo_2_span\" onclick=\"$('IFRAME').attr('SRC','/index.php/admin/tikupoint/edit/id/"+t.id+"')\">"+t.point_name+"</span></a>";
								if(t.childs){
									zs += '<ul style="display:none;" class="level1 " id="treeDemo_2_ul">';
									$.each(t.childs,
									function(is,ts){
										zs += "<li id=\"treeDemo_7\" class=\"level2\" tabindex=\"0\" hidefocus=\"true\" treenode=\"\"><span id=\"treeDemo_7_switch\" title=\"\" class=\"button level2 switch noline_docu\" treenode_switch=\"\"></span><a id=\"treeDemo_7_a\" class=\"level2\" treenode_a=\"\" onclick=\"\" target=\"_blank\" style=\"\" title=\"三级分类\"><span id=\"treeDemo_7_ico\" title=\"\" treenode_ico=\"\" class=\"button ico_docu\" style=\"\"></span><span id=\"treeDemo_7_span\" onclick=\"$('IFRAME').attr('SRC','/index.php/admin/tikupoint/edit/id/"+ts.id+"')\">"+ts.point_name+"</span></a></li>"
									}
									);
									zs += '</ul>';
								}
							}
							);
							zs += '</ul>';
						}
						zs += '</li>';
					}
				);
				$('#treeDemo').html(zs);
			}else{
				$('#treeDemo').html('');
			}
			
		}
		
	);
	
}

</script>
</body>
</html>
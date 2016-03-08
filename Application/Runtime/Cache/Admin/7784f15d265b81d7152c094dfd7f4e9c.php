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
<!--[if IE 6]>
<script type="text/javascript" src="http://lib.h-ui.net/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>资讯列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 题库管理 <span class="c-gray en">&gt;</span> 题库列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
<form action="/index.php/admin/tiku/index" name="" id="form1" method="POST">
	<div class="text-c"> <span class="select-box inline">
		<select name="course_id" class="select" onchange="select_course(this.value);" >
			<option value="0" <?php if(($course_id) == "0"): ?>selected=""<?php endif; ?>>全部科目</option>
			<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($course_id) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
		</span> 
		<span class="select-box inline">
		<select name="type_id" class="select" id="tiku_type">
			<option value="0" <?php if(($course_id) == "0"): ?>selected=""<?php endif; ?>>题型</option>
			<?php if(is_array($tiku_type)): $i = 0; $__LIST__ = $tiku_type;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($type_id) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		</select>
		</span>
		<span class="select-box inline">
		<select name="status" class="select">
			<option value="" <?php if(($status) == ""): ?>selected=""<?php endif; ?>>状态</option>
			<option value="0" <?php if(($status) == "0"): ?>selected=""<?php endif; ?>>未审核</option>
			<option value="1" <?php if(($status) == "1"): ?>selected=""<?php endif; ?>>已审核</option>
		</select>
		</span>
		<input type="text" name="content" value="<?php echo ($content); ?>" id="" placeholder="题目" style="width:250px" class="input-text">
		<input type="text" name="source_name" id="" value="<?php echo ($source_name); ?>" placeholder="来源" style="width:250px" class="input-text">
		<button name="" id="" class="btn btn-success" type="submit" onclick="$('#form1').submit();"><i class="Hui-iconfont">&#xe665;</i> 搜题库</button>
	</div>
</form>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="delete_all()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a> <a class="btn btn-primary radius" onclick="shenhe_all()" href="javascript:;"><i class="Hui-iconfont">&#xe600;</i> 批量审核</a></span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="25"><input type="checkbox" name="" value=""></th>
					<th width="80">ID</th>
					<th width="500">题目</th>
					<th width="180">来源</th>
					<th width="80">创建时间</th>
					<th width="60">状态</th>
					<th width="120">操作</th>
				</tr>
			</thead>
			<tbody>
				<?php if(is_array($tiku_data)): $i = 0; $__LIST__ = $tiku_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="text-c">
					<td><input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="tiku_id"></td>
					<td><?php echo ($vo["id"]); ?></td>
					<td class="text-l" style="line-height: 50px;"><?php echo (htmlspecialchars_decode($vo["content"])); ?></td>
					<td><?php echo ($vo["source_name"]); ?></td>
					<td><?php echo (date('Y-m-d',$vo["create_time"])); ?></td>

					<td class="td-status"><span class="label label-<?php if(($vo["status"]) == "1"): ?>success<?php else: ?>error<?php endif; ?> radius"><?php if(($vo["status"]) == "1"): ?>已审核<?php else: ?>未审核<?php endif; ?></span></td>
					<td class="f-14 td-manage"><a style="text-decoration:none" class="ml-5"  href="/index.php/admin/tiku/edit/id/<?php echo ($vo["id"]); ?>" title="编辑"><i class="Hui-iconfont">&#xe6df;</i></a> <a style="text-decoration:none" class="ml-5" onClick="article_del(this,<?php echo ($vo["id"]); ?>)" href="javascript:;" title="删除"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
				</tr><?php endforeach; endif; else: echo "" ;endif; ?>
			</tbody>
		</table>
		<style>
			.current{color:red;}
			.dataTables_paginate a{
   				 border: 1px solid #ccc;
			    color: #666;
			    cursor: pointer;
			    display: inline-block;
			    font-size: 14px;
			    height: 26px;
			    line-height: 26px;
			    margin: 0 0 6px 6px;
			    padding: 0 10px;
			    text-align: center;
			    text-decoration: none;
   				 }
   				 
		</style>
		<div class="dataTables_paginate" id="DataTables_Table_0_paginate"><?php echo ($page_show); ?></div>
	</div>
</div>
<script type="text/javascript" src="/Public/lib/jquery/2.1.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/lib/layer/1.9.3/layer.js"></script> 
<!-- <script type="text/javascript" src="/Public/lib/My97DatePicker/WdatePicker.js"></script>  -->
<script type="text/javascript" src="/Public/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.admin.js"></script>
<script type="text/javascript">
function select_course(id){
	var type_html = '<option value="0">题型</option>';
	$.getJSON(
		'/index.php/admin/tiku/gettikutype',
		{course_id:id},
		function(data){
			$.each(data, function(index, item) {
			  type_html += '<option value="'+item.id+'">'+item.type_name+'</option>';
			});
			$('#tiku_type').html(type_html);
		}
		
	);
}
// $('.table-sort').dataTable({
	// "aaSorting": [[ 1, "desc" ]],//默认第几个排序
	// "bStateSave": true,//状态保存
	// "aoColumnDefs": [
	  // //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  // {"orderable":false,"aTargets":[0,3,6]}// 制定列不参与排序
	// ]
// });

/*资讯-添加*/
function delete_all(){
	var ids ='';
	var size;
	size = $('tbody').find("input[type='checkbox']:checked").size();
	if(size==0){
		alert("请选择要删除的数据！");
		return ;
	}
	$('tbody').find("input[type='checkbox']:checked").each(function(index) {
		  ids = ids + $(this).val();
		  if(index<size-1) {ids = ids + ',';}
		});
	
	if(confirm("确定删除？")){
		window.location.href = "/index.php/admin/tiku/deleteall/ids/"+ids;
	}
	
}
function shenhe_all(){
	var ids ='';
	var size;
	size = $('tbody').find("input[type='checkbox']:checked").size();
	if(size==0){
		alert("请选择要审核的数据！");
		return ;
	}
	$('tbody').find("input[type='checkbox']:checked").each(function(index) {
		  ids = ids + $(this).val();
		  if(index<size-1) {ids = ids + ',';}
		});
	
	if(confirm("确定审核并发布？")){
		window.location.href = "/index.php/admin/tiku/shenheall/ids/"+ids;
	}
	
}
/*资讯-删除*/
function article_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.getJSON(
		'/index.php/admin/tiku/delete',
		{id:id},
		function(data){
			if(data.status=='success'){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',1);
			}
		}
		
	);
		
	});
}
/*资讯-审核*/
function article_shenhe(obj,id){
	layer.confirm('审核文章？', {
		btn: ['通过','不通过'], 
		shade: false
	},
	function(){
		$(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_start(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
		$(obj).remove();
		layer.msg('已发布', {icon:6,time:1000});
	},
	function(){
		$(obj).parents("tr").find(".td-manage").prepend('<a class="c-primary" onClick="article_shenqing(this,id)" href="javascript:;" title="申请上线">申请上线</a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-danger radius">未通过</span>');
		$(obj).remove();
    	layer.msg('未通过', {icon:5,time:1000});
	});	
}
/*资讯-下架*/
function article_stop(obj,id){
	layer.confirm('确认要下架吗？',function(index){
		$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_start(this,id)" href="javascript:;" title="发布"><i class="Hui-iconfont">&#xe603;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已下架</span>');
		$(obj).remove();
		layer.msg('已下架!',{icon: 5,time:1000});
	});
}

/*资讯-发布*/
function article_start(obj,id){
	layer.confirm('确认要发布吗？',function(index){
		$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_stop(this,id)" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
		$(obj).remove();
		layer.msg('已发布!',{icon: 6,time:1000});
	});
}
/*资讯-申请上线*/
function article_shenqing(obj,id){
	$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">待审核</span>');
	$(obj).parents("tr").find(".td-manage").html("");
	layer.msg('已提交申请，耐心等待审核!', {icon: 1,time:2000});
}

</script> 
</body>
</html>
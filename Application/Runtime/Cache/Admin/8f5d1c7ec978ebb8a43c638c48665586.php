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
<![endif]--><title>系统报表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 系统管理 <span class="c-gray en">&gt;</span> 系统报表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">

  <table class="table table-border table-bordered table-bg table-hover table-sort">
    <thead>
      <tr class="text-c">


        <!-- <th width="100">类型</th> -->
        <th width="25%">账号</th>
        <th width="15%">昨日</th>
        <th width="15%">今日</th>
        <th width="15%">上周</th>
        <th width="15%">本周</th>
        <th width="15%">本月</th>

      </tr>
    </thead>
    <tbody>
    <?php if(is_array($data)): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="text-c">

        <!-- <td>1</td> -->
        <td><?php echo ($vo["user_name"]); ?></td>
        <td><?php echo ($vo["yestoday_count"]); ?></td>
        <td><?php echo ($vo["day_count"]); ?></td>
        <td><?php echo ($vo["lastweek_count"]); ?></td>
        <td><?php echo ($vo["week_count"]); ?></td>
        <td><?php echo ($vo["month_count"]); ?></td>
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
<script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script>  
<script type="text/javascript" src="/Public/lib/layer/1.9.3/layer.js"></script><script type="text/javascript" src="lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript">

$('.table-sort').dataTable({
	'paging' : false,
	"info":     false,
	"lengthMenu":false,//显示数量选择 
	"bFilter": false,//过滤功能
	"bPaginate": false,//翻页信息
	"bInfo": false,//数量信息
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,7]}// 制定列不参与排序
	]
});
/*用户-删除*/
function system_log_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.getJSON(
		'/index.php/admin/system/deletelog',
		{id:id},
		function(data){
			if(data.status=='y'){
				$(obj).parents("tr").remove();
				layer.msg('已删除!',{icon:1,time:1000});
			}
		});
	});
}
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
		window.location.href = "/index.php/admin/system/deletealllog/ids/"+ids;
	}
	
}
</script>
</body>
</html>
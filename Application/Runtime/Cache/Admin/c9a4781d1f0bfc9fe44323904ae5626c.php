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
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>新建网站角色</title>
</head>
<body>
<div class="pd-20">
	<form action="<?php echo U('/admin/admin/roleAdd');?>" method="post" class="form form-horizontal" id="form-user-character-add">
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>角色名称：</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" value="" placeholder="" id="user-name" name="title" datatype="*4-16" nullmsg="角色名称不能为空">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">描述：</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" value="" placeholder="" id="" name="description">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">权限：</label>
			<div class="formControls col-10">
				<?php if(is_array($modules)): $i = 0; $__LIST__ = $modules;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dl class="permission-list">
					
					<dt>
						<label>
							<input type="checkbox" value="" name="user-Character-0" id="user-Character-0">
							<?php echo ($vo["title"]); ?></label>
					</dt>
					<dd>
						<dl class="cl permission-list2">
							
							<dd>
								<?php if(is_array($vo["childs"])): $i = 0; $__LIST__ = $vo["childs"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><label class="">
									<input type="checkbox" value="<?php echo ($v["id"]); ?>" name="rules[]" id="user-Character-0-0-0">
									<?php echo ($v["title"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
								
							</dd>
						</dl>
						
						
					</dd>
				</dl><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<button type="submit" class="btn btn-success radius" id="admin-role-save" name="admin-role-save"><i class="icon-ok"></i> 确定</button>
			</div>
		</div>
	</form>
</div>
<script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/lib/layer/1.9.3/layer.js"></script> 
<script type="text/javascript" src="/Public/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="/Public/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.admin.js"></script> 
<script>
$(function(){
	$(".permission-list dt input:checkbox").click(function(){
		$(this).closest("dl").find("dd input:checkbox").prop("checked",$(this).prop("checked"));
	});
	$(".permission-list2 dd input:checkbox").click(function(){
		var l =$(this).parent().parent().find("input:checked").length;
		var l2=$(this).parents(".permission-list").find(".permission-list2 dd").find("input:checked").length;
		if($(this).prop("checked")){
			$(this).closest("dl").find("dt input:checkbox").prop("checked",true);
			$(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",true);
		}
		else{
			if(l==0){
				$(this).closest("dl").find("dt input:checkbox").prop("checked",false);
			}
			if(l2==0){
				$(this).parents(".permission-list").find("dt").first().find("input:checkbox").prop("checked",false);
			}
		}
		
	});
	$("#form-user-character-add").Validform({
		tiptype:2,
		ignoreHidden:true,
		ajaxPost:true,
		callback:function(data){
			if(data.status=='y'){
				var index = parent.layer.getFrameIndex(window.name);
			 	parent.$('.btn-refresh').click();
			 	parent.layer.close(index);
			}
		}
	});
});
</script>
</body>
</html>
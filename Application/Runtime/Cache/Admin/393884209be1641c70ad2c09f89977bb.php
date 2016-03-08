<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
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
<link href="/Public/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
<link href="/Public/lib/Hui-iconfont/1.0.1/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/Public/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>新增文章</title>
</head>
<body>
<div class="pd-20">
	<form action="/index.php/admin/tiku/edit" method="post" onsubmit="return true;" id="form1" class="form form-horizontal" id="form-article-add">
		<input type="hidden" name="id" value="<?php echo ($tiku_data["id"]); ?>" />
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>题目：</label>
			<div class="formControls col-10">
				<script id="editor" name="content" type="text/plain"> <?php echo (htmlspecialchars_decode($tiku_data["content"])); ?></script> 
			</div>
		</div>

					<?php if($tiku_data['type_id']==1 || $tiku_data['type_id']==6){ if($tiku_data['options']){ $options = json_decode($tiku_data['options']); $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D'); ?>
                    	<div class="row cl">
						<label class="form-label col-2"><span class="c-red">*</span>选项：</label>
						<div class="formControls col-10">
							<table>
                    		<?php $k = 0; foreach($options as $k=>$val){ ?>
                    			<!-- <tr><td width="10%"><?php echo $options_index[$k] ?></td><td width="70%" onkeyup="$(this).next().html($(this).children().val());"><textarea cols="100" rows="5"  name="options[]"> <?php echo $val ?> </textarea><a>上标</a>&nbsp;&nbsp;<a>下标</a>&nbsp;&nbsp;<a>下划线</a>&nbsp;&nbsp;<a>斜体</a></td><td><?php echo $val ?></td></tr> -->
                    			<tr><td width="10%"><?php echo $options_index[$k] ?></td><td width="70%"><textarea id="option_<?php echo $options_index[$k] ?>" name="options[]"><?php echo $val ?></textarea></td></tr>
                    		<?php } if($k==0){ foreach($options_index as $k=>$val){ ?>
                    			<tr><td width="10%"><?php echo $val ?></td><td width="70%"><textarea id="option_<?php echo $val ?>" name="options[]"></textarea></td></tr>
                    		<?php }} ?>
			                    	</table>
							</div>
						</div>
                    	<?php } }else{ $options_index = array(0=>'A',1=>'B',2=>'C',3=>'D'); ?>
                    	<div class="row cl" style="display:none;" id="option_div">
						<label class="form-label col-2"><span class="c-red">*</span>选项：</label>
						<div class="formControls col-10">
							<table>
                    	<?php $k = 0; foreach($options_index as $val){ ?>
                    			<!-- <tr><td width="10%"><?php echo $options_index[$k] ?></td><td width="70%" onkeyup="$(this).next().html($(this).children().val());"><textarea cols="100" rows="5"  name="options[]"> <?php echo $val ?> </textarea><a>上标</a>&nbsp;&nbsp;<a>下标</a>&nbsp;&nbsp;<a>下划线</a>&nbsp;&nbsp;<a>斜体</a></td><td><?php echo $val ?></td></tr> -->
                    			<tr><td width="10%"><?php echo $val ?></td><td width="70%"><textarea id="option_<?php echo $val ?>" name="options[]"></textarea></td></tr>
                    		<?php } ?>
			                    	</table>
							</div>
						</div>
						<?php } ?>

        <div class="row cl">
			<label class="form-label col-2">源题目：</label>
			<div class="formControls col-10">
				<script id="content_old" name="content_old" type="text/plain"> <?php echo (htmlspecialchars_decode($tiku_data["content_old"])); ?></script> 
			</div>
		</div>           	
		<div class="row cl">
			<label class="form-label col-2">答案：</label>
			<div class="formControls col-10">
				<script id="answer" name="answer" type="text/plain"> <?php echo (htmlspecialchars_decode($tiku_data["answer"])); ?></script> 
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">解析：</label>
			<div class="formControls col-10">
				<script id="analysis" name="analysis" type="text/plain"> <?php echo (htmlspecialchars_decode($tiku_data["analysis"])); ?></script> 
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2"><span class="c-red">*</span>科目：</label>
			<div class="formControls col-2"> <span class="select-box">
				<select name="course_id" class="select" onchange="select_course(this.value)">
					<?php if(is_array($course_data)): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($tiku_data["course_id"]) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["course_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				</span> </div>
			<label class="form-label col-2"><span class="c-red">*</span>题型：</label>
			<div class="formControls col-2"> <span class="select-box">
				<select name="type_id" class="select" id="type_select">
					<?php if(is_array($type_data)): $i = 0; $__LIST__ = $type_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($tiku_data["type_id"]) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				</span> </div>
			<label class="form-label col-2"><span class="c-red">*</span>知识点：</label>
			<div class="formControls col-2"> <span class="select-box">
				<select name="point_id" class="select" id="point_id">
					<?php echo ($point_html); ?>
				</select>
				</span> </div>
			<label class="form-label col-2">难度：</label>
			<div class="formControls col-2">
				<select name="difficulty" class="select">
					<?php if(is_array($difficulty_data)): $i = 0; $__LIST__ = $difficulty_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if(($tiku_data["difficulty_id"]) == $vo["id"]): ?>selected<?php endif; ?> >(<?php echo ($vo["section"]); ?>)<?php echo ($vo["description"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-2">来源：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" disabled="" value="<?php echo ($tiku_data["source_name"]); ?>" placeholder="" id="" name="">
			</div>
			<label class="form-label col-2">年份：</label>
			<div class="formControls col-1">
				<input type="text" class="input-text" disabled="" value="<?php echo ($tiku_data["year"]); ?>" placeholder="" id="" name="">
			</div>
			<label class="form-label col-2">省份： </label>
			<div class="formControls col-1">
				<input type="text" class="input-text" disabled="" value="<?php echo ($tiku_data["province_name"]); ?>" placeholder="" id="" name="">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">适用年级：</label>
			<div class="formControls col-4">
				<input type="text" class="input-text" disabled="" value="<?php if($tiku_data["grade"] == 1): ?>高一<?php elseif($tiku_data["grade"] == 2): ?>高二<?php elseif($tiku_data["grade"] == 3): ?>高三<?php endif; ?>" placeholder="" id="" name="">
			</div>
			<label class="form-label col-2">试卷类型：</label>
			<div class="formControls col-1">
				<select name="source_type_id" class="select" disabled="">
					<?php if(is_array($source_type_data)): $i = 0; $__LIST__ = $source_type_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="1" <?php if(($tiku_data["source_type_id"]) == $vo["id"]): ?>selected=""<?php endif; ?>><?php echo ($vo["type_name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
				</select>
				
			</div>
			<label class="form-label col-2">文理：</label>
			<div class="formControls col-1">
				<input type="text" class="input-text" disabled="" value="<?php if($tiku_data["wen_li"] == 1): ?>理科<?php elseif($tiku_data["wen_li"] == 2): ?>文科<?php else: ?>不分<?php endif; ?>" placeholder="" id="" name="">
			</div>
		</div>

		<div class="row cl">
			<label class="form-label col-2">创建时间：</label>
			<div class="formControls col-10"> 
				<?php echo (date('Y-m-d',$tiku_data["create_time"])); ?>
			</div>
		</div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
				<?php if(($tiku_data["status"]) == "0"): ?><button onClick="article_save_submit();" class="btn btn-primary radius" ><i class="Hui-iconfont">&#xe632;</i> 保存并审核通过</button><?php endif; ?>
				<button onClick="article_save();" class="btn btn-secondary radius" type="button"><i class="Hui-iconfont">&#xe632;</i> 保存</button>
				<button onClick="window.location.href='/index.php/admin/tikusource/edit/id/<?php echo ($tiku_data["sid"]); ?>'" class="btn btn-success radius" type="button">修改试卷</button>
				<button onClick="history.back(-1)" class="btn btn-default radius" type="button">&nbsp;&nbsp;取消&nbsp;&nbsp;</button>
				
			</div>
		</div>
		<input type="hidden" name="status" value="<?php echo ($tiku_data["status"]); ?>" id="status"/>
	</form>
</div>
<script type="text/javascript" src="/Public/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/lib/layer/1.9.3/layer.js"></script> 
<script type="text/javascript" src="/Public/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="/Public/lib/Validform/5.3.2/Validform.min.js"></script> 
<script type="text/javascript" src="/Public/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="/Public/lib/ueditor/1.4.3/ueditor.config.js"></script> 
<script type="text/javascript" src="/Public/lib/ueditor/1.4.3/ueditor.all.js"> </script> 
<script type="text/javascript" src="/Public/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/js/H-ui.admin.js"></script> 
<script type="text/javascript" src="/Public/lib/kindeditor/kindeditor.js"></script> 
<script type="text/javascript">
function article_save_submit(){
	$('#status').val(1);
}
function article_save(){
	$('#form1').submit();
}
$(function(){
	$('.skin-minimal input').iCheck({
		checkboxClass: 'icheckbox-blue',
		radioClass: 'iradio-blue',
		increaseArea: '20%'
	});
	
	$list = $("#fileList"),
	$btn = $("#btn-star"),
	state = "pending",
	uploader;

	var uploader = WebUploader.create({
		auto: true,
		swf: 'lib/webuploader/0.1.5/Uploader.swf',
	
		// 文件接收服务端。
		server: 'http://lib.h-ui.net/webuploader/0.1.5/server/fileupload.php',
	
		// 选择文件的按钮。可选。
		// 内部根据当前运行是创建，可能是input元素，也可能是flash.
		pick: '#filePicker',
	
		// 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
		resize: false,
		// 只允许选择图片文件。
		accept: {
			title: 'Images',
			extensions: 'gif,jpg,jpeg,bmp,png',
			mimeTypes: 'image/*'
		}
	});
	uploader.on( 'fileQueued', function( file ) {
		var $li = $(
			'<div id="' + file.id + '" class="item">' +
				'<div class="pic-box"><img></div>'+
				'<div class="info">' + file.name + '</div>' +
				'<p class="state">等待上传...</p>'+
			'</div>'
		),
		$img = $li.find('img');
		$list.append( $li );
	
		// 创建缩略图
		// 如果为非图片文件，可以不用调用此方法。
		// thumbnailWidth x thumbnailHeight 为 100 x 100
		uploader.makeThumb( file, function( error, src ) {
			if ( error ) {
				$img.replaceWith('<span>不能预览</span>');
				return;
			}
	
			$img.attr( 'src', src );
		}, thumbnailWidth, thumbnailHeight );
	});
	// 文件上传过程中创建进度条实时显示。
	uploader.on( 'uploadProgress', function( file, percentage ) {
		var $li = $( '#'+file.id ),
			$percent = $li.find('.progress-box .sr-only');
	
		// 避免重复创建
		if ( !$percent.length ) {
			$percent = $('<div class="progress-box"><span class="progress-bar radius"><span class="sr-only" style="width:0%"></span></span></div>').appendTo( $li ).find('.sr-only');
		}
		$li.find(".state").text("上传中");
		$percent.css( 'width', percentage * 100 + '%' );
	});
	
	// 文件上传成功，给item添加成功class, 用样式标记上传成功。
	uploader.on( 'uploadSuccess', function( file ) {
		$( '#'+file.id ).addClass('upload-state-success').find(".state").text("已上传");
	});
	
	// 文件上传失败，显示上传出错。
	uploader.on( 'uploadError', function( file ) {
		$( '#'+file.id ).addClass('upload-state-error').find(".state").text("上传出错");
	});
	
	// 完成上传完了，成功或者失败，先删除进度条。
	uploader.on( 'uploadComplete', function( file ) {
		$( '#'+file.id ).find('.progress-box').fadeOut();
	});
	uploader.on('all', function (type) {
        if (type === 'startUpload') {
            state = 'uploading';
        } else if (type === 'stopUpload') {
            state = 'paused';
        } else if (type === 'uploadFinished') {
            state = 'done';
        }

        if (state === 'uploading') {
            $btn.text('暂停上传');
        } else {
            $btn.text('开始上传');
        }
    });

    $btn.on('click', function () {
        if (state === 'uploading') {
            uploader.stop();
        } else {
            uploader.upload();
        }
    });

	
	
	var ue = UE.getEditor('editor',{'enterTag':''});
	var ue = UE.getEditor('answer',{'enterTag':''});
	var ue = UE.getEditor('analysis',{'enterTag':''});
	var ue = UE.getEditor('content_old',{'enterTag':''});
	
});
$('#type_select').change(function(){
	var id = $(this).val();
	if(id==1 || id==6){
		$('#option_div').show();
	}else{
		$('#option_div').hide();
	}
});
KindEditor.ready(function(K) {
    window.editor = K.create('#option_A',{width:'700px',afterBlur: function(){this.sync();}});
    window.editor = K.create('#option_B',{width:'700px',afterBlur: function(){this.sync();}});
    window.editor = K.create('#option_C',{width:'700px',afterBlur: function(){this.sync();}});
    window.editor = K.create('#option_D',{width:'700px',afterBlur: function(){this.sync();}});
});
function mobanxuanze(){
	
}
function select_course(id){
	var type_html = '<option value="0">知识点</option>';
	$.getJSON(
		'/index.php/admin/tiku/getPointsByCouresId',
		{course_id:id},
		function(data){
			type_html = data;
			$('#point_id').html(type_html);
		}
		
	);
}

</script>
</body>
</html>
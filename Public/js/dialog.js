function dia_log(){
	this.init();
	this.check();
};
dia_log.prototype.init = function(){
	$("#biaozhun").click(function(){
		$(this).addClass('yl-on');
		$("#zuoye").removeClass('yl-on');
		$(".b_z").show();
		$(".z_y").hide();
		$("#pui_main").css("margin-left","105px");
		$(".questypescore").show();
	});
	$("#zuoye").click(function(){
		$(this).addClass('yl-on');
		$("#biaozhun").removeClass('yl-on');
		$(".z_y").show();
		$(".b_z").hide();
		$(".questypescore").hide();
		$("#pui_main").css("margin-left","0px");
	});
	
};
dia_log.prototype.check = function(){
	$("#paperdownload").click(function(){
		$("#sj_div").dialog({
			title:"生成试卷",
			width:"760",
			height:"auto",
			modal:true,
			create: function(){
			},
			buttons:[{
				text:"生成word试卷",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					dialog = this;		
					$('#sj_div').dialog("destroy");
				}
			},{
				text:"取消",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					$("#sj_div").dialog("destroy");
				}
			}],
			beforeClose: function() {
				$('#sj_div').dialog("destroy");
			}
		});
	});
	$("#answercar").click(function(){
		$("#dtk_div").dialog({
			title:"生成答题卡",
			width:"600",
			height:"auto",
			modal:true,
			create: function(){
			},
			buttons:[{
				text:"生成word试卷",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					dialog = this;		
					$('#dtk_div').dialog("destroy");
				}
			},{
				text:"取消",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					$("#dtk_div").dialog("destroy");
				}
			}],
			beforeClose: function() {
				$('#dtk_div').dialog("destroy");
			}
		});
	});
	$(".bjxs").click(function(){
		$(".bjxs_div").dialog({
			title:"编辑学生",
			width:"450",
			height:"auto",
			modal:true,
			create: function(){
			},
			buttons:[{
				text:"确定",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					dialog = this;		
					$('.bjxs_div').dialog("destroy");
				}
			},{
				text:"取消",
				show:function(){
					$(":button").slice(2,3).css({"background":"#fff","color":"#000","border":"1px solid #e5e5e5"});
				},
				click:function(){
					$(".bjxs_div").dialog("destroy");
				}
			}],
			beforeClose: function() {
				$('.bjxs_div').dialog("destroy");
			}
		});
	});
	
};
$(function(){
	new dia_log();
});
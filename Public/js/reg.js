function noticeadd(){
	this.init();
};

noticeadd.prototype.init = function(){
	$("#phone_btn").click(function(){
		$(".ph_li").show();
		$(".em_li").hide();
		$('#is_mail').val(0);
	});
	$("#email_btn").click(function(){
		$(".em_li").show();
		$(".ph_li").hide();
		$('#is_mail').val(1);
	});
	$(document).on("click","#is_teacher",function(){
		$("#is_teacher").addClass("l_k_check").removeClass("l_k");
		$("#is_student").addClass("l_k1").removeClass("l_k1_check");
		$(".is_tea").show();
		$(".is_stu").hide();
		$("#type").val(2);
	});	
	$(document).on("click","#is_student",function(){
		$("#is_student").addClass("l_k1_check").removeClass("l_k1");
		$("#is_teacher").addClass("l_k").removeClass("l_k_check");
		$(".is_stu").show();
		$(".is_tea").hide();
		$("#type").val(1);
	});	
	$(".clause").click(function(){
		$("#clause_content").dialog({
			title:"哈学库使用条款",
			width:"630",
			height:"auto",
			modal:true,
			create: function(){
			},
			buttons:[{
				text:"同意",
				click:function(){
					dialog = this;		
					$('#is_clause').prop("checked","checked");
					$('#clause_content').dialog("destroy");
				}
			}],
			beforeClose: function(){
				$('#clause_content').dialog("destroy");
			}
		});
	});
};

$(function(){
	new noticeadd();
});
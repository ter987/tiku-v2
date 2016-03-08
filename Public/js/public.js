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

function noticeadd(){
	this.init();
	this.submit();
	this.mima();
};

noticeadd.prototype.init = function(){
	$("#phone_btn").click(function(){
		$(".ph_li").show();
		$(".em_li").hide();
	});
	$("#email_btn").click(function(){
		$(".em_li").show();
		$(".ph_li").hide();
	});
	$(document).on("click","#is_teacher",function(){
		$("#is_teacher").addClass("l_k_check").removeClass("l_k");
		$("#is_student").addClass("l_k1").removeClass("l_k1_check");
		$(".is_tea").show();
		$(".is_stu").hide();
		$("#role").val(1);
	});	
	$(document).on("click","#is_student",function(){
		$("#is_student").addClass("l_k1_check").removeClass("l_k1");
		$("#is_teacher").addClass("l_k").removeClass("l_k_check");
		$(".is_stu").show();
		$(".is_tea").hide();
		$("#role").val(2);
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
noticeadd.prototype.submit = function(){
	$.formValidator.initConfig({
		formid:"add_form",
		debug:false,
		submitonce:true,
		onerror:function(msg,obj,errorlist){}
	});
	$("#phone").formValidator({
		onshow:"",
		onfocus:"请输入手机号",
		oncorrect:"&nbsp;",
	}).inputValidator({
    	min:11,
    	max:11,
    	onerror:"手机格式不对"
	}).regexValidator({
		regexp:["mobile"],
		datatype:"enum",
		onerror:"手机号码格式错误"
	});/* .ajaxValidator ({
		type:"post",
		url:"",
		datatype:"json",
		async:false,
		data:"phone="+$('#phone').val(),
		success:function(json){
			if(json.status<0){
				return false;
			}else{
				return true;
			}
		},
		onwait : "正在对手机进行合法性校验，请稍候...",
		error: function(){alert("服务器没有返回数据，可能服务器忙，请重试");},
		onerror : "手机号码错误或已经注册"
	}) ;*/
	$("#email").formValidator({
		onshow:"",
		onfocus:"请输入邮箱",
		oncorrect:"&nbsp;"
	}).inputValidator({
		min:1,
		max:100,
		onerror:"长度非法"
	}).regexValidator({
		regexp:["email"],
		onerror:"邮箱格式不正确"
	});
	$("#password").formValidator({
		onshow:"",
		onfocus:"请输入密码",
		oncorrect:"&nbsp;"
	}).inputValidator({
		min:6,
		max:18,
		onerror:"密码长度为6~18个字符"
	});
	$("#password1").formValidator({
		onshow:"",
		onfocus:"请再次输入密码",
		oncorrect:"&nbsp;"
	}).compareValidator({
        desid: "password",
        operateor: "=",
		onerror:"2次密码不一致,请确认"
	}).inputValidator({
		min:6,
		max:18,
		onerror:"密码长度为6~18个字符"
	});
	$("#phone_code").formValidator({
		onshow:"",
		onfocus:"请输入激活码",
		oncorrect:"&nbsp;",
		empty: true
	}).inputValidator({
		min:6,
		max:6,
		onerror:"激活码不正确或无效"
	});
};
noticeadd.prototype.mima = function(){
	$('#password').focus(function () {
		$('#level_1').addClass("aq_opt_c").removeClass("aq_opt");
		$('#password').keyup();
	});
	$('#password').keyup(function () {
		var __th = $(this);
		if (!__th.val()){
			Primary();
			return;
		}
		if (__th.val().length < 6) {
			Weak();
			return;
		}
		var _r = checkPassword(__th);
		if (_r < 1) {
			Primary();
			return;
		}

		if (_r > 0 && _r < 2) {
			Weak();
		} else if (_r >= 2 && _r < 4) {
			Medium();
		} else if (_r >= 4) {
			Tough();
		}
	});
	function Primary(){
		$('.level_all').hide();
	}
	function Weak() {
		$('.level_all').show();
		$('#level_1').addClass("aq_opt_c").removeClass("aq_opt");
		$('#level_2').addClass("aq_opt").removeClass("aq_opt_c");
		$('#level_3').addClass("aq_opt").removeClass("aq_opt_c");
	}
	function Medium() {
		$('.level_all').show();
		$('#level_1').addClass("aq_opt").removeClass("aq_opt_c");
		$('#level_2').addClass("aq_opt_c").removeClass("aq_opt");
		$('#level_3').addClass("aq_opt").removeClass("aq_opt_c");
	}
	function Tough() {
		$('.level_all').show();
		$('#level_1').addClass("aq_opt").removeClass("aq_opt_c");
		$('#level_2').addClass("aq_opt").removeClass("aq_opt_c");
		$('#level_3').addClass("aq_opt_c").removeClass("aq_opt");
	}
	function checkPassword(pwdinput) {
		var maths, smalls, bigs, corps, cat, num;
		var str = $(pwdinput).val()
		var len = str.length;

		var cat = /.{16}/g
		if (len == 0) return 1;
		if (len > 16) { $(pwdinput).val(str.match(cat)[0]); }
		cat = /.*[\u4e00-\u9fa5]+.*$/
		if (cat.test(str)) {
			return -1;
		}
		cat = /\d/;
		var maths = cat.test(str);
		cat = /[a-z]/;
		var smalls = cat.test(str);
		cat = /[A-Z]/;
		var bigs = cat.test(str);
		var corps = corpses(pwdinput);
		var num = maths + smalls + bigs + corps;

		if (len < 6) { return 1; }

		if (len >= 6 && len <= 8) {
			if (num == 1) return 1;
			if (num == 2 || num == 3) return 2;
			if (num == 4) return 3;
		}

		if (len > 8 && len <= 11) {
			if (num == 1) return 2;
			if (num == 2) return 3;
			if (num == 3) return 4;
			if (num == 4) return 5;
		}

		if (len > 11) {
			if (num == 1) return 3;
			if (num == 2) return 4;
			if (num > 2) return 5;
		}
	}

	function corpses(pwdinput) {
		var cat = /./g
		var str = $(pwdinput).val();
		var sz = str.match(cat)
		for (var i = 0; i < sz.length; i++) {
			cat = /\d/;
			maths_01 = cat.test(sz[i]);
			cat = /[a-z]/;
			smalls_01 = cat.test(sz[i]);
			cat = /[A-Z]/;
			bigs_01 = cat.test(sz[i]);
			if (!maths_01 && !smalls_01 && !bigs_01) { return true; }
		}
		return false;
	}
}
$(function(){
	new noticeadd();
});
	



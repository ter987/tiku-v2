$(function(){
    $(".ce > li > a").click(function(){
		if($(this).is('.xz')){
            $(this).removeClass("xz").children('i').addClass('ce_jia').removeClass('ce_jian');
			$(this).removeClass("xz").children('span').addClass('active')
        }else{
			$(this).children('i').addClass('ce_jian').removeClass('ce_jia').parents().siblings().find(".ce_jian").addClass('ce_jia').removeClass('ce_jian');
			$(this).addClass("xz").parents().siblings().find("a").removeClass("xz");
		}
		$(this).siblings(".er").toggle();
	})
    $(".er > li > a").click(function(){
		if($(this).is('.sen_x')){
            $(this).removeClass("sen_x").children('i').addClass('ce_jia').removeClass('ce_jian');
        }else{
			$(this).children('i').addClass('ce_jian').removeClass('ce_jia').parents(".e_li").siblings().find(".ce_jian").addClass('ce_jia').removeClass('ce_jian');
			$(this).addClass("sen_x").parents().siblings().find("a").removeClass("sen_x");
		}
        
        $(this).parents().siblings().find(".thr").hide();	
	    $(this).siblings(".thr").toggle();	
	})

    $(".thr > li > a").click(function(){
	     $(this).addClass("xuan").parents().siblings().find("a").removeClass("xuan");
		 $(this).parents().siblings().find(".thr_nr").hide();	
	     $(this).siblings(".thr_nr").toggle();
	})
	$(".j_c").click(function(){
		$('.j_c').addClass("na_n_check").removeClass("na_n");
		$('.zsd').addClass("na_n").removeClass("na_n_check");
		$('.j_c_list').show();
		$('.zsd_list').hide();
    });
    $(".zsd").click(function(){
		$('.zsd').addClass("na_n_check").removeClass("na_n");
		$('.j_c').addClass("na_n").removeClass("na_n_check");
		$('.j_c_list').hide();
		$('.zsd_list').show();
    });
    $("div[id^='kd_']").click(function(){
		var id = (this.id.toString().match(/\d+/ig)||[])[0];
		$("#kd").val(id);
		$(".kedu_"+id).show().parents().siblings("div[id^='kd_']").children().hide();
		
	});	
	//下拉菜单
	$(".sel_down").click(function(){
		$(this).siblings(".sel_list").show();
	});
	$(".sel_list > div").click(function(){
		$(this).parent().hide().siblings(".sel_list").removeClass('chc');
		$(".select_div").html($(this).html());
	});
});

  
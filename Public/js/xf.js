$(function(){
	// 首页
	$(".xf_teshuli").hover(function(){
			$(this).addClass('hover');
		},function(){
			$(this).removeClass('hover');
		});
		$(".xf_mysjleft").click(function(){
			if($(this).parent().hasClass('active')){
				$(this).parent().removeClass('active');
				$(this).find("i").text("《");
			}else{
				$(this).parent().addClass('active');
				$(this).find("i").text("》");
			}
		});
		$(".xf_showsj em i").click(function(){
			$(".xf_qksjpop").show();
		});
		
		
		$(".xf_qksjpop div a:eq(0)").click(function(){
			$(".xf_showsj p").remove();
			$(".xf_showsj .xf_scsjbutton a").addClass('noneac');
		});
		$(".tag2").click(function(){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
			}else{
				$(this).addClass('active');
			}
		});
		$(".xf_datika ul li div i").click(function(){
			if($(this).hasClass('cur')){
				$(this).removeClass('cur');
			}else{
				$(this).addClass('cur');
			}
		});

		$(".xf_xuanzekm").click(function(){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$(".xf_xuanzekmbox").hide();
			}else{
				$(this).addClass('active');
				$(".xf_xuanzekmbox").show();
			}
		});
		$(".zan").click(function(){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
			}else{
				$(this).addClass('active');
			}
		});
		$(".xf_xuanzekmbox div a").click(function(){
			$(".xf_xuanzekm").removeClass('active');
			$(".xf_xuanzekmbox").hide();
		});
		$(".xf_shaixuansctm a,.down_btn").click(function(){
			if($(this).hasClass('active')){
				$(this).removeClass('active');
			}else{
				$(this).addClass('active');
			}
		});
		

	// 选择搜索类型
	$(".sea_left em").click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur').siblings().hide();
		}else{
			$(this).addClass('cur').siblings().show();
		}
	});
	$(".sea_left ul li").click(function(){
		$(this).parent().hide().siblings().removeClass('cur').find("a").text($(this).text());
	});
	$(".xf_righexzfl ul li").click(function(){
		$(this).addClass('cur').siblings().removeClass('cur')
	});
	
	// 答题卡
	$(".xf_datika em span").html("<i>0</i>/"+$(".xf_xuanzetibox ul li").length)
	$(".xf_shaixuansctm a,.is_zd a,.down_btn").click(function(){
		if($(this).hasClass('active')){
			$('#auto_login').val(0);
			$(this).removeClass('active');
		}else{
			$('#auto_login').val(1);
			$(this).addClass('active');
		}
	});
	// 切换科目
	$(".xf_dqkm a").click(function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(".xf_xuanzekmbox").hide();
		}else{
			$(this).addClass('active');
			$(".xf_xuanzekmbox").show();
		}
	});
	$(".xf_xuanzekmbox div a").click(function(){
		$(".xf_dqkm a").removeClass('active').siblings(".xf_dqkmlx").text($(this).text());
		$(".xf_xuanzekmbox").hide();
		return false;
	});
	$(".xf_shaixuanbox .more_tags").click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur').siblings(".tags_line").css("height",44)
		}else{
			$(this).addClass('cur').siblings(".tags_line").css("height","auto")
		}
	});
	$(".xf_datika em u").click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur').parent().siblings("ul").slideDown(300);
		}else{
			$(this).addClass('cur').parent().siblings("ul").slideUp(300);;
		}
	});
	$(".xf_bentijx").click(function(){
		if($(this).hasClass('cur')){
			$(this).removeClass('cur').parents(".xf_teshuli").find(".xf_jiexida").slideUp(300);
		}else{
			$(this).addClass('cur').parents(".xf_teshuli").find(".xf_jiexida").slideDown(300);
		}
	});
	
	$(".xf_quanbfs a").click(function(){
		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$(".xf_xueshenglb ul li").removeClass('active');
		}else{
			$(this).addClass('active');
			$(".xf_xueshenglb ul li").addClass('active');
		}
	});
	
	$(".xf_fasongxspop .xf_biaoti a").click(function(){
		$(".xf_fasongxspop,.xf_popbg").hide();
	});


	$(".xf_tybutton a,.xf_bianjipop .xf_biaoti a").click(function(){
		$(".xf_bianjipop,.xf_popbg").hide();
	});

	
	$(".xf_tishijjpop div a").click(function(){
		$(".xf_tishijjpop").hide();
	});
	$(".xf_jiucuo").click(function(){
		$(this).siblings(".xf_jiecuobox").show();
	});
	
	
	$(".param_tr td span").click(function(){
		$(this).parents(".param_tr").remove();
	});

})
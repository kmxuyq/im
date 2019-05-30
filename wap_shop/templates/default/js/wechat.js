// JavaScript Document
$(function(){
	//禁止手机的默认滑动事件
	//document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
	//底部导航
	$(".footer ul li a").on("click",function(){
		$(this).addClass("selected").parent().siblings().find("a").removeClass("selected");
		})
	//轮播图js
	var banner_len=$(".banner ul li").length;
	var banner_nav='';
	for(i=0;i<banner_len;i++){
		banner_nav+="<a href='javascript:;'></a>";
		}
	$(".flicking_con").html(banner_nav).css("margin-left",'-'+(banner_len*18-10)/2+"px");
	$dragBln = false;
	$(".main_image").touchSlider({
		flexible : true,
		speed : 500,
		btn_prev : $("#btn_prev"),
		btn_next : $("#btn_next"),
		paging : $(".flicking_con a"),
		counter : function (e){
			$(".flicking_con a").removeClass("on").eq(e.current-1).addClass("on");
		}
	});
	
	$(".main_image").bind("mousedown", function() {
		$dragBln = false;
	});
	
	$(".main_image").bind("dragstart", function() {
		$dragBln = true;
	});
	
	$(".main_image a").click(function(){
		if($dragBln) {
			return false;
		}
	});
	
	timer = setInterval(function(){
		$("#btn_next").click();
	}, 3500);
	
	$(".banner").hover(function(){
		clearInterval(timer);
	},function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		},3500);
	});
	
	$(".main_image").bind("touchstart",function(){
		clearInterval(timer);
	}).bind("touchend", function(){
		timer = setInterval(function(){
			$("#btn_next").click();
		}, 3500);
	});
	//搜索框关闭按钮显示/隐藏 和事件
	$(".search input").keydown(function(){
		$(".empty").show();
		})
	$(".empty").on("click",function(){
		$(this).hide().siblings("input").val("").focus();
		})
	//子菜单导航出现
	$(".first_pages_nav").on("click",function(){
		$(".shaixuan").removeClass("animate");
		$(".screening").slideUp(500);
		$(".shaixuan_mask").hide();
		
		$(".sub_menu").addClass("animate");
		$(".sub_menu_bg").show();
		})
	$(".sub_menu_bg,.sub_menu ul li a").on("click",function(){
		$(".sub_menu").removeClass("animate");
		$(".sub_menu_bg").hide();
		})
	//GF传奇和GF资讯 选项卡
	$(".gf-legend .gf-legend-nav li").on("click",function(){
		var index=$(this).index();
		$(this).find("a").addClass("on").parent().siblings("li").find("a").removeClass("on");
		$(".gf-legend-cons").hide();
		if(index==0){
			$(".gf-legend-cons-l").show();
			}
		else{
			$(".gf-legend-cons-r").show();
			}
		})
	
	//搜索列表 里 点击筛选
	$(".search_results .search_results_nav .shaixuan").on("click",function(){
		$(".screening").slideToggle(500);
		$(this).toggleClass("animate");
		$(".shaixuan_mask").toggle();
		})
	$(".screening ul li a").on("click",function(){
		$(".screening").slideUp(500);
		$(".search_results .search_results_nav .shaixuan").removeClass("animate");
		$(".shaixuan_mask").hide();
		})
})














































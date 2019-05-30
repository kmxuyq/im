;$(function(){
	$(".ym-apply-tab li").tabBox({
		boxClass : ".ym-tab-box",
		onClass : "on"
	});
	$(document).selectCity({
		selectDom : ["#ym-province", "#ym-city"],
		initVal : ["请选择省份", "请选择城市"]
	});
});
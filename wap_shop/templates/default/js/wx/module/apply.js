$(function(){
	$(".ym-blue-btn").on("click",function(){
		$(".apply-id").hide();
	})
	 $(".ym-apply-tab li").click(function(){
      $(this).addClass("on").siblings().removeClass("on");
    });
})
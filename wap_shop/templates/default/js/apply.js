$(function(){
	/*$(".ym-blue-btn").on("click",function(){
		$(".apply-id").hide();
	})*/
	 $(".ym-apply-tab li").click(function(){
      $(this).addClass("on").siblings().removeClass("on");
    });
	 $(".btn_n").click(function () {
		 alert("ss");
		$("az").val("您已经参加免费申领活动，请耐心等候！！");
	})
})
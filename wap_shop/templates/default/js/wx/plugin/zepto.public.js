;(function($){
	$.fn.tabBox = function(options){
		var opts = $.extend({
			boxClass : '.ym-tab-box',
			onClass : 'on'
		}, options);
		var $self = $(this);		
		
		$self.on("click",function(){
			var i = $(this).index();
			$(this).siblings().removeClass(opts.onClass);
			$(this).addClass(opts.onClass);
			$(opts.boxClass).hide();
			$(opts.boxClass).eq(i).show();
			return false;
		});
	}
})(Zepto);
define(function(require, exports, module) {

	function $inform() {
		this.init.apply(this, arguments);
	};

	about.prototype = {
		init: function() {
			var _self= this;
			this.bindDomEvent();
			
		},
		bindDomEvent:function(){
			$(document).on('click', '.inform', function(e) {
			   alert(1)
			 });
			
		}
	};
	module.exports = new $inform();

});
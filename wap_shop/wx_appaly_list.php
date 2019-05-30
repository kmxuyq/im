<meta charset="utf-8"/>
<script src="/data/resource/js/jquery.js"></script>
<script>

$(function(){
	function GetQueryString(name) {  
	    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");  
	    var r = location.search.substr(1).match(reg);  
	    if (r != null) return unescape(decodeURI(r[2])); return null;  
	}
	var code=GetQueryString("code");
	var link='/wap_shop/?act=search&op=product_list&type=appaly&wx=0&cate_id=1106'+'&code='+code;
	if(code!=''){
		$.get('/mobile/?act=index&op=weixin_login&code='+code,function(data){
			window.location.href = link;
		});
	}else{
		window.location.href=link;
	}
});
</script>
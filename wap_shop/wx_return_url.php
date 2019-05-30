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
	var openid=GetQueryString("openid");
	var link='<?php echo base64_decode($_GET["return_url"])?>';
	if(code!=''&&code!=null){
		$.get('/mobile/?act=index&op=weixin_login&code='+code,function(data){
			//alert('/mobile/?act=index&op=weixin_login&code='+code);
			window.location.href = link;
		});
	}else if(openid!=''&&openid!=null){
		$.get('/mobile/?act=index&op=weixin_login&openid='+openid,function(data){
			//document.write('/mobile/?act=index&op=weixin_login&openid='+openid);
			window.location.href = link;
		});
	
	} else{
		window.location.href=link;
	}
});
</script>
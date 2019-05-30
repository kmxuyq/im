<!-- 分享 -->
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<?php

	function get_access_token($store_id,$appid,$appsecret){
		$token = rkcache("access_token_$store_id");
		if(!empty($token)){
			$token = unserialize($token);
		}else{
			$token = array();
		}
		if(empty($token) || time()-$token['create_time'] > 7200){
			$uri   = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
			$token = curl($uri);
			$token = json_decode($token,true);
			if(!isset($token['access_token']) || empty($token['access_token'])){
				return '';
			}
			$token['create_time']=time();
			wkcache("access_token_$store_id",serialize($token));
		}
		return $token['access_token'];

	}
	
	function get_jsapi_ticket($store_id,$appid,$appsecret) {
		$tickets = rkcache("jsapi_ticket_$store_id");
		if ($tickets) {
			$tickets = unserialize($tickets);
		}
		if (empty($tickets) || time() - $tickets['create_time'] >= 7200) {
			$access_token = get_access_token($store_id,$appid,$appsecret);
			if ('' == $access_token) {
				return '';
			}
			$uri     = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
			$tickets = curl($uri);
			$tickets = json_decode($tickets, true);
			if (empty($tickets['ticket'])) {
				return '';
			}
			$tickets['create_time'] = time();
			wkcache("jsapi_ticket_$store_id", serialize($tickets));
		}
		return $tickets['ticket'];
	}
	
	//store_id 优先级 $_SESSION['store_member_info_ID'] >>  $_GET['store_id'] >> 7
	$store_id = intval($_SESSION['store_member_info_ID'])?intval($_SESSION['store_member_info_ID']):(intval($_GET['store_id'])?intval($_GET['store_id']):7);
	$action   = WAP_SHOP_SITE_URL."/index.php?act=payment_handle&op=getStoreOrderInfo";
	$return2  = json_decode(curl($action,'post',array('type'=>'store_wxinfo','store_id'=>$store_id)),true);
	//得到  jsapi_ticket 、 access_token
	$jsapi_ticket 	= get_jsapi_ticket($store_id,$return2['data']['APPID'],$return2['data']['APPSECRET']);
	$timestamp 		= time();
	$chars 	= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$str   	= '';
	for ($i = 0; $i < 16; $i++) {
		$str .= $chars{mt_rand(0, strlen($chars) - 1)};
	}
	$protocol 	= strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') !== false ? 'https://' : 'http://';
	$host     	= $protocol . $_SERVER['HTTP_HOST'];
	$url      	= $host . $_SERVER['REQUEST_URI'];
	$string1   	= "jsapi_ticket={$jsapi_ticket}&noncestr={$str}&timestamp={$timestamp}&url={$url}";
	$signature 	= sha1($string1);
	//分享的链接
	$ref_url  = 'http://'.$_SERVER['HTTP_HOST'].
	            $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'].
	            '&store_member_info='.$store_id;
	?>
	<script>
	wx.config({
	    debug 	 : false, 										// 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	    appId 	 : '<?php echo $return2['data']['APPID']; ?>',  // 必填，公众号的唯一标识
	    timestamp: '<?php echo $timestamp; ?>', 			    // 必填，生成签名的时间戳
	    nonceStr : '<?php echo $str; ?>', 					    // 必填，生成签名的随机串
	    signature: '<?php echo $signature; ?>',				    // 必填，签名，见附录1
	    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});
	//分享的信息
	var sharedata = {
	   title 	:'昆明旅游超市',
	   desc  	:'昆明旅游超市欢迎您,希望你再来',
	   imgUrl 	:'https://a-ssl.duitang.com/uploads/item/201504/09/20150409H0738_VC8WR.jpeg',
	   link  	:'<?php echo $ref_url; ?>',
	   success 	:function(){
		  console.log('user success share');
	   },
	   cancel 	:function(){
	      console.log('user cancel share');
	   }
	};
	wx.ready(function () {
	   wx.onMenuShareAppMessage(sharedata);
	   wx.onMenuShareTimeline(sharedata);
	});
	</script>

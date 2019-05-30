<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/
header("Content-type:text/html;charset=utf-8");
define('InShopNC',true);
include_once("./WxPayPubHelper/WxPayPubHelper.php");
include_once $_SERVER["DOCUMENT_ROOT"].'/core/framework/function/core.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/data/config/config.ini.php';
global $config;
	//error_reporting(0);
	//print_r($_GET);
	//echo 'fffffffffffffffffffffffffffff';exit();
	//使用jsapi接口
	function wx_pay($order_name,$order_id,$price,$site,$gid,$bc,$data){
		$jsApi = new JsApi_pub();
	    //赋值
		$jsApi->setWxParam($data);
		//=========步骤1：网页授权获取用户openid============
		//通过code获得openid
		if (!isset($_GET['code']))
		{
			//触发微信返回code码
			$url = $jsApi->createOauthUrlForCode($data['JS_API_CALL_URL']);
			//------------------
			$state =urlencode(json_encode(array(
					"body" => $order_name,
					"out_trade_no" => $order_id,
					"price" => $price,
					"attach" => $site,
					"gid" => $gid,
					"bc" => $bc,
			)));
			$url = str_replace("STATE", $state, $url);
			//------------------
			header("Location: {$url}");
		}else{
			//获取code码，以获取openid
			$code = $_GET['code'];
			$jsApi->setCode($code);
			$openid = $jsApi->getOpenId();
		}
		$state_arr=json_decode(urldecode($_GET["state"]));
		$order_name=str_replace(array('<','>',"'"), array('','',''), $state_arr->body);
		$tatal_fee=floatval($state_arr->price)*100;
		//增加数据中,有店铺的信息
		$attach=$state_arr->attach.'-'.$data['STORE_ID'];
		//print_r(json_decode($_GET["state"]));
		//echo $code.'--'.$openid;
		//=========步骤2：使用统一支付接口，获取prepay_id============
		//使用统一支付接口
		$unifiedOrder = new UnifiedOrder_pub();
		//赋值
		$unifiedOrder->setWxParam($data);
		//设置统一支付接口参数
		//设置必填参数
		//appid已填,商户无需重复填写
		//mch_id已填,商户无需重复填写
		//noncestr已填,商户无需重复填写
		//spbill_create_ip已填,商户无需重复填写
		//sign已填,商户无需重复填写
		//$order_name=str_replace(array('<','>',"'"), array('','',''),urlencode($_POST["tb_order_name"]));
		$unifiedOrder->setParameter("is_subscribe","Y");//是否自动关注关众号
		$unifiedOrder->setParameter("openid","$openid");//商品描述
		$unifiedOrder->setParameter("body",$order_name);//商品描述

		//自定义订单号，此处仅作举例
		$timeStamp =substr(time(),-6);
		//$out_trade_no =WxPayConf_pub::APPID."$timeStamp";
		$unifiedOrder->setParameter("out_trade_no",$state_arr->out_trade_no.'-'.$timeStamp);//商户订单号  .'-'.date('His')
		$unifiedOrder->setParameter("total_fee",$tatal_fee);//总金额
		$unifiedOrder->setParameter("notify_url",$data['NOTIFY_URL']);//通知地址
		$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
		//非必填参数，商户可根据实际情况选填
		//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
		//$unifiedOrder->setParameter("device_info","XXXX");//设备号
		$unifiedOrder->setParameter("attach",$attach);//附加数据
		//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
		//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
		//$unifiedOrder->setParameter("openid","XXXX");//用户标识
		//$unifiedOrder->setParameter("product_id","XXXX");//商品ID

		//echo "<pre>";print_r($unifiedOrder);echo "</pre>";
		$prepay_id = $unifiedOrder->getPrepayId();
	
		//=========步骤3：使用jsapi调起支付============
		$jsApi->setPrepayId($prepay_id);

		$jsApiParameters = $jsApi->getParameters();

		//echo "<pre>";		 print_r(json_decode($jsApiParameters));		echo "<pre>";
		return $jsApiParameters;
		//$_SESSION["order_name"]='';
		//$_SESSION["order_id"]='';
		//$_SESSION["order_price"]='';


	}
	//print_r($_GET);
	    if(!isset($_GET['code'])){
	    	$body =  $_GET['body'];
	    	$out_trade_no =  $_GET['out_trade_no'];
	    	$total_fee = $_GET['total_fee'];
	    	$attach =  $_GET['attach'];
	    	//--新添加--
	    	$gid = 0;
	    	$bc = 0;
	    	if(isset($_GET['gid'])){
	    		$gid = $_GET['gid'];
	    	}
	    	if(isset($_GET['b_c'])){
	    		$bc = $_GET['b_c'];
	    	}
	    }else{
	    	$state_arr_new = json_decode(urldecode($_GET["state"]),true);
	    	$attach        = $state_arr_new['attach'];
	    	$out_trade_no  = $state_arr_new['out_trade_no'];
	    }
		//根据不同的attach,去不同的值
		$action  =$config['wap_shop_site_url']."/index.php?act=payment_handle&op=getStoreOrderInfo";
		if($attach == 'vr_order'){
			//虚拟订单
			$return1  = json_decode(curl($action,'post',array('type'=>'vr_order','out_trade_no'=>$out_trade_no)),true);
			if(empty($return1) || empty($return1['stat']))  exit($return1['msg']);
			$store_id = $return1['data'];
		}elseif ($attach == 'real_order'){
			//真实订单
			$return1 = json_decode(curl($action,'post',array('type'=>'real_order','out_trade_no'=>$out_trade_no)),true);
			if(empty($return1) || empty($return1['stat']))  exit($return1['msg']);
			$store_id = $return1['data'];
		}else{
			//夺宝  和 会员充值 预存卡
			$store_id = $_SESSION['store_member_info_ID'] ;
		}
		$return2  = json_decode(curl($action,'post',array('type'=>'store_wxinfo','store_id'=>$store_id)),true);
		if(empty($return2['stat'])) exit($return2['msg']);
		$jsApiParameters=wx_pay($body, $out_trade_no,$total_fee,$attach,$gid,$bc,$return2['data']);
		
$o_info = json_decode($_GET['state'], true);
?>
<html lang="en">
<head>
   <meta http-equiv="content-type" content="text/html;charset=utf-8">
   <title>微信支付</title>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
</head>
<body ontouchstart>
<script type="text/javascript" src="/data/resource/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/data/resource/js/layer-v2.1/layer/layer.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>


	<script type="text/javascript">
		var time='<?php echo time()?>';
		callpay();
		//-------------------------------
		//调用微信JS api 支付
	       function jsApiCall()
	       {
		       var siteurl='<?php echo $data['SITEURL'];?>';
	           WeixinJSBridge.invoke(
	               'getBrandWCPayRequest',
	               <?php echo $jsApiParameters; ?>,
	               function(res){
	                   WeixinJSBridge.log(res.err_msg);
	                   if(res.err_msg == "get_brand_wcpay_request:ok"){
	                   		//layer.msg(res.err_code+res.err_desc+res.err_msg);
                           if(<?php echo $o_info['attach']=='vr_order' ? 1: 0; ?>){
                              window.location.href=siteurl+'/wap_shop/index.php?act=buy_virtual&op=pay_ok&order_sn=<?php echo $o_info['out_trade_no']; ?>';
                           } else if(<?php echo ($o_info['attach'] == 'pdr_order') ? 1: 0; ?>){
							   //积分充值
							  window.location.href = siteurl+'/wap_shop/index.php?act=duobao&op=inOrders&gid=<?php echo $o_info['gid'];?>&b_c=<?php echo $o_info['bc'];?>&pay_sn=<?php echo $o_info['out_trade_no']; ?>';
						   } else {
                              window.location.href=siteurl+'/wap_shop/index.php?act=buy&op=pay_ok&source=weixin_jsapi';
                           }
		              }else{
						   window.location.href=siteurl+'/wap_shop/index.php?act=member&op=home';
	                       //返回跳转到订单详情页面
/*                          layer.open({
                             type:1,
                             content:'支付失败',
                             btn: '确定',
                             area:['80%', '150px'],
                             yes: function(){
                                window.location.href=siteurl+'/wap_shop/index.php?act=member&op=home';
                             }
                          });*/
						   /*
						   alertPopWin('支付失败',function(){
							   window.location.href=siteurl+'/wap_shop/index.php?act=member&op=home';
						   })
						   */
	                   }
	               }
	           );
	       }
		//-------------------------------

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall);
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
		//------------------------------
		function is_wxpay(){
			 var ua = navigator.userAgent.toLowerCase();
			 //判断是否用微信打开
			    if(ua.match(/MicroMessenger/i)=="micromessenger") {
					getmess();
			    }

		}
		//------------------

		 function ShowMess(){
		 	$.get('/wap_shop/index.php?act=weixinpay&op=ispayok&order_sn=<?php echo $_GET['out_trade_no']; ?>&time='+time,function(result){
		 		var mess=result.split('|');
		 		if(mess[0]>0){
		 			var is_vr='buy';
		 			//如果是虚拟订单
					if(mess[2]=='vr_order'){
						is_vr='buy_virtual';
					}
					//如果是用户预存款充值
					if(mess[2]=='pd_order'){
						window.location.href='/wap_shop/index.php?act=predeposit&op=pd_log_list';return false;
					}
		 			window.location.href='/wap_shop/index.php?act='+is_vr+'&op=pay_ok&order_sn=<?php echo $_GET['out_trade_no']; ?>&pay_amount='+mess[1];return false;
		 		}
		 	});
		 }
		 function getmess(){
		 	window.setInterval("ShowMess()",3000);	//显示消息
		 }
	</script>
<br/><br/><br/>
	<div align="center">
		<button style="width:210px; line-height:2.2; background-color:#FE6714; border:0px #FE6714 solid;cursor: pointer;color:white;font-size:25px;display:none" type="button" onclick="callpay()" >确认支付</button>
	</div>
</body>
</html>

<?php
/**
 * 通用通知接口demo
 * ====================================================
 * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
 * 商户接收回调信息后，根据需要设定相应的处理流程。
 * 
 * 这里举例使用log文件形式记录回调信息。
*/

define('InShopNC',true);

require_once ("WxPayPubHelper/WxPayPubHelper.php");
// echo $_SERVER['HTTP_HOST'] ;
require_once $_SERVER ["DOCUMENT_ROOT"] . '/wap_shop/framework/function/function.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/data/config/config.ini.php';
global $config;
// 使用通用通知接口
$notify = new Notify_pub ();

// 存储微信的回调
$xml = $GLOBALS ['HTTP_RAW_POST_DATA'];

$notify->saveData ( $xml );

//获取信息 begin
$store_id = substr(strstr($notify->data['attach'],'-'),1);
$action   = $config['wap_shop_site_url']."/index.php?act=payment_handle&op=getStoreOrderInfo";
$return2  = json_decode(curl($action,'post',array('type'=>'store_wxinfo','store_id'=>$store_id)),true);
if(empty($return2['stat'])) exit($return2['msg']);
$data = $return2['data'];
//获取信息 end
//赋值
$notify->setWxParam($data);
// 验证签名，并回应微信。
// 对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
// 微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
// 尽可能提高通知的成功率，但微信不保证通知最终能成功。
if ($notify->checkSign () == FALSE) {
	$notify->setReturnParameter ( "return_code", "FAIL" ); // 返回状态码
	$notify->setReturnParameter ( "return_msg", "签名失败" ); // 返回信息
} else {
	$notify->setReturnParameter ( "return_code", "SUCCESS" ); // 设置返回码
}
$returnXml = $notify->returnXml ();
//echo $returnXml;
	
//==商户根据实际情况设置相应的处理流程，此处仅作举例=======

//以log文件形式记录回调信息
$log_name='';//"./notify_url.log";//log文件路径
log_result("【接收到的notify通知】:\n".$xml."\n",'xml');
$wxpay_client_pub = new Wxpay_client_pub ();
//赋值
$wxpay_client_pub ->setWxParam($data);
if ($notify->checkSign () == TRUE) {
	if ($notify->data ["return_code"] == "FAIL") {
		// 此处应该更新一下订单状态，商户自行增删操作
		// log_result($log_name,"【通信出错】:\n".$xml."\n");
		log_result ( "【通讯出错】" . $xml, 'xml' );
	} elseif ($notify->data ["result_code"] == "FAIL") {
		// 此处应该更新一下订单状态，商户自行增删操作
		// log_result($log_name,"【业务出错】:\n".$xml."\n");
		log_result ( "【业务出错】" . $xml, 'xml' );
	} 
	else{
		$arr=$wxpay_client_pub->xmlToArray($xml);
		//将 "attach" 还原 begin
		$arr["attach"] = strstr($arr["attach"],'-',true);
		//还原 end
		$remark=$arr["attach"];//附加数据，这里传的是网站域名，以处理不同网站数据
		$pay_status=$arr["attach"].'|'.$arr["return_code"].'|'.$arr['result_code'].'|'.$arr["return_msg"].'|'.$arr["trade_type"].'|'.$arr["time_end"];//支付状态信息
		$str='3'.','.$arr["out_trade_no"].','.$arr["bank_type"].','.($arr["total_fee"]/100).','.$arr["fee_type"].','.''.','.$arr["transaction_id"].','.$pay_status;
		log_result("【支付成功】".$xml.$str,'xml');
		$action= $wxpay_client_pub->SITEURL."/wap_shop/index.php?act=payment_handle";
		curl($action,'post',$arr);
	}
	
	//商户自行增加处理流程,
	//例如：更新订单状态
	//例如：数据库操作
	//例如：推送支付完成信息
}
?>
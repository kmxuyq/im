<?php
/**
 * 购买流程 好商城V3
 */
defined('InShopNC') or exit('Access Invalid!');
require_once("./api/payment/wxpay/WxPayPubHelper/WxPayPubHelper.php");
class weixinpayControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_SESSION['member_id']){
            redirect('index.php?act=login&ref_url='.urlencode(request_uri()));
        }
        //验证该会员是否禁止购买
        if(!$_SESSION['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
    }

    public function indexOp() {
    	//增加支付处理 begin
    	$store_id = $_GET['store_id'];
    	//根据store_id获取对应的支付信息
    	$return  = json_decode(curl(
    			   WAP_SHOP_SITE_URL."/index.php?act=payment_handle&op=getStoreOrderInfo",'post',
    			   array('type'=>'store_wxinfo','store_id'=>$store_id)
    			   ),true);
    	if(empty($return['stat'])) exit($return['msg']);
    	$pay_data = $return['data'];
    	//增加支付处理 end
		// 使用统一支付接口
    	error_reporting(0);
		$unifiedOrder = new UnifiedOrder_pub ();
		//赋值
		$unifiedOrder->setWxParam($pay_data);
		// 设置统一支付接口参数
		// 设置必填参数
		// appid已填,商户无需重复填写
		// mch_id已填,商户无需重复填写
		// noncestr已填,商户无需重复填写
		// spbill_create_ip已填,商户无需重复填写
		// sign已填,商户无需重复填写
		// 获取商品描述，订单号，总金额,附加数据
//		$body=mb_convert_encoding($_GET['body'], "UTF-8");
		$body=$_GET['body'];
		$out_trade_no = $_GET ['out_trade_no'] .'-'. date ( 'dHis' );
		$total_fee = $_GET ['total_fee'];
		$attach = $_GET ['attach'];
		$unifiedOrder->setParameter("is_subscribe","Y");//是否自动关注关众号
		$unifiedOrder->setParameter ( "body", $body ); // 商品描述
		$unifiedOrder->setParameter ( "out_trade_no", $out_trade_no ); // 商户订单号
		$unifiedOrder->setParameter ( "total_fee", $total_fee ); // 总金额
		
		$unifiedOrder->setParameter ( "notify_url", $pay_data['NOTIFY_URL']); // 通知地址
		$unifiedOrder->setParameter ( "trade_type", "NATIVE" ); // 交易类型
		
		//增加数据中,有店铺的信息
		$unifiedOrder->setParameter ( "attach", $attach.'-'.$store_id); // 附加数据
		                                                 
		// 非必填参数，商户可根据实际情况选填
		// $unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
		// $unifiedOrder->setParameter("device_info","XXXX");//设备号
		// $unifiedOrder->setParameter("attach","XXXX");//附加数据
		// $unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
		// $unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
		// $unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
		// $unifiedOrder->setParameter("openid","XXXX");//用户标识
		// $unifiedOrder->setParameter("product_id","XXXX");//商品ID                                                 
		                                                 
		// 获取统一支付接口结果
		$unifiedOrderResult = $unifiedOrder->getResult ();
		
		$a = $unifiedOrderResult ["return_code"];
		$b = $unifiedOrderResult ["result_code"];
		$c = $unifiedOrderResult ['return_msg'];
		// echo
		// "<script>alert('$a'+'$b'+'$c');alert('$b');alert('$c');</script>";
		// 商户根据实际情况设置相应的处理流程
		if ($unifiedOrderResult ["return_code"] == "FAIL") {
			// 商户自行增加处理流程
			echo "通信出错：" . $unifiedOrderResult ['return_msg'] . "<br>";
		} elseif ($unifiedOrderResult ["result_code"] == "FAIL") {
			// 商户自行增加处理流程
			echo "错误代码：" . $unifiedOrderResult ['err_code'] . "<br>";
			echo "错误代码描述：" . $unifiedOrderResult ['err_code_des'] . "<br>";
		} elseif ($unifiedOrderResult ["code_url"] != NULL) {
			// 从统一支付接口获取到code_url
			$code_url = $unifiedOrderResult ["code_url"];
			// 商户自行增加处理流程
			// ......
		}
		// $js_api_url="?act=weixinpay&op=jsapi&body={$body}&out_trade_no={$out_trade_no}&total_fee={$total_fee}&attach={$attach}";
		Tpl::output ( 'unifiedOrderResult', $unifiedOrderResult );
		Tpl::output ( 'code_url', $code_url );
		Tpl::showpage ( 'buy_weixinpay' );
    }
    
    public function notify_urlOp(){
    	require_once($_SERVER["DOCUMENT_ROOT"]."/wap_shop/framework/function/function.php");    	
    	$tt=log_result('', json_encode($_GET));    	
    	print_r($tt);exit();
    	//echo WxPayConf_pub::APPID;
    	require_once("./api/payment/wxpay/log_.php");
    	$log_ = new Log_();
    	//require_once($_SERVER["DOCUMENT_ROOT"]."/wap_shop/framework/function/function.php");
		$tt=$log_->log_result('', json_encode($_POST));	
		exit();
    	//echo $_SERVER['HTTP_HOST'] ;   	
    	//使用通用通知接口
    	$notify = new Notify_pub();
    	//存储微信的回调
    	$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
    	
    	//获取交易支付id,附加数据,交易结果
    	$postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    	$pay_sn = $postObj->out_trade_no;  //可以这样获取XML里面的信息
    	$attach = $postObj->attach;
    	$result_code = $postObj->result_code ;
    	
    	$notify->saveData($xml);
    	
    	//验证签名，并回应微信。
    	//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
    	//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
    	//尽可能提高通知的成功率，但微信不保证通知最终能成功。
    	if($notify->checkSign() == FALSE){
    		$notify->setReturnParameter("return_code","FAIL");//返回状态码
    		$notify->setReturnParameter("return_msg","签名失败");//返回信息
    	}else{
    		$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
    	}
    	$returnXml = $notify->returnXml();
    	echo $returnXml;
    	
    	//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
    	
    	//以log文件形式记录回调信息
    	
    	$log_name='';//"./notify_url.log";//log文件路径
    	$log_->log_result($log_name,"【接收到的notify通知0】:\n".$xml."\n");
    	
    	if($notify->checkSign() == TRUE)
    	{
    		if ($notify->data["return_code"] == "FAIL") {
    			//此处应该更新一下订单状态，商户自行增删操作
    			$log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
    		}
    		elseif($notify->data["result_code"] == "FAIL"){
    			//此处应该更新一下订单状态，商户自行增删操作
    			$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
    		}
    		else{
    			//此处应该更新一下订单状态，商户自行增删操作
    			$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
    	
    			$log_->log_result($log_name,"【支付成功】:\n".$pay_sn."\n");
    			$log_->log_result($log_name,"【支付成功11】:\n".$attach."\n");
    			//$log_->log_result($log_name,"【支付成功12】:\n".$order_list."\n");
    			//终于到最后一步了。
    			$_GET['act']	= 'payment';
    			$_GET['op']	= 'return';
    			$_GET['payment_code'] = 'wxpay';
    			//获取订单交易类型，一个是商品购买，一个是预存款,这里取返回的附加数据
    			$_GET['extra_common_param'] = $attach;
    			//交易号，这里取返回的pay_sn即可
    			$_GET['out_trade_no'] = $pay_sn;
    	
    			$_GET['result_code'] = $result_code ;
    			require_once(dirname(__FILE__).'/../../../index.php');
    		}   	
    		//商户自行增加处理流程,
    		//例如：更新订单状态
    		//例如：数据库操作
    		//例如：推送支付完成信息
    	}
    }
    /**
     * 微信支付是否成功，如果成功就跳转到支付成功页面，每三秒刷新一次
     */
    public function ispayokOp(){
    	$time=$_GET["time"];
    	$type='微信';
    	$order_id=$_GET["order_sn"];    	
    	$where="oid='{$order_id}' and type='{$type}' and d_time>={$time}";
    	$count=Model()->table('pay_log')->where($where)->count();
    	if($count>0){
    		//remark1为订单类型，real_order为实物订单，vr_order为线路订单
    		$amount=Model()->table('pay_log')->where($where)->field('amount,remark1')->order("d_time desc")->find();
    		echo $count.'|'. encrypt($amount['amount']).'|'.$amount["remark1"];	
    	}
    }   
    /**
     * 通用通知接口demo
     * ====================================================
     * 支付完成后，微信会把相关支付和用户信息发送到商户设定的通知URL，
     * 商户接收回调信息后，根据需要设定相应的处理流程。
     *
     * 这里举例使用log文件形式记录回调信息。
     */
	public function notify_urlOp0(){
		require_once (FUNCTION_PATH.'function.php');
		
		//log_result('',"1110000000000000");exit();
		include_once LIB_PATH.'PaymentHandle.php';
		require_once($_SERVER["DOCUMENT_ROOT"]."/wap_shop/api/payment/wxpay/WxPayPubHelper/WxPayPubHelper.php");
		$payment=new PaymentHandle();
		//echo $_SERVER['HTTP_HOST'] ;
		
		
		//使用通用通知接口
		$notify = new Notify_pub();
		//存储微信的回调
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		
		//获取交易支付id,附加数据,交易结果
		$postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$pay_sn = $postObj->out_trade_no;  //可以这样获取XML里面的信息
		$attach = $postObj->attach;
		$result_code = $postObj->result_code ;
		
		$notify->saveData($xml);
		
		//验证签名，并回应微信。
		//对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
		//微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
		//尽可能提高通知的成功率，但微信不保证通知最终能成功。
		if($notify->checkSign() == FALSE){
			$notify->setReturnParameter("return_code","FAIL");//返回状态码
			$notify->setReturnParameter("return_msg","签名失败");//返回信息
		}else{
			$notify->setReturnParameter("return_code","SUCCESS");//设置返回码
		}
		$returnXml = $notify->returnXml();
		//echo $returnXml;
		
		//==商户根据实际情况设置相应的处理流程，此处仅作举例=======
		
		//以log文件形式记录回调信息
		//$log_ = new Log_();
		$log_name='wx';//log文件路径
		$wxpay_client_pub=new Wxpay_client_pub();
		log_result($log_name,"【接收到的notify通知】:\n".$xml."\n");
		
		if($notify->checkSign() == TRUE)
		{
			if ($notify->data["return_code"] == "FAIL") {
				//此处应该更新一下订单状态，商户自行增删操作
				log_result($log_name,"【通信出错】:\n".$xml."\n");
			}
			elseif($notify->data["result_code"] == "FAIL"){
				//此处应该更新一下订单状态，商户自行增删操作
				log_result($log_name,"【业务出错】:\n".$xml."\n");
			}
			else{
				/*//此处应该更新一下订单状态，商户自行增删操作
				$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
		
				$log_->log_result($log_name,"【支付成功】:\n".$pay_sn."\n");
				$log_->log_result($log_name,"【支付成功11】:\n".$attach."\n");
				//$log_->log_result($log_name,"【支付成功12】:\n".$order_list."\n");
				//终于到最后一步了。
				$_GET['act']	= 'payment';
				$_GET['op']	= 'return';
				$_GET['payment_code'] = 'wxpay';
				//获取订单交易类型，一个是商品购买，一个是预存款,这里取返回的附加数据
				$_GET['extra_common_param'] = $attach;
				//交易号，这里取返回的pay_sn即可
				$_GET['out_trade_no'] = $pay_sn;
		
				$_GET['result_code'] = $result_code ;
				require_once(dirname(__FILE__).'/../../../index.php');*/
				$arr=$wxpay_client_pub->xmlToArray($xml);
				$remark=$arr["attach"];//附加数据，这里传的是网站域名，以处理不同网站数据
				$pay_status=$arr["attach"].'|'.$arr["return_code"].'|'.$arr['result_code'].'|'.$arr["return_msg"].'|'.$arr["trade_type"].'|'.$arr["time_end"];//支付状态信息
				$str='3'.','.$arr["out_trade_no"].','.$arr["bank_type"].','.($arr["total_fee"]/100).','.$arr["fee_type"].','.''.','.$arr["transaction_id"].','.$pay_status;
				log_result("【支付成功】".$xml.$str,'xml');
				$payadd=$payment->pay_add('微信',$arr["out_trade_no"],$arr["bank_type"],($arr["total_fee"]/100),$arr["fee_type"],$remark,$arr["transaction_id"],$pay_status);
			}
		
			//商户自行增加处理流程,
			//例如：更新订单状态
			//例如：数据库操作
			//例如：推送支付完成信息
		}
	}  
}

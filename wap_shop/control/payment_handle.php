<?php
/**
 * 购买流程 好商城V3
 */
//defined('InShopNC') or exit('Access Invalid!');
class payment_handleControl extends Control {
	/**
	 * 微信支付处理
	 */	
	public function indexOp(){
		$payment=Model('payment_handle');
		$arr=$_POST;
		$remark=$arr["attach"];//附加数据，这里传的是网站域名，以处理不同网站数据
		$pay_status=$arr["attach"].'|'.$arr["return_code"].'|'.$arr['result_code'].'|'.$arr["return_msg"].'|'.$arr["trade_type"].'|'.$arr["time_end"];//支付状态信息
		/* $word='微信'.'--'.$arr["out_trade_no"].'--'.$arr["bank_type"].'--'.($arr["total_fee"]/100).'--'.$arr["fee_type"].'--'.$remark.'--'.$arr["transaction_id"].'--'.$pay_status;
		log_result($word, ''); */
		$payadd=$payment->pay_add('微信',$arr["out_trade_no"],$arr["bank_type"],($arr["total_fee"]/100),$arr["fee_type"],$remark,$arr["transaction_id"],$pay_status);
	}
	/**
	 * 支付宝支付处理
	 */
	public function alipayOp(){
		$payment=Model('payment_handle');
		$arr=$_POST;
		//($pay_type,$order_id,$mode,$amount,$moneytype,$remark1,$alipay_trade_no,$alipay_trade_status)
		$payadd=$payment->pay_add('alipay',$arr["out_trade_no"],'',$arr["total_fee"],'','',$arr["trade_no"],$arr["trade_status"]);
	}
	/**
	 * 支付成功跳转处理
	 */
	public function pay_successOp(){
		$order_sn_tem=explode('-', $_GET["order_sn"]);
		$order_sn=$order_sn_tem[0];
		$order_amount=encrypt($_GET["order_amount"]);
		$is_vr='buy';
		$is_order=Model()->table('vr_order')->where(array('order_sn'=>$order_sn))->count();
		if($is_order>0){
			//虚拟订单
			$is_vr='buy_virtual';
		}
		header("Location:/wap_shop/index.php?act={$is_vr}&op=pay_ok&order_sn={$order_sn}&order_amount={$order_amount}");
	}
	/**
	 * 获取支付接口参数，以JSON数据返回
	 * /wap_shop/index.php?act=payment_handle&op=get_payment_api_json&payment_code=alipay
	 */
	public function get_payment_api_jsonOp(){
		if(!empty($_GET["payment_code"])){
			$arr_str=Model()->table('payment')->where(array('payment_code'=>$_GET["payment_code"]))->field('payment_config')->find();
			$arr=unserialize($arr_str["payment_config"]);
			echo json_encode($arr);
		}
	}
	public function testOp(){
		$payment=Model('payment_handle');
		//'微信--500497788308379083-10103151--ABC_DEBIT--0.01--CNY--real_order--1001330388201510101146606649--real_order|SUCCESS|SUCCESS||NATIVE|20151010103213'
		//$payadd=$payment->pay_add('微信','490496766096870001-'.date('His'),'ABC_DEBIT',0.01,'CNY','real_order','1001330388201510091139619983','real_order|SUCCESS|SUCCESS||NATIVE|2015100915365');

	}
	/**
	 * 异步获取订单,openid 信息
	 */
	public function getStoreOrderInfoOp(){
		if(!isset($_POST['type']) || !in_array($_POST['type'],array('vr_order','real_order','store_wxinfo'))){
			  exit(json_encode(array('stat'=>0,'msg'=>'无效参数')));
		}
		if($_POST['type'] == 'vr_order'){
			//虚拟订单
			$out_trade_no = trim($_POST['out_trade_no']);
			if(empty($out_trade_no)) exit(json_encode(array('stat'=>0,'msg'=>'请传递订单号')));
			$store_info = Model('vr_order')->getOrderInfo(array('order_sn'=>$out_trade_no),'store_id');
			if(empty($store_info)) exit(json_encode(array('stat'=>0,'msg'=>'对应的订单号'.$out_trade_no.'没有找到订单信息')));
			exit(json_encode(array('stat'=>1,'data'=>$store_info['store_id'])));
		}elseif ($_POST['type'] == 'real_order'){
			//正常订单
			$out_trade_no = trim($_POST['out_trade_no']);
			if(empty($out_trade_no)) exit(json_encode(array('stat'=>0,'msg'=>'请传递订单号')));
			$store_info = Model('order')->getOrderInfo(array('pay_sn'=>$out_trade_no),array(),'store_id');
			if(empty($store_info)) exit(json_encode(array('stat'=>0,'msg'=>'对应的订单号'.$out_trade_no.'没有找到订单信息')));
			exit(json_encode(array('stat'=>1,'data'=>$store_info['store_id'])));
		}elseif ($_POST['type'] == 'store_wxinfo'){
			//微信信息
			$store_id = intval($_POST['store_id']);
			if($store_id <=0) exit(json_encode(array('stat'=>0,'msg'=>'请传递店铺号')));
			$info  = Model()->table('store_wxinfo')->where(array('store_id'=>$store_id))->find();
			if(empty($info)) exit(json_encode(array('stat'=>0,'msg'=>'对应的'.$store_id.'的店铺没有配置微信支付信息')));
			$data = array(
					'STORE_ID'			=>$info['store_id'],
					'SITEURL'			=>$info['siteurl'],
					'APPID'				=>$info['appid'],
					'MCHID'				=>$info['mchid'],
					'KEY'				=>$info['mchid_key'],
					'APPSECRET'			=>$info['appsecret'],
					'JS_API_CALL_URL'	=>$info['js_api_call_url'],
					'NOTIFY_URL'		=>$info['notify_url'],
					'TOKEN'				=>$info['token'],
					'SSLCERT_PATH'		=>UPLOAD_SITE_URL.DS.$info['sslcert_path'],
					'SSLKEY_PATH'		=>UPLOAD_SITE_URL.DS.$info['sslkey_path']
			);
			exit(json_encode(array('stat'=>1,'data'=>$data)));
		}
		exit(json_encode(array('stat'=>0,'msg'=>'返回空')));
	}

}
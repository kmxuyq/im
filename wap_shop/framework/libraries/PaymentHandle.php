<?php 
/**
 * 支付成功数据表操作
 */
define('InShopNC',true);
//defined('InShopNC') or exit('Access Invalid!');
include_once $_SERVER["DOCUMENT_ROOT"].'/core/framework/libraries/SendCms.php';
include_once $_SERVER["DOCUMENT_ROOT"].'/wap_shop/framework/function/function.php';

class PaymentHandle{
	/**
	 * 支付成功处理
	 * @param $is_virtual是否是虚拟商品1是，0否
	 * @param  $pay_type 支付方式
	 * @param  $order_id 订单号
	 * @param  $mode 网银在线支付的银行
	 * @param  $amount 支付金额
	 * @param  $moneytype 币种
	 * @param  $remark1 网银在线订单备注1
	 * @param  $alipay_trade_no 支付宝交易号
	 * @param  $alipay_trade_status 支付宝交易状态，包括微信支付的其他返回信息，支付类型和支付结束时间
	 */
	public function pay_add($pay_type,$order_id,$mode,$amount,$moneytype,$remark1,$alipay_trade_no,$alipay_trade_status){
		//支付成功
		//商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......
		$order_id_arr=explode("-",$order_id);
		$order_id=$order_id_arr[0];
		//判断商品是否是虚拟商品,1为1，0为否,以调用不同的订单表
		$is_virtual='0';
		$is_virtual_table='';
		//普通商品订单号为pay_sn,虚拟商品（线路)为order_sn
		$where_sn=array("pay_sn"=>$order_id);//普通商品
		$order_sn_str="a.pay_sn='{$order_id}'";
		$is_virtual_count=Model()->table('order')->where(array('order_sn'=>$order_id))->count();//存在则为虚拟订单，线路
		if($is_virtual_count=='1'){//虚拟商品
			$is_virtual='1';
			$is_virtual_table='vr_';
			$where_sn=array("order_sn"=>$order_id);
			$order_sn_str="a.order_sn='{$order_id}'";
			
		}
		$order_res=Model()->query("select a.buyer_id,a.buyer_name,a.buyer_phone,a.order_amount,a.rcb_amount,a.pd_amount,b.member_email from ymjr_{$is_virtual_table}order a,ymjr_member b where a.buyer_id=b.member_id and {$order_sn_str}");
		$order_list=$order_res[0];
		$pay_log=Model('pay_log');
		$is_pay=$pay_log->where(array('oid'=>$order_id,'oid_number'=>$order_id_arr[1]))->count();
		if($is_pay==0){
			$time=time();	
			$pay_d["oid"]=$order_id;
			$pay_d["oid_number"]=$order_id_arr[1];
			$pay_d["type"]=$pay_type;
			$pay_d["mode"]=$mode;
			$pay_d["amount"]=$amount;
			$pay_d["moneytype"]=$moneytype;
			$pay_d["remark1"]=$remark1;
			$pay_d["alipay_trade_no"]=$alipay_trade_no;
			$pay_d["alipay_trade_status"]=$alipay_trade_status;
			$pay_d["d_time"]=$time;
			$pay_log->insert($pay_d);//插入支付日志表  只插入一次
			
			$order=Model()->table($is_virtual_table.'order');
			$order->where($where_sn)->setInc('rcb_amount',$amount);//更新金额   只更新一次
			
			$order_d["payment_code"]=$pay_type;
			$order_d["payment_time"]=$time;
			if(($order_list["rcb_amount"]+$order_list["pd_amount"])>=$order_list["order_amount"]){
				$order_d["order_state"]='30';
			}
			$order->where($where_sn)->update($order_d);//更新订单
			//----------------------------
			if(empty($is_virtual_table)){
				$myphone_res=Model()->table('address')->where(array("member_id"=>$_SESSION["member_id"]))->field('mob_phone')->order('is_default desc')->find();
				$myphone=$myphone_res['mob_phone'];
	
			}else{
				$myphone=$order_list["buyer_phone"];
			}
			
			$myemail=$order_list["member_email"];
			$uid=$order_list["buyer_id"];
			$is_login='1';
			$web_tel=$GLOBALS['setting_config']["site_tel400"];
			/*//$uid=$order_res["user_id"];
			 	
			if(isset($_SESSION[UID]))$is_login='1';
			//如果支付完成，赠送积分
			if($order_res["product_price"]==$order_res["pay_money"]){
			$score=round(C("order")["price_score"]*$order_res["product_price"],0);
			D('Score')->updateScore($uid, $score,0, '支付完成，订单赠送', $order_id);
			}//
			//交了费用未审核的新订单，更新消息提醒文件data.txt
			$ismessage_count=M('order')->where("id=%d and order_status=0",$order_res['id'])->count('id');
			if($ismessage_count>0)D('Message')->updata('order');//更新消息提醒
			*/
			//发送邮件
			//$email_status=$this->sendmail_order_payok($uid,$order_id,$amount,$is_login);
			//发送短信通知
			/* print_r($order_list);
			echo $_SESSION["member_id"]."<br/>";
			echo $is_virtual_table.'||'.$myphone.'--'.$order_list["buyer_name"].'--'.$order_id.'--'.$amount.'--'.$web_tel;  */
			log_result("\n".$myphone.'--'.$order_list["buyer_name"].'--'.$order_id.'--'.$amount.'--'.$web_tel."\n",'xml');
			$send_phone=new SendCms();
			$phone_status=$send_phone->send_order_payok($myphone, $order_list["buyer_name"], $order_id, $amount, $web_tel);
			return '1';
		}
	}

}
?>
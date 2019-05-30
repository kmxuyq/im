<?php
/**
 *
 *
 * @copyright  Copyright (c) 2007-2014 ShopNC Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.com
 * @link       http://www.shopwwi.com
 * @since      File available since Release v1.1
 */
defined('InShopNC') or exit('Access Invalid!');

class wxpay{

    private $pay_result;
    private $order_type ;
    private $payment;
    private $order;


    public function __construct($payment_info,$order_info){
    	$this->wxpay($payment_info,$order_info);
    }
    public function wxpay($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
    	}
    }
	/**
	 * 获取支付表单
	 *
	 * @param
	 * @return array
	 */
	public function get_payurl(){
			// echo '111111';
			// 将商品名称 ，商品价格(变为分)以get方式传到下面这个页面里面。。
			// 在下面页面里使用这两个变量
			// 然后生成二维码
			/*
		 * echo "<meta charset='utf-8'/><pre>"; print_r($this->order); echo
		 * "</pre>"; exit ;
		 */
		if ($this->order ['order_type'] == 'real_order') {
			if ($this->order['order_list']['0'] ['store_name']) {
				$body = $this->order['order_list']['0']['store_name'];
				echo "<script>alert('$body')</script>";
			} else {
				$body = "预存款充值";
			}
         if (!empty($this->order['vr_order_list']) and is_array($this->order['vr_order_list'])) {
            foreach ($this->order['vr_order_list'] as $key => $value) {
               $pay_amount += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];
            }
         }
         $pay_amount += intval($this->order['order_list']['0']['order_amount']);
		 $total_fee = ($pay_amount) * 100;
		 //店铺ID
		 $store_id  = $this->order['order_list']['0']['store_id'];
		} else { // 虚拟商品支付
			if ($this->order ['store_name']) {
				$body = $this->order ['store_name'];
				// $detail = $this->order['goods_name'];
				//店铺ID
				$store_id  = $this->order['store_id'];
			} else {
				$body = "预存款充值";
				$detail = $this->order ['goods_name'];
				//店铺ID 优先级  $_SESSION['store_member_info_ID']  》》 7 
				$store_id  = empty($_SESSION['store_member_info_ID'])?7:$_SESSION['store_member_info_ID'];
			}
			//$total_fee = ($this->order ['order_amount']) * 100;//订单总金额
			$total_fee = ($this->order ['api_pay_amount']) * 100;//应支付金额
		}
		$out_trade_no = $this->order ['pay_sn'];

		// print_r($this->order);
		// exit ;

		// 附加数据,这里设置为order_type,分为商品购买和预存款充值
		$attach = $this->order ['order_type'];

		// 请求的URL

		$reqUrl = "/wap_shop/index.php?act=weixinpay&op=index&body=$body&out_trade_no=$out_trade_no&total_fee=$total_fee&attach=$attach&store_id=$store_id";
		return $reqUrl;

	}

  /*
  *返回验证

  */


  public function return_verify(){
    //根据交易结果，为pay_result和order_type赋值，并返回true .
    if($_GET['result_code']=='SUCCESS')
    {
       $this->pay_result = true ;
       $this->order_type = $_GET['extra_common_param'] ;
       return true ;
    }else{
       return false ;
    }

	}

	/**
	 * 取得订单支付状态，成功或失败
	 *
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
	   return $this->pay_result;
	}


  public function __get($name){
	    return $this->$name;
	}

}

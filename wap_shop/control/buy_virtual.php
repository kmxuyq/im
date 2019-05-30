<?php
/**
 * 虚拟商品购买
 ***/

defined('InShopNC') or exit('Access Invalid!');
class buy_virtualControl extends BaseBuyControl {

   public function __construct() {
      parent::__construct();
      if (!$_SESSION['member_id']) {
         redirect('index.php?act=login&ref_url=' . urlencode(request_uri()));
      }
      //验证该会员是否禁止购买
      if (!$_SESSION['is_buy']) {
         showMessage(Language::get('cart_buy_noallow'), '', 'html', 'error');
      }
      Tpl::output('hidden_rtoolbar_cart', 1);
   }

   /**
    * 虚拟商品购买第一步
    */
   public function buy_step1Op() {
      //价格日历模型
      if(in_array(intval($_GET['calendar_type']),array(1,2,3)) && $_GET['rl_data']){
         $calendar_date = urldecode($_GET['rl_data']);
         $calendar_type = trim($_GET['calendar_type']);
         Tpl::output('calendar_array',$calendar_date);
      }
//      echo '<pre>';var_dump($calendar_date);exit;
      $logic_buy_virtual = Logic('buy_virtual');
      $result            = $logic_buy_virtual->getBuyStep1Data($_GET['goods_id'], $_GET['quantity'], $_SESSION['member_id'],$calendar_date,$calendar_type);
      if (!$result['state']) {
         showMessage($result['msg'], '', 'html', 'error');
      }
      if($result['data']['goods_info']['calendar_type']){//验证价格日历是否能购买
         if(!$result['data']['goods_info']['state']){
            showMessage($result['data']['goods_info']['msg'], '', 'html', 'error');
         }
      }
	  if(intval($_GET['calendar_type']) ==4){
		  $result['data']['goods_info']['calendar_type'] = $_GET['calendar_type'];
		  $result['data']['goods_info']['ticket_date'] = $_GET['date'];
	  }
      Tpl::output('goods_info', $result['data']['goods_info']);
      Tpl::output('store_info', $result['data']['store_info']);
      if(trim($_GET['calendar_type']) ==2){//酒店类型
         Tpl::showpage('buy_hotel_virtual_step1');
      }
      Tpl::showpage('buy_virtual_step1');
   }

   /**
    * 虚拟商品购买第二步
    */
   public function buy_step2Op() {
      $logic_buy_virtual = Logic('buy_virtual');
      $quantity = $_POST['quantity'] ? $_POST['quantity'] : $_POST['goods_num'];
      $get_info = json_decode(decrypt($_POST["get_info"]), true);
      $result   = $logic_buy_virtual->getBuyStep2Data($get_info['goods_id'], $quantity, $_SESSION['member_id']);
      if (!$result['state']){
         showMessage($result['msg'], '', 'html', 'error');
      }
      if(in_array(intval($_POST['calendar_type']),array(1,2,3))){//总价
         $goods_total = decrypt($_POST["price_total"]);//总价
         $result['data']['goods_info']['calendar_type'] =$_POST['calendar_type'];//价格类型
      }else{
         $goods_total=decrypt($_POST["goods_price"])*intval($result['data']['goods_info']['quantity']);//单价乘以数量
      }
      //处理会员信息
      $member_info                                 = array_merge($this->member_info, $result['data']['member_info']);

      $result['data']['goods_info']['goods_price'] = isset($_POST["goods_price"]) ? decrypt($_POST["goods_price"]) : $goods_total;//单价

      $result['data']['goods_info']['goods_total'] = $goods_total; //总价

      $result['data']['goods_info']['goods_name']  =  isset($_POST["goods_name"]) ? decrypt($_POST["goods_name"]) : $result['data']['goods_info']['goods_name'];
	  //车票
	  if(intval($_POST['calendar_type']) ==4){
		  $result['data']['goods_info']['calendar_type'] = $_POST['calendar_type'];
		  $result['data']['goods_info']['ticket_date'] = decrypt($_POST['ticket_date']);
	  }
      Tpl::output('goods_info', $result['data']['goods_info']);
      Tpl::output('store_info', $result['data']['store_info']);
      Tpl::output('member_info', $member_info);
      $buy_1_logic = Logic('buy_1');
      $store_voucher_list = $buy_1_logic->getStoreAvailableVoucherList([$result['data']['store_info']['store_id'] => $goods_total],  $_SESSION['member_id']);
      Tpl::output('store_voucher_list',  $store_voucher_list[$result['data']['store_info']['store_id']]);
      Tpl::showpage('buy_virtual_step2');
   }
   /**
    * 虚拟酒店商品使用预存款或充值卡购买
    */
   public  function  hotel_vr_payOp(){
      $logic_buy_virtual   = Logic('buy_virtual');
      $_POST['order_from'] = 1;
      if (1 == $_SESSION['share_shop']) {
         $_POST['is_share'] = 1;
      }
      $result = $logic_buy_virtual->online_pay($_POST, $_SESSION['member_id']);
      redirect('index.php?act=buy_virtual&op=pay&order_id=' . $result['data']['order_id']);
   }

   /**
    * 虚拟商品购买第三步
    */
   public function buy_step3Op() {
      $logic_buy_virtual   = Logic('buy_virtual');
      $_POST['order_from'] = 1;
      if (1 == $_SESSION['share_shop']) {
         $_POST['is_share'] = 1;
      }
      $result = $logic_buy_virtual->buyStep3($_POST, $_SESSION['member_id']);
      if (!$result['state']) {
         showMessage($result['msg'], 'index.php', 'html', 'error');
      }
         //转向到商城支付页面
      redirect('index.php?act=buy_virtual&op=pay&order_id=' . $result['data']['order_id']);
   }

   /**
    * 下单时支付页面
    */
   public function payOp() {
      $order_id = intval($_GET['order_id']);
      if ($order_id <= 0) {
         showMessage('该订单不存在', 'index.php?act=member_vr_order', 'html', 'error');
      }

      $model_vr_order = Model('vr_order');
      //取订单信息
      $condition             = array();
      $condition['order_id'] = $order_id;
      $order_info            = $model_vr_order->getOrderInfo($condition, '*', true);
      if (empty($order_info) || !in_array($order_info['order_state'], array(ORDER_STATE_NEW, ORDER_STATE_PAY))) {
         showMessage('未找到需要支付的订单', 'index.php?act=member_order', 'html', 'error');
      }

      //重新计算在线支付金额
      $pay_amount_online = 0;
      //订单总支付金额
      $pay_amount = 0;

      $payed_amount = floatval($order_info['rcb_amount']) + floatval($order_info['pd_amount']);//已经支付的金额

      //计算所需要支付金额
      $diff_pay_amount = ncPriceFormat(floatval($order_info['order_amount']) - $payed_amount);

      //显示支付方式与支付结果
      $order_info['pay_message'] = ''; //显示提示
      if ($payed_amount > 0) {
         $payed_tips = '';
         if (floatval($order_info['rcb_amount']) > 0) {
            $payed_tips = '充值卡已支付：￥' . $order_info['rcb_amount'];
         }
         if (floatval($order_info['pd_amount']) > 0) {
            $payed_tips .= ' 预存款已支付：￥' . $order_info['pd_amount'];
         }
         $order_info['pay_message'] = " ( {$payed_tips} )"; //支付提示
      }
      //$sum_price=round($order_info["goods_price"]*$order_info["goods_num"],2);
      $show_url = urlencode(WAP_SHOP_SITE_URL); //商品地址
      $wxpay_url="/wap_shop/index.php?act=weixinpay&op=buy_?jsapi&body={$order_info["goods_name"]}&out_trade_no={$order_info["order_sn"]}&total_fee={$diff_pay_amount}&attach=vr_order";
      $wxpay_jsapi_url           = "/wap_shop/api/payment/wxpay/js_api_call.php?body={$order_info["goods_name"]}&out_trade_no={$order_info["order_sn"]}&total_fee={$diff_pay_amount}&attach=vr_order";
      $alipay_api_url            = "/wap_shop/api/payment/wap_alipay/alipayapi.php?body={$_GET["goods_name"]}&out_trade_no={$order_info["order_sn"]}&total_fee=" . encrypt($diff_pay_amount) . "&show_url={$show_url}&attach=vr_order";
      $order_info["goods_price"] = $diff_pay_amount;
      Tpl::output('order_info', $order_info);
      //如果所需支付金额为0，转到支付成功页
      if (0 == $diff_pay_amount) {
         redirect('index.php?act=buy_virtual&op=pay_ok&order_sn=' . $order_info['order_sn'] . '&order_id=' . $order_info['order_id'] . '&order_amount=' . encrypt($order_info['order_amount'])); //ncPriceFormat
      }

      Tpl::output('diff_pay_amount', ncPriceFormat($diff_pay_amount));

      //显示支付接口列表
      $model_payment = Model('payment');
      $condition     = array();
      $payment_list  = $model_payment->getPaymentOpenList($condition);
      if (!empty($payment_list)) {
         unset($payment_list['predeposit']);
         unset($payment_list['offline']);
      }
      if (empty($payment_list)) {
         showMessage('暂未找到合适的支付方式', 'index.php?act=member_vr_order', 'html', 'error');
      }
      $order_info_arr = unserialize($order_info["order_info"]); //订单详细
      Tpl::output('order_info_arr', $order_info_arr);
      Tpl::output('payment_list', $payment_list);
      Tpl::output('wxpay_jsapi_url', $wxpay_jsapi_url);
      Tpl::output('alipay_api_url', $alipay_api_url);
      Tpl::output('wxpay_url', $wxpay_url);
      if($order_info['order_station_type']==2){//酒店类型
         Tpl::output('ticket_date', explode(" ",$order_info['ticket_date']));
         Tpl::showpage('buy_hotel_virtual_step2');
      }else{
         Tpl::showpage('buy_virtual_step3');
      }

   }

   /**
    * 支付成功页面
    */
   public function pay_okOp() {
      $order_sn = $_GET['order_sn'];//获取订单号
      if (!preg_match('/^\d{18}$/', $order_sn)) {
         showMessage('该订单不存在', 'index.php?act=member_vr_order', 'html', 'error');
      }
      $order_id = Model()->table('vr_order')->where(array("order_sn" => $order_sn))->field('order_id,store_id,is_share,order_amount')->find();
      $store_wx_info = Model('store_wxinfo')->where(['store_id' => $this->store_info['store_id']])->find();
     Tpl::output('store_wx_info', $store_wx_info);
      Tpl::output('share_shop',$order_id['is_share']);
	  Tpl::output('route_store_id', $order_id['store_id']);
	  Tpl::output('order_id', $order_id["order_id"]);
	  Tpl::output('order_amount', $order_id["order_amount"]);
      Tpl::showpage('buy_virtual_step4');
   }
   /**
    * 如果是高尔夫判断商品是否已支付，如果有预订的场次已支付，则输出提示，否则输出为空（可以支付）
    * 参数：commonid商品主ID,date日期年月日，golf_minute商尔夫小时和分钟的场次11点(30分40分),12点(30分40分),13点(),
    */
   public function get_golf_minute_stock_statusOp() {
      //print_r($_GET);
      $commonid    = $_GET["commonid"];
      $date        = $_GET["date"];
      $golf_minute = $_GET["golf_minute"];
      $message     = Model('golf_stock')->get_golf_stock_status($commonid, $date, $golf_minute);
      echo $message;
   }
   /**
    * 获取商品价格及所订商品名称（订单名称）
    * @param unknown_type $get
    */
   private function get_goods_info($get) {
      $_GET = $get;
      if (!empty($_GET["type_id"])) {
         $type_id  = $_GET["type_id"]; //商品大类
         $commonid = $_GET["commonid"];
         $package  = $_GET["package"];

         $date         = $_GET["date"];
         $quantity     = $_GET["quantity"];
         $show_package = $_GET["show_package"]; //如果套餐只有一个而且和商品名一致，那么名称中不能有套餐名，值为no则为套餐名和商品名一致
         if ('40' == $type_id || '41' == $type_id || '42' == $type_id || '43' == $type_id) {
            //线路和门票

            $stock_info = Model()->table('stock')->where(array('commonid' => $commonid, 'package' => $package, 'date' => substr($date, 0, 7)))->field('stock_info')->find();
            $price_arr  = json_decode($stock_info["stock_info"], true);
            //成人，成人票；儿童，儿童票
            if (strpos($_GET["man_type"], '成') !== false) {
               $man_type = 'man_price';
            } elseif (strpos($_GET["man_type"], '儿') !== false) {
               $man_type = 'child_price';
            }
            //$man_type=str_replace(array('成人','儿童'),array('man_price','child_price'),$_GET["man_type"]);
            $day            = intval(substr($date, -2));
            $price          = $price_arr[$day][$man_type];
            $goods_total    = round($price * $quantity, 2);
            $goods_name_res = Model()->table('goods_common')->where(array('goods_commonid' => $commonid))->field('goods_name')->find();
            if ('no' == $show_package) {
               $package = '';
            }

            $goods_name = $goods_name_res["goods_name"] . '&nbsp; ' . $package . '&nbsp; ' . $_GET["man_type"] . '&nbsp; ' . $_GET["date"];
         } elseif ('46' == $type_id) {
            //高尔夫
            //$golf_hour=$_GET["golf_hour"];//小时段
            $golf_minute     = substr($_GET["golf_minute"], 0, -1); //分钟段
            $golf_minute_arr = explode(',', $golf_minute);
            $stock_info      = Model()->table('golf_stock')->where(array('commonid' => $commonid, 'date' => $date))->field('stock_info')->find();
            $stock_info_arr  = unserialize($stock_info["stock_info"]);
            foreach ($golf_minute_arr as $v) {
               $hour_arr   = explode('点', $v);
               $hour       = $hour_arr[0];
               $minute_str = str_replace(array('(', ')'), array('', ''), $hour_arr[1]);
               $minute_arr = explode('分', $minute_str);
               foreach ($minute_arr as $v2) {
                  if ('' != $v2) {
                     //echo $stock_info_arr.'-'.$hour.'-'.$v2.'-'.price.'-'.$package.'<br/>';
                     $price_arr[] = $stock_info_arr[$hour][$v2]['price'][$package];
                  }
               }
            }
            $price = $price_arr[0];
            //计算新增加的人数价格
            $golf_minute_price = 0;
            $golf_new_man      = ''; //新加的人数
            if ($_GET["golf_num"] > 0) {
               $golf_num          = $_GET["golf_num"]; //每场在套餐外新增的人数
               $data              = Model()->table('golf_price')->where(array('commonid' => $commonid, 'package' => $package))->field('price')->find();
               $golf_price_arr    = explode('|', $data["price"]);
               $golf_munite_num   = $_GET["golf_minute_num"]; //选择的分钟场数
               $golf_minute_price = $golf_price_arr[2] * $golf_munite_num * $golf_num;
               $golf_new_man      = '+' . $golf_num . '人';
            }
            $goods_total    = (array_sum($price_arr)) + $golf_minute_price;
            $goods_name_res = Model()->table('goods_common')->where(array('goods_commonid' => $commonid))->field('goods_name')->find();
            if ('no' == $show_package) {
               $package = '';
            }

            $goods_name = $goods_name_res["goods_name"] . '&nbsp; ' . $package . $golf_new_man . '&nbsp; ' . $_GET["man_type"] . '&nbsp; ' . $date . '&nbsp; ' . $golf_minute;
            //print_r($price_arr);
            //echo $price.'--'.$goods_name;exit();
         }
         $data['goods_price'] = $price;
         $data['goods_total'] = $goods_total;
         $data['goods_name']  = $goods_name;
         return $data;
      }
   }
}

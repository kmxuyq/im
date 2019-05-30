<?php
/**
 * 购买流程
 ***/

defined('InShopNC') or exit('Access Invalid!');
class buyControl extends BaseBuyControl {

   public function __construct() {
      parent::__construct();

      Language::read('home_cart_index');
      if (!$_SESSION['member_id']) {
         redirect('index.php?act=login&ref_url=' . urlencode(request_uri()));
      }
      //验证该会员是否禁止购买
      if (!$_SESSION['is_buy']) {
         showMessage(Language::get('cart_buy_noallow'), '', 'html', 'error');
      }
      Tpl::output('hidden_rtoolbar_cart', 1);
   }
   public function indexOp() {

   }
   public function activeOP() {
      Tpl::showpage('act_address');
   }

   /**
    * 实物商品 购物车、直接购买第一步:选择收货地址和配送方式
    */
   public function buy_step1Op() {
      //虚拟商品购买分流
      $return_url = "/wap_shop/?act=buy&op=buy_step1&data=" . base64_encode(json_encode($_POST));
      if (empty($_GET["data"])) {
         redirect($return_url);
      } else {
         $_POST = json_decode(base64_decode($_GET["data"]), true);
      }

      //价格日历
      if(!empty($_POST['calendar_date'])){
         $calendar_type    = Model('goods')->getGoodCommonsList(array('goods_commonid' => trim($_POST['tb_commonid']),'calendar_type'));
         $calendar_state =  $calendar_type['calendar_type'];

         switch($calendar_state){
            case 1:
               if($_POST['is_cx']=="is_cx"){//限时折扣
                  //普通价格日历 "时间,'xs_price',数量,套餐名称"
                  $calendar_array =explode(',',$_POST['calendar_date']);
               }else{
                  //普通价格日历 "2016-10-31,-1,250,'套餐名称',50"  -1代表没选择
                  $calendar_array =explode(',',$_POST['calendar_date']);
                  if(count($calendar_array)  ==5 && !empty($calendar_array[0]) ){
                     if(strtotime($calendar_array[0]) >= strtotime(date('y-m-d'))){
                        if( $calendar_array[1]==-1 && $calendar_array[2]==-1 && $calendar_array[4] ==-1){
                           showMessage('请选择票规格', '', 'html', 'error');
                        }
                     }else{
                        $msg = '请选择'.$calendar_array[0].'之后的日期';
                        showMessage($msg, '', 'html', 'error');
                     }
                  }else{
                     showMessage('请选择票出发时间和票规格', '', 'html', 'error');
                  }
               }

               break;
            case 2://酒店价格日历参数 "2016-11-01,2016-11-02,1,300,'大床房'"
               $calendar_array =explode(',',$_POST['calendar_date']);
               $start_time  = strtotime($calendar_array[0]); //开始入住时间
               $end_time    = strtotime($calendar_array[1]); //离店时间
               $hotel_num   = $calendar_array[2]; //入住晚数
               $hotel_price = $calendar_array[3]; //单价
               $hotel_spec_name = $calendar_array[4];//规格值
                  if($end_time > $start_time){
                     $num= ($end_time - $start_time)/86400;
                     if( $num != $hotel_num){
                        showMessage('时间有误，请重新选择入住期间', '', 'html', 'error');
                     }
                  }else{
                     showMessage('时间有误，请重新选择入住期间', '', 'html', 'error');
                  }

               break;
            case 3://高尔夫球类型 2016-11-01 18:00
                   if(strlen($_POST['calendar_date'])>= 15 || (strlen($_POST['calendar_date'])<=16)){
                      $calendar_array =explode(' ',$_POST['calendar_date']);
                      if(strtotime($calendar_array[0]) < strtotime(date('y-m-d'))){
                         $msg = '请选择'.$calendar_array[0].'之后的日期';
                         showMessage($msg, '', 'html', 'error');
                      }
                   }else{
                      showMessage('请重新选择预定日期-时段-分钟段', '', 'html', 'error');
                   }
               break;
         }
      }
	//车票库存后端验证
	if($_POST['calendar_type'] == 4){
       if(!isset($_POST['date']) || empty($_POST['date'])){
          showMessage('发车时间错误，请重新选择', '', 'html', 'error');
       }
		$cart_arr = explode('|',$_POST['cart_id'][0]);
		$data = Model()->table('cart_operate_date')->where("goods_id = {$cart_arr[0]} AND date = '{$_POST['date']}'")->find();
       if((intval($data['storage']) - $cart_arr[1]) < 0){
			showMessage('库存不足', '', 'html', 'error');
		}
	}
      //判断是否是微信打开，用户的收货地址为空，微信端的收货地址不为空，则载入微信端的收货地址
      if ($this->is_wx_pro()) {
         $condition              = array();
         $condition['member_id'] = $_SESSION['member_id'];
         if (!C('delivery_isuse')) {
            $condition['dlyp_id'] = 0;
            $order                = 'dlyp_id asc,address_id desc';
         }
         $model_addr = Model('address');
         $addr_list  = $model_addr->getAddressList($condition, $order);

         if (empty($addr_list)) {
         	//对应的store_id的优先级 $_POST['store_id'] 》》  $_SESSION['store_id'] 》》 7 
         	$store_id = empty($_POST['store_id'])?(empty($_SESSION['store_id'])?7:$_SESSION['store_id']):$_POST['store_id'];
            $wx_addr = $this->get_wx_addr($return_url,intval($store_id));
            Tpl::output('wx_addr', $wx_addr);
         }
      }

      $this->_buy_branch($_POST);

      //得到购买数据
      $logic_buy = Logic('buy');

      if (!empty($_POST["tb_goods_id"])) {
         $count = '0';
         /* foreach($_POST["cart_id"] as $v){
         $cart_id_str.='|'.$v;
         }
         if(strpos($cart_id_str, $_POST["tb_goods_id"])){
         $count=count($_POST["cart_id"])+1;
         } */
         $cart_id_arr  = explode('|', $_POST["cart_id"][$count]);
         $cart_id_arr2 = array($_POST["tb_goods_id"] . '|' . $cart_id_arr[1]);
      } else {
         $cart_id_arr2 = $_POST["cart_id"];
      }
      //得到页面所需要数据：收货地址、发票、代金券、预存款、商品列表等信息
      $result = $logic_buy->buyStep1($cart_id_arr2, $_POST['ifcart'], $_SESSION['member_id'], $_SESSION['store_id'],$_POST['calendar_date']);

      if (!$result['state']) {
         showMessage($result['msg'], '', 'html', 'error');
      } else {
         $result = $result['data'];
      }

      /*
      if ($_POST['ifcart'] and 1 == count($result['store_cart_list'])) {
         foreach($result['store_cart_list'] as $store_id => $goods_list) {
            if (1 == count($goods_list) and 1 == $goods_list[0]['is_virtual']) {
               $params = [
                  'act' => 'buy_virtual',
                  'op' => 'buy_step1',
                  'goods_id' => $goods_list[0]['goods_id'],
                  'quantity' => $goods_list[0]['goods_num'],
                  'buynum' => $goods_list[0]['goods_num'],
                  'commonid' => $goods_list[0]['goods_commonid'],
                  'rl_data' => base64_encode($goods_list[0]['calendar_date']),
                  'calendar_type' => $goods_list[0]['calendar_type'],
               ];
               QueueClient::push('delCart', array('buyer_id' => $_SESSION['member_id'], 'cart_ids' => array($goods_list[0]['cart_id'])));
               redirect('/wap_shop/index.php?' . http_build_query($params));
            }
            break;
         }
      }
      */
      $has_virtual = false;
      if ($_POST['ifcart']) {
         foreach ($result['store_cart_list'] as $store_id => $goods_list) {
            foreach ($goods_list as $item) {
               if (1 == $item['is_virtual']) {
                  $has_virtual = true;
                  break;
               }
            }
         }
      }
      Tpl::output('has_virtual', $has_virtual);
      //商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
      Tpl::output('store_cart_list', $result['store_cart_list']);
      Tpl::output('store_goods_total', $result['store_goods_total']);

      //取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
      Tpl::output('store_premiums_list', $result['store_premiums_list']);
      Tpl::output('store_mansong_rule_list', $result['store_mansong_rule_list']);

      //返回店铺可用的代金券
      Tpl::output('store_voucher_list', $result['store_voucher_list']);

      //返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
      Tpl::output('need_calc_sid_list', $result['need_calc_sid_list']);
      Tpl::output('cancel_calc_sid_list', $result['cancel_calc_sid_list']);

      //将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
      Tpl::output('freight_hash', $result['freight_list']);

      //输出用户默认收货地址
      Tpl::output('address_info', $result['address_info']);

      //输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
      Tpl::output('pay_goods_list', $result['pay_goods_list']);
      Tpl::output('ifshow_offpay', $result['ifshow_offpay']);
      Tpl::output('deny_edit_payment', $result['deny_edit_payment']);

      //不提供增值税发票时抛出true(模板使用)
      Tpl::output('vat_deny', $result['vat_deny']);

      //增值税发票哈希值(php验证使用)
      Tpl::output('vat_hash', $result['vat_hash']);

      //输出默认使用的发票信息
      Tpl::output('inv_info', $result['inv_info']);

      //显示预存款、支付密码、充值卡
      Tpl::output('available_pd_amount', $result['available_predeposit']);
      Tpl::output('member_paypwd', $result['member_paypwd']);
      Tpl::output('available_rcb_amount', $result['available_rc_balance']);

      //删除购物车无效商品
      $logic_buy->delCart($_POST['ifcart'], $_SESSION['member_id'], $_POST['invalid_cart']);

      //标识购买流程执行步骤
      Tpl::output('buy_step', 'step2');

      Tpl::output('ifcart', $_POST['ifcart']);
      //是否是分销商品标识
      Tpl::output('is_share', $_POST['is_share']);

      //店铺信息
      $store_list = Model('store')->getStoreMemberIDList(array_keys($result['store_cart_list']));
      Tpl::output('store_list', $store_list);

      Tpl::showpage('buy_step1');
   }

   /**
    * 生成订单
    *
    */
   public function buy_step2Op() {
      $logic_buy = logic('buy');
      $result = $logic_buy->buyStep2($_POST, $_SESSION['member_id'], $_SESSION['member_name'], $_SESSION['member_email']);
      if (!$result['state']) {
         showMessage($result['msg'], 'index.php?act=cart', 'html', 'error');
      }
      //转向到商城支付页面
      $goods_name = urlencode($_POST['goods_name']);
      redirect('index.php?act=buy&op=pay&pay_sn=' . $result['data']['pay_sn'] . '&goods_name=' . $goods_name);
   }

   /**
    * 下单时支付页面
    */
   public function payOp() {
      $pay_sn = $_GET['pay_sn'];
      if (!preg_match('/^\d{18}$/', $pay_sn)) {
         showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
      }
      Tpl::output('pay_sn', $pay_sn);
      //查询支付单信息
      $model_order = Model('order');
      $pay_info    = $model_order->getOrderPayInfo(array('pay_sn' => $pay_sn, 'buyer_id' => $_SESSION['member_id']), true);

      $model_vr_order = Model()->table('vr_order');
      $condition             = array();
      $condition['pay_sn']   = $pay_sn;
      $vr_order_list         = $model_vr_order->where($condition)->select();
      if (empty($pay_info) and empty($vr_order_list)) {
         showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
      }
      Tpl::output('pay_info', $pay_info);
      if (!empty($pay_info)) {
         //取子订单列表
         $condition                = array();
         $condition['pay_sn']      = $pay_sn;
         $condition['order_state'] = array('in', array(ORDER_STATE_NEW, ORDER_STATE_PAY));
         $order_list               = $model_order->getOrderList($condition, '', 'order_id,order_state,payment_code,order_amount,rcb_amount,pd_amount,order_sn', '', '', array(), true);
         if (empty($order_list) and empty($vr_order_list)) {
            showMessage('未找到需要支付的订单', 'index.php?act=member_order', 'html', 'error');
         }
      }

      //重新计算在线支付金额
      $pay_amount_online  = 0;
      $pay_amount_offline = 0;
      //订单总支付金额(不包含货到付款)
      $pay_amount = 0;
      $payed_amount = 0;

      foreach ($vr_order_list as $key => $val) {
         $payed_amount += floatval($val['rcb_amount']);
         $pay_amount += floatval($val['order_amount']);
         $pay_amount_online += floatval($val['order_amount']) - floatval($payed_amount);
         $vr_order_list[$key]['payment_state'] = '在线支付';
      }

      Tpl::output('vr_order_list', $vr_order_list);
      foreach ($order_list as $key => $order_info) {

         $payed_amount += floatval($order_info['rcb_amount']) + floatval($order_info['pd_amount']);
         //计算相关支付金额
         if ('offline' != $order_info['payment_code']) {
            if (ORDER_STATE_NEW == $order_info['order_state']) {
               $pay_amount_online += ncPriceFormat(floatval($order_info['order_amount']) - $payed_amount);
            }
            $pay_amount += floatval($order_info['order_amount']);
         } else {
            $pay_amount_offline += floatval($order_info['order_amount']);
         }

         //显示支付方式与支付结果
         if ('offline' == $order_info['payment_code']) {
            $order_list[$key]['payment_state'] = '货到付款';
         } else {
            $order_list[$key]['payment_state'] = '在线支付';
            if ($payed_amount > 0) {
               $payed_tips = '';
               if (floatval($order_info['rcb_amount']) > 0) {
                  $payed_tips = '充值卡已支付：￥' . $order_info['rcb_amount'];
               }
               if (floatval($order_info['pd_amount']) > 0) {
                  $payed_tips .= ' 预存款已支付：￥' . $order_info['pd_amount'];
               }
               $order_list[$key]['order_amount'] .= " ( {$payed_tips} )";
            }
         }
      }
      Tpl::output('order_list', $order_list);
      //商品名称
      if (!empty($_GET['goods_name']) && intval($_GET['goods_id'])) {
         $goods_name = $_GET['goods_name'];
      } else {
         if (!empty($vr_order_list)) {
            $goods_name = $vr_order_list[0]['goods_name'];
         } else {
            $goods_name_res = Model()->table('order_goods')->where(array('order_id' => $order_list[0]['order_id']))->field('goods_name,goods_id')->find();
            $goods_name     = $goods_name_res['goods_name'];
         }
      }
      $sum_price       = $pay_amount_online;
      $show_url        = urlencode(WAP_SHOP_SITE_URL); //商品地址
      $_goods_name     = urlencode($goods_name);
      $wxpay_jsapi_url = "/wap_shop/api/payment/wxpay/js_api_call.php?body={$_goods_name}&out_trade_no={$pay_sn}&total_fee={$sum_price}&attach=real_order";
      $alipay_api_url  = "/wap_shop/api/payment/wap_alipay/alipayapi.php?body={$goods_name}&out_trade_no={$pay_sn}&total_fee=" . encrypt($sum_price) . "&show_url={$show_url}&attach=real_order";
      //如果线上线下支付金额都为0，转到支付成功页
      if (empty($pay_amount_online) && empty($pay_amount_offline)) {
         redirect('index.php?act=buy&op=pay_ok&pay_sn=' . $pay_sn . '&pay_amount=' . encrypt($pay_amount));
      }

      //输出订单描述
      if (empty($pay_amount_online)) {
         $order_remind = '下单成功，我们会尽快为您发货，请保持电话畅通！';
      } elseif (empty($pay_amount_offline)) {
         $order_remind = '请您及时付款，以便订单尽快处理！';
      } else {
         $order_remind = '部分商品需要在线支付，请尽快付款！';
      }
      Tpl::output('order_remind', $order_remind);
      Tpl::output('pay_amount_online', ncPriceFormat($pay_amount_online));
      Tpl::output('pd_amount', ncPriceFormat($pd_amount));

      //显示支付接口列表
      if ($pay_amount_online > 0) {
         $model_payment = Model('payment');
         $condition     = array();
         $payment_list  = $model_payment->getPaymentOpenList($condition);
         if (!empty($payment_list)) {
            unset($payment_list['predeposit']);
            unset($payment_list['offline']);
         }
         if (empty($payment_list)) {
            showMessage('暂未找到合适的支付方式', 'index.php?act=member_order', 'html', 'error');
         }
         Tpl::output('payment_list', $payment_list);
      }

      //标识 购买流程执行第几步
      Tpl::output('wxpay_jsapi_url', $wxpay_jsapi_url);
      Tpl::output('alipay_api_url', $alipay_api_url);
      Tpl::output('buy_step', 'step3');
      //Tpl::output('goods_name', $goods_name);
      Tpl::output('goods', $goods_name_res);
      Tpl::showpage('buy_step2');
   }

   /**
    * 预存款充值下单时支付页面
    */
   public function pd_payOp() {
      $pay_sn = $_GET['pay_sn'];
      if (!preg_match('/^\d{18}$/', $pay_sn)) {
         showMessage(Language::get('para_error'), 'index.php?act=predeposit', 'html', 'error');
      }

      //查询支付单信息
      $model_order = Model('predeposit');
      $pd_info     = $model_order->getPdRechargeInfo(array('pdr_sn' => $pay_sn, 'pdr_member_id' => $_SESSION['member_id']));
      if (empty($pd_info)) {
         showMessage(Language::get('para_error'), '', 'html', 'error');
      }
      if (intval($pd_info['pdr_payment_state'])) {
         showMessage('您的订单已经支付，请勿重复支付', 'index.php?act=predeposit', 'html', 'error');
      }
      Tpl::output('pdr_info', $pd_info);

      //显示支付接口列表
      $model_payment              = Model('payment');
      $condition                  = array();
      $condition['payment_code']  = array('not in', array('offline', 'predeposit'));
      $condition['payment_state'] = 1;
      $payment_list               = $model_payment->getPaymentList($condition);
      if (empty($payment_list)) {
         showMessage('暂未找到合适的支付方式', 'index.php?act=predeposit&op=index', 'html', 'error');
      }
      //-------------------
      $sum_price       = $pd_info["pdr_amount"];
      $show_url        = urlencode(WAP_SHOP_SITE_URL); //商品地址
      $goods_name      = '预存款充值';
      $wxpay_jsapi_url = "/wap_shop/api/payment/wxpay/js_api_call.php?body={$goods_name}&out_trade_no={$pay_sn}&total_fee={$sum_price}&attach=pd_order";
      $alipay_api_url  = "/wap_shop/api/payment/wap_alipay/alipayapi.php?body={$goods_name}&out_trade_no={$pay_sn}&total_fee=" . encrypt($sum_price) . "&show_url={$show_url}&attach=pd_order";
      Tpl::output('payment_list', $payment_list);
      Tpl::output('wxpay_jsapi_url', $wxpay_jsapi_url);
      Tpl::output('alipay_api_url', $alipay_api_url);
      //标识 购买流程执行第几步
      Tpl::output('buy_step', 'step3');
      Tpl::output('buy_step', 'step3');
      Tpl::showpage('predeposit_pay');
   }

   /**
    * 支付成功页面
    */
   public function pay_okOp() {
      //微信JSAPIPAY支付返回的地址
      if ('' != $_GET["source"] && 'weixin_jsapi' == $_GET["source"]) {
         if (isset($_SESSION["member_id"])) {
            $member_id  = $_SESSION["member_id"];
            $pay_sn_res = Model()->table('order')->where("buyer_id={$member_id} and rcb_amount>0")->field('pay_sn')->order('order_id desc')->find();
            $pay_sn     = $pay_sn_res["pay_sn"];
            $is_pay_res = Model()->table('pay_log')->where(array('oid' => $pay_sn))->field('remark1,amount')->order('id desc')->find();
            if (!empty($is_pay_res)) {
               $pay_amount = $is_pay_res["amount"];
               //如果是预存款支付
               if ('pd_order' == $is_pay_res["remark1"]) {
                  $url = "/wap_shop/index.php?act=predeposit&op=pd_log_list";
                  redirect($url);
               }
            }
         }
      } else {
         if (!empty($_GET['order_sn'])) {
            $pay_sn = $_GET['order_sn'];
         } else {
            $pay_sn = $_GET['pay_sn'];
         }
         $pay_amount = decrypt($_GET["pay_amount"]);
      }

      if (!preg_match('/^\d{18}$/', $pay_sn)) {
         showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
      }

      //查询支付单信息
      $model_order = Model('order');
      $pay_info    = $model_order->getOrderPayInfo(array('pay_sn' => $pay_sn, 'buyer_id' => $_SESSION['member_id']));
      if (empty($pay_info)) {
         showMessage(Language::get('cart_order_pay_not_exists'), 'index.php?act=member_order', 'html', 'error');
      }
      $order_id = Model()->table('order')->where(array("pay_sn" => $pay_sn))->field('order_id,store_id,is_share')->find();
      $store_wx_info = Model('store_wxinfo')->where(['store_id' => $this->store_info['store_id']])->find();
     Tpl::output('store_wx_info', $store_wx_info);
     
       Tpl::output('share_shop',$order_id['is_share']);
	  Tpl::output('route_store_id', $order_id['store_id']);
	  Tpl::output('order_id', $order_id["order_id"]);
      Tpl::output('pay_sn', $pay_sn);
      Tpl::output('pay_amount', $pay_amount);
      // Tpl::output('pay_info',$pay_info);
      Tpl::output('buy_step', 'step4');
      Tpl::showpage('buy_step3');
   }

   /**
    * 加载买家收货地址
    *
    */
   public function load_addrOp() {
      $model_addr = Model('address');
      //如果传入ID，先删除再查询
      if (!empty($_GET['id']) && intval($_GET['id']) > 0) {
         $model_addr->delAddress(array('address_id' => intval($_GET['id']), 'member_id' => $_SESSION['member_id']));
      }
      $condition              = array();
      $condition['member_id'] = $_SESSION['member_id'];
      if (!C('delivery_isuse')) {
         $condition['dlyp_id'] = 0;
         $order                = 'dlyp_id asc,address_id desc';
      }
      $list = $model_addr->getAddressList($condition, $order);
      Tpl::output('address_list', $list);
      Tpl::showpage('buy_address.load', 'null_layout');
   }

   /**
    * 选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
    * 如果店铺统一设置了满免运费规则，则运费模板无效
    * 如果店铺未设置满免规则，且使用运费模板，按运费模板计算，如果其中有商品使用相同的运费模板，则两种商品数量相加后再应用该运费模板计算（即作为一种商品算运费）
    * 如果未找到运费模板，按免运费处理
    * 如果没有使用运费模板，商品运费按快递价格计算，运费不随购买数量增加
    */
   public function change_addrOp() {
      $logic_buy = Logic('buy');

      $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $_SESSION['member_id']);
      if (!empty($data)) {
         exit(json_encode($data));
      } else {
         exit();
      }
   }

   /**
    * 添加新的收货地址
    *
    */
   public function add_addrOp() {
      $model_addr = Model('address');
      if (chksubmit()) {
         //验证表单信息
         $obj_validate                = new Validate();
         $obj_validate->validateparam = array(
            array("input" => $_POST["true_name"], "require" => "true", "message" => Language::get('cart_step1_input_receiver')),
            array("input" => $_POST["area_id"], "require" => "true", "validator" => "Number", "message" => Language::get('cart_step1_choose_area')),
            array("input" => $_POST["address"], "require" => "true", "message" => Language::get('cart_step1_input_address')),
         );
         $error = $obj_validate->validate();
         if ('' != $error) {
            $error = strtoupper(CHARSET) == 'GBK' ? Language::getUTF8($error) : $error;
            exit(json_encode(array('state' => false, 'msg' => $error)));
         }
         $data              = array();
         $data['member_id'] = $_SESSION['member_id'];
         $data['true_name'] = $_POST['true_name'];
         $data['area_id']   = intval($_POST['area_id']);
         $data['city_id']   = intval($_POST['city_id']);
         $data['area_info'] = $_POST['area_info'];
         $data['address']   = $_POST['address'];
         $data['tel_phone'] = $_POST['tel_phone'];
         $data['mob_phone'] = $_POST['mob_phone'];
         //转码
         $data = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
         $id   = intval($_POST['id']);
         if (!$id) {
            $insert_id = $model_addr->addAddress($data);
         } else {
            $model_addr->where(array('address_id' => $id, 'member_id' => $_SESSION['member_id']))->update($data);
            $insert_id = $id;
         }
         if ($insert_id) {
            exit(json_encode(array('state' => true, 'addr_id' => $insert_id)));
         } else {
            exit(json_encode(array('state' => false, 'msg' => Language::get('cart_step1_addaddress_fail', 'UTF-8'))));
         }
      } else {
         $id        = intval($_GET['id']);
         $addr_info = array();
         if ($id) {
            $addr_info = $model_addr->where(array('address_id' => intval($_GET['id']), 'member_id' => $_SESSION['member_id']))->find();
         }
         Tpl::output('addr_info', $addr_info);
         Tpl::showpage('buy_address.add', 'null_layout');
      }
   }

   /**
    * 加载买家发票列表，最多显示10条
    *
    */
   public function load_invOp() {
      $logic_buy = Logic('buy');

      $condition = array();
      if ($logic_buy->buyDecrypt($_GET['vat_hash'], $_SESSION['member_id']) == 'allow_vat') {
      } else {
         Tpl::output('vat_deny', true);
         $condition['inv_state'] = 1;
      }
      $condition['member_id'] = $_SESSION['member_id'];

      $model_inv = Model('invoice');
      //如果传入ID，先删除再查询
      if (intval($_GET['del_id']) > 0) {
         $model_inv->delInv(array('inv_id' => intval($_GET['del_id']), 'member_id' => $_SESSION['member_id']));
      }
      $list = $model_inv->getInvList($condition, 10);
      if (!empty($list)) {
         foreach ($list as $key => $value) {
            if (1 == $value['inv_state']) {
               $list[$key]['content'] = '普通发票' . ' ' . $value['inv_title'] . ' ' . $value['inv_content'];
            } else {
               $list[$key]['content'] = '增值税发票' . ' ' . $value['inv_company'] . ' ' . $value['inv_code'] . ' ' . $value['inv_reg_addr'];
            }
         }
      }
      Tpl::output('inv_list', $list);
      Tpl::showpage('buy_invoice.load', 'null_layout');
   }

   /**
    * 新增发票信息
    *
    */
   public function add_invOp() {
      $model_inv = Model('invoice');
      if (chksubmit()) {
         //如果是增值税发票验证表单信息
         if (2 == $_POST['invoice_type']) {
            if (empty($_POST['inv_company']) || empty($_POST['inv_code']) || empty($_POST['inv_reg_addr'])) {
               exit(json_encode(array('state' => false, 'msg' => Language::get('nc_common_save_fail', 'UTF-8'))));
            }
         }
         $data = array();
         if (1 == $_POST['invoice_type']) {
            $data['inv_state']   = 1;
            $data['inv_title']   = 'person' == $_POST['inv_title_select'] ? '个人' : $_POST['inv_title'];
            $data['inv_content'] = $_POST['inv_content'];
         } else {
            $data['inv_state']        = 2;
            $data['inv_company']      = $_POST['inv_company'];
            $data['inv_code']         = $_POST['inv_code'];
            $data['inv_reg_addr']     = $_POST['inv_reg_addr'];
            $data['inv_reg_phone']    = $_POST['inv_reg_phone'];
            $data['inv_reg_bname']    = $_POST['inv_reg_bname'];
            $data['inv_reg_baccount'] = $_POST['inv_reg_baccount'];
            $data['inv_rec_name']     = $_POST['inv_rec_name'];
            $data['inv_rec_mobphone'] = $_POST['inv_rec_mobphone'];
            $data['inv_rec_province'] = $_POST['area_info'];
            $data['inv_goto_addr']    = $_POST['inv_goto_addr'];
         }
         $data['member_id'] = $_SESSION['member_id'];
         //转码
         $data      = strtoupper(CHARSET) == 'GBK' ? Language::getGBK($data) : $data;
         $insert_id = $model_inv->addInv($data);
         if ($insert_id) {
            exit(json_encode(array('state' => 'success', 'id' => $insert_id)));
         } else {
            exit(json_encode(array('state' => 'fail', 'msg' => Language::get('nc_common_save_fail', 'UTF-8'))));
         }
      } else {
         Tpl::showpage('buy_address.add', 'null_layout');
      }
   }

   /**
    * AJAX验证支付密码
    */
   public function check_pd_pwdOp() {
      if (empty($_GET['password'])) {
         exit('0');
      }

      $buyer_info = Model('member')->getMemberInfoByID($_SESSION['member_id'], 'member_paypwd');
      echo ('' != $buyer_info['member_paypwd'] && md5($_GET['password']) === $buyer_info['member_paypwd']) ? '1' : '0';
   }

   /**
    * F码验证
    */
   public function check_fcodeOp() {
      $result = logic('buy')->checkFcode($_GET['goods_commonid'], $_GET['fcode']);
      echo json_encode($result);
      exit;
   }

   /**
    * 得到所购买的id和数量
    *
    */
   private function _parseItems($cart_id) {
      //存放所购商品ID和数量组成的键值对
      $buy_items = array();
      if (is_array($cart_id)) {
         foreach ($cart_id as $value) {
            if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
               $buy_items[$match[1][0]] = $match[2][0];
            }
         }
      }
      return $buy_items;
   }

   /**
    * 购买分流
    */
   private function _buy_branch($post) {
      if (!$post['ifcart']) {//虚拟没有购物车
         //取得购买商品信息
         $buy_items = $this->_parseItems($post['cart_id']);
         $goods_id  = key($buy_items);
         $quantity  = current($buy_items);
         $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
         if ($goods_info['is_virtual']){//是否是虚拟商品
            $date    = date('Y-m-d', strtotime($_POST["tb_month"] . $_POST["tb_day"]));
            $vr_path = "&package=" . urlencode($_POST["tb_package"]) . "&man_type={$_POST["tb_man_type"]}&buynum={$quantity}&goods_id={$goods_id}&commonid={$_POST["tb_commonid"]}&show_package={$_POST["tb_show_package"]}&is_hare={$_POST['is_share']}";
            if(in_array(intval($post['calendar_type']),array(1,2,3))){//价格日历类型
               $vr_path .="&rl_data=". urlencode($post['calendar_date']);
               $vr_path .="&calendar_type=".$post['calendar_type'];
            }
			if(intval($post['calendar_type']) == 4){
			   $vr_path .="&date=".$post['date'];
               $vr_path .="&calendar_type=".$post['calendar_type'];
			}
            redirect('index.php?act=buy_virtual&op=buy_step1&goods_id=' . $goods_id . '&quantity=' . $quantity . $vr_path);
         }
      }
   }
   public function addressOp() {
      Tpl::showpage('buy_address.add');
   }
   /**
    * 代金券使用
    */
   public function voucher_code_userOp() {
      if (empty($_GET["total_price"]) || empty($_GET["voucher_code"])) {
         exit();
      }

      $total_price                   = $_GET["total_price"];
      $condition['voucher_code']     = $_GET["voucher_code"];
      $condition['voucher_state']    = 1;
      $condition['voucher_owner_id'] = 0;
      $message                       = '';
      $price_str                     = '';
      $voucher_res                   = Model()->table('voucher')->where($condition)->field('voucher_store_id,voucher_id,voucher_t_id,voucher_price,voucher_limit,voucher_end_date')->find();
      if (!empty($voucher_res["voucher_id"])) {
         if ($voucher_res["voucher_end_date"] < time()) {
            $message = '此券已超过使用有效期';
         } elseif ($total_price < $voucher_res["voucher_limit"]) {
            $message = '订单金额不足，此券不能使用';
         } else {
            $price_str = $voucher_res["voucher_t_id"] . "|" . $voucher_res["voucher_store_id"] . "|" . $voucher_res["voucher_price"];
         }
      } else {
         $message = '此券不能使用';
      }
      if ('' == $message && '' != $price_str) {
         $arr = array('msg' => true, 'price_str' => $price_str);
      } else {
         $arr = array('msg' => false, 'message' => $message);
      }
      echo json_encode($arr);

   }
   /**
    * 获取微信收货地址，获取acces_token
    * */
   private function get_access_token($appid, $appsecret, $code) {
      // 以$code为键的token只能获取一次token,所以需要保存此token,将token以$code为键存取，时间为获取token时间，过期时间为7200秒，如果$code为键的token不存在则重新写入新的token,键为$code
      $tokenurl    = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $appid . "&secret=" . $appsecret . "&code={$code}&grant_type=authorization_code";
      $tokenclient = new TenpayHttpClient();
      $tokenclient->setReqContent($tokenurl);
      $tokenclient->setMethod("GET");
      $tokenres = "";
      if ($tokenclient->call()) {
         $tokenres = $tokenclient->getResContent();
      }

      if ("" != $tokenres) {
         $tk = json_decode($tokenres);
         if ("" != $tk->access_token) {
            //$this->log_result("addr|back|access_token:".$tk->access_token."|openid:".$tk->openid);
            $accesstoken = $tk->access_token;
         } else {
            //echo "get access token empty";
            //exit(0);
         }
      } else {
         echo "get access token error";
         exit(0);
      }
      return $accesstoken;
   }
   // 请注意服务器是否开通fopen配置
   private function log_result($word) {
      date_default_timezone_set("Etc/GMT-8");
      $log_file = $_SERVER["DOCUMENT_ROOT"] . '/data/log/' . time() . '-' . date('Y-d-m') . '.log';
      $fp       = fopen($log_file, "a");
      flock($fp, LOCK_EX);
      fwrite($fp, "\n" . date('Y-m-d H:i:s') . ":" . $word . PHP_EOL);
      flock($fp, LOCK_UN);
      fclose($fp);
   }
   /**
    * 获取微信收货地址,返回array('appId'=>$appid,'addrSign'=>$addrsign,'timeStamp'=>$timestamp,'nonceStr'=>$noncestr);
    * */
   private function get_wx_addr($return_url,$store_id) {
      loadfunc("tools.class");
      loadfunc("TenpayHttpClient.class");
      $siteurl   = BASE_SITE_URL;
      //动态获取对应appid 和  appsecret begin
      $action   = WAP_SHOP_SITE_URL."/index.php?act=payment_handle&op=getStoreOrderInfo";
      $return   = json_decode(curl($action,'post',array('type'=>'store_wxinfo','store_id'=>$store_id)),true);
      if(empty($return['stat'])){
      	 exit('店铺'.$store_id.'错误原因:'.$return['msg']);
      }
      $app_date = $return['data'];
      //动态获取对应appid 和  appsecret end
      $appid     = $app_date['APPID'];
      $appsecret = $app_date['APPSECRET'];
      //获取 code
      $redirect_uri = $siteurl . '/wap_shop/wx_return_url2.php?return_url=' . base64_encode($return_url);

      $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$appid}&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
      //echo $appid.'--'.$appsecret.'--'.$redirect_uri.'----'.$url;//exit();
      if (empty($_GET['code'])) {
         header("location: $url");
      }
      //https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
      //获取access_token
      $accesstoken = $this->get_access_token($appid, $appsecret, $_GET["code"]);

      //调起地址控件签名
      $timestamp = time();
      $noncestr  = rand(100000, 999999);
      $url       = $siteurl . $_SERVER['REQUEST_URI'];

      $myaddr = new SignTool();
      $myaddr->setParameter("appid", $appid);
      $myaddr->setParameter("url", $url);
      $myaddr->setParameter("noncestr", $noncestr);
      $myaddr->setParameter("timestamp", $timestamp);
      $myaddr->setParameter("accesstoken", $accesstoken);

      $addrsign = $myaddr->genSha1Sign();

      $addrstring = $myaddr->getDebugInfo();
      //$this->log_result("addr|back|addsign:".$addrstring);
      return array('appId' => $appid, 'addrSign' => $addrsign, 'timeStamp' => $timestamp, 'nonceStr' => $noncestr);
   }
   /**
    * 是否是微信打开
    * */
   private function is_wx_pro() {
      $user_agent = $_SERVER['HTTP_USER_AGENT'];
      if (strpos($user_agent, 'MicroMessenger') === false) {
         // 非微信浏览器禁止浏览
         return false;
      } else {
         // 微信浏览器，允许访问
         //preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);//echo "MicroMessenger";
         //echo '<br>Version:'.$matches[2];// 获取版本号
         return true;
      }
   }
   /**
    * 获取微信收货地址,ajax处理
    */
   public function wx_addr_haddleOp() {
      //将微信地址存为session
      if (!empty($_GET["username"])) {
         if (!empty($_GET["province"]) && 'undefined' != $_GET["username"]) {
            $province                      = substr($_GET["province"], 0, 6); //取前两个汉字
            $city                          = substr($_GET["city"], 0, 6);
            $area_name                     = substr($_GET["area"], 0, 6);
            $area_id_res                   = Model()->table('area')->where(array('area_name' => array('like', $province . '%'), 'area_deep' => 1))->field('area_id')->find();
            $city_id_res                   = Model()->table('area')->where(array('area_name' => array('like', $city . '%'), 'area_deep' => 2, 'area_parent_id' => $area_id_res["area_id"]))->field('area_id')->find();
            $area_name_id_res              = Model()->table('area')->where(array('area_name' => array('like', $area_name . '%'), 'area_deep' => 3, 'area_parent_id' => $city_id_res["area_id"]))->field('area_id')->find(); //区县
            $order_address["area_id"]      = $area_id_res["area_id"];
            $order_address["city_id"]      = $city_id_res["area_id"];
            $order_address["area_name_id"] = $area_name_id_res["area_id"];
         }
         $order_address["true_name"] = $_GET["username"];
         $order_address["area_info"] = $_GET['province'] . $_GET['city'] . $_GET['area'];
         $order_address["address"]   = $_GET["info"];
         $order_address["mob_phone"] = $_GET["tel"];
         $_SESSION["order_address"]  = $order_address;
         echo '1';
      } elseif ('1' == $_GET["check_wx_addr"]) {
         //判断微信地址的session是否存在
         if (!empty($_SESSION["order_address"])) {
            $true_name    = $_SESSION["order_address"]["true_name"];
            $area_id      = $_SESSION["order_address"]["area_id"];
            $city_id      = $_SESSION["order_address"]["city_id"];
            $area_name_id = $_SESSION["order_address"]["area_name_id"];
            $area_info    = $_SESSION["order_address"]["area_info"];
            $address      = $_SESSION["order_address"]["address"];
            $mob_phone    = $_SESSION["order_address"]["mob_phone"];
            exit(json_encode(array('state' => 'true', 'true_name' => $true_name, 'area_id' => $area_id, 'city_id' => $city_id, 'area_name_id' => $area_name_id, 'area_info' => $area_info, 'address' => $address, 'mob_phone' => $mob_phone)));
         } else {
            //如果不是在微信打开或者用户已有地址则返回false
            $address_count = Model()->table('address')->where(array("member_id" => $_SESSION["member_id"]))->count();
            if (!$this->is_wx_pro() || $address_count > 0) {
               exit(json_encode(array('state' => 'false', 'data' => '')));
            }
         }
      } elseif ($_GET["area_deep"]) {
         //获取城市选择列表
         $list = Model()->table('area')->where(array('area_parent_id' => $_GET["area_parent_id"], 'area_deep' => $_GET["area_deep"]))->field('area_id,area_name')->select();
         foreach ($list as $v) {
            $option_str .= '<option value="' . $v["area_id"] . '">' . $v["area_name"] . '</option>';
         }
         echo '<option>-请选择-</option>' . $option_str;
      }
   }
   public function testOp() {
      print_r($_SESSION);
      exit();
      $count                = '0';
      $_POST["tb_goods_id"] = '104487';
      $_POST["cart_id"]     = array('104490|1', '103367|2');
      $cart_key             = 0;
      foreach ($_POST["cart_id"] as $k => $v) {
         $cart_id_str .= '|' . $v;
         $v_arr = explode('|', $v);
         if ($_POST["tb_goods_id"] == $v_arr[0]) {
            $cart_key = $k;
         }
      }
      $is_count = strpos($cart_id_str, $_POST["tb_goods_id"]);
      if ('' == $is_count) {
         //购物车商品不存在
         $count = count($_POST["cart_id"]) + 1;
      } else {
         //购物车商品已存在
         $count = $cart_key;
      }
      $cart_id_arr = explode('|', $_POST["cart_id"][$count]);
      print_r($cart_id_arr);
      $_POST["cart_id"][$count] = $_POST["tb_goods_id"] . '|' . $cart_id_arr[1];
      print_r($_POST);
   }

}

<?php
/**
 * 支付回调
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2013 ShopNC Inc. (http://www.shopnc.net)
 * @license    http://www.shopnc.net
 * @link       http://www.shopnc.net
 * @since      File available since Release v1.1
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class alipaymentControl extends BaseHomeControl {

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = 'alipay';
    }
    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);
        include(BASE_ROOT_PATH.'/wap_shop/api/payment/alipay/alipay.config.php');
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyReturn();
        if($verify_result) {
            //验证成功
            //商户订单号
            $out_trade_no = $_GET['out_trade_no'];
            //支付宝交易号
            $trade_no = $_GET['trade_no'];
            //交易状态
            $trade_status = $_GET['trade_status'];
            /*
            if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序
            } else {
                echo "trade_status=".$_GET['trade_status'];
            }*/
            //echo "验证成功<br />";
            $total_fee = $_GET['total_fee'];
            
            $result = $this->_update_order($out_trade_no, $trade_no);
            if($result['state']) {
                $pay_ok_url = WAP_SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.encrypt(ncPriceFormat($total_fee));
                //Tpl::output('result', 'success');
                //Tpl::output('message', '支付成功');
                showMessage('',$pay_ok_url,'tenpay');
            } else {
                $pay_ok_url = WAP_SHOP_SITE_URL.'/index.php?act=buy&op=pay&pay_sn='.$out_trade_no;
                //Tpl::output('result', 'fail');
                // /Tpl::output('message', '支付失败');
                showMessage('支付状态验证失败', $pay_ok_url, 'html','error');
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
        }
        else {
            $pay_ok_url = WAP_SHOP_SITE_URL.'/index.php?act=buy&op=pay&pay_sn='.$out_trade_no;
            //验证失败
            showMessage('支付状态验证失败', $pay_ok_url, 'html','error');
        }
        // Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {
        // 恢复框架编码的post值
        include(BASE_ROOT_PATH.'/wap_shop/api/payment/alipay/alipay.config.php');
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();

        if($verify_result) {
            //验证成功
            $result = $this->_update_order($_POST['out_trade_no'], $_POST['trade_no']);
            if($result['state']) {
                echo 'success';die;
            }
        }
        //验证失败
        echo "fail";die;
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('|', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $this->payment_code, $order_list, $trade_no);

        } elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            if ($result['data']['order_state'] != ORDER_STATE_NEW) {
                return array('state'=>true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $this->payment_code, $result['data'], $trade_no);
        }

        return $result;
    }

}

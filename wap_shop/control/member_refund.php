<?php
/**
 * 买家退款
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class member_refundControl extends BaseMemberControl {
   public function __construct() {
      parent::__construct();
      Language::read('member_member_index');
      $model_refund = Model('refund_return');
      $model_refund->getRefundStateArray();
   }
   /**
    * 添加订单商品部分退款
    *
    */
   public function add_refundOp() {

      $model_refund = Model('refund_return');
      $condition    = array();
      $reason_list  = $model_refund->getReasonList($condition); //退款退货原因
      Tpl::output('reason_list', $reason_list);
      $order_id = intval($_GET['order_id']);
      $goods_id = intval($_GET['goods_id']); //订单商品表编号
      if ($order_id < 1 || $goods_id < 1) { //参数验证
         showDialog(Language::get('wrong_argument'), 'index.php?act=member_order&op=index', 'error');
      }
      $condition             = array();
      $condition['buyer_id'] = $_SESSION['member_id'];
      $condition['order_id'] = $order_id;
      $order                 = $model_refund->getRightOrderList($condition, $goods_id);
      $order_id              = $order['order_id'];
      $order_amount          = $order['order_amount']; //订单金额
      $order_refund_amount   = $order['refund_amount']; //订单退款金额
      $goods_list            = $order['goods_list'];
      $goods                 = $goods_list[0];
      $goods_pay_price       = $goods['goods_pay_price']; //商品实际成交价
      if ($order_amount < ($goods_pay_price + $order_refund_amount)) {
         $goods_pay_price          = $order_amount - $order_refund_amount;
         $goods['goods_pay_price'] = $goods_pay_price;
      }
      Tpl::output('order_amount', $order_amount);
      Tpl::output('goods', $goods);

      $goods_id                    = $goods['rec_id'];
      $condition                   = array();
      $condition['buyer_id']       = $order['buyer_id'];
      $condition['order_id']       = $order['order_id'];
      $condition['order_goods_id'] = $goods_id;
      $condition['seller_state']   = array('lt', '3');
      $refund_list                 = $model_refund->getRefundReturnList($condition);
      $refund                      = array();
      if (!empty($refund_list) && is_array($refund_list)) {
         $refund = $refund_list[0];
      }
      $refund_state = $model_refund->getRefundState($order); //根据订单状态判断是否可以退款退货
      if ($refund['refund_id'] > 0 || 1 != $refund_state) { //检查订单状态,防止页面刷新不及时造成数据错误
         showDialog(Language::get('wrong_argument'), 'index.php?act=member_order&op=index', 'error');
      }
      if (chksubmit() && $goods_id > 0) {
         $refund_array  = array();
         $refund_amount = floatval($_POST['refund_amount']); //退款金额
         if (($refund_amount < 0) || ($refund_amount > $goods_pay_price)) {
            $refund_amount = $goods_pay_price;
         }
         $goods_num = intval($_POST['goods_num']); //退货数量
         if (($goods_num < 0) || ($goods_num > $goods['goods_num'])) {
            $goods_num = 1;
         }
         $refund_array['reason_info'] = '';
         $reason_id                   = intval($_POST['reason_id']); //退货退款原因
         $refund_array['reason_id']   = $reason_id;
         $reason_array                = array();
         $reason_array['reason_info'] = '其他';
         $reason_list[0]              = $reason_array;
         if (!empty($reason_list[$reason_id])) {
            $reason_array                = $reason_list[$reason_id];
            $refund_array['reason_info'] = $reason_array['reason_info'];
         }
//          print_r($_POST);
         //          print_r($refund_array);exit;
         $pic_array                = array();
         $pic_array['buyer']       = $this->upload_pic(); //上传凭证
         $info                     = serialize($pic_array);
         $refund_array['pic_info'] = $info;

         $model_trade   = Model('trade');
         $order_shipped = $model_trade->getOrderState('order_shipped'); //订单状态30:已发货
         if ($order['order_state'] == $order_shipped) {
            $refund_array['order_lock'] = '2'; //锁定类型:1为不用锁定,2为需要锁定
         }
         $refund_array['refund_type'] = $_POST['refund_type']; //类型:1为退款,2为退货
         $show_url                    = 'index.php?act=member_return&op=index';
         $refund_array['return_type'] = '2'; //退货类型:1为不用退货,2为需要退货
         if ('2' != $refund_array['refund_type']) {
            $refund_array['refund_type'] = '1';
            $refund_array['return_type'] = '1';
            $show_url                    = 'index.php?act=member_return&op=index';
         }
         $refund_array['seller_state']  = '1'; //状态:1为待审核,2为同意,3为不同意
         $refund_array['refund_amount'] = ncPriceFormat($refund_amount);
         $refund_array['goods_num']     = $goods_num;
         $refund_array['buyer_message'] = $_POST['buyer_message'];
         $refund_array['add_time']      = time();
         $state                         = $model_refund->addRefundReturn($refund_array, $order, $goods);

         if ($state) {
            if ($order['order_state'] == $order_shipped) {
               $model_refund->editOrderLock($order_id);
            }

            showDialog(Language::get('nc_common_save_succ'), $show_url, 'succ');
         } else {
            showDialog(Language::get('nc_common_save_fail'), 'reload', 'error');
         }
      }
      Tpl::showpage('member_refund_add');
   }
   /**
    * 添加全部退款即取消订单
    *
    */
   public function add_refund_allOp() {
      $model_order           = Model('order');
      $model_trade           = Model('trade');
      $model_refund          = Model('refund_return');
      $order_id              = intval($_GET['order_id']);
      $condition             = array();
      $condition['buyer_id'] = $_SESSION['member_id'];
      $condition['order_id'] = $order_id;
      $order                 = $model_refund->getRightOrderList($condition);
      //
      $order_id              = $order['order_id'];
      $order_amount          = $order['order_amount']; //订单金额
      $order_refund_amount   = $order['refund_amount']; //订单退款金额
      $goods_list            = $order['goods_list'];
      $goods                 = $goods_list[0];
      $goods_pay_price       = $goods['goods_pay_price']; //商品实际成交价
      if ($order_amount < ($goods_pay_price + $order_refund_amount)) {
         $goods_pay_price          = $order_amount - $order_refund_amount;
         $goods['goods_pay_price'] = $goods_pay_price;
      }
      Tpl::output('order_amount', $order_amount);
      Tpl::output('goods', $goods);
      //
      Tpl::output('order', $order);
      $order_amount              = $order['order_amount']; //订单金额
      $condition                 = array();
      $condition['buyer_id']     = $order['buyer_id'];
      $condition['order_id']     = $order['order_id'];
      $condition['goods_id']     = '0';
      $condition['seller_state'] = array('lt', '3');
      $refund_list               = $model_refund->getRefundReturnList($condition);
      $refund                    = array();
      if (!empty($refund_list) && is_array($refund_list)) {
         $refund = $refund_list[0];
      }
      $order_paid   = $model_trade->getOrderState('order_paid'); //订单状态20:已付款
      $payment_code = $order['payment_code']; //支付方式
      if ($refund['refund_id'] > 0 || $order['order_state'] != $order_paid || 'offline' == $payment_code) { //检查订单状态,防止页面刷新不及时造成数据错误
         showDialog(Language::get('wrong_argument'), 'index.php?act=member_order&op=index', 'error');
      }
      if (chksubmit()) {
         $refund_array                   = array();
         $refund_array['refund_type']    = '1'; //类型:1为退款,2为退货
         $refund_array['seller_state']   = '1'; //状态:1为待审核,2为同意,3为不同意
         $refund_array['order_lock']     = '2'; //锁定类型:1为不用锁定,2为需要锁定
         $refund_array['goods_id']       = '0';
         $refund_array['order_goods_id'] = '0';
         $refund_array['reason_id']      = '0';
         $refund_array['reason_info']    = '取消订单，全部退款';
         $refund_array['goods_name']     = '订单商品全部退款';
         $refund_array['refund_amount']  = ncPriceFormat($order_amount);
         $refund_array['buyer_message']  = $_POST['buyer_message'];
         $refund_array['add_time']       = time();

         $pic_array                = array();
         $pic_array['buyer']       = $this->upload_pic(); //上传凭证
         $info                     = serialize($pic_array);
         $refund_array['pic_info'] = $info;
         $state                    = $model_refund->addRefundReturn($refund_array, $order, $goods);

         if ($state) {
            $model_refund->editOrderLock($order_id);
            showDialog(Language::get('nc_common_save_succ'), 'index.php?act=member_refund&op=index', 'succ');
         } else {
            showDialog(Language::get('nc_common_save_fail'), 'reload', 'error');
         }
      }
      Tpl::showpage('member_refund_all');
   }
   /**
    * 退款记录列表页
    *
    */
   public function indexOp() {
      $model_refund          = Model('refund_return');
      $condition             = array();
      $condition['buyer_id'] = $_SESSION['member_id'];

      $keyword_type = array('order_sn', 'refund_sn', 'goods_name');
      if (trim($_GET['key']) != '' && in_array($_GET['type'], $keyword_type)) {
         $type             = $_GET['type'];
         $condition[$type] = array('like', '%' . $_GET['key'] . '%');
      }
      if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
         $add_time_from = strtotime(trim($_GET['add_time_from']));
         $add_time_to   = strtotime(trim($_GET['add_time_to']));
         if (false !== $add_time_from || false !== $add_time_to) {
            $condition['add_time'] = array('time', array($add_time_from, $add_time_to));
         }
      }
      $refund_list = $model_refund->getRefundList($condition, 10);
      Tpl::output('refund_list', $refund_list);
      Tpl::output('show_page', $model_refund->showpage());
      $store_list = $model_refund->getRefundStoreList($refund_list);
      Tpl::output('store_list', $store_list);
      self::profile_menu('member_order', 'buyer_refund');
      Tpl::showpage('member_refund');
   }
   /**
    * 退款记录查看
    *
    */
   public function viewOp() {
      $model_refund           = Model('refund_return');
      $condition              = array();
      $condition['buyer_id']  = $_SESSION['member_id'];
      $condition['refund_id'] = intval($_GET['refund_id']);
      $refund_list            = $model_refund->getRefundList($condition);
      $refund                 = $refund_list[0];
      Tpl::output('refund', $refund);
      $info['buyer'] = array();
      if (!empty($refund['pic_info'])) {
         $info = unserialize($refund['pic_info']);
      }
      Tpl::output('pic_list', $info['buyer']);
      $condition             = array();
      $condition['order_id'] = $refund['order_id'];
      $model_refund->getRightOrderList($condition, $refund['order_goods_id']);
      Tpl::showpage('member_refund_view');
   }
   /**
    * 上传凭证
    *
    */
   private function upload_pic() {
      $refund_pic    = array();
      $refund_pic[1] = 'refund_pic1';
      $refund_pic[2] = 'refund_pic2';
      $refund_pic[3] = 'refund_pic3';
      // $post_param['dir'] = ATTACH_PATH.DS.'refund'.DS;
      $post_param['image'] = array();
      //图片发送到图片服务器
      foreach ($refund_pic as $pic) {
         if (!empty($_FILES[$pic]['name'])) {
            // $post_param['images'][$pic]['name'] = $_FILES[$pic]['name'];
            // $post_param['images'][$pic]['type'] = $_FILES[$pic]['type'];
            // $fp = @fopen($_FILES[$pic]['tmp_name'],'r');
            // $post_param['images'][$pic]['tmp_name'] = stream_get_contents($fp);
            // fclose($fp);
            // $post_param['images'][$pic]['size'] = $_FILES[$pic]['size'];
            // $post_param['images'][$pic]['error'] = $_FILES[$pic]['error'];
            // unset($_FILES[$pic]);
            $fp                    = @fopen($_FILES[$pic]['tmp_name'], 'r');
            $post_param['image'][] = stream_get_contents($fp);
            fclose($fp);
         }
      }
      $url = C('remote_upload_url') . '/wap_shop/api/file.php';
      // echo '<pre>';
      // var_dump($post_param);exit;
      $curl = function ($url, $postFields = null) {
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_FAILONERROR, false);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         @curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
         curl_setopt($ch, CURLOPT_TIMEOUT, 300);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
         curl_setopt($ch, CURLOPT_USERAGENT, "IMGAGE");
         if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
         }
         //判断是否post
         if (is_array($postFields) && 0 < count($postFields)) {
            $post = http_build_query($postFields);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
         }
         $reponse = curl_exec($ch);
         if (curl_errno($ch)) {
            return false;
         } else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode) {
               return $httpStatusCode;
               return false;
            }
         }
         curl_close($ch);
         return $reponse;
      };
      $image = $curl($url, $post_param);
      $ret   = json_decode($image, true);
      return $ret['list'];
      /*
   $pic_array = array();
   $upload = new UploadFile();
   $dir = ATTACH_PATH.DS.'refund'.DS;
   $upload->set('default_dir',$dir);
   $upload->set('allow_type',array('jpg','jpeg','gif','png'));
   $count = 1;
   foreach($refund_pic as $pic) {
   if (!empty($_FILES[$pic]['name'])){
   $result = $upload->upfile($pic);
   if ($result){
   $pic_array[$count] = $upload->file_name;
   $upload->file_name = '';
   } else {
   $pic_array[$count] = '';
   }
   }
   $count++;
   }
   return $pic_array; */
   }

   private function curl($url, $postFields = null) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_FAILONERROR, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      @curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

      curl_setopt($ch, CURLOPT_TIMEOUT, 300);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);

      curl_setopt($ch, CURLOPT_USERAGENT, "IMGAGE");

      if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == 'https') {
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
         curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      }

      //判断是否post
      if (is_array($postFields) && 0 < count($postFields)) {
         // curl_setopt($ch, CURLOPT_POST, true);
         // curl_setopt($ch, CURLOPT_HTTPHEADER, "content-type:multipart/form-data;charset=UTF-8");
         // curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
         $post = http_build_query($postFields);
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      }

      $reponse = curl_exec($ch);

      if (curl_errno($ch)) {
         // return curl_errno($ch);
         return false;
      } else {
         $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         if (200 !== $httpStatusCode) {
            return $httpStatusCode;
            return false;
         }
      }
      curl_close($ch);
      return $reponse;
   }
   /**
    * 用户中心右边，小导航
    *
    * @param string   $menu_type   导航类型
    * @param string    $menu_key   当前导航的menu_key
    * @return
    */
   private function profile_menu($menu_type, $menu_key = '') {
      $menu_array = array();
      switch ($menu_type) {
      case 'member_order':
         $menu_array = array(
            array('menu_key' => 'buyer_refund', 'menu_name' => Language::get('nc_member_path_buyer_refund'), 'menu_url' => 'index.php?act=member_refund'),
            array('menu_key' => 'buyer_return', 'menu_name' => Language::get('nc_member_path_buyer_return'), 'menu_url' => 'index.php?act=member_return'),
            array('menu_key' => 'buyer_vr_refund', 'menu_name' => '虚拟兑码退款', 'menu_url' => 'index.php?act=member_vr_refund'));
         break;
      }
      Tpl::output('member_menu', $menu_array);
      Tpl::output('menu_key', $menu_key);
   }
}

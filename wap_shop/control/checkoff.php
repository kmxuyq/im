<?php

defined('InShopNC') or exit('Access Invalid!');

class checkoffControl extends Control {

   public function __construct() {
      if (!C('site_status')) {
         halt(C('closed_reason'));
      }

      // $this->showLayout();
      Tpl::setDir('checkoff');
      Tpl::setLayout('checkoff_layout');

      if (empty($_SESSION['seller_login']) || 1 != $_SESSION['seller_login']) {
         if (!in_array($_GET['op'], array('login', 'dologin'))) {
            redirect('/wap_shop/index.php?act=checkoff&op=login');
         }
      }
   }

   public function addLog($code, $info){
      Model('order_code_log')->insert(array(
         'code'        => $code,
         'order_id'    => $info['order_id'],
         'addtime'     => time(),
         'member_id'   => $_SESSION['member_id'],
         'member_name' => $_SESSION['member_name'],
         'order_sn'    => $info['order_sn'],
         'goods_name'  => $info['goods_name'],
         'goods_id'    => $info['goods_id'],
      ));
   }

   public function indexOp() {
      Tpl::output('member_name', $_SESSION['member_name']);
      Tpl::showpage('checkoff.check');
   }

   public function checkOp() {
      $code = trim($_POST['code']);
      $info = Model()->table('lottery_record')->where(array('prize_code'=>$code))->find();
      if(!empty($info)){
         if($info['usetime'] > 0){
            die(json_encode(array('status' => 0, 'msg' => '兑奖码已使用')));
         }
         Model()->table('lottery_record')->where(array('prize_code'=>$code))->update(array('usetime' => TIMESTAMP));
         $prize = Model()->table('prize')->where(array('prize_id' => $info['prize_id']))->find();
         $this->addLog($code, array('order_id' => 0, 'order_sn' => '', 'goods_name' => $prize['prize_name'], 'goods_id' => ''));
         die(json_encode(array('status' => 1, 'msg' => '核销成功')));
      }


      $model_vr_order = Model('vr_order');
      $vr_code_info = $model_vr_order->getOrderCodeInfo(array('vr_code' => $code));
      if (empty($vr_code_info)) {
          die(json_encode(array('msg' => '该兑换码不存在', 'status' => 0)));
      }
      if ($vr_code_info['vr_state'] == '1') {
         die(json_encode(array('msg' => '该兑换码已被使用', 'status' => 0)));
      }
      if ($vr_code_info['vr_indate'] < TIMESTAMP) {
         die(json_encode(array('msg' => '该兑换码已过期，使用截止日期为： '.date('Y-m-d H:i:s',$vr_code_info['vr_indate']), 'status' => 0)));
      }
      if ($vr_code_info['refund_lock'] > 0) {//退款锁定状态:0为正常,1为锁定(待审核),2为同意
         die(json_encode(array('msg' => '该兑换码已申请退款，不能使用', 'status' => 0)));
      }

      //更新兑换码状态
      $update = array();
      $update['vr_state'] = 1;
      $update['vr_usetime'] = TIMESTAMP;
      $update = $model_vr_order->editOrderCode($update, array('vr_code' => $code));
      //如果全部兑换完成，更新活动状态
      //  Model()->table('active')->where(array('az_code'=>$code))->update(array('code_usetime'=>TIMESTAMP));
      //如果全部兑换完成，更新订单状态
      Logic('vr_order')->changeOrderStateSuccess($vr_code_info['order_id']);

      if ($update) {
          //取得返回信息
          $order_info = $model_vr_order->getOrderInfo(array('order_id'=>$vr_code_info['order_id']));
          if ($order_info['use_state'] == '0') {
              $model_vr_order->editOrder(array('use_state' => 1), array('order_id' => $vr_code_info['order_id']));
          }
          $this->addLog($code, array('order_id' => $order_info['order_id'], 'order_sn' => $order_info['order_sn'], 'goods_name' => $order_info['goods_name'], 'goods_id' => $order_info['goods_id']));
         die(json_encode(array('status' => 1, 'msg' => '核销成功')));
      }
      die(json_encode(array('msg' => '核销失败', 'status' => 0)));
   }

   public function loginOp() {
      Tpl::showpage('checkoff.index');
   }

   public function dologinOp() {
      $username = trim($_POST['username']);
      $password = trim($_POST['password']);
      if ('' == $username || '' == $password) {
         die(json_encode(array('status' => 0, 'msg' => '请输入用户名密码')));
      }
      $model_seller = Model('seller');
      $seller_info  = $model_seller->getSellerInfo(array('seller_name' => $username));
      if (empty($seller_info)) {
         die(json_encode(array('status' => 0, 'msg' => '用户名密码错误')));
      }
      $model_member = Model('member');
      $member_info  = $model_member->getMemberInfo(
         array(
            'member_id'     => $seller_info['member_id'],
            'member_passwd' => md5($password),
         )
      );
      if (empty($member_info)) {
         die(json_encode(array('status' => 0, 'msg' => '用户名密码错误')));
      }
      $_SESSION['seller_login'] = 1;
      $_SESSION['member_name']  = $member_info['member_name'];
      $_SESSION['member_id']    = $member_info['member_id'];
      die(json_encode(array('status' => 1)));
   }

   public function logoutOp() {
      unset($_SESSION['seller_login'], $_SESSION['member_name'], $_SESSION['member_id']);
      redirect('/wap_shop/index.php?act=checkoff&op=login');
   }

   public function listOp() {
      Tpl::output('member_name', $_SESSION['member_name']);
      $list = Model('order_code_log')->where('member_id=' . intval($_SESSION['member_id']))->order('id desc')->limit(50)->select();
      Tpl::output('list', $list);
      Tpl::showpage('checkoff.list');
   }

}

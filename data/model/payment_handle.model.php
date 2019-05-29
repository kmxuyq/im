<?php
/**
 * 支付成功数据表操作
 */
define('InShopNC', true);
//defined('InShopNC') or exit('Access Invalid!');
class payment_handleModel {
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
   public function pay_add($pay_type, $order_id, $mode, $amount, $moneytype, $remark1, $alipay_trade_no, $alipay_trade_status) {

      //支付成功
      //商户系统的逻辑处理（例如判断金额，判断支付状态(20成功,30失败),更新订单状态等等）......
      $order_id_arr = explode("-", $order_id);
      $order_id     = $order_id_arr[0];
      $web_tel      = $GLOBALS['setting_config']["site_tel400"];
      $tablepre     = $GLOBALS["setting_config"]["tablepre"];
      //user_order为用户预存款充值
      if ('pd_order' == $remark1) {
         //更新充值日志表
         $pay_log     = Model('pay_log'); //充值日志
         $pd_recharge = Model()->table('pd_recharge'); //预存款充值信息表
         $member      = Model()->table('member');
         $is_pay      = $pay_log->where(array('oid' => $order_id, 'oid_number' => $order_id_arr[1]))->count();
         if (0 == $is_pay) {
            $time                         = time();
            $pay_d["oid"]                 = $order_id;
            $pay_d["oid_number"]          = $order_id_arr[1];
            $pay_d["type"]                = $pay_type;
            $pay_d["mode"]                = $mode;
            $pay_d["amount"]              = $amount;
            $pay_d["moneytype"]           = $moneytype;
            $pay_d["remark1"]             = $remark1;
            $pay_d["alipay_trade_no"]     = $alipay_trade_no;
            $pay_d["alipay_trade_status"] = $alipay_trade_status;
            $pay_d["d_time"]              = $time;
            $pay_log->insert($pay_d); //插入支付日志表  只插入一次

            $pd_d['pdr_payment_code']  = $moneytype;
            $pd_d['pdr_payment_name']  = $pay_type;
            $pd_d['pdr_trade_sn']      = $alipay_trade_no;
            $pd_d['pdr_add_time']      = $time;
            $pd_d['pdr_payment_state'] = 1;
            $pd_d['pdr_payment_time']  = $time;
            $pd_recharge->where(array('pdr_sn' => $order_id))->update($pd_d); //更新预存款充值信息表

            //更新用户预存款
            $member_info = $pd_recharge->where(array('pdr_sn' => $order_id))->field('pdr_member_id,pdr_member_name')->find();
            $member->where(array('member_id' => $member_info["pdr_member_id"]))->setInc('available_predeposit', $amount); //更新用户表用户余额
            $member_mobile_res = $member->where(array('member_id' => $member_info["pdr_member_id"]))->field('member_mobile')->find();
            //发送邮件
            //$email_status=$this->sendmail_order_payok($uid,$order_id,$amount,$is_login);
            //发送短信通知
            /* print_r($order_list);
            echo $order_list["buyer_id"]."<br/>";
            echo $is_virtual_table.'||'.$myphone.'--'.$order_list["buyer_name"].'--'.$order_id.'--'.$amount.'--'.$web_tel;  */
            if ('' != $member_mobile_res["pdr_member_mobile"]) {
               $myphone      = $member_mobile_res["member_mobile"];
               $member_name  = $member_info["pdr_member_name"];
               $send_phone   = new SendCms();
               $phone_status = $send_phone->send_pre_deposit($myphone, $member_name, $order_id, $amount, $web_tel);
            }
            return '1';
         }
      } elseif('pdr_order'  == $remark1){
		  //积分充值
		  //更新充值日志表
         $pay_log     = Model('pay_log'); //充值日志
         $is_pay      = $pay_log->where(array('oid' => $order_id, 'oid_number' => $order_id_arr[1]))->count();
         if (0 == $is_pay) {
            $time                         = time();
            $pay_d["oid"]                 = $order_id;
            $pay_d["oid_number"]          = $order_id_arr[1];
            $pay_d["type"]                = $pay_type;
            $pay_d["mode"]                = $mode;
            $pay_d["amount"]              = $amount;
            $pay_d["moneytype"]           = $moneytype;
            $pay_d["remark1"]             = $remark1;
            $pay_d["alipay_trade_no"]     = $alipay_trade_no;
            $pay_d["alipay_trade_status"] = $alipay_trade_status;
            $pay_d["d_time"]              = $time;
            $pay_log->insert($pay_d); //插入支付日志表  只插入一次
            return '1';
         }

		$internalSn = $order_id . '_' . $remark1;
		$paySn      = $order_id;
		$externalSn = $alipay_trade_no;
		$up                      = array();
		$up['pdr_trade_sn']      = $externalSn;
		$up['pdr_payment_state'] = 1;
		$up['pdr_payment_time']  = time();
		$updateSuccess           = Model()->table('pts_recharge')->where(array('pdr_sn' => $paySn))->update($up);
		if($updateSuccess){
		   $r            = Model()->table('pts_recharge')->where(array('pdr_sn' => $paySn))->find();
		   $pts          = intval($r['pdr_pts']);
		   $setting = Model('setting');
		   $l       = $setting->getListSetting();
		   $dhl     = intval($l['points_dhl']);
		   $memo         = ('积分充值：' . ($dhl*$r['pdr_amount']) . '；支付人民币：' . $r['pdr_amount'] . '元');
		   $points_model = Model('points');
		   $points_model->savePointsLog('points_pull', array('pl_desc' => $memo, 'pl_memberid' => $_SESSION['member_id'], 'pl_membername' => $_SESSION['member_name'], 'pl_points' => $dhl*$r['pdr_amount']));
		}
	  } else {
         //订单支付
         //判断商品是否是虚拟商品,1为1，0为否,以调用不同的订单表
         $is_virtual_count = Model()->table('order')->where(array('pay_sn' => $order_id))->count(); //存在则为实物订单
         if ('1' == $is_virtual_count) { //实物商品
            $is_virtual       = '0';
            $is_virtual_table = '';
            //普通商品订单号为pay_sn,虚拟商品（线路)为order_sn
            $where_sn       = array("pay_sn" => $order_id); //普通商品
            $order_sn_str   = "a.pay_sn='{$order_id}'";
            $order_info_str = '';
            $vr_order_list = Model('vr_order')->where(array('pay_sn' => $order_id))->select();
         } else {
            $is_virtual       = '1';
            $is_virtual_table = 'vr_';
            $where_sn         = array("order_sn" => $order_id);
            $order_sn_str     = "a.order_sn='{$order_id}'";
            // $order_info_str='a.order_info,';
         }

         $pay_log = Model('pay_log');
         $is_pay  = $pay_log->where(array('oid' => $order_id, 'oid_number' => $order_id_arr[1]))->count();
         if (0 == $is_pay) {
            $order_res  = Model()->query("select a.*,b.member_email from {$tablepre}{$is_virtual_table}order a,{$tablepre}member b where a.buyer_id=b.member_id and {$order_sn_str}");
            $order_list = $order_res[0];
            //----------------------------
            $time                         = time();
            $pay_d["oid"]                 = $order_id;
            $pay_d["oid_number"]          = $order_id_arr[1];
            $pay_d["type"]                = $pay_type;
            $pay_d["mode"]                = $mode;
            $pay_d["amount"]              = $amount;
            $pay_d["moneytype"]           = $moneytype;
            $pay_d["remark1"]             = $remark1;
            $pay_d["alipay_trade_no"]     = $alipay_trade_no;
            $pay_d["alipay_trade_status"] = $alipay_trade_status;
            $pay_d["d_time"]              = $time;
            $pay_log->insert($pay_d); //插入支付日志表  只插入一次

            $order = Model()->table($is_virtual_table . 'order');
            if ($is_virtual == '0') {
               if (!empty($order_list)) {
                  $order->where($where_sn)->setInc('rcb_amount', $amount); //更新金额   只更新一次
               }
               if (!empty($vr_order_list)) {
                  foreach($vr_order_list as $val) {
                     Model('vr_order')->where(array('order_id' => $val['order_id']))->setInc('rcb_amount', $val['order_amount']);
                  }
               }
            } else {
               $order->where($where_sn)->setInc('rcb_amount', $amount); //更新金额   只更新一次
            }

            $order_d["payment_code"] = $pay_type;
            $order_d["payment_time"] = $time;
            //if(($order_list["rcb_amount"]+$order_list["pd_amount"])>=$order_list["order_amount"]){
            $order_d["order_state"] = '20';
            //}
            // if (1 == $is_virtual and 1 == $order_list['is_share'] and 0 == $order_list['re_status']) {
            if (1 == $order_list['is_share'] and 0 == $order_list['re_status']) {
               #虚拟订单，自动结算佣金
               $order_d['re_time']   = TIMESTAMP;
               $order_d['re_status'] = 1;
               $cmms_model           = Model('share_cmm_log');
               $ssm_model            = Model('share_member');
               for ($i = 1; $i <= 3; $i++) {
                  $re_amount = $order_list['re_price_' . $i];
                  $re_mid    = $order_list['re_mid_' . $i];
                  if ($re_amount > 0 and $re_mid > 0) {
                     $mem = $ssm_model->where(array('store_id' => $order_list['store_id'], 'member_id' => $re_mid))->find();
                     if (1 == $mem['isshare'] and 1 == $mem['status']) {
                        #存入冻结余额
                        $ssm_model->where(array('store_id' => $order_list['store_id'], 'member_id' => $re_mid))->update(array('credits' => array('exp', 'credits+' . $re_amount)));
                        $log_data = array(
                           'addtime'       => TIMESTAMP,
                           'openid'        => (string) $mem['openid'],
                           'mid'           => $re_mid,
                           'nickname'      => strval($mem['nickname']),
                           'type'          => '余额',
                           'order_id'      => $order_list['order_id'],
                           'amount'        => $re_amount,
                           'remark'        => '',
                           'order_type'    => '2',
                           'store_id'      => $order_list['store_id'],
                           'order_sn'      => $order_list['order_sn'],
                           'from_mid'      => $order_list['buyer_id'],
                           'from_nickname' => $order_list['buyer_name'],
                        );
                        $cmms_model->insert($log_data);
                     }
                  }
               }
            }

            if (1 == $is_virtual) {
               $order->where($where_sn)->update($order_d); //更新订单
               $model_vr_order = Model('vr_order');
               $insert         = $model_vr_order->addOrderCode($order_list);
               if ($insert) {
                  //发送兑换码短信
                  $param = array('goods_name'=>$order_list['goods_name'],'order_id' => $order_list['order_id'], 'buyer_id' => $order_list['buyer_id'], 'buyer_phone' => $order_list['buyer_phone']);
                  QueueClient::push('sendVrCode', $param);
               } else {
                  echo "生成兑换码失败！";
               }
            } else {
               if (!empty($order_list)) {
                  $order->where($where_sn)->update($order_d); //更新订单
               }
               if (!empty($vr_order_list)) {
                  $msg = '';
                  foreach ($vr_order_list as $val) {
                     Model('vr_order')->where(array('order_id' => $val['order_id']))->update($order_d);
                     $insert = Model('vr_order')->addOrderCode($val);
                     if ($insert) {
                        $param = array('order_id' => $val['order_id'], 'buyer_id' => $val['buyer_id'], 'buyer_phone' => $val['buyer_phone']);
                        QueueClient::push('sendVrCode', $param);
                     } else {
                        $msg .= ',生成兑换码失败！:' . $val['order_id'];
                     }
                  }
                  echo $msg;
               }
            }

            //----------------------------
            //获取收货地址电话
            $reciver_info_res = Model()->table('order_common')->where(array('order_id' => $order_list["order_id"]))->field('reciver_info')->find();
            $reciver_info_arr = unserialize($reciver_info_res["reciver_info"]);
            $myphone          = $reciver_info_arr['phone'];
            //$myphone=$order_list["buyer_phone"];

            $myemail  = $order_list["member_email"];
            $uid      = $order_list["buyer_id"];
            $is_login = '1';

            //高尔夫支付成功后将场次的库存设为0
            if ('1' == $is_virtual) {
               $order_info = unserialize($order_list["order_info"]);
               if ('46' == $order_info['type_id']) {
                  $commonid    = $order_info["commonid"];
                  $date        = $order_info["date"];
                  $golf_minute = $order_info["golf_minute"];
                  $stock       = '0';
                  Model('golf_stock')->golf_stock_up($commonid, $date, $golf_minute, $stock);
               }
            }
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
            echo $order_list["buyer_id"]."<br/>";
            echo $is_virtual_table.'||'.$myphone.'--'.$order_list["buyer_name"].'--'.$order_id.'--'.$amount.'--'.$web_tel;  */
            //获取卖家电话，发送信息提醒
            $send_phone      = new SendCms();
            $store_phone_res = Model()->table('store')->where(array('store_id' => $order_list["store_id"]))->field('store_mobile')->find();
            if ('' != $store_phone_res["store_mobile"] && $amount > 10) {
               //卖家订单提醒
               $send_phone->send_payok_message($store_phone_res["store_mobile"], $order_list["buyer_name"], $order_id, $amount);
            }
            $phone_status = $send_phone->send_order_payok($myphone, $order_list["buyer_name"], $order_id, $amount, $web_tel);
            return '1';
         }
      }

   }
}
?>

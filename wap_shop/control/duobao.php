<?php
/**
 * 默认展示页面
 *
 *
 **by 多用户商城 运营版*/

defined('InShopNC') or exit('Access Invalid!');
class duobaoControl extends BaseHomeControl {
   public function indexOp() {
      $model_member = Model('member');
      $minf         = $model_member->getMemberInfo(array('member_id' => $_SESSION['member_id']));
      if (!empty($minf)) {
         Tpl::output('mem_points', $minf['member_points']);
         $_SESSION['member_name'] = trim($minf['member_name']);
      }

      $model_groupbuy           = Model('groupbuy');
      $condition                = array();
      $t                        = time();
      $condition['state']       = 20;
      $condition['is_vr']       = 1;
      $condition['vr_class_id'] = 0;
      $condition['start_time']  = array('lt', $t);
      $groupbuy_list1            = $model_groupbuy->getGroupbuyExtendList($condition, 500, 'groupbuy_id desc');
      Tpl::output('group1', $groupbuy_list1);
      $condition                = array();
      $t                        = time();
      $condition['state']       = 20;
      $condition['is_vr']       = 1;
      $condition['vr_class_id'] = 5;
      $condition['start_time']  = array('lt', $t);
	  $route_store_id = 7;
	  if($_SESSION['store_id']){
		  $route_store_id = $_SESSION['store_id'];
	  }elseif($groupbuy_list1[0]['store_id']){
		  $route_store_id = $groupbuy_list1[0]['store_id'];
	  }else{
		  $route_store_id = $groupbuy_list[0]['store_id'];
	  }
      $groupbuy_list            = $model_groupbuy->getGroupbuyExtendList($condition, 500, 'groupbuy_id desc');
      Tpl::output('group5', $groupbuy_list);
	  Tpl::output('route_store_id', $route_store_id);
      Tpl::showpage('yydb.index', 'null_layout');
   }

   //查看参与记录
   public function viewDbRecOp() {
//       if ("" == $_SESSION["openid"]) {
//          $this->wxdloginOp();
//       } else {
//          if (intval($_SESSION['member_id']) == 0) {
//             $array                    = array();
//             $array['member_wxopenid'] = $_SESSION['openid'];
//             $member_info              = $model_member->getMemberInfo($array);
//             if (!empty($member_info)) {
//                $_SESSION['member_id']   = $member_info['member_id'];
//                $_SESSION['member_name'] = $member_info['member_name'];
//             } else {
//                //showMessage('系统错误', '/index.php', 'html', 'error');
//                $this->showDlogMessage('系统错误！');
//                die;
//             }
//          }
//       }
      //不用再次登陆  继承的BaseHomeControl会自动免登陆,然后塞入session不用再次走微信登陆
      $model_groupbuy = Model('groupbuy');
      //
      $gstr = "";
      $ggss = Model()->table('order')->where(array('buyer_id' => intval($_SESSION['member_id'])))->select();
      if (!empty($ggss)) {
         foreach ($ggss as $gs) {
            $gstr .= ($gs['gr_id'] . ',');
         }
         $gstr = substr($gstr, 0, strlen($gstr) - 1);
      }
	  $_SESSION['route_store_id'] = $ggss[0]['store_id'];
	  Tpl::output('route_store_id',$ggss[0]['store_id']);
      //
      $condition                = array();
      $t                        = time();
      $condition['state']       = 20;
      $condition['is_vr']       = 1;
      $condition['groupbuy_id'] = array('in', $gstr);
      $groupbuy_list            = $model_groupbuy->getGroupbuyExtendList($condition, 500, 'groupbuy_id desc');
      if (!empty($groupbuy_list)) {
         foreach ($groupbuy_list as $k => $v) {
            $win                                  = explode('_', $v['go_code']);
            $wt                                   = $win[0];
            $wo                                   = $win[1];
            $wr                                   = $win[2];
            $wc                                   = $win[3];
            $groupbuy_list[$k]['g_winBuyer']      = $wr;
            $groupbuy_list[$k]['g_winBuyerCode']  = $wc;
            $groupbuy_list[$k]['g_winBuyerTotal'] = $wt;
         }
      }
      Tpl::output('g_jjjx', $groupbuy_list);
      //
      $yjkj                     = array();
      $zzkj                     = array();
      $dbcg                     = array();
      $condition                = array();
      $t                        = time();
      $condition['state']       = 32;
      $condition['is_vr']       = 1;
      $condition['groupbuy_id'] = array('in', $gstr);
      $groupbuy_list            = $model_groupbuy->getGroupbuyExtendList($condition, 500, 'groupbuy_id desc');
      if (!empty($groupbuy_list)) {
         $s1 = 0;
         $s2 = 0;
         $s3 = 0;
         foreach ($groupbuy_list as $k => $v) {
            $win = explode('_', $v['go_code']);
            $wt  = $win[0];
            $wo  = $win[1];
            $wr  = $win[2];
            $wc  = $win[3];
            if (($t - $v['gend_time']) <= 1800) { //半小时
               $zzkj[$s1]                    = $v;
               $zzkj[$s1]['g_winBuyer']      = $wr;
               $zzkj[$s1]['g_winBuyerCode']  = $wc;
               $zzkj[$s1]['g_winBuyerTotal'] = $wt;
               $fsstr                        = "";
               $fs                           = Model()->table('order')->where(array('buyer_id' => $_SESSION['member_id'], 'gr_id' => $v['groupbuy_id']))->select();
               if (!empty($fs)) {

                  foreach ($fs as $fv) {
                     if ($fv['gr_code2']) {
                        $fsstr .= ($fv['gr_code'] . ',' . $fv['gr_code2'] . ',');
                     } else {
                        $fsstr .= ($fv['gr_code'] . ',');
                     }
                  }

               }
               //$zzkj[$s1]['g_codeStr'] = strlen($fsstr)?substr($fsstr,0,strlen($fsstr)-1):"";
               //$zzkj[$s1]['g_codeStr'] = strlen($fsstr)?$fsstr:"";
               $zzkj[$s1]['g_codeStr'] = "";
               if (strlen($fsstr)) {
                  $code1_array = explode(',', substr($fsstr, 0, strlen($fsstr) - 1));
                  foreach ($code1_array as $v) {
                     $zzkj[$s1]['g_codeStr'] .= "<span>{$v}</span>";
                  }
               }
               $s1 = $s1 + 1;
            } else {
               $yjkj[$s3]                    = $v;
               $yjkj[$s3]['g_winBuyer']      = $wr;
               $yjkj[$s3]['g_winBuyerCode']  = $wc;
               $yjkj[$s3]['g_winBuyerTotal'] = $wt;
               $fsstr                        = "";
               $fs                           = Model()->table('order')->where(array('buyer_id' => $_SESSION['member_id'], 'gr_id' => $v['groupbuy_id']))->select();
               if (!empty($fs)) {

                  foreach ($fs as $fv) {
                     if ($fv['gr_code2']) {
                        $fsstr .= ($fv['gr_code'] . ',' . $fv['gr_code2'] . ',');
                     } else {
                        $fsstr .= ($fv['gr_code'] . ',');
                     }
                  }

               }
               $yjkj[$s3]['g_codeStr'] = "";
               if (strlen($fsstr)) {
                  $code_array = explode(',', substr($fsstr, 0, strlen($fsstr) - 1));
                  foreach ($code_array as $v) {
                     $yjkj[$s3]['g_codeStr'] .= "<span>{$v}</span>";
                  }
               }
               $s3 = $s3 + 1;
            }
         }
      }
      Tpl::output('g_zzkj', $zzkj);
      Tpl::output('g_yjkj', $yjkj);
      //Tpl::output('g_dbcg',$dbcg);

      $g_dbcg = array();
      foreach ($yjkj as $k => $v) {
         if ($v['g_winBuyer'] == $_SESSION['member_name']) {
            $g_dbcg[$k] = $v;
         }
      }
      Tpl::output('g_dbcg', $g_dbcg);

      Tpl::showpage('yydb.viewDbRec', 'null_layout');
   }

   //查看往期中奖
   public function viewOldRecOp() {

      //
      $model_groupbuy = Model('groupbuy');

      $condition          = array();
      $t                  = time();
      $condition['state'] = 32;
      $condition['is_vr'] = 1;
      $groupbuy_list      = $model_groupbuy->getGroupbuyExtendList($condition, 500, 'gend_time desc');
      if (!empty($groupbuy_list)) {
         foreach ($groupbuy_list as $k => $v) {
            $win                                  = explode('_', $v['go_code']);
            $wt                                   = $win[0];
            $wo                                   = $win[1];
            $wr                                   = $win[2];
            $wc                                   = $win[3];
            $groupbuy_list[$k]['g_winBuyer']      = $wr;
            $groupbuy_list[$k]['g_winBuyerCode']  = $wc;
            $groupbuy_list[$k]['g_winBuyerTotal'] = $wt;
         }
      }
      Tpl::output('group', $groupbuy_list);

      Tpl::showpage('yydb.viewOldRec', 'null_layout');
   }

   //查看往期揭晓
   public function viewOldJxRecOp() {
      $goods_id             = intval($_GET['goods_id']);
      $model_groupbuy       = Model('groupbuy');
      $gr_id                = intval($_GET['gr_id']);
      $groupbuy_list        = array();
      $condition            = array();
      $t                    = time();
      $conds['groupbuy_id'] = $gr_id;
      if ($groupbuy_infos = $model_groupbuy->where($conds)->find()) {
         $condition['is_vr'] = 1;
         $condition['state'] = 32;
         if (0 == $groupbuy_infos['parent_id']) {
            $condition['parent_id'] = intval($groupbuy_infos['groupbuy_id']);
         } else {
            $condition['parent_id'] = intval($groupbuy_infos['parent_id']);
         }
         $wheres        = "(parent_id = {$condition['parent_id']} OR groupbuy_id = {$condition['parent_id']}) AND is_vr = 1 AND state = 32";
         $groupbuy_list = $model_groupbuy->getGroupbuyExtendList($wheres, 500, 'groupbuy_id desc');
         if (!empty($groupbuy_list)) {
            foreach ($groupbuy_list as $k => $v) {
               $win                                  = explode('_', $v['go_code']);
               $wt                                   = $win[0];
               $wo                                   = $win[1];
               $wr                                   = $win[2];
               $wc                                   = $win[3];
               $groupbuy_list[$k]['g_winBuyer']      = $wr;
               $groupbuy_list[$k]['g_winBuyerCode']  = $wc;
               $groupbuy_list[$k]['g_winBuyerTotal'] = $wt;
               //
               $cond             = array();
               $cond['order_sn'] = $wo;
               $orderinfo        = Model()->table('order')->where($cond)->find();
               //
               $ipx                                 = explode('.', $orderinfo['shipping_code']);
               $groupbuy_list[$k]['g_winBuyerIpxx'] = $ipx[0] . '.***.***.' . $ipx[3];
            }
         }
      }
      Tpl::output('group', $groupbuy_list);
      Tpl::output('class', $groupbuy_infos['vr_class_id']);
      Tpl::showpage('yydb.viewOldJxRec', 'null_layout');
   }

   //确认订单
   public function setOrderOp() {
      if (!isset($_SESSION['last_order_gid']) || empty($_SESSION['last_order_gid'])) {
         $groupid = intval($_POST['gr_id']);
      } else {
         @header('Location: /wap_shop/index.php?act=dbgoods&gr_id=' . $_SESSION['last_order_gid']);
      }
      $_SESSION['last_order_gid'] = '';
      $buyCount                   = intval($_POST['buyCount']);
      Tpl::output('buyCount', $buyCount);
      Tpl::output('gr_id', $groupid);
      Tpl::output('b_c', $buyCount);
      //
      $model_groupbuy           = Model('groupbuy');
      $condition                = array();
      $condition['groupbuy_id'] = $groupid;
      $groupbuy                 = $model_groupbuy->getGroupbuyInfo($condition);
      Tpl::output('group', $groupbuy);
      $goods_id = $goods_id ? $goods_id : intval($groupbuy['goods_id']);
      $g_type   = (intval($groupbuy['vr_class_id']) == 5 ? 5 : 1);
      Tpl::output('g_type', $g_type);
      $chae = intval($groupbuy['total_quantity']) - intval($groupbuy['buy_quantity']);
      if ($buyCount > $chae) {
         //showMessage('库存不足', '', 'html', 'error');
         $this->showDlogMessage('库存不足');
         exit();
      }
      $model_goods  = Model('goods');
      $goods_detail = $model_goods->getGoodsDetail($goods_id);
      $goods_info   = $goods_detail['goods_info'];
      if (empty($goods_info)) {
         //showMessage('信息错误', '', 'html', 'error');
         $this->showDlogMessage('信息错误');
      }

      //
      $setting = Model('setting');
      $l       = $setting->getListSetting();
      $dhl     = intval($l['points_dhl']);
      Tpl::output('p_dhl', $dhl);
      Tpl::output('points', $g_type * $buyCount * $dhl);
      //
      $model_member = Model('member');
      $minf         = $model_member->getMemberInfo(array('member_id' => $_SESSION['member_id']));
      $points       = $g_type * $buyCount * $dhl; //所需积分
      if ($points > $minf['member_points']) {
         $setting = Model('setting');
         $l       = $setting->getListSetting();
         $dh      = trim($l['yydb_dhsm']);
         $this->showDlogMessage('你的积分不足！', BASE_SITE_URL . "/wap_shop/index.php?act=duobao&op=addPoints&gid={$groupid}&points=" . (intval($g_type * $buyCount * $dhl) - intval($minf['member_points'])) . "&b_c={$buyCount}&class={$groupbuy['vr_class_id']}");
         exit();
      }
      Tpl::output('mem_points', $minf['member_points']);

      //
      if (intval($minf['member_points']) >= intval($g_type * $buyCount * $dhl)) {
         Tpl::output('points_flag', '1');
         Tpl::output('points_chae', 0);
      } else {
         Tpl::output('points_flag', '0');
         Tpl::output('points_chae', (intval($g_type * $buyCount * $dhl) - intval($minf['member_points'])));
      }
      Tpl::showpage('yydb.setOrder', 'null_layout');
   }

   //充值积分
   public function addPointsOp() {
      $gid = $_GET['gid'];
      Tpl::output('gr_id', $gid);
      //
      $p = $_GET['points'];
      Tpl::output('points', $p);
      //
      $bc = $_GET['b_c'];
      Tpl::output('bc', $bc);
      //
      $setting = Model('setting');
      $l       = $setting->getListSetting();
      $dh      = trim($l['yydb_dhsm']);
      Tpl::output('dhsm', $dh);
      //
      $model_member = Model('member');
      $minf         = $model_member->getMemberInfo(array('member_id' => $_SESSION['member_id']));
      Tpl::output('mem_points', $minf['member_points']);

      Tpl::showpage('yydb.addPoints', 'null_layout');
   }

   //微信支付
   public function addPtsWxOp() {
      if(!$this->checkIsInWeixin()){
         return $this->showDlogMessage('请在微信中打开充值');
      }
      $gid = intval($_GET['gid']);
      $p   = intval($_GET['points']);
      $bc  = intval($_GET['bc']);
      if (0 == $gid || 0 == $p || 0 == $bc) {
         return $this->showDlogMessage('参数错误');
      }
      $setting = Model('setting');
      $l       = $setting->getListSetting();
      $dh      = intval($l['points_dhl']);
      if ($dh <= 0) {
         return $this->showDlogMessage('积分设置错误，请联系管理员');
      }
      $rmb1 = intval($p / $dh);
      $rmb2 = $p - ($rmb1 * $dh);
      if ($rmb2 > 0) {
         $rmb = $rmb1 + 1;
      } else {
         $rmb = $rmb1;
      }
      $t                         = time();
      $tt                        = $t . rand(100, 999);
      $data                      = array();
      $data['pdr_sn']            = $tt;
      $data['pdr_member_id']     = $_SESSION['member_id'];
      $data['pdr_member_name']   = $_SESSION['member_name'];
      $data['pdr_amount']        = $rmb;
      $data['pdr_payment_code']  = 'wxpay_jsapi';
      $data['pdr_payment_name']  = '微信支付';
      $data['pdr_trade_sn']      = '';
      $data['pdr_add_time']      = $t;
      $data['pdr_payment_state'] = 0;
      $data['pdr_pts']           = $p; //($rmb*$dh);
      $data['pdr_memo']          = '积分充值';
      $ret                       = Model()->table('pts_recharge')->insert($data);
      if ($ret) {
		 $_goods_name = C('site_name') . '积分充值单' . $data['pdr_sn'];
		 $pay_sn = $data['pdr_sn'];
		 $sum_price = $data['pdr_amount'];
		 $wxpay_jsapi_url = "/wap_shop/api/payment/wxpay/js_api_call.php?body={$_goods_name}&out_trade_no={$pay_sn}&total_fee={$sum_price}&gid={$_GET['gid']}&b_c={$_GET['bc']}&attach=pdr_order";
         header("Location: {$wxpay_jsapi_url}");
		 /* $inc_file = BASE_PATH . DS . 'api' . DS . 'payment' . DS . 'wxpay_jsapi' . DS . 'wxpay_jsapi.php';
         require $inc_file;
         $param                = array();
         $param['orderSn']     = $tt;
         $param['orderFee']    = (int) (100 * $data['pdr_amount']);
         $param['orderInfo']   = C('site_name') . '积分充值单' . $data['pdr_sn'];
         $param['orderAttach'] = ('r');
         $param['finishedUrl'] = (BASE_SITE_URL . '/wap_shop/index.php?act=duobao&op=inOrders&gid=' . $gid . '&b_c=' . $bc . '&pay_sn=' . $tt);
         $api = new wxpay_jsapi();
         $api->setConfigs($param);
         try {
            echo $api->paymentHtml($this);
         } catch (Exception $ex) {
            if (C('debug')) {
               header('Content-type: text/plain; charset=utf-8');
               echo $ex, PHP_EOL;
            } else {
               Tpl::output('msg', $ex->getMessage());
               print_r($ex->getMessage());exit();
            }
         } */
		 
      } else {
         return $this->showDlogMessage('创建充值订单失败');
      }
   }

/*    private function _get_payment_api() {
      $inc_file = BASE_PATH . DS . 'api' . DS . 'payment' . DS . 'wxpay_jsapi' . DS . 'wxpay_jsapi.php';

      if (is_file($inc_file)) {
         require $inc_file;
      }

      $payment_api = new wxpay_jsapi();

      return $payment_api;
   } */

/*    public function notifyOp() {

      // wxpay_jsapi
      if (1) {
         $api = $this->_get_payment_api();
         //$params = $this->_get_payment_config();
         //$api->setConfigs($params);

         list($result, $output) = $api->notify();

         if ($result) {
            $internalSn = $result['out_trade_no'] . '_' . $result['attach'];
            $paySn      = $result['out_trade_no'];
            $externalSn = $result['transaction_id'];
            $up                      = array();
            $up['pdr_trade_sn']      = $externalSn;
            $up['pdr_payment_state'] = 1;
            $up['pdr_payment_time']  = time();
            $updateSuccess           = Model()->table('pts_recharge')->where(array('pdr_sn' => $paySn))->update($up);
            if (!$updateSuccess) {
               // @todo
               // 直接退出 等待下次通知
               exit;
            } else {
               //$points_model = Model('points');
               //$points_model->savePointsLog('points_cash',array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],'pl_points'=>(0-$pts)));
               $r            = Model()->table('pts_recharge')->where(array('pdr_sn' => $paySn))->find();
               $pts          = intval($r['pdr_pts']);
               $memo         = ('积分充值：' . $pts . '；支付人民币：' . $r['pdr_amount'] . '元');
               $points_model = Model('points');
               $points_model->savePointsLog('points_pull', array('pl_desc' => $memo, 'pl_memberid' => $_SESSION['member_id'], 'pl_membername' => $_SESSION['member_name'], 'pl_points' => $pts));
            }
         }

         echo $output;
         exit;
      }
   } */

   //
   //生成订单
   public function inOrdersOp() {
      $groupid  = isset($_POST['gid']) ? intval($_POST['gid']) : intval($_GET['gid']);
      $buyCount = intval($_POST['b_c']) ? intval($_POST['b_c']) : intval($_GET['b_c']);
      if (0 == $groupid || 0 == $buyCount || '' == $_SESSION['member_id']) {
         $this->showDlogMessage('系统错误！');
      }
      $psn = trim($_GET['pay_sn']);

      if ($psn) {
         $up                      = array();
         $up['pdr_trade_sn']      = $psn;
         $up['pdr_payment_state'] = 1;
         $up['pdr_payment_time']  = time();
         $updateSuccess           = Model()->table('pts_recharge')->where(array('pdr_sn' => $psn))->update($up);
         //
         $rr           = Model()->table('pts_recharge')->where(array('pdr_sn' => $psn))->find();
         $ppts         = intval($rr['pdr_pts']);
         $mmemo        = ('积分充值：' . $ppts . '；支付人民币：' . $rr['pdr_amount'] . '元');
         $points_model = Model('points');
         $points_model->savePointsLog('points_pull', array('pl_desc' => $mmemo, 'pl_memberid' => $_SESSION['member_id'], 'pl_membername' => $_SESSION['member_name'], 'pl_points' => $ppts));
      }
      //
      $model_groupbuy = Model('groupbuy');

      $condition                = array();
      $condition['groupbuy_id'] = $groupid;
      $groupbuy                 = $model_groupbuy->getGroupbuyInfo($condition);
      $goods_id                 = $goods_id ? $goods_id : intval($groupbuy['goods_id']);
      $g_type                   = (intval($groupbuy['vr_class_id']) == 5 ? 5 : 1);
      $chae                     = intval($groupbuy['total_quantity']) - intval($groupbuy['buy_quantity']);
      if ($buyCount > $chae) {
         $this->showDlogMessage('库存不足');
      }
      $model_goods  = Model('goods');
      $goods_detail = $model_goods->getGoodsDetail($goods_id);
      $goods_info   = $goods_detail['goods_info'];
      if (empty($goods_info)) {
         $this->showDlogMessage('信息错误');
      }
      $setting      = Model('setting');
      $l            = $setting->getListSetting();
      $dhl          = intval($l['points_dhl']);
      $pts          = intval($g_type * $buyCount * $dhl);
      $model_member = Model('member');
      $minf         = $model_member->getMemberInfo(array('member_id' => $_SESSION['member_id']));
      if ($pts > intval($minf['member_points'])) {
         return $this->showDlogMessage('非法操作', BASE_SITE_URL . "/wap_shop/index.php?act=duobao", 2000);
      }
      if (intval($minf['member_points']) >= intval($g_type * $buyCount * $dhl)) {
         Tpl::output('points_flag', '1');
      } else {
         Tpl::output('points_flag', '0');
         Tpl::output('points_chae', (intval($g_type * $buyCount * $dhl) - intval($minf['member_points'])));
      }
      $model_order            = Model('order');
      $order                  = array();
      $order_goods            = array();
      $t                      = time();
      $t                      = $t . rand(100, 999);
      $order['order_sn']      = $t;
      $order['pay_sn']        = $t;
      $order['store_id']      = $groupbuy['store_id'];
      $order['store_name']    = $groupbuy['store_name'];
      $order['buyer_id']      = $_SESSION['member_id'];
      $order['buyer_name']    = $_SESSION['member_name'];
      $order['buyer_email']   = $minf['member_email'];
      $order['add_time']      = TIMESTAMP;
      $order['payment_code']  = 'points_cash';
      $order['order_state']   = 20;
      $order['order_amount']  = $pts;
      $order['shipping_fee']  = 0;
      $order['goods_amount']  = $buyCount;
      $order['order_from']    = $g_type;
      $ipp                    = getIp();
      $ipp                    = $ipp ? $ipp : $_SERVER['HTTP_REMOTE_HOST'];
      $ipxx                   = getAreaByIp($ipp);
      $order['shipping_code'] = ($ipxx . 'Ip:' . $ipp);
      $order['gr_id']         = $groupbuy['groupbuy_id'];
      $order['code']          = date('Y-m-d', time());
      $c                      = date('mdHi', $t);
      $order['gr_code']       = $c;
      $castr                  = '';
      if ($buyCount > 1) {
         $buyCCount = ($buyCount - 1);
         $c_a       = array();
         while (1) {
            if (0 == $buyCCount) {
               $castr = implode(',', $c_a);
               break;
            } else {
               $c_a[] = rand(10000000, 99999999);
               $buyCCount -= 1;
            }
         }
         $order['gr_code2'] = $castr;
      }
      $model_order->execute('LOCK TABLES `az_order` WRITE');
      $order_id = $model_order->addOrder($order);
      $model_order->execute('UNLOCK TABLES');
      if (empty($order_id)) {
         return $this->showDlogMessage('创建订单失败。');
      }
      //
      $order_goods[0]['order_id']    = $order_id;
      $order_goods[0]['goods_id']    = $groupbuy['goods_id'];
      $order_goods[0]['store_id']    = $groupbuy['store_id'];
      $order_goods[0]['goods_name']  = $groupbuy['goods_name'];
      $order_goods[0]['goods_price'] = $goods_info['goods_price'];
      $order_goods[0]['goods_num']   = $buyCount;
      $order_goods[0]['goods_image'] = $goods_info['goods_image'];
      $order_goods[0]['buyer_id']    = $_SESSION['member_id'];
      //
      $order_goods[0]['goods_type']    = $g_type;
      $order_goods[0]['promotions_id'] = 0;
      $order_goods[0]['commis_rate']   = 0;
      $order_goods[0]['gc_id']         = $goods_info['gc_id'];
      //计算商品金额
      $goods_total                       = $buyCount;
      $order_goods[0]['goods_pay_price'] = $g_type * $buyCount;
      $insert                            = $model_order->addOrderGoods($order_goods);

      if ($insert) {
         $buygrp                   = intval($groupbuy['buy_quantity']) + $buyCount;
         $totalgrp                 = intval($groupbuy['total_quantity']);
         $condition                = array();
         $condition['groupbuy_id'] = $groupid;

         $model_order->execute('LOCK TABLES `az_groupbuy` WRITE');
         $ret = $model_groupbuy->editGroupbuy(array('buy_quantity' => $buygrp), $condition);
         $model_order->execute('UNLOCK TABLES');

         if ($ret) {
            if ($buygrp == $totalgrp) {
               //开始处理新的
               $qt = intval($groupbuy['qishu_total']);
               $qc = intval($groupbuy['qishu_curr']);
               if ($qc < $qt) {
                  $qc  = $qc + 1;
                  $ct  = time();
                  $new = array();
                  if (intval($groupbuy['parent_id']) == 0) {
                     $new['parent_id'] = $groupbuy['groupbuy_id'];
                  } else {
                     $new['parent_id'] = $groupbuy['parent_id'];
                  }
                  $new['groupbuy_name']    = $groupbuy['groupbuy_name'];
                  $new['start_time']       = $ct;
                  $new['end_time']         = 0;
                  $new['gend_time']        = 0;
                  $new['group_shixian']    = 0;
                  $new['qishu_total']      = $groupbuy['qishu_total'];
                  $new['qishu_curr']       = $qc;
                  $new['goods_id']         = $groupbuy['goods_id'];
                  $new['goods_name']       = $groupbuy['goods_name'];
                  $new['store_id']         = $groupbuy['store_id'];
                  $new['store_name']       = $groupbuy['store_name'];
                  $new['goods_price']      = $groupbuy['goods_price'];
                  $new['groupbuy_price']   = $groupbuy['groupbuy_price'];
                  $new['groupbuy_rebate']  = $groupbuy['groupbuy_rebate'];
                  $new['virtual_quantity'] = 0;
                  $new['upper_limit']      = $groupbuy['upper_limit'];
                  $new['buyer_count']      = 0;
                  $new['total_quantity']   = $groupbuy['total_quantity'];
                  $new['buy_quantity']     = 0;
                  $new['groupbuy_intro']   = $groupbuy['groupbuy_intro'];
                  $new['state']            = 20;
                  $new['recommended']      = $groupbuy['recommended'];
                  $new['views']            = 0;
                  $new['vr_class_id']      = $groupbuy['vr_class_id'];
                  $new['class_id']         = $groupbuy['class_id'];
                  $new['s_class_id']       = $groupbuy['s_class_id'];
                  $new['groupbuy_image']   = $groupbuy['groupbuy_image'];
                  $new['groupbuy_image1']  = $groupbuy['groupbuy_image1'];
                  $new['is_vr']            = $groupbuy['is_vr'];
                  $new['remark']           = '第' . $qc . '期/总' . $groupbuy['qishu_total'] . '期';
                  $ret                     = $model_groupbuy->addGroupbuy($new);

               }
               //
               if ($ret) {
                  $ugp              = array();
                  $ugp_id           = $groupbuy['groupbuy_id'];
                  $ugp['gend_time'] = time();
                  $ugp['state']     = 32;
                  //
                  $cond          = array();
                  $cond['gr_id'] = $groupid;
                  if (!empty($gol)) {
                     $gostr = "";
                     $tot   = count($gol);
                     foreach ($gol as $key => $vv) {
                        $gostr .= ($tot . '_' . $vv['order_sn'] . '_' . $vv['buyer_name'] . '_' . $vv['gr_code'] . ',');
                     }
                  }
                  $gostr          = substr($gostr, 0, strlen($gostr) - 1);
                  $gocode         = explode(',', $gostr);
                  $kj             = rand(1, count($gocode));
                  $kjstr          = $gocode[intval($kj) - 1];
                  $ugp['go_code'] = $kjstr;
                  $ret            = $model_groupbuy->editGroupbuy($ugp, array('groupbuy_id' => $groupid));
               }
            }
            //kouchu jf
            $points_model = Model('points');
            $points_model->savePointsLog('points_cash', array('pl_memberid' => $_SESSION['member_id'], 'pl_membername' => $_SESSION['member_name'], 'pl_points' => (-$pts)));

         }
      } else {
         return $this->showDlogMessage('创建订单失败');
      }
      $this->finishBuyOp($groupid);
   }

   //支付完成跳转页面
   public function finishBuyOp($groupid) {
      $groupid                    = intval($groupid);
      $_SESSION['last_order_gid'] = $groupid;
      Tpl::output('gr_id', $groupid);
      Tpl::showpage('yydb.goods.finish', 'null_layout');
   }
   //领奖
   public function getPrizeOp() {
      $groupid = intval($_GET['gr_id']);
      Tpl::output('gpid', $groupid);
      //
      Tpl::showpage('yydb.prize', 'null_layout');
   }

   //
   public function setPrizeInfOp() {
      $model_groupbuy = Model('groupbuy');
      $groupid        = intval($_POST['gid']);
      //
      $data           = array();
      $data['name']   = trim($_POST['name']);
      $data['type']   = trim($_POST['type']);
      $data['num']    = trim($_POST['num']);
      $data['phone']  = trim($_POST['tel']);
      $data['liuyan'] = trim($_POST['liuyan']);

      $dataStr = serialize($data);

      //
      $condition                = array();
      $condition['groupbuy_id'] = $groupid;
      $ret                      = $model_groupbuy->editGroupbuy(array('prize_inf' => $dataStr), $condition);
      if ($ret) {
         echo '1';
      }
      //
   }

   public function wxbackOp() {
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxcallback.php';
   }

   public function wxloginOp() {
      session_start();
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxlogin.php';
   }

   public function wxuloginOp() {
      session_start();
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxulogin.php';
   }

   public function wxubackOp() {
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxucallback.php';
   }

   //
   public function wxdbackOp() {
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxcallbackd.php';
   }

   public function wxdloginOp() {
      session_start();
      include dirname(__FILE__) . '/' . '../api/wxlogin/oauth/wxlogind.php';
   }
   //显示错误跳转
   public function showDlogMessage($message, $url, $time = 2000) {
      $error = !empty($message) ? $message : '系统错误！';
      $url   = !empty($url) ? $url : '/wap_shop/index.php?act=duobao';
      Tpl::output('time', $time);
      Tpl::output('url', $url);
      Tpl::output('message', $error);
      foreach ($args as $key => $value) {
         Tpl::output($key, $value);
      }
      Tpl::showpage('error.message', 'null_layout');
      exit();
   }
}

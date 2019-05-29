<?php
/**
 * 虚拟商品购买行为
 *

 */
defined('InShopNC') or exit('Access Invalid!');
class buy_virtualLogic {

   /**
    * 虚拟商品购买第一步，得到购买数据(商品、店铺、会员)
    * @param int $goods_id 商品ID
    * @param int $quantity 购买数量
    * @param int $member_id 会员ID
    * @return array
    */
   public function getBuyStep1Data($goods_id, $quantity, $member_id, $calendar_array, $calendar_type) {
      return $this->getBuyStepData($goods_id, $quantity, $member_id, $calendar_array, $calendar_type);
   }

   /**
    * 虚拟商品购买第二步，得到购买数据(商品、店铺、会员)
    * @param int $goods_id 商品ID
    * @param int $quantity 购买数量
    * @param int $member_id 会员ID
    * @return array
    */
   public function getBuyStep2Data($goods_id, $quantity, $member_id) {
      return $this->getBuyStepData($goods_id, $quantity, $member_id);
   }

   /**
    * 得到虚拟商品购买数据(商品、店铺、会员)
    * @param int $goods_id 商品ID
    * @param int $quantity 购买数量
    * @param int $member_id 会员ID
    * @param int $calendar_date 日历商品数据
    * @param int $calendar_type 日历类型
    * @return array
    */
   public function getBuyStepData($goods_id, $quantity, $member_id, $calendar_array = '', $calendar_type = '') {
      $goods_info = Model('goods')->getVirtualGoodsOnlineInfoByID($goods_id);
      if (empty($goods_info)) {
         return callback(false, '该商品不符合购买条件，可能的原因有：下架、不存在、过期等');
      }
      $goods_common = Model('goods')->getGoodCommonsList(array('goods_commonid' => $goods_info['goods_commonid']));
      if (1 == $_SESSION['share_shop'] and 1 == $goods_info['isshare']) {
         if (0 == intval($calendar_type)) {
            $goods_info['goods_storage'] = $goods_info['share_stock'];
            $goods_info['goods_price']   = $goods_info['share_price'];
         }
      }
      if (intval($calendar_type) == 4) {
         $goods_info['departure_time'] = $goods_common['departure_time'];
      }

      if ($goods_info['virtual_limit'] > $goods_info['goods_storage']) {
         $goods_info['virtual_limit'] = $goods_info['goods_storage'];
      }

      //取得抢购信息
      $goods_info = $this->_getGroupbuyInfo($goods_info);
      $quantity   = abs(intval($quantity)); //获取绝对值
      $quantity   = 0 == $quantity ? 1 : $quantity;
      $quantity   = $quantity > $goods_info['virtual_limit'] ? $goods_info['virtual_limit'] : $quantity;

      $goods_info['quantity']        = $quantity;
      $goods_info['goods_total']     = ncPriceFormat($goods_info['goods_price'] * $goods_info['quantity']);
      $goods_info['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $goods_info['store_id']);
      //价格日历类型的商品
      if ($calendar_array && $calendar_type) {
         if (1 == $calendar_type) { //普通门票、线路
            $calendar_array = explode(',', $calendar_array);
            $start_time     = trim($calendar_array[0]); //出发时间
            $man_price      = $calendar_array[1]; //成人价
            $child_price    = $calendar_array[2]; //儿童价
            $spec_name      = $calendar_array[3]; //套餐
            $diff_price     = $calendar_array[4]; //单房差
            $year_m         = substr($start_time, 0, 7); //2016-10
            if (-1 == $spec_name) { //没有套餐、规格
               $stock = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], "date" => $year_m))->field('stock_info,date')->find();
            } else {
               $stock = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], 'package' => $spec_name, "date" => $year_m))->field('stock_info,date')->find();
            }

            if ($stock) {
               $goods_info['ticket_type'] = array();
               $stock                     = json_decode($stock['stock_info'], true); //转换成数组
               $input_names               = array('man_price' => '成人价格', 'child_price' => '儿童价格', 'diff_price' => '单房差');
               $price_array               = array('man_price' => $man_price, 'child_price' => $child_price, 'diff_price' => $diff_price);
               $i                         = intval(substr($start_time, -2));
               $price                     = 0; //价格初始化
               foreach ($price_array as $key => $item) {
                  if ('-1' == $item) {
                     unset($price_array[$key]);
                     unset($input_names[$key]);
                  } else {
                     $str = "<input type='hidden' name='putong_" . $key . "' value='" . $stock[$i][$key] . "' />";
                     $str .= "<p class='ui-nowrap'>$input_names[$key]：￥<font class='qz-color2'>" . $stock[$i][$key] . "</font></p>";
                     array_push($goods_info['ticket_type'], $str); //入栈
                     $price += floatval($stock[$i][$key]); //总价
                  }
               }
               $goods_info['storage_state'] = floatval($stock[$i]['man_stock']) + floatval($stock[$i]['child_stock']);
               $goods_info['start_time']    = $stock[$i]['date']; //出发时间
               $goods_info['state']         = true;
               $goods_info['goods_num']     = $goods_info['quantity'];
               $goods_info['price_total']   = $price * intval($goods_info['goods_num']);
               $goods_info['calendar_type'] = 1;
               $goods_info['goods_price']   = $goods_info['price_total']; //重置商品价格
            } else {
               $goods_info['state'] = false;
               $goods_info['msg']   = $start_time . '时段已经被购买！';
            }
         } elseif (2 == $calendar_type) { //酒店日历 2016-11-01,2016-11-02,1,300,'单房差'"
            $calendar_array = explode(',', $calendar_array);

            $start_time  = strtotime($calendar_array[0]); //开始入住时间戳
            $end_time    = strtotime($calendar_array[1]); //离店时间戳
            $hotel_num   = $calendar_array[2]; //入住间数
            $hotel_price = $calendar_array[3]; //单价

            $spec_name_val = empty($calendar_array[4]) ? " " : $calendar_array[4]; //套餐
            if (!empty($spec_name_val)) {
               $goods_info['input_spec'] = trim($spec_name_val); //套餐类型
            }

            $hotel_start_time = substr($calendar_array[0], 0, 7); //入住年月
            $hotel_end_time   = substr($calendar_array[1], 0, 7); //离店年月

            if (strtotime($hotel_start_time) == strtotime($hotel_end_time)) {
               $stock = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], "date" => substr($calendar_array[0], 0, 7), 'package' => $spec_name_val))->field('stock_info,date,package')->select();

            } elseif (strtotime($hotel_start_time) < strtotime($hotel_end_time)) { //跨月份
               $stock = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], "date" => array("between", "{$hotel_start_time},{$hotel_end_time}"), 'package' => $spec_name_val))->field('stock_info,date')->select();

            }

            if (strtotime($hotel_start_time) < strtotime($hotel_end_time)) {

               $stock = array_merge(json_decode($stock[0]['stock_info'], true), json_decode($stock[1]['stock_info'], true));

            } elseif (strtotime($hotel_start_time) == strtotime($hotel_end_time)) {
               $stock = json_decode($stock[0]['stock_info'], true); //转换成数组
            }
            $goods_info['goods_price'] = null;

            if ($stock) {
               foreach ($stock as $k => $val) {
                  $mktime = strtotime($val['date']);
                  if ($mktime == $start_time) {
                     $goods_info['hotel_state_time'] = $val['date']; //入住时间
                  }
                  if ($mktime == $end_time) {
                     $goods_info['hotel_end_time'] = $val['date']; //离店时间
                  }
                  if ($mktime > $start_time && $mktime <= $end_time) {
                     $goods_info['hotel_total']   = floatval($goods_info['hotel_total']) + floatval($val['man_price']); //总价
                     $goods_info['storage_state'] = floatval($goods_info['storage_state']) + floatval($val['man_stock']); //库存
                  }
               }
               $goods_info['state'] = true;
            } else {
               $goods_info['state'] = false;
               $goods_info['msg']   = '时段已经被购买了！';
            }

            $goods_info['hotel_num']     = abs(intval($calendar_array[2])); //一共住几天晚上
            $goods_info['calendar_type'] = 2;
         } elseif (3 == $calendar_type) { //高尔夫日历
            $date     = explode(' ', $calendar_array);
            $_date    = $date[0];
            $_time    = explode(':', $date[1]);
            $_hours   = intval($_time[0]);
            $_minutes = $_time[1];
            $stock    = Model()->table('golf_stock')->where(array('commonid' => $goods_info['goods_commonid'], 'date' => $_date))->find();
            $stock    = unserialize($stock['stock_info']);
            if (0 != $stock[$_hours][$_minutes]['stock'] && 2 != $stock[$_hours][$_minutes]['stock']) { //有库存并且没有被预定，支付成功以后库存减1;
               if (!Model()->table('golf_order')->where("goods_id = {$goods_info['goods_id']} AND date = '{$_date}' AND hours = $_hours AND minutes = $_minutes")->find()) {
                  $goods_info['calendar_type'] = 3;
                  $goods_info['goods_num']     = 1; //购买1
                  $goods_info['start_time']    = $calendar_array;
                  $goods_info['state']         = true;
               } else {
                  $goods_info['state'] = false;
                  $goods_info['msg']   = $calendar_array . '时段已经被购买！';
               }
            } else {
               $goods_info['state'] = false;
               $goods_info['msg']   = $calendar_array . '时段已经被预定了！';
            }
            $goods_info['calendar_type'] = 3;
         }
      }

      $return                = array();
      $return['goods_info']  = $goods_info;
      $return['store_info']  = Model('store')->getStoreOnlineInfoByID($goods_info['store_id'], 'store_name,store_id,member_id');
      $return['member_info'] = Model('member')->getMemberInfoByID($member_id);
      return callback(true, '', $return);
   }

   /**
    * 虚拟商品购买第三步
    * @param array $post 接收POST数据，必须传入goods_id:商品ID，quantity:购买数量,buyer_phone:接收手机,buyer_msg:买家留言
    * @param int $member_id
    * @return array
    */
   public function buyStep3($post, $member_id) {
      if (2 == $post['calendar_type']) {
         $calendar_array = $post['calendar_array'];
      }
      $result = $this->getBuyStepData($post['goods_id'], $post['quantity'], $member_id, $calendar_array, $post['calendar_type']);
      if (!$result['state']) {
         return $result;
      }
      $goods_info = $result['data']['goods_info'];

      $member_info = $result['data']['member_info'];
//         echo '<pre>';var_dump($goods_info);exit;
      //应付总金额计算
      $pay_total = $post['goods_total'];
      if ($post['voucher']) {
         $buy_1_logic        = Logic('buy_1');
         $store_voucher_list = $buy_1_logic->getStoreAvailableVoucherList([$result['data']['store_info']['store_id'] => $pay_total], $_SESSION['member_id']);
         list($voucher_id)   = explode('|', $post['voucher']);
         if (count($store_voucher_list[$result['data']['store_info']['store_id']]) and !empty($store_voucher_list[$result['data']['store_info']['store_id']][$voucher_id])) {
            $pay_total -= floatval($store_voucher_list[$result['data']['store_info']['store_id']][$voucher_id]['voucher_price']);
         }
      }
      // $pay_total = $goods_info['goods_price'] * $goods_info['quantity'];
      $store_id               = $goods_info['store_id'];
      $store_goods_total_list = array($store_id => $pay_total);

      $input['order_from'] = $post['order_from'];
      $input['is_share']   = $post['is_share'];
      //整理数据
      $input                = array();
      $input['pick_date']   = strtotime($post['pick_date']);
      $input['quantity']    = $goods_info['quantity'];
      $input['buyer_phone'] = $post['buyer_phone'];
      $input['buyer_msg']   = $post['buyer_msg'];
      $input['pay_total']   = $pay_total; //总价
      $input['order_from']  = $post['order_from'];
      if (in_array(intval($post['calendar_type']), array(1, 2, 3))) {
         $input['calendar_type'] = $post['calendar_type']; //日历模型
      }
      if (2 == $post['calendar_type']) {
         $input['buyer_hotel_name']   = $post['buyer_hotel_name'];
         $input['buyer_id_card']      = $post["buyer_id_card"];
         $input['pay_total']          = decrypt($post['goods_total']); //总价
         $goods_info['calendar_type'] = $post['calendar_type'];
         $goods_info['ticket_date']   = $post['hotel_state_time'] . " " . $post['hotel_end_time']; //预订的时间，开始时间和离店时间
      }
      if (intval($post['calendar_type']) == 4) {
         $goods_info['calendar_type'] = $post['calendar_type'];
         $goods_info['ticket_date']   = $post['ticket_date'];
         $input['calendar_type']      = $post['calendar_type'];
         $input['ticket_date']        = $post['ticket_date'];
      }
      //-----新增HOME---------
      $input['goods_name']  = $post['goods_name'];
      $input['goods_price'] = $post['goods_price'];
      $input['get_info']    = decrypt($post['get_info']); //订单详细
      //--------------
      $goods_info['goods_name']  = $input['goods_name']; //订单名称
      $goods_info['goods_price'] = trim($input['goods_price']); //单价
      //-------新增END-------------
      try {

         $model_goods = Model('goods');
         //开始事务
         $model_goods->beginTransaction();

         //生成订单
         //                  print_r($input);exit;
         //         print_r($goods_info);exit;
         $order_info = $this->_createOrder($input, $goods_info, $member_info);

         $pay_state = 0; //支付标识

         if (!empty($post['password'])) {
            if ('' != $member_info['member_paypwd'] && md5($post['password']) == $member_info['member_paypwd']) {
               //充值卡支付
               if (!empty($post['rcb_pay'])) {
                  $order_info = $this->_rcbPay($order_info, $post, $member_info);
                  $pay_state  = 1;
               }
               //预存款支付
               if (!empty($post['pd_pay'])) {
                  $this->_pdPay($order_info, $post, $member_info);
                  $pay_state = 2;

               }
            }
         }
         Model()->table('voucher')->where(['voucher_id' => $store_voucher_list[$result['data']['store_info']['store_id']][$voucher_id]['voucher_id']])->update(['voucher_state' => 0, 'voucher_order_id' => $order_info['order_id']]);
         //提交事务
         $model_goods->commit();

      } catch (Exception $e) {

         //回滚事务
         $model_goods->rollback();
         return callback(false, $e->getMessage());
      }
      //变更库存和销量
      $update_order['goods_id']      = $goods_info['goods_id'];
      $update_order['quantity']      = $goods_info['quantity'];
      $update_order['calendar_type'] = intval($post['calendar_type']);
      $update_order['ticket_date']   = $post['ticket_date'];
      if (in_array($post['calendar_type'], array(1, 2, 3))) { //拍下减库存
         //更新价格日历类型库存表的库存和goods表中的销量
         $this->up_sale(array('goods_id' => $goods_info['goods_id'], 'quantity' => $goods_info['quantity'])); //更新销量
         $this->up_stock($input['get_info']);
      } elseif($post['is_share']==1){
         $model_goods = Model('goods');
         $data = array();
         $data['share_stock'] = array('exp','share_stock-'.$goods_info['quantity']);
         $data['goods_salenum'] = array('exp','goods_salenum+'.$goods_info['quantity']);
         $result = $model_goods->editGoodsById($data, $goods_info['goods_id']);
      } else { //其他的按照原来更新库存
         QueueClient::push('createOrderUpdateStorage', $update_order);
      }
      //更新抢购信息
      $this->_updateGroupBuy($goods_info);
      //发送兑换码到手机
      $param = array('goods_name' => $order_info['goods_name'], 'order_id' => $order_info['order_id'], 'buyer_id' => $member_id, 'buyer_phone' => $order_info['buyer_phone']);
      QueueClient::push('sendVrCode', $param);

      return callback(true, '', array('order_id' => $order_info['order_id']));
   }
   //酒店类型 在线方式付款  预存款或充值卡
   public function online_pay($post, $member_id) {
      if (!empty($member_id) && !empty($post) && !empty($post['order_id'])) {
         try {
            $model_vr_order = Model('vr_order');
            $member_model   = Model('member');
            $model_vr_order->beginTransaction();
            $member_info = $member_model->getMemberInfo(array('member_id' => $member_id));
            $order_id    = trim($post['order_id']);
            //取订单信息
            $condition             = array();
            $condition['order_id'] = $order_id;
            $order_info            = $model_vr_order->getOrderInfo($condition, '*', true);
            if (empty($order_info) || !in_array($order_info['order_state'], array(ORDER_STATE_NEW, ORDER_STATE_PAY))) {
               showMessage('未找到需要支付的订单', 'index.php?act=member_order', 'html', 'error');
            }
            if (!empty($post['password'])) {
               if ('' != $member_info['member_paypwd'] && md5(trim($post['password'])) == $member_info['member_paypwd']) {
                  //充值卡支付
                  if (trim($post['payment_code']) == 'rcb_pay') {
                     $order_info = $this->_rcbPay($order_info, $post, $member_info);
                  }
                  //预存款支付
                  if (trim($post['payment_code']) == 'pd_pay') {
                     $this->_pdPay($order_info, $post, $member_info);
                  }
               }
            }
            //提交事务
            $model_vr_order->commit();

         } catch (Exception $e) {
            //回滚事务
            $model_vr_order->rollback();
            return callback(false, $e->getMessage());
         }
      } else {
         showMessage('系统繁忙，稍后再试', 'index.php?act=member_vr_order', 'html', 'error');
      }
      //发送兑换码到手机
      $param = array('goods_name' => $order_info['goods_name'], 'order_id' => $order_info['order_id'], 'buyer_id' => $member_id, 'buyer_phone' => $order_info['buyer_phone']);
      QueueClient::push('sendVrCode', $param);
      return callback(true, '', array('order_id' => $order_info['order_id']));
   }
   /**
    * 生成订单
    * @param array $input 表单数据
    * @param unknown $goods_info 商品数据
    * @param unknown $member_info 会员数据
    * @throws Exception
    * @return array
    */
   private function _createOrder($input, $goods_info, $member_info) {

      extract($input);
      $model_vr_order = Model('vr_order');

      //存储生成的订单,函数会返回该数组
      $order_list = array();
      $order      = array();
      $order_code = array();
      if ($_SESSION['share_shop']) {
         $goods_info['goods_price'] = $goods_info['share_price'];
         #计算佣金
         if (1 == $goods_info['isshare']) {
            $share_settings = Model('share_settings')->where(array('store_id' => $goods_info['store_id']))->find();
            if ($goods_info['is_re']) {
               $share_settings['re_price_1'] = $goods_info['re_price_1'];
               $share_settings['re_price_2'] = $goods_info['re_price_2'];
               $share_settings['re_price_3'] = $goods_info['re_price_3'];
            }
            switch ($share_settings['re_type']) {
            case 2:
               $re_price_1 = $input['quantity'] * $share_settings['re_price_1'];
               $re_price_2 = $input['quantity'] * $share_settings['re_price_2'];
               $re_price_3 = $input['quantity'] * $share_settings['re_price_3'];
               break;
            case 1:
            default:
               $re_price_1 = $goods_info['goods_price'] * $input['quantity'] * $share_settings['re_price_1'] * .01;
               $re_price_2 = $goods_info['goods_price'] * $input['quantity'] * $share_settings['re_price_2'] * .01;
               $re_price_3 = $goods_info['goods_price'] * $input['quantity'] * $share_settings['re_price_3'] * .01;
               break;
            }
            $order['re_price_1'] = sprintf('%.2f', $re_price_1);
            $order['re_price_2'] = sprintf('%.2f', $re_price_2);
            $order['re_price_3'] = sprintf('%.2f', $re_price_3);
            #获取上级信息
            $wx_model = Model('share_member');
            $wx_info  = $wx_model->where(array('member_id' => $_SESSION['member_id']))->find();
            if ($wx_info['pid']) {
               $re_info           = $wx_model->where(array('member_id' => $wx_info['pid']))->find();
               $order['re_mid_1'] = $re_info['member_id'];
               if ($re_info['pid']) {
                  $re_info_2         = $wx_model->where(array('member_id' => $re_info['pid']))->find();
                  $order['re_mid_2'] = $re_info_2['member_id'];
                  if ($re_info['pid']) {
                     $re_info_3         = $wx_model->where(array('member_id' => $re_info_2['pid']))->find();
                     $order['re_mid_3'] = $re_info_3['member_id'];
                  } else {
                     $order['re_price_3'] = 0;
                     $order['re_mid_3']   = 0;
                  }
               } else {
                  $order['re_price_2'] = 0;
                  $order['re_price_3'] = 0;
                  $order['re_mid_2']   = 0;
                  $order['re_mid_3']   = 0;
               }
            } else {
               $order['re_price_1'] = 0;
               $order['re_price_2'] = 0;
               $order['re_price_3'] = 0;
               $order['re_mid_1']   = 0;
               $order['re_mid_2']   = 0;
               $order['re_mid_3']   = 0;
            }
            $order['is_share']  = 1;
            $order['re_status'] = 0;
         }
      }
      if ($order['re_mid_1'] <= 0) {
         $order['re_price_1'] = 0;
      }
      if ($order['re_mid_2'] <= 0) {
         $order['re_price_2'] = 0;
      }
      if ($order['re_mid_3'] <= 0) {
         $order['re_price_3'] = 0;
      }

      $order['order_sn']         = $this->_makeOrderSn($member_info['member_id']);
      $order['store_id']         = $goods_info['store_id'];
      $order['store_name']       = $goods_info['store_name'];
      $order['buyer_id']         = $member_info['member_id'];
      $order['buyer_name']       = $member_info['member_name'];
      $order['buyer_hotel_name'] = $input['buyer_hotel_name'];
      $order['buyer_phone']      = $input['buyer_phone'];
      $order['buyer_msg']        = $input['buyer_msg'];
      $order['buyer_id_card']    = isset($input['buyer_id_card']) ? $input['buyer_id_card'] : 0;
      $order['add_time']         = TIMESTAMP;
      $order['order_state']      = ORDER_STATE_NEW; //已经产生单未支付
      $order['order_amount']     = $input['pay_total']; //$pay_total;
      $order['goods_id']         = $goods_info['goods_id'];

      $order['goods_name']        = $goods_info['goods_name'];
      $order['goods_price']       = $goods_info['goods_price'];
      $order['goods_num']         = $input['quantity'];
      $order['goods_image']       = $goods_info['goods_image'];
      $order['commis_rate']       = 200;
      $order['gc_id']             = $goods_info['gc_id'];
      $order['vr_indate']         = $goods_info['virtual_indate'];
      $order['vr_invalid_refund'] = $goods_info['virtual_invalid_refund'];
      $order['order_from']        = $input['order_from'];
      $order['order_info']        = serialize(json_decode($input['get_info'], true)); //订单详细
      $order['pick_date']         = $input['pick_date'];
      if (4 == $goods_info['calendar_type']) {
         $order['ticket_date'] = $goods_info['ticket_date'] . ' ' . $goods_info['departure_time'];
      } elseif (2 == $goods_info['calendar_type']) {
         $order['ticket_date'] = $goods_info['ticket_date'];
      }
      //订单标识 0为普通 1门票 2酒店 3高尔夫 4车票
      $order['order_station_type'] = isset($goods_info['calendar_type']) ? intval($goods_info['calendar_type']) : 0;
      if (1 == $goods_info['ifgroupbuy']) {
         $order['order_promotion_type'] = 1;
         $order['promotions_id']        = $goods_info['groupbuy_id'];
      }
      /* //高尔夫球购买完成才往高尔夫球订单表中插入
      if (3 == $input['calendar_type']) { //高尔夫订单
      $get_info                   = json_decode($input['get_info'], true);
      $golf_start                 = base64_decode($get_info['rl_data']); //2016-10-25 08:50打球时间段
      $date                       = explode(' ', trim($golf_start)); //日期
      $hours_minute               = explode(':', $date[1]); //日期
      $golf_insert['order_id']    = $order['order_sn']; //订单号
      $golf_insert['goods_id']    = $goods_info['goods_id']; //商品id
      $golf_insert['common_id']   = $get_info['commonid']; //商品公共id
      $golf_insert['date']        = $date[0]; //时间
      $golf_insert['hours']       = $hours_minute[0];
      $golf_insert['minutes']     = $hours_minute[1];
      $golf_insert['buy_time']    = TIMESTAMP; //下单时间
      $golf_insert['order_state'] = ORDER_STATE_NEW; //已下单未付款
      $golf_insert_id             = $model_vr_order->addGolfOrder($golf_insert); //订单号
      if (!$golf_insert_id) {
      throw new Exception('高尔夫订单保存失败');
      }

      }*/
      // $sql = "INSERT INTO az_vr_order (`".implode('`,`', array_keys($order))."`) VALUES('".implode("','",$order)."')";
      // echo($sql);exit;
      $order_id = $model_vr_order->addOrder($order);

      if (!$order_id) {
         throw new Exception('订单保存失败');
      }
      $order['order_id'] = $order_id;

      // 提醒[库存报警]
      if ($goods_info['goods_storage_alarm'] >= ($goods_info['goods_storage'] - $input['quantity'])) {
         $param              = array();
         $param['common_id'] = $goods_info['goods_commonid'];
         $param['sku_id']    = $goods_info['goods_id'];
         QueueClient::push('sendStoreMsg', array('code' => 'goods_storage_alarm', 'store_id' => $goods_info['store_id'], 'param' => $param));
      }

      return $order;
   }

   /**
    * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
    * 长度 =2位 + 10位 + 3位 + 3位  = 18位
    * 1000个会员同一微秒提订单，重复机率为1/100
    * @return string
    */
   private function _makeOrderSn($member_id) {
      return mt_rand(10, 99)
      . sprintf('%010d', time() - 946656000)
      . sprintf('%03d', (float) microtime() * 1000)
      . sprintf('%03d', (int) $member_id % 1000);
   }

   /**
    * 更新抢购购买人数和数量
    */
   private function _updateGroupBuy($goods_info) {
      if ($goods_info['ifgroupbuy'] && $goods_info['groupbuy_id']) {
         $groupbuy_info                = array();
         $groupbuy_info['groupbuy_id'] = $goods_info['groupbuy_id'];
         $groupbuy_info['quantity']    = $goods_info['quantity'];
         QueueClient::push('editGroupbuySaleCount', $groupbuy_info);
      }
   }

   /**
    * 充值卡支付
    * 如果充值卡足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
    */
   private function _rcbPay($order_info, $input, $buyer_info) {
      $available_rcb_amount = floatval($buyer_info['available_rc_balance']);

      if ($available_rcb_amount <= 0) {
         return;
      }

      $model_vr_order = Model('vr_order');
      $model_pd       = Model('predeposit');

      $order_amount           = floatval($order_info['order_amount']);
      $data_pd                = array();
      $data_pd['member_id']   = $buyer_info['member_id'];
      $data_pd['member_name'] = $buyer_info['member_name'];
      $data_pd['amount']      = $order_amount;
      $data_pd['order_sn']    = $order_info['order_sn'];

      if ($available_rcb_amount >= $order_amount) {

         // 预存款立即支付，订单支付完成
         $model_pd->changeRcb('order_pay', $data_pd);
         $available_rcb_amount -= $order_amount;

         // 订单状态 置为已支付
         $data_order                 = array();
         $order_info['order_state']  = $data_order['order_state']  = ORDER_STATE_PAY;
         $data_order['payment_time'] = TIMESTAMP;
         $data_order['payment_code'] = 'predeposit';
         $data_order['rcb_amount']   = $order_info['order_amount'];
         $result                     = $model_vr_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
         if (!$result) {
            throw new Exception('订单更新失败');
         }

         //发放兑换码
         $insert = $model_vr_order->addOrderCode($order_info);
         if (!$insert) {
            throw new Exception('兑换码发送失败');
         }

      } else {

         //暂冻结预存款,后面还需要 API彻底完成支付
         $data_pd['amount'] = $available_rcb_amount;
         $model_pd->changeRcb('order_freeze', $data_pd);
         //预存款支付金额保存到订单
         $data_order               = array();
         $order_info['rcb_amount'] = $data_order['rcb_amount'] = $available_rcb_amount;
         $result                   = $model_vr_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
         if (!$result) {
            throw new Exception('订单更新失败');
         }
      }
      return $order_info;
   }

   /**
    * 预存款支付
    * 如果预存款足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
    */
   private function _pdPay($order_info, $input, $buyer_info) {
      if (ORDER_STATE_PAY == $order_info['order_state']) {
         return;
      }

      $available_pd_amount = floatval($buyer_info['available_predeposit']);
      if ($available_pd_amount <= 0) {
         return;
      }

      $model_vr_order = Model('vr_order');
      $model_pd       = Model('predeposit');

      $order_amount           = floatval($order_info['order_amount']) - floatval($order_info['rcb_amount']);
      $data_pd                = array();
      $data_pd['member_id']   = $buyer_info['member_id'];
      $data_pd['member_name'] = $buyer_info['member_name'];
      $data_pd['amount']      = $order_amount;
      $data_pd['order_sn']    = $order_info['order_sn'];

      if ($available_pd_amount >= $order_amount) {

         //预存款立即支付，订单支付完成
         $model_pd->changePd('order_pay', $data_pd);
         $available_pd_amount -= $order_amount;

         //下单，支付被冻结的充值卡
         $pd_amount = floatval($order_info['rcb_amount']);
         if ($pd_amount > 0) {
            $data_pd                = array();
            $data_pd['member_id']   = $buyer_info['member_id'];
            $data_pd['member_name'] = $buyer_info['member_name'];
            $data_pd['amount']      = $pd_amount;
            $data_pd['order_sn']    = $order_info['order_sn'];
            $model_pd->changeRcb('order_comb_pay', $data_pd);
         }

         // 订单状态 置为已支付
         $data_order                 = array();
         $data_order['order_state']  = ORDER_STATE_PAY;
         $data_order['payment_time'] = TIMESTAMP;
         $data_order['payment_code'] = 'predeposit';
         $data_order['pd_amount']    = $order_amount;
         $result                     = $model_vr_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
         if (!$result) {
            throw new Exception('订单更新失败');
         }

         #支付完成，结算佣金 start
         if (1 == $order_info['is_share'] and 0 == $order_info['re_status']) {
            $wx_model   = Model('share_member');
            $cmms_model = Model('share_cmm_log');
            for ($i = 0; $i <= 3; $i++) {
               $re_mid    = $order_info['re_mid_' . $i];
               $re_amount = $order_info['re_price_' . $i];
               if ($re_mid > 0 and $re_amount > 0) {
                  $mem = $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->find();
                  if (1 == $mem['status'] and 1 == $mem['isshare']) {
                     #佣金存入冻结余额中
                     $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->update(array('credits' => array('exp', 'credits+' . $re_amount)));
                     Model()->table('vr_order')->where(array('order_id' => $order_info['order_id']))->update(array('re_status' => 1, 're_time' => TIMESTAMP));
                     $log_data = array(
                        'addtime'       => TIMESTAMP,
                        'openid'        => (string) $mem['openid'],
                        'mid'           => $re_mid,
                        'nickname'      => strval($mem['nickname']),
                        'type'          => '余额',
                        'order_id'      => $order_info['order_id'],
                        'amount'        => $re_amount,
                        'remark'        => '',
                        'order_type'    => '2',
                        'store_id'      => $order_info['store_id'],
                        'order_sn'      => $order_info['order_sn'],
                        'from_mid'      => $order_info['buyer_id'],
                        'from_nickname' => $order_info['buyer_name'],
                     );
                     $cmms_model->insert($log_data);
                  }
               }
            }
         }
         #支付完成，结算佣金 end

         //发放兑换码
         $model_vr_order->addOrderCode($order_info);

      } else {

         //暂冻结预存款,后面还需要 API彻底完成支付
         $data_pd['amount'] = $available_pd_amount;
         $model_pd->changePd('order_freeze', $data_pd);
         //预存款支付金额保存到订单
         $data_order              = array();
         $data_order['pd_amount'] = $available_pd_amount;
         $result                  = $model_vr_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
         if (!$result) {
            throw new Exception('订单更新失败');
         }
      }
   }

   /**
    * 取得抢购信息
    * @param array $goods_info
    * @return array
    */
   private function _getGroupbuyInfo($goods_info = array()) {
      if (!C('groupbuy_allow') || empty($goods_info) || !is_array($goods_info)) {
         return $goods_info;
      }

      $groupbuy_info = Model('groupbuy')->getGroupbuyInfoByGoodsCommonID($goods_info['goods_commonid']);
      if (empty($groupbuy_info)) {
         return $goods_info;
      }

      // 虚拟抢购数量限制
      if ($groupbuy_info['upper_limit'] > 0 && $groupbuy_info['upper_limit'] < $goods_info['virtual_limit']) {
         $goods_info['virtual_limit'] = $groupbuy_info['upper_limit'];
      }
      $goods_info['goods_price'] = $groupbuy_info['groupbuy_price'];
      $goods_info['groupbuy_id'] = $groupbuy_info['groupbuy_id'];
      $goods_info['ifgroupbuy']  = true;

      return $goods_info;
   }
   /**
    * 更新价格日历类型的销量
    * @param  array
    */
   public function up_sale($data) {
      if (!empty($data) && is_array($data)) {
         $a = Model()->table('goods')->where(array('goods_id' => $data['goods_id']))->setInc('goods_salenum', 1);
         return $a;
      } else {
         return false;
      }
   }
   /**
    * 更新价格日历类型库存表的库存
    * @param  $post
    */
   public function up_stock($get_info) {
      $post          = json_decode($get_info, true); //传值数组
      $calendar_type = intval($post["calendar_type"]); //日历类型
      //线路门票

      if (1 == $calendar_type) { //普通门票 string(17) "2016-11-30,-1,100"
         $calendar_data  = base64_decode($post['rl_data']);
         $calendar_array = explode(',', $calendar_data);
         $where_stock    = array('commonid' => $post['commonid'], 'date' => substr($calendar_array[0], 0, 7));
         $stock_info     = Model()->table('stock')->where($where_stock)->field('stock_info')->find();

         $stock_info_arr                = json_decode($stock_info["stock_info"], true);
         $day                           = intval(substr($calendar_array[0], -2));
         $new_stock                     = $stock_info_arr[$day]["stock"] - $post['quantity'];
         $stock_info_arr[$day]["stock"] = $new_stock;
         Model()->table('stock')->where($where_stock)->update(array('stock_info' => json_encode($stock_info_arr)));
      } elseif (2 == $calendar_type) { //酒店类型 string(27) "2016-11-03,2016-11-05,2,200"
         $calendar_data  = base64_decode($post['rl_data']);
         $calendar_array = explode(',', $calendar_data);

         $hotel_start_time = substr($calendar_array[0], 0, 7); //入住年月
         $hotel_end_time   = substr($calendar_array[1], 0, 7); //离店年月
         $hotel_num        = $calendar_array[3]; //入住间数
         $where_stock      = array('commonid' => $post['commonid'], 'date' => $hotel_start_time);
         $stock_info       = Model()->table('stock')->where($where_stock)->field('stock_info')->find();
         $stock_info_arr   = json_decode($stock_info["stock_info"], true);
         $day_num          = abs((strtotime($calendar_array[1]) - strtotime($calendar_array[0])) / 86400); //住几天

         if (1 == $day_num && 1 == $hotel_num) { //住了一天
            $day                           = intval(substr($calendar_array[0], -2));
            $stock_info_arr[$day]["stock"] = $stock_info_arr[$day]["stock"] - 1;
            Model()->table('stock')->where($where_stock)->update(array('stock_info' => json_encode($stock_info_arr)));
         } elseif ($day_num > 1 && $hotel_num > 1 && (strtotime($hotel_start_time) == strtotime($hotel_end_time))) { //同一个月住了2天以上

            for ($i = intval(substr($calendar_array[0], -2)); $i < intval(substr($calendar_array[1], -2)); $i++) {
               $stock_info_arr[$i]['stock'] = $stock_info_arr[$i]['stock'] - 1;
            }
            Model()->table('stock')->where($where_stock)->update(array('stock_info' => json_encode($stock_info_arr)));
         } elseif ($day_num > 1 && $hotel_num > 1 && (strtotime($hotel_start_time) < strtotime($hotel_end_time))) { //跨月 目前只考虑最多两个月

            $have_day = date('t', $hotel_start_time);
            for ($i = intval(substr($calendar_array[0], -2)); $i <= $have_day; $i++) {
               $stock_info_arr[$i]['stock'] = $stock_info_arr[$i]['stock'] - 1;
            }
            //先更新第一个月
            $re = Model()->table('stock')->where($where_stock)->update(array('stock_info' => json_encode($stock_info_arr)));
            if ($re) {
               //更新第二个月
               for ($i = 1; $i <= intval(substr($calendar_array[1], -2)); $i++) {
                  $stock_info_arr[$i]['stock'] = $stock_info_arr[$i]['stock'] - 1;
               }
               unset($where_stock);
               $where_stock = array('commonid' => $post['commonid'], 'date' => $hotel_end_time);
               Model()->table('stock')->where($where_stock)->update(array('stock_info' => json_encode($stock_info_arr)));
            }
         }

      } elseif (3 == $calendar_type) { //高尔夫球 string(15) "2016-11-30 8:10"

         $calendar_data  = base64_decode($post['rl_data']);
         $date           = explode(' ', $calendar_data);
         $_date          = $date[0]; //年月日
         $_time          = explode(':', $date[1]);
         $commonid       = $post["commonid"];
         $update_date    = trim($_date); //年月日
         $update_hours   = intval($_time[0]); //小时
         $update_minutes = $_time[1]; //分钟

         $stock                                          = Model()->table('golf_stock')->where(array('commonid' => $post['commonid'], 'date' => trim($_date)))->find();
         $stock                                          = unserialize($stock['stock_info']);
         $stock[$update_hours][$update_minutes]['stock'] = 2; //2为预订锁定状态，支付成功后为0,默认库存为1
         // 更新库存状态为2
         Model()->table('golf_stock')->where(array('commonid' => $commonid, 'date' => $update_date))->update(array('stock_info' => serialize($stock)));

         //更新高尔夫库存表stock_info字段的库存
         //         Model('golf_stock')->golf_stock_up($commonid, $update_date, $update_hours,$update_minutes,$stock);
      }
   }
}

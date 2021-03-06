<?php
/**
 * 购买行为
 *

 */
defined('InShopNC') or exit('Access Invalid!');
class buy_1Logic {

   /**
    * 取得商品最新的属性及促销[购物车]
    * @param unknown $cart_list
    */
   public function getGoodsCartList($cart_list) {

      $cart_list = $this->_getOnlineCartList($cart_list);

      //优惠套装
      $this->_getBundlingCartList($cart_list);

      //抢购
      $this->getGroupbuyCartList($cart_list);

      //限时折扣
      $this->getXianshiCartList($cart_list);

      //赠品
      $this->_getGiftCartList($cart_list);

      return $cart_list;

   }

   /**
    * 取得失效的购物车商品[购物车]
    * @param unknown $goods_list
    */
   public function getNotOnSaleGoodsCartList($cart_list) {

      $goods_list = $this->_getNotOnSaleCartList($cart_list);

      return $goods_list;

   }

   /**
    * 取得商品最新的属性及促销[立即购买]
    * @param int $goods_id
    * @param int $quantity
    * @return array
    */
   public function getGoodsOnlineInfo($goods_id, $quantity, $calendar_array) {
      $goods_info = $this->_getGoodsOnlineInfo($goods_id, $quantity, $calendar_array);
      //抢购
      $this->getGroupbuyInfo($goods_info);

      //限时折扣
      $this->getXianshiInfo($goods_info, $goods_info['goods_num']);

      //赠品
      $this->_getGoodsGiftList($goods_info);

      return $goods_info;
   }
   /**
    * 取得商品最新的属性及促销[立即购买]
    * @param int $goods_id
    * @param int $quantity
    * @return array
    */
   public function getGoodsWeixinInfo($goods_id, $quantity) {
      $goods_info = $this->_getGoodsWeixinInfo($goods_id, $quantity);

      //抢购
      $this->getGroupbuyInfo($goods_info);

      //限时折扣
      $this->getXianshiInfo($goods_info, $goods_info['goods_num']);

      //赠品
      $this->_getGoodsGiftList($goods_info);

      return $goods_info;
   }
   /**
    * 商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
    * @param unknown $store_cart_list 以店铺ID分组的购物车商品信息
    * @return array
    */
   public function calcCartList($store_cart_list) {
      if (empty($store_cart_list) || !is_array($store_cart_list)) {
         return array($store_cart_list, array(), 0);
      }

      //存放每个店铺的商品总金额
      $store_goods_total = array();
      //存放本次下单所有店铺商品总金额
      $order_goods_total = 0;

      foreach ($store_cart_list as $store_id => $store_cart) {
         $tmp_amount       = 0;
         $order_re_total_1 = 0;
         $order_re_total_2 = 0;
         $order_re_total_3 = 0;
         $share_settings = Model('share_settings')->where(array('store_id' => $store_id))->find();
         foreach ($store_cart as $key => $cart_info) {
            if (1 == $_SESSION['share_shop'] and 1 == $cart_info['is_share']) {
               $store_cart[$key]['goods_total'] = ncPriceFormat($cart_info['share_price'] * $cart_info['goods_num']);
               #计算佣金
               if (1 == $cart_info['is_re']) {
                  $share_settings['re_price_1'] = $cart_info['re_price_1'];
                  $share_settings['re_price_2'] = $cart_info['re_price_2'];
                  $share_settings['re_price_3'] = $cart_info['re_price_3'];
               }
               switch ($share_settings['re_type']) {
               case 2:
                  $re_price_1 = $cart_info['goods_num'] * $share_settings['re_price_1'];
                  $re_price_2 = $cart_info['goods_num'] * $share_settings['re_price_2'];
                  $re_price_3 = $cart_info['goods_num'] * $share_settings['re_price_3'];
                  break;
               case 1:
               default:
                  $re_price_1 = $store_cart[$key]['goods_total'] * $share_settings['re_price_1'] * .01;
                  $re_price_2 = $store_cart[$key]['goods_total'] * $share_settings['re_price_2'] * .01;
                  $re_price_3 = $store_cart[$key]['goods_total'] * $share_settings['re_price_3'] * .01;
                  break;
               }
               $store_cart[$key]['re_price_1_total'] = sprintf('%.2f', $re_price_1);
               $store_cart[$key]['re_price_2_total'] = sprintf('%.2f', $re_price_2);
               $store_cart[$key]['re_price_3_total'] = sprintf('%.2f', $re_price_3);
               $order_re_total_1 += $re_price_1;
               $order_re_total_2 += $re_price_2;
               $order_re_total_3 += $re_price_3;
            } elseif ($cart_info['calendar_type']) { //价格日历
               $store_cart[$key]['goods_total'] = ncPriceFormat($cart_info['calendar_total']);
               $store_cart[$key]['goods_num']   = intval($cart_info['goods_num']) > 1 ? $cart_info['goods_num'] : 1;

            } else {
               $store_cart[$key]['goods_total'] = ncPriceFormat($cart_info['goods_price'] * $cart_info['goods_num']); //计算总价格
            }
            $store_cart[$key]['isshare']         = $cart_info['isshare'];
            $store_cart[$key]['goods_image_url'] = cthumb($store_cart[$key]['goods_image']);
            $tmp_amount += $store_cart[$key]['goods_total'];
         }
         $store_cart_list[$store_id]   = $store_cart;
         $store_goods_total[$store_id] = ncPriceFormat($tmp_amount);
      }
      return array($store_cart_list, $store_goods_total);
   }

   /**
    * 取得店铺级优惠 - 跟据商品金额返回每个店铺当前符合的一条活动规则，如果有赠品，则自动追加到购买列表，价格为0
    * @param unknown $store_goods_total 每个店铺的商品金额小计，以店铺ID为下标
    * @return array($premiums_list,$mansong_rule_list) 分别为赠品列表[下标自增]，店铺满送规则列表[店铺ID为下标]
    */
   public function getMansongRuleCartListByTotal($store_goods_total) {
      if (!C('promotion_allow') || empty($store_goods_total) || !is_array($store_goods_total)) {
         return array(array(), array());
      }

      $model_mansong = Model('p_mansong');
      $model_goods   = Model('goods');

      //定义赠品数组，下标为店铺ID
      $premiums_list = array();
      //定义满送活动数组，下标为店铺ID
      $mansong_rule_list = array();

      foreach ($store_goods_total as $store_id => $goods_total) {
         $rule_info = $model_mansong->getMansongRuleByStoreID($store_id, $goods_total);
         if (is_array($rule_info) && !empty($rule_info)) {
            //即不减金额，也找不到促销商品时(已下架),此规则无效
            if (empty($rule_info['discount']) && empty($rule_info['mansong_goods_name'])) {
               continue;
            }
            $rule_info['desc']            = $this->_parseMansongRuleDesc($rule_info);
            $rule_info['discount']        = ncPriceFormat($rule_info['discount']);
            $mansong_rule_list[$store_id] = $rule_info;
            //如果赠品在售,有库存,则追加到购买列表
            if (!empty($rule_info['mansong_goods_name']) && !empty($rule_info['goods_storage'])) {
               $data                       = array();
               $data['goods_id']           = $rule_info['goods_id'];
               $data['goods_name']         = $rule_info['mansong_goods_name'];
               $data['goods_num']          = 1;
               $data['goods_price']        = 0.00;
               $data['goods_image']        = $rule_info['goods_image'];
               $data['goods_image_url']    = cthumb($rule_info['goods_image']);
               $data['goods_storage']      = $rule_info['goods_storage'];
               $premiums_list[$store_id][] = $data;
            }
         }
      }
      return array($premiums_list, $mansong_rule_list);
   }

   /**
    * 重新计算每个店铺最终商品总金额(最初计算金额减去各种优惠/加运费)
    * @param array $store_goods_total 店铺商品总金额
    * @param array $preferential_array 店铺优惠活动内容
    * @param string $preferential_type 优惠类型，目前只有一个 'mansong'
    * @return array 返回扣除优惠后的店铺商品总金额
    */
   public function reCalcGoodsTotal($store_goods_total, $preferential_array, $preferential_type) {
      $deny = empty($store_goods_total) || !is_array($store_goods_total) || empty($preferential_array) || !is_array($preferential_array);
      if ($deny) {
         return $store_goods_total;
      }

      switch ($preferential_type) {
      case 'mansong':
         if (!C('promotion_allow')) {
            return $store_goods_total;
         }

         foreach ($preferential_array as $store_id => $rule_info) {
            if (is_array($rule_info) && $rule_info['discount'] > 0) {
               $store_goods_total[$store_id] -= $rule_info['discount'];
            }
         }
         break;

      case 'voucher':
         if (!C('voucher_allow')) {
            return $store_goods_total;
         }

         foreach ($preferential_array as $store_id => $voucher_info) {
            $store_goods_total[$store_id] -= $voucher_info['voucher_price'];
         }
         break;

      case 'freight':
         foreach ($preferential_array as $store_id => $freight_total) {
            $store_goods_total[$store_id] += $freight_total;
         }
         break;
      }
      return $store_goods_total;
   }
   /**
    * 取得店铺可用的代金券
    * @param array $store_goods_total array(店铺ID=>商品总金额)
    * @return array
    */
   public function getStoreAvailableNoLimitVoucherList($store_goods_total, $member_id) {
      if (!C('voucher_allow')) {
         return $store_goods_total;
      }

      $voucher_list  = array();
      $model_voucher = Model('voucher');
      foreach ($store_goods_total as $store_id => $goods_total) {
         $condition                     = array();
         $condition['voucher_store_id'] = $store_id;
         $condition['voucher_owner_id'] = $member_id;
         $voucher_list[$store_id]       = $model_voucher->getCurrentAvailableNoLimitVoucher($condition, $goods_total);
      }
      return $voucher_list;
   }
   /**
    * 取得店铺可用的代金券
    * @param array $store_goods_total array(店铺ID=>商品总金额)
    * @return array
    */
   public function getStoreAvailableVoucherList($store_goods_total, $member_id) {
      if (!C('voucher_allow')) {
         return $store_goods_total;
      }

      $voucher_list  = array();
      $model_voucher = Model('voucher');
      foreach ($store_goods_total as $store_id => $goods_total) {
         $condition                     = array();
         $condition['voucher_store_id'] = $store_id;
         $condition['voucher_owner_id'] = $member_id;
         $voucher_list[$store_id]       = $model_voucher->getCurrentAvailableVoucher($condition, $goods_total);
      }
      return $voucher_list;
   }

   /**
    * 验证传过来的代金券是否可用有效，如果无效，直接删除
    * @param array $input_voucher_list 代金券列表
    * @param array $store_goods_total (店铺ID=>商品总金额)
    * @return array
    */
   public function reParseVoucherList($input_voucher_list = array(), $store_goods_total = array(), $member_id) {
      if (empty($input_voucher_list) || !is_array($input_voucher_list)) {
         return array();
      }

      $store_voucher_list = $this->getStoreAvailableVoucherList($store_goods_total, $member_id);
      foreach ($input_voucher_list as $store_id => $voucher) {
         $tmp = $store_voucher_list[$store_id];
         if (is_array($tmp) && isset($tmp[$voucher['voucher_t_id']])) {
            $input_voucher_list[$store_id]['voucher_id']       = $tmp[$voucher['voucher_t_id']]['voucher_id'];
            $input_voucher_list[$store_id]['voucher_code']     = $tmp[$voucher['voucher_t_id']]['voucher_code'];
            $input_voucher_list[$store_id]['voucher_owner_id'] = $tmp[$voucher['voucher_t_id']]['voucher_owner_id'];
         } else {
            throw new Exception('该代金券不符合用券要求，请重新选择！');

            //unset($input_voucher_list[$store_id]);
         }
      }
      return $input_voucher_list;
   }

   /**
    * 判断商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
    * @param array $goods_info
    * @param number $quantity 购买数量
    */
   public function getXianshiInfo(&$goods_info, $quantity) {
      if (empty($quantity)) {
         $quantity = 1;
      }

      if (!C('promotion_allow') || empty($goods_info['xianshi_info'])) {
         return;
      }

      $goods_info['xianshi_info']['down_price'] = ncPriceFormat($goods_info['goods_price'] - $goods_info['xianshi_info']['xianshi_price']);
      if ($quantity >= $goods_info['xianshi_info']['lower_limit']) {
         $goods_info['goods_price']   = $goods_info['xianshi_info']['xianshi_price'];
         $goods_info['promotions_id'] = $goods_info['xianshi_info']['xianshi_id'];
         $goods_info['ifxianshi']     = true;
      }
   }

   /**
    * 输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表
    * @param $buy_list 商品列表
    * @return 返回 以支付方式为下标分组的商品列表
    */
   public function getOfflineGoodsPay($buy_list) {
      //以支付方式为下标，存放购买商品
      $buy_goods_list = array();
      $offline_pay    = Model('payment')->getPaymentOpenInfo(array('payment_code' => 'offline'));
      if ($offline_pay) {
         //下单里包括平台自营商品并且平台已开启货到付款，则显示货到付款项及对应商品数量,取出支持货到付款的店铺ID组成的数组，目前就一个，DEFAULT_PLATFORM_STORE_ID
         $offline_store_id_array = model('store')->getOwnShopIds();
         foreach ($buy_list as $value) {
            //if (in_array($value['store_id'],$offline_store_id_array)) {
            $buy_goods_list['offline'][] = $value;
            //} else {
            //    $buy_goods_list['online'][] = $value;
            //}
         }
      }
      return $buy_goods_list;
   }

   /**
    * 计算每个店铺(所有店铺级优惠活动)总共优惠多少金额
    * @param array $store_goods_total 最初店铺商品总金额
    * @param array $store_final_goods_total 去除各种店铺级促销后，最终店铺商品总金额(不含运费)
    * @return array
    */
   public function getStorePromotionTotal($store_goods_total, $store_final_goods_total) {
      if (!is_array($store_goods_total) || !is_array($store_final_goods_total)) {
         return array();
      }

      $store_promotion_total = array();
      foreach ($store_goods_total as $store_id => $goods_total) {
         $store_promotion_total[$store_id] = abs($goods_total - $store_final_goods_total[$store_id]);
      }
      return $store_promotion_total;
   }

   /**
    * 返回需要计算运费的店铺ID组成的数组 和 免运费店铺ID及免运费下限金额描述
    * @param array $store_goods_total 每个店铺的商品金额小计，以店铺ID为下标
    * @return array
    */
   public function getStoreFreightDescList($store_goods_total) {
      if (empty($store_goods_total) || !is_array($store_goods_total)) {
         return array(array(), array());
      }

      //定义返回数组
      $need_calc_sid_array   = array();
      $cancel_calc_sid_array = array();

      //如果商品金额未达到免运费设置下线，则需要计算运费
      $condition  = array('store_id' => array('in', array_keys($store_goods_total)));
      $store_list = Model('store')->getStoreOnlineList($condition, null, '', 'store_id,store_free_price');
      foreach ($store_list as $store_info) {
         $limit_price = floatval($store_info['store_free_price']);
         if (0 == $limit_price || $limit_price > $store_goods_total[$store_info['store_id']]) {
            //需要计算运费
            $need_calc_sid_array[] = $store_info['store_id'];
         } else {
            //返回免运费金额下限
            $cancel_calc_sid_array[$store_info['store_id']]['free_price'] = $limit_price;
            $cancel_calc_sid_array[$store_info['store_id']]['desc']       = sprintf('满%s免运费', $limit_price);
         }
      }
      return array($need_calc_sid_array, $cancel_calc_sid_array);
   }

   /**
    * 取得店铺运费(使用运费模板的商品运费不会计算，但会返回模板信息)
    * 先将免运费的店铺运费置0，然后算出店铺里没使用运费模板的商品运费之和 ，存到iscalced下标中
    * 然后再计算使用运费模板的信息(array(店铺ID=>array(运费模板ID=>购买数量))，放到nocalced下标里
    * @param array $buy_list 购买商品列表
    * @param array $free_freight_sid_list 免运费的店铺ID数组
    */
   public function getStoreFreightList($buy_list = array(), $free_freight_sid_list) {
      //定义返回数组
      $return = array();
      //先将免运费的店铺运费置0(格式:店铺ID=>0)
      $freight_list = array();
      if (!empty($free_freight_sid_list) && is_array($free_freight_sid_list)) {
         foreach ($free_freight_sid_list as $store_id) {
            $freight_list[$store_id] = 0;
         }
      }

      //然后算出店铺里没使用运费模板(优惠套装商品除外)的商品运费之和(格式:店铺ID=>运费)
      //定义数组，存放店铺优惠套装商品运费总额 store_id=>运费
      $store_bl_goods_freight = array();
      foreach ($buy_list as $key => $goods_info) {
         //免运费店铺的商品不需要计算
         if (in_array($goods_info['store_id'], $free_freight_sid_list)) {
            unset($buy_list[$key]);
            continue;
         }
         //优惠套装商品运费另算
         if (intval($goods_info['bl_id'])) {
            unset($buy_list[$key]);
            $store_bl_goods_freight[$goods_info['store_id']] = $goods_info['bl_id'];
            continue;
         }
         if (!intval($goods_info['transport_id']) && !in_array($goods_info['store_id'], $free_freight_sid_list)) {
            $freight_list[$goods_info['store_id']] += $goods_info['goods_freight'];
            unset($buy_list[$key]);
         }
      }
      //计算优惠套装商品运费
      if (!empty($store_bl_goods_freight)) {
         $model_bl = Model('p_bundling');
         foreach (array_unique($store_bl_goods_freight) as $store_id => $bl_id) {
            $bl_info = $model_bl->getBundlingInfo(array('bl_id' => $bl_id));
            if (!empty($bl_info)) {
               $freight_list[$store_id] += $bl_info['bl_freight'];
            }
         }
      }

      $return['iscalced'] = $freight_list;

      //最后再计算使用运费模板的信息(店铺ID，运费模板ID，购买数量),使用使用相同运费模板的商品数量累加
      $freight_list = array();
      foreach ($buy_list as $goods_info) {
         $freight_list[$goods_info['store_id']][$goods_info['transport_id']] += $goods_info['goods_num'];
      }
      $return['nocalced'] = $freight_list;

      return $return;
   }

   /**
    * 根据地区选择计算出所有店铺最终运费
    * @param array $freight_list 运费信息(店铺ID，运费，运费模板ID，购买数量)
    * @param int $city_id 市级ID
    * @return array 返回店铺ID=>运费
    */
   public function calcStoreFreight($freight_list, $city_id) {
      if (!is_array($freight_list) || empty($freight_list) || empty($city_id)) {
         return;
      }

      //免费和固定运费计算结果
      $return_list = $freight_list['iscalced'];

      //使用运费模板的信息(array(店铺ID=>array(运费模板ID=>购买数量))
      $nocalced_list = $freight_list['nocalced'];

      //然后计算使用运费运费模板的在该$city_id时的运费值
      if (!empty($nocalced_list) && is_array($nocalced_list)) {
         //如果有商品使用的运费模板，先计算这些商品的运费总金额
         $model_transport = Model('transport');
         foreach ($nocalced_list as $store_id => $value) {
            if (is_array($value)) {
               foreach ($value as $transport_id => $buy_num) {
                  $freight_total = $model_transport->calc_transport($transport_id, $buy_num, $city_id);
                  if (empty($return_list[$store_id])) {
                     $return_list[$store_id] = $freight_total;
                  } else {
                     $return_list[$store_id] += $freight_total;
                  }
               }
            }
         }
      }

      return $return_list;
   }

   /**
    * 追加赠品到下单列表,并更新购买数量
    * @param array $store_cart_list 购买列表
    * @param array $store_premiums_list 赠品列表
    * @param array $store_mansong_rule_list 满即送规则
    */
   public function appendPremiumsToCartList($store_cart_list, $store_premiums_list = array(), $store_mansong_rule_list = array(), $member_id) {
      if (empty($store_cart_list)) {
         return array();
      }

      //处理商品级赠品
      foreach ($store_cart_list as $store_id => $cart_list) {
         foreach ($cart_list as $cart_info) {
            if (empty($cart_info['gift_list'])) {
               continue;
            }

            if (!is_array($store_premiums_list)) {
               $store_premiums_list = array();
            }

            if (!array_key_exists($store_id, $store_premiums_list)) {
               $store_premiums_list[$store_id] = array();
            }

            $zenpin_info = array();
            foreach ($cart_info['gift_list'] as $gift_info) {
               $zenpin_info['goods_id']          = $gift_info['gift_goodsid'];
               $zenpin_info['goods_name']        = $gift_info['gift_goodsname'];
               $zenpin_info['goods_image']       = $gift_info['gift_goodsimage'];
               $zenpin_info['goods_storage']     = $gift_info['goods_storage'];
               $zenpin_info['goods_num']         = $cart_info['goods_num'] * $gift_info['gift_amount'];
               $store_premiums_list[$store_id][] = $zenpin_info;
            }
         }
      }

      //取得每种商品的库存[含赠品]
      $goods_storage_quantity = $this->_getEachGoodsStorageQuantity($store_cart_list, $store_premiums_list);

      //取得每种商品的购买量[不含赠品]
      $goods_buy_quantity = $this->_getEachGoodsBuyQuantity($store_cart_list);
      foreach ($goods_buy_quantity as $goods_id => $quantity) {
         $goods_storage_quantity[$goods_id] -= $quantity;
         if ($goods_storage_quantity[$goods_id] < 0) {
            //商品库存不足，请重购买
            return false;
         }
      }
      //将赠品追加到购买列表

      if (is_array($store_premiums_list)) {
         foreach ($store_premiums_list as $store_id => $goods_list) {
            $zp_list   = array();
            $gift_desc = '';
            foreach ($goods_list as $goods_info) {
               //如果没有库存了，则不再送赠品
               if (0 == $goods_storage_quantity[$goods_info['goods_id']]) {
                  $gift_desc = '，赠品库存不足，未能全部送出 ';
                  continue;
               }

               $new_data                  = array();
               $new_data['buyer_id']      = $member_id;
               $new_data['store_id']      = $store_id;
               $new_data['store_name']    = $store_cart_list[$store_id][0]['store_name'];
               $new_data['goods_id']      = $goods_info['goods_id'];
               $new_data['goods_name']    = $goods_info['goods_name'];
               $new_data['goods_price']   = 0;
               $new_data['goods_image']   = $goods_info['goods_image'];
               $new_data['bl_id']         = 0;
               $new_data['state']         = true;
               $new_data['storage_state'] = true;
               $new_data['gc_id']         = 0;
               $new_data['transport_id']  = 0;
               $new_data['goods_freight'] = 0;
               $new_data['goods_vat']     = 0;
               $new_data['goods_total']   = 0;
               $new_data['ifzengpin']     = true;

               //计算赠送数量，有就赠，赠完为止
               if ($goods_storage_quantity[$goods_info['goods_id']] - $goods_info['goods_num'] >= 0) {
                  $goods_buy_quantity[$goods_info['goods_id']] += $goods_info['goods_num'];
                  $goods_storage_quantity[$goods_info['goods_id']] -= $goods_info['goods_num'];
                  $new_data['goods_num'] = $goods_info['goods_num'];
               } else {
                  $new_data['goods_num'] = $goods_storage_quantity[$goods_info['goods_id']];
                  $goods_buy_quantity[$goods_info['goods_id']] += $goods_storage_quantity[$goods_info['goods_id']];
                  $goods_storage_quantity[$goods_info['goods_id']] = 0;
               }
               if (array_key_exists($goods_info['goods_id'], $zp_list)) {
                  $zp_list[$goods_info['goods_id']]['goods_num'] += $new_data['goods_num'];
               } else {
                  $zp_list[$goods_info['goods_id']] = $new_data;
               }
            }
            sort($zp_list);
            $store_cart_list[$store_id] = array_merge($store_cart_list[$store_id], $zp_list);

            $store_mansong_rule_list[$store_id]['desc'] .= $gift_desc;
            $store_mansong_rule_list[$store_id]['desc'] = trim($store_mansong_rule_list[$store_id]['desc'], '，');
         }
      }
      return array($store_cart_list, $goods_buy_quantity, $store_mansong_rule_list);
   }

   /**
    * 充值卡支付,依次循环每个订单
    * 如果充值卡足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
    */
   public function rcbPay($order_list, $input, $buyer_info) {
      $member_id   = $buyer_info['member_id'];
      $member_name = $buyer_info['member_name'];

      $available_rcb_amount = floatval($buyer_info['available_rc_balance']);
      if ($available_rcb_amount <= 0) {
         return;
      }

      $model_order = Model('order');
      $model_pd    = Model('predeposit');
      foreach ($order_list as $key => $order_info) {

         //货到付款的订单跳过
         if ('offline' == $order_info['payment_code']) {
            continue;
         }

         $order_amount           = floatval($order_info['order_amount']);
         $data_pd                = array();
         $data_pd['member_id']   = $member_id;
         $data_pd['member_name'] = $member_name;
         $data_pd['amount']      = $order_info['order_amount'];
         $data_pd['order_sn']    = $order_info['order_sn'];

         if ($available_rcb_amount >= $order_amount) {
            //立即支付，订单支付完成
            $model_pd->changeRcb('order_pay', $data_pd);
            $available_rcb_amount -= $order_amount;

            //记录订单日志(已付款)
            $data                   = array();
            $data['order_id']       = $order_info['order_id'];
            $data['log_role']       = 'buyer';
            $data['log_msg']        = L('order_log_pay');
            $data['log_orderstate'] = ORDER_STATE_PAY;
            $insert                 = $model_order->addOrderLog($data);
            if (!$insert) {
               throw new Exception('记录订单充值卡支付日志出现错误');
            }

            //订单状态 置为已支付
            $data_order                      = array();
            $order_list[$key]['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
            $data_order['payment_time']      = TIMESTAMP;
            $data_order['payment_code']      = 'predeposit';
            $data_order['rcb_amount']        = $order_amount;
            $result                          = $model_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
            if (!$result) {
               throw new Exception('订单更新失败');
            }
            // 发送商家提醒
            $param             = array();
            $param['code']     = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param']    = array(
               'order_sn' => $order_info['order_sn'],
            );
            QueueClient::push('sendStoreMsg', $param);

            ##---订单完成，结算佣金 start
            if (1 == $order_info['is_share'] and 0 == $order_info['re_status']) {
               $wx_model   = Model('share_member');
               $cmms_model = Model('share_cmm_log');
               for ($i = 0; $i <= 3; $i++) {
                  $re_mid    = $order_info['re_mid_' . $i];
                  $re_amount = $order_info['re_price_' . $i];
                  if ($re_mid > 0 and $re_amount > 0) {
                     $mem = $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->find();
                     if (1 == $mem['status'] and 1 == $mem['isshare']) {
                        $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->update(array('credits' => array('exp', 'credits+' . $re_amount)));
                        Model()->table('order')->where(array('order_id' => $order_info['order_id']))->update(array('re_status' => 1, 're_time' => TIMESTAMP));
                        $log_data = array(
                           'addtime'       => TIMESTAMP,
                           'openid'        => (string) $mem['openid'],
                           'mid'           => $re_mid,
                           'nickname'      => strval($mem['nickname']),
                           'type'          => '余额',
                           'order_id'      => $order_info['order_id'],
                           'amount'        => $re_amount,
                           'remark'        => '',
                           'order_type'    => '1',
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
            ##--- 结算佣金 end
         } else {
            //暂冻结充值卡,后面还需要 API彻底完成支付
            if ($available_rcb_amount > 0) {
               $data_pd['amount'] = $available_rcb_amount;
               $model_pd->changeRcb('order_freeze', $data_pd);
               //支付金额保存到订单
               $data_order                     = array();
               $order_list[$key]['rcb_amount'] = $data_order['rcb_amount'] = $available_rcb_amount;
               $result                         = $model_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
               $available_rcb_amount           = 0;
               if (!$result) {
                  throw new Exception('订单更新失败');
               }
            }
         }
      }
      return $order_list;
   }

   /**
    * 预存款支付,依次循环每个订单
    * 如果预存款足够就单独支付了该订单，如果不足就暂时冻结，等API支付成功了再彻底扣除
    */
   public function pdPay($order_list, $input, $buyer_info) {
      $member_id   = $buyer_info['member_id'];
      $member_name = $buyer_info['member_name'];

//         $model_payment = Model('payment');
      //         $pd_payment_info = $model_payment->getPaymentOpenInfo(array('payment_code'=>'predeposit'));
      //         if (empty($pd_payment_info)) return;

      $available_pd_amount = floatval($buyer_info['available_predeposit']);
      if ($available_pd_amount <= 0) {
         return;
      }

      $model_order = Model('order');
      $model_pd    = Model('predeposit');
      foreach ($order_list as $order_info) {

         //货到付款的订单、已经充值卡支付的订单跳过
         if ('offline' == $order_info['payment_code']) {
            continue;
         }

         if (ORDER_STATE_PAY == $order_info['order_state']) {
            continue;
         }

         $order_amount           = floatval($order_info['order_amount']) - floatval($order_info['rcb_amount']);
         $data_pd                = array();
         $data_pd['member_id']   = $member_id;
         $data_pd['member_name'] = $member_name;
         $data_pd['amount']      = $order_amount;
         $data_pd['order_sn']    = $order_info['order_sn'];

         if ($available_pd_amount >= $order_amount) {
            //预存款立即支付，订单支付完成
            $model_pd->changePd('order_pay', $data_pd);
            $available_pd_amount -= $order_amount;

            //支付被冻结的充值卡
            $rcb_amount = floatval($order_info['rcb_amount']);
            if ($rcb_amount > 0) {
               $data_pd                = array();
               $data_pd['member_id']   = $member_id;
               $data_pd['member_name'] = $member_name;
               $data_pd['amount']      = $rcb_amount;
               $data_pd['order_sn']    = $order_info['order_sn'];
               $model_pd->changeRcb('order_comb_pay', $data_pd);
            }

            //记录订单日志(已付款)
            $data                   = array();
            $data['order_id']       = $order_info['order_id'];
            $data['log_role']       = 'buyer';
            $data['log_msg']        = L('order_log_pay');
            $data['log_orderstate'] = ORDER_STATE_PAY;
            $insert                 = $model_order->addOrderLog($data);
            if (!$insert) {
               throw new Exception('记录订单预存款支付日志出现错误');
            }

            //订单状态 置为已支付
            $data_order                 = array();
            $data_order['order_state']  = ORDER_STATE_PAY;
            $data_order['payment_time'] = TIMESTAMP;
            $data_order['payment_code'] = 'predeposit';
            $data_order['pd_amount']    = $order_amount;
            $result                     = $model_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
            if (!$result) {
               throw new Exception('订单更新失败');
            }
            ##---订单完成，结算佣金 start
            if (1 == $order_info['is_share'] and 0 == $order_info['re_status']) {
               $wx_model   = Model('share_member');
               $cmms_model = Model('share_cmm_log');
               for ($i = 0; $i <= 3; $i++) {
                  $re_mid    = $order_info['re_mid_' . $i];
                  $re_amount = $order_info['re_price_' . $i];
                  if ($re_mid > 0 and $re_amount > 0) {
                     $mem = $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->find();
                     if (1 == $mem['status'] and 1 == $mem['isshare']) {
                        $wx_model->where(array('member_id' => $re_mid, 'store_id' => $order_info['store_id']))->update(array('credits' => array('exp', 'credits+' . $re_amount)));
                        Model()->table('order')->where(array('order_id' => $order_info['order_id']))->update(array('re_status' => 1, 're_time' => TIMESTAMP));
                        $log_data = array(
                           'addtime'       => TIMESTAMP,
                           'openid'        => (string) $mem['openid'],
                           'mid'           => $re_mid,
                           'nickname'      => strval($mem['nickname']),
                           'type'          => '余额',
                           'order_id'      => $order_info['order_id'],
                           'amount'        => $re_amount,
                           'remark'        => '',
                           'order_type'    => '1',
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
            ##--- 结算佣金 end
            // 发送商家提醒
            $param             = array();
            $param['code']     = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param']    = array(
               'order_sn' => $order_info['order_sn'],
            );
            QueueClient::push('sendStoreMsg', $param);
         } else {
            //暂冻结预存款,后面还需要 API彻底完成支付
            if ($available_pd_amount > 0) {
               $data_pd['amount'] = $available_pd_amount;
               $model_pd->changePd('order_freeze', $data_pd);
               //预存款支付金额保存到订单
               $data_order              = array();
               $data_order['pd_amount'] = $available_pd_amount;
               $result                  = $model_order->editOrder($data_order, array('order_id' => $order_info['order_id']));
               $available_pd_amount     = 0;
               if (!$result) {
                  throw new Exception('订单更新失败');
               }
            }
         }
      }
   }

   /**
    * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
    * 长度 =2位 + 10位 + 3位 + 3位  = 18位
    * 1000个会员同一微秒提订单，重复机率为1/100
    * @return string
    */
   public function makePaySn($member_id) {
      return mt_rand(10, 99)
      . sprintf('%010d', time() - 946656000)
      . sprintf('%03d', (float) microtime() * 1000)
      . sprintf('%03d', (int) $member_id % 1000);
   }

   /**
    * 订单编号生成规则，n(n>=1)个订单表对应一个支付表，
    * 生成订单编号(年取1位 + $pay_id取13位 + 第N个子订单取2位)
    * 1000个会员同一微秒提订单，重复机率为1/100
    * @param $pay_id 支付表自增ID
    * @return string
    */
   public function makeOrderSn($pay_id) {
      //记录生成子订单的个数，如果生成多个子订单，该值会累加
      static $num;
      if (empty($num)) {
         $num = 1;
      } else {
         $num++;
      }
      return (date('y', time()) % 9 + 1) . sprintf('%013d', $pay_id) . sprintf('%02d', $num);
   }

   /**
    * 更新库存与销量
    *
    * @param array $buy_items 商品ID => 购买数量
    */
   public function editGoodsNum($buy_items) {
      foreach ($buy_items as $goods_id => $buy_num) {
         $data   = array('goods_storage' => array('exp', 'goods_storage-' . $buy_num), 'goods_salenum' => array('exp', 'goods_salenum+' . $buy_num));
         $result = Model('goods')->editGoods($data, array('goods_id' => $goods_id));
         if (!$result) {
            throw new Exception(L('cart_step2_submit_fail'));
         }

      }
   }

   /**
    * 取得店铺级活动 - 每个店铺可用的满即送活动规则列表
    * @param unknown $store_id_array 店铺ID数组
    */
   public function getMansongRuleList($store_id_array) {
      if (!C('promotion_allow') || empty($store_id_array) || !is_array($store_id_array)) {
         return array();
      }

      $model_mansong     = Model('p_mansong');
      $mansong_rule_list = array();
      foreach ($store_id_array as $store_id) {
         $store_mansong_rule = $model_mansong->getMansongInfoByStoreID($store_id);
         if (!empty($store_mansong_rule['rules']) && is_array($store_mansong_rule['rules'])) {
            foreach ($store_mansong_rule['rules'] as $rule_info) {
               //如果减金额 或 有赠品(在售且有库存)
               if (!empty($rule_info['discount']) || (!empty($rule_info['mansong_goods_name']) && !empty($rule_info['goods_storage']))) {
                  $mansong_rule_list[$store_id][] = $this->_parseMansongRuleDesc($rule_info);
               }
            }
         }
      }
      return $mansong_rule_list;
   }

   /**
    * 取得哪些店铺有满免运费活动
    * @param array $store_id_array 店铺ID数组
    * @return array
    */
   public function getFreeFreightActiveList($store_id_array) {
      if (empty($store_id_array) || !is_array($store_id_array)) {
         return array();
      }

      //定义返回数组
      $store_free_freight_active = array();

      //如果商品金额未达到免运费设置下线，则需要计算运费
      $condition  = array('store_id' => array('in', $store_id_array));
      $store_list = Model('store')->getStoreOnlineList($condition, null, '', 'store_id,store_free_price');
      foreach ($store_list as $store_info) {
         $limit_price = floatval($store_info['store_free_price']);
         if ($limit_price > 0) {
            $store_free_freight_active[$store_info['store_id']] = sprintf('满%s免运费', $limit_price);
         }
      }
      return $store_free_freight_active;
   }

   /**
    * 取得收货人地址信息
    * @param array $address_info
    * @return array
    */
   public function getReciverAddr($address_info = array()) {
      if (intval($address_info['dlyp_id'])) {
         $reciver_info['phone']     = trim($address_info['dlyp_mobile'] . ($address_info['dlyp_telephony'] ? ',' . $address_info['dlyp_telephony'] : null), ',');
         $reciver_info['tel_phone'] = $address_info['dlyp_telephony'];
         $reciver_info['mob_phone'] = $address_info['dlyp_mobile'];
         $reciver_info['address']   = $address_info['dlyp_area_info'] . ' ' . $address_info['dlyp_address'];
         $reciver_info['area']      = $address_info['dlyp_area_info'];
         $reciver_info['street']    = $address_info['dlyp_address'];
         $reciver_info['dlyp']      = 1;
         $reciver_info              = serialize($reciver_info);
         $reciver_name              = $address_info['dlyp_address_name'];
      } else {
         $reciver_info['phone']     = trim($address_info['mob_phone'] . ($address_info['tel_phone'] ? ',' . $address_info['tel_phone'] : null), ',');
         $reciver_info['mob_phone'] = $address_info['mob_phone'];
         $reciver_info['tel_phone'] = $address_info['tel_phone'];
         $reciver_info['address']   = $address_info['area_info'] . ' ' . $address_info['address'];
         $reciver_info['area']      = $address_info['area_info'];
         $reciver_info['street']    = $address_info['address'];
         $reciver_info              = serialize($reciver_info);
         $reciver_name              = $address_info['true_name'];
      }
      return array($reciver_info, $reciver_name);
   }

   /**
    * 整理发票信息
    * @param array $invoice_info 发票信息数组
    * @return string
    */
   public function createInvoiceData($invoice_info) {
      //发票信息
      $inv = array();
      if (1 == $invoice_info['inv_state']) {
         $inv['类型'] = '普通发票 ';
         $inv['抬头'] = 'person' == $invoice_info['inv_title_select'] ? '个人' : $invoice_info['inv_title'];
         $inv['内容'] = $invoice_info['inv_content'];
      } elseif (!empty($invoice_info)) {
         $inv['单位名称']       = $invoice_info['inv_company'];
         $inv['纳税人识别号'] = $invoice_info['inv_code'];
         $inv['注册地址']       = $invoice_info['inv_reg_addr'];
         $inv['注册电话']       = $invoice_info['inv_reg_phone'];
         $inv['开户银行']       = $invoice_info['inv_reg_bname'];
         $inv['银行账户']       = $invoice_info['inv_reg_baccount'];
         $inv['收票人姓名']    = $invoice_info['inv_rec_name'];
         $inv['收票人手机号'] = $invoice_info['inv_rec_mobphone'];
         $inv['收票人省份']    = $invoice_info['inv_rec_province'];
         $inv['送票地址']       = $invoice_info['inv_goto_addr'];
      }
      return !empty($inv) ? serialize($inv) : serialize(array());
   }

   /**
    * 计算本次下单中每个店铺订单是货到付款还是线上支付,店铺ID=>付款方式[online在线支付offline货到付款]
    * @param array $store_id_array 店铺ID数组
    * @param boolean $if_offpay 是否支持货到付款 true/false
    * @param string $pay_name 付款方式 online/offline
    * @return array
    */
   public function getStorePayTypeList($store_id_array, $if_offpay, $pay_name) {
      $store_pay_type_list = array();
      if ('online' == $_POST['pay_name']) {
         foreach ($store_id_array as $store_id) {
            $store_pay_type_list[$store_id] = 'online';
         }
      } else {
         $offline_pay = Model('payment')->getPaymentOpenInfo(array('payment_code' => 'offline'));
         if ($offline_pay) {
            //下单里包括平台自营商品并且平台已开启货到付款
            $offline_store_id_array = model('store')->getOwnShopIds();
            foreach ($store_id_array as $store_id) {
               //if (in_array($store_id,$offline_store_id_array)) {
               $store_pay_type_list[$store_id] = 'offline';
               //} else {
               //    $store_pay_type_list[$store_id] = 'online';
               //}
            }
         }
      }
      return $store_pay_type_list;
   }

   /**
    * 直接购买时返回最新的在售商品信息（需要在售）
    *
    * @param int $goods_id 所购商品ID
    * @param int $quantity 购买数量
    * @param int $calendar_array价格日历参数
    * @return array
    */
   private function _getGoodsOnlineInfo($goods_id, $quantity, $calendar_array) {
      //取目前在售商品

      $goods_info = Model('goods')->getGoodsOnlineInfoAndPromotionById($goods_id);
      if (empty($goods_info)) {
         return null;
      }
      $new_array                        = array();
      $new_array['goods_num']           = $goods_info['is_fcode'] ? 1 : $quantity;
      $new_array['goods_id']            = $goods_id;
      $new_array['goods_commonid']      = $goods_info['goods_commonid'];
      $new_array['gc_id']               = $goods_info['gc_id'];
      $new_array['store_id']            = $goods_info['store_id'];
      $new_array['goods_name']          = $goods_info['goods_name'];
      $new_array['goods_price']         = (1 == $_SESSION['share_shop'] and 1 == $goods_info['isshare']) ? $goods_info['share_price'] : $goods_info['goods_price'];
      $new_array['store_name']          = $goods_info['store_name'];
      $new_array['goods_image']         = $goods_info['goods_image'];
      $new_array['transport_id']        = $goods_info['transport_id'];
      $new_array['goods_freight']       = $goods_info['goods_freight'];
      $new_array['goods_vat']           = $goods_info['goods_vat'];
      $new_array['goods_storage']       = (1 == $_SESSION['share_shop'] and 1 == $goods_info['isshare']) ? $goods_info['share_stock'] : $goods_info['goods_storage'];
      $new_array['goods_storage_alarm'] = $goods_info['goods_storage_alarm'];
      $new_array['is_fcode']            = $goods_info['is_fcode'];
      $new_array['have_gift']           = $goods_info['have_gift'];
      $new_array['state']               = true;
      $new_array['groupbuy_info']       = $goods_info['groupbuy_info'];
      $new_array['xianshi_info']        = $goods_info['xianshi_info'];
      $new_array['is_share']            = $goods_info['isshare'];
      $new_array['share_price']         = $goods_info['share_price'];
      $new_array['share_stock']         = $goods_info['share_stock'];
      $new_array['re_price_1']          = $goods_info['re_price_1'];
      $new_array['re_price_2']          = $goods_info['re_price_2'];
      $new_array['re_price_3']          = $goods_info['re_price_3'];
      $new_array['is_re']               = $goods_info['is_re'];
      $new_array['endtime']             = $goods_info['endtime'];
      $new_array['stock_type']          = $goods_info['stock_type'];
      $new_array['storage_state']       = (1 == $_SESSION['share_shop'] and 1 == $goods_info['isshare']) ? $goods_info['share_stock'] >= intval($quantity) : intval($goods_info['goods_storage']) > intval($quantity);
      $new_array['is_virtual']          = $goods_info['is_virtual'];
      $new_array['virtual_indate']      = $goods_info['virtual_indate'];
      $new_array['virtual_invalid_refund'] = $goods_info['virtual_invalid_refund'];

      if (1 == $_SESSION['share_shop'] and 1 == $goods_info['isshare']) {
         $new_array['goods_price'] = $goods_info['share_price'];
      }

      $calendar_type = Model('goods')->getGoodCommonsList(array('goods_commonid' => $goods_info['goods_commonid']), 'calendar_type');
      if(in_array($calendar_type['calendar_type'],array(1,2,3))){
         $new_array['calendar_array'] = $calendar_array;//表单赋值
      }
      if (1 == $calendar_type['calendar_type']) { //普通日历
         //限时折扣格式： "2016-10-31,'xs_price',数量,套餐名称"
         //普通价格日历 "2016-10-31,-1,250,'套餐名称',50"  -1代表没选择
         $calendar_array = explode(',', $calendar_array);
         $start_time     = trim($calendar_array[0]); //出发时间
         $man_price      = $calendar_array[1]; //成人价
         $child_price    = $calendar_array[2]; //儿童价
         $spec_name    = $calendar_array[3]; //套餐
         $diff_price    = $calendar_array[4]; //单房差
         $year_m = substr($start_time, 0, 7); //2016-10
         if( $spec_name == -1){//没有套餐、规格
            $stock  = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], "date" => $year_m))->field('stock_info,date')->find();
         }else{
            $stock  = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'],'package'=>$spec_name, "date" => $year_m))->field('stock_info,date')->find();
         }

         if ($stock) {
            $stock = json_decode($stock['stock_info'], true); //转换成数组
            $input_names  =  array('man_price'=>'成人价格','child_price'=>'儿童价格','diff_price'=>'单房差');
            $price_array  =  array('man_price'=>$man_price,'child_price'=>$child_price,'diff_price'=>$diff_price);
            $i = intval(substr($start_time,-2));
            $price =0;//价格初始化
            $new_array['ticket_type'] = array();
            if($man_price =='xs_price'){//限时折扣价格
               $i                         = intval(substr($start_time, -2));
               $price  = floatval($stock[$i]["xs_price"]); //总价
               $str = "<input type='hidden' name='xs_price' value='{$price}' />";
               $str .= "<p class='ui-nowrap'>限时折扣价：￥<font class='qz-color2'>{$price}</font></p>";
               array_push($new_array['ticket_type'], $str); //入栈
            }else{
               foreach($price_array as $key => $item){
                  if($item == '-1'){
                     unset($price_array[$key]);
                     unset($input_names[$key]);
                  }else{
                     $str =  "<input type='hidden' name='putong_".$key."' value='".$stock[$i][$key] ."' />";
                     $str.=  "<p class='ui-nowrap'>$input_names[$key]：￥<font class='qz-color2'>".$stock[$i][$key]."</font></p>";
                     array_push($new_array['ticket_type'],$str);//入栈
                     $price += floatval($stock[$i][$key]);//总价
                  }
               }
            }

            $new_array['storage_state'] = floatval($stock[$i]['man_stock'])+floatval($stock[$i]['child_stock']);
            $new_array['start_time']    = $stock[$i]['date']; //出发时间
            $new_array['price_total']   = floatval($price * intval($new_array['goods_num']));//总价
            $new_array['calendar_type'] = 1;
            $new_array['goods_price']   = $new_array['price_total']; //重置商品价格
            $new_array['state']         = true;
         } else {
            $new_array['state'] = false;
            $new_array['msg']   = $start_time . '时段已经被购买！';
         }
//         print_r($new_array);exit;
      } elseif (2 == $calendar_type['calendar_type']) { //酒店日历
         $calendar_array = explode(',', $calendar_array);

         $start_time  = strtotime($calendar_array[0]); //开始入住时间
         $end_time    = strtotime($calendar_array[1]); //离店时间
         $hotel_num   = $calendar_array[2]; //入住间数
         $hotel_price = $calendar_array[3]; //单价

         $year_m                   = substr($start_time, 0, 7); //年月
         $stock                    = Model()->table('stock')->where(array('commonid' => $goods_info['goods_commonid'], "date" => substr($calendar_array[0], 0, 7)))->field('stock_info,date')->find();
         $stock                    = json_decode($stock['stock_info'], true); //转换成数组
         $new_array['goods_price'] = null;
         if ($stock) {
            foreach ($stock as $k => $val) {
               $mktime = strtotime($val['date']);
               if ($mktime == $start_time) {
                  $new_array['hotel_state_time'] = $val['date']; //入住时间
               }
               if ($mktime == $end_time) {
                  $new_array['hotel_end_time'] = $val['date']; //离店时间
               }
               if ($mktime >= $start_time && $mktime < $end_time) {
                  if($hotel_price=="xs_price"){
                     $new_array['hotel_total']   = floatval($new_array['hotel_total']) + floatval($val['xs_price']); //总价
                     $new_array['storage_state'] = floatval($new_array['storage_state']) + floatval($val['xs_stock']); //库存
                  }else{
                     $new_array['hotel_total']   = floatval($new_array['hotel_total']) + floatval($val['child_price']); //总价
                     $new_array['storage_state'] = floatval($new_array['storage_state']) + floatval($val['stock']); //库存
                  }

               }
            }
            $new_array['state'] = true;
         } else {
            $new_array['state'] = false;
            $new_array['msg']   = '时段已经被购买了！';
         }

         $new_array['hotel_num']     = intval($calendar_array[2]); //一共住几天晚上
         $new_array['calendar_type'] = 2;
      } elseif (3 == $calendar_type['calendar_type']) { //高尔夫日历
         $date     = explode(' ', $calendar_array);
         $_date    = $date[0];
         $_time    = explode(':', $date[1]);
         $_hours   = intval($_time[0]);
         $_minutes = $_time[1];
         $stock    = Model()->table('golf_stock')->where(array('commonid' => $goods_info['goods_commonid'], 'date' => $_date))->find();
         $stock    = unserialize($stock['stock_info']);
         if (0 != $stock[$_hours][$_minutes]['stock']) { //有库存
            if (!Model()->table('golf_order')->where("goods_id = {$goods_info['goods_id']} AND date = '{$_date}' AND hours = $_hours AND minutes = $_minutes")->find()) {
               $new_array['calendar_type'] = 3;
               $new_array['goods_num']     = 1; //购买1
               $new_array['start_time']    = $calendar_array;
               $new_array['state']         = true;
            } else {
               $new_array['state'] = false;
               $new_array['msg']   = $calendar_array . '时段已经被购买！';
            }
         } else {
            $new_array['state'] = false;
            $new_array['msg']   = $calendar_array . '时段已经被购买了！';
         }

      }

      //填充必要下标，方便后面统一使用购物车方法与模板
      //cart_id=goods_id,优惠套装目前只能进购物车,不能立即购买
      $new_array['cart_id'] = $goods_id;
      $new_array['bl_id']   = 0;

      return $new_array;
   }

   /**
    * 直接购买时返回最新的在售商品信息（需要在售）
    *
    * @param int $goods_id 所购商品ID
    * @param int $quantity 购买数量
    * @return array
    */
   private function _getGoodsWeixinInfo($goods_id, $quantity=3) {
      //取目前在售商品
      $goods_info = Model('goods')->getGoodsInfoAndPromotionById($goods_id);
      if (empty($goods_info)) {
         return null;
      }
      $new_array                        = array();
      $new_array['goods_num']           = $goods_info['is_fcode'] ? 1 : $quantity;
      $new_array['goods_id']            = $goods_id;
      $new_array['goods_commonid']      = $goods_info['goods_commonid'];
      $new_array['gc_id']               = $goods_info['gc_id'];
      $new_array['store_id']            = $goods_info['store_id'];
      $new_array['goods_name']          = $goods_info['goods_name'];
      $new_array['goods_price']         = $goods_info['goods_price'];
      $new_array['store_name']          = $goods_info['store_name'];
      $new_array['goods_image']         = $goods_info['goods_image'];
      $new_array['transport_id']        = $goods_info['transport_id'];
      $new_array['goods_freight']       = $goods_info['goods_freight'];
      $new_array['goods_vat']           = $goods_info['goods_vat'];
      $new_array['goods_storage']       = $goods_info['goods_storage'];
      $new_array['goods_storage_alarm'] = $goods_info['goods_storage_alarm'];
      $new_array['is_fcode']            = $goods_info['is_fcode'];
      $new_array['have_gift']           = $goods_info['have_gift'];
      $new_array['state']               = true;
      $new_array['storage_state']       = intval($goods_info['goods_storage']) < intval($quantity) ? false : true;
      $new_array['groupbuy_info']       = $goods_info['groupbuy_info'];
      $new_array['xianshi_info']        = $goods_info['xianshi_info'];

      //填充必要下标，方便后面统一使用购物车方法与模板
      //cart_id=goods_id,优惠套装目前只能进购物车,不能立即购买
      $new_array['cart_id'] = $goods_id;
      $new_array['bl_id']   = 0;

      return $new_array;
   }

   /**
    * 直接购买时，判断商品是不是正在抢购中，如果是，按抢购价格计算，购买数量若超过抢购规定的上限，则按抢购上限计算
    * @param array $goods_info
    */
   public function getGroupbuyInfo(&$goods_info = array()) {
      if (!C('groupbuy_allow') || empty($goods_info['groupbuy_info'])) {
         return;
      }

      $groupbuy_info             = $goods_info['groupbuy_info'];
      $goods_info['goods_price'] = $groupbuy_info['groupbuy_price'];
      if ($groupbuy_info['upper_limit'] && $goods_info['goods_num'] > $groupbuy_info['upper_limit']) {
         $goods_info['goods_num'] = $groupbuy_info['upper_limit'];
      }
      $goods_info['upper_limit']   = $groupbuy_info['upper_limit'];
      $goods_info['promotions_id'] = $goods_info['groupbuy_id'] = $groupbuy_info['groupbuy_id'];
      $goods_info['ifgroupbuy']    = true;
   }

   /**
    * 取得某商品赠品列表信息
    * @param array $goods_info
    */
   private function _getGoodsGiftList(&$goods_info) {
      if (!$goods_info['have_gift']) {
         return;
      }

      $gift_list = Model('goods_gift')->getGoodsGiftListByGoodsId($goods_info['goods_id']);
      //取得赠品当前信息，如果未在售踢除，如果在售取出库存
      if (empty($gift_list)) {
         return array();
      }

      $model_goods = Model('goods');
      foreach ($gift_list as $k => $v) {
         $goods_online_info = $model_goods->getGoodsOnlineInfoByID($v['gift_goodsid'], 'goods_storage');
         if (empty($goods_online_info)) {
            unset($gift_list[$k]);
         } else {
            $gift_list[$k]['goods_storage'] = $goods_online_info['goods_storage'];
         }
      }
      $goods_info['gift_list'] = $gift_list;
   }

   /**
    * 取商品最新的在售信息
    * @param unknown $cart_list
    * @return array
    */
   private function _getOnlineCartList($cart_list) {
      if (empty($cart_list) || !is_array($cart_list)) {
         return $cart_list;
      }

      //验证商品是否有效
      $goods_id_array = array();
      foreach ($cart_list as $key => $cart_info) {
         if (!intval($cart_info['bl_id'])) {
            $goods_id_array[] = $cart_info['goods_id'];
         }
      }
      $model_goods        = Model('goods');
      $goods_online_list  = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
      $goods_online_array = array();
      foreach ($goods_online_list as $goods) {
         $goods_online_array[$goods['goods_id']] = $goods;
      }

      foreach ((array) $cart_list as $key => $cart_info) {
         if (intval($cart_info['bl_id'])) {
            continue;
         }

         $cart_list[$key]['state']         = true;
         $cart_list[$key]['storage_state'] = true;
         if (in_array($cart_info['goods_id'], array_keys($goods_online_array))) {
            $goods_online_info                      = $goods_online_array[$cart_info['goods_id']];
            if ($cart_info['calendar_date']) {
               $vr_info = $this->getGoodsOnlineInfo($cart_info['goods_id'], $cart_info['goods_num'], $cart_info['calendar_date']);
               $goods_online_info['goods_price'] = $vr_info['goods_price'];
               $cart_list[$key]['is_virtual']  = $vr_info['is_virtual'];
               $cart_list[$key]['virtual_indate']  = $vr_info['virtual_indate'];
               $cart_list[$key]['virtual_invalid_refund']  = $vr_info['virtual_invalid_refund'];
               $cart_list[$key]['calendar_type']  = $vr_info['calendar_type'];
               $cart_list[$key]['calendar_date']  = $cart_info['calendar_date'];
               $cart_list[$key]['cart_id']  = $cart_info['cart_id'];
               $cart_list[$key]['calendar_total']  = $vr_info['goods_price'] * $cart_info['goods_num'];
            }
            $cart_list[$key]['goods_commonid']      = $goods_online_info['goods_commonid'];
            $cart_list[$key]['goods_name']          = $goods_online_info['goods_name'];
            $cart_list[$key]['gc_id']               = $goods_online_info['gc_id'];
            $cart_list[$key]['goods_image']         = $goods_online_info['goods_image'];
            $cart_list[$key]['goods_price']         = $goods_online_info['goods_price'];
            $cart_list[$key]['transport_id']        = $goods_online_info['transport_id'];
            $cart_list[$key]['goods_freight']       = $goods_online_info['goods_freight'];
            $cart_list[$key]['goods_vat']           = $goods_online_info['goods_vat'];
            $cart_list[$key]['goods_storage']       = $goods_online_info['goods_storage'];
            $cart_list[$key]['goods_storage_alarm'] = $goods_online_info['goods_storage_alarm'];
            $cart_list[$key]['goods_marketprice']   = $goods_online_info['goods_marketprice'];
            $cart_list[$key]['is_fcode']            = $goods_online_info['is_fcode'];
            $cart_list[$key]['have_gift']           = $goods_online_info['have_gift'];
            if ($cart_info['goods_num'] > $goods_online_info['goods_storage']) {
               $cart_list[$key]['storage_state'] = false;
            }
            $cart_list[$key]['groupbuy_info'] = $goods_online_info['groupbuy_info'];
            $cart_list[$key]['xianshi_info']  = $goods_online_info['xianshi_info'];
         } else {
            //如果商品下架
            $cart_list[$key]['state']         = false;
            $cart_list[$key]['storage_state'] = false;
         }
      }
      return $cart_list;
   }

   /**
    * 取不在出售商品最新的信息
    * @param unknown $cart_list
    * @return array
    */
   private function _getNotOnSaleCartList($cart_list) {
      if (empty($cart_list) || !is_array($cart_list)) {
         return $cart_list;
      }

      //验证商品是否有效
      $goods_id_array = array();
      foreach ($cart_list as $key => $cart_info) {
         if (!intval($cart_info['bl_id'])) {
            $goods_id_array[] = $cart_info['goods_id'];
         }
      }
      $model_goods           = Model('goods');
      $goods_notonsale_list  = $model_goods->getGoodsNotOnSaleListByIdArray($goods_id_array);
      $goods_notonsale_array = array();
      foreach ($goods_notonsale_list as $goods) {
         $goods_notonsale_array[$goods['goods_id']] = $goods;
      }
      $NotOnSaleCart_list = array();
      foreach ((array) $cart_list as $key => $cart_info) {
         if (intval($cart_info['bl_id'])) {
            continue;
         }

         if (in_array($cart_info['goods_id'], array_keys($goods_notonsale_list))) {
            $goods_notonsale_info                  = $goods_notonsale_array[$cart_info['goods_id']];
            $NotOnSaleCart_list[$key]['goods_num'] = array_merge($cart_info, $goods_notonsale_info);
         }
      }
      return $NotOnSaleCart_list;
   }

   /**
    *  直接购买时，判断商品是不是正在抢购中，如果是，按抢购价格计算，购买数量若超过抢购规定的上限，则按抢购上限计算
    * @param array $cart_list
    */
   public function getGroupbuyCartList(&$cart_list) {
      if (!C('promotion_allow') || empty($cart_list)) {
         return;
      }

      $model_goods = Model('goods');
      foreach ($cart_list as $key => $cart_info) {
         if ('1' === $cart_info['bl_id'] || empty($cart_info['groupbuy_info'])) {
            continue;
         }

         $this->getGroupbuyInfo($cart_info);
         $cart_list[$key] = $cart_info;
      }
   }

   /**
    * 批量判断购物车内的商品是不是限时折扣中，如果购买数量若>=规定的下限，按折扣价格计算,否则按原价计算
    * 并标识该商品为限时商品
    * @param array $cart_list
    */
   public function getXianshiCartList(&$cart_list) {
      if (!C('promotion_allow') || empty($cart_list)) {
         return;
      }

      foreach ($cart_list as $key => $cart_info) {
         if ('1' === $cart_info['bl_id'] || empty($cart_info['xianshi_info'])) {
            continue;
         }

         $this->getXianshiInfo($cart_info, $cart_info['goods_num']);
         $cart_list[$key] = $cart_info;
      }
   }

   /**
    * 取得购物车商品的赠品列表[商品级赠品]
    *
    * @param array $cart_list
    */
   private function _getGiftCartList(&$cart_list) {
      foreach ($cart_list as $k => $cart_info) {
         if ($cart_info['bl_id']) {
            continue;
         }

         $this->_getGoodsGiftList($cart_info);
         $cart_list[$k] = $cart_info;
      }
   }

   /**
    * 取得购买车内组合销售信息以及包含的商品及有效状态
    * @param array $cart_list
    */
   private function _getBundlingCartList(&$cart_list) {
      if (!C('promotion_allow') || empty($cart_list)) {
         return;
      }

      $model_bl    = Model('p_bundling');
      $model_goods = Model('goods');
      foreach ($cart_list as $key => $cart_info) {
         if (!intval($cart_info['bl_id'])) {
            continue;
         }

         $cart_list[$key]['state']         = true;
         $cart_list[$key]['storage_state'] = true;
         $bl_info                          = $model_bl->getBundlingInfo(array('bl_id' => $cart_info['bl_id']));

         //标志优惠套装是否处于有效状态
         if (empty($bl_info) || !intval($bl_info['bl_state'])) {
            $cart_list[$key]['state'] = false;
         }

         //取得优惠套装商品列表
         $cart_list[$key]['bl_goods_list'] = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));

         //取最新在售商品信息
         $goods_id_array = array();
         foreach ($cart_list[$key]['bl_goods_list'] as $goods_info) {
            $goods_id_array[] = $goods_info['goods_id'];
         }
         $goods_list        = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);
         $goods_online_list = array();
         foreach ($goods_list as $goods_info) {
            $goods_online_list[$goods_info['goods_id']] = $goods_info;
         }
         unset($goods_list);

         //使用最新的商品名称、图片,如果一旦有商品下架，则整个套装置置为无效状态
         $total_down_price = 0;
         foreach ($cart_list[$key]['bl_goods_list'] as $k => $goods_info) {
            if (array_key_exists($goods_info['goods_id'], $goods_online_list)) {
               $goods_online_info = $goods_online_list[$goods_info['goods_id']];
               //如果库存不足，标识false
               if ($cart_info['goods_num'] > $goods_online_info['goods_storage']) {
                  $cart_list[$key]['storage_state'] = false;
               }
               $cart_list[$key]['bl_goods_list'][$k]['goods_id']            = $goods_online_info['goods_id'];
               $cart_list[$key]['bl_goods_list'][$k]['goods_commonid']      = $goods_online_info['goods_commonid'];
               $cart_list[$key]['bl_goods_list'][$k]['store_id']            = $goods_online_info['store_id'];
               $cart_list[$key]['bl_goods_list'][$k]['goods_name']          = $goods_online_info['goods_name'];
               $cart_list[$key]['bl_goods_list'][$k]['goods_image']         = $goods_online_info['goods_image'];
               $cart_list[$key]['bl_goods_list'][$k]['goods_storage']       = $goods_online_info['goods_storage'];
               $cart_list[$key]['bl_goods_list'][$k]['goods_storage_alarm'] = $goods_online_info['goods_storage_alarm'];
               $cart_list[$key]['bl_goods_list'][$k]['gc_id']               = $goods_online_info['gc_id'];
               //每个商品直降多少
               $total_down_price += $cart_list[$key]['bl_goods_list'][$k]['down_price'] = ncPriceFormat($goods_online_info['goods_price'] - $goods_info['bl_goods_price']);
            } else {
               //商品已经下架
               $cart_list[$key]['state']         = false;
               $cart_list[$key]['storage_state'] = false;
            }
         }
         $cart_list[$key]['down_price'] = ncPriceFormat($total_down_price);
      }
   }

   /**
    * 取得每种商品的库存
    * @param array $store_cart_list 购买列表
    * @param array $store_premiums_list 赠品列表
    * @return array 商品ID=>库存
    */
   private function _getEachGoodsStorageQuantity($store_cart_list, $store_premiums_list = array()) {
      if (empty($store_cart_list) || !is_array($store_cart_list)) {
         return array();
      }

      $goods_storage_quangity = array();
      foreach ($store_cart_list as $store_cart) {
         foreach ($store_cart as $cart_info) {
            if (!intval($cart_info['bl_id'])) {
               //正常商品
               $goods_storage_quangity[$cart_info['goods_id']] = $cart_info['goods_storage'];
            } elseif (!empty($cart_info['bl_goods_list']) && is_array($cart_info['bl_goods_list'])) {
               //优惠套装
               foreach ($cart_info['bl_goods_list'] as $goods_info) {
                  $goods_storage_quangity[$goods_info['goods_id']] = $goods_info['goods_storage'];
               }
            }
         }
      }
      //取得赠品商品的库存
      if (is_array($store_premiums_list)) {
         foreach ($store_premiums_list as $store_id => $goods_list) {
            foreach ($goods_list as $goods_info) {
               if (!isset($goods_storage_quangity[$goods_info['goods_id']])) {
                  $goods_storage_quangity[$goods_info['goods_id']] = $goods_info['goods_storage'];
               }
            }
         }
      }
      return $goods_storage_quangity;
   }

   /**
    * 取得每种商品的购买量
    * @param array $store_cart_list 购买列表
    * @return array 商品ID=>购买数量
    */
   private function _getEachGoodsBuyQuantity($store_cart_list) {
      if (empty($store_cart_list) || !is_array($store_cart_list)) {
         return array();
      }

      $goods_buy_quangity = array();
      foreach ($store_cart_list as $store_cart) {
         foreach ($store_cart as $cart_info) {
            if (!intval($cart_info['bl_id'])) {
               //正常商品
               $goods_buy_quangity[$cart_info['goods_id']] += $cart_info['goods_num'];
            } elseif (!empty($cart_info['bl_goods_list']) && is_array($cart_info['bl_goods_list'])) {
               //优惠套装
               foreach ($cart_info['bl_goods_list'] as $goods_info) {
                  $goods_buy_quangity[$goods_info['goods_id']] += $cart_info['goods_num'];
               }
            }
         }
      }
      return $goods_buy_quangity;
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
    * 拼装单条满即送规则页面描述信息
    * @param array $rule_info 满即送单条规则信息
    * @return string
    */
   private function _parseMansongRuleDesc($rule_info) {
      if (empty($rule_info) || !is_array($rule_info)) {
         return;
      }

      $discount_desc = !empty($rule_info['discount']) ? '减' . $rule_info['discount'] : '';
      $goods_desc    = (!empty($rule_info['mansong_goods_name']) && !empty($rule_info['goods_storage'])) ?
      " 送<a href='" . urlShop('goods', 'index', array('goods_id' => $rule_info['goods_id'])) . "' title='{$rule_info['mansong_goods_name']}' target='_blank'>[赠品]</a>" : '';
      return sprintf('满%s%s%s', $rule_info['price'], $discount_desc, $goods_desc);
   }

}

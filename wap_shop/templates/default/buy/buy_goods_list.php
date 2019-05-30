<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
.qz-list-thumb {
    width: 80px;
   height: 80px;
}
.voucher_input input{display:inline-block;padding: 0 3% 0 3%;height:35px}
.voucher_input .az-input{width:75%;}
.voucher_input .ui-btn-lg{width:15%; line-height:1.3;color:#fff; font-size: 14px;height:37px;}
#voucher_msg{color:Red}
</style>
<div class="qz-bk10"></div>
    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">商品清单</div>
    <div class="qz-padding">
    <?php if(!empty($output['ifcart'])){?>
    <a href="index.php?act=cart"><?php echo $lang['cart_step1_back_to_cart'];?></a>
    <?php }?>

<?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
<!--免运费活动-->
        <?php if (!empty($output['cancel_calc_sid_list'][$store_id])) {?>
            <em><i class="icon-gift"></i>店铺活动-免运费</em><?php echo $output['cancel_calc_sid_list'][$store_id]['desc'];?>
        <?php } ?>

<!--       满送活动 -->
        <?php if (!empty($output['store_mansong_rule_list'][$store_id])) {?>
         <em><i class="icon-gift"></i>店铺活动-满即送</em><?php echo $output['store_mansong_rule_list'][$store_id]['desc'];?>
          <?php if (is_array($output['store_premiums_list'][$store_id])) {?>
              <?php foreach ($output['store_premiums_list'][$store_id] as $goods_info) { ?>
                <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="ncc-store-gift" title="<?php echo $goods_info['goods_name']; ?>"><img src="<?php echo cthumb($goods_info['goods_image'],60,$store_id);?>" alt="<?php echo $goods_info['goods_name']; ?>" /></a>
              <?php } ?>
         <?php  } ?>
        <?php } ?>
<!--第二个循环-->
    <?php foreach($cart_list as $cart_info) {?>

        <?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
            <input type="hidden" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" name="cart_id[]">
        <?php } ?>

        <ul class="ui-list qz-top-b">
            <li class="ui-border-t">
                <div class="ui-list-thumb qz-list-thumb" >
                    <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($cart_info,60);?>" alt="" class="qz-img-block" /></a>
                </div>
                <div style="margin:10px 0 0 0">
                    <h4 class="ui-nowrap"><a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank"><?php echo str_cut($cart_info['goods_name'],30,'……'); ?></a></h4>
                    <?php if($cart_info['calendar_type'] == 2){ ?>
                        <p class="ui-nowrap">入住时间：<font class="qz-color2"><?php echo $cart_info['hotel_state_time']; ?></font></p>
                        <p class="ui-nowrap">离店时间：<font class="qz-color2"><?php echo $cart_info['hotel_end_time']; ?></font></p>
                        <p class="ui-nowrap">共：<font class="qz-color2"><?php echo $cart_info['hotel_num']; ?>晚</font></p>
                        <p class="ui-nowrap">总价：￥<font class="qz-color2"> </font><?php echo $cart_info['hotel_total'];$total=$cart_info['hotel_total']; ?></p>
                        <input type="hidden" value="<?php echo $cart_info['hotel_state_time'];?>" name="hotel_state_time">
                        <input type="hidden" value="<?php echo $cart_info['hotel_end_time'];?>" name="hotel_end_time">
                        <input type="hidden" value="<?php echo $cart_info['hotel_num'];?>" name="hotel_num">
                        <input type="hidden" value="<?php echo $cart_info['hotel_total'];?>" name="hotel_total">
                        <input type="hidden" value="<?php echo $cart_info['storage_state'];?>" name="hotel_stock">
                        <input type="hidden" value="2" name="calendar_type">
                        <input type="hidden" value="<?php echo $cart_info['state']; ?>" name="state"><!--判断商品是否能购买必填 1或0 -->
                    <?php }elseif($cart_info['calendar_type'] ==1){//门票普通价格 ?>
                        <!--              --><?php //echo print_r($cart_info);exit; ?>
                            <p class="ui-nowrap">出发时间：<font class="qz-color2"><?php echo $cart_info['start_time']; ?></font></p>
                            <?php if(!empty($cart_info['ticket_type']) and is_array($cart_info['ticket_type'])): foreach($cart_info['ticket_type'] as $item){
                                echo $item;
                            } endif;?>


                            <p class="ui-nowrap">数量：<?php echo  $cart_info['goods_num']; ?></p>
                            <p class="ui-nowrap">总价：￥<font class="qz-color2"><?php  if($cart_info['ifxianshi']==1){echo $_total = $cart_info['price_total'];$total += $_total;}else{echo $_total = $cart_info['goods_price'] * $cart_info['goods_num']; $total += $_total;} ?></font></p>


                            <input type="hidden" value="<?php echo $cart_info['start_time'];?>" name="start_time">
                            <input type="hidden" value="<?php echo $cart_info['price_total'];?>" name="price_total">
                            <input type="hidden" value="<?php echo $cart_info['goods_num'];?>" name="goods_num">
                            <input type="hidden" value="<?php echo $cart_info['storage_state'];?>" name="storage_state">
                            <input type="hidden" value="1" name="calendar_type">

                            <input type="hidden" value="<?php echo $cart_info['state']; ?>" name="state">
                    <?php }elseif($cart_info['calendar_type'] ==3){//高尔夫日历 ?>
                        <p class="ui-nowrap">预定时间：<font class="qz-color2"><?php echo $cart_info['start_time']; ?></font></p>
                        <p class="ui-nowrap">价格：<font class="qz-color2"><?php echo $cart_info['goods_price']; ?></font></p>
                        <p class="ui-nowrap">总价：￥<font class="qz-color2"><?php echo $cart_info['goods_price'];$total=$cart_info['goods_price']; ?></font></p>
                        <input type="hidden" value="<?php echo $cart_info['storage_state'];?>" name="storage_state">
                        <input type="hidden" value="<?php echo $cart_info['goods_price'];?>" name="price_total">
                        <input type="hidden" value="3" name="calendar_type">
                        <input type="hidden" value="<?php echo $cart_info['state']; ?>" name="state">
                    <?php } ?>
                    <?php if(in_array($cart_info['calendar_type'],array(1,2,3))){ ?>
                        <input type="hidden" value="<?php echo $cart_info['calendar_array'];?>" name="calendar_date">
                    <?php } ?>
<!--                    下面的是非价格类型显示的-->
                    <?php  if(!$cart_info['calendar_type']){  ?> <!--  非日历类型才显示 -->

                        <p class="ui-nowrap">单价：￥<font class="qz-color2"><?php echo $cart_info['goods_price']; ?></font></p>
                        <!-- 满降-->
                        <?php if (!empty($cart_info['xianshi_info'])) {?>
                            <span class="xianshi">满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></span>
                        <?php }?>
                        <!--                抢购-->
                        <?php if ($cart_info['ifgroupbuy']) {?>
                            <span class="groupbuy">抢购</span>
                        <?php }?>
                        <!--                直降-->
                        <?php if ($cart_info['bl_id'] != '0') {?>
                            <span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span>
                        <?php }?>
                        <!--                赠品-->
                        <?php if (!empty($cart_info['gift_list'])) { ?>
                            <span class="ncc-goods-gift">赠</span>
                            <ul class="ncc-goods-gift-list">
                                <?php foreach ($cart_info['gift_list'] as $goods_info) { ?>
                                    <li nc_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$goods_info['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_info['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_info['gift_goodsname']; ?>"/></a> </li>
                                <?php } ?>
                            </ul>
                        <?php  } ?>

                        <p class="ui-nowrap">
                            数量：<?php echo $cart_info['state'] ? $cart_info['goods_num'] : ''; ?>
                        </p>
                        <?php if ($cart_info['state'] && $cart_info['storage_state']) {?>
                            <p class="ui-nowrap">小计：￥<em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" nc_type="eachGoodsTotal" style="color:#DA3228;font-size:18px;"><?php echo $cart_info['goods_total']; ?></em>元</p>
                        <?php } elseif (!$cart_info['storage_state']) {?>
                            <span style="color: #F00;">库存不足</span>
                        <?php }elseif (!$cart_info['state']) {?>
                            <span style="color: #F00;">已下架</span>
                        <?php }?>



                        <!-- S bundling goods list -->
                        <?php if (is_array($cart_info['bl_goods_list'])) {?>

                            <?php foreach ($cart_info['bl_goods_list'] as $goods_info) { ?>
                                <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo cthumb($goods_info['goods_image'],60,$store_id);?>" alt="<?php echo $goods_info['goods_name']; ?>" /></a>
                                <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" style="overflow: hidden; text-overflow:ellipsis;white-space:nowrap;"><?php echo $goods_info['goods_name']; ?></a>
                                <em><?php echo $goods_info['bl_goods_price'];?></em>
                            <?php } ?>
                        <?php  } ?>

                    <?php  } ?><!--其他显示结束 -->
                </div>
            </li>
        </ul>


    <?php } ?>
<!--第二个循环结束-->
    </div>
       <div class=" qz-background-white qz-padding">
           买家留言
           <div class="qz-bk15"></div>
           <textarea  name="pay_message[<?php echo $store_id;?>]" class="qz-textarea" placeholder="选填：可填写对本次交易的特别说明" title="选填：可填写对本次交易的特别说明"  maxlength="150"></textarea>
        </div>

         <?php if (!empty($output['available_pd_amount']) && !empty($output['available_rcb_amount'])) { ?>
            <!--<p class="qz-padding">如果二者同时使用，系统优先使用充值卡</p>-->
        <?php } ?>

    <div class="qz-padding qz-background-white clearfix">
            <div class="qz-border-yellow2 clearfix">
                    <!-- S 预存款 & 充值卡 -->
                  <?php if (!empty($output['available_pd_amount']) || !empty($output['available_rcb_amount'])) { ?>
                    <?php if (!empty($output['available_rcb_amount'])) { ?>
                    <style>.qz-border-yellow2{border: 1px solid #f6921e;}</style>
                        <div class="ui-form-item">
                            <span class="qz-f12 qz-fr">
                                使用充值卡（可用金额：<em style="color:#DA3228;font-size:18px;"><?php echo $output['available_rcb_amount'];?></em><?php echo $lang['currency_zh'];?>）
                            </span>
                            <label class="ui-checkbox qz-fr">
                                <input type="checkbox" value="1" name="rcb_pay">
                            </label>
                        </div>
                   <?php } ?>
                   <?php if (!empty($output['available_pd_amount'])) { ?>
                        <div class="ui-form-item clearfix">
                            <span class="qz-f12 qz-fr" style="font-size: 12px">
                                使用预存款（可用金额：<em style="color:#DA3228;font-size:14px;"><?php echo $output['available_pd_amount'];?></em><?php echo $lang['currency_zh'];?>）
                            </span>
                            <label class="ui-checkbox qz-fr">
                                <input type="checkbox" value="1" name="pd_pay">
                            </label>
                        </div>
                  <?php } ?>
                  <div  id="pd_password" style="display: none">
                    <div class="qz-padding qz-padding-t clearfix">
                        <input type="password" class="qz-light qz-border-gray qz-fr" value="" name="password" id="pay-password" maxlength="35" autocomplete="off">
                        <input type="hidden" value="" name="password_callback" id="password_callback">
                        <label class="qz-fr" style="line-height: 30px;">支付密码&nbsp;</label>
                    </div
                    <p class="qz-fr">
                        <a style="margin-right: 10px; float: right" id="pd_pay_submit" class="ui-btn ui-btn-primary qz-padding-30 qz-background-yellow" href="javascript:void(0)">使用</a>
                    </p>
                </div>
                <?php if (!$output['member_paypwd']) {?>
                          <div class="qz-padding qz-padding-t clearfix">
                    <div class="qz-bk10"></div>
                    <p class="qz-fr">还未设置支付密码，<a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" target="_blank">马上设置</a></p>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
          <!-- E 预存款 -->
    </div>

<div style="padding:0 1% 0 1%">
        <div class="qz-bk10"></div>
        <span class="qz-fl">运费：</span>
        <span class="qz-fr">￥<em id="eachStoreFreight_<?php echo $store_id;?>" style="color:#DA3228;">0.00</em><?php echo $lang['currency_zh'];?></span>
        <div class="qz-bk5"></div>

    <?php  if($cart_info['calendar_type']){  ?> <!--  日历类型才显示 -->
        <span class="qz-fl">商品金额：</span>
        <span class="qz-fr">￥<em id="eachStoreGoodsTotal_<?php echo $store_id;?>" style="color:#DA3228;"><?php echo $total ?></em><?php echo $lang['currency_zh'];?></span>
    <?php }else{ ?>
        <span class="qz-fl">商品金额：</span>
        <span class="qz-fr">￥<em id="eachStoreGoodsTotal_<?php echo $store_id;?>" style="color:#DA3228;"><?php echo $output['store_goods_total'][$store_id];?></em><?php echo $lang['currency_zh'];?></span>
    <?php } ?><!--  日历类型才显示结束 -->

    <div class="qz-bk5"></div>
		<?php if (!empty($output['store_mansong_rule_list'][$store_id]['discount'])) {?>
        <span class="qz-fl">
              满即送-<?php echo $output['store_mansong_rule_list'][$store_id]['desc'];?>：
		</span>
        <span class="qz-fr">￥<em id="eachStoreManSong_<?php echo $store_id;?>" style="color:#DA3228;">-<?php echo $output['store_mansong_rule_list'][$store_id]['discount'];?></em><?php echo $lang['currency_zh'];?></span>
		<?php } ?>
	 <div class="qz-bk10"></div>

<!-- S voucher list 代金券流程-->
            <?php if (!empty($output['store_voucher_list'][$store_id]) && is_array($output['store_voucher_list'][$store_id])) {?>
                <div>
                      <select nctype="voucher" class="qz-select" style="width:75%;height:35px; display: inline-block" name="voucher[<?php echo $store_id;?>]">
                          <option value="|<?php echo $store_id;?>|0.00||">选择代金券</option>
                          <?php foreach ($output['store_voucher_list'][$store_id] as $voucher) {?>
                          <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $store_id;?>|<?php echo $voucher['voucher_price'];?>|<?php echo $voucher['voucher_id']; ?>|<?php echo $voucher['voucher_limit']; ?>"><?php echo $voucher['desc'];?></option>
                          <?php } ?>
                      </select>
                      <span style="float:right;display: inline-block; line-height:35px"><em id="eachStoreVoucher_<?php echo $store_id;?>">-0.00</em><?php echo $lang['currency_zh'];?></span>
                </div>
                  <div class="qz-bk10"></div>
            <?php }else{ ?>
                 <div class="voucher_input" style="display:none">
                     <input type="hidden" id="voucher_user"  name="voucher_user"/>
                     <input type="text" class="az-input" id="voucher_code" placeholder="请输入优惠券编码"/>
                     <input type="button" class='ui-btn-lg qz-background-yellow' id="bt_voucher_user" value='使用'/>
                 </div>
                 <span id="voucher_msg"></span>
                 <div class="qz-bk10"></div>
            <?php }?>
    <!-- E voucher list 代金券结束-->
              <div style="display:none;">
                本店合计：<em store_id="<?php echo $store_id;?>" nc_type="eachStoreTotal"></em><?php echo $lang['currency_zh'];?>
			  </div>


<?php }?><!-- 最大循环结束-->


    <?php  if($cart_info['calendar_type']){  ?>
        <div class="qz-padding qz-background-blue qz-color7 clearfix" style="border-radius:3px">
            <span class="qz-fl">订单总金额：</span>
            <span class="qz-fr">￥<em id="orderTotal"><?php echo $total;?></em></span>
        </div>
    <?php }else{?>
        <div class="qz-padding qz-background-blue qz-color7 clearfix" style="border-radius:3px">
            <span class="qz-fl">订单总金额：</span>
            <span class="qz-fr">￥<em id="orderTotal"></em><?php echo $lang['currency_zh'];?></span>
        </div>
    <?php } ?>
<style>
    @media screen and (-webkit-min-device-pixel-ratio: 2)
        .ui-btn:before, .ui-btn-lg:before, .ui-btn-s:before {display: none}
</style>

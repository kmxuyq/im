<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>消息设置</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member_message&op=systemmsg'"></i>
    <h1 class="qz-color">消息接收设置</h1>
	<!-- delete by tanzn start
    <span class="user_setInfo"></span>
    <ul class="user_setInfo_cell">
       <li onclick="location.href = 'index.php?act=member_message&op=setting'"> 接收设置</li>
    </ul>
	delete by tanzn stop -->
</header>
<form method="post" action="index.php?act=member_message&op=setting" id="setting_form" >
<input type="hidden" name="form_submit" value="ok">
<div class="info_setting">
    <div class="info_setting_wrap">
        <h3 class="setting_tt">订单交易通知</h3>
        <div class="setting_cell <?php if ($output['setting_array']['order_payment_success'] || !isset($output['setting_array']['order_payment_success'])) {?>on<?php }?>">
            <p>付款成功提示</p>
             <input type="checkbox" name="order_payment_success" id="order_payment_success" value="1" <?php if ($output['setting_array']['order_payment_success'] || !isset($output['setting_array']['order_payment_success'])) {?>checked<?php }?> />
        </div>
        <div class="setting_cell <?php if ($output['setting_array']['order_deliver_success'] || !isset($output['setting_array']['order_deliver_success'])) {?>on<?php }?>">
            <p>商品出库提示</p>
             <input type="checkbox" name="order_deliver_success" id="order_deliver_success" value="1" <?php if ($output['setting_array']['order_deliver_success'] || !isset($output['setting_array']['order_deliver_success'])) {?>checked<?php }?> />
        </div>
        <div class="setting_cell <?php if ($output['setting_array']['vr_code_will_expire'] || !isset($output['setting_array']['vr_code_will_expire'])) {?>on<?php }?>">
            <p>兑换码即将到期提醒</p>
             <input type="checkbox" name="vr_code_will_expire" id="vr_code_will_expire" value="1"  <?php if ($output['setting_array']['vr_code_will_expire'] || !isset($output['setting_array']['vr_code_will_expire'])) {?>checked<?php }?>/>
        </div>
    </div>
    <div class="info_setting_wrap">
        <h3 class="setting_tt">余额卡券提醒</h3>
        <div class="setting_cell <?php if ($output['setting_array']['predeposit_change'] || !isset($output['setting_array']['predeposit_change'])) {?>on<?php }?> ">
            <p>余额变动提醒</p>
             <input type="checkbox" name="predeposit_change" id="predeposit_change" value="1" <?php if ($output['setting_array']['predeposit_change'] || !isset($output['setting_array']['predeposit_change'])) {?>checked<?php }?>/>
        </div>
		<!-- delete by tanzn start
        <div class="setting_cell <?php if ($output['setting_array']['recharge_card_balance_change'] || !isset($output['setting_array']['recharge_card_balance_change'])) {?>on<?php }?>">
            <p>充值卡余额变动提醒</p>
            <input type="checkbox" name="recharge_card_balance_change" id="recharge_card_balance_change" value="1" <?php if ($output['setting_array']['recharge_card_balance_change'] || !isset($output['setting_array']['recharge_card_balance_change'])) {?>checked<?php }?> />
        </div>
		delete by tanzn end -->
        <div class="setting_cell <?php if ($output['setting_array']['voucher_use'] || !isset($output['setting_array']['voucher_use'])) {?>on<?php }?>">
            <p>代金券使用提醒</p>
            <input type="checkbox" name="voucher_use" id="voucher_use" value="1" <?php if ($output['setting_array']['voucher_use'] || !isset($output['setting_array']['voucher_use'])) {?>checked<?php }?> />
              
        </div>
        <div class="setting_cell <?php if ($output['setting_array']['voucher_will_expire'] || !isset($output['setting_array']['voucher_will_expire'])){?>on<?php }?>">
            <p>代金券即将到期提醒</p>
            <input type="checkbox" name="voucher_will_expire" id="voucher_will_expire" value="1" <?php if ($output['setting_array']['voucher_will_expire'] || !isset($output['setting_array']['voucher_will_expire'])){?>checked<?php }?> />
              
        </div>
    </div>
    <div class="info_setting_wrap">
        <h3 class="setting_tt">售后服务消息</h3>
        <div class="setting_cell <?php if ($output['setting_array']['refund_return_notice'] || !isset($output['setting_array']['refund_return_notice'])) {?>on<?php }?>">
            <p>退换货消息</p>
            <input type="checkbox" name="refund_return_notice" id="refund_return_notice" value="1" <?php if ($output['setting_array']['refund_return_notice'] || !isset($output['setting_array']['refund_return_notice'])) {?>checked<?php }?> />
        </div>
        <div class="setting_cell <?php if ($output['setting_array']['arrival_notice'] || !isset($output['setting_array']['arrival_notice'])) {?>on<?php }?> ">
            <p>到货通知消息</p>
            <input type="checkbox" name="arrival_notice" id="arrival_notice" value="1" <?php if ($output['setting_array']['arrival_notice'] || !isset($output['setting_array']['arrival_notice'])) {?>checked<?php }?> />
              
        </div>
		<!-- delete by tanzn start
        <div class="setting_cell  <?php if ($output['setting_array']['consult_goods_reply'] || !isset($output['setting_array']['consult_goods_reply'])) {?>on<?php }?> ">
            <p>商品咨询回复提示</p>
            <input type="checkbox" name="consult_goods_reply" id="consult_goods_reply" value="1" <?php if ($output['setting_array']['consult_goods_reply'] || !isset($output['setting_array']['consult_goods_reply'])) {?>checked<?php }?> />
             
        </div>
		delete by tanzn stop -->
        <div class="setting_cell <?php if ($output['setting_array']['consult_mall_reply'] || !isset($output['setting_array']['consult_mall_reply'])) {?>on<?php }?>">
            <p>平台咨询回复提示</p>
             <input type="checkbox" name="consult_mall_reply" id="consult_mall_reply" value="1" <?php if ($output['setting_array']['consult_mall_reply'] || !isset($output['setting_array']['consult_mall_reply'])) {?>checked<?php }?> />
             
        </div>
    </div>
</div>
<div class="service_subBtn">
    <input type="button" id="btn_inform_submit" class="public_btn1" value="保存设置">
</div>
</form>
<script>
$(function(){
	$('#btn_inform_submit').click(function(){
		$('#setting_form').submit();
	})
    $('.user_setInfo').click(function(){
        if($('.user_setInfo_cell').css('display') ==='none'){
			$('.user_setInfo_cell').fadeIn()
            $('.block_mark').fadeIn()
		}else{
			$('.user_setInfo_cell').fadeOut()
           $('.block_mark').fadeOut()
		}
    });
    /**/
    $('.setting_cell').each(function(){
		$(this).click(function(){
		    if($(this).hasClass('on')){
			    $(this).removeClass('on')
				$(this).find('input').removeAttr('checked')
		    }else{
				$(this).addClass('on')
				$(this).find('input').attr('checked','checked')
			}
		})
	})
});
</script>
<script type="text/javascript">TouchSlide({ slideCell:"#leftTabBox" });</script>
<style>
    body{ background:#f5f5f5;}
</style>
</body>
</html>
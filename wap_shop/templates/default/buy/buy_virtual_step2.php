<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>填写核对购物信息-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL?>/css/style.css" />
<!--<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>-->
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script> <!--替换alert -->
<script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go(-1)" ></i>
    <h1 class="qz-color">填写核对购物信息</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<form action="<?php echo urlShopWAP('buy_virtual','buy_step3');?>" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
    <input type="hidden" name="quantity" value="<?php echo $output['goods_info']['quantity'];?>">
    <input type="hidden" name="is_share" value="<?php echo $output['goods_info']['isshare'];?>">
    <input type="hidden" name="goods_name" value="<?php echo $output['goods_info']['goods_name'];?>">
    <input type="hidden" name="goods_price" value="<?php echo $output['goods_info']['goods_price'];?>">
    <input type="hidden" name="goods_total" value="<?php echo $output['goods_info']['goods_total'];?>">
    <input type="hidden" name="calendar_type" value="<?php echo $output['goods_info']['calendar_type'];?>">
    <input type="hidden" name="ticket_date" value="<?php echo $output['goods_info']['ticket_date'];?>">
    <input type="hidden" name="get_info" value="<?php echo $_POST["get_info"];?>">
    <div class="qz-padding">
        <p>电子兑换码/券接收方式<br>

		<h5>请仔细填写手机号，以确保电子兑换码准确发到您的手机。</h5></p>
        <div class="qz-bk10"></div>
		<input name="buyer_phone" class="qz-txt3 qz-light3 qz-border" placeholder="请填写手机号码"  autocomplete = "off"  type="text" id="buyer_phone" value="<?php echo $output['member_info']['member_mobile'];?>" maxlength="11">
        <h5>您本次购买的商品不需要收货地址，请正确输入接收手机号码，确保及时获得“电子兑换码”。可使用您已经绑定的手机或重新输入其它手机号码。</h5>
    </div>

    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">
        套餐类型
    </div>

    <div class="qz-padding">店铺名称：<a href="<?php echo urlShopWAP('show_store','index',array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $output['store_info']['store_name'];?></a> <span member_id="<?php echo $output['store_info']['member_id'];?>"></span></div>

    <ul class="ui-list qz-top-b">
        <li class="ui-border-t">
            <div class="ui-list-thumb qz-list-thumb">
			<a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($output['goods_info'],240);?>" alt="<?php echo $output['goods_info']['goods_name']; ?>"  class="qz-img-block"/></a>

            </div>
            <div class="ui-list-info qz-light">
                <h4 class="ui-nowrap"><a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank"><?php echo $output['goods_info']['goods_name']; ?></a> </h4>
				<?php if ($output['goods_info']['ifgroupbuy']) { ?>
                <span class="groupbuy">抢购</span>
                <?php } ?>
                <p class="ui-nowrap"><span class="qz-f18">￥<em id="item_price" style="color:#DA3228;font-size:18px;"><?php
if($output["goods_info"]['is_virtual']=='1'){
	echo $output['goods_info']['goods_total'];//虚拟商品显示总价
}else{
	echo $output['goods_info']['goods_price'];//实物商品显示单价
}?></em></p>
            </div>
        </li>
    </ul>
<div class=" qz-background-white qz-padding">
	   买家留言
	   <div class="qz-bk15"></div>
          <textarea name="buyer_msg"  class="qz-textarea" placeholder="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）" title="选填：对本次交易的说明（建议填写已经和商家达成一致的说明）"  maxlength="150"></textarea>
        </div>
<div class="qz-padding qz-background-white clearfix">

   <div class="qz-border-yellow clearfix">
	<!-- S 预存款/充值卡 -->
    <?php if ($output['member_info']['available_predeposit'] > 0 || $output['member_info']['available_rc_balance'] > 0) { ?>

            <div class="ui-form-item">
			<?php if ($output['member_info']['available_rc_balance'] > 0) { ?>
                <span class="qz-f12 qz-fr">使用充值卡（可用金额：<em><?php echo $output['member_info']['available_rc_balance'];?></em>元）</span>
                <label class="ui-checkbox qz-fr">
            <input type="checkbox" value="1" name="rcb_pay">
                </label>
			<?php } ?>
			<?php if ($output['member_info']['available_predeposit'] > 0) { ?>
			<span class="qz-f12 qz-fr" style="font-size: 12px">使用预存款（可用金额：<em  style="font-size: 14px"><?php echo $output['member_info']['available_predeposit'];?></em>元）</span>
                <label class="ui-checkbox qz-fr">
            <input type="checkbox"  value="1" name="pd_pay">
        <?php } ?>
      <?php if ($output['member_info']['available_predeposit'] > 0 && $output['member_info']['available_rc_balance'] > 0) { ?>
      <!--如果二者同时使用，系统优先使用充值卡&nbsp;&nbsp;-->
      <?php } ?>
            </div>
			<div  id="pd_password"  style="display: none">
            <div class="qz-padding qz-light" style="text-align:right; clear: both">
			支付密码：&nbsp;<input type="password" class="qz-light qz-border-gray qz-fr" value="" name="password" id="pay-password" maxlength="35">
            <input type="hidden" value="" name="password_callback" id="password_callback">
            </div>
            <span class="qz-padding qz-padding-t clearfix">
                <p class="qz-fr">
				<a style="margin-right: 10px;" class="ui-btn ui-btn-primary qz-padding-30 qz-background-yellow"  id="pd_pay_submit" href="javascript:void(0)">使用</a>
                </p>
				</span>
				</div>
                <div class="qz-bk10"></div>
				<div class="qz-padding qz-padding-t clearfix">
				<p class="qz-fr">
				<?php if (!$output['member_info']['member_paypwd']) {?>
              还未设置支付密码，<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" target="_blank">马上设置</a>
              <?php } ?>
                </p>
            </div>
		<?php } ?>
		<!-- E 预存款 -->

		<!-- S voucher list -->
		<?php if (count($output['store_voucher_list']) > 0): ?>
         <div class="ui-form-item">
		 <select nctype="voucher" name="voucher">
                  <option value="|0.00">选择代金券</option>
                  <?php foreach ($output['store_voucher_list'] as $voucher) {?>
                  <option value="<?php echo $voucher['voucher_t_id'];?>|<?php echo $voucher['voucher_price'];?>"><?php echo $voucher['desc'];?></option>
                  <?php } ?>
                </select> ：
              ￥<em id="storeVoucher">-0.00</em></div>
         <?php endif; ?>
		 <!-- E voucher list -->
	</div>
    <div class="qz-bk10"></div>

    <div class="qz-padding qz-background-blue qz-color7 clearfix" style="background: #fff; color: #333">
        <span class="qz-fl">订单总金额：</span>
        <span class="qz-fr">￥<em id="item_subtotal" style="color:#DA3228;font-size:18px;"><?php echo $output['goods_info']['goods_total'];?></em></span>
    </div>
</form>
<div class="ui-btn-wrap">
	<a id="submitOrder" href="javascript:void(0)" class="ui-btn ui-btn-lg ui-btn-primary qz-padding-30 qz-background-yellow">提交订单</a>
 </div>

</section>

<!--<div id="menu"></div>-->

<script src="<?php echo RESOURCE_SITE_URL;?>/js/input_max.js"></script>
<script>
//input内容放大
$(function(){
	new TextMagnifier({
		inputElem: '.inputElem',
			align: 'top'
	});
});

//计算应支付金额计算
function calcOrder() {
    var allTotal = parseFloat($('#item_subtotal').html());
    if ($('#storeVoucher').length > 0) {
      console.log(parseFloat($('#storeVoucher').html()))
      console.log(allTotal)
    	allTotal += parseFloat($('#storeVoucher').html());
      console.log(allTotal)
    }
    $('#item_subtotal').html(number_format(allTotal,2));
}

$(document).ready(function(){

    $('select[nctype="voucher"]').on('change',function(){
        if ($(this).val() == '') {
        	$('#storeVoucher').html('-0.00');
        } else {
            var items = $(this).val().split('|');
            $('#storeVoucher').html('-'+number_format(items[1],2));
        }
        calcOrder();
    });

    <?php if ($output['member_info']['available_predeposit'] > 0 || $output['member_info']['available_rc_balance'] > 0) { ?>
    function showPaySubmit() {
        if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) {
        	$('#pay-password').val('');
        	$('#password_callback').val('');
        	$('#pd_password').show();
        } else {
        	$('#pd_password').hide();
        }
    }
    <?php } ?>

    <?php if ($output['member_info']['available_rc_balance'] > 0) { ?>
    $('input[name="rcb_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
        	if (<?php echo $output['member_info']['available_rc_balance']; ?> >= parseFloat($('#orderTotal').html())) {
        		$('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="pd_pay"]').attr('disabled',false);
    	}
    });
    <?php } ?>

    <?php if ($output['member_info']['available_predeposit'] > 0) { ?>
    $('input[name="pd_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
        	if (<?php echo $output['member_info']['available_predeposit'] ?> >= parseFloat($('#orderTotal').html())) {
            	$('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="rcb_pay"]').attr('disabled',false);
    	}
    });
    <?php } ?>

    $('#pd_pay_submit').on('click',function(){
        if ($('#pay-password').val() == '') {
            layer.msg('请输入支付密码!');return false;
        }
        $('#password_callback').val('');
		$.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
            if (data == '1') {
            	$('#password_callback').val('1');
            	$('#pd_password').hide();
            } else {
            	$('#pay-password').val('');
                layer.msg('密码错误,请重新输入');

            }
        });
    });

    var SUBMIT_FORM = true;
    $('#submitOrder').on('click',function(){

        if (!$("#form_buy").valid()) return;
    	if (!SUBMIT_FORM) return;
    	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
    		showDialog('使用充值卡/预存款支付，需输入支付密码并使用  ', 'error','','','','','','','','',2);
    		return;
    	}
        var mobile = $("#buyer_phone").val();// 手机号码
        if(mobile ==''){
            alertPopWin('请填写手机号码','close');
            return;
        }
        var mobileRule = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0-9]|170)\d{8}$/;
        if (!mobileRule.test(mobile)) {
            //自定义错误提示
            alertPopWin('手机号码格式错误','close');
            return;
        }
    	SUBMIT_FORM = false;
    	$('#form_buy').submit();
    });
});
</script>
<style>
    @media screen and (-webkit-min-device-pixel-ratio: 2)
        .ui-btn:before, .ui-btn-lg:before, .ui-btn-s:before {display: none}
</style>

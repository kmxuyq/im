<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>在线支付-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
</head>
<style>
body{font-size: 14px;}
</style>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="location.href = 'index.php?act=goods&op=index&goods_id=<?php echo $output['goods']['goods_id'];?>'" ></i>
    <h1 class="qz-color">在线支付</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<form action="index.php?act=payment&op=real_order" method="POST" id="buy_form">
	<input type="hidden" name="pay_sn" value="<?php echo $output['pay_sn'];?>">
	<input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="qz-padding">下单成功，请即时付款！查看<a href="index.php?act=member_order" target="_blank">我的订单</a><?php if ($output['pay_amount_online'] > 0) {?>在线支付金额： ￥<em style="color:#DA3228;font-size:18px;"><?php echo $output['pay_amount_online']; } ?></em>元</div>
	 <?php if (count($output['order_list'])>1) {?>
            由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']);?>个不同子订单配送！
          <?php }?>
    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">
	<?php if(!empty($output['order_list']) and is_array($output['order_list'])) : foreach ($output['order_list'] as $key => $order) {?>
        <div class="qz-bottom-b">
            <p>订单号：<?php echo $order['order_sn']; ?></p>
            <div class="qz-bk5"></div>
            <p class="qz-color9"></p>
            <div class="qz-bk5"></div>
			<p>支付方式：<?php echo $order['payment_state'];?></p>
            <div class="qz-bk5"></div>
            <p class="qz-color9"></p>
            <div class="qz-bk5"></div>
            <p>商品名称：<?php echo $output['goods']['goods_name']; ?></p>
            <div class="qz-bk5"></div>
            <p class="qz-color9"></p>
            <div class="qz-bk10"></div>
        </div>
        <?php } endif; ?>

        <?php if(!empty($output['vr_order_list']) and is_array($output['vr_order_list'])) : foreach ($output['vr_order_list'] as $key => $order) {?>
             <div class="qz-bottom-b">
                 <p>订单号：<?php echo $order['order_sn']; ?></p>
                 <div class="qz-bk5"></div>
                 <p class="qz-color9"></p>
                 <div class="qz-bk5"></div>
     			<p>支付方式：<?php echo $order['payment_state'];?></p>
                 <div class="qz-bk5"></div>
                 <p class="qz-color9"></p>
                 <div class="qz-bk5"></div>
                 <p>商品名称：<?php echo $order['goods_name']; ?></p>
                 <div class="qz-bk5"></div>
                 <p class="qz-color9"></p>
                 <div class="qz-bk10"></div>
             </div>
             <?php } endif; ?>

        <div class="qz-bk10"></div>

		<?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
	  <?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?act=member_message&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?>
      <?php } else {?>
        <p>支付选择</p>
        <div class="qz-bk10"></div>

<ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <center><div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /></div> </center>
          </label>
        </li>
        <?php } ?>

        <li payment_code="wxpay_2" id="wxpay_2" style="display:none">
          <label for="pay_wxpay2">
          <i></i>
          <div class="logo" for="pay_7"> <img src="/wap_shop/templates/default/images/payment/wxpay_logo2.gif"> </div>
          </label>
        </li>
        <li payment_code="alipay_2" id="alipay_2" style="display:none">
          <label for="pay_alipay2">
          <i></i>
          <div class="logo" for="pay_8"> <img src="/wap_shop/templates/default/images/payment/alipay_logo.gif"> </div>
          </label>
        </li>

      </ul>
      <?php } ?>



    </div>

    <div class="qz-bk10"></div>

<?php if ($output['pay_amount_online'] > 0) {?>
    <div class="ui-btn-wrap"><a href="javascript:void(0);" id="next_button" class="ui-btn ui-btn-lg ui-btn-primary qz-padding-30 qz-background-yellow"><i class="icon-shield"></i>确认提交支付</a></div>
    <?php }?>


</form>
</section>
<script type="text/javascript">
$(function(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger"){
		$('#wxpay_2').show();
    $('li[payment_code="alipay"]').hide();
	}
	//---------------------
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });
    $('#next_button').on('click',function(){

        if ($('#payment_code').val() == '') {
        	showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }
        //-------------
        if($('#payment_code').val()=='wxpay_2'&&ua.match(/MicroMessenger/i)=="micromessenger"){
			window.location.href='<?php echo $output["wxpay_jsapi_url"]?>';return false;
         }else if($('#payment_code').val()=='alipay_2'){
        	 window.location.href='<?php echo $output["alipay_api_url"]?>';return false;
         }
        else{
       		 $('#buy_form').submit();
		}
    });

});
</script>

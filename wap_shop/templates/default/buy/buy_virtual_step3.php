<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>在线支付-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<!--<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>-->

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="location.href='index.php?act=member_vr_order&state_type=10'" ></i>
    <h1 class="qz-color">在线支付</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<form action="index.php?act=payment&op=vr_order" method="POST" id="buy_form">
    <input type="hidden" name="order_sn" value="<?php echo $output['order_info']['order_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="qz-padding">下单成功，请即时付款！查看<a href="index.php?act=member_vr_order&op=show_order&order_id=<?php echo $output['order_info']['order_id'];?>" target="_blank">我的订单</a>在线支付金额：
	￥<em style="color:#DA3228;font-size:18px;"><?php echo $output['order_info']['goods_price']; ?></em>元 <?php echo $output['order_info']['pay_message']?></div>
    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">
        <div class="qz-bottom-b">
            <p>订单号：<?php echo $output['order_info']['order_sn']; ?></p>
            <div class="qz-bk5"></div>
            <p>商&nbsp;&nbsp; 品：<?php echo $output['order_info']['goods_name']; ?></p>
			<div class="qz-bk5"></div>
			<p>价&nbsp;&nbsp;格：￥<em style="color:#DA3228;font-size:18px;"><?php echo $output['order_info']['goods_price']; ?></em></p>
            <div class="qz-bk5"></div>
           <p>数&nbsp;&nbsp;量：<em style="color:#DA3228;font-size:18px;"><?php echo $output['order_info']['goods_num']; ?></em></p>
            <div class="qz-bk10"></div>
        </div>

        <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?act=member_message&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
	  <div class="qz-bk10"></div>
        <h3>支付选择</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
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
    <div class="ncc-bottom tc mb50">
   <span style='color:#f00' id="golf_stock_message"></span>
    <a href="javascript:void(0);" id="next_button" class="ui-btn ui-btn-lg ui-btn-primary qz-padding-30 qz-background-yellow"><i class="icon-shield"></i>确认支付(￥<em style="color:#44b549;font-size:18px;"><?php echo $output['order_info']['order_amount']; ?></em>元)</a></div>
  </form>

<div id="menu"></div>
</section>

</div>
<script type="text/javascript">
$(function(){
	var ua = navigator.userAgent.toLowerCase();
	if(ua.match(/MicroMessenger/i)=="micromessenger"){
		$('#wxpay_2').show();
	}
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
    });

    $('#next_button').on('click',function(){
		$('#golf_stock_message').html('');
        if ($('#payment_code').val() == '') {
        	$('#golf_stock_message').html('请选择支付方式！');return false;
        	//showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }
		//如果是高尔夫判断商品是否已支付,如果已有时段被订购支付了，则提示
//         get_golf_minute_status();
         if ($('#payment_code').val() == 'wxpay_2' && ua.match(/MicroMessenger/i) == "micromessenger") {

            window.location.href='<?php echo $output["wxpay_jsapi_url"]?>';return false;//这里跳转后要加上退出，否则无效
         }
         else if ($('#payment_code').val() == 'alipay_2') {
                 window.location.href = '<?php echo $output["alipay_api_url"]?>';return false;
             }
         else{
       		 $('#buy_form').submit();
		}
    });
	//如果是高尔夫判断商品是否已支付,如果已有时段被订购支付了，则提示
	function get_golf_minute_status(){
        var type_id='<?php echo $output["order_info_arr"]["type_id"]?>';
        if(type_id=='46'){
            var commonid='<?php echo $output["order_info_arr"]["commonid"]?>';
            var date='<?php echo $output["order_info_arr"]["date"]?>';
            var golf_minute='<?php echo $output["order_info_arr"]["golf_minute"]?>';
            var minute_stock_url='/wap_shop/index.php?act=buy_virtual&op=get_golf_minute_stock_status&commonid='+commonid+'&date='+date+'&golf_minute='+ encodeURIComponent(golf_minute);

			$.get(minute_stock_url,function(data){
				if(data!=''){
					//显示时段已被 订购的消息
					alert(data);
					window.location.href='<?php echo $_SERVER["REQUEST_URL"]?>';return false;
					//$('#golf_stock_message').html(data);return false;
				}
			});
        }
	}
	//------------------------
});
</script>

<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>在线支付-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL; ?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL; ?>/js/menu.js"></script>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color"><?php echo $lang['cart_index_payment']; ?></h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<form action="index.php?act=payment&op=pd_order" method="POST" id="buy_form">
    <input type="hidden" name="pdr_sn" value="<?php echo $output['pdr_info']['pdr_sn']; ?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="qz-padding">您已申请账户余额充值；<p>请查看<a href="index.php?act=predeposit&op=index">我的充值列表 ；</a><p>充值金额：￥<em style="color:#DA3228;font-size:18px;"><?php echo $output['pdr_info']['pdr_amount']; ?></em>元</div>
    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">
        <div class="qz-bottom-b">
            <p>充值单号 : <?php echo $output['pdr_info']['pdr_sn']; ?></p>
            <div class="qz-bk5"></div>
        </div>

        <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php } else if (empty($output['payment_list'])) {?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?act=member_message&op=sendmsg&member_id=<?php echo $output['order']['seller_id']; ?>"><?php echo $lang['cart_step2_paymentnull_2']; ?></a> <?php echo $lang['cart_step2_paymentnull_3']; ?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
    <div class="qz-bk10"></div>
        <h3>支付选择</h3>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach ($output['payment_list'] as $val) {?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL ?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          </label>
        </li>
        <?php }?>
    <li payment_code="wxpay_2" id="wxpay_2" style="display:none">
          <label for="pay_wxpay2">
          <i></i>
          <div class="logo" for="pay_7"> <img src="/wap_shop/templates/default/images/payment/wxpay_logo2.gif"> </div>
          </label>
        </li>
         <li payment_code="alipay_2" style="display:none" id="alipay_2">
          <label for="pay_alipay2">
          <i></i>
          <div class="logo" for="pay_8"> <img src="/wap_shop/templates/default/images/payment/alipay_logo.gif"> </div>
          </label>
        </li>
      </ul>
      <?php }?>
    </div>
    <div class="ncc-bottom tc mb50"><a href="javascript:void(0);" id="next_button" class="ui-btn ui-btn-lg ui-btn-primary qz-padding-30 qz-background-yellow" style="margin:10px 0 0 -6px;padding:5px;"><i class="icon-shield"></i>确认支付(￥<em style="color:#FFF;font-size:18px;"><?php echo $output['pdr_info']['pdr_amount']; ?></em>)</a></div>
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
        if ($('#payment_code').val() == '') {
          showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }


        if($('#payment_code').val()=='wxpay_2'&&ua.match(/MicroMessenger/i)=="micromessenger"){
      window.location.href='<?php echo $output["wxpay_jsapi_url"] ?>';
         }else if($('#payment_code').val()=='alipay_2'){
           window.location.href='<?php echo $output["alipay_api_url"] ?>';
         }
        else{
           $('#buy_form').submit();
    }
    });
});
</script>

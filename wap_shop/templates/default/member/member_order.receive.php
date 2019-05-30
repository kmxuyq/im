<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>确认收货</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">确认收货</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php if ($output['order_info']) {?>
<form id="form-box" action="index.php?act=member_order&op=change_state&state_type=order_receive&order_id=<?php echo $output['order_info']['order_id']; ?>" method="post" >
<input type="hidden" name="form_submit" value="ok" />
<div class="order_number_wrap">
    <div class="order_number_tt2">您是否确定已经收到以下订单的货品?</div>
    <div class="order_number"><?php echo $lang['member_change_order_no'].$lang['nc_colon'];?> <?php echo trim($_GET['order_sn']); ?></div>
    <div class="order_number_tip">请注意： 如果你尚未收到货品请不要点击“确认”。大部分被骗案件都是由于提前确认付款被骗的，请谨慎操作！</div>
</div>
<div class="service_subBtn">
    <input type="button" id="form-submit" class="public_btn1" value="确认">
    <input type="button" class="public_btn2"onclick="location.href = 'index.php?act=member_order&state_type=state_send'" value="返回">
</div>
</form>
<?php }else{ ?>
<div class="order_number_wrap">
    <div class="order_number_tt2">该订单并不存在，请检查参数是否正确!</div>
</div>
<div class="service_subBtn">
    <input type="button" class="public_btn2" onclick="location.href = 'index.php?act=member_order&state_type=state_send'" value="返回">
</div>
<?php } ?>

<!---->
<script type="text/javascript">
$(function(){
	$('#form-submit').click(function(){
		if(confirm('确定收货后将不能更改，\n你确定已收到订单中所有商品吗?')){
			$('#form-box').submit()
		}else{
			window.location.href = 'index.php?act=member_order&state_type=state_send'
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













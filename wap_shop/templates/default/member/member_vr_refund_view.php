<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>虚拟物品退款</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?PHP ECHO SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退款服务</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 同意退款后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中。</p>
    <p class="text">2. 如果平台不同意退款，自动解除兑换码的锁定状态，在有效期内可以继续兑换使用。</p>
</div>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>买家<br>申请退款</span>
            <b class="radius_dot"></b>
        </li>
        <li class="active">
            <span>商家处理<br>退款申请 </span>
            <b class="<?php echo $output['refund']['add_time'] > 0 ? 'radius_dot':'';?>"></b>
        </li>
        <li class="active">
            <span>平台审核<br>退款完成 </span>
            <b class="<?php echo $output['refund']['admin_time'] > 0 ? 'radius_dot':'';?>"></b>
        </li>
    </ul>
</div>
<!-- 我的退款申请 -->
<div class="refund_wrap">
    <div class="refund_tp">我的退款申请</div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo $lang['refund_order_refundsn'].$lang['nc_colon'];?> <?php echo $output['refund']['refund_sn']; ?></p>
        <p class="refund_text_row">退兑换码：
		<?php if (is_array($output['code_array']) && !empty($output['code_array'])) { ?>
        <?php foreach ($output['code_array'] as $key => $val) { ?>
        <?php echo $val;?><br />
		<?php } ?>
		<?php } ?>
		</p>
        <p class="refund_text_row">退款金额： <?php echo $lang['currency'];?><?php echo $output['refund']['refund_amount']; ?></p>
        <p class="refund_text_row">退款说明： <?php echo $output['refund']['buyer_message']; ?></p>
    </div>
</div>
<!-- 商城退款审核 -->
<div class="refund_wrap">
    <div class="refund_tp">商城退款审核</div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo '审核状态'.$lang['nc_colon'];?> <?php echo $output['admin_array'][$output['refund']['admin_state']]; ?></p>
        <?php if ($output['refund']['admin_time'] > 0) { ?>
	   <p class="refund_text_row"><?php echo '平台备注'.$lang['nc_colon'];?><?php echo $output['refund']['admin_message']; ?></p>
		<?php } ?>
    </div>
</div>

<div class="service_subBtn">
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1);" value="返回列表">
</div>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<style>.nav_touch{display:none;}</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">提现详情页</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="counseling respond_list">

    <!-- <div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_cashsn'].$lang['nc_colon']; ?><?php echo $output['info']['pdc_sn']; ?></span>
    </div> -->

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_cash_price'].$lang['nc_colon']; ?><?php echo $output['info']['amount']; ?> <?php echo $lang['currency_zh']; ?></span>
    </div>

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_cash_shoukuanbank'].$lang['nc_colon']; ?><?php echo $output['info']['bank_name']; ?></span>
    </div>

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_cash_shoukuanaccount'].$lang['nc_colon'];?><?php echo $output['info']['bank_no']; ?></span>
    </div>

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_cash_shoukuanname'].$lang['nc_colon'];?><?php echo $output['info']['bank_user']; ?></span>
    </div>

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_addtime'].$lang['nc_colon'];?><?php echo @date('Y-m-d',$output['info']['addtime']); ?></span>
    </div>

	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_paystate'].$lang['nc_colon'];?><?php echo str_replace(array('0','1','2'),array('审核中','已审核','已支付'),$output['info']['status']);?></span>
    </div>
	<?php if (intval($output['info']['apply_time'])) {?>
	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_checktime'].$lang['nc_colon'];?><?php echo @date('Y-m-d H:i:s',$output['info']['apply_time']); ?></span>
    </div>
	<?php } ?>
	<?php if (intval($output['info']['pdc_payment_time'])) {?>
	<div class="counseling_dec">
        <span class="counseling_respond"><?php echo $lang['predeposit_paytime'].$lang['nc_colon'];?><?php echo @date('Y-m-d H:i:s',$output['info']['pdc_payment_time']); ?></span>
    </div>
    <?php } ?>
</div>


<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

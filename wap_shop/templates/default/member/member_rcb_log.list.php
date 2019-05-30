<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>充值卡</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/lzf.css" />
</head>
<style>
.l_balanceT1 li{
					float:left;
					margin-left:8px;
				}
</style>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">充值卡</h1>
    <em class="recharge_em1">
    	<a class="color_blue" href="index.php?act=predeposit&op=rechargecard_add">充值</a>
    </em>
</header>

<section class="ui-container">
	<div class="l_balanceT1">
		充值卡可用余额：<span class="color_red"><?php echo $output['member_info']['available_rc_balance']; ?></span> <?php echo $lang['currency_zh']; ?>  
		冻结金额：<em class="color_blue"><?php echo $output['member_info']['freeze_rc_balance']; ?></em><?php echo $lang['currency_zh']; ?>
	</div>
	
	<div class="l_balanceT2_list">
		<dl class="l_balanceT2_list_dl">
		   <?php if (count($output['list']) > 0) { ?>
           <?php foreach ($output['list'] as $v) { ?>
			<dd>
				<div class="divL fl" style="width:100%">
				<?php $availableFloat = (float) $v['available_amount']; if ($availableFloat > 0) { ?>
					<div >
						<li style="float:left">收入：</li><li class="color_green" style="float:right"><?php echo $v['available_amount']; ?>(<?php echo $lang['currency_zh'];?>)</li>
					</div>
					<div style="clear:none" >&nbsp </div>
				<?php } elseif ($availableFloat < 0) { ?>
				    <div>
						<li style="float:left">支出</li><li class="color_red" style="float:right"><?php echo $v['available_amount']; ?>(<?php echo $lang['currency_zh'];?>)</li>
					</div>
					<div style="clear:none" >&nbsp </div>
				<?php } else { ?>

                <?php } ?>				
					<div>
						<li style="float:left">冻结: </li><li class="color_blue" style="float:right"><?php echo floatval($v['freeze_amount']) ? (floatval($v['freeze_amount']) > 0 ? '+' : null ).$v['freeze_amount'] : null;?>(<?php echo $lang['currency_zh'];?>)</li>
					</div>
					<div style="clear:none" >&nbsp </div>
					<div>
					    <li style="float:left">状态：<?php echo $v['description'];?></li>
					</div>
					<div style="clear:none" >&nbsp </div>
					<div>
						<?php echo @date('Y-m-d H:i:s',$v['add_time']);?>
					</div>
				</div>
			</dd>
			<?php } ?>
           <?php } ?>
		</dl>
	</div>
	<div class="l_balanceT1">
	 <?php echo $output['show_page']; ?>
	</div>
</section>

</body>
</html>
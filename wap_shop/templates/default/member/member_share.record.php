<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>我的收益</title>
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
    <h1 class="qz-color">佣金</h1>
    <!-- <em class="recharge_em1">
    	<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
    </em> -->
</header>

<section class="ui-container">
	<div class="ui-tab">
		<!--
			账户余额 strt
		-->
		<ul class="ui-tab-content" style="width:100%">
			<li>

				<div class="l_balanceT1">
               当前佣金：
					<span class="color_red"><?php echo $output['share_member']['credits']; ?><?php echo $lang['currency_zh'];?></span>

				</div>

				<dl class="l_balanceT2_list_dl">
				    <?php  if (count($output['list'])>0) { ?>
                    <?php foreach($output['list'] as $v) { ?>
					<dd>
						<div class="divL fl" style="width:100%;">
							<p class="p1">
								金额：<span class="color_green"><?php echo $v['amount']; ?></span>
							</p>
                     <p class="p1">
								订单：<span class="color_red"><?php echo $v['order_sn']; ?></span>
							</p>
							<p class="p1">
								下级：<span class="color_red"><?php echo $v['from_nickname']; ?></span>
							</p>
							<p class="p2">
								<?php echo @date('Y-m-d H:i:s',$v['addtime']);?>
							</p>
						</div>
					</dd>
				<?php } ?>
				<?php } ?>
				</dl>

				<div class="l_balanceT1">
				<?php echo $output['show_page']; ?>
				</div>
			</li>

		<!--
			账户余额 end
		-->
		</ul>
	</div>

</section>

<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/ui.js"></script>
<script type="text/javascript">
</script>
</body>
</html>

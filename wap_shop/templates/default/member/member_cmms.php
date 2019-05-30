<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>佣金提现</title>
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
    <i class="ui-icon-return" onclick="location.href='index.php?act=member_share&op=index'" ></i>
    <h1 class="qz-color">佣金提现</h1>
    <em class="recharge_em1">
    	<a class="color_blue" href="/wap_shop/index.php?act=member&amp;op=cmmsapply">提现</a>
    </em>
</header>

<section class="ui-container">
	<div class="l_balanceT1">
      剩余佣金：<span class="color_red"><?php echo $output['member_info']['credits']; ?></span><?php echo $lang['currency_zh'];?>
      已结佣金：<em class="color_blue"><?php echo $output['member_info']['done_credits']; ?></em> <?php echo $lang['currency_zh'];?>
	</div>

	<div class="l_balanceT2_list">
		<dl class="l_balanceT2_list_dl">
		  <?php  if (count($output['list'])>0) : ?>
          <?php foreach($output['list'] as $val) : ?>
             <dd>
   				<div class="divL fl">
   					<p class="p1">
   						状态：<?php echo str_replace(array('0','1'),array('未支付','已支付'),$val['status']);?>
   					</p>
   					<p class="p2">
   						<?php echo @date('Y-m-d H:i:s',$val['addtime']);?>
   					</p>
   					<a class="l_blue_bt l_f16" href="index.php?act=member&amp;op=cmmsinfo&amp;id=<?php echo $val['id']; ?>">查看</a>
   				</div>

   				<div class="divR fr">
   					<span class="l_f22 color_red"><?php echo $val['amount'];?></span>
   					<em class="l_f14 color_4f5f6f_8">提现金额(元)</em>
   				</div>
   			</dd>
      <?php endforeach; ?>
   <?php endif; ?>
		</dl>
	</div>
	<div class="l_balanceT1">
	<?php echo $output['show_page']; ?>
	</div>
</section>

</body>
</html>

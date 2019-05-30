<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>账户余额</title>
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
    <h1 class="qz-color">账户余额</h1>
    <em class="recharge_em1">
    	<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
    </em>
</header>

<section class="ui-container">
	<div class="ui-tab">
		<!--
			账户余额 strt
		-->
		<ul class="ui-tab-content" style="width:100%">
			<li>
			
				<div class="l_balanceT1">
				
					<?php echo $lang['predeposit_pricetype_available'].$lang['nc_colon']; ?>
					<span class="color_red"><?php echo $output['member_info']['available_predeposit']; ?><?php echo $lang['currency_zh'];?></span> 
					<?php echo $lang['predeposit_pricetype_freeze'].$lang['nc_colon']; ?>
					<em class="color_blue"><?php echo $output['member_info']['freeze_predeposit']; ?></em> <?php echo $lang['currency_zh'];?>
				
				</div>

				<dl class="l_balanceT2_list_dl">
				    <?php  if (count($output['list'])>0) { ?>
                    <?php foreach($output['list'] as $v) { ?>	
					<dd>
						<div class="divL fl" style="width:100%;">
							
							<p class="p1">
								收入：<span class="color_green"><?php echo $v['lg_av_amount']; ?></span>
							</p>
							
							<p class="p1">
								支出：<span class="color_red"><?php echo $v['lg_av_amount']; ?></span>
							</p>
							
							
							<p class="p1">
								冻结：<span class="color_blue"><?php echo floatval($v['lg_freeze_amount']) ? (floatval($v['lg_freeze_amount']) > 0 ? '+' : null ).$v['lg_freeze_amount'] : null;?></span>
							</p>
							<p class="p1">
							    状态：<?php echo $v['lg_desc'];?>
							</p>
							<p class="p2">
								<?php echo @date('Y-m-d H:i:s',$v['lg_add_time']);?>
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
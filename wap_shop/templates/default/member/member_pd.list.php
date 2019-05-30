<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<title>充值明细</title>
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/lzf.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/az.js"></script>
</head>
<style>
.l_balanceT1 li{
					float:left;
					margin-left:8px;
				}
</style>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go('-1');" ></i>
    <h1 class="qz-color">充值明细</h1>
    <em class="recharge_em1">
    	<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
    </em>
</header>

<section class="ui-container" style="margin-top:10px; padding:0px;">
	<div class="ui-tab">
	    <ul class="ui-tab-content" style="width:100%">
			<li>
			   <div class="l_balanceT1">
				
					<?php echo $lang['predeposit_pricetype_available'].$lang['nc_colon']; ?>
					<span class="color_red"><?php echo $output['member_info']['available_predeposit']; ?><?php echo $lang['currency_zh'];?></span> 
					<?php echo $lang['predeposit_pricetype_freeze'].$lang['nc_colon']; ?>
					<em class="color_blue"><?php echo $output['member_info']['freeze_predeposit']; ?></em> <?php echo $lang['currency_zh'];?>
				
				</div>
				
				<dl class="l_balanceT2_list_dl" id="az_1">
				<?php if (count($output['list'])>0) { ?>
                <?php foreach($output['list'] as $val) { ?>
					<dd>
						<div class="divL fl" style="width: 70%">
							<p class="p1">
								<?php echo $lang['predeposit_paystate']; ?>：<?php echo intval($val['pdr_payment_state']) ? '已支付' : '未支付';?>
							</p>
							<?php if(intval($val['pdr_payment_state'])){ ?>
							<p class="p1">
								<?php echo $lang['predeposit_payment']; ?>:<?php echo $val['pdr_payment_name'];?>
							</p>
							<?php } ?>
							<p class="p1">
								<?php echo $lang['predeposit_rechargesn']; ?>:<?php echo $val['pdr_sn'];?>
							</p>
							<p class="p2 color_4f5f6f_8">
								<?php echo date('Y-m-d H:i:s',$val['pdr_add_time']);?>
							</p>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="l_blue_bt l_f16" href="index.php?act=buy&op=pd_pay&pay_sn=<?php echo $val['pdr_sn'];?>" style="text-align: left;"><i></i>去支付</a>
						   <?php }else{ ?>
						   <a class="l_blue_bt l_f16" href="index.php?act=predeposit&op=recharge_show&id=<?php echo $val['pdr_id']; ?>" style="text-align: left;"><i></i><?php echo $lang['nc_view'];?></a>				
						   <?php } ?>
     					</div>
						
						<div class="divR fr">
							<span class="l_f22 color_red">+<?php echo $val['pdr_amount'];?></span>
							<em class="l_f14 color_4f5f6f_8"><?php echo $lang['predeposit_recharge_price']; ?>(<?php echo $lang['currency_zh'];?>)</em>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="ui-icon-delete l_deleteIco" href="javascript:if(confirm('你确定要删除吗？'))location='index.php?act=predeposit&op=recharge_del&id=<?php echo $val['pdr_id']; ?>'" title="删除"></a>			
							<?php } ?>
						</div>
					</dd>
				<?php } ?>
                <?php } else {?>
				 您，暂无充值记录！立即<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
				<?php } ?>
				</dl>
				
				<dl class="l_balanceT2_list_dl" id="az_2" style="display: none;">
				<?php if (count($output['list'])>0) { ?>
                <?php foreach($output['list'] as $val) { ?>
					<dd>
						<div class="divL fl">
							<p class="p1">
								<?php echo $lang['predeposit_paystate']; ?>：<?php echo intval($val['pdr_payment_state']) ? '已支付' : '未支付';?>
							</p>
							<?php if(intval($val['pdr_payment_state'])){ ?>
							<p class="p1">
								<?php echo $lang['predeposit_payment']; ?>:<?php echo $val['pdr_payment_name'];?>
							</p>
							<?php } ?>
							<p class="p1">
								<?php echo $lang['predeposit_rechargesn']; ?>:
							</p>
							<p class="p1">
								<?php echo $val['pdr_sn'];?>
							</p>
							<p class="p2 color_4f5f6f_8">
								<?php echo date('Y-m-d H:i:s',$val['pdr_add_time']);?>
							</p>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="l_blue_bt l_f16" href="index.php?act=buy&op=pd_pay&pay_sn=<?php echo $val['pdr_sn'];?>" style="text-align: left;"><i></i>去支付</a>
						   <?php }else{ ?>
						   <a class="l_blue_bt l_f16" href="index.php?act=predeposit&op=recharge_show&id=<?php echo $val['pdr_id']; ?>" style="text-align: left;"><i></i><?php echo $lang['nc_view'];?></a>				
						   <?php } ?>
     					</div>
     					
						
						<div class="divR fr">
							<span class="l_f22 color_red">+<?php echo $val['pdr_amount'];?></span>
							<em class="l_f14 color_4f5f6f_8"><?php echo $lang['predeposit_recharge_price']; ?>(<?php echo $lang['currency_zh'];?>)</em>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="ui-icon-delete l_deleteIco" href="javascript:if(confirm('你确定要删除吗？'))location='index.php?act=predeposit&op=recharge_del&id=<?php echo $val['pdr_id']; ?>'" title="删除"></a>			
							<?php } ?>
						</div>
					</dd>
				<?php } ?>
                <?php } else {?>
				 您，暂无充值记录！立即<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
				<?php } ?>
				</dl>
				
				<div class="l_balanceT1">
				<?php echo $output['show_page']; ?>
				</div>
			</li>
		<!--
			充值明细 end
		-->
		</ul>
	</div>
	
</section>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/ui.js"></script>  

</body>
</html>
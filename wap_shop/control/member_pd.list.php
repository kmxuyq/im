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
.text-s{
	min-width:220px;
	height:35px;
	line-height:35px;
	display:block;
	margin-top:5px;
	margin-left:20px;
	margin-right:5px;
	float:left;
	-moz-border-radius: 5px; 
    -webkit-border-radius: 5px; 
    border-radius:10px;
}
.sub-s{
	min-width:60px;
	height:35px;
	line-height:35px;
	background:#10B0E6;
	display:block;
	margin-top:5px;
	float:left;
	border:#FF8444 1px solid;
	-moz-border-radius: 5px; 
    -webkit-border-radius: 5px; 
    border-radius:10px;
}
.l_balanceT1 li{
					float:left;
					margin-left:8px;
				}
</style>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" style="color:#10b0e6;"></i>
    <h1 class="qz-color">充值明细</h1>
    <em class="recharge_em">
    	<a class="color_blue" href="index.php?act=predeposit&op=recharge_add">充值</a>
    </em>
</header>

<section class="ui-container" style="margin-top:10px; padding:0px;">
	<div class="ui-tab">
	   <!-- <ul class="ui-tab-nav ui-border-b">
		<form action="index.php" method="get">
		<input type="hidden" name="act" value="predeposit" />
        <input type="hidden" name="op" value="index" />
            <input type="text" width="60%" placeholder="请输入你要查询的订单号" class="text-s" name="pdr_sn" value="<?php echo $_GET['"pdr_sn"'];?>"/>
            <input type="submit" class="sub-s" value="<?php echo $lang['nc_search'];?>" />
		</form>
        </ul>-->
	    <ul class="ui-tab-content" style="width:100%">
		<!--
		                    	color_red 为支出
		                    	color_blue 为冻结
		                    	color_green 为收入
		                    	只需更换class  即可
		                    -->
		<!--
			充值明细 start
		-->
			<li>
				<dl class="l_balanceT2_list_dl">
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
								<?php echo $lang['predeposit_rechargesn']; ?>:<?php echo $val['pdr_sn'];?>
							</p>
							<p class="p2 color_4f5f6f_8">
								<?php echo date('Y-m-d H:i:s',$val['pdr_add_time']);?>
							</p>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="l_blue_bt l_f16" href="index.php?act=buy&op=pd_pay&pay_sn=<?php echo $val['pdr_sn'];?>" style="text-align: left;"><i></i>安全支付</a>
						   <?php }else{ ?>
						   <a class="l_blue_bt l_f16" href="index.php?act=predeposit&op=recharge_show&id=<?php echo $val['pdr_id']; ?>" style="text-align: left;"><i></i><?php echo $lang['nc_view'];?></a>				
						   <?php } ?>
							
     					</div>
						
						<div class="divR fr">
							<span class="l_f22 color_red">+<?php echo $val['pdr_amount'];?></span>
							<em class="l_f14 color_4f5f6f_8"><?php echo $lang['predeposit_recharge_price']; ?>(<?php echo $lang['currency_zh'];?>)</em>
							<?php if (!intval($val['pdr_payment_state'])){?>
							<a class="ui-icon-delete l_deleteIco" href="index.php?act=predeposit&op=recharge_del&id=<?php echo $val['pdr_id']; ?>"></a>			
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
<script type="text/javascript">

</script>
</body>
</html>
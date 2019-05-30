<?php defined('InShopNC') or exit('Access Invalid!');?>
<title><?php echo $lang['czxx']?></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/lzf.css" />

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.go(-1)" ></i>
    <h1 class="qz-color"><?php echo $lang['czxx']?>
	<em class="recharge_em1">
    	<a class="color_blue" href="index.php?act=predeposit&op=recharge_add"><?php echo $lang['cz']?></a>
    </em></h1>
</header>


    <?php include template('layout/submenu');?>
  
    <p>
      <?php echo $lang['predeposit_rechargesn'].$lang['nc_colon'];?>
      <?php echo $output['info']['pdr_sn']; ?>
    
    <p>
      <?php echo $lang['predeposit_payment'].$lang['nc_colon'];?>
      <?php echo $output['info']['pdr_payment_name']; ?>
    
    <p>
      <?php echo $lang['predeposit_recharge_price'].$lang['nc_colon'];?>
      <?php echo $output['info']['pdr_amount']; ?> <?php echo $lang['currency_zh']; ?>
    
    <p>
      <?php echo $lang['predeposit_addtime'].$lang['nc_colon'];?>
      <?php echo @date('Y-m-d H:i:s',$output['info']['pdr_add_time']); ?>
    
    <p>
      <?php echo $lang['predeposit_paytime'].$lang['nc_colon'];?>
      <?php echo intval(date('His',$output['info']['pdr_payment_time'])) ? date('Y-m-d H:i:s',$output['info']['pdr_payment_time']) : date('Y-m-d',$output['info']['pdr_payment_time']); ?>
    
    <p>
      <?php echo $output['info']['pdr_payment_name'].$lang['predeposit_trade_no'].$lang['nc_colon'];?>
      <?php echo $output['info']['pdr_trade_sn']; ?>
    
    <p>
      <?php echo $lang['predeposit_paystate'].$lang['nc_colon'];?>
      <?php echo !intval($output['info']['pdr_payment_state']) ? L('predeposit_rechargewaitpaying'): L('predeposit_rechargepaysuccess'); ?>
      
        <input type="submit" style="display:none;"  value="<?php echo $lang['predeposit_backlist'];?>" onclick="window.location='<?php echo $_SERVER['HTTP_REFERER'];?>'"/>
      

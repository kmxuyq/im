<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $lang['salel_num'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL ;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL ;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>

<script type="text/javascript">
$(document).ready(function(){
		$('#salelog_demo').find('.demo').ajaxContent({
			event:'click', //mouseover
			loaderType:"img",
			loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
			target:'#salelog_demo'
		});

});
</script>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="custom-color" style='color:#00A5E0'><?php echo $lang['salel_num'];?></h1>
</header>

<section class="ui-container">
    <div class="qz-padding">
        <div class="qz-block">
        <table class="ui-table">
            <thead>
                <tr>
                    <th><?php echo $lang['goods_index_buyer'];?></th>
					<!--<th><?php echo $lang['goods_index_buy_price'];?></th>-->
				  <th><?php echo $lang['goods_index_buy_amount'];?></th>
				  <th><?php echo $lang['goods_index_buy_time'];?></th>
                </tr>
            </thead>
    
		<?php if(!empty($output['sales']) && is_array($output['sales'])){?>
            <tbody>
			<?php foreach($output['sales'] as $key=>$sale){?>
                <tr>
                    <td><a href="index.php?act=member_snshome&mid=<?php echo $sale['buyer_id'];?>" target="_blank" data-param="{'id':<?php echo $sale['buyer_id'];?>}" nctype="mcard"  style="color:#4F5F6F"><?php echo $sale['buyer_name'];?></a></td>
                    <!--<td><?php echo $lang['currency'].$sale['goods_price'];?><?php echo $output['order_type'][$sale['goods_type']];?></td>-->
					<td><?php echo $sale['goods_num'];?></td>
                    <td><?php echo date('Y-m-d H:i:s', $sale['add_time']);?></td>
                </tr>
				<?php }?>
            </tbody>
			<?php }else{?>
		  <tbody>
			<tr>
			  <td colspan="10" class="ncs-norecord"><?php echo $lang['no_record'];?></td>
			</tr>
		  </tbody>
		  <?php }?>
        </table>
        </div>
    </div>
	<div id="menu"></div>
</section>



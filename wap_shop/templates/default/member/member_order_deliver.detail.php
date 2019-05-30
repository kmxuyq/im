<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $lang['member_show_express_detail'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<style type="text/css">
    .ui-list>li{
        display: flex;
    }
    .qz-light3{
    -webkit-box-flex: 1;
    -webkit-flex: 1;
    -ms-flex: 1;
    flex: 1;
    }
</style>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member_order&state_type=state_send'" ></i>
    <h1 class="qz-color"><?php echo $lang['member_show_express_detail'];?></h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-padding qz-background-yellow qz-color7">
        <p><a href="<?php echo $output['e_url'];?>"  target="_blank"><?php echo $output['e_name'];?></a><br>
           <?php echo $lang['member_show_express_ship_code'].$lang['nc_colon'];?>
           <?php echo $output['order_info']['shipping_code']; ?>
        </p>
    </div>

    <div class="qz-dd-list">
        <dl>
            <div class="qz-padding qz-background-white">
                <?php echo $lang['member_show_express_ship_tips'];?>
            </div>

            <ul class="ui-list" style="border-bottom:none;">
			 <?php if(is_array($output['order_info']['extend_order_goods']) and !empty($output['order_info']['extend_order_goods'])) { ?>
			 <?php  foreach($output['order_info']['extend_order_goods'] as $val) { ?>
                <li class="ui-border-b">
                    <div class="ui-list-thumb qz-list-thumb" style="width:70px;">
					<a href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>" target="_blank">
                        <img src="<?php echo thumb($val,'60'); ?>" class="qz-img-block">
					</a>
                    </div>
                    <div class="qz-light3">
                        <h4 class="ui-nowrap">
						<a href="index.php?act=goods&goods_id=<?php echo $val['goods_id']; ?>" target="_blank">
						<?php echo $val['goods_name']; ?>
						<?php if ($val['goods_type'] != 1) {?>
						(<?php echo orderGoodsType($val['goods_type']);?>)
						<?php } ?>
						</a>
						</h4>
                        <p class="ui-nowrap">￥<font class="qz-color2"><?php echo $val['goods_price']; ?></font></p>
                        <p>商品数目：<?php echo $val['goods_num']; ?></p>
                    </div>
                </li>
			 <?php } ?>
             <?php } ?>
            </ul>

        </dl>
    </div>

    <div class="qz-bk10"></div>
    <div class="qz-padding qz-background-white">
        <h2><?php echo $lang['member_show_express_ship_dstatus'];?></h2>

        <div class="qz-bk20"></div>
        <?php if($output['order_info']['extend_order_common']['shipping_time']) { ?>
        <div class="qz-fl-list" >
		    <dl class="clearfix" id="express_list"><div class="lt"><span></span></div><div class="rt qz-color6">
	             <p><?php echo $lang['member_show_seller_has_send'];?></p>
				 <p>
				 <?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?>
				 </p>
				 </div>
		    </dl>
        </div>
		<?php } ?>
    </div>

</section>
<script type="text/javascript">
   $(function(){
	   //var url='index.php?act=member_order&op=get_express&e_code=<?php echo $output['e_code']?>&shipping_code=<?php echo $output['order_info']['shipping_code']?>&t=<?php echo random(7);?>';
	   var url='<?php echo $output["kaid100_url"]?>';
	   $.getJSON(url,function(data){
		if(data){
         if(typeof data['result']!= 'undefined' && data['result']==false){
            return;
         }
			$.each(data,function(index,item){
				$('#express_list').after('<dl class="clearfix"><div class="lt"><span></span></div><div class="rt">'
                    +'<p>'+item.context+'</p><p>'+item.time+'</p></div></dl>')
			})

		}else{
			$('#express_list').html(var_send);
		}
	});
});
</script>
</body>
</html>

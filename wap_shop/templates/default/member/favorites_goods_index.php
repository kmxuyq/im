<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>收藏商品</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
	<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
	<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
	<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/layer.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/layer.js"></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="window.location.href='/wap_shop/index.php?act=member&op=home'"></i>
    <h1 class="qz-color">收藏商品</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="store">
<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
   
   <?php foreach($output['favorites_list'] as $key=>$favorites){?>
   
    <div class="store_box">
	    <!-- delete by tanzn start
        <div class="store_header">
		<a href="index.php?act=show_store&op=index&store_id=<?php echo $favorites['goods']['store_id'];?>" >
		<?php echo $favorites['goods']['store_name'];?>
		</a>
		</div>
		delete by tanzn stop -->
        <div class="store_tt"> <?php echo $lang['favorite_date'];?>:<?php echo date("Y-m-d",$favorites['fav_time']);?> </div>
        <div class="store_text">
            <p class="imgBox">
			<a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank">
			<img src="<?php echo thumb($favorites['goods'], 240);?>" alt=""/>
			</a>
			</p>
            <div class="text">
                <span class="text_dec">
				<a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank">
				<?php echo $favorites['goods']['goods_name'];?>
				</a>
				</span>

				<span class="text_money">￥<span class="tip_red"><?php echo $favorites['goods']['goods_price'];?></span></span>
                <a href="index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>" >
				<input class="btn1" type="button"/>
				</a>
            </div>
        </div>
    </div>
	
   <?php } ?>
   
<?php }else{ ?>
    <div class="no_center">
	 <?php echo $lang['no_record'];?>
	</div>
<?php } ?> 
</div>

<!-- delete by tanzn start
<div class="store">
   
    <div class="store_box">
        <div class="store_header" id="show_page">
          <?php echo $output['show_page']; ?>
		</div>
	</div>
</div>
delete by tanzn start -->

<style>
body{ background:#f5f5f5;}
#show_page li{
	float:left;
	margin:auto 5px;
}
</style>
</body>
</html>













<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>收藏店铺</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">收藏店铺</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
<div class="store">
    <?php foreach($output['favorites_list'] as $key=>$favorites){?>
    <div class="store_box">
        <div class="store_tt"><?php echo $lang['favorite_date'];?>:<?php echo date("Y-m-d",$favorites['fav_time']);?>  </div>
        <div class="store_text">
            <p class="imgBox">
			<a href="index.php?act=show_store&op=index&store_id=<?php echo  $favorites['store']['store_id'];?>" >
			<img src="<?php echo getStoreLogo($favorites['store']['store_avatar']);?>" alt=""/>
			</p>
			</p>
            <div class="text">
                <span class="text_dec">
				<a href="index.php?act=show_store&op=index&store_id=<?php echo  $favorites['store']['store_id'];?>" >
				<?php echo $favorites['store']['store_name'];?>
				</a>
				</span>
				
				<a href="index.php?act=member_favorites&op=delfavorites&type=store&fav_id=<?php echo $favorites['fav_id'];?>">
                <input class="btn1" type="button"/>
				</a>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<?php }else{ ?>
<div class="store">
    <div class="no_center">
        <?php echo $lang['no_record'];?>
    </div>   
</div>
<?php  } ?>
<?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
<div class="store">
    <div class="no_center" id="show_page">
        <?php echo $output['show_page']; ?>
    </div>   
</div>
<?php } ?>
<style>
#show_page li{
	float:left;
	margin:5px;
}
body{ background:#f5f5f5;}
</style>
</body>
</html>

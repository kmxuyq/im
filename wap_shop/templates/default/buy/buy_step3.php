

<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>订单完成</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">订单完成</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-padding">订单已支付完成，祝您购物愉快<a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=member_order&op=show_order&order_id=<?php echo $output['order_id'];?>">查看订单详情</a></div>
    <div class="qz-padding qz-background-white qz-top-b qz-bottom-b">
        
        
        订单支付成功！您已成功支付订单金额￥<em style="color:#DA3228;font-size:18px;"><?php echo $output["pay_amount"]?></em>，订单编号：<em style="color:#DA3228;font-size:18px;"><?php echo $output['pay_sn'];?></em>。
        <div class="qz-bk10"></div>
		<div class="qz-bk10"></div>

		<div class="qz-padding qz-background-white clearfix">
        <div class="qz-ft-l qz-fl">
            <dl class="qz-fl">
                <a href="index.php?act=show_store&op=<?php if($output['share_shop']){echo 'share';}else{ echo 'index';}?>&store_id=<?php echo $output['route_store_id'];?>" class="ui-btn-lg ui-btn-primary qz-btn-lg"><i class="icon-shopping-cart"></i>继续购物</a>
            </dl>
            
            <dl class="qz-fr"><a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=member_order" class="ui-btn-lg ui-btn-primary qz-btn-lg qz-background-yellow"><i class="icon-file-text-alt"></i>查看订单</a>
            </dl>
        </div>
        <?php if($output['store_wx_info']['qr']): ?>
		<div style="text-align: center; color: #f00;">
            <img src="/wap_shop/templates/default/images/haowu.jpg" style="max-width: 100%;" />
            <p>太棒啦，支付成功了哦。</p>
            <p>快快关注HAO物盒子公众号，<br/>一键查看订单吧！</p>
        </div>
    <?php endif;?>
    </div>
    
    <div id="menu"></div>
</section>
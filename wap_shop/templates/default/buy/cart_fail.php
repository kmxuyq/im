<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>购物车里失效产品</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery.event.drag-1.5.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery.touchSlider.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/iscroll.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/wechat.js"></script>
<style type="text/css">
.bottom_btnt{
    position: fixed;
    left: 0px;
    bottom: 0px;
}
</style>
</head>

<body class="bg_gray">
<!--失效产品-->
<div class="search_results failure_product">
    <div class="search_results_list">
        <?php if(!empty($output['store_cart_list'])) { ?>
        <ul id="store_cart">
            <?php foreach($output['store_cart_list'] as $cart_list) {?>
                <?php foreach($cart_list as $cart_info) {?>
                    <?php if(!$cart_info['state']) {?>
                        <li data-cart-id="<?php echo $cart_info['cart_id']; ?>">
                            <a href="javascript:;">
                                <div class="goods_pic"><div class="srl_width"><img src="<?php echo thumb($cart_info,60);?>" /></div></div>
                                <div class="goods_num">x<?php echo $cart_info['goods_num']; ?></div>
                                <div class="goods_dis">
                                    <div class="title"><?php echo $cart_info['goods_name']; ?></div>
                                    <div class="goods_price">
                                        <div class="present_price"><span>&yen;</span><?php echo $cart_info['goods_price']; ?></div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <div class="clear"></div>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </ul>
        <?php } else { ?>
            <p>没有失效的商品</p>
        <?php } ?>
    </div>
</div>
<?php if(!empty($output['store_cart_list'])) { ?>
<div class="bottom_btnt"><a href="javascript:;" id="clearcart">清 空</a></div>
<?php } ?>
<script type="text/javascript">
    //删除购物车信息
function ClearNotOnSaleCartGoods(){
    var CART_IDS = new Array();
    var stores = $('#store_cart').find('li');
    stores.each(function(i, n){
        var ck = $(this).data('cart-id')
        CART_IDS.push(ck);
    })
    var ids = CART_IDS.join(',');
    if(ids) {
        $.getJSON('index.php?act=cart&op=clearfail&cart_ids='+ids, function(result){
            if(result.state){      
               window.location.reload();
            }else{
                alert(result.msg);
            }
        });
    }
}
$(function(){
    $("#clearcart").click(function () {
        ClearNotOnSaleCartGoods();
    })
})
</script>
</body>
</html>

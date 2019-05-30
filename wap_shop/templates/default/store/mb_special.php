<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<title>专题</title>
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/main.css">
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/index.css">
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.js"></script>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/mb_special.css">
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css">
</head>
<body>
<?php  if($_GET['type'] !="iframe"){ ?>
<header class="product_detail_hd">
    <div class="arrow" onclick="location.href=history.back()"></div>
    <h1>活动专题</h1>
</header>
<?php } ?>

<div class="main" id="main-container">
<?php
foreach ((array) $output['list'] as $v) {
    foreach ($v as $kk => $vv) {
        require 'mb_special_item.module_' . $kk . '.php';
        break;
    }
}
?>
</div>
<script type="text/javascript" src="/wap_shop/templates/default/js/zepto.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/wap_shop/templates/default/js/swiper/swiper-3.4.1.jquery.min.js" charset="utf-8"></script>
<link href="/wap_shop/templates/default/js/swiper/swiper-3.4.1.min.css" rel="stylesheet"/>
<link href="<?php echo RESOURCE_SITE_URL."/js/layer_mobile/need/layer.css"?>" rel="stylesheet"/>
<script src="<?php echo RESOURCE_SITE_URL."/js/layer_mobile/layer.js"?>" type="text/javascript"></script>
<script type="text/javascript">
Zepto(function($) {
   new Swiper('.swiper-container', {
      autoplay: 3000,
   });
   var loc = '<?php echo $_GET['type'];?>'=='iframe'?parent.window.location:window.location;
    $('[nctype="btn_item"]').on('click', function() {
        var type = $(this).attr('data-type');
        var data = $(this).attr('data-data');
        var url = '';
        switch(type){
           case 'url':
           url = data;
           break;
           case 'keyword':
           url = 'index.php?act=search&op=share_product_list&keyword='+data;
           break;
           case 'goods':
           url = 'index.php?act=goods&goods_id=' + data;
           break;
           case 'special':
           url = 'index.php?act=mb_special&special_id='+data;
           break;
           case 'coupon':
           $.post('index.php?act=womens_voucher&op=test&tid='+data, function(res){
             layer.open({
                content: res.msg
                ,skin: 'foot'
                ,time: 2 //2秒后自动关闭
                ,type: 0
                ,title: '提示信息'
                ,btn: ['确定']
                ,end:function(){
                   if(res.state=='login'){
                      location.href='index.php?act=login';
                   }
                },

              });
          },'json');
           break;
        }
        if(url!=''){
           loc.href = url;
        }
    });

});
</script>
<style>
    .index_block{overflow: hidden;}

    .content{overflow: hidden}
    body{
        background: rgb(242, 242, 242);
        <?php if($_GET['type']!='iframe'):?>
        padding-bottom: 50px;
        height: 480px;
        <?php endif;?>
        font-size: 12px;
    }
    .adv_list .list_img{width:10rem; height:4.26rem }
    .adv_list .list_img{width:10rem; height:4.26rem }
</style>
</body>
</html>

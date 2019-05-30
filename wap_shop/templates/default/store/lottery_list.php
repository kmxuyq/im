<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title>城市</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/reset.css">
    <!--<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.less">-->
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/TouchSlide.1.1.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/flexible.js"></script>
</head>
<body class="grayBg">
<div class="header_top">
    <div class="titleWrap">我的奖券</div>
</div>
<ul class="lottery_tab_tt">
    <li class="on"><span>全部</span></li>
    <li><span>有效</span></li>
    <li><span>过期</span></li>
</ul>
<div class="lottery_tab_list on">
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>
</div>
<div class="lottery_tab_list">
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test01.jpg" alt="">
    </div>

</div>
<div class="lottery_tab_list">
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>
    <div class="item">
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/testimage/lottery_test02.jpg" alt="">
    </div>

</div>
<div class="lottery_tips">
    <div>优惠券使用说明：</div>
    <p>说明内容说明内容说明内容说明内容说明内容说明内容说明内容说明内容。</p>
</div>
<ul class="footer_menu">
    <li class="rows on">
        <a href="index.php?act=show_store&amp;op=index&amp;store_id=7">
            <i class="icon1"></i>
            <span>首页</span>
        </a>
    </li>
    <li class="rows">
        <a class="" href="index.php?act=member_vr_order">
            <i class="icon2" style="font-size: 26px"></i>
            <span>订单中心</span>
        </a>
    </li>
    <li class="rows">
        <a class="" href="index.php?act=member&amp;op=home">
            <i class="icon3"></i>
            <span>个人中心</span>
        </a>
    </li>
</ul>
<script>
    $(function(){
        $('.lottery_tab_tt li').click(function(){
            var _thisIndex= $(this).index();

            $(this).addClass('on').siblings().removeClass('on');
            $('.lottery_tab_list').hide();
            $('.lottery_tab_list').eq(_thisIndex).show()
        })
    })
</script>
<!-- 引入 -->
<?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</body>
</html>

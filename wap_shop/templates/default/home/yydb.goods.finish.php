<!DOCTYPE html>
<html lang="en">
<head>
    <title>一元去旅行</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元去旅行，九休旅行"/>
    <meta name="author" content="九休旅行"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/reset.css">
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/main.css">
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/TouchSlide.1.1.js"></script>
</head>
<body>
<!-- 支付结果 -->
<div class="header">
    <div class="arrow" onclick="javascript:location.href = '<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=dbgoods&amp;gr_id=<?php echo $output['gr_id'];?>'"></div>
    <div class="tt">支付结果</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>
<div class="pay-result">
    <div class="result-row">恭喜您参与成功，完成所需参与次数后系统将尽快为您揭晓</div>
    <div class="result-row">您可以多购买次数加快进度又能增加中奖几率呦！</div>
    <div class="result-row"> 【再次购买】（建议添加同款商品下单链接）</div>
    <div class="result-btn">
        <a class="btn" href="/wap_shop/index.php?act=duobao">继续参与</a>
        <a class="btn" href="<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&amp;op=viewdbRec">查看参与记录</a>
    </div>
</div>
<!-- 二维码弹出  -->
<div class="follow-code">
    <h2>请扫描二维码，关注公众号</h2>
    <div class="red-tip" style="display:block">后续活动还有更多优惠、大量礼品</div>
    <div class="pay-result">
        <div class="reuslt-code">
            <img src="http://wx.yimayholiday.com/wap_shop/templates/default/style/images/yimayCode.jpg" alt="">
        </div>
        <div class="code-help-tip">
            <p>输入关键词<br/><span>“参与记录”</span>查询中奖记录</p>
        </div>
    </div>
</div>
<script>
$(function(){
    $('body').css({'background':'#fff'});
});

function hideShopping(){
    $('.buying-mark,.buying-information').fadeOut();
    $('.index-popwin').fadeOut();
    $('.follow-code').fadeOut('fast');
}
function showCode(){
    $('.buying-mark').fadeIn();
    $('.follow-code').show(300);
}

function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}
</script>
</body>
</html>

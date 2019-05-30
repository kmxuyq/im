<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>取消订单</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">取消订单</h1>
</header>
<form method="post" action="index.php?act=member_order&op=change_state&state_type=order_cancel&order_id=<?php echo $output['order_info']['order_id']; ?>" id="order_cancel_form" onsubmit="ajaxpost('order_cancel_form','','','onerror')">
<input type="hidden" name="form_submit" value="ok" />
<div class="order_number_wrap">
    <div class="order_number">订单号： 7000000000025701</div>
    <div class="order_number_tt">取消原因:</div>
    <ul class="order_number_choose">
        <li class="item">
            <input class="choose_radio" name="order_type" type="button"/>
            <span>改买其他商品</span>
        </li>
        <li class="item">
            <input class="choose_radio" name="order_type" type="button"/>
            <span>改用其他配送方式</span>
        </li>
        <li class="item">
            <input class="choose_radio" name="order_type" type="button"/>
            <span>从其他店铺购买</span>
        </li>
        <li class="item">
            <input class="choose_radio" name="order_type" type="button"/>
            <span>其他原因</span>
        </li>
		<li class="item">
            <textarea name="state_info1" style="width:80%;height:80px;"></textarea>
            <span>其他原因</span>
        </li>
    </ul>
</div>
<div class="service_subBtn">
    <input type="button" class="public_btn1" value="确认并提交">
    <input type="button" class="public_btn2" value="取消并返回">
</div>
</form>
<!---->
<script>
$(function(){
    $(".order_number_choose li").click(function(){
        if(!$(this).hasClass('on')){
            $(".order_number_choose li").removeClass('on');
            $(this).addClass('on');
            $(this).find('.choose_radio').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });
});
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













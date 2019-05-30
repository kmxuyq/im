<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>订单列表</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" style="color:#10b0e6;"></i>
	<h1>
	<?php echo $_GET['state_type']=='state_new'?'代付款':''; ?>
	<?php echo $_GET['state_type']=='state_pay'?'待发货':''; ?>
	<?php echo $_GET['state_type']=='state_send'?'待收货':''; ?>
	<?php echo $_GET['state_type']=='state_success'?'已完成':''; ?>
	<?php echo $_GET['state_type']=='state_noeval'?'待评价':''; ?>
	<?php echo $_GET['state_type']=='state_cancel'?'已取消':''; ?>
	订单
	</h1>
</header>

<section class="ui-container">
    <div class="qz-dd-list">       
        <dl class="qz-bottom-b">
            <div class="qz-padding qz-top-b qz-bottom-b">
                下单时间：2015-08-26 09：32
            </div>
            
            <div class="qz-padding qz-padding-b qz-background-white clearfix">
                <p>店铺名称：怡美假日</p>
                <span class="qz-fl qz-color8">订单编号：8304938967230</span>
                <span class="qz-fr"><a href="#">订单详情</a></span>
            </div>
            
            <ul class="ui-list">
                <li class="ui-border-t">
                    <div class="ui-list-thumb qz-list-thumb" style="width:70px;">
                        <img src="images/pj_pic.png" class="qz-img-block">
                    </div>
                    <div class="ui-list-info qz-light3">
                        <h4 class="ui-nowrap">昆明大理丽江 西双版纳8天7晚跟团… </h4>
                        <p class="ui-nowrap">￥<font class="qz-color2"><span class="qz-f18">1100</span>.00</font></p>
                        <p>商品数目：1</p>
                    </div>
                </li>
            </ul>
            
            <div class="qz-padding qz-padding-t qz-background-white">
                <div class="qz-top-b clearfix">
                    <div class="qz-bk10"></div>
                    <span class="qz-fl">运费：</span>
                    <span class="qz-fr">￥15.00</span>
                    <div class="qz-bk5"></div>
                    <span class="qz-fl">合计</span>
                    <span class="qz-fr">￥90.00</span>
                    <div class="qz-bk5"></div>
                    <p class="qz-fl">待付款</p>
                    <p class="qz-fr">
                        <input type="submit" value="取消订单" class="ui-btn qz-padding-10 qz-background-gray qz-color4" /><input type="submit" value="支付订单(￥90.00)" class="ui-btn qz-padding-10 qz-background-yellow qz-margin-l10 qz-color7" />
                        <input type="submit" value="查看物流" class="ui-btn qz-padding-10 ui-btn-primary" /><input type="submit" value="确认订单(￥90.00)" class="ui-btn qz-padding-10 qz-background-yellow qz-margin-l10 qz-color7" />
					</p>
                </div>
            </div>
            
        </dl>
        
    </div>
</section>


<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
    $(".qz-number .qz-ico-plus").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        num_total = parseInt($(this).parent().children(".num").attr("total"));
        if (num_val < num_total) {
            num_val = num_val + 1;
        } else {
            num_val = num_total;
            alert("已超过最大库存！");
        }
	   $(this).parent().children(".num").val(num_val);
    });

    $(".qz-number .qz-ico-reduction").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        if (num_val<=0) {
            num_val = 0;
        } else {
            num_val = num_val - 1;
        }
        $(this).parent().children(".num").val(num_val);
    });
    
    $(".qz-tcxz span").click(function(){
        $(this).parent().find("span").removeClass("ui-btn-primary");
        $(this).addClass("ui-btn-primary");
        loc_num = $(this).attr("value");
        $(this).parent().children(".type").val(loc_num);
    });
</script>
</body>
</html>
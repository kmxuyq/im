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
<!-- 头部 -->
<div class="header">
    <div class="arrow" onclick="javascript:history.back(-1);"></div>
    <div class="tt">确认订单</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>
<div class="db-tab">
    <div class="cl-main">
        <div class="tp">
            <div class="hd"><img src="<?php echo ($output['group']['goods_imageurl']);?>" alt=""></div>
            <div class="tp-rt">
                <div class="tt">
                    <?php echo ($output['group']['groupbuy_name'].'（'.$output['group']['goods_name'].'）');?>
                </div>
                <p><?php echo ($output['group']['remark']);?></p>
            </div>
        </div>
    </div>
</div>



<!--积分兑换-->
<div class="moblie-number pd">
    <div class="count-money2">
        <span class="t1">所需积分</span>
        <span class="t2 icon"><?php echo ($output['points']);?></span>
    </div>
    <div class="count-money2">
        <span class="t1">可用积分</span>
        <span class="t2 icon"><?php echo ($output['mem_points']);?></span>
    </div>
    <div class="count-money2">
        <span class="t1">需付金额</span>
        <span class="t2"> <i>￥</i> <?php echo (intval($output['buyCount'])*(intval($output['group']['vr_class_id'])==5?5:1));?></span>
    </div>
</div>

<div class="help-tip">点击支付订单表示已阅读并同意预定须知</div>
<form action="<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao" method="post" id="submitForm">
<input name="act" value="duobao" type="hidden" />
<input name="op" value="inOrders" type="hidden" />
<input name="gid" value="<?php echo $output['gr_id'];?>" type="hidden"/>
<input name="b_c" value="<?php echo $output['b_c'];?>" type="hidden" />
</form>
<input class="bt-btn" value="确认支付" type="button" onclick="pop2Win()">

<!--弹窗-->
<div class="pop-mark"></div>
<div class="pop-win-text">
   <div class="close-btn" onclick="closePop()"></div>
    <div class="recharge">
        <div class="recharge-tt">您的积分不足,请兑换</div>
        <div class="recharge-numb"><?php echo $output['p_dhl'];?>积分=1元</div>
        <input type="button" class="go-btn" onclick="addPop()" value="去兑换">
    </div>
</div>
<!-- 弹窗组件-->
<script>

//
function pop2Win(){
    var f = parseInt("<?php echo $output['points_flag'];?>");
	if(f > 0){
		$('#submitForm').submit();
	}
}
//
function popWin(){
    $('.pop-mark').addClass('in');
    $('.pop-win-text').css({marginTop:-$('.pop-win-text').height()/2});
    setTimeout(function(){
        $('.pop-win-text').addClass('in');
    },500);


    $('.pop-mark').click(function(){
        closePop();
    })
}
function closePop(){
    $('.pop-mark').removeClass('in')
    $('.pop-win-text').removeClass('in');
}

function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}

</script>
</body>
</html>

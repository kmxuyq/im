<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
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
<div class="gray-inner">
    <div class="header">
        <div class="arrow" onclick="javascript:history.back(-1);"></div>
        <div class="tt"><?php if(intval($output['class']) == 5){ echo '五';}else{ echo '一';}?>元参与区</div>

        <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
    </div>
    <div class="history-tt"><?php echo (count($output['group'])?'本期即将揭晓....':'暂无将要揭晓的活动...');?></div>
	<?php if(!empty($output['group'])){?>
	<?php foreach($output['group'] as $k=>$val){if(intval($val['state']==20)){continue;}?>
	<?php if(($val['gend_time']+1800) < time()){ ?>
    <div class="history-fortune">
        <div class="tt">第<?php echo $val['qishu_curr'];?>期(揭晓时间:<?php echo date('Y-m-d H:i',($val['gend_time']+1800));?>)</div>
        <div class="main-des">
            <div class="user-head"><img src="<?php echo ($val['goods_imageurl']);?>" alt=""></div>
            <div class="rt-des">
                <div class="name">获得者:<?php echo ($val['g_winBuyer']);?></div>
                <p class="ip">(<?php echo ($val['g_winBuyerIpxx']);?>)</p>
                <div class="luck-number">幸运号码: <span><?php echo ($val['g_winBuyerCode']);?></span></div>
                <span class="numb">本期参与:<?php echo ($val['g_winBuyerTotal']);?>次</span>
            </div>
        </div>
    </div>
	<?php } ?>
	<?php }}else{?>
    <!--没有往期信息-->
    <div class="member-wrap">
        <div class="yiyuan-icon1"></div>
        <div class="go-home-tip">此商品在等待您，所以暂未揭晓。来试试手气！</div>
    </div>
	<?php }?>
</div>
<script>
function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}
</script>
</body>
</html>

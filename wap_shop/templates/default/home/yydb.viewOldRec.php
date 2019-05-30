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
    <div class="tt">往期中奖名单</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>

<div id="db-tab" class="db-tab">
    <div class="db-tab-body">

        <div class="column">
            <!-- 2 -->
			<?php if(!empty($output['group'])){?>
			<?php foreach($output['group'] as $k=>$val){?>
			<?php if(($val['gend_time']+1800) < time()){ ?>
            <div class="cl-main">
                <div class="tp">
                    <div class="hd"><img src="<?php echo ($val['goods_imageurl']);?>" alt=""></div>
                    <div class="tp-rt">
                        <div class="tt">
                            <span>已经揭晓</span><?php echo ($val['groupbuy_name'].'（'.$val['goods_name'].'）');?>
                        </div>
                        <p><?php echo ($val['remark']);?></p>
                        <div class="el">获得者:<?php echo ($val['g_winBuyer']);?></div>
                        <div class="el">幸运号码: <span class="red"><?php echo ($val['g_winBuyerCode']);?></span> </div>
                        <div class="el">揭晓时间:<?php echo date('Y-m-d H:i',($val['gend_time']+1800));?></div>
                        <div class="el">本期参与:<?php echo ($val['g_winBuyerTotal']);?>人次</div>
                    </div>
                </div>
                <input type="button" class="db-cl-btn" onclick="toDetail(<?php echo $val['groupbuy_id'];?>);" value="查看详细">
            </div>
			<?php }?>
			<?php }}else{?>
            <div class="member-wrap">
                <div class="yiyuan-icon2"></div>
                <div class="go-home-tip">还没有中奖名单哦!</div>
            </div>
            <input type="button" class="btn" onclick="toBuy();" value="立即参与">
			<?php }?>
        </div>


    </div>
</div>
<script>

function toDetail(gid){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=dbgoods&gr_id="+gid;
	return;
}

function toBuy(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao";
	return;
}

function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}

</script>
</body>
</html>

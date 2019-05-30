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
    <div class="tt">
	<?php
		if(isset($output['class'])){
			if(intval($output['class']) == 5){
				echo "五";
			}else{
				echo "一";
			}
		}else{
			if(intval($_GET['class']) == 5){
				echo "五";
			}else{
				echo "一";
			}
		}
	?>元参与区</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>
<div class="note-help">积分兑换比例：1元=10积分</div>

<!-- 积分兑换 -->
<div class="moblie-number">
    <div class="phone-num">请输入您要兑换的积分</div>
    <input placeholder="所需兑换的积分" type="text" class="phone-text" readonly="readonly" value="<?php echo $output['points'];?>">
    <div class="code-numb">兑换积分为: <span><?php echo $output['points'];?></span></div>
</div>
<!-- 兑换须知 -->
<div class="help-tip">点击支付订单表示已阅读并同意预定须知</div>
<input class="bt-btn" value="微信支付" type="button" onclick="popWin()">
<script>

function popWin(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&op=addPtsWx&gid=<?php echo $output['gr_id'];?>&points=<?php echo $output['points'];?>&bc=<?php echo $output['bc'];?>";
	return;
}

function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}

</script>
</body>
</html>

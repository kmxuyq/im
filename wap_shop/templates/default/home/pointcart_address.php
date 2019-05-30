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
<title>申领地址</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">
<!--申领地址-->
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/header_sign.css" rel="stylesheet" type="text/css">
<header class="lbdb_header">
	<span class="lbdb_arrow" onclick="location.href = 'index.php?act=pointprod'"></span>	
	<h1>确认收货地址</h1>
</header>
<div class="claim_address">
    <div class="title">
        请确认您的收货地址
        <a href="index.php?act=member_address&op=address">设置</a>
    </div>
    <div class="claim_address_cons">
        <div class="name"><?php echo $output['address_info']['true_name'];?></div>
        <div class="tel_num"><?php if($output['address_info']['mob_phone'] != ''){ echo $output['address_info']['mob_phone'];}else{echo $output['address_info']['tel_phone'];}?></div>
        <div class="clear"></div>
        <div class="address_txt"><?php echo $output['address_info']['area_info'];?> <?php echo $output['address_info']['address'];?></div>
    </div>
    <div class="notice">系统只提取默认地址，请在申请前设置完成！</div>
    <div class="claim_btn"><a href="index.php?act=pointcart&op=step1">提交地址</a></div>
</div>
</body>
</html>

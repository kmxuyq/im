<!DOCTYPE html>
<html lang="zh-cmn-Hans">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<meta name="keywords" content="your keywords">
		<meta name="description" content="your description">
		<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base.css">		
		<title>礼品申领</title>
		
	</head>
	<body>
	<div >
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/wx-pic.jpg" width="100%"/>
	<p class="pub-title" style="width:70%"><?php echo $output["message"]?></p>	
	<center><a href="<?php echo BASE_SITE_URL?>/wap_shop/index.php?act=active&op=index&type=appaly_goods&wx=2&goods_id=<?php echo encrypt($output["goods_id"])?>&present_member_id=<?php echo encrypt($output["present_member_id"])?>&openid=<?php echo $output["openid"]?>" class="pub-submit">邮寄给我</a></center>
	</body>
</html>
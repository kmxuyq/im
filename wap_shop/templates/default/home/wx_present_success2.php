<!DOCTYPE html>
<html lang="zh-cmn-Hans">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" /> 
        <meta content="telephone=no" name="format-detection" />
<!-- UC默认竖屏 ，UC强制全屏 -->
<meta name="full-screen" content="yes"/>
<meta name="browsermode" content="application"/>
<!-- QQ强制竖屏 QQ强制全屏 -->
<meta name="x5-orientation" content="portrait"/>
<meta name="x5-fullscreen" content="true"/>
<meta name="x5-page-mode" content="app"/>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name=*apple-touch-fullscreen* content=*yes*>
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<meta name="viewport" id="viewportid" content="target-densitydpi=285,width=600,user-scalable=no" />

		<meta name="keywords" content="your keywords">
		<meta name="description" content="your description">
        <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base.css">
        <link rel="stylesheet" href="../css/wx/base.css">		
		<title>申领成功</title>

		<style>
body{ margin:0 auto}
</style>
	</head>
	<body><div align="center">
    <div class="content">
  <!--content home-->  
	<div class="success_content">
<div class="img1"></div>
<div class="img2">
<p class="message1">领取成功， <br/>
我们会尽快安排礼物快递给您。</p>
<p class="message"><?php echo $output["message"]?>
</p>
<p class="message2">成为GF谜之大使<br/>轻松分享，获取专属奢华礼遇</p>
</div>
<div class="img3"><p style="height:100px"></p>
<a href="?act=weixin_active&op=wx_present_code"><span class="button">成为谜之大使</span></a>
</div>
	</div>
    
    <!--footer-->
<div class="footer_wx"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/footer.png"/></div>
<!--content end-->
</div>
</div>
	</body>
</html>
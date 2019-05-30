<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">
<meta name="full-screen" content="yes">
<meta name="x5-fullscreen" content="true">
<meta name="browsermode" content="application">
<meta name="x5-page-mode" content="app">
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
<script type="text/javascript" src="/wap/js/swiper.min.js"></script>
<script type="text/javascript" src="/wap/js/iscroll.js"></script>
<script type="text/javascript" src="/wap/js/menu.js"></script>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.css">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <!-- 视频和订单修改js-->
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">

<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>

<style>
#footer_<?php echo $_GET["act"]?>{color:#bba059;}

#footer_<?php echo $_GET["act"]?> #span_<?php echo $_GET["act"]?>{
    background: url(<?php echo SHOP_TEMPLATES_URL;?>/images/footericon_<?php echo $_GET["act"]?>.png) no-repeat center -20px;
    background-size: auto 40px;
    }
</style>
</head>
<body>
<div class="sub_menu_bg"></div>
<div class="sub_menu" id="myScroll"></div>
    <?php require_once($tpl_file);?>
    <?php require_once template('sharejs');?>
<?php
if($_SESSION['store_id']){
   $settings = Model('share_settings')->where(array('store_id'=>$_SESSION['store_id']))->find();
}
?>
<script>var follow_url = '<?php echo $settings['follow_url']; ?>';</script>
<div id="footer_html"></div>
<?php require_once('nav.php');?>
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css" />
<script type="text/javascript" src="/wap/js/tmpl/footer_html.js"></script>
<!-- 引入 -->
<?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</body></html>

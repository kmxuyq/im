<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="screen-orientation" content="portrait">
<meta name="x5-orientation" content="portrait">
<meta name="full-screen" content="yes">
<meta name="x5-fullscreen" content="true">
<meta name="browsermode" content="application">
<meta name="x5-page-mode" content="app">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.css">
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css" />
<?php include template('layout/common_layout');?>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>

<script type="text/javascript" src="/wap/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="/wap/js/swiper.min.js"></script>
<script type="text/javascript" src="/wap/js/iscroll.js"></script>
<!-- 视频和订单修改js-->
<?php include template('layout/cur_local');?>
<?php require_once($tpl_file);?>
<?php require_once template('footer');?>
<?php require_once template('sharejs');?>
<div id="footer_html"></div>
<script type="text/javascript" src="/wap/js/tmpl/footer_html.js"></script>
<!-- 引入 -->
<?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</body>
</html>

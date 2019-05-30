<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title><?php echo $output['html_title'];?></title>
<!--<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_cart.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />-->
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo WAP_SHOP_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_TEMPLATES_URL;?>';var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<?php if ($_GET['act'] != 'buy_virtual') {?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/goods_cart.js"></script>
<?php } else { ?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/buy_virtual.js"></script>
<?php } ?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
try{
document.execCommand("BackgroundImageCache", false, true);
   }
catch(e){}
// ]]>
</script>
<![endif]-->
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script type="text/javascript" src="/wap/js/swiper.min.js"></script>
<script type="text/javascript" src="/wap/js/menu.js"></script>
</head>
<body>
<div id="menu"></div>
<!--< ?/*php require_once template('layout/layout_top');*/?>-->  <!--<header class="ncc-head-layout">
    <div class="site-logo"><a href="<?php echo WAP_SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></div>
    <?php if ($_GET['op'] != 'pd_pay' && $_GET['act'] != 'buy_virtual') { ?>
    <ul class="ncc-flow">
      <li class="<?php echo $output['buy_step'] == 'step1' ? 'current' : '';?>"><i class="step1"></i>
        <p><?php echo $lang['cart_index_ensure_order'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step2' ? 'current' : '';?>"><i class="step2"></i>
        <p><?php echo $lang['cart_index_ensure_info'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step3' ? 'current' : '';?>"><i class="step3"></i>
        <p><?php echo $lang['cart_index_payment'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
      <li class="<?php echo $output['buy_step'] == 'step4' ? 'current' : '';?>"><i class="step4"></i>
        <p><?php echo $lang['cart_index_buy_finish'];?></p>
        <sub></sub>
        <div class="hr"></div>
      </li>
    </ul>
    <?php } ?>
  </header>-->


  <?php require_once($tpl_file);?>
  <!-- 引入 -->
  <?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</body>
</html>

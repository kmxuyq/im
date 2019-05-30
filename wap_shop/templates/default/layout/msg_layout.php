<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php echo $output['html_title'];?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>js/TouchSlide.1.1.js" ></script>
</head>
<style>
.article_page{
	  padding: 20px 10px;
}
.article_tt{
  text-align: center;
  height: 35px;
  line-height: 35px;
  font-size: 16px;
  font-family: "Microsoft YaHei";
  color: #5c6b7a;
}
</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <h1 class="qz-color">提示信息</h1>
</header>
<div class="article_page">
    <div class="article_tt"><?php require_once($tpl_file);?></div>
</div>
<script type="text/javascript">
<?php if (!empty($output['url'])){
?>
	window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>);
<?php
}else{
?>
	window.setTimeout("javascript:history.back()", <?php echo $time;?>);
<?php
}?>
</script>
<script>
$(function(){
    $('.emaill_radio_box .adchek').click(function(){
        var this_parent=$(this).parent();
        if($(this).is(':checked')){
            $('.emaill_radio_box span').removeClass('on');
            this_parent.addClass('on');
        }
    })
})
</script>
</body>
</html>

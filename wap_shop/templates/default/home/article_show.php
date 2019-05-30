<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color"><?php if($output['ac_id'] == 2){ echo '帮助中心';}else if($output['ac_id'] == 5){ echo '售后服务';} ?></h1>
<span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>
<div class="article_page">
    <div class="article_tt"><?php echo $output['article']['article_title'];?></div> 
    <div class="article_content"><?php echo $output['article']['article_content'];?></div>
</div>
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
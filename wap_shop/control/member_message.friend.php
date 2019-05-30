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
    <i class="ui-icon-return" onclick="history.back()" style="color:#10b0e6;font-size: 24px;"></i>
    <h1 class="qz-color">我的好友</h1>
    <span class="recharge_send_btn">添加</span>

</header>
<?php if(!empty($output['friend_list'])){ ?>
<div class="contact_person">
    <div class="contact_person_choose"><input type="checkbox" value="<?php echo $val['friend_tomname']; ?> "/></div>
    <div class="imgBox"><img src="<?php echo getMemberAvatar($val['friend_tomavatar']);?>" alt=""></div>
    <div class="person_name"><?php echo $val['friend_tomname']; ?> </div>
</div>
<?php }else{ ?>
<div class="contact_person">
    <div class="person_name" onclick="index.php?act=member_snsfriend&op=find">添加新好友</div>
</div>
<?php } ?>
<script>
$(function(){
    /**/
    $('.contact_person_choose input[type="checkbox"]').click(function(){
        if( $(this).parent().hasClass('on')){
            $(this).parent().removeClass('on');
        }else{
            $(this).parent().addClass('on');
        }
    })
});
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
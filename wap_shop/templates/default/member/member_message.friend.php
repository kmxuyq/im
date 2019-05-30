<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui_1.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style_1.css" />
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">我的好友</h1>
    <span class="recharge_send_btn" id="add_button">添加</span>

</header>
<?php if(!empty($output['friend_list'])){ ?>
<?php foreach($output['friend_list'] as $val){?>
<div class="contact_person ">
    <div class="contact_person_choose <?php echo $val['checked'] != ''?'on':'';?>"><input class="ck_name"<?php echo $val['checked'] != ''?'checked':'';?> type="checkbox" id="<?php echo $val['friend_tomname']; ?>"/></div>
    <div class="imgBox"><img src="<?php echo getMemberAvatar($val['friend_tomavatar']);?>" alt=""></div>
    <div class="person_name"><?php echo $val['friend_tomname']; ?> </div>
</div>
<?php } ?>
<?php }else{ ?>
<div class="contact_person">
    <div class="person_name" onclick="index.php?act=member_snsfriend&op=find">添加新好友</div>
</div>
<?php } ?>
<script>
$(function(){
	
	$('#add_button').click(function(){
		var toStr = '';
		$('.ck_name').each(function(){
			if($(this).attr('checked') == 'checked'){
				toStr += $(this).attr('id')+','
			}
		})
		window.location.href = 'index.php?act=member_message&op=sendmsg&namestr='+toStr;
	})
     $('.ck_name').each(function(){
		 $(this).on('click',function(){
			  if($(this).attr('checked') == 'checked'){
				  $(this).parent('.contact_person_choose').addClass('on')
			  }else{
				  $(this).parent('.contact_person_choose').removeClass('on')
			  }
		 })
	 })
});
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
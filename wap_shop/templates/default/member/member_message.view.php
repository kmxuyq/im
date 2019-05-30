<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>查看站内信</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
	<script type="text/javascript" src="js/TouchSlide.1.1.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">查看站内信</h1>
	<!--delete by tanzn start
    <span class="user_setInfo"></span>	
    <ul class="user_setInfo_cell">	
       <li onclick="location.href = 'index.php?act=member_message&op=sendmsg'">发送站内信</li>
       <li onclick="location.href = 'index.php?act=member_message&op=setting'"> 接收设置</li>
    </ul>
	delete by tanzn stop -->
</header>
<div class="infoBox_wrap">
    <div class="infoBox_border_wrap">
    <?php if(!empty($output['message_list'])) { ?>
    <?php foreach ($output['message_list'] as $k=>$v){?>
        <div class="my_info_text">
            <div class="counseling_dec">
                <span class="counseling_respond"><?php echo date("Y-m-d H:i",$v['message_time']); ?></span>
            </div>
            <p class="counseling_text">
            <?php if ($output['drop_type'] == 'msg_seller'){?>
            <?php echo '<span class="t1">'.$v['from_member_name']; ?> 
			<?php echo $lang['home_message_speak'].'</span>'; 
			}elseif ($output['drop_type'] == 'msg_system') {
        	echo '<span class="t1">'.$v['from_member_name'].'</span>';
        	} else {
        		echo '<span class="t1">'.$v['from_member_name'].$lang['home_message_speak'];} ?><?php echo $lang['nc_colon'].'</span>';
			?>
			<?/*php echo nl2br(parsesmiles($v['message_body'])); */?>
			<?php
			$az = array('shop'=>'wap_shop');
			echo strtr(nl2br(parsesmiles($v['message_body'])),$az); ?>
			</p>
        </div>
    <?php } ?>
    <?php } ?> 
    </div>
</div>
<?php if($_GET['drop_type'] == 'msg_list' && $output['isallowsend']){?>
<form id="replyform" method="post" action="index.php?act=member_message&op=savereply">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="message_id" value="<?php echo $output['message_id']; ?>" />
<div class="infoBox_wrap padWrap14">
    <div class="service_msg">
        <div class="text"><?php echo $lang['home_message_reply'].$lang['nc_colon'];?></div>
        <textarea name="msg_content" id="msg_content" cols="30" rows="10"></textarea>
    </div>
    <div class="service_subBtn">
        <input type="button" id="form_button" class="public_btn1 fl" value="<?php echo $lang['home_message_submit'];?>">
    </div>
</div>
<script type="text/javascript">
$(function(){
	$('#form_button').click(function(){
		if($('#msg_content').val() !== ''){
			$('#replyform').submit()
		}else{
			alert('回复内容不能为空')
		}
	})
})
</script>
<?php }?>
<script>
$(function(){
    $('.user_setInfo').click(function(){
        if($('.user_setInfo_cell').css('display') ==='none'){
			$('.user_setInfo_cell').fadeIn()
            $('.block_mark').fadeIn()
		}else{
			$('.user_setInfo_cell').fadeOut()
           $('.block_mark').fadeOut()
		}
            

    });
    $('.block_mark').click(function(){
        $('.user_setInfo_cell').fadeOut()
        $('.block_mark').fadeOut()
    });

});
</script>
<script type="text/javascript">TouchSlide({ slideCell:"#leftTabBox" });</script>
<style>
    body{ background:#f5f5f5;}
</style>
</body>
</html>
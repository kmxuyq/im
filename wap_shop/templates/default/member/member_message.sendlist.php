<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
    <title></title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member&op=home'"></i>
    <h1 class="qz-color">我的消息</h1>
    <span class="user_setInfo"></span>
    <ul class="user_setInfo_cell">
       <li onclick="location.href = 'index.php?act=member_message&op=sendmsg'">发送站内信</li>
       <li onclick="location.href = 'index.php?act=member_message&op=setting'"> 接收设置</li>
    </ul>
</header>
<div  class="tabBox-main">
    <div class="hd tabBox-tt2">
         <ul>
            <li onclick="location.href='index.php?act=member_message&op=message'" class="<?php if($_GET['op'] == 'message'){echo 'on';}?>">收到(<?php echo $output['newcommon'];?>)</li>
            <li onclick="location.href='index.php?act=member_message&op=privatemsg'" class="<?php if($_GET['op'] == 'privatemsg'){echo 'on';}?>">已发送</li>
            <li onclick="location.href='index.php?act=member_message&op=systemmsg'" class="<?php if($_GET['op'] == 'systemmsg'){echo 'on';}?>">系统(<?php echo $output['newsystem'];?>)</li>
            <li onclick="location.href='index.php?act=member_message&op=personalmsg'" class="<?php if($_GET['op'] == 'personalmsg'){echo 'on';}?>">私信(<?php echo $output['newpersonal'];?>)</li>
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
        <div class="bd">
            <!--收到(-->
            <div class="msgBoxWrap">
            <?php if (!empty($output['message_array'])) { ?>
            <?php foreach($output['message_array'] as $k => $v){ ?>
                <div class="counseling adBod">
                    <div class="counseling_dec">
                        <span class="<?php if($_GET['op'] != 'systemmsg'){ echo 'counseling_respond';}else{ echo 'window_tip';}?>"><?php echo $v['to_member_name']; ?></span>
                        <em class="counseline_times2"><?php echo @date("Y-m-d H:i:s",$v['message_time']); ?></em>
                    </div>
                    <p class="counseling_text"><?php echo parsesmiles($v['message_body']); ?></p>
                    <div class="operate_btn noBd">
				   
				        <a href="index.php?act=member_message&op=dropcommonmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>" >
                          <input type="button" class="remove_btn">
                        </a>
				
                    </div>
                </div>
            <?php } ?>
            <?php }else{ ?>
			<?php echo $lang['no_record'];?>
			<?php } ?>			
            </div>    
        </div>
    </div>
	<div class="tempWrap" id="show_page" style="clear: both">
	<?php if (!empty($output['message_array'])) { ?>      
      <?php echo $output['show_page']; ?>
      <?php } ?>
	</div>
</div>
<div class="block_mark"></div>
<script>
$(function(){
    $('.user_setInfo').click(function(){

            $('.user_setInfo_cell').fadeIn()
            $('.block_mark').fadeIn()

    });
    $('.block_mark').click(function(){
        $('.user_setInfo_cell').fadeOut()
        $('.block_mark').fadeOut()
    });
});
</script>
<style>
#show_page li{
	float:left;
	margin:5px;
}
    body{ background:#f5f5f5;}
</style>
</body>
</html>
<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
    <title>我的消息</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member&op=home'"></i>
    <h1 class="qz-color">消息(<?php echo $output['newsystem'];?>)</h1>
    <span class="user_setInfo"></span>
    <ul class="user_setInfo_cell">
       <li onclick="location.href = 'index.php?act=member_message&op=setting'"> 接收设置</li>
    </ul>
</header>
<div  class="tabBox-main">

    <div class="hd tabBox-tt2">
        <ul>
		<!-- delete by tanzn start
            <li onclick="location.href='index.php?act=member_message&op=message'" class="<?php if($_GET['op'] == 'message'){echo 'on';}?>">收到(<?php echo $output['newcommon'];?>)</li>
            <li onclick="location.href='index.php?act=member_message&op=privatemsg'" class="<?php if($_GET['op'] == 'privatemsg'){echo 'on';}?>">已发送</li>
			
            <li onclick="location.href='index.php?act=member_message&op=systemmsg'" class="<?php if($_GET['op'] == 'systemmsg'){echo 'on';}?>">系统(<?php echo $output['newsystem'];?>)</li>
            
			<li onclick="location.href='index.php?act=member_message&op=personalmsg'" class="<?php if($_GET['op'] == 'personalmsg'){echo 'on';}?>">私信(<?php echo $output['newpersonal'];?>)</li>
			
        </ul>
    </div>
	delete by tanzn end-->
    <div class="tempWrap" style="clear: both">
        <div class="bd">
            <!--收到(-->
            <div class="msgBoxWrap">
            <?php if (empty($output['message_array'])) { ?>
            <?php foreach($output['message_array'] as $k => $v){ ?>
                <div class="counseling adBod">
                    <div class="counseling_dec">
                        <span class="<?php if($_GET['op'] != 'systemmsg'){ echo 'counseling_respond';}else{ echo 'window_tip';}?>"><?php echo $v['from_member_name']; ?></span>
                        <em class="counseline_times2"><?php echo @date("Y-m-d H:i:s",$v['message_update_time']); ?></em>
                    </div>
                    <p class="counseling_text">
					<?php $az = array('shop' => 'wap_shop'); echo strtr(parsesmiles($v['message_body']),$az); ?>
					</p>
                    <div class="operate_btn noBd">
					
					<?php if ($output['drop_type'] == 'msg_list'){?>
                         <a href="index.php?act=member_message&op=showmsgcommon&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?><?php if($v['message_parent_id']>0) echo '#'.$v['message_id']; ?>" >
                         <input type="button" value="查看" class="btn<?php if($v["message_state"]=='3') echo '_0';?>">
                         </a>
				   
						 <!--<a href="index.php?act=member_message&op=dropbatchmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>" >-->
						 <a href="javascript:if(confirm('确实要删除该内容吗?'))location='index.php?act=member_message&op=dropcommonmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>'"><input type="button" class="remove_btn"></a>
                   <?php }?>
				   
                   <?php if ($output['drop_type'] == 'msg_system' || $output['drop_type'] == 'msg_seller'){?>
                        <a href="index.php?act=member_message&op=showmsgbatch&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?><?php if($v['message_parent_id']>0) echo '#'.$v['message_id']; ?>" >
                         <input type="button" value="查看" class="btn<?php if($v["message_state"]=='3') echo '_0';?>">
                        </a>
				   
						<a href="javascript:if(confirm('确实要删除该内容吗?'))location='index.php?act=member_message&op=dropbatchmsg&drop_type=<?php echo $output['drop_type']; ?>&message_id=<?php echo $v['message_id']; ?>'">
                          <input type="button" class="remove_btn">
                        </a>
                  <?php }?>
				
                    </div>
                </div>
            <?php } ?>
            <?php }else{ ?>
                <div class="msg_page_number">
			    <?php echo $lang['no_record'];?>
                </div>
			<?php } ?>			
            </div>    
        </div>
    </div>
	<div class="tempWrap" id="show_page" style="clear: both">
	<?php if (!empty($output['message_array'])) { ?>
        <div class="msg_page_number">
            <?php echo $output['show_page']; ?>
        </div>
      <?php } ?>
	</div>
</div>
<div class="block_mark"></div>
<script>
$(function(){
	$('#az').click(function(){
confirm('你确定要删除？');
	})

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
#show_page .msg_page_number{text-align: center}
#show_page .msg_page_number li{
    float: none;
    display: inline;
}
    body{ background:#f5f5f5;}
</style>
</body>
</html>
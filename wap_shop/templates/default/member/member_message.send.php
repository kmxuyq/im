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
<form method="post" id="send_form" action="index.php?act=member_message&op=savemsg">
<input type="hidden" name="form_submit" value="ok" />
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class=
    " onclick="location.href='index.php?act=member_message&op=message'"></i>
    <h1 class="qz-color">我的消息</h1>
    <span class="recharge_send_btn" id="form_button">发送</span>

</header>

<div class="emaill_pdWrap">
    <div class="emaill_tt">收件人</div>
    <div class="add_emaill_name">
        <input class="text_btn1" type="text" name="to_member_name" value="<?php echo $output['member_name']; ?>" placeholder="多个收件人请用逗号分隔" /> 
        <input type="button" class="add_emaill_btn" id="add_friend" value="+" />
    </div>
    <div class="emaill_text_tip">多个收件人请用逗号分隔</div>

    <div class="emaill_tt emaillTop">消息类型</div>
    <div class="emaill_radio_box">
        <span class="on">
			<input type="radio" class="adchek" value="2" name="msg_type" checked="checked" />
            <?php echo $lang['home_message_open'];?>
        </span>
        <span>
			<input type="radio" class="adchek" name="msg_type" value="0" />
              <?php echo $lang['home_message_close'];?>
        </span>
    </div>
<textarea name="msg_content"  cols="30" rows="10" class="emaill_dec"></textarea>
</div>
</script>
<script>
$(function(){
	$('#form_button').click(function(){
		var namestr = $.trim($('input[name="to_member_name"]').val())
		var content = $.trim($('textarea[name="msg_content"]').val())
		var Error = '';
		if(namestr==''){
			Error +='\n请填写收件人'
		}
		if(content == ''){
			Error += '\n发送内容不能为空'
		}
		if(Error === ''){
			$('#send_form').submit()
		}else{
			alert('错误提示！'+Error)
		}
	})
	
	$('#add_friend').click(function(){
         var namestr = $('input[name="to_member_name"]').val()
		window.location.href='index.php?act=member_message&op=showfriend&namestr='+namestr;
	})
    $('.emaill_radio_box .adchek').click(function(){
        var this_parent=$(this).parent();
        if($(this).is(':checked')){
            $('.emaill_radio_box span').removeClass('on');
            this_parent.addClass('on');
        }
    })
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
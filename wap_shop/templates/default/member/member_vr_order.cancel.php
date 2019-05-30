<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>取消订单</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member_vr_order&state_type=state_new'"></i>
    <h1 class="qz-color">取消订单</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<form method="post" id="form-box" action="index.php?act=member_vr_order&op=change_state&state_type=order_cancel&order_id=<?php echo $output['order_info']['order_id']; ?>" >
<input type="hidden" name="form_submit" value="ok" />
<div class="order_number_wrap">
    <div class="order_number"><?php echo $lang['member_order_sn'].$lang['nc_colon'];?><?php echo $output['order_info']['order_sn']; ?></div>
    <div class="order_number_tt">取消原因:</div>
    <ul class="order_number_choose">
        <li class="item">
            <input class="choose_radio" checked name="state_info" id="d1" value="<?php echo $lang['member_change_other_goods'];?>" type="button"/>
            <span><?php echo $lang['member_change_other_goods'];?></span>
        </li>
        <li class="item">
            <input class="choose_radio" name="state_info" id="d2" value="<?php echo $lang['member_change_other_store'];?>" type="button"/>
            <span><?php echo $lang['member_change_other_store'];?></span>
        </li>
        <li class="item">
            <input class="choose_radio" name="state_info"  id="d4" value="" type="button" />
            <span>其他原因</span>
        </li>
		<li class="item" id="state_info1"style="display:none">
            <textarea name="state_info1" style="width:80%;height:80px;"></textarea>
        </li>
    </ul>
</div>
<div class="service_subBtn">
    <input type="button" id="form-submit" class="public_btn1" value="确认提交">
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1)" value="取消">
</div>
</form>
<!---->
<script>
$(function(){
	$('#form-box').find('input[name="state_info"]').each(function(){
		var value = $(this).val()
		$(this).parent('.item').click(function(){
			if(value == ''){
				$('#state_info1').css({'display':'block'})
			}else{
				$('#state_info1').css({'display':'none'})
			}
		})
	})
	$('#form-submit').click(function(){
		
		$('#form-box').submit()
	})
})
$(function(){
    $(".order_number_choose li").click(function(){
        if(!$(this).hasClass('on')){
            $(".order_number_choose li").removeClass('on');
            $(this).addClass('on');
            $(this).find('.choose_radio').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });
});
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













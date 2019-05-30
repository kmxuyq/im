<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>退款服务</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?PHP echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退款申请</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 有效期内的未使用兑换码都可申请退款，同意退款后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</p>
    <p class="text">2. 已过期兑换码如果“支持过期退款”，请在到期后<span class="tip_red">“<?php echo CODE_INVALID_REFUND;?>天内”</span>申请退款，<?php echo CODE_INVALID_REFUND;?>天后则不能申请。</p>
    <p class="text">3. 如果平台不同意退款，自动解除兑换码的锁定状态，在有效期内可以继续兑换使用。</p>
</div>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>买家<br>申请退款</span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>锁定<br>兑换码 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>平台审核<br>退款完成 </span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<div class="drawback_top_tip">如果提交退款保存成功，选择的对应兑换码将被锁定即不能进行兑换。</div>
<!--退货原因-->
 <form id="post_form1" enctype="multipart/form-data" method="post" action="index.php?act=member_vr_refund&op=add_refund&order_id=<?php echo $output['order']['order_id']; ?>">
 <input type="hidden" name="form_submit" value="ok" />
 <div class="service_wrap ">
    <div class="service_type_select">
        <div class="text">可退款兑换码</div>
        <div class="text" id="code_list">
		<?php if (is_array($output['code_list']) && !empty($output['code_list'])) { ?>
        <?php foreach ($output['code_list'] as $key => $val) { ?>
		<label><input name="rec_id[]" class="vm" type="checkbox" value="<?php echo $val['rec_id'];?>" checked="checked" /><?php echo $val['vr_code'];?></label><br />
		<?php } ?>
		<?php } ?>
		</div>
    </div>
    <div class="service_msg">
        <div class="text">退款说明</div>
        <textarea name="buyer_message"  cols="30" rows="10"></textarea>
    </div>
    <div class="service_subBtn">
	<input type="button" id="tj" class="public_btn1" value="确认">
        <!--<input type="button" name="button" class="public_btn1" value="确认">-->
		<!--<a href="javascript:void();" class="public_btn1">确认</a>-->
        <input type="button" class="public_btn2" onclick="javascript:history.go(-1);" value="返回">
    </div>
</div>
</form>
<script type="text/javascript">
$(function(){
	
   // $('input[name="button"]').click(function(){
	   $("#tj").click(function(){
		var error = '';
		if($('#code_list').html() !== ''){
		  if(!$('input[name="rec_id[]"]').attr('checked')){
			error += '请选择可兑换码，';
		  }
		  if($('textarea[name="buyer_message"]').val() == ''){
			error += '请填写你的退款说明，';
		  }
		}else{
			error = '对不起，你没有可退款兑换码。如有疑问请联系售后服务';
		}
		if(error == ''){
			$('#post_form1').submit()
		}else{
			alert(error)
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













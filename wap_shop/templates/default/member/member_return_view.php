<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>退货服务</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退货服务</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 若您未收到货，或已收到货且与商家达成一致不退货仅退款时，请选择<span class="tip_red">“仅退款”</span>选项。</p>
    <p class="text">2. 若为商品问题，或者不想要了且与商家达成一致退货，请选择<span class="tip_red">“退货退款”</span>选项，退货后请保留物流底单。</p>
    <p class="text">3. 若提出申请后，商家拒绝退款或退货，可再次提交申请或选择<span class="tip_red">“商品投诉”</span>，请求商城客服人员介入。</p>
    <p class="text">4. 成功完成退款/退货；经过商城审核后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</p>
</div>
<div class="schedule2">
    <ul class="list">
        <li class="active">
            <span>买家<br>申请退款</span>
            <b class="radius_dot"></b>
        </li>
        <li class="active">
            <span>商家处理<br>退款申请 </span>
            <b class="<?php echo $output['return']['seller_time'] > 0 ? 'radius_dot':'';?>"></b>
        </li>
        <li class="active">
            <span>买家<br>退货给商家</span>
            <b class="<?php echo ($output['return']['ship_time'] > 0 || $output['return']['return_type']==1) ? 'radius_dot':'';?>"></b>
        </li>
        <li class="active">
            <span>确认收货<br>平台审核</span>
            <b class="<?php echo $output['return']['admin_time'] > 0 ? 'radius_dot':'';?>"></b>
        </li>
    </ul>
</div>
<!-- 我的退款申请 -->
<div class="refund_wrap">
    <div class="refund_tp">我的退款申请</div>
    <div class="wrap_padding">
        <p class="refund_text_row">退货退款编号： <?php echo $output['return']['refund_sn']; ?></p>
        <p class="refund_text_row">退货退款原因： <?php echo $output['return']['reason_info']; ?> </p>
        <p class="refund_text_row">退款金额： <?php echo $lang['currency'];?><?php echo $output['return']['refund_amount']; ?></p>
        <p class="refund_text_row">退货数量： <?php echo $output['return']['return_type']==2 ? $output['return']['goods_num']:'无'; ?></p>
        <p class="refund_text_row">退货退款说明： <?php echo $output['return']['buyer_message']; ?></p>
    </div>
</div>
<!-- 凭证上传 -->
<div class="drawback_uploadpic">
    <div class="tt">凭证上传：</div>
	<?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>
    <ul class="drawback_uploadpic_lst">
	<?php foreach ($output['pic_list'] as $key => $val) { ?>
    <?php if(!empty($val)){ ?>
        <li class="">
            <p class="uploadpic">
			<a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>" >
			<img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>" alt="">
			</a>
			</p>
        </li>
    <?php } ?>
	<?php } ?>	
    </ul>
	<?php } ?>
</div>
<!-- 商家退货处理 -->
<div class="refund_wrap">
    <div class="refund_tp">商家退货处理</div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo $lang['refund_state'].$lang['nc_colon'];?> <?php echo $output['state_array'][$output['return']['seller_state']]; ?></p>
        <?php if ($output['return']['seller_time'] > 0) { ?>
	    <p class="refund_text_row"><?php echo $lang['refund_seller_message'].$lang['nc_colon'];?><?php echo $output['return']['seller_message']; ?></p>
        <?php } ?>
	</div>
</div>
<!-- 请填写退货发货信息 -->
<?php if($output['return']['seller_state'] == 2 && $output['return']['return_type'] == 2 && $output['return']['goods_state'] == 1 && $output['ship'] == 1) { ?>
  
<div class="service_wrap ">
<form id="post_form" method="post" action="index.php?act=member_return&op=ship&return_id=<?php echo $output['return']['refund_id']; ?>">
   <input type="hidden" name="form_submit" value="ok" />   
   <div class="service_type_select">
        <div class="text">物流公司</div>
        <select class="service_select" name="express_id">
            <option value="0">-请选择-</option>
			<?php if(!empty($output['express_list']) && is_array($output['express_list'])){?>
            <?php foreach($output['express_list'] as $key=> $val){?>
            <option value="<?php echo $val['id']; ?>"><?php echo $val['e_name']; ?></option>
			<?php } ?>
            <?php } ?>
        </select>
    </div>
    <div class="service_type_select">
        <div class="text">物流单号</div>
        <div class="drawback_tt">
            <input class="ipt_text" type="text" name="invoice_no" value="">
            <span>(最多<?php echo $lang['currency'];?><?php echo $output['return']['refund_amount']; ?>元)</span>
        </div>
        <div class="drawback_tip_row">发货 <?php echo $output['return_delay'];?> 天后，当商家选择未收到则要进行延迟时间操作；
        如果超过 <?php echo $output['return_confirm'];?> 天不处理按弃货处理，直接由管理员确认退款。</div>
    </div>
	<div class="service_subBtn">
	<input type="button" class="public_btn1" id="confirm_button" value="<?php echo $lang['nc_ok'];?>" />
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1);" value="返回列表">
    </div>
</form>

</div>
<?php } else { ?>
     <?php if ($output['return']['express_id'] > 0 && !empty($output['return']['invoice_no'])) { ?>
    <div class="refund_wrap">
    <div class="refund_tp">我的退货发货信息</div>
    <div class="wrap_padding">
       <p><?php echo '物流信息'.$lang['nc_colon'];?><?php echo $output['return_e_name'].' , '.$output['return']['invoice_no']; ?></p>
	</div>
    </div>
	 <?php } ?>
	 
	<?php if ($output['return']['seller_state'] == 2 && $output['return']['refund_state'] >= 2) { ?>
	<div class="refund_wrap">
    <div class="refund_tp">平台退款审核</div>
    <div class="wrap_padding">
       <p><?php echo '平台确认'.$lang['nc_colon'];?><?php echo $output['admin_array'][$output['return']['refund_state']]; ?></p>
	   <?php if ($output['return']['admin_time'] > 0) { ?>
	   <p><?php echo '平台备注'.$lang['nc_colon'];?><?php echo $output['return']['admin_message']; ?></p>
	   <?php } ?>
	</div>
    </div>
    <?php } ?>	
	<div class="service_subBtn">
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1);" value="返回列表">
    </div>
	  
<?php } ?>
<script type="text/javascript" >
$(function(){
	$('#confirm_button').click(function(){
		var error = '';
		if($('select[name="express_id"] option:selected').val() == 0){
			error += "请选择物流公司,"
		}
		if($('input[name="invoice_no"]').val() == ''){
			error += "物流单号不能为空,";
		}
		if( error == ''){
			$('#post_form').submit()
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













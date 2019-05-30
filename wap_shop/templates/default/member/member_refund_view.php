<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>退款服务</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退款服务</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 若您对订单进行支付后想取消购买且与商家达成一致退款，请填写<span class="tip_red">“订单退款”</span>内容并提交。</p>
	<p class="text">2. 若提出申请后，商家拒绝退款或退货，可再次提交申请或选择<em>“商品投诉”</em>，请求商城客服人员介入。</p>
    <p class="text">3. 成功完成退款/退货；经过商城审核后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</p>
</div>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>买家<br>申请退款</span>
            <b class="radius_dot"></b>
        </li>
        <li class="<?php echo $output['refund']['seller_state'] == 2 ? 'active':'';?>">
            <span>商家处理<br>退款申请 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="<?php echo $output['refund']['refund_state'] ==3 ? 'active':'';?>">
            <span>平台审核<br>退款完成 </span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<!-- 我的退款申请 -->
<div class="refund_wrap">
    <div class="refund_tp">我的退款申请</div>
    <div class="wrap_padding">
        <p class="refund_text_row">退款编号： <?php echo $output['refund']['refund_sn']; ?></p>
        <p class="refund_text_row">退款原因： <?php echo $output['refund']['reason_info']; ?></p>
        <p class="refund_text_row">退款金额： <?php echo $lang['currency'];?><?php echo $output['refund']['refund_amount']; ?></p>
        <p class="refund_text_row">退款说明： <?php echo $output['refund']['buyer_message']; ?></p>
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
			<a href="<?php echo C('remote_upload_url').$val;?>" />
			<img src="<?php echo C('remote_upload_url').$val;?>" alt="">
			</a>
			</p>
        </li>
	 <?php } ?>
	 <?php } ?>
    </ul>
	<?php } ?>
</div>
<!-- 商家退款处理 -->
<div class="refund_wrap">
    <div class="refund_tp">商家退款处理</div>
    <div class="wrap_padding">
        <p class="refund_text_row">审核状态： <?php echo $output['state_array'][$output['refund']['seller_state']]; ?></p>
        <?php if ($output['refund']['seller_time'] > 0) { ?>
	   <p class="refund_text_row">商家备注：<?php echo $output['refund']['seller_message']; ?></p>
		<?php } ?>
	</div>
</div>
<!-- 商城退款审核 -->
<?php if ($output['refund']['refund_state']) { ?>
<div class="refund_wrap">
    <div class="refund_tp">商城退款审核</div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo '平台确认'.$lang['nc_colon'];?> <?php echo $output['admin_array'][$output['refund']['refund_state']]; ?></p>
        <p class="refund_text_row"><?php echo '平台备注'.$lang['nc_colon'];?> <?php echo $output['refund']['admin_message']; ?> </p>
    </div>
</div>
<?php } ?>
<div class="service_subBtn">
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1)" value="返回列表">
</div>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

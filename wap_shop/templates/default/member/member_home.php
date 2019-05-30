<?php defined('InShopNC') or exit('Access Invalid!');?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>个人中心</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/lzf.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/lightSlider.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/style/css/main.css">
<script type="text/javascript">
$(function(){
	$(".recharge_em").click(function(){

$("#azheng").hide();
})
})
</script>

<body ontouchstart>
<div class="header_top" style=" position: absolute; left: 0; top: 0; width: 100%; z-index: 999; background: none">
	<a href="javascript:history.go(-1)" class="lt_icon_arrow"></a>
	<a href="index.php?act=member_message&op=systemmsg" class="rt_icon2"></a>
</div>

<div class="member_info_top">
	<div class="user_header">
		<div class="edit_user_info" onclick="location.href='index.php?act=member_information&op=index'">
			<img  src="<?php echo getMemberAvatar($output['member_info']['member_avatar'], $output["member_time"]); ?>?<?php echo TIMESTAMP; ?>" alt="">
			<p>编辑资料</p>
		</div>
	</div>
	<div class="user_name"><?php echo $output['member_info']['member_name']; ?></div>
	<button class="safe" onclick="location.href='index.php?act=member_security&op=index'">账户安全</button>
</div>

<ul class="member_method_cell">
	<li>
		<span class="icon1" onclick="location.href='index.php?act=member_favorites&op=fglist&show=list'"></span>
		<p>我的收藏</p>
	</li>
	<li>
		<span class="icon2" onclick="location.href='index.php?act=member_goodsbrowse&op=list'"></span>
		<p>浏览历史</p>
	</li>
	<li>
		<span class="icon3" onclick="location.href='index.php?act=member_voucher'"></span>
		<p>我的礼券</p>
	</li>
</ul>
<div class="member_order_info">
   <div class="order_top" onclick="location.href='index.php?act=member_order'">
		<div class="my_order_btn">
			<span class="icon"></span>
			<div class="text">
				<h1>实物订单</h1>
				<p>查看所有订单可以点击全部</p>
			</div>
		</div>
		<button class="rt_button">全部</button>
	</div>
	<div class="order_top" onclick="location.href='index.php?act=member_vr_order'">
		<div class="my_order_btn">
			<span class="icon"></span>
			<div class="text">
				<h1>虚拟订单</h1>
				<p>查看所有订单可以点击全部</p>
			</div>
		</div>
		<button class="rt_button">全部</button>
	</div>
	<ul class="order_list_icon">
		<li>
			<a href="index.php?act=member_vr_order&state_type=state_new">
				<span class="icon1"></span>
				<p>待付款</p>
			</a>
		</li>
		<li>
			<a href="index.php?act=member_vr_order&state_type=state_pay">
				<span class="icon2"></span>
				<p>已付款</p>
			</a>
		</li>
		<li>
			<a href="index.php?act=member_vr_order&state_type=state_noeval">
				<span class="icon3"></span>
				<p>待评价</p>
			</a>
		</li>
		<li>
			<a href="index.php?act=member_vr_order&state_type=state_success">
				<span class="icon4"></span>
				<p>已完成</p>
			</a>
		</li>
	</ul>
</div>

<ul class="member_action_link">
	<li>
		<a href="index.php?act=member_evaluation">
			<span class="icon2"></span>
			<span class="text">我的评价</span>
		</a>
	</li>
	<li>
		<a href="index.php?act=member_complain&op=index">
			<span class="icon3"></span>
			<span class="text">交易投诉</span>
		</a>
	</li>
	<li>
		<a href="index.php?act=article&op=article&ac_id=2">
			<span class="icon5"></span>
			<span class="text">帮助中心</span>
		</a>
	</li>
	<li>
		<a href="index.php?act=article&op=article&ac_id=5">
			<span class="icon6"></span>
			<span class="text">售后服务</span>
		</a>
	</li>
</ul>
<!--分销-->
<!--
<div class="member_Box1" >
	<div class="member_Box1_tt">
		我的推客
	</div>
	<ul class="member_BList">
		<?php if($output['share_member_info']['isshare'] == 1): ?>
			<li>
				<a href="index.php?act=member_share&amp;op=index">
					<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/new/tk_center.png" />
					<span>推客中心</span>
				</a>
			</li>
			<li>
				<a href="index.php?act=show_store&amp;op=share&amp;store_id=<?php echo $_SESSION['share_store_id']; ?>&amp;share_uid=<?php echo $_SESSION['member_id'];?>">
					<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/new/tkxd.png" />
					<span>我的小店</span>
				</a>
			</li>
		<?php else:
			if($output['settings']['share_cnd'] == 1):
				?>
				<li>
					<a href="index.php?act=member_share&amp;op=apply">
						<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/new/sqtk.png" />
						<span>申请成为推客</span>
					</a>
				</li>
			<?php endif; endif;?>

	</ul>
</div>
-->
<!--分销-->
<style>
	body{background: #f4f4f4;}
</style>
</body>
</html>

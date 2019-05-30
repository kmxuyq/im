<?php defined('InShopNC') or exit('Access Invalid!');?>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="your keywords">
<meta name="description" content="your description">
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css">
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/apply.css">
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css">
<title>申领</title>
</head>
<header class="az-header az-header-positive az-header-background az-header-bb noProsition">
   <a href="history.back()">
    <i class="az-icon-return"></i>
   </a>
    <h1 class="qz-color">申领</h1>
</header>
<body class="bg_gray">

<img src="<?php echo thumb($output['goods'],360)?>" width="100%" />
		<div class="ym-apply-box">
			<h1 class="ym-font-18"><?php echo $output['goods']['goods_name'];?></h1>
			<!--
			<p class="ym-col-bba success">
				恭喜您提交成功，工作人员会在活动后X个工作日内将申领奖品寄出，请耐心等待！
			</p>-->
			<div class = "apply-id">
				<p class="price">
					价值：<em class="ym-col-bba">￥</em><span class="ym-col-bba"><?php echo $output['goods']['goods_price'];?></span>
				</p>
				<?php if($_SESSION['is_login']){?>
				<?php if($output['appaly_list']['goods_id']==$output['goods']['goods_id']){?>
				<?php if($output['appaly_list']['az_active_state']=='0'){?>
                    <img class="az_appaly_img" src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-get.png"/>
                    <?php }elseif($output['appaly_list']['az_active_state']=='1'){?>
                    <img class="az_appaly_img" src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-success.png"/>
                    <?php }elseif ($output['appaly_list']['az_active_state']=='2') {?>
                    <img class="az_appaly_img" src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-fail.png"/>
                    <?php }?>
			<?php }else {?>
			<?php if(!empty($output['appaly_list1']['member_id'])){//判断是否已经领取过?>
			<a id="az_applay" class="ym-blue-btn">申领试用</a>
			<?php }else {?>
			<a href="<?php echo urlShopWAP('active','index',array('type'=>'appaly_goods','wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$output['goods']['goods_id']))?>" class="ym-blue-btn">申领试用</a>
			<?php }?>
			<?php }?>
			<?php }else {?>
			<a href="<?php echo urlShopWap('login', 'index');?>" class="ym-blue-btn">申领试用</a>
			<?php }?>
			</div>
		</div>

		<?php if($_GET['cate_id'] == 1106){?>
			<div class="ym-apply-box">
			<h1 class="ym-font-14">发放条件</h1>
			<p class="ym-words-list">
				1.登陆滴滴出行，点击左上方菜单进入积分商城；<br/>
				2.找到婕珞芙灵感花园多效精华油兑换产品，点击“立即兑换”获得“兑换码”；<br/>
				3.关注婕珞芙GF公众号，并注册成为GF会员；<br/>
				4.在公众号中回复关键字“D”，或点击菜单中的“滴滴兑换”，进入商品兑换页面；<br/>
				5.输入兑换码，并填写相关信息，确认兑换成功即可领取商品。
			</p>
			<p class="ym-foot"><strong><?php echo $output['goods']['appaly_tatol_person'];?></strong>人已申请，赶快去申请吧！</p>
		</div>
		<?php }else {?>
		<div class="ym-apply-box">
			<h1 class="ym-font-14">发放条件</h1>
			<p class="ym-words-list">
				1.所有会员均可免费申请，申请成功无需支付邮费<br/>
				2.申请成功需要提交真实原创的试用报告
			</p>
			<p class="ym-foot"><strong><?php echo $output['goods']['appaly_tatol_person'];?></strong>人已申请，赶快去申请吧！</p>
		</div>
		<?php }?>

		<ul class="ym-apply-tab clearfix">
			<li class="on">试用规则</li>
			<li>图文描述</li>
		</ul>
		<div class="ym-tab-box">
			<span class="ym-small-title">申请流程</span>

			<?php if($_GET['cate_id'] == 1106){?>
			<span class="ym-apply-flow"><img src="<?php echo SHOP_TEMPLATES_URL;?>/img/apply-flow-img2.jpg" width="100%"/></span>
			<?php }else {?>
			<span class="ym-apply-flow"><img src="<?php echo SHOP_TEMPLATES_URL;?>/img/apply-flow-img.jpg" width="100%"/></span>
			<?php }?>

			<span class="ym-small-title">活动规则</span>
			<p class="ym-words-list">
				<strong>第一步：</strong>
				注册成为婕珞芙官网会员并完善您的个人信息及收货地址，即可马上参与申请免费试用。
				（注：为了您能及时地收到我们为您准备的试用产品，请务必提交真实并详细的个人信息，保证寄送地址正确并保持手机畅通！）<br/><br/>
				<strong>第二步：</strong>
				选择您喜欢的的单品，确认寄送信息无误后提交试用申请即可。<br/>
				我们将尽快为您安排试用品的派送。
			</p>
			<span class="ym-small-title">温馨提示</span>
			<ol>
				<li><em>1.</em>只有注册会员才可以参与免费试用活动，且每位
    注册会员每个试用产品仅能申领一次。</li>
				<li><em>2.</em>若因会员联系资料（姓名、电话、地址、邮编）
    不完整，婕珞芙官网有权取消其试用资格。</li>
				<li><em>3.</em>若因会员填写的联系资料（姓名、电话、地址、
    邮编）错误导致的未能收到货品，婕珞芙官网将
    不予安排补寄。</li>
				<li><em>4.</em>免费试用无需支付邮费，确认您的收货信息正确
    即可。</li>
				<li><em>5.</em>活动解释权最终归婕珞芙官网所有。</li>
			</ol>
		</div>

		<div class="ym-tab-box" style="display:none">
			<span class="ym-small-title">图文描述</span>
			<p style="padding: 0.5rem 0px 0rem 0px;">
			<?php echo $output['goods']['goods_body']; ?>
			</p>
		</div>
	<script>
	$(document).ready(function(){
		$("#az_applay").click(function () {
			alert("<?php echo $lang['az_active_lang']?>");
		})
		$(".ym-apply-tab li").on("click",function(){
			var index=$(this).index();
			$(".ym-apply-tab li").removeClass("on");
			$(this).addClass("on");
			$(".ym-tab-box").hide();
			$(".ym-tab-box").eq(index).show();
		})
	});
	</script>
	</body>

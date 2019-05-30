<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>订单列表</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />

<style>
#order_<?php echo $_GET["state_type"]?>{color: #BBA059;
border-bottom: 2px solid #BBA059;
line-height: 47px;}
</style>
</head>

<body ontouchstart class="bg_gray">
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <a href="index.php?<?php if($_GET['is_share']==1): ?>act=member_share<?php else:?>act=member&amp;op=home<?php endif;?>">
	<i class="ui-icon-return"  ></i>
	</a>

    <h1 class="qz-color">
    <?php echo $_GET['state_type']=='state_new'?'待付款':''; ?>
	<?php echo $_GET['state_type']=='state_pay'?'待发货':''; ?>
	<?php echo $_GET['state_type']=='state_send'?'待收货':''; ?>
	<?php echo $_GET['state_type']=='state_success'?'已完成':''; ?>
	<?php echo $_GET['state_type']=='state_noeval'?'待评价':''; ?>
	<?php echo $_GET['state_type']=='state_cancel'?'已取消':''; ?>
	订单
	</h1>
	 <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>


<!--订单管理-->
<div class="order_mans">
	<!--导航-->
	<!-- <ul class="order_nav">
    	<li>
        	<a href="index.php?act=member_order"  id="order_">全部</a>
        </li>
    	<li>
            <a href="index.php?act=member_order&state_type=state_new" id="order_state_new"><span>待付款</span></a>
        </li>
    	<li>
			<a href="index.php?act=member_order&state_type=state_pay"  id="order_state_pay"><span>待发货</span></a>
        </li>
    	<li>
            <a href="index.php?act=member_order&state_type=state_send" id="order_state_send"><span>待收货</span></a>
        </li>
    	<li>
            <a href="index.php?act=member_order&state_type=state_noeval" id="order_state_noeval"><span>待评价</span></a>
        </li>
        <div class="clear"></div>
    </ul> -->
    <!--未付款-->
    <div class="order_states_list">
    	<!--还没有任何订单-->
        <div  class="no_order" style="display:none">
            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/wdd_03.png" />
            <p>您没有任何相关的订单</p>
        </div>
        <!--有订单-订单列表-->
    </div>
</div>
<script>
//点击导航 切换选中效果
$(".order_nav li a").on("click",function(){
	$(this).addClass("sel").parent().siblings("li").find("a").removeClass("sel");
	})
</script>

<section>
    <div class="qz-dd-list">
    <?php if ($output['order_group_list']) { ?>
	    <!--foreach one STA-->
        <?php foreach ($output['order_group_list'] as $order_pay_sn => $group_info) { ?>
        <?php $p = 0;?>
        <!--foreach two STA-->
        <?php foreach($group_info['order_list'] as $order_id => $order_info) {?>
        <dl class="qz-bottom-b">
            <div class="qz-padding qz-top-b qz-bottom-b">
			<span class="qz-fl">
				订单编号:<?php echo $order_info['order_sn']; ?>
				</span>
                <span class="qz-fr">
				    <p class="qz-fl">
					  <?php echo $order_info['state_desc']; ?>
		              <?php echo $order_info['evaluation_status'] ? $lang['member_order_evaluated'] : '';?>
					</p>
				</span>
			<div class="qz-bk5"></div>
            </div>
            <div class="qz-bk5"></div>
            <div class="qz-padding qz-padding-b qz-background-white clearfix">
               <?php if($output['is_share'] == 1):?>
				<p>下级名称:
				<a href="javascript:;">
				<?php echo $order_info['buyer_name']; ?>(<?php echo $order_info['buyer_id']; ?>)
				</a>
				</p>
         <?php endif;?>
                <span class="qz-fl qz-color8">下单时间:<?php echo date("Y-m-d H:i:s",$order_info['add_time']); ?></span>
                <?php if($output['is_share'] == 0):?>
                <span class="qz-fr">
				<a href="index.php?act=member_order&op=show_order&order_id=<?php echo $order_info['order_id']; ?>" >
				订单详情
				</a>
				</span>
         <?php endif; ?>
            </div>
		   <!--foreach three STA -->
		   <?php
		   if(!empty($order_info['goods_list'])){

		   foreach ($order_info['goods_list'] as $k => $goods_info) {?>
            <ul class="ui-list">
                <li class="ui-border-t">
                    <div class="ui-list-thumb qz-list-thumb" style="width:70px;">
					    <a href="<?php echo $goods_info['goods_url'];?>" >
						<img src="<?php echo $goods_info['image_60_url'];?>" class="qz-img-block"/>
						</a>
                    </div>
                    <div class="ui-list-info qz-light3">
                        <h4 class="ui-nowrap">
						<a href="<?php echo $goods_info['goods_url'];?>" >
						<?php echo $goods_info['goods_name']; ?>
						</a>
						</h4>
                        <p class="ui-nowrap">￥<font class="qz-color2"><?php echo $goods_info['goods_price'];?></font></p>
                        <p>商品数目：<?php echo $goods_info['goods_num']; ?></p>
                    </div>
                </li>
            </ul>
		   <?php }    }?>
            <!--foreach three END -->
            <div class="qz-padding qz-padding-t qz-background-white">
                <div class="qz-top-b clearfix">
                    <div class="qz-bk10"></div>
				    <?php if (($order_info['goods_count'] > 1 && $k ==0) || ($order_info['goods_count'] == 1)){?>
				    <span class="qz-fl">运费：</span>
                    <span class="qz-fr">
					<?php if ($order_info['shipping_fee'] > 0){?>
					<?php echo $order_info['shipping_fee'];?>
					<?php }else{?>
                    <?php     echo $lang['nc_common_shipping_free'];?>
                    <?php    }?>
					</span>
					<?php } ?>
                    <div class="qz-bk5"></div>
                    <span class="qz-fl">合计</span>
                    <span class="qz-fr"><?php echo $order_info['order_amount']; ?></span>
                    <div class="qz-bk5"></div>
					<!--
                    <p class="qz-fl">
					  <?php echo $order_info['state_desc']; ?>
		              <?php echo $order_info['evaluation_status'] ? $lang['member_order_evaluated'] : '';?>
					</p
					-->
               <?php if($output['is_share'] == 1): ?>
                    <p class="qz-fr">
					    <!--取消订单-->
					    <?php if ($order_info['if_cancel']) { ?>
						<a href="index.php?act=member_order&op=change_state&state_type=order_cancel&order_id=<?php echo $order_info['order_id']; ?>" >
                        <input type="button" value="取消订单" class="ui-btn qz-padding-10 qz-background-gray qz-color4" />
					   <?php } ?>
					   <!--支付订单-->
					   <?php if (!empty($group_info['pay_amount']) && $p == 0) {?>
					   <a href="index.php?act=buy&op=pay&pay_sn=<?php echo $order_pay_sn; ?>" >
				    	<input type="button" value="支付订单" class="ui-btn qz-padding-10 qz-background-yellow qz-margin-l10 qz-color7" />
                       </a>
					   <?php }?>

					   <!--我要评价-->
						<?php if ($_GET['state_type']=='state_noeval'){ ?>
						<a href='index.php?act=member_evaluate&op=add&order_id=<?php echo $order_info['order_id']; ?>' >
						<input type="button" value="评价" class="ui-btn qz-padding-10 ui-btn-primary" />
						</a>
						<?php } ?>

						<!--查看物流-->
						<?php if ($order_info['if_deliver']){ ?>
						<a href='index.php?act=member_order&op=search_deliver&order_id=<?php echo $order_info['order_id']; ?>&order_sn=<?php echo $order_info['order_sn']; ?>' >
						<input type="button" value="<?php echo $lang['member_order_show_deliver']?>" class="ui-btn qz-padding-10 ui-btn-primary" />
						</a>
						<?php } ?>
						<!--确认收货-->
						<?php if ($order_info['if_receive']) { ?>
						<a href="index.php?act=member_order&op=change_state&state_type=order_receive&order_sn=<?php echo $order_info['order_sn']; ?>&order_id=<?php echo $order_info['order_id']; ?>" />
						<input type="button" value="<?php echo $lang['member_order_ensure_order'];?>" class="ui-btn qz-padding-10 qz-background-yellow qz-margin-l10 qz-color7" />
				        </a>
					   <?php } ?>
				  </p>
           <?php endif; ?>
                </div>
            </div>

        </dl>
		<!--foreach two END-->
		<?php } ?>
		<!--foreach one END-->
	    <?php } ?>
    <?php }else{ ?>
	    <div class="qz-bottom-b no_center">
		 您没有任何相关的订单！
		</div>
	<?php } ?>
    </div>
</section>


<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
    $(".qz-number .qz-ico-plus").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        num_total = parseInt($(this).parent().children(".num").attr("total"));
        if (num_val < num_total) {
            num_val = num_val + 1;
        } else {
            num_val = num_total;
            alert("已超过最大库存！");
        }
	   $(this).parent().children(".num").val(num_val);
    });

    $(".qz-number .qz-ico-reduction").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        if (num_val<=0) {
            num_val = 0;
        } else {
            num_val = num_val - 1;
        }
        $(this).parent().children(".num").val(num_val);
    });

    $(".qz-tcxz span").click(function(){
        $(this).parent().find("span").removeClass("ui-btn-primary");
        $(this).addClass("ui-btn-primary");
        loc_num = $(this).attr("value");
        $(this).parent().children(".type").val(loc_num);
    });
</script>
</body>
</html>

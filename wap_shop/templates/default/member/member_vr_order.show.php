<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>虚拟订单信息</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <a href="index.php?act=member_vr_order&state_type=<?php echo $output['order_info']['order_state'];?>">
	<i class="ui-icon-return"></i>
	</a>
    <h1 class="qz-color">虚拟订单信息</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">接收手机:<?php echo $output['order_info']['buyer_phone'];?></p>
	<?php if (!empty($output['order_info']['extend_vr_order_code'])){ ?>
         <?php foreach($output['order_info']['extend_vr_order_code'] as $code_info){ ?>
            <p class="text">电子兑换码：
        <!--<img src="<?php echo WAP_SHOP_SITE_URL;?>/html/image.php?code=code11&o=1&t=30&r=1&text=<?php echo $code_info['vr_code'];?>&f1=Arial.ttf&f2=8&a1=&a2=&a3=">-->
            <?php echo $code_info['vr_code'];?>(<?php echo $code_info['vr_code_desc'];?>)</p>
         <?php } ?>
	<?php } ?>
</div>

<div class="refund_dec">
    <div class="refund_dec_tt">店铺名称：<?php echo $output['order_info']['store_name'];?></div>
    <div class="refund_dec_num">
        <p>虚拟单号：<?php echo $output['order_info']['order_sn'];?></p>
        <div class="arrow" ></div>
    </div>
    <div class="refund_dec_main" style="display:none">
        <p class="text">支付方式：<?php echo orderPaymentName($output['order_info']['payment_code']);?></p>
        <p class="text">下单时间：<?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']);?></p>
		<?php if(!empty($output['order_info']['payment_time'])){?>
        <p class="text">付款时间：<?php echo date("Y-m-d H:i:s",$output['order_info']['payment_time']);?></p>
        <?php } ?>
    </div>
</div>

<div class="business">

    <div class="refund_tp">
	 <?php if ($output['order_info']['order_state'] == ORDER_STATE_CANCEL){ ?>
        <span class="fl">交易关闭</span>
	 <?php } elseif ($output['order_info']['order_state'] == ORDER_STATE_NEW){ ?>
        <span class="fl">订单已生成，待付款&nbsp&nbsp
		<a href="index.php?act=buy_virtual&op=pay&order_id=<?php echo $output['order_info']['order_id']; ?>" >支付订单</a>
		</span>
	<?php } elseif ($output['order_info']['order_state'] == ORDER_STATE_PAY){ ?>
	    <span class="fl">已付款，电子兑换码已发放</span>
	<?php } elseif ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS){ ?>
	    <span class="fl">订单已完成。</span>
	<?php }?>
	<?php if ($output['order_info']['if_share']) { ?>
        <a href="javascript:void(0)" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['order_info']['goods_id'];?>"}'><div class="refund_icon" style="display:none;"></div></a>
		<?php } ?>
    </div>

    <div class="white-warp">
        <!-- 列表 -->
        <div class="business_wrap" style="clear: both">
            <div class="refund_text">
                <p class="imgBox">
				<a href="index.php?act=goods&op=index&goods_id=<?php echo $output['order_info']['goods_id'];?>" target="_blank" title="<?php echo $output['order_info']['goods_name'];?>">
				<span class="imgSpan" style="background: url(<?php echo thumb($output['order_info'], 240);?>) no-repeat center; background-size:contain; border: none">
				</span>
                </a>
				</p>
                <div class="text">
                    <h3>
					<a href="index.php?act=goods&op=index&goods_id=<?php echo $output['order_info']['goods_id'];?>" target="_blank" title="<?php echo $output['order_info']['goods_name'];?>">
					<?php echo $output['order_info']['goods_name'];?>
					</a>
					</h3>

                    <p class="red_money">￥<?php echo $output['order_info']['goods_price'];?></p>
                    <p>商品数目:<?php echo $output['order_info']['goods_num'];?></p>
                </div>
                <ul class="mth">
                  <!--  测试说先去掉退款按钮<?php /*if($output['order_info']['if_refund']){ */?>
				   <li>
                   <a href="index.php?act=member_vr_refund&op=add_refund&order_id=<?php /*echo $output['order_info']['order_id']; */?>" class="ui-btn-lg ui-btn-primary" style="color:#fff;height:28px;line-height:28px;width:60px">退款</a>
                   </li>
				   --><?php /*} */?>
                </ul>
            </div>
        </div>
        <div class="business_user_mode">
		    <?php if ($output['order_info']['if_cancel']){ ?>
            <a href="index.php?act=member_vr_order&op=change_state&state_type=order_cancel&order_id=<?php echo $output['order_info']['order_id'];?>" >
			<input class="subbtn" type="button" value="取消订单" />
			</a>
            <?php }?>
		     <!-- 评价 -->
            <?php if ($output['order_info']['if_evaluation']) { ?>
            <a href="index.php?act=member_evaluate&op=add_vr&order_id=<?php echo $output['order_info']['order_id']; ?>">
			<input class="subbtn" type="button" value="<?php echo $lang['member_order_want_evaluate'];?>" />
			</a>
            <?php } ?>
            <div class="mode_right">
			 <?php if($output['order_info']['rcb_amount']>0){ ?>
             <p>已支付：<?php echo $output['order_info']['rcb_amount']; ?>元</p>
             <?php } ?>
			 <?php if($output['order_info']['pd_amount']>0){ ?>
              <p>预存款已支付：<?php echo $output['order_info']['pd_amount']; ?>元</p>
             <?php } ?>
             <p>订单应付金额：<?php echo $output['order_info']['order_amount'];?>元</p>
            </div>
        </div>
    </div>
</div>
<div class="service_subBtn">
<a href="index.php?act=member_vr_order&state_type=<?php echo $output['order_info']['order_state'];?>">
    <input type="button" class="public_btn2" value="返回" >
</a>
</div>
<script>
$(function(){
    $('.refund_dec .refund_dec_num .arrow').click(function(){
        var showBlock=$('.refund_dec_main');
        if(showBlock.is(":hidden")){
            showBlock.slideDown();
            $('.refund_dec .refund_dec_num .arrow').css({
                '-webkit-transform':'-webkit-rotate(180deg)',
                '-moz-transform':'-moz-rotate(180deg)',
                '-ms-transform':'-ms-rotate(180deg)',
                '-webkit-transform':'-webkit-rotate(180deg)',
                'transform':'rotate(180deg)'
            })
        }else{
            showBlock.slideUp();
            $('.refund_dec .refund_dec_num .arrow').css({
                '-webkit-transform':'-webkit-rotate(0deg)',
                '-moz-transform':'-moz-rotate(0deg)',
                '-ms-transform':'-ms-rotate(0deg)',
                '-webkit-transform':'-webkit-rotate(0deg)',
                'transform':'rotate(0deg)'
            })
        }
    });
});
</script>
<style>
body{ background:#f5f5f5;}
.refund_text .imgBox .imgSpan{width: 80px;height: 80px;display: block;}
</style>
</body>
</html>













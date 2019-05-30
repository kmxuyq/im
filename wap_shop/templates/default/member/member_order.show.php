<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>订单详情
<?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) { echo "待付款";}
if ($output['order_info']['order_state'] == ORDER_STATE_PAY) { echo "待发货";}
if ($output['order_info']['order_state'] == ORDER_STATE_SEND) { echo "待收货";} 
if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 1) {  echo "已完成";}
if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 0) {  echo "待评价";}?>
</title>

    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
    <style type="text/css">
.orderbtn {float:right;}
.orderbtn a {
    font: normal 12px/20px arial;
    color: #777;
    background-color: #F5F5F5;
    text-align: center;
    vertical-align: middle;
    display: inline-block;
    height: 20px;
    padding: 0 10px;
    margin-right: 2px;
    border-style: solid;
    border-width: 1px;
    border-color: #DCDCDC #DCDCDC #B3B3B3 #DCDCDC;
    cursor: pointer;
}
    </style>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>
<header class="az-header az-header-positive qz-header-background qz-header-bb noProsition">
   <a href="index.php?act=member_order&state_type=<?php echo $output['order_info']['order_state'];?>">
    <i class="az-icon-return"></i>
   </a>
    <h1 class="az-color">订单详情<?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) { echo "待付款";}
if ($output['order_info']['order_state'] == ORDER_STATE_PAY) { echo "待发货";}
if ($output['order_info']['order_state'] == ORDER_STATE_SEND) { echo "待收货";} 
if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 1) {  echo "已完成";}
if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 0) {  echo "待评价";}?></h1>
</header>
<body class="bg_gray">
<!--订单详情待付款-->
<div class="confirmation_order order_mans order_dels">
	<!--订单提示-->
	<?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) {?>
	<div class="notice" style="display: none;">您尚未付款，订单将在<span></span>后自动取消</div>
	<?php }?>
    <!--收货地址-->
	<div class="sure_receiving_address">
    	<a href="javascript:;" class="address_selcted">
        	<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/mrdz_03.png" />
            <div class="people">
                <span><?php echo $output['order_info']['extend_order_common']['reciver_name'];?></span>
                <span class="fr"><?php echo @$output['order_info']['extend_order_common']['reciver_info']['phone'];?></span>
                <div class="clear"></div>
            </div>
            <div class="add"><?php echo @$output['order_info']['extend_order_common']['reciver_info']['address'];?></div>
        </a>
    </div>
<!--最新物流信息-->
<?php if ($output['order_info']['order_state'] != ORDER_STATE_PAY && $output['order_info']['order_state'] != ORDER_STATE_NEW){?>
<?php if (!empty($output['order_info']['shipping_code'])) { ?>
	<div class="sure_receiving_address new_logistics"  id="x_az" style="border-top:none">
	<a href="javascript:;" class="address_selcted" style="padding-bottom:0">
        	<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/car_03.png" />
            <span class="sj"></span>
            <div class="add add2"><?php echo $lang['member_show_seller_has_send'];?></div>
            <div class="people">
                <span class="times"><?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?></span>
                <span class="times"><?php echo $output['order_info']['express_info']["e_name"].'：'.$output['order_info']['shipping_code']?></span><div class="clear"></div>
            </div>
        </a>
    </div>
    
    <div class="sure_receiving_address new_logistics"  id="az" style="border-top:none;display: none;"></div>
<?php }?>
 <?php } ?>
    <!--订单-->
    <div class="order_states_list">
        <ul>
            <li>
                <div class="top">
                    <div class="order_num">订单号：<span><?php echo $output['order_info']['order_sn']; ?></span></div>
                </div>
                <div class="middle_wrap">
                <?php $i = 0;?>
				<?php foreach($output['order_info']['goods_list'] as $k => $goods) {?>
        		<?php $i++;?>
                    <div class="middle">
                            <div class="order_goods">
                                <div class="goods_pic">
                                    <div class="self_width">
                                        <a href="<?php echo $goods['goods_url']; ?>">
                                            <img src="<?php echo $goods['image_60_url']; ?>" alt="">
                                        </a>
                                    </div>
                                </div>
                                <div class="goods_dis">
                                    <div class="title">
                                        <a href="<?php echo $goods['goods_url']; ?>">
                                            <?php echo $goods['goods_name']; ?>
                                        </a>
                                    </div>
                                    <div class="goods_fun"><?php echo $goods['goods_jingle']; ?></div>
                                    <div class="goods_price">
                                        <div class="present_price"><span>&yen;</span> <?php echo $goods['goods_price']; ?></div>
                                        <!-- 退款 -->
                                        <?php if ($goods['refund'] == 1){?>
                                        <span class="orderbtn">
                                        <a href="index.php?act=member_refund&op=add_refund&order_id=<?php echo $output['order_info']['order_id']; ?>&goods_id=<?php echo $goods['rec_id']; ?>">退款/退货</a>
                                        </span>
                                        <?php }?>

                                        <!-- 投诉 -->
                                        <?php if ($output['order_info']['if_complain']){ ?>
                                        <span class="orderbtn">
                                        <a href="index.php?act=member_complain&op=complain_new&order_id=<?php echo $output['order_info']['order_id']; ?>&goods_id=<?php echo $goods['rec_id']; ?>">交易投诉</a>
                                        </span>
                                        <?php } ?>
                                        <span class="goods_num">x<?php echo $goods['goods_num']; ?></span>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    <?php } ?>
                </div>
                <?php if ($output['order_info']['order_state'] != ORDER_STATE_PAY) { ?>


                <?php if(!empty($output['order_info']['extend_order_common']['promotion_info'])
                || !empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
                <div class="bottom activejs">
                
                    <span class="fl"><?php echo $output['order_info']['extend_order_common']['promotion_info']?></span>
                    
                    <?php if(!empty($output['order_info']['extend_order_common']['promotion_info'])){ ?>
                    <span class="fl"><?php echo $output['order_info']['extend_order_common']['promotion_info'];?></span>
                    <?php } ?>
                    <?php if(!empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
                          <span class="fr">优惠券：<?php echo $output['order_info']['extend_order_common']['voucher_price'];?>元</span>
                    <?php } ?>

                    
                </div>
                <?php } ?>
                
                <div class="bottom">
                    <div class="price">总共<span class="ico"> &yen; </span><span><?php echo $output['order_info']['order_amount']; ?></span></div>
                    <span class="stas">共<?php echo $goods['goods_num']; ?>件商品</span>
                </div>
                <?php }?>
                
                <?php if (!($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 1)){?>
                <div class="bottom_btnr">
            <?php if ($output['order_info']['if_lock']) { ?>
            <p>退款退货中</p>
            <?php } ?>
            
            <!-- 取消订单 -->
            
            <?php if ($output['order_info']['if_cancel']) { ?>
            <a class="btn_right" href="index.php?act=member_order&op=change_state&state_type=order_cancel&order_id=<?php echo $output['order_info']['order_id']; ?>"><?php echo $lang['member_order_cancel_order'];?></a>
            <?php } ?>

            <!-- 退款取消订单 -->
            
            <?php if ($output['order_info']['if_refund_cancel']){ ?>
            <a class="btn_right" href="index.php?act=member_refund&op=add_refund_all&order_id=<?php echo $output['order_info']['order_id']; ?>" class="ncm-btn">订单退款</a>
            <?php } ?>
            
            <!-- 收货 -->
            
            <?php if ($output['order_info']['if_receive']) { ?>
            <a class="btn_right" href="index.php?act=member_order&op=change_state&state_type=order_receive&order_sn=<?php echo $output['order_info']['order_sn']; ?>&order_id=<?php echo $output['order_info']['order_id']; ?>"><?php echo $lang['member_order_ensure_order'];?></a>
            <?php } ?>
            
            <!-- 评价 -->
            
            <?php if ($output['order_info']['if_evaluation']) { ?>
            <a class="btn_right" href="index.php?act=member_evaluate&op=add&order_id=<?php echo $output['order_info']['order_id']; ?>"><?php echo $lang['member_order_want_evaluate'];?></a>
            <?php } ?>
            
            <!-- 已经评价 -->
            
            <?php if ($output['order_info']['evaluation_state'] == 1) { echo $lang['order_state_eval'];} ?>
            </div>
          <?php } ?>
	     	
	       <!-- 退款取消订单 -->
            </li>
        </ul>
    </div>
    <!--订单号、下单时间、订单状态-->
    <div class="order_dels_msg">
    	<p>订单号：<?php echo $output['order_info']['order_sn']; ?></p>
    	<p><?php echo $lang['member_order_time'].$lang['nc_colon'];?>
		<?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></p>
    	<p>订单状态：<span>
    	<?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) { echo "待付款";}
            if ($output['order_info']['order_state'] == ORDER_STATE_PAY) { echo "待发货";}
            if ($output['order_info']['order_state'] == ORDER_STATE_SEND) { echo "待收货";} 
            if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 1) {  echo "已完成";}
            if ($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 0) {  echo "待评价";}
            if($output['order_info']['order_state'] == ORDER_STATE_CANCEL){echo "已取消";}?>
		</span></p>
        <div class="leave_msg">
            <div class="title">给卖家留言：</div>
            <p><?php echo $output['order_info']['extend_order_common']['order_message']?></p>
        </div>
    </div>
<div class="bottom_btnt">
<?php if ($output['order_info']['order_state'] == ORDER_STATE_NEW) {?>
<a href="index.php?act=buy&op=pay&pay_sn=<?php echo $output['order_info']['pay_sn'];?>">去付款</a>
<?php } if($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 0){?>
<a href="index.php?act=member_evaluate&op=add&order_id=<?php echo $output['order_info']['order_id']; ?>">去评价</a>
<?php } if($output['order_info']['order_state'] == ORDER_STATE_SEND){?>
<a href="index.php?act=member_order&op=change_state&state_type=order_receive&order_sn=<?php echo $output['order_info']['order_sn']; ?>&order_id=<?php echo $output['order_info']['order_id']; ?>">确认收货</a>
<?php } if($output['order_info']['order_state'] == ORDER_STATE_SUCCESS && $output['order_info']['evaluation_state'] == 1){?>
<a href="index.php?act=member_order&op=change_state&state_type=order_delete&order_id=<?php echo $output['order_info']['order_id']; ?>">删除</a>
<?php }?>
</div>
</div>
<!-- <script src="http://127.0.0.1/data/resource/js/jquery.js"></script> -->
<script type="text/javascript">
$(function(){
	$('#x_az').click(function(){
		$('#x_az').hide();
		$('#az').show();
		get_az_html();
	});
	$('#az').click(function(){
		$('#az').hide();
		$('#x_az').show();
	});
	
	function get_az_html(){
		az_send='<a href="javascript:;" class="address_selcted" style="padding-bottom:0">'
			 +'<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/car_03.png" />'
			 +'<div class="add add2">'+"<?php echo $lang['member_show_seller_has_send'];?>"+'</div>'
		            +'<div class="people">'
		               + '<span class="times">'+"<?php echo date("Y-m-d H:i:s",$output['order_info']['extend_order_common']['shipping_time']); ?>"+'</span>'
		                +'<div class="clear"></div>'
		           + '</div></a>';
		//url='index.php?act=member_order&op=get_express&e_code=<?php echo $output['order_info']['express_info']['e_code']?>&shipping_code=<?php echo $output['order_info']['shipping_code']?>&t=<?php echo random(7);?>';
		url='<?php echo $output["kaid100_url"]?>';
		$.getJSON(url,function(data){
			if(data){
				var az_html='';
				for(i in data){
					 az_html +='<a href="javascript:;" class="address_selcted" style="padding-bottom:0">'
						 +'<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/car_03.png" />'
						 +'<div class="add add2" id="has_send">'+data[i].context+'</div>'
						+ '<div class="people"><span class="times" id="shipping_time">'+ data[i].time +'</span>'
						+ '<div class="clear"></div></div></a>';
					}
				$('#az').html(az_send+az_html);
				}
		});
	}
});
</script>
<style>
    .bottom_btnt{position: static;}
</style>














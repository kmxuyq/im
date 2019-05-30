<!doctype html>
<html>
<head>
    <title>订单中心</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="高铁"/>
    <meta name="author" content="技术中心"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
</head>
<body class="gt_newpage_body">
<div class="train_position_top">
    <div class="arrow" onclick="history.back()"></div>
    <div class="text">
        <?php echo $_GET['state_type']=='state_new'?'待付款':''; ?>
        <?php echo $_GET['state_type']=='state_pay'?'待发货':''; ?>
        <?php echo $_GET['state_type']=='state_send'?'待收货':''; ?>
        <?php echo $_GET['state_type']=='state_success'?'已完成':''; ?>
        <?php echo $_GET['state_type']=='state_noeval'?'待评价':''; ?>
        <?php echo $_GET['state_type']=='state_cancel'?'已取消':''; ?>
        订单
    </div>
</div>
<div class="gt_list_order">
    <?php if(!empty($output['order_list'])){ ?>
        <?php foreach($output['order_list'] as $order_info) { ?>
        <div class="item2">
            <div class="img_wrap" <?php if($outpu['is_share'] != 1): ?> onclick="location.href='index.php?act=member_vr_order&op=show_order&order_id=<?php echo $order_info['order_id'];?>'"<?php endif; ?> style="background: url('<?php echo thumb($order_info, 240);?>') no-repeat center; background-size:contain; border:none" >
                <!--<img src="">-->
            </div>
            <div class="text" <?php if($outpu['is_share'] != 1): ?> onclick="location.href='index.php?act=member_vr_order&op=show_order&order_id=<?php echo $order_info['order_id'];?>'" <?php endif; ?>>
                <div class="tt"><?php echo $order_info['goods_name'];?></div>
                <?php if(isset($order_info['order_station_type'])){
                    switch (intval($order_info['order_station_type'])) {
                       /* case 1:
                            echo '<div class="time">预定时间：'.$order_info['ticket_date'].'</div>';
                            break;*/
                       /* case 4:
                            echo '<div class="time">发车时间：'.$order_info['ticket_date'].'</div>';
                            break;*/
                        default:
                            echo '<div class="time">下单时间：'.date("Y-m-d H:i",$order_info['add_time']).'</div>';
                    }

                }?>
                <div class="number">数量：<?php echo $order_info['goods_num'];?></div>
            </div>
            <div class="order_detail">
                <div class="order_number">
                    <p class="num">订单编号 <?php echo $order_info['order_sn'];?></p>
                    <span class="order_pice">
                        <i>¥</i><?php echo $order_info['order_amount'];?>
                    </span>
                </div>
                <?php if($outpu['is_share'] == 1): ?>
                   <div class="order_number">
                       <p class="num">购买人： <?php echo $order_info['buyer_name'];?>(<?php echo $order_info['buyer_id']; ?>)</p>
                       <span class="order_pice">
                           <i>¥</i><?php echo $order_info['order_amount'];?>
                       </span>
                   </div>
                <?php endif;?>
                <div class="order_status">
                    <p class="lt">状态:<?php echo $order_info['order_state_text'];?></p>
                     <?php if($outpu['is_share'] != 1): ?>
                    <div class="pay_btn">
            <?php if($order_info['if_cancel']) {?><!--去取消 -->
                    <button onclick="location.href='index.php?act=member_vr_order&op=change_state&state_type=order_cancel&order_id=<?php echo $order_info['order_id']?>'" class="btn1">取消</button>
                <?php if($order_info['if_pay']){ ?><!--去支付 -->
                    <button onclick="location.href='index.php?act=buy_virtual&op=pay&order_id=<?php echo $order_info['order_id']?>'" class="btn2">付款</button>
                <?php } ?>
            <?php } ?><?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php }else{ ?>
            <div class="no_order">暂无订单</div>
    <?php } ?>
</div>

</body>
</html>

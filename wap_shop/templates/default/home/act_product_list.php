<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>精彩活动列表</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<header class="az-header az-header-positive az-header-background az-header-bb noProsition">
   <a href="index.php?act=member_order&state_type=<?php echo $output['order_info']['order_state'];?>">
    <i class="az-icon-return"></i>
   </a>
    <h1 class="qz-color">精彩活动列表</h1>
</header>

<body class="bg_gray">
<!--精彩活动列表-->
<div class="cool_active" style="padding-top: 47px;">
	<div class="order_states_list">
        <!--有订单-订单列表-->
    	<ul>
    	<?php foreach($output['goods_list'] as $value){?>
        	<li>
                <div class="middle_wrap">
                    <div class="middle">
                        <a href="<?php echo urlShopWAP('goods','index',array('type'=>'act_goods','wx'=>$_GET['wx'],'goods_id'=>$value['goods_id']));?>">
                            <div class="order_goods">
                                <div class="goods_pic">
                                <div class="self_width">
                                <img src="<?php echo thumb($value, 20);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" />
								</div>
								</div>
                                <div class="goods_dis">
                                    <div class="title">
                                    <!-- <a href="<?php echo urlShopWAP('goods','index',array('type'=>'act_goods','wx'=>$_GET['wx'],'goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>">
                                    <?php echo $value['goods_name'];?></a> --><?php echo $value['goods_name'];?></div>
                                    <div class="goods_fun"><?php echo $value['goods_jingle'];?></div>
                                    <div class="active_time">活动时间：2015-12-12</div>
                                </div>
                            </div>
                            <div class="icon"></div>
                        </a>
                    </div>
                </div>
            </li>
       <?php };?>
        </ul>
    </div>
</div>
<!--公共底部导航-->
<div class="bottomHeight"></div>
<footer class="footer">
	<ul>
    	<li class="border_right"><a href="/wap" class="selected"><span class="sy"></span><br />首页</a></li>
    	<li class="border_left_right"><a href="/wap_shop/index.php?act=member&op=home"><span class="wd"></span><br />个人中心</a></li>
    	<li class="border_left_right"><a href="/wap_shop/index.php?act=cart"><span class="kf"></span><br />购物车</a></li>
    	<li class="border_left"><a href="/wap_shop/index.php?act=member_order"><span class="hd"></span><br />订单</a></li>
    </ul>
</footer>
<!--返回头部-->
<a href="javascript:;" class="back_to_top gf_back_to_top has_bottom"><img src="images/backtop.png" /></a>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>我的积分</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">
<!--我的积分-->
<div class="my_points_gf">
    <div class="scores_wrap">
        <div class="scores">
            <div class="pig"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/zzz_03.png" /></div>
            <span class="score"><?php echo $output['member_info']['member_points']; ?></span>
            <span>积分</span>
        </div>
    </div>
    <div class="navs">
      <ul>
          <li><a href="index.php?act=member_points">积分明细</a></li>
          <li><a href="index.php?act=member_pointorder" class="sel">兑换记录</a></li>
        </ul>
    </div>
    <div class="points_list points_list1">
      <div class="order_states_list">
          <?php if(count($output['order_list'])>0){ ?>
            <ul>  
              <?php foreach ($output['order_list'] as $val) { ?>
              <?php foreach((array)$val['prodlist'] as $k=>$v) {?>
                <li>
                    <div class="middle_wrap">
                        <div class="middle">
                            <a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $v['point_goodsid']));?>" target="_blank">
                                <div class="order_goods">
                                    <div class="goods_pic"><div class="self_width"><img src="<?php echo $v['point_goodsimage_small']; ?>" /></div></div>
                                    <div class="goods_buy_points">&ndash;<?php echo $v['point_goodspoints']; ?></div>
                                    <div class="goods_dis">
                                        <div class="title"><?php echo $v['point_goodsname']; ?></div>
                                        <div class="goods_fun"><?php echo $v['point_goodsname']; ?></div>
                                        <div class="points_buy_time"><?php echo @date("Y-m-d H:i:s",$val['point_addtime']); ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>
                   </div>
                </li>
              <?php } ?>
              <?php } ?>
            </ul>
          <?php } else { ?>
            <span><?php echo $lang['no_record'];?></span>
          <?php } ?>            
      </div>
    </div>
</div>
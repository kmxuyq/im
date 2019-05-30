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
          <li><a href="index.php?act=member_points" class="sel">积分明细</a></li>
          <li><a href="index.php?act=member_pointorder">兑换记录</a></li>
        </ul>
    </div>
    <div class="points_list points_list1">
      <!--积分明细-->
        <?php  if (count($output['list_log'])>0) { ?>
        <ul class="points_dels">
        <?php foreach($output['list_log'] as $val) { ?>
            <li>
                <a href="javascript:;">
                    <span><?php echo $val['pl_desc'];?></span>
                    <span><?php echo @date('Y-m-d H:i:s',$val['pl_addtime']);?></span>
                    <span><?php echo ($val['pl_points'] > 0 ? '+' : '').$val['pl_points']; ?></span>
                </a>
            </li>
        <?php } ?>
        </ul>
        <?php } else { ?>
            <span><?php echo $lang['no_record']; ?></span>
        <?php } ?>
    </div>
</div>

</body>
</html>

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
<title>积分商城首页</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/layer-v2.1/layer/layer.js"></script>
<style type="text/css">
    .integral_mall .integ_mallpro .mallpic .mall_right a {
        color:white;
    }
	.mallproduct_tit a {
        color:white;
    }
</style>
</head>

<body class="bg_gray">

<!--积分首页导航-->
<div class="integral_mall">
    <div class="search_results">
        <div class="search_results_nav_wrap fixed">
            <ul class="search_results_nav">
                <?php
                    $choice_class['default'] = '';
                    $choice_class['pointsdesc'] = '';
                    $choice_class['exchangenumdesc'] = '';

                    if($_GET['orderby']=='pointsdesc') {
                        $choice_class['pointsdesc'] = ' class="choice"';
                    } elseif($_GET['orderby']=='exchangenumdesc') {
                        $choice_class['exchangenumdesc'] = ' class="choice"';
                    } else {
                        $choice_class['default'] = ' class="choice"';
                    }
                ?>
                <li><a href="index.php?act=pointvoucher&op=index&orderby=default"<?php echo $choice_class['default']; ?>>全部</a></li>
                <li><a href="index.php?act=pointvoucher&op=index&orderby=pointsdesc"<?php echo $choice_class['pointsdesc']; ?>>积分值</a></li>
                <li><a href="index.php?act=pointvoucher&op=index&orderby=exchangenumdesc"<?php echo $choice_class['exchangenumdesc']; ?>>兑换量</a></li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>

    <div class="integ_mallpro">
    <?php if (is_array($output['voucherlist']) && count($output['voucherlist'])){?>
        <?php foreach ($output['voucherlist'] as $v){?>
        <div class="mallpro">
            <div class="mallproduct">
                <img src="<?php echo $v['voucher_t_customimg'] ?>">
                <div class="mallproduct_tit">
                <a>
                    购物满<?php echo $v['voucher_t_limit'];?>元可用
                </a>
                </div>
            </div>

            <div class="mallpic">
                <div class="mall_left">
                     <span class="mlpic01"></span>
                     <span class="mlpic02"><em><?php echo $v['voucher_t_points'];?></em></span>
                </div>
                <div class="mall_right">
                <?php if($_SESSION['is_login']){?>
                     <a  href="#" nc_type="exchangebtn" data-param='<?php echo $v['voucher_t_id'];?>' class="ncp-btn ncp-btn-red">我要兑换</a>
                     <?php }else {?>
                     <a  href="<?php echo urlShopWap('login', 'index');?>" class="ncp-btn ncp-btn-red">我要兑换</a>
                     <?php }?>
                </div>
            </div>
        </div>
        <div id= "con"></div>
        <?php } ?>
    <?php }else{?>
    <div class="norecord"><?php echo $lang['home_voucher_list_null'];?></div>
    <?php }?>

    </div>
</div>
<script type="text/javascript">
$(function(){
    $("[nc_type='exchangebtn']").click(function(){
        $.get("index.php?act=pointvoucher&op=voucherexchange&vid="+$(this).attr('data-param'),function(data){
          layer.open({
              title:['积分兑换','background-color:#6A5883; color:#fff;'],
              anim:true,
              type: 1,
              shadeClose: true, //点击遮罩关闭
              content: data,
              cancel:function(index){return true;}
      		});
        });
    });
});
</script>
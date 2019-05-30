<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title>我的优惠券</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/reset.css">
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/TouchSlide.1.1.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/flexible.js"></script>
</head>
<style type="text/css">
.lottery_tips p{    padding: 6px;
    line-height: 18px;}
</style>
<body class="grayBg">
<div class="header_top">
    <div class="titleWrap">我的奖券</div>
</div>
<ul class="lottery_tab_tt">
    <li<?php if(empty($_GET['select_detail_state']) or $_GET['select_detail_state']==0):?> class="on"<?php endif;?> data-state="0"><span>全部</span></li>
    <li<?php if($_GET['select_detail_state']==1):?> class="on"<?php endif;?>  data-state="1"><span>有效</span></li>
    <li<?php if($_GET['select_detail_state']==2):?> class="on"<?php endif;?>  data-state="2"><span>过期</span></li>
</ul>
<div class="lottery_tab_list on">
   <?php if(count($output['list'])): foreach($output['list'] as $item): ?>
    <div class="item">
        <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_VOUCHER.DS.$_SESSION['store_id'].DS.str_ireplace('.', '_small.', $item['voucher_t_customimg']);?>" alt="<?php echo $item['voucher_title'];?>">
    </div>
<?php endforeach; endif; ?>
</div>
<div class="lottery_tips">
    <div>优惠券使用说明：</div>
    <p>1、购买商品时，优惠卷券可抵购物券面显示的现金价值；</p>
<p>2、每张订单只能使用一张优惠券，且不得与其他优惠方式同时使用；</p>
<p>3、本券不得兑换现金不设找零；</p>
<p>4、退货时，本券价值不予退还现金；</p>
     <p>最终解释权归由云之南优品所有</p>
</div>
<ul class="footer_menu">
    <li class="rows on">
        <a href="index.php?act=show_store&amp;op=index&amp;store_id=7">
            <i class="icon1"></i>
            <span>首页</span>
        </a>
    </li>
    <li class="rows">
        <a class="" href="index.php?act=member_vr_order">
            <i class="icon2" style="font-size: 26px"></i>
            <span>订单中心</span>
        </a>
    </li>
    <li class="rows">
        <a class="" href="index.php?act=member&amp;op=home">
            <i class="icon3"></i>
            <span>个人中心</span>
        </a>
    </li>
</ul>
<script>
    $(function(){
        $('.lottery_tab_tt li').click(function(){
            location.href='index.php?act=member_voucher&op=voucher_list&select_detail_state='+$(this).data('state');
        })
    })
</script>
</body>
</html>

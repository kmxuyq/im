<!doctype html>
<html>
<head>
    <title>推客中心</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元抢购，伊美假日"/>
    <meta name="author" content="伊美假日"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/shareshop/css/reset.css">
    <link rel="stylesheet" href="/wap_shop/templates/default/shareshop/css/main.css">
    <script src="/wap_shop/templates/default/shareshop/js/less.min.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/jquery-1.11.3.min.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/main.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/TouchSlide.1.1.js"></script>
</head>
<body>
<div class="dist_user_data">
    <div class="hd">
        <img src="<?php echo getMemberAvatar($output['share_member']['headimgurl']);?>" alt="<?php echo $output['share_member']['nickname'];?>">
    </div>
    <div class="data">
        <h1><?php echo $output['share_member']['nickname'];?></h1>
        <div class="jion_time">加入时间: <?php echo date('Y-m-d H:i:s', $output['share_member']['reg_time']);?></div>
    </div>
</div>

<!-- <ul class="earnings">
    <li class="columns">
        <div class="tt">
            <span class="icon1"></span>
            <p>已提现佣金</p>
        </div>
        <div class="text">
            <span>￥</span>
            <em><?php echo floatval($output['share_member']['done_credits']);?></em>
        </div>
    </li>
    <li class="columns">
        <div class="tt">
            <span class="icon2"></span>
            <p>可提佣金</p>
        </div>
        <div class="text">
            <span>￥</span>
            <em><?php echo floatval($output['share_member']['credits']);?></em>
        </div>
    </li>
</ul> -->
<?php if($output['total'] > 0):?>
<div class="join_tips" onclick="window.location.href='/wap_shop/index.php?act=member_share&amp;op=lower_member';">
    <span class="icon"></span>
    <p>您有<span><?php echo $output['total']; ?></span>个新伙伴申请加入!</p>
</div>
<?php endif;?>
<ul class="dist_menu">
    <li>
        <a class="ceil" href="/wap_shop/index.php?act=member_order&amp;is_share=1">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon1.jpg" alt="分销订单">
            <p>实物订单</p>
        </a>
    </li>
    <li>
      <a class="ceil" href="/wap_shop/index.php?act=member_vr_order&amp;is_share=1">
           <img src="/wap_shop/templates/default/shareshop/images/dist_icon1.jpg" alt="分销订单">
           <p>虚拟订单</p>
      </a>
  </li>
   <!--  <li>
        <a class="ceil" href="/wap_shop/index.php?act=member_share&amp;op=record">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon2.jpg" alt="我的收益">
            <p>我的收益</p>
        </a>
    </li> -->
    <li>
        <a class="ceil" href="/wap_shop/index.php?act=member_share&amp;op=lower_member">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon3.jpg" alt="我的团队">
            <p>我的团队</p>
        </a>
    </li>
   <!--  <li>
        <a class="ceil" href="/wap_shop/index.php?act=member&amp;op=cmmsapply">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon4.jpg" alt="提现">
            <p>提现</p>
        </a>
    </li>
    <li>
        <a class="ceil" href="/wap_shop/index.php?act=member&amp;op=cmms">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon5.jpg" alt="财务明细">
            <p>财务明细</p>
        </a>
    </li> -->
    <li>
        <a class="ceil" href="javascript:openPopWin('#qrcode');">
            <img src="/wap_shop/templates/default/shareshop/images/dist_icon6.jpg" alt="">
            <p>推广码</p>
        </a>
    </li>
</ul>
<div id="qrcode" style="display:none;"></div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/layer-v2.1/layer/layer.js"></script>
<script type="text/javascript" src="/wap_shop/templates/default/shareshop/js/qrcode.min.js"></script>
<style>
#qrcode{  display: block;  background: #fff;  z-index: 999;  left: 10px;  top: 10px;  position: fixed;  width: 200px;  height: 200px;}
#qrcode img{ display: block; width: 170px;  margin: 15px auto; }
</style>
<script>
new QRCode(document.getElementById('qrcode'), '<?php echo WAP_SHOP_SITE_URL; ?>/index.php?act=show_store&op=share&share_uid=<?php echo $_SESSION['member_id']; ?>&store_id=<?php echo $_SESSION['share_store_id'] ?>&store_member_info=<?php echo $_SESSION['share_store_id']; ?>');
/*function show_qr(){
   layer.open({
      type: '1',
      // area: ['200px', '200px'],
      content: document.getElementById('qrcode').innerHTML,
      btn: '确定',
   });
}*/
</script>
</body>
</html>

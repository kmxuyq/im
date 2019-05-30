<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_cart.css" rel="stylesheet" type="text/css">
<style type="text/css">
.head-search-bar, .head-user-menu, .public-nav-layout, .head-app {
	display: none !important;
}
h3,h5{padding: 0; margin: 0;}
.ncc-title{
  height: auto;
  padding: 0;
}
.ncc-finish-a {
  height: auto;
  line-height: 54px;
}
.ncc-title{
  padding: 10px;
}

a.ncc-btn-mini{width: 35%;
  height: 30px;
  line-height: 28px;
  font-size: 16px;
  font-family: "Microsoft Yahei";
  border-radius: 4px;
  margin: 0 17px;}
.ncc-finish-b{
  line-height: 40px;
}
</style>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/header_sign.css" rel="stylesheet" type="text/css">
<header class="lbdb_header">
	<span class="lbdb_arrow" onclick="location.href = 'index.php?act=pointprod'"></span>	
	<h1>订单完成</h1>
</header>
<div class="pr">
  <ul class="ncc-flow ncc-point-flow">
    <li class=""><i class="step1"></i>
      <p><?php echo $lang['pointcart_ensure_order'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class=""><i class="step2"></i>
      <p><?php echo $lang['pointcart_ensure_info'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class="current"><i class="step4"></i>
      <p><?php echo $lang['pointcart_exchange_finish'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
  </ul>
  <div class="ncc-main">
    <div class="ncc-title" style="text-align: center">
      <h3><?php echo $lang['pointcart_exchange_finish'];?></h3>
      <h5>兑换订单已提交完成，祝您购物愉快</h5>
    </div>
      <div class="ncc-receipt-info mb30" style="font-size: 14px">
        <div class="ncc-finish-a"><?php echo $lang['pointcart_step2_order_created'];?>
          <span class="all-points" style="clear: both;display: block"><?php echo $lang['pointcart_step2_order_allpoints'].$lang['nc_colon'];?><em style="font-weight: normal;"><?php echo $output['order_info']['point_allpoint']; ?></em></span> </div>
        <div class="ncc-finish-b">可通过用户中心<a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=member_pointorder&op=orderlist">积分兑换记录</a>查看兑换单状态。

        </div>
        <div class="ncc-finish-c mb30" style="text-align: center">
        <a class="ncc-btn-mini ncc-btn-green mr15" href="index.php?act=pointprod" style="border: none"><i class="icon-shopping-cart"></i>继续购物</a>
        <a class="ncc-btn-mini ncc-btn-acidblue" href="index.php?act=member_pointorder&op=order_info&order_id=<?php echo $output['order_info']['point_orderid'];?>" style="border: none"><i class="icon-file-text-alt"></i><?php echo $lang['pointcart_step2_view_order'];?></a></div>
      </div>
  </div>
</div>


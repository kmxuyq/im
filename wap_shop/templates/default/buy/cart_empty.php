<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
    a{text-decoration: none;}
    .ui-btn {
    font-size: 14px;
    border: 1px #3b84ed solid;
    width: 86px;
    height: 26px;
    margin-top: 7px;
    line-height: 24px;
    background-color: #3b84ed;
    border-color: #3b84ed;
    background-image: -webkit-gradient(linear,left top,left bottom,color-stop(0.5,#3b84ed),to(#3b84ed));
    color: #fff;
    background-clip: padding-box;
    padding: 5px;
    margin: 10px;
    }
</style>
<div style="text-align:center;margin-top:30%;">
  <h4 style="font-size:16px;padding:20px;color:gray;"><?php echo $lang['cart_index_no_goods_in_cart'];?></h4>
  <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/buy/cart.png" style="margin:0 auto;width:20%">
  <p style="padding:20px;padding-top:30px">
  <a href="index.php?act=show_store&op=<?php if($output['share_shop']){echo 'share';}else{ echo 'index';}?>&store_id=<?php echo $output['route_store_id']?$output['route_store_id']:$_SESSION['share_store_id'];?>" class="ui-btn qz-padding-10 ui-btn-primary">
  <i class="icon-reply-all"></i>
  <?php echo $lang['cart_index_shopping_now'];?>
  </a>
  <a href="index.php?act=member_order" class="ui-btn qz-padding-10 ui-btn-primary">
  <i class="icon-file-text"></i>
  <?php echo $lang['cart_index_view_my_order'];?>
  </a>
  </p>
</div>
    <!--公共底部导航-->
<div id="footer_html"></div>
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css" />
<script type="text/javascript" src="/wap/js/tmpl/footer_html.js"></script>

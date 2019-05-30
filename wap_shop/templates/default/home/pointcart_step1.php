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
<title>积分商城-确认兑换</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/header_sign.css" rel="stylesheet" type="text/css">
</head>

<body class="bg_gray">
<header class="lbdb_header">
	<span class="lbdb_arrow" onclick="location.href = 'index.php?act=pointprod'"></span>	
	<h1>确认订单信息</h1>
</header>
<!--订单详情待付款-->
<div class="confirmation_order order_mans order_dels">
    <form method="post" id="porder_form" name="porder_form" action="index.php?act=pointcart&op=step2">
    <!--收货地址-->
    <div class="sure_receiving_address" style="margin-bottom:0; border-top:none">
        <a href="javascript:;" class="address_selcted">
            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/mrdz_03.png" />
              <div class="people">
                  <input id="address_default" type="hidden" name="address_options" value="<?php echo $output['address_info']['address_id']; ?>"/>
                  <span><?php echo $output['address_info']['true_name'];?></span>
                  <span class="fr"><?php if($output['address_info']['mob_phone'] != ''){ echo $output['address_info']['mob_phone'];}else{echo $output['address_info']['tel_phone'];}?></span>
                  <div class="clear"></div>
              </div>
              <div class="add"><?php echo $output['address_info']['area_info'];?> <?php echo $output['address_info']['address'];?></div>
          </a>
    </div>
    <!--订单-->
    <div class="order_states_list">
        <ul>
            <li>
                <div class="middle_wrap">
                    <?php
                        $num = 0;
                        if(is_array($output['pointprod_arr']['pointprod_list']) and count($output['pointprod_arr']['pointprod_list'])>0) {
                        foreach($output['pointprod_arr']['pointprod_list'] as $val) {
                    ?>
                    <div class="middle">
                        <a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $val['pgoods_id']));?>">
                            <div class="order_goods integral_mall">
                                <div class="goods_pic"><div class="self_width"><img src="<?php echo $val['pgoods_image_small']; ?>" /></div></div>
                                <div class="goods_dis integ_mallpro">
                                    <div class="title"><?php echo $val['pgoods_name']; ?></div>
                                    <div class="goods_price mallpic">
                                        <div class="mall_left">
                                             <span class="mlpic01"></span>
                                             <span class="mlpic02"><?php echo $val['onepoints']; ?></span>
                                             <span class="mlpic03">￥<?php echo $val['pgoods_price']; ?></span>
                                        </div>
                                        <span class="goods_num goods_numL">x<?php echo $val['quantity']; ?></span>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                        $num++;
                        }
                        }
                    ?>
                </div>
                <div class="bottom activejs">
                    <span class="fl">共 <?php echo $num; ?> 件商品</span>
                    <span class="fr frpic"><?php echo $output['pointprod_arr']['pgoods_pointall']; ?></span>
                </div>
            </li>
        </ul>
    </div>
    </form>
</div>
<div class="foothr"></div>
<div class="bottom_btnt"><a href="javascript:;" id="submitpointorder">确认兑换</a></div>
<script type="text/javascript">
  function pcart_messageclear(tt){
    if (!tt.name)
    {
      tt.value = '';
      tt.name = 'pcart_message';
    }
  }

  $("#submitpointorder").click(function(){
    var chooseaddress = parseInt($("#address_default").val());
    if(!chooseaddress || chooseaddress <= 0){
      alert('请选择收货人地址');
    } else {
      $('#porder_form').submit();
    }
  });
</script>
</body>
</html>

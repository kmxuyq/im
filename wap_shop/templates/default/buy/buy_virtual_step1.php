<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<!--<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>-->

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="location.href=history.back(-1);" ></i>
    <h1 class="qz-color">设置购买数量</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<!--价格日历-->
<?php if(in_array(intval($output['goods_info']['calendar_type']),array(1,2,3))){ ?>
    <section class="ui-container">

        <form action="<?php echo urlShopWAP('buy_virtual','buy_step2');?>" method="POST" id="form_buy" name="form_buy">
            <div class="qz-padding">店铺名称：<a href="<?php echo urlShopWAP('show_store','index',array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $output['store_info']['store_name'];?></a><span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
                <a href="javascript:void(0)" onclick="collect_goods('<?php echo $output['goods_info']['goods_id']; ?>');"></a>
            </div>

            <div class="ui-list qz-top-b ui-border-t">

                <div class="ui-list-thumb qz-list-thumb" style="width:120px;height:120px;margin:0px 10px 10px 0px">
                    <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($output['goods_info'],240);?>" alt="<?php echo $output['goods_info']['goods_name']; ?>"  class="qz-img-block"style="padding:15px;"/></a>

                </div>



                <div class="ui-list-info qz-light" style="padding:15px;">
                    <h4 class="ui-nowrap"><a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank"><?php echo $output['goods_info']['goods_name']; ?></a></h4>
                    <?php if ($output['goods_info']['ifgroupbuy']) { ?>
                        <span class="groupbuy">抢购</span>
                    <?php } ?>
                    <?php if($output['goods_info']['calendar_type'] == 2){ ?>
                        <p class="ui-nowrap">入住时间：<font class="qz-color2"><?php echo $output['goods_info']['hotel_state_time']; ?></font></p>
                        <p class="ui-nowrap">离店时间：<font class="qz-color2"><?php echo $output['goods_info']['hotel_end_time']; ?></font></p>
                        <p class="ui-nowrap">共：<font class="qz-color2"><?php echo $output['goods_info']['hotel_num']; ?>晚</font></p>
                        <p class="ui-nowrap">总价：￥<font class="qz-color2"> </font><?php echo $output['goods_info']['hotel_total'];$total=$output['goods_info']['hotel_total']; ?></p>
                        <input type="hidden" value="<?php echo $output['goods_info']['hotel_state_time'];?>" name="hotel_state_time">
                        <input type="hidden" value="<?php echo $output['goods_info']['hotel_end_time'];?>" name="hotel_end_time">
                        <input type="hidden" value="<?php echo $output['goods_info']['hotel_num'];?>" name="goods_num">
                        <input type="hidden" value="<?php echo encrypt($output['goods_info']['hotel_total']);?>" name="price_total">
                        <input type="hidden" value="<?php echo $output['goods_info']['storage_state'];?>" name="hotel_stock">
                        <input type="hidden" value="2" name="calendar_type">
                        <input type="hidden" value="<?php echo $output['goods_info']['state']; ?>" name="state"><!--判断商品是否能购买必填 1或0 -->
                    <?php }elseif($output['goods_info']['calendar_type'] ==1){//门票普通价格 ?>
                        <p class="ui-nowrap">出发时间：<font class="qz-color2"><?php echo  $output['goods_info']['start_time']; ?></font></p>
                        <?php foreach( $output['goods_info']['ticket_type'] as $item){
                            echo $item;
                        } ?>
                        <p class="ui-nowrap">数量：<?php echo  $output['goods_info']['goods_num']; ?></p>
                        <p class="ui-nowrap">总价：￥<font class="qz-color2"><?php echo $output['goods_info']['price_total'];$total=$output['goods_info']['price_total']; ?></font></p>

                        <input type="hidden" value="<?php echo $output['goods_info']['start_time'];?>" name="start_time">
                        <input type="hidden" value="<?php echo encrypt($output['goods_info']['price_total']);?>" name="price_total">
                        <input type="hidden" value="<?php echo $output['goods_info']['goods_num'];?>" name="goods_num">
                        <input type="hidden" value="<?php echo $output['goods_info']['storage_state'];?>" name="storage_state">
                        <input type="hidden" value="1" name="calendar_type">
                        <input type="hidden" value="<?php echo $output['goods_info']['state']; ?>" name="state">
                    <?php }elseif($output['goods_info']['calendar_type'] ==3){//高尔夫日历 ?>

                        <p class="ui-nowrap">预定时间：<font class="qz-color2"><?php echo $output['goods_info']['start_time']; ?></font></p>
                        <p class="ui-nowrap">价格：<font class="qz-color2"><?php echo $output['goods_info']['goods_price']; ?></font></p>
                        <p class="ui-nowrap">单价：￥<font class="qz-color2"><?php echo $output['goods_info']['goods_price'];$total=$output['goods_info']['goods_price']; ?></font></p>
                        <input type="hidden" value="<?php echo $output['goods_info']['storage_state'];?>" name="storage_state">
                        <input type="hidden" value="<?php echo encrypt($output['goods_info']['goods_price']);?>" name="price_total">
                        <input type="hidden" value="3" name="calendar_type">
                        <input type="hidden" value="<?php echo $output['goods_info']['state']; ?>" name="state">
                    <?php } ?>

                    <input type="hidden" name="get_info" value="<?php echo encrypt(json_encode($_GET));?>">

                    <p style="font-size:16px">订单总价：<em id="cartTotal" style="color:#DA3228;font-size:18px;"><?php echo $total; ?></em>元</p>
                </div>
        </form>
        <div  style="padding:10px;margin:0px;"><a id="next_submit" href="javascript:void(0)" class="ui-btn-lg ui-btn-primary">下一步</a></div>
        <div id="menu"></div>
    </section>

<?php }else{ ?>
    <section class="ui-container">

        <form action="<?php echo urlShopWAP('buy_virtual','buy_step2');?>" method="POST" id="form_buy" name="form_buy">
            <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
            <input type="hidden" name="goods_name" value="<?php echo encrypt($output['goods_info']['goods_name']);?>">
            <input type="hidden" name="goods_price" value="<?php echo encrypt($output['goods_info']['goods_price']);?>">
            <input type="hidden" name="goods_total" value="<?php echo encrypt($output['goods_info']['goods_total']);?>">
            <input type="hidden" name="get_info" value="<?php echo encrypt(json_encode($_GET));?>">

			<input type="hidden" name="calendar_type" value="<?php echo $output['goods_info']['calendar_type'];?>">
			<input type="hidden" name="ticket_date" value="<?php echo encrypt($output['goods_info']['ticket_date']);?>">

            <div class="qz-padding">店铺名称：<a href="<?php echo urlShopWAP('show_store','index',array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $output['store_info']['store_name'];?></a><span member_id="<?php echo $output['store_info']['member_id'];?>"></span>
                <a href="javascript:void(0)" onclick="collect_goods('<?php echo $output['goods_info']['goods_id']; ?>');"></a>
            </div>

            <div class="ui-list qz-top-b ui-border-t">

                <div class="ui-list-thumb qz-list-thumb" style="width:120px;height:120px;margin:0px 10px 10px 0px">
                    <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank" class="ncc-goods-thumb"><img src="<?php echo thumb($output['goods_info'],240);?>" alt="<?php echo $output['goods_info']['goods_name']; ?>"  class="qz-img-block"style="padding:15px;"/></a>

                </div>

                <div style="float:right;padding:15px;<?php if($_GET["type_id"]=='46') echo 'display:none';?>"><a href="JavaScript:void(0);" onclick="decrease_quantity();" class="zj" style=" border: 1px #3b84ed solid;color: #4f5f6f; border-radius: 3px; background: none">-</a>
                    <input id="quantity" name="quantity" value="<?php echo $output['goods_info']['quantity'];?>" maxvalue="<?php echo $output['goods_info']['virtual_limit'];?>" price="<?php echo $output['goods_info']['goods_price'];?>" onkeyup="change_quantity(this);" type="text" class="tijiao w20"/>
                    <a href="JavaScript:void(0);" title="最多允许购买<?php echo $output['goods_info']['virtual_limit'];?>个" onclick="add_quantity();" class="zj"  style=" border: 1px #3b84ed solid;color: #4f5f6f; border-radius: 3px; background: none">+</a></div>

                <div class="ui-list-info qz-light" style="padding:15px;">
                    <h4 class="ui-nowrap"><a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$output['goods_info']['goods_id']));?>" target="_blank"><?php echo $output['goods_info']['goods_name']; ?></a></h4>
                    <?php if ($output['goods_info']['ifgroupbuy']) { ?>
                        <span class="groupbuy">抢购</span>
                    <?php } ?>
                    <p <?php if($_GET["type_id"]=='46') echo 'style="display:none"';?>>最多允许购买<?php echo $output['goods_info']['virtual_limit'];?>个</p>
                    <p style="font-size:16px">商品总价：<em id="cartTotal" style="color:#DA3228;font-size:18px;"><?php echo $output['goods_info']['goods_total']; ?></em>元</p>
                </div>


        </form>
        <div  style="padding:10px;margin:0px;"><a id="next_submit" href="javascript:void(0)" class="ui-btn-lg ui-btn-primary">下一步</a></div>
        <div id="menu"></div>
    </section>
<?php } ?>





<script>
$(document).ready(function(){
	$('#next_submit').on('click',function(){
		$('#form_buy').submit();
	});
});
</script>

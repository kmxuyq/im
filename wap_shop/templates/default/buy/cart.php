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
<title>购物车</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery.event.drag-1.5.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery.touchSlider.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/iscroll.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/wechat.js"></script>
<style type="text/css">
    .shopping_bag .order_list_cons .order_list li .order_goods .goods_dis .title a{word-break: break-all;}
.color{color: red;}
.bottom_btnt{
    position: fixed;
    left: 0px;
    bottom: 0px;
}
</style>
</head>
<body class="bg_gray">
<!--购物袋-->
<div class="shopping_bag">
    <!--购物袋空-->
    <div class="shopping_bag_empty" style="display:none">
        <img src="images/gwck_03.png" />
        <p>您的购物车中没有需要结算的商品</p>
        <a href="javascript:;">去逛逛</a>
    </div>
    <!--订单列表-->
    <form action="index.php?act=buy&op=buy_step1" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" value="1" name="ifcart">
    <div class="order_list_cons_wrap">
        <div class="order_list_cons" id="store_cart">
            <?php $disable_carts = 0; ?>
            <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
            <div class="active_type" id="store_cart_list">
                <div class="full_cut">
                <?php if (!empty($output['mansong_rule_list'][$store_id]) && is_array($output['mansong_rule_list'][$store_id])) { ?>
                    <span class="title1">满即送</span><span><?php echo implode('<br/>', $output['mansong_rule_list'][$store_id]);?></span>
                <?php } elseif (!empty($output['free_freight_list'][$store_id])) { ?>
                    <span class="title1">免运费</span><span><?php echo $output['free_freight_list'][$store_id];?>&emsp;</span>
                <?php } else { ?>
                    <span class="title1">店铺</span><span class="dis"><a href="/"><?php echo $cart_list[0]['store_name']; ?></a></strong> <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span>
                <?php } ?>
                </div>
                <ul class="order_list">
                    <?php foreach($cart_list as $cart_info) {?>
                    <?php if($cart_info['state']) { ?>
                    <li class="store_goods_info">
                        <a class="sel has_sel">
                            <input style="display:none;" id="only_ck" class="only_ck_<?php echo $cart_info['cart_id'];?>" type="checkbox" name="cart_id[]" <?php echo $cart_info['state'] ? 'checked' : 'disabled';?> value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>">
                        </a>
                        <div class="order_goods">
                            <div class="goods_pic"><a href="index.php?act=goods&op=index&goods_id=<?php echo $cart_info['goods_id'];?>"><img src="<?php echo thumb($cart_info,60);?>" /></a></div>
                            <div class="goods_dis">
                                <div class="title"><a href="index.php?act=goods&op=index&goods_id=<?php echo $cart_info['goods_id'];?>"><?php echo $cart_info['goods_name']; ?></a>
                                <?php if (!empty($cart_info['xianshi_info'])) {?>
                                    <span class="xianshi">满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></span>
                                <?php }?>
                                <?php if ($cart_info['ifgroupbuy']) {?>
                                    <span class="groupbuy">抢购<?php if ($cart_info['upper_limit']) {?>，最多限购<strong><?php echo $cart_info['upper_limit']; ?></strong>件<?php } ?></span>
                                <?php }?>
                                <?php if ($cart_info['bl_id'] != '0') {?>
                                    <span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span>
                                <?php }?>
                                </div>
                                <div class="buy_num_wrap">
                                    <div class="buy_num" data-cart-id="<?php echo $cart_info['cart_id'];?>">
                                        <a id="min" class="min min_yes_img"></a>
                                        <input type="text" class="num" stock="<?php echo $cart_info["goods_storage"];?>" upper-limit="<?php echo $cart_info['upper_limit']; ?>" id="goods_num_<?php echo $cart_info['cart_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" onkeyup="this.value=this.value.replace(/[^\d]/g,'') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"/>
                                        <a id="plus" class="plus plus_yes_img"></a>
                                    </div>

                                </div>
                                <div class="goods_price">
                                    <span class="present_price"><span>&yen;</span><span class="good_price" id="good_price_<?php echo $cart_info['cart_id']?>"><?php echo $cart_info['goods_price']; ?></span></span>
                                    <span class="original_price">&yen;<?php echo $cart_info['goods_marketprice']; ?></span>
                                </div>
                                <div class="goods_price">
                                   <span class="present_price"><?php if ($cart_info['calendar_date']):
                                      $cal = explode(',', $cart_info['calendar_date']);
                                      echo '出行日期：',$cal[0];
                                   endif; ?></span>
                                </div>
                                <span id="error_<?php echo $cart_info['cart_id']; ?>" class="buy_num_wrap color buy_error" style="height: 38px;line-height: 38px;margin: 10px;"></span>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </li>
                    <?php }else{ ?>
                        <?php $disable_carts++; ?>
                    <?php } ?>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
        </div>
        <!--失效商品-->
        <?php if($disable_carts){ ?>
        <div class="invalid_goods">
            <p>您的购物车中有<?php echo $disable_carts; ?>件商品已失效</p>
            <a href="index.php?act=cart&op=fail">查看</a>
        </div>
        <?php } ?>
        <!--操作-->
        <div class="order_handle">
            <a href="javascript:;" class="sel_all has_sel_all">
                <span class="icon"></span>
                <span class="txt">全选</span>
            </a>
            <a href="javascript:;" class="delete">删除</a>
            <div class="price">总计：<span class="ico">&yen;</span> <span id="cart_price">0.00</span></div>
            <div class="clear"></div>
        </div>
    </div>
    <div style="foothr"></div>
    <div class="bottom_btnt"><a href="javascript:;" id="form_submit">去结算（<span id="cart_num">0</span>）</a></div>
</div>
<!--结算按钮-->
</form>
<script type="text/javascript" >
//店铺小计，和购物车合计
function StoreTotal(){
    var CART_PRICE = 0;
    var stores = $('#store_cart').find('.order_list');
    stores.each(function(i, n){
        var STORE_TOTAL = 0;
        $(this).find('.store_goods_info').each(function(){
            var _GOODS_PRICE = 0;
            if($(this).find('#only_ck').attr('checked') == 'checked'){
                var goods_num = parseInt($(this).find('.num').val())
//             	var goods_num = $(this).find('.num').val()
                var goods_price = parseFloat($(this).find('.good_price').text())
                _GOODS_PRICE = goods_num * goods_price ;
            }
            STORE_TOTAL += _GOODS_PRICE;
        })
        CART_PRICE += STORE_TOTAL;
    })
    var cart_price = parseFloat(CART_PRICE).toFixed(2);
    $('#cart_price').text(cart_price);
    $('#cart_num').text($(".has_sel").length);
}
//商品数量后台更新验证
function UpdataGoodsNum(id,num){

    $.getJSON('index.php?act=cart&op=update&cart_id=' +id + '&quantity=' +num, function(result){
        if(result.state == 'true'){
           $('#goods_num_'+id).val(num)
           $('#good_price_'+id).text(result.goods_price);
            StoreTotal();
           $('.only_ck_'+id).val(id+'|'+num)
        }else{
        	$('#plus').removeClass("plus_yes_img");
            $('#plus').addClass("plus_no_img");
            $('#error_'+id).html(result.msg);
        }
    });
}
//商品数量加减
function GoodsPlus(id){
	$('.buy_error').html('');
	$('#min').removeClass("min_no_img");
    $('#min').addClass("min_yes_img");
    var num = parseInt($('#goods_num_'+id).val())+1;
    UpdataGoodsNum(id,num);
}
function GoodsSubtract(id){
	$('.buy_error').html('');
	$('#plus').removeClass("plus_no_img");
    $('#plus').addClass("plus_yes_img");
    var num = parseInt($('#goods_num_'+id).val())-1;
    if(num > 0){
      UpdataGoodsNum(id,num);
    }else{
    	$('#min').removeClass("min_yes_img");
        $('#min').addClass("min_no_img");
        $('#error').html("不能再减少");
     }
}
function GoodsCont(id){
    var num = parseInt($('#goods_num_'+id).val());
    if(num > 0) {
        UpdataGoodsNum(id, num);
    }
}
//头部删除购物车信息
function DelCartGoods(id){
    if(confirm('你确定要删除此商品吗？')){
    $.getJSON('index.php?act=cart&op=del&cart_id='+id, function(result){
        if(result.state){
            window.location.reload();
        }else{
            alert(result.msg);
        }
    });
    }
}
//删除购物车信息
function DelChooseCartGoods(){
    var CART_IDS = new Array();
    var stores = $('#store_cart').find('.order_list');
    stores.each(function(i, n){
        $(this).find('.store_goods_info').each(function(){
            if($(this).find('#only_ck').attr('checked') == 'checked'){
                var goods_num = parseInt($(this).find('.num').val());
                var ck = $(this).find('#only_ck').val().split("|")[0];
                CART_IDS.push(ck);
            }
        })
    })
    var ids = CART_IDS.join(',');
    $.getJSON('index.php?act=cart&op=delcarts&cart_ids='+ids, function(result){
        if(result.state){
            window.location.reload();
        }else{
            alert(result.msg);
        }
    });
}

$(function(){
  StoreTotal();
  $('#form_submit').click(function(){
  		if ($('#form_buy').find('#only_ck').size() == 0) {
             alert('请勾选你要购买的商品!');return false;
        }
		var vaid_msg='';
	  	$('.buy_num_wrap').find('input').each(function(){
			var cart_id=$(this).parent().data('cart-id');
			var goods_num=parseInt($(this).val());
    		var stock=parseInt($(this).attr('stock'));
    		var upper_limit=$(this).attr('upper-limit');
			//alert(cart_id+'--'+goods_num+'--'+stock+'--'+upper_limit);
			if(upper_limit!==''){
				if(parseInt(upper_limit)>0 && goods_num>upper_limit){
					$('#error_'+cart_id).text('购买数量不能超过'+upper_limit+'件');
					vaid_msg+=upper_limit;return false;
			    }else{
			    	$('#error_'+cart_id).text();
			    }
			}
		    //---
		    if(goods_num>stock){
				$('#error_'+cart_id).text('库存不足');
				vaid_msg+=stock;return false;
				return false;
		    }else{
		    	$('#error_'+cart_id).text();
		    }
		 });
		 if(vaid_msg==''){
			$('#form_buy').submit();
		}
   });

$(".delete").click(function () {
    DelChooseCartGoods();
})
//点击活动下拉菜单显示活动列表
$(".choice-active").click(function(){
        $(this).find("span").toggleClass("choiced").parent().find("ul").stop().slideToggle(350);
        })
//选取活动
$(".choice-active ul li").click(function(){
    $(this).parents(".choice-active").find("p").html($(this).html());
    })

//商品详情页面 购买数量 js
$(".buy_num .min").on("click",function(){
    if(!$(this).hasClass("disable")&&parseInt($(this).siblings(".num").val())>0){
        GoodsSubtract($(this).parent().data('cart-id'));
        }
    })
$(".buy_num .plus").on("click",function(){
	var cart_id=$(this).parent().data('cart-id');
    var goods_num=parseInt($('#goods_num_'+cart_id).val());
    var stock= parseInt($(this).parent().find('input').attr('stock'));
    var upper_limit=$(this).parent().find('input').attr('upper-limit');
	if (upper_limit !== '') {
		if (parseInt(upper_limit)>0 && goods_num >= upper_limit) {
			$('#error_' + cart_id).text('购买数量不能超过' + upper_limit + '件');
			return false;
		}
		else {
			$('#error_' + cart_id).text();
		}
	}
    //---
    if(goods_num>=stock){
		$('#error_'+cart_id).text('库存不足');return false;
    }else{
    	$('#error_'+cart_id).text();
    }
   // alert(goods_num+'--'+stock+'--'+upper_limit);
    GoodsPlus($(this).parent().data('cart-id'));
    })

$(".num").keyup(function(){
	var cart_id=$(this).parent().data('cart-id');
    var goods_num=parseInt($('#goods_num_'+cart_id).val());
    var stock=parseInt($(this).parent().find('input').attr('stock'));
    var upper_limit=$(this).parent().find('input').attr('upper-limit');
	if (upper_limit !== '') {
		if (parseInt(upper_limit)>0 && goods_num >= upper_limit) {
			$('#error_' + cart_id).text('购买数量不能超过' + upper_limit + '件');
			$(this).val(upper_limit);
			return false;
		}
		else {
			$('#error_' + cart_id).text();
		}
	}
    //---
    if(goods_num>=stock){
		$('#error_'+cart_id).text('库存不足');
		$(this).val('1');
		return false;
    }else{
    	$('#error_'+cart_id).text();
    }
    GoodsCont($(this).parent().data('cart-id'));
})
//选择要操作的商品
$(".shopping_bag .order_list_cons .order_list li .sel").on("click",function(){
    var index=$(".shopping_bag .order_list_cons .order_list li .sel").length;
    $(this).toggleClass("has_sel");
    var check = $(this).find("input");
    if(check.attr("checked"))
    {
        check.removeAttr("checked");
    }
    else
    {
        check.attr("checked",'true');
    }
    if($(".has_sel").length==index){
        $(".shopping_bag .order_handle .sel_all").addClass("has_sel_all")
        }
    else{
        $(".shopping_bag .order_handle .sel_all").removeClass("has_sel_all")
        }
    StoreTotal()
    })
//全选
$(".shopping_bag .order_handle .sel_all").on("click",function(){
    if(!$(this).hasClass("has_sel_all")){
        $(this).addClass("has_sel_all").parents(".shopping_bag").find(".order_list li .sel").addClass("has_sel");
        $(this).parents(".shopping_bag").find(".order_list li .sel");
            var sellist = $(this).parents(".shopping_bag").find(".order_list li .sel");
            sellist.each(function (i,n){
                var check = $(n).find("input");
                check.attr("checked",'true');
            })
            StoreTotal();
        }

    else{
        $(this).removeClass("has_sel_all").parents(".shopping_bag").find(".order_list li .sel").removeClass("has_sel");
            var sellist = $(this).parents(".shopping_bag").find(".order_list li .sel");
            sellist.each(function (i,n){
                var check = $(n).find("input");
                check.removeAttr("checked");
            })
            StoreTotal();
        }
    })
})
</script>
</body>
</html>

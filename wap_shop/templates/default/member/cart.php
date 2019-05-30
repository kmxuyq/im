<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>购物车-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">购物车</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<form action="index.php?act=buy&op=buy_step1" method="POST" id="form_buy" name="form_buy">
<input type="hidden" value="1" name="ifcart">
<section class="ui-container" style="margin-top:40px;">
    <div class="qz-padding" id="store_cart">
	<?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
	<!--店铺循环 STA-->
     <div id="store_cart_list" class="ui-form qz-background-none qz-border qz-radius-l-t qz-radius-r-t">
		<!--STA 店铺信息-->
        <div class="ui-form-item ui-form-item-checkbox" style="background:#F5F5F5">
			
                <label class="ui-checkbox" id="all_ck" >
                    <input type="checkbox" checked="checked"  value="<?php echo $store_id;?>">
                </label>
				 
                <p>
				<a href="index.php?act=show_store&op=index&store_id=<?php echo $store_id;?>">
				<?php echo $cart_list[0]['store_name']; ?>
				</a>
				</p>
				
            </div>
		<!--END 店铺信息-->	
            <div class="qz-gwc-c qz-background-white" id="store_goods_list">
            <?php foreach($cart_list as $cart_info) {?>  
			   <!--STA 店铺中的商品-->
			   <dl class="clearfix qz-padding qz-bottom-b" id="store_goods_info">
			   
                    <div class="lcn qz-fl">
                        <label class="ui-checkbox">
                            <input id="only_ck" class="only_ck_<?php echo $cart_info['cart_id'];?>" type="checkbox" name="cart_id[]" <?php echo $cart_info['state'] ? 'checked' : 'disabled';?> value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>">
                        </label>
                    </div>
                    
                    <div class="rcn">
					
                        <div class="qz-fl" style="width:90px;">
						    <a href="index.php?act=goods&op=index&goods_id=<?php echo $cart_info['goods_id'];?>" target="_blank" >
                            <img src="<?php echo thumb($cart_info,60);?>" class="qz-img-block">
							</a>
                        </div>
						
                        <div class="ui-list-info qz-light3 qz-padding-l10 qz-padding-t">
						
                            <h4 class="ui-nowrap">
							 <a href="index.php?act=goods&op=index&goods_id=<?php echo $cart_info['goods_id'];?>" target="_blank" >
							<?php echo $cart_info['goods_name']; ?>
							</a>
							</h4>
							
                            <div class="qz-bk5"></div>
							<?php if ($cart_info['state']) {?>
							<!--<input type="text" onclick="GoodsPlus(<?php echo $cart_info['cart_id'];?>);" class="qz-txt6"  value="+" readonly>-->
                            <input type="text" onchange="GoodsChange(<?php echo $cart_info['cart_id'];?>);" id="goods_num_<?php echo $cart_info['cart_id']; ?>" class="qz-txt6 goods_num" value="<?php echo $cart_info['goods_num']; ?>"  >
							<!--<input type="text" onclick="GoodsSubtract(<?php echo $cart_info['cart_id'];?>);" class="qz-txt6" value="-" readonly>-->
							<?php }else{ ?>
							<input name="invalid_cart[]" type="text" class="qz-txt6" value="<?php echo $cart_info['cart_id']; ?>" >
							<?php } ?>
                            <div class="clearfix qz-light4">
                                <span class="qz-fl qz-color2"><font class="qz-f22">￥</font><em id="goods_price" style="color:#DA3228"><?php echo $cart_info['goods_price']; ?><em></span>
                                <i class="ui-icon-delete qz-fr" onclick="DelCartGoods(<?php echo $cart_info['cart_id']; ?>);"></i>
                            </div>
                        </div>
                    </div>
                </dl>
				<!--END 店铺中的商品-->
            <?php } ?>	
			    <div class="ui-form-item ui-form-item-checkbox" style="color:#DA3228">
				    店铺合计:￥<em id="store_total" style="color:#DA3228"></em>
			    </div>
            </div>
            
        </div>
		<!--店铺循环 END-->
        <div class="qz-bk10"></div>
	<?php } ?>
    </div>
</section>

<div class="qz-bk40"></div>
<div class="qz-bk10"></div>
<footer class="ui-footer" style="height:52px;">
    <ul class="qz-padding qz-background-white qz-bottom-b qz-top-b qz-light clearfix">
	
        <span class="qz-fl" style="color:#4F5F6F">合计：<font class="qz-f22 qz-color2">￥
		</font><font class="qz-color2" id="cart_picre"></font></span>
        <span class="qz-fr"><input type="button" id="form_submit" value="结算" class="ui-btn ui-btn-primary qz-padding-30" /> </span>
    </ul>
</footer>
</form>
<script type="text/javascript" >
//店铺小计，和购物车合计      
function StoreTotal(){
	var CART_PICRE = 0;
	$('#store_cart').find('#store_cart_list').each(function(){
		var STORE_TOTAL = 0;
		var GOODS_PICRE = 0;
		$(this).find('#store_goods_info').each(function(){
			if($(this).find('#only_ck').attr('checked') == 'checked'){	
				var goods_num = parseInt($(this).find('.goods_num').val())
				var goods_price = parseFloat($(this).find('#goods_price').text())
				GOODS_PICRE = goods_num * goods_price ;
			}
			STORE_TOTAL = STORE_TOTAL + GOODS_PICRE;
		})
		$(this).find('#store_total').text(STORE_TOTAL);
		CART_PICRE += STORE_TOTAL;
	})
	$('#cart_picre').text(CART_PICRE)
}
//商品数量后台更新验证
function UpdataGoodsNum(id,num){
	
    $.getJSON('index.php?act=cart&op=update&cart_id=' +id + '&quantity=' +num, function(result){	
    	if(result.state == 'true'){
           $('#goods_num_'+id).val(num)
		    StoreTotal();
           $('.only_ck_'+id).val(id+'|'+num) 
        }else{
          alert(result.msg)
		}
    });
}
//商品数量加减
function GoodsChange(id){
	var num = parseInt($('#goods_num_'+id).val());
	UpdataGoodsNum(id,num);
}
function GoodsPlus(id){
	var num = parseInt($('#goods_num_'+id).val())+1;
	UpdataGoodsNum(id,num);
}
function GoodsSubtract(id){
	var num = parseInt($('#goods_num_'+id).val())-1;
	if(num > 0){
	  UpdataGoodsNum(id,num);
	}
}

$(function(){
  StoreTotal();
  //店铺全选按钮
  $('#store_cart').find('#all_ck').each(function(){
    $(this).click(function(){
		var all_ck = $(this).find('input').attr('checked')
		if(all_ck == 'checked'){
	    	$(this).parent().siblings('#store_goods_list').find('#only_ck').each(function(){
			   if($(this).attr('checked') !== 'checked'){
				    $(this).attr('checked','checked')
			   }
		   })
		}else{
		    $(this).parent().siblings('#store_goods_list').find('#only_ck').each(function(){
                if($(this).attr('checked') === 'checked'){
			    	$(this).removeAttr('checked')
			    }
			})
		}
	})	
	
  })
  $('#form_submit').click(function(){
		if ($('#form_buy').find('#only_ck').size() == 0) {
             alert('请勾选你要购买的商品!')
        }else {
            $('#form_buy').submit();
       }
   })
  
})
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
</script>
</body>
</html>
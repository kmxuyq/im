<?php defined('InShopNC') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>

 <script language="javascript">
//document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
//WeixinJSBridge.call('hideToolbar');
//});
 <?php if(!empty($output["wx_addr"])){?>
	 var ua = navigator.userAgent.toLowerCase();
	 //判断是否用微信打开
    if(ua.match(/MicroMessenger/i)=="micromessenger") {
    	if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
    		alert('请在微信中打开');
        }
    }
    <?php }?>
	function jsApiCall(){
		WeixinJSBridge.invoke('getLatestAddress',{
			"appId" : '<?php echo $output["wx_addr"]["appId"] ?>',
			"scope" : "jsapi_address",
			"signType" : "sha1",
			"addrSign" : '<?php echo $output["wx_addr"]["addrSign"] ?>',
			"timeStamp" : '<?php echo $output["wx_addr"]["timeStamp"]?>',
			"nonceStr" : '<?php echo $output["wx_addr"]["nonceStr"] ?>',
			},function(res){
			//若res 中所带的返回值不为空，则表示用户选择该返回值作为收货地址。否则若返回空，则表示用户取消了这一次编辑收货地址。
				if(res.err_msg == 'get_recently_used_address:ok'||res.err_msg == 'get_latest_address:ok'||res.userName!=''){
					//alert("收件人："+res.userName+"  联系电话："+res.telNumber+"  收货地址："+res.proviceFirstStageName+res.addressCitySecondStageName+res.addressCountiesThirdStageName+res.addressDetailInfo+"  邮编："+res.addressPostalCode);
					//document.getElementById("showAddress").innerHTML="收件人："+res.userName+"  联系电话："+res.telNumber+"  收货地址："+res.proviceFirstStageName+res.addressCitySecondStageName+res.addressCountiesThirdStageName+res.addressDetailInfo+"  邮编："+res.addressPostalCode;
					var url='/wap_shop/?act=buy&op=wx_addr_haddle&username='+res.userName+'&province='+res.proviceFirstStageName+'&city='+res.addressCitySecondStageName+'&area='+res.addressCountiesThirdStageName+'&info='+res.addressDetailInfo+'&tel='+res.telNumber;
					$.getJSON(url,function(data){
						//alert(data.state);
					});

				}
				else{
					//alert("获取地址失败，请重新点击");
				}
			});
		}
</script>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>填写核对购物信</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return"  onclick="location.href=history.back()" ></i>
    <h1 class="qz-color">
	<?php if ($output['store_cart_list'][key($output['store_cart_list'])][0]['is_fcode'] == 1) {?>
	F码购买
	<?php }else{?>
	填写核对购物信息
	<?php }?>
	</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>


<section class="ui-container">
<form method="post" id="order_form" name="order_form" action="index.php">
	<!--F码购买-->
	<?php include template('buy/buy_fcode');?>
<h5 class="qz-padding">请仔细核对填写收货、发票等信息，以确保物流快递及时准确投递。</h5>
<?php include template('buy/buy_address');?>
<input type="hidden" value="<?php echo $output['has_virtual'] ? 1 : 0; ?>" name="has_virtual">
<?php if ($output['has_virtual']) : ?>
   <div class="qz-padding">
        <p>电子兑换码/券接收方式<br>

		</p><h5>请仔细填写手机号，以确保电子兑换码准确发到您的手机。</h5><p></p>
        <div class="qz-bk10"></div>
		<input name="buyer_phone" class="qz-txt3 qz-light3 qz-border" placeholder="请填写手机号码" autocomplete="off" type="text" id="buyer_phone" value="" maxlength="11">
        <h5>您本次购买的商品含有虚拟商品，请正确输入接收手机号码，确保及时获得“电子兑换码”。可使用您已经绑定的手机或重新输入其它手机号码。</h5>
    </div>
<?php endif; ?>

 <?php include template('buy/buy_invoice');?>


	<?php include template('buy/buy_goods_list');?>

	<?php include template('buy/buy_amount');?>
    <input value="buy" type="hidden" name="act">
    <input value="buy_step2" type="hidden" name="op">
	<!--商品名称测试-->
    <input value="<?php echo $cart_list[0]['goods_name']; ?>" type="hidden" name="goods_name">
    <!-- 来源于购物车标志 -->
    <input value="<?php echo $output['ifcart'];?>" type="hidden" name="ifcart">
    <!-- 商品是否是分销商品-->
    <input value="<?php echo $output['is_share'];?>" type="hidden" name="is_share">

    <!-- offline/online -->
    <input value="online" name="pay_name" id="pay_name" type="hidden">

    <!-- 是否保存增值税发票判断标志 -->
    <input value="<?php echo $output['vat_hash'];?>" name="vat_hash" type="hidden">

    <!-- 收货地址ID -->
    <input value="<?php echo $output['address_info']['address_id'];?>" name="address_id" id="address_id" type="hidden">

    <!-- 城市ID(运费) -->
    <input value="" name="buy_city_id" id="buy_city_id" type="hidden">

    <!-- 记录所选地区是否支持货到付款 第一个前端JS判断 第二个后端PHP判断 -->
    <input value="" id="allow_offpay" name="allow_offpay" type="hidden">
    <input value="" id="allow_offpay_batch" name="allow_offpay_batch" type="hidden">
    <input value="" id="offpay_hash" name="offpay_hash" type="hidden">
    <input value="" id="offpay_hash_batch" name="offpay_hash_batch" type="hidden">

    <!-- 默认使用的发票 -->
    <input value="<?php echo $output['inv_info']['inv_id'];?>" name="invoice_id" id="invoice_id" type="hidden">
    <input value="<?php echo getReferer();?>" name="ref_url" type="hidden">

	</form>
</section>

<script type="text/javascript">
var SUBMIT_FORM = true;
//计算总运费和每个店铺小计
function calcOrder() {
    var allTotal = 0;
    $('em[nc_type="eachStoreTotal"]').each(function(){
        store_id = $(this).attr('store_id');//店铺id

        var eachTotal = 0;

        if ($('#eachStoreFreight_'+store_id).length > 0) { //运费
        	eachTotal += parseFloat($('#eachStoreFreight_'+store_id).html());
	    }
        if ($('#eachStoreGoodsTotal_'+store_id).length > 0) {//商品金额
        	eachTotal += parseFloat($('#eachStoreGoodsTotal_'+store_id).html());
	    }
        if ($('#eachStoreManSong_'+store_id).length > 0) {//满送
        	eachTotal += parseFloat($('#eachStoreManSong_'+store_id).html());
	    }
        if ($('#eachStoreVoucher_'+store_id).length > 0) {//代金券
        	eachTotal += parseFloat($('#eachStoreVoucher_'+store_id).html());
        }
        $(this).html(number_format(eachTotal,2));//格式化总额，取两位小数
        allTotal += eachTotal;
    });


    $('#orderTotal').html(number_format(allTotal,2));//订单总金额
}
$(function(){
    if($('select[nctype="voucher"]').length>0) {
        $('select[nctype="voucher"]').each(function(){
            var max=0;
            var choose=0;
            var _items;
            $(this).find("option").each(function(i, n){
                _items = $(n).val().split('|');
                var total = $('#eachStoreGoodsTotal_'+_items[1]).text();
                if(parseFloat(total)>=parseFloat(_items[4])) {
                    var _val = parseFloat(_items[2]);
                    if(_val > max) {
                        max = _val;
                        choose = i;
                    }
                }
            });
            $(this).find("option").eq(choose).attr("selected",true);
            if (max == 0) {
                $('#eachStoreVoucher_'+_items[1]).html('-0.00');
            } else {
                $('#eachStoreVoucher_'+_items[1]).html('-'+number_format(max,2));
            }
            calcOrder();
        });
    }
    $.ajaxSetup({
        async : false
    });
    if($('select[nctype="voucher"]').length>0) {
        $('select[nctype="voucher"]').each(function(){
            var items = $(this).val().split('|');
        });
        $('select[nctype="voucher"]').on('change',function(){
            if ($(this).val() == '') {
            	$('#eachStoreVoucher_'+items[1]).html('-0.00');
            } else {
                var items = $(this).val().split('|');
                $('#eachStoreVoucher_'+items[1]).html('-'+number_format(items[2],2));
            }
            calcOrder();
        });
    }
    <?php if (!empty($output['available_pd_amount']) || !empty($output['available_rcb_amount'])) { ?>
    function showPaySubmit() {
        if ($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) {
        	$('#pay-password').val('');
        	$('#password_callback').val('');
        	$('#pd_password').show();
        } else {
        	$('#pd_password').hide();
        }
    }

    $('#pd_pay_submit').on('click',function(){
        if ($('#pay-password').val() == '') {
        	alert('请输入支付密码');return false;
        }
        $('#password_callback').val('');
		$.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
            if (data == '1') {
            	$('#password_callback').val('1');
            	$('#pd_password').hide();
            } else {
            	$('#pay-password').val('');
            	alert('支付密码码错误');
            }
        });
    });
    <?php } ?>

    <?php if (!empty($output['available_rcb_amount'])) { ?>
    $('input[name="rcb_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="pd_pay"]').attr('checked')) {
        	if (<?php echo $output['available_rcb_amount']?> >= parseFloat($('#orderTotal').html())) {
            	$('input[name="pd_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="pd_pay"]').attr('disabled',false);
    	}
    });
    <?php } ?>

    <?php if (!empty($output['available_pd_amount'])) { ?>
    $('input[name="pd_pay"]').on('change',function(){
    	showPaySubmit();
    	if ($(this).attr('checked') && !$('input[name="rcb_pay"]').attr('checked')) {
        	if (<?php echo $output['available_pd_amount']?> >= parseFloat($('#orderTotal').html())) {
            	$('input[name="rcb_pay"]').attr('checked',false).attr('disabled',true);
        	}
    	} else {
    		$('input[name="rcb_pay"]').attr('disabled',false);
    	}
    });
    <?php } ?>

});
function disableOtherEdit(showText){
	$('a[nc_type="buy_edit"]').each(function(){
	    if ($(this).css('display') != 'none'){
			$(this).after('<font color="#B0B0B0">' + showText + '</font>');
		    $(this).hide();
	    }
	});
    disableSubmitOrder('请选择并保存收货地址信息');
}
function ableOtherEdit(){
    $('a[nc_type="buy_edit"]').show().next('font').remove();
    ableSubmitOrder();

}
function ableSubmitOrder(){
    $('#submitOrder').unbind('click');
    $('#submitOrder').on('click',function(){submitNext()}).css('cursor','').addClass('ncc-btn-acidblue');
}
function disableSubmitOrder(msg){
    $('#submitOrder').unbind('click').css('cursor','not-allowed').removeClass('ncc-btn-acidblue');
    $('#submitOrder').on('click',function(){if(msg){alert(msg);}});
}
//------------------------------------------------------------
//使用代金券
  $('#bt_voucher_user').click(function(){
	  var voucher_code=$('#voucher_code').val();
	  $('#voucher_msg').text('');
	  if(voucher_code!=''){

		  var total_price=$('em[nc_type="eachStoreTotal"]').text();
		  var voucher_url='/wap_shop/?act=buy&op=voucher_code_user&voucher_code='+voucher_code+'&total_price='+total_price;
		  $.getJSON(voucher_url,function(data){
			  if(data.msg==false){
				  $('#voucher_msg').text(data.message);
				  $('#voucher_user').val('');
			  }else if(data.msg==true){
				  var items = data.price_str.split('|');
				  var store=items[1];
				  var vacher_price=parseInt(items[2]);
  			 	  var new_tatal= parseInt($('#eachStoreGoodsTotal_'+store).text())-vacher_price;
				  $('#voucher_msg').text('-'+number_format(items[2],2));
				  $('#voucher_user').val(voucher_code);
				  $('#orderTotal').text(number_format(new_tatal,2));
			  }
		});//return false;
	  }else{
		  $('#voucher_msg').text('请输入代金券编码');
	  }
});
  </script>

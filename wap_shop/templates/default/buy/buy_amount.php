<?php defined('InShopNC') or exit('Access Invalid!');?>

<div style="width:100%;margin:20px 0 10px 0">
		<a href="javascript:void(0)" id='submitOrder' style="margin:0px 20px 0px 0px;line-height:1.5" class="qz-padding ui-btn-lg ui-btn-primary"><?php echo $lang['cart_index_submit_order'];?></a>
    </div>
</div>
<script>
function submitNext(){

	if (!SUBMIT_FORM) return;

	if ($('input[name="cart_id[]"]').size() == 0) {
		alert('所购商品无效');
		return false;
	}
	if($('input[name="fcode"]').size() == 1&&$('input[name="fcode"]').val()==''){
		alert('请输入F码');$('input[name="fcode"]').focus();
		return false;
	}
	if ($('input[name="fcode"]').size() == 1 && $('#fcode_callback').val() != '1') {
		alert('请输入并使用F码');$('input[name="fcode"]').focus();
		return false;
	}
   <?php if ($output['has_virtual']) : ?>
   if ($('#buyer_phone').val() == '' || !/^1[\d]{10}$/.test($('#buyer_phone').val())) {
      alert('请输入正确的手机号码[电子兑换码]');
      $('#buyer_phone').focus();
      return false;
   }
   <?php endif; ?>

    if ($('#address_id').val() == ''){
        alert('<?php echo $lang['cart_step1_please_set_address'];?>');
        return false;
	}
	if ($('#buy_city_id').val() == '') {
		alert('正在计算运费,请稍后');
		return false;
	}
	if (($('input[name="pd_pay"]').attr('checked') || $('input[name="rcb_pay"]').attr('checked')) && $('#password_callback').val() != '1') {
		alert('使用充值卡/预存款支付，需输入支付密码并使用');
		return false;
	}
    var voucher_check=false;
    $('select[nctype="voucher"]').each(function(){
        if ($(this).val() == '') {
        } else {
            var items = $(this).val().split('|');
            var total = $('#eachStoreGoodsTotal_'+items[1]).text();
            if(parseFloat(total)<parseFloat(items[4])) {
                voucher_check=true;
            }
        }
    });
    if(voucher_check) {
        alert('该代金券不符合用券要求，请重新选择！');
        return false;
    }
	SUBMIT_FORM = false;

	$('#order_form').submit();
}
$(function(){
    $(document).keydown(function(e) {
        if (e.keyCode == 13) {
        	submitNext();
        	return false;
        }
    });
	$('#submitOrder').on('click',function(){
        submitNext()
    });
	calcOrder();
});
</script>

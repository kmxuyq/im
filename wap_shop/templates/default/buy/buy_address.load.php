<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/address.css" />
<div class="qz-padding qz-background-white qz-top-b qz-bottom-b clearfix">
	<?php foreach((array)$output['address_list'] as $k=>$val){ ?>
	<label for="addr_<?php echo $val['address_id']; ?>">
	<span class="qz-fl">
		<?php echo $val['true_name'];?><br>
		<?php echo intval($val['dlyp_id']) ? '[自提服务站] ' : '';?><?php echo $val['area_info']; ?>&nbsp;<?php echo $val['address']; ?><br>
		</span>
        <span class="qz-fr">
		<?php echo $val['mob_phone'] ? $val['mob_phone'] : $val['tel_phone'];?>
		<input address="<?php echo intval($val['dlyp_id']) ? '[自提服务站] ' : '';?>
		<?php echo $val['area_info'].'&nbsp;'.$val['address']; ?>"
		 true_name="<?php echo $val['true_name'];?>"
		  id="addr_<?php echo $val['address_id']; ?>"
		   nc_type="addr"
		   type="radio" class="radio"
		   city_id="<?php echo $val['city_id']?>"
		   area_id=<?php echo $val['area_id'];?>
		   name="addr"
		   value="<?php echo $val['address_id']; ?>"
		   phone="<?php echo $val['mob_phone'] ? $val['mob_phone'] : $val['tel_phone'];?>"
		   <?php echo $val['is_default'] == '1' ? 'checked' : null; ?> />
		   <a href="javascript:void(0);" onclick="delAddr(<?php echo $val['address_id']?>);" class="del">[ 删除 ]</a>
         <a href="javascript:void(0);" onclick="editAddr(<?php echo $val['address_id']?>);" class="del">[ 编辑 ]</a>
		</span>
		<div class="qz-bk10"></div>
		</label>
		<?php }?>
		<div class="qz-bk10"></div>
		<span class="qz-fl">
		<!--使用新地址 start-->
		<input value="0" nc_type="addr" id="add_addr" type="radio" name="addr">
    <label for="add_addr">使用新地址</label>
    <?php if (C('delivery_isuse')) { ?>
    <!--&nbsp;<label ><a class="del" href="<?php echo urlShop('member_address','address');?>" target="_blank">使用自提服务站 </a>--></label>
    <?php } ?>
		<!--使用新地址 end-->
		</span>
		<div class="qz-bk10"></div>
		<div id="add_addr_box"><!-- 存放新增地址表单 --></div>
	</div>

<div class="hr16"> <a id="hide_addr_list" class="ui-btn-lg ui-btn-primary" href="javascript:void(0);"><?php echo $lang['cart_step1_addnewaddress_submit'];?></a></div>

<script type="text/javascript">
function delAddr(id){
    $('#addr_list').load(SITEURL+'/index.php?act=buy&op=load_addr&id='+id);
}
function editAddr(id){
   $('#add_addr').attr('checked',true);
   $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr&id='+id);
}
$(function(){
    function addAddr() {
        $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr');

    }
    $('input[nc_type="addr"]').on('click',function(){
        if ($(this).val() == '0') {
            $('.address_item').removeClass('ncc-selected-item');
            $('#add_addr_box').load(SITEURL+'/index.php?act=buy&op=add_addr');
        } else {
            $('.address_item').removeClass('ncc-selected-item');
            $(this).parent().addClass('ncc-selected-item');
            $('#add_addr_box').html('');
        }
    });
    $('#hide_addr_list').on('click',function(){

        if ($('input[nc_type="addr"]:checked').val() == '0'){
            submitAddAddr();
        } else {
            if ($('input[nc_type="addr"]:checked').size() == 0) {
                alert('请选择一个收货地址');
                return false;
            }
            var city_id = $('input[name="addr"]:checked').attr('city_id');
            var area_id = $('input[name="addr"]:checked').attr('area_id');
            var addr_id = $('input[name="addr"]:checked').val();
            var true_name = $('input[name="addr"]:checked').attr('true_name');
            var address = $('input[name="addr"]:checked').attr('address');
            var phone = $('input[name="addr"]:checked').attr('phone');
            showShippingPrice(city_id,area_id);

            hideAddrList(addr_id,true_name,address,phone);
        }
    });
    if ($('input[nc_type="addr"]').size() == 1){
        $('#add_addr').attr('checked',true);
        addAddr();
    }
    //----------------

});

</script>

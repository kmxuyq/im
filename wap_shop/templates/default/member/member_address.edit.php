<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>添加地址</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/address_ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/address_member_style.css" />
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/TouchSlide.1.1.js" ></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>	
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color"><?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?></h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<form method="post" action="index.php?act=member_address&op=address" id="address_form" target="_parent">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['address_info']['address_id'];?>" />
<div class="address_wrap">
    <div class="address_item">
        <div class="address_tt"><?php echo $lang['member_address_input_name'];?></div>
        <input class="address_ipt" type="text" name="true_name" value="<?php echo $output['address_info']['true_name'];?>"/>
    </div>
    <div class="address_item">
        <div class="address_tt"><?php echo $lang['member_address_mobile_num'];?></div>
        <input class="address_ipt" type="text" name="mob_phone" value="<?php echo $output['address_info']['mob_phone'];?>" />
    </div>
    <div class="address_item">
        <div class="address_tt"><?php echo $lang['member_address_phone_num'];?></div>
        <input class="address_ipt" type="text" name="tel_phone" value="<?php echo $output['address_info']['tel_phone'];?>" />
    </div>
    <div class="address_item" >
	    <div class="address_tt"><?php echo $lang['member_address_location'];?></div>
		<div id="region">
		 <input type="hidden" value="<?php echo $output['address_info']['city_id'];?>" name="city_id" id="city_id">
            <input type="hidden" name="area_id" id="area_id" value="<?php echo $output['address_info']['area_id'];?>" class="area_ids" />
            <input type="hidden" name="area_info" id="area_info" value="<?php echo $output['address_info']['area_info'];?>" class="area_names" />
        <?php if(!empty($output['address_info']['area_id'])){?>
        <span class="address_tt"><?php echo $output['address_info']['area_info'];?></span>
		<input type="button" value="<?php echo $lang['nc_edit'];?>" class="edit_region" />
        <select style="display:none;" class="valid">
        </select>
        <?php }else{ ?>
	    <select >
		
        </select>
		<?php } ?>
        </div>		
    </div>
    <div class="address_item">
        <div class="address_tt"><?php echo $lang['member_address_address'];?></div>
        <input class="address_ipt" type="text"  name="address" value="<?php echo $output['address_info']['address'];?>" />
    </div>
    <div class="info_setting_wrap">
        <div class="setting_cell">
            <p class="bigFont">设置为默认收货地址</p>
            <input type="checkbox"  <?php if ($output['address_info']['is_default']) echo 'checked';?> name="is_default" id="is_default" value="1"/>
        </div>
    </div>
    <input class="address_submit" id="form_submit" type="submit" value="<?php if($output['type'] == 'add'){?><?php echo $lang['member_address_new_address'];?><?php }else{?><?php echo $lang['member_address_edit_address'];?><?php }?>"/>
</div>
</form>
<script>
var SITEURL = "<?php echo WAP_SHOP_SITE_URL; ?>";
$(document).ready(function(){
	regionInit("region");
    $('#address_form').validate({
    	submitHandler:function(form){
    		if ($('select[class="valid"]').eq(1).val()>0) $('#city_id').val($('select[class="valid"]').eq(1).val());
    		ajaxpost('address_form', '', '', 'onerror');
    	},
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        rules : {
            true_name : {
                required : true
            },
            area_id : {
                required : true,
                min   : 1,
                checkarea : true
            },
            address : {
                required : true
            },
            tel_phone : {
                required : check_phone,
                minlength : 6,
				maxlength : 20
            },
            mob_phone : {
                required : check_phone,
                minlength : 11,
				maxlength : 11,                
                digits : true
            }
        },
        messages : {
            true_name : {
                required : '<?php echo $lang['member_address_input_receiver'];?>'
            },
            area_id : {
                required : '<?php echo $lang['member_address_choose_location'];?>',
                min  : '<?php echo $lang['member_address_choose_location'];?>',
                checkarea  : '<?php echo $lang['member_address_choose_location'];?>'
            },
            address : {
                required : '<?php echo $lang['member_address_input_address'];?>'
            },
            tel_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_phone_rule'];?>',
				maxlength: '<?php echo $lang['member_address_phone_rule'];?>'
            },
            mob_phone : {
                required : '<?php echo $lang['member_address_phone_and_mobile'];?>',
                minlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
				maxlength: '<?php echo $lang['member_address_wrong_mobile'];?>',
                digits : '<?php echo $lang['member_address_wrong_mobile'];?>'
            }
        },
        groups : {
            phone:'tel_phone mob_phone'
        }
    });
});
function check_phone(){
    return ($('input[name="tel_phone"]').val() == '' && $('input[name="mob_phone"]').val() == '');
}
$(function(){
		/*表单验证*/
		/*$('#form_submit').click(function(){
			var name = $.trim($('input[name="true_name"]').val());
			var mob = $.trim($('input[name="mob_phone"]').val());
			var tel = $.trim($('input[name="tel_phone"]').val());
			var city_id = $('input[name="city_id"]').val();
			var area_id = $('input[name="area_id"]').val();
			var area_info = $('input[name="area_info"]').val();
			var address = $.trim($('input[name="address"]').val());
			var Error = '';
		    if(name == ''){
				Error += '\n请填写真实姓名';
			}
			if(mob.length != 11){
				Error += '\n请输入正确的手机号码'
			}
			if(mob == ''){
				Error += '\n手机号码不能为空'
			}
			if(tel.length <6 || tel.length >20 ){
				Error +='\n请输入正确的电话号码'
			}
			if(address == ''){
				Error+='\n请填写街道地址'
			}
			if(Error != ''){
				alert('错误提示：'+Error)
			}else{
				$('#address_form').submit()
			}
		})*/
        $('.setting_cell input[type="checkbox"]').click(function(){
            if( $(this).parent().hasClass('on')){
                $(this).parent().removeClass('on');
            }else{
                $(this).parent().addClass('on');
            }
        })
    });

</script>
<style>
body{ background:#f5f5f5;}
#region select {
	  margin-bottom: 20px;
  border: 1px #d0d0d0 solid;
  height: 35px;
  line-height: 35px;
  width: 100%;
  text-indent: 5px;
}
/*苹果按钮*/
input[type=text],input[type=button],input[type=password]{-webkit-appearance: none;}
input[type=text],input[type=button],input[type=submit]{-webkit-appearance: none;}
</style>
</body>
</html>
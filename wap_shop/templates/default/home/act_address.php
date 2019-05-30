<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>活动报名信息</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/TouchSlide.1.1.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>	
<style>
body{ background:#f5f5f5;}
.address_item{clear: both;margin-bottom: 5px;}
.address_item .address_tt {font-size: 16px;font-family: "Microsoft Yahei";line-height: 30px;}
.address_item #region select {margin-bottom: 10px;border: 1px #d0d0d0 solid;height: 42px;line-height: 42px;width: 100%;/* 	text-align:center; */text-indent: 5px;float: right;display: block;}
.address_item #region span{margin-bottom: 20px;height: 42px;line-height: 42px;float: right;width: 90%;border: 1px #d0d0d0 solid;border: 1px #d0d0d0 solid;padding-left: 10px;}
.address_item #region span i{ display: block;width: 8px;height: 42px; line-height: 42px;background: transparent url("<?php echo SHOP_TEMPLATES_URL?>/images/sj_right_03.png") no-repeat scroll 0px 0px / 8px auto;margin-left: 12px;position: absolute;right: 12px;}
   
</style>
</head>

<body class="bg_gray">
<header class="az-header az-header-positive az-header-background qz-header-bb noProsition">
   <a href=" javascript:history.back();">
    <i class="az-icon-return"></i>
   </a>
   <h1 class="az-color">活动报名信息</h1></header>
<form  method="POST" name="addr_active_form" id="addr_active_form" action="index.php?act=active">
    <input type="hidden" value="active" name="act">
    <input type="hidden" value="index" name="op">
    <input type="hidden" name="form_submit" value="ok"/>
    <input type="hidden" name="type" value="<?php echo $_GET['type']?>">
    <input type="hidden" name="goods_id" value="<?php echo $output['goods_type']['goods_id']?>">
    <input type="hidden" name="wx" value="<?php echo $_GET['wx'];?>">
    <input type="hidden" name="cate_id" value="<?php echo $_GET['cate_id'];?>">
<div class=" az_active_top"></div> 
<div class="cool_active_msg">
	<div class="cool_msg_line">
    	<div class="title">*姓名 ：</div>
        <div class="cons">
        	<input type="text" id="true_name" name="true_name" value="<?php echo $output['member_info']['member_truename']; ?>"  placeholder="请填写真实姓名"/>
        </div>
    </div>
	
    <div class="az_address_text" id="m_sex" style="padding: 0 40px;">
        <span class="text fl az_font">性别：</span>
        <p style="display: none;" class="sex_choose <?php if($output['member_info']['member_sex']==3 or ($output['member_info']['member_sex']!=2 and $output['member_info']['member_sex']!=1)) { ?>on<?php } ?>">
			<input id="member_sex"  class="choose_radio"  type="radio" name="member_sex" value="3" <?php if($output['member_info']['member_sex']==3 or ($output['member_info']['member_sex']!=2 and $output['member_info']['member_sex']!=1)) { ?>checked="checked"<?php } ?> />              
            <span>保密</span>
        </p>
        <p class="sex_choose <?php if($output['member_info']['member_sex']==2) { ?>on<?php } ?>">
			<input id="member_sex" class="choose_radio" type="radio" name="member_sex" value="2" <?php if($output['member_info']['member_sex']==2) { ?>checked="checked"<?php } ?> />
            <span>女</span>
        </p>
        <p class="sex_choose <?php if($output['member_info']['member_sex']==1) { ?>on<?php } ?>">
			<input id="member_sex"  class="choose_radio" type="radio" name="member_sex" value="1" <?php if($output['member_info']['member_sex']==1) { ?>checked="checked"<?php } ?> /> 
            <span>男</span>
        </p>
    </div>
    <div class="cool_msg_line">
    	<div class="title">生日：</div>
        <div class="cons">
        	<input type="date" name="birthday" value="<?php echo $output['member_info']['member_birthday']?>" placeholder="例如：1990-01-01"/>
        </div>
    </div>
    
	<div class="cool_msg_line">
    	<div class="title">*电话号码 ：</div>
        <div class="cons">
        	<input type="tel" id="mob_phone" name="mob_phone" value="<?php echo $output['member_info']['member_mobile']?>" placeholder="请填写您的电话"/>
        </div>
    </div>
    <?php if($output['goods_type']['is_virtual']==1){?>
    <div class="cool_msg_line">
    	<div class="title">*兑换码 ：</div>
        <div class="cons">
        	<input type="tel" id="az_code" name="az_code"  placeholder="请填写您的兑换码"/>
        </div>
    </div>
   <?php }?>
   
        <div class="address_item cool_msg_line" >
	    <div class="address_tt title">*所在省市 ：</div>
		<div id="region" class="cons" style="clear:both;display:block;height:auto;">
        	<input type="hidden" value="<?php echo $output['member_info']['member_cityid'];?>" name="city_id" id="city_id">
            <input type="hidden" value="<?php echo $output['member_info']['member_areaid'];?>" name="area_id" id="area_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['member_info']['member_areainfo'];?>" name="area_info" id="area_info" class="area_names" />
        	<input type="hidden" value="0" name="privacy[area]" />
        <?php if(!empty($output['member_info']['member_areaid'])){?>
        <span class="address_tt"><?php echo $output['member_info']['member_areainfo'];?><i></i></span>
		<input type="button" value="编辑" style="background-color: #F5F5F5; width: 10%; height: 42px; line-height: 42px; border-left: solid 1px #E7E7E7;float: right; cursor: pointer;" class="edit_region" />
        <select style="display:none;" class="valid">
        </select>
        <?php }else{ ?>
	    <select >
        </select>
		<?php } ?>
		<div style="clear:both"></div>
        </div>	
        <div style="clear:both"></div>
    </div>
<div style="clear:both"></div>

	<div class="cool_msg_line autow" style="padding-top:5px;">
    	<div class="title">*详细地址 ：</div>
        <div class="cons">
        	<textarea rows="2" id="address" name="address" value="<?php echo $output['member_info']['address']?>" placeholder="请填写地址"></textarea>
        </div>
    </div>
	<div class="cool_msg_line autow" style="padding-top:5px;">
    	<div class="title">特别事项 ：</div>
        <div class="cons">
        	<textarea rows="2" name="content" placeholder="特别事项"></textarea>
        </div>
    </div>
</div>
<div  class="az_active_msg_save">
<input type="submit" class="az_input_sub" value="保存" id="hide_addr_list">
</div>
</form>
<script>
$(function(){
	regionInit("region");
	
	$('#region select').eq(0).click(function(){
		$('#city_id').val($(this).val());
		$('#area_id').val('');
	});
	$('#region select').eq(1).click(function(){
		$('#area_id').val($(this).val());
	});
	/***选择**/
    $(".sex_choose").click(function(){
        if(!$(this).hasClass('on')){
            $(".sex_choose").removeClass('on');
            $(this).addClass('on');
            $(this).find('.choose_radio').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });
	$(".edit_region").click(function(){
		$("#addressgap").show();
	});
    
    $(".follow_choose").click(function(){
        if(!$(this).hasClass('on')){
            $(".follow_choose").removeClass('on');
            $(this).addClass('on');
            $(this).find('.choose_radio').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });
    $('#hide_addr_list').click(function(){
        var true_name = $('#true_name').val();
        var city_id = $('#city_id').val();
        var area_id = $('#area_id').val();
        var area_info = $('#area_info').val();
        var mob_phone = $('#mob_phone').val();
        var az_code =$('#az_code').val();
        var address = $('#address').val();
        var is_virtual = <?php echo $output['goods_type']['is_virtual'];?>;
        var  member_sex=$('#member_sex').val();
//         alert(true_name);
        if (true_name == '') {
            alert("请填写您的真实姓名！");
            $('#true_name').focus();
            return false;
        }
        /* if(member_sex==3||member_sex==''){
        	alert("请选择性别！");
            $('#member_sex').focus();
            return false;
            } */
        if (mob_phone == '') {
            alert("请填写电话号码！");
            $('#mob_phone').focus();
            return false;
        }
         if(az_code==''&& is_virtual=='1'){
            alert("请填写兑换码！");
            $('#az_code').focus();
            return false;
            } 
        if (city_id == '') {
            alert("请选择所在省份！");
            $('#city_id').focus();
            return false;
        }
        if (area_id == '') {
            alert("请选择所在城市！");
            $('#area_id').focus();
            return false;
        }
        if (area_info == '') {
            alert("请选择所在区域！");
            $('#area_info').focus();
            return false;
        }
        if (address == '') {
            alert("请填写详细地址！");
            $('#address').focus();
            return false;
        }
        return true;
        //$('#addr_active_form').submit();
       
    });
});
/* function checkPhone(){
    return ($('input[name="mob_phone"]').val() == '');
}
function submitAddAddr(){
    if ($('#addr_active_form').valid()){
        $('#buy_city_id').val($('#region').find('select').eq(1).val());
        $('#city_id').val($('#region').find('select').eq(1).val());
        var datas=$('#addr_active_form').serialize();
        $.post('index.php?act=active',datas,function(data){
            if (data.state){
                var true_name = $.trim($("#true_name").val());
                var mob_phone = $.trim($("#mob_phone").val());
            	var area_info = $.trim($("#area_info").val());
            	var address = $.trim($("#address").val());
            	showShippingPrice($('#city_id').val(),$('#area_id').val());
            	hideAddrList(data.addr_id,true_name,area_info+'&nbsp;&nbsp;'+address,(mob_phone != '' ? mob_phone : tel_phone));
            }else{
                alert(data.msg);
            }
        },'json');
    }else{
        return false;
    }
} */
</script>



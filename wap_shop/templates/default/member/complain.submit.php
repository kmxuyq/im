<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>交易投诉申请</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/az.js"></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">交易投诉申请</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="complaint_schedule">
    <ul class="list">
        <li class="active">
            <span><?php echo $lang['complain_state_new'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span><?php echo $lang['complain_state_appeal'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span><?php echo $lang['complain_state_talk'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span><?php echo $lang['complain_state_handle'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span><?php echo $lang['complain_state_finish'];?></span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<form action="index.php?act=member_complain&op=complain_save" method="post" id="add_form" enctype="multipart/form-data">
    <input name="input_order_id" type="hidden" value="<?php echo $output['order']['order_id'];?>" />
    <input name="input_goods_id" type="hidden" value="<?php echo $output['goods_id'];?>" />
<div class="complaint_content">
    <h3 class="complaint_tt"><?php echo $lang['complain_subject_select'].$lang['nc_colon'];?></h3>
    <ul class="complaint_choose">
	<?php if (is_array($output['subject_list']) && !empty($output['subject_list'])) { ?>
    <?php $i=0; foreach($output['subject_list'] as $subject) {?>
        <li class="<?php if($i == 0){ echo 'on';}?>">
            <div><?php echo $subject['complain_subject_content']?></div>
            <p><?php echo $subject['complain_subject_desc'];?> </p>
            <input name="input_complain_subject" checked="<?php if($i == 0){ echo 'checked';}?>" type="radio" value="<?php echo $subject['complain_subject_id'].','.$subject['complain_subject_content']?>" class="adchek"/>
        </li>
	<?php $i++; } ?>
    <?php } ?>
    </ul>
</div>

<div class="service_wrap ">
    <div class="service_msg">
        <div class="text"><?php echo $lang['complain_content'].$lang['nc_colon'];?></div>
        <textarea name="input_complain_content" id="input_complain_content" cols="30" rows="10"></textarea>
    </div>
    <!--退货上传照片-->
    <div class="drawback_uploadpic">
        <div class="tt"><?php echo $lang['complain_evidence_upload'].$lang['nc_colon'];?></div>
        <ul class="drawback_uploadpic_lst">
            <li class="">
			<p class="uploadpic"><img id="img_0" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic1" name="input_complain_pic1" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img id="img_1" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
			<div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic2" name="input_complain_pic2" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img id="img_2" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
            <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic3" name="input_complain_pic3" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
        </ul>
        
        <div class="service_subBtn">
        <input type="button" id="submit_button" class="public_btn1" value="<?php echo $lang['complain_text_submit'];?>">
            <input type="button" class="public_btn2" onclick="javascript:history.back();" value="取消">
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
//仅退款form表单验证
var PIC_EXT = new Array('.jpg','.jpeg','.gif','.png');
$(function(){
	$('#submit_button').click(function(){
		//var Obj = $('#post_form1');
		var input_complain_content = $('#input_complain_content').val();
        var pic1 = $('input[name="input_complain_pic1"]').val();
		var refund_pic1 = pic1.substr(pic1.indexOf('.'));
        var pic2 = $('input[name="input_complain_pic2"]').val();
		var refund_pic2 = pic2.substr(pic2.indexOf('.'));
        var pic3 = $('input[name="input_complain_pic3"]').val();
        var refund_pic3 = pic3.substr(pic3.indexOf('.')); 		
		var Error = '';
	    if(input_complain_content == ''){
			Error +='请填写投诉内容。';
		}
		if(pic1 !== ''){
			if(jQuery.inArray(refund_pic1, PIC_EXT) === -1){
				Error +="\n只允许上传'jpg','jpeg','gif','png'格式图片。";
			}
		}
		if(pic2 !== ''){
			if(jQuery.inArray(refund_pic2, PIC_EXT) === -1){
				Error +="\n只允许上传'jpg','jpeg','gif','png'格式图片。";
			}
		}
		if(pic3 !== ''){
			if(jQuery.inArray(refund_pic3, PIC_EXT) === -1){
				Error +="\n只允许上传'jpg','jpeg','gif','png'格式图片。";
			}
		}
		if( Error === ''){
			$('#add_form').submit();
		}else{
			alert(Error)
		}
	})
})
</script>
<script>
$(function(){
    $('.complaint_choose .adchek').click(function(){
        var this_parent=$(this).parent();
        if($(this).is(':checked')){
            $('.complaint_choose li').removeClass('on');
            this_parent.addClass('on');
        }
    })
})
</script>

<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













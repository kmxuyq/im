<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>申请退款</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/az.js"></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退款</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 若您对订单进行支付后想取消购买且与商家达成一致退款，请填写<span class="tip_red">“订单退款”</span>内容并提交。</p>
    <p class="text">2. 成功完成退款/退货；经过商城审核后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</p>
</div>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>买家<br>申请退款</span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>商家处理<br>退款申请 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>平台审核<br>退款完成 </span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<!--退货原因-->
        <form id="post_form1" enctype="multipart/form-data" method="post" action="index.php?act=member_refund&op=add_refund_all&order_id=<?php echo $output['order']['order_id']; ?>&goods_id=<?php echo $output['goods']['rec_id']; ?>">
          <input type="hidden" name="form_submit" value="ok" />
<div class="service_wrap ">
    <div class="service_type_select">
        <div class="text">退款原因:取消订单全部退款</div>
    </div>

    <div class="service_type_select">
        <div class="text">退款金额</div>
        <div  class="drawback_tt">
            <span><?php echo ncPriceFormat($output['order']['order_amount']); ?>元</span>
        </div>
    </div>
    <div class="service_msg">
        <div class="text">退款原因</div>
        <textarea name="buyer_message" cols="30" rows="10"></textarea>
    </div>
    
    <div class="drawback_uploadpic">
        <div class="tt">退款凭证</div>
        <ul class="drawback_uploadpic_lst">
            <li class="">
			<p class="uploadpic"><img id="img_0" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic1" name="refund_pic1" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img id="img_1" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
			<div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic2" name="refund_pic2" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img id="img_2" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
            <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" id="refund_pic3" name="refund_pic3" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
        </ul>
        
        <div class="service_subBtn">
        	<input type="button" id="submit-1" class="public_btn1" value="确认并提交">
            <input type="button" class="public_btn2" onClick="javascript:history.back();" value="取消返回">
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
//仅退款form表单验证
var PIC_EXT = new Array('.jpg','.jpeg','.gif','.png');
$(function(){
	$('#submit-1').click(function(){
		//var Obj = $('#post_form1');
		var buyer_message = $('textarea[name="buyer_message"]').val() //退款说明
        var pic1 = $('input[name="refund_pic1"]').val();
		var refund_pic1 = pic1.substr(pic1.indexOf('.'));
        var pic2 = $('input[name="refund_pic2"]').val();
		var refund_pic2 = pic2.substr(pic2.indexOf('.'));
        var pic3 = $('input[name="refund_pic3"]').val();
        var refund_pic3 = pic3.substr(pic3.indexOf('.')); 		
		var Error = '';

	    if(buyer_message == ''){
			Error +='\n请填写退款说明。';
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
			$('#post_form1').submit();
		}else{
			alert(Error)
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
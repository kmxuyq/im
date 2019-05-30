<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>申请退款</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
    
<script type="text/javascript" src="js/jquery-1.11.0.min.js" ></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>	
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<style>
body{ background:#f5f5f5;}
.schedule .list4 li {
    float: left;
    width: 25%;
    position: relative;
    top: -46px;
}
.schedule .list4 li:nth-of-type(1) {
    left: -14%;
}
.schedule .list4 li:nth-of-type(2) {
    left: -7%;
}
.schedule .list4 li:nth-of-type(3) {
    right: 0%;
}
.schedule .list4 li:nth-of-type(4) {
    right: -14%;
}
</style>

</head>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()" style="color:#10b0e6;font-size: 24px;"></i>
    <h1 class="qz-color">申请退款</h1>
</header>
<div class="refund_tip">
    <p class="text">操作提示：</p>
    <p class="text">1. 若您未收到货，或已收到货且与商家达成一致不退货仅退款时，请选择<span class="tip_red">“仅退款”</span>选项。</p>
    <p class="text">2. 若为商品问题，或者不想要了且与商家达成一致退货，请选择<span class="tip_red">“退货退款”</span>选项，退货后请保留物流底单。</p>
    <p class="text">3. 若提出申请后，商家拒绝退款或退货，可再次提交申请或选择<span class="tip_red">“商品投诉”</span>，请求商城客服人员介入。</p>
    <p class="text">4. 成功完成退款/退货；经过商城审核后，会将退款金额以<span class="tip_red">“预存款”</span>的形式返还到您的余额账户中（充值卡部分只能退回到充值卡余额）。</p>
</div>
<div class="schedule schedule1">
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

<div class="schedule az" style="display: none;">
    <ul class="list list4">
        <li class="active">
            <span>买家<br>申请退货</span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>商家处理<br>退货申请 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>买家退货<br>给商家 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>确认收货<br>平台审核 </span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<!--类型的选择-->
<div class="service_wrap ">
<div class="service_type_select">
    <div class="text">申请退款服务类型</div>
        <select class="service_select" id="az">
            <option value="1" checked="checked">仅退款</option>
            <option value="2">退货/退款</option>
        </select>
    </div>
</div>	

<!--仅退款原因-->
<form id="post_form1" enctype="multipart/form-data" method="post" action="index.php?act=member_refund&op=add_refund&order_id=<?php echo $output['order']['order_id']; ?>&goods_id=<?php echo $output['goods']['rec_id']; ?>">
<input type="hidden" name="form_submit" value="ok" />
<input type="hidden" name="refund_type" value="1" />
<div class="service_wrap ">
<h3>请填写退款申请</h3>
    <div class="service_type_select">
        <div class="text">退款原因</div>
        <select class="service_select" name="reason_id">
                <option value="">请选择退款原因</option>
                <?php if (is_array($output['reason_list']) && !empty($output['reason_list'])) { ?>
                <?php foreach ($output['reason_list'] as $key => $val) { ?>
                <option value="<?php echo $val['reason_id'];?>"><?php echo $val['reason_info'];?></option>
                <?php } ?>
                <?php } ?>
                <option value="0">其他</option>
              </select>
    </div>
    <div class="service_type_select">
        <div class="text">退款原因</div>
        <div  class="drawback_tt">
            <input class="ipt_text" type="text"  name="refund_amount"  value="<?php echo $output['goods']['goods_pay_price']; ?>"/>
            <span>(最多<?php echo $output['goods']['goods_pay_price']; ?>元)</span>
        </div>
        <div class="drawback_tip">退款金额不能超过可退金额</div>
    </div>
    <div class="service_msg">
        <div class="text">退款原因</div>
        <textarea  name="buyer_message" rows="3"></textarea>
    </div>
    <!--退货上传照片-->
    <div class="drawback_uploadpic">
        <div class="tt">退款凭证</div>
        <ul class="drawback_uploadpic_lst">
            <li class="">
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic1" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
			<div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic2" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
            <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic3" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
        </ul>
        
        <div class="service_subBtn">
            <input type="submit" class="public_btn1" value="确认并提交">
            <input type="button" class="public_btn2" onClick="javascript:history.back();" value="取消返回">
        </div>
    </div>
</div>
</form>

<form id="post_form2" style="display: none;" method="post" enctype="multipart/form-data" action="index.php?act=member_refund&op=add_refund&order_id=<?php echo $output['order']['order_id']; ?>&goods_id=<?php echo $output['goods']['rec_id']; ?>">
 <input type="hidden" name="form_submit" value="ok" />
 <input type="hidden" name="refund_type" value="2" />
<div class="service_wrap ">
<h3>请填写退货退/款申请</h3>
    <div class="service_type_select">
        <div class="text">退货/退款原因</div>
        <select class="service_select" name="reason_id">
                <option value="">请选择退货/退款原因</option>
                <?php if (is_array($output['reason_list']) && !empty($output['reason_list'])) { ?>
                <?php foreach ($output['reason_list'] as $key => $val) { ?>
                <option value="<?php echo $val['reason_id'];?>"><?php echo $val['reason_info'];?></option>
                <?php } ?>
                <?php } ?>
                <option value="0">其他</option>
       </select>
    </div>
    <div class="service_type_select">
        <div class="text">退货退款原因</div>
        <div  class="drawback_tt">
            <input class="ipt_text" type="text"  name="refund_amount"  value="<?php echo $output['goods']['goods_pay_price']; ?>"/>
            <span>(最多<?php echo $output['goods']['goods_pay_price']; ?>元)</span>
        </div>
        <div class="drawback_tip">退款金额不能超过可退金额</div>
    </div>
    <div class="service_type_select">
       <div class="text"><?php echo $lang['return_order_return'].$lang['nc_colon'];?></div>
       <div  class="drawback_tt">
            <input class="ipt_text" type="text"  name="goods_num"  value="<?php echo $output['goods']['goods_num']; ?>"/>
            <span class="error"></span>
        </div>
    </div>
    
    <div class="service_msg">
        <div class="text">退货/退款原因</div>
        <textarea name="buyer_message" rows="3"></textarea>
    </div>
    <!--退款/退货上传照片-->
    <div class="drawback_uploadpic">
        <div class="tt">退款/退货凭证</div>
        <ul class="drawback_uploadpic_lst">
            <li class="">
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic1" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
			<div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic2" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
            <li>
			<p class="uploadpic"><img src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""/></p>
            <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1" name="refund_pic3" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;"/>
                    <span class="error"></span> </p>
                </div>
            </li>
        </ul>
        
        <div class="service_subBtn">
            <input type="submit" class="public_btn1" value="确认并提交">
            <input type="button" class="public_btn2" onClick="javascript:history.back();" value="取消返回">
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(function(){
	$('#az').on('click',function(){
		if($("#az").attr("value")==1){
			$('.schedule1').show();
			$('#post_form1').show();
			$('.az').hide();
			$('#post_form2').hide();
		 }else {
			$('.az').show();
			$('#post_form2').show();
			$('.schedule1').hide();
			$('#post_form1').hide();
		}
	});
})
</script>
<script type="text/javascript">
$(function(){
    $('#post_form1').validate({
		errorPlacement: function(error, element){
			error.appendTo(element.nextAll('span.error'));
        },
		submitHandler:function(form){
			ajaxpost('post_form1', '', '', 'onerror')
		},
        rules : {
            reason_id : {
                required   : true
            },
            refund_amount : {
                required   : true,
                number   : true,
                min:0.01,
                max:<?php echo $output['goods']['goods_pay_price']; ?>
            },
            buyer_message : {
                required   : true
            },
            refund_pic1 : {
                accept : 'jpg|jpeg|gif|png'
            },
            refund_pic2 : {
                accept : 'jpg|jpeg|gif|png'
            },
            refund_pic3 : {
                accept : 'jpg|jpeg|gif|png'
            }
        },
        messages : {
            reason_id  : {
                required  : '<i class="icon-exclamation-sign"></i><?php echo '请选择退款原因';?>'
            },
            refund_amount  : {
                required  : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>',
                number   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>',
                min   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_min'];?> 0.01',
	            max   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>'
            },
            buyer_message  : {
                required   : '<i class="icon-exclamation-sign"></i>请填写退款说明'
            },
            refund_pic1: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            },
            refund_pic2: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            },
            refund_pic3: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            }
        }
    });
    $('#post_form2').validate({
		errorPlacement: function(error, element){
			error.appendTo(element.nextAll('span.error'));
        },
		submitHandler:function(form){
			ajaxpost('post_form2', '', '', 'onerror')
		},
        rules : {
            reason_id : {
                required   : true
            },
            refund_amount : {
                required   : true,
                number   : true,
                min:0.01,
                max:<?php echo $output['goods']['goods_pay_price']; ?>
            },
            goods_num : {
                required   : true,
                digits   : true,
                min:1,
                max:<?php echo $output['goods']['goods_num']; ?>
            },
            buyer_message : {
                required   : true
            },
            refund_pic1 : {
                accept : 'jpg|jpeg|gif|png'
            },
            refund_pic2 : {
                accept : 'jpg|jpeg|gif|png'
            },
            refund_pic3 : {
                accept : 'jpg|jpeg|gif|png'
            }
        },
        messages : {
            reason_id  : {
                required  : '<i class="icon-exclamation-sign"></i><?php echo '请选择退货退款原因';?>'
            },
            refund_amount  : {
                required  : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>',
                number   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>',
                min   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_min'];?> 0.01',
	            max   : '<i class="icon-exclamation-sign"></i><?php echo $lang['refund_pay_refund'];?> <?php echo $output['goods']['goods_pay_price']; ?>'
            },
            goods_num  : {
                required  : '<i class="icon-exclamation-sign"></i><?php echo $lang['return_add_return'];?> <?php echo $output['goods']['goods_num']; ?>',
                digits   : '<i class="icon-exclamation-sign"></i><?php echo $lang['return_add_return'];?> <?php echo $output['goods']['goods_num']; ?>',
                min   : '<i class="icon-exclamation-sign"></i><?php echo $lang['return_number_min'];?> 1',
	            max   : '<i class="icon-exclamation-sign"></i><?php echo $lang['return_number_max'];?> <?php echo $output['goods']['goods_num']; ?>'
            },
            buyer_message  : {
                required   : '<i class="icon-exclamation-sign"></i>请填写退货退款说明'
            },
            refund_pic1: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            },
            refund_pic2: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            },
            refund_pic3: {
                accept : '<i class="icon-exclamation-sign"></i>图片必须是jpg/jpeg/gif/png格式'
            }
        }
    });
});
</script>









<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>交易投诉申请</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()" style="color:#10b0e6;font-size: 24px;"></i>
    <h1 class="qz-color">交易投诉申请</h1>
</header>
<div class="complaint_schedule">
    <ul class="list">
        <li class="active" id="state_new">
            <span><?php echo $lang['complain_state_new'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="" id="state_appeal">
            <span><?php echo $lang['complain_state_appeal'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="" id="state_talk">
            <span><?php echo $lang['complain_state_talk'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="" id="state_handle">
            <span><?php echo $lang['complain_state_handle'];?></span>
            <b class="radius_dot"></b>
        </li>
        <li class="" id="state_finish">
            <span><?php echo $lang['complain_state_finish'];?></span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<!--投诉信息-->
<div class="refund_wrap">
    <div class="refund_tp"><?php echo $lang['complain_message'];?></div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo $lang['complain_accused'].$lang['nc_colon'];?><?php echo $output['complain_info']['accused_name'];?></p>
        <p class="refund_text_row"><?php echo $lang['complain_subject_content'].$lang['nc_colon'];?><?php echo $output['complain_info']['complain_subject_content'];?></p>
        <p class="refund_text_row"><?php echo $lang['complain_datetime'].$lang['nc_colon'];?><?php echo date('Y-m-d H:i:s',$output['complain_info']['complain_datetime']);?></p>
        <p class="refund_text_row"><?php echo $lang['complain_content'].$lang['nc_colon'];?><?php echo $output['complain_info']['complain_content'];?></p>
    </div>
</div>
<!--投诉证据-->
<div class="drawback_uploadpic">
   <?php if (is_array($output['complain_pic']) && !empty($output['complain_pic'])) { ?>
    <div class="tt"><?php echo $lang['complain_evidence'].$lang['nc_colon'];?></div>
    <ul class="drawback_uploadpic_lst">
	
	<?php foreach ($output['complain_pic'] as $key => $val) { ?>
    <?php if(!empty($val)){ ?>
        <li class="">
            <p class="uploadpic">
			<a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain/'.$val;?>" nctype="nyroModal" rel="gal">
			<img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain/'.$val;?>" alt="">
			</a>
			</p>
        </li>
	<?php } ?>
	<?php } ?>
	</ul>
	<?php } else { ?>
	<div class="tt"><?php echo $lang['complain_pic_none'];?>&nbsp<a id="post_add_open">上传凭证</a></div>
    
	 <?php if((intval($output['complain_info']['complain_state']) < 99)) { ?>
	     <form style="display: none" id="post_add_pic_form" method="post" action="index.php?act=member_complain&op=complain_add_pic&complain_id=<?php echo $output['complain_info']['complain_id']; ?>" enctype="multipart/form-data">
         <input type="hidden" name="form_submit" value="ok" />
          <ul class="drawback_uploadpic_lst">
		  <li class="">
                 <input name="input_complain_pic1" type="file" />
            </li>
            <li>
                 <input name="input_complain_pic2" type="file" />
            </li>
            <li>
                 <input name="input_complain_pic3" type="file" />
            </li>
			</ul>
		
	<div class="service_subBtn">
            <input type="button" id="add_pic_submit_button" class="public_btn1" value="提交">
            <input type="button" id="add_pic_submit_cancel" class="public_btn2" value="取消">
    </div>
	</form>
	<?php } ?>
	<?php } ?>
</div>
<?php if ($output['complain_info']['complain_state'] >= 30) { ?>
<?php if ($output['complain_info']['appeal_datetime'] > 0) { ?>
<!--投诉信息-->
<div class="refund_wrap">
    <div class="refund_tp"><?php echo $lang['complain_appeal_message'];?></div>
    <div class="wrap_padding">
        <p class="refund_text_row"><?php echo $lang['complain_appeal_datetime'].$lang['nc_colon'];?><?php echo date('Y-m-d H:i:s',$output['complain_info']['appeal_datetime']);?></p>
        <p class="refund_text_row"><?php echo $lang['complain_appeal_content'].$lang['nc_colon'];?><?php echo $output['complain_info']['appeal_message'];?></p>
    </div>
</div>
<!--投诉证据-->

<div class="drawback_uploadpic">
    <div class="tt"><?php echo $lang['complain_appeal_evidence'].$lang['nc_colon'];?></div>
    <ul class="drawback_uploadpic_lst">
	<?php if (is_array($output['appeal_pic']) && !empty($output['appeal_pic'])) { ?>
      <?php foreach ($output['appeal_pic'] as $key => $val) { ?>
      <?php if(!empty($val)){ ?>
	  <li class="">
            <p class="uploadpic">
			<a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain/'.$val;?>" nctype="nyroModal" rel="gal">
			<img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/complain/'.$val;?>" alt="">
			</a>
			</p>
        </li>
	  <?php } ?>
	  <?php } ?>
	<?php } else { ?>
    <?php echo $lang['complain_pic_none'];?>
    <?php } ?>
    </ul>
</div>
<?php } ?>

<div class="refund_tp"><?php echo $lang['talk_detail'];?></div>
<div class="chat_log">
    <div class="chat_log_tt"><?php echo $lang['talk_list'].$lang['nc_colon'];?></div>
    <div class="chat_log_content" id="div_talk" >
        <div class="text1">
            <div class="times">2015-09-01 15:28:47</div>
            <p class="dec">管理员(admin)说:管理员发布对话发布对话发布对话发布对话</p>
        </div>
    </div>
	<?php if(intval($output['complain_info']['complain_state']) <= 40) { ?>
    <div class="service_msg">
        <div class="text"><?php echo $lang['talk_send'].$lang['nc_colon'];?></div>
        <textarea name="" id="complain_talk" cols="30" rows="10"></textarea>
    </div>
    <div class="service_subBtn2">
        <input type="button" id="btn_publish" class="public_btn1" value="<?php echo $lang['talk_send'];?>">
        <input type="button" id="btn_refresh" class="public_btn1" value="<?php echo $lang['talk_refresh'];?>">
        <input type="button"  id="btn_handle" class="public_btn1" value="<?php echo $lang['handle_submit'];?>">
    </div>
	<form action="index.php?act=member_complain&op=apply_handle" method="post" id="handle_form">
    <input name="input_complain_id" type="hidden" value="<?php echo $output['complain_info']['complain_id'];?>" />
    </form>
	<?php } ?>
</div>
<?php } ?>

<!-- 处理意见 -->
<?php if ($output['complain_info']['complain_state'] == 99) { ?>
<div class="refund_tp "><?php echo $lang['final_handle_message'];?></div>
<div class="chat_log">
    <p><?php echo '处理意见'.$lang['nc_colon'];?><?php echo $output['complain_info']['final_handle_message'];?></p>
    <p><?php echo $lang['final_handle_datetime'].$lang['nc_colon'];?><?php echo date('Y-m-d H:i:s',$output['complain_info']['final_handle_datetime']);?></p>
</div>
<?php } ?>
<div class="service_subBtn">
    <input type="button" class="public_btn2" onclick="javascript:history.go(-1);" value="返回列表">
</div>
<script type="text/javascript" >
$(function(){
	$('#post_add_open').click(function(){
		$('#post_add_pic_form').css({'display':'block'})
	})
	$('#add_pic_submit_cancel').click(function(){
		$('#post_add_pic_form').css({'display':'none'})
	})
	var PIC_EXT = new Array('.jpg','.jpeg','.gif','.png');
	$('#add_pic_submit_button').click(function(){
		var pic1 = $('input[name="input_complain_pic1"]').val();
		var refund_pic1 = pic1.substr(pic1.indexOf('.'));
        var pic2 = $('input[name="input_complain_pic2"]').val();
		var refund_pic2 = pic2.substr(pic2.indexOf('.'));
        var pic3 = $('input[name="input_complain_pic3"]').val();
        var refund_pic3 = pic3.substr(pic3.indexOf('.')); 		
		var Error = '';
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
			$('#post_add_pic_form').submit();
		}else{
			alert(Error)
		}
	})
})
</script>
<script>
$(document).ready(function(){
    var state = <?php echo empty($output['complain_info']['complain_state'])?0:$output['complain_info']['complain_state'];?>;
	if(state == 20 ){
        $("#state_appeal").addClass('active');
    }
    if(state == 30 ){
        $("#state_appeal").addClass('active');
        $("#state_talk").addClass('active');
    }
    if(state == 40 ){
        $("#state_appeal").addClass('active');
        $("#state_talk").addClass('active');
        $("#state_handle").addClass('active');
    }
    if(state == 99 ){
        $("#state_appeal").addClass('active');
        $("#state_talk").addClass('active');
        $("#state_handle").addClass('active');
        $("#state_finish").addClass('active');
    }
});
</script>

<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>













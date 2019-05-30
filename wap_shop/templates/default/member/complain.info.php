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
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">交易投诉申请</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
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
			<a href="<?php echo C('remote_upload_url').$val;?>" nctype="nyroModal" rel="gal">
			<img src="<?php echo C('remote_upload_url').$val;?>" alt="">
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
            <li>
                <p class="uploadpic"><img id="img_0" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1 filepathUpload" id="refund_pic1" name="input_complain_pic1" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;">
                    <span class="error"></span> <p></p>
                </div>
            </li>
            <li>
                <p class="uploadpic"><img id="img_1" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1 filepathUpload" id="input_complain_pic2" name="input_complain_pic2" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;">
                    <span class="error"></span> <p></p>
                </div>
            </li>
            <li>
                <p class="uploadpic"><img id="img_2" src="<?php echo SHOP_TEMPLATES_URL?>/images/uploadpic.jpg" alt=""></p>
                <div class="upload_btn" style="position:relative;">
                    <span>图片上传</span>
                    <input class="upload_btn1 filepathUpload" id="input_complain_pic3" name="input_complain_pic3" type="file" value="图片上传" accept="image/png,image/gif" style="filter:alpha(opacity:0);opacity: 0;top:0px;position:absolute;float:left;">
                    <span class="error"></span> <p></p>
                </div>
            </li>
          </ul>
		<!---->
             <li class="">


             </li>


        <!---->
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
    <div class="chat_log_tt"><?php echo $lang['talk_list'];?></div>
    <div class="chat_log_content" id="div_talk" >

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
    //图片上传
    $(".filepathUpload").on("change",function() {
        var srcs = getObjectURL(this.files[0]);
        $(this).parents('li').find('img').attr("src",srcs);
    });
    function getObjectURL(file) {
        var url = null;
        if (window.createObjectURL != undefined) {
            url = window.createObjectURL(file)
        } else if (window.URL != undefined) {
            url = window.URL.createObjectURL(file)
        } else if (window.webkitURL != undefined) {
            url = window.webkitURL.createObjectURL(file)
        }
        return url
    };
	//加载对话
	get_talk();
	//发表对话
   $('#btn_publish').click(function(){
		if($('#complain_talk').val() === ''){
			alert('错误提示：对话内容不能为空')
		}else{
			add_talk();
		}
	})
	//刷新对话
	$('#btn_refresh').click(function(){
		get_talk();
	})
	//提交仲裁
	$("#btn_handle").click(function(){
        if(confirm("<?php echo $lang['handle_confirm_message'];?>")) {
            $("#handle_form").submit();
        }
    });
function add_talk(){
	$.ajax({
            type:'POST',
                url:'index.php?act=member_complain&op=publish_complain_talk',
                cache:false,
                data:"complain_id=<?php echo $output['complain_info']['complain_id'];?>&complain_talk="+$("#complain_talk").val(),
                dataType:'json',
                error:function(){
                    alert("<?php echo $lang['talk_send_fail'];?>");
                },
                success:function(data){
                    if(data == 'success') {
                        $("#complain_talk").val('');
                        get_talk();
                        alert("<?php echo $lang['talk_send_success'];?>");
                    }
                    else {
                        alert("<?php echo $lang['talk_send_fail'];?>");
                    }
                }
        });
}
function get_talk(){
	$('#div_talk').empty();
	$.ajax({
        type:'POST',
        url:'index.php?act=member_complain&op=get_complain_talk',
        cache:false,
        data:"complain_id=<?php echo $output['complain_info']['complain_id'];?>",
        dataType:'json',
        error:function(){
                alert('<?php echo $lang['talk_none'];?>')
        },
        success:function(data){
              $.each(data,function(index,item){
				  var css = '';
				  if(item.talk !== '投诉人'){
					 css = 'style="color:red"'
				  }
				  $('#div_talk').append('<div class="text1"><div class="times">'+item.time
				  +'</div><p class="dec" '+css+'>'+item.talk+'('+item.name+')说:'+item.content+'</p></div>')
			  })
        }
	});
}
})
</script>
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

<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="viewport" id="viewportid" content="target-densitydpi=285,width=750,user-scalable=no" />
<title>领取红包</title>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js" /></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/mdialog.js" /></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/hongbao.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/mdialog.css" />
</head>
<body ontouchstart>
<form method="post" id="auth_form" action="<?php echo $_SERVER["REQUEST_URI"]?>">
<input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="type" id="whatstype" value="<?php echo $_GET['type'];?>">
      <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
      <input type="hidden" id="mobile_err"/>
      <input type="hidden" id="code_err"/>
      <input type="hidden"  name="auth_type" id="auth_type"value="mobile"/>
      <input type="hidden" name="mobile" value="<?php echo $_GET["mobile"]?>" id="mobile"/>
	<center>
<div class="hongbao_02">	
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-1.jpg"/>
<div class="content_bg"><br/><br/>
<section class="ui-container">
    <div class="remainingSumBox">
<div class="qz-padding">
        <div class="qz-border qz-padding5 qz-background-white clearfix qz-relative">
            <span class="qz-fl qz-ico qz-ico-write"></span>
             <input type="text" name="captcha" autocomplete="off"  placeholder="请输入验证码" class="qz-fl qz-txt2 qz-light" id="captcha" maxlength="4" size="10" />
             <a href="javascript:void(0)" class="ml5 code" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?><img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>" name="codeimage" border="0" id="codeimage" class="fl ml5"></a>
        </div>
       
        <div class="qz-bk10"> <span id="code_msg" class="msg"></span></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix qz-relative">
            <span class="qz-fl qz-ico qz-ico-write"></span>
            <input type="text" class="qz-fl qz-txt2 qz-light" placeholder='请输入短信验证码' onKeyUp="this.value=this.value.replace(/\D/g,'').substring(0,6)"  maxlength="6" value="" name="auth_code" size="10" id="auth_code" autocomplete="off" />
                        <input type="button" id="send_auth_code" class="qz-button qz-light3" style="padding:8.5px;"  value="获取验证码">
						<input type="hidden" id="ck_time" value="" />
						
        </div>
      
        <div class="qz-bk10 "><span id="auth_code_msg" class="msg"></span><span id="mobile_msg" class="msg"></span></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <span class="qz-fl qz-ico qz-ico-pwd"></span>
            <input type="password" class="qz-fl qz-txt2 qz-light" placeholder="请设置登陆密码（6位以上的字符）" name="password" autocomplete="off" id="password">
        </div>
        
         <span id="password_msg" class="msg"></span>
    </div>
        
        <p class="text">红包打入你的帐户，就差这一步啦！</p><br/>
        <div class="remainingSum1Sumbit">
        	<a href="javascript:" id="form-button">查看红包</a>
        </div>
    </div>

</section>
<div class="text">
<p>活动规则</p>
1. 参与@婕珞芙GF官方微博 #2016让红包飞# 活动，抽中GELLÉ FRÈRES
 （婕珞芙）官方商城优惠券即可注册领取现金优惠券。<br/>
2. 用户进入注册页面，输入手机号和验证码，点击领取红包，一个用户只能领取一次。<br/>
3. 红包用于GELLÉ FRÈRES（婕珞芙）官方商城全场商品购买享满减优惠（详见优惠券
使用规则）。<br/>
4. 红包使用时间有限，有效期以实际领取时提示的有效期为准，逾期失效。<br/>
5.代金券不可与其他活动共同使用，不找零，不折现，逾期作废。<br/>
</div>	
</div>
</div>
</center>
</form>
<script type="text/javascript">
$(function(){
	
	var img_no='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic22.gif" style="width:25px"/>';
	var img_ok='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic21.gif" style="width:25px"/>';
	var CK_TIME = true;
	
    $('#form-button').click(function(){
    	var msg='';
    	var code_msg='';
		$('#mobile_msg').text('');
		$('#password_msg').text('');
		if($('#mobile').val()==''){
			$('#mobile_msg').text('没有手机号');return false;
        }
		if($('#captcha').val()==''){
			$('#code_msg').text('请输入验证码');return false;
        }
        //验证码
        //<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
		//-----
		var code=$('#captcha').val();
		$.ajax({ 
		type:"GET", 
		async:false,
		dataType: 'text',
		url:'<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>&captcha='+code, 
		success:function(data){
				if(data == 'false') {
                    document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
               }
				code_msg=data;						
			} 
		});
		if( code_msg =='false'){
			alert('请输入正确的验证码');return false;
		}
		//-----
		if(!isPhone($('#mobile').val())){
			$('#auth_code_msg').text('手机号格式不对');return false;
		}
		if($('#password').val()==''){
			$('#password_msg').text('密码不能为空');return false;
		}
		if($('#password').val().length<6){
			$('#password_msg').text('密码最少长度为6位');return false;
		}
		var code =$('input[name="auth_code"]').val();
		if( code =='' || code.length != 6){
			alert('短信验证码不对');return false;
		}
		//-----
		var mobile=$('#mobile').val();
		$.ajax({ 
		type:"GET", 
		async:false,
		dataType: 'json',
		url:"/wap_shop/?act=mobile_hongbao&op=checkSubmit&mobile="+mobile+"&code="+code+'&password='+$('#password').val(), 
		success:function(data){
				if(data.state==false){
					msg=data.msg;						
				}
			} 
		});
		//-----
		if(msg!=''){
			alert(msg);return false;
		}else{
		//-------------
			form_sub();
		}
		
	});
	function form_sub(){
		var voucher='<?php echo $_GET["voucher"]?>';
		var data={mobile:$('#mobile').val(),password:$('#password').val(),voucher:voucher,auth_code:$('#auth_code').val()};
		$.ajax({
			type:'POST',
			async:false,
			dataType:'json',
			url:'/wap_shop/?act=mobile_hongbao&op=hongbao2_sub&voucher='+voucher,
			data:data,
			success:function(data){
				if(data.state==true){
					voucher_ok();
				}else{
					alert(data.msg);
				}
			}
		});
	}
	$('#send_auth_code').click(function(){
		if (!CK_TIME) return;
		CK_TIME = false;
		var mobile=$('#mobile').val();
		if(mobile==''){
			$('#auth_code_msg').text('手机号不能为空');return false;
        }
		if(!isPhone(mobile)){
			$('#auth_code_msg').text('手机号格式不对');return false;
		}
		$('#send_auth_code').val('发送中...');
		
		$.getJSON('index.php?act=mobile_hongbao&op=send_auth_code&type',{type:$('#auth_type').val(),mobile:mobile},function(data){
			if (data.state == 'true') {
				$('#ck_time').val(90);
			    setTimeout(StepTimes,1000);
			} else {
				CK_TIME = true;
				$('#send_auth_code').val('重新发送');
				if (data.msg != '') {
					$('#auth_code_msg').text(data.msg);
				}else{
					$('#auth_code_msg').text('发送失败');
				}
			}
		});
	});
	function StepTimes() {
		$num = parseInt($('#ck_time').val());
		$num = $num - 1;
		$('#ck_time').val($num)
		$('#send_auth_code').val($num+'秒后再次获取');
		if ($num <= 0) {
			CK_TIME = true;
			$('#send_auth_code').val('重新发送')
		} else {
			setTimeout(StepTimes,1000);
		}
	}
	//------------
	$('#password').blur(function(){
		$('#password_msg').html('');
		var password=$(this).val();
		if(password!=''&&password.length<6){
			$('#password_msg').html('密码长度不够');
		}else{
			$('#password_msg').html('');
		}
	});
	//----
	$('#auth_code').keyup(function(){
		var code=$(this).val();
		if (code != '') {
			if (code.length== 6) {
				check_auth_code(code);
				//$('#auth_code_msg').html(img_no);
			}
			else {
				$('#auth_code_msg').html('');
			}
		}
	});
	function isPhone(str){
	    var myReg = /^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|17|18)\d{9}$/;//;/^(1[0-9]{10})$/;
	    return myReg.exec(str) != null;
	}
	//-------
	function voucher_ok(){  
        new TipBox({type:'image',str:'领取成功',hasBtn:true});
		var linkurl='?act=member_voucher';
		var src='<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/voucher<?php echo $output["voucher"]?>.gif';
		var window_w=$(window).width();
		$('#animationTipBox').html('<a href="'+linkurl+'"><img src="'+src+'"/></a>');
		$('#animationTipBox').css({'margin-top':'0px','margin-left':'0px','left':'0','top':'0','width':window_w});
		$('#animationTipBox img').css({'width':window_w});
		window.setTimeout(function(){
			$('#animationTipBox').css({'display':'none','width':window_w});
			$('#animationTipBox img').css({'width':window_w,'display':'hidden'});
			window.location.href=linkurl;
		},3000);
    }
	function check_auth_code(code){
		var mobile=$('#mobile').val();
		$.ajax({ 
		type:"GET", 
		async:false,
		dataType: 'json',
		url:"/wap_shop/?act=mobile_hongbao&op=checkSubmit&mobile="+mobile+"&code="+code+'&password='+$('#password').val(), 
		success:function(data){
				if(data.state==false){
					$('#auth_code_msg').html(img_no);						
				}else if(data.state==true){
					$('#auth_code_msg').html(img_ok);	
				}
			} 
		});
	}
	//成功  
    $("#success").click(function(){  
        voucher_ok();
    });
	 //-----------------
	 $('#captcha').keyup(function(){
		$('#code_msg').html('');
		var code=$(this).val();
		if (code != ''&&code.length==4) {
			$.ajax({
				type: "GET",
				async: false,
				dataType: 'text',
				url: '<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=check&nchash=<?php echo getNchash();?>&captcha=' + code,
				success: function(data){
					if (data == 'false') {
						$('#code_msg').html(img_no);
						document.getElementById('codeimage').src = '<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash();?>&t=' + Math.random();
					}
					else {
						$('#code_msg').html(img_ok);
					}
				}
			});
		}
	});
	//---------------
});
</script> 
</body>
</html>
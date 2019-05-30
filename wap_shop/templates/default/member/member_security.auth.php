<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>身份认证</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script> <!--替换alert -->
</head>
<body ontouchstart>
<form method="post" id="auth_form" action="index.php?act=member_security&op=auth">
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="gotoback()" ></i>
    <h1 class="qz-color">安全认证</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="type" id="whatstype" value="<?php echo $_GET['type'];?>">
      <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
<section class="ui-container">
    <div class="remainingSumBox">
    	<div class="remainingSum1Text">
        	操作提示：<br>
1. 请选择“绑定邮箱”或“绑定手机”方式其一作为安全校验码的获取方式并正确输入。<br>
2. 如果您的邮箱已失效，可以 <a href="index.php?act=member_security&op=auth&type=modify_mobile">绑定手机</a> 后通过接收手机短信完成验证。<br>
3. 如果您的手机已失效，可以 <a href="index.php?act=member_security&op=auth&type=modify_email">绑定邮箱</a> 后通过接收邮件完成验证。<br>
4. 请正确输入下方图形验证码，如看不清可点击图片进行更换，输入完成后进行下一步操作。<br>
5. 收到安全验证码后，请在30分钟内完成验证。
        </div>
        <div class="remainingSum1Select">
        	<h1>请选择身份认证方式</h1>
            <dl class="remainingSum1SelectList">
            	<dd>
                	<select name="auth_type" id="auth_type">
                    	<?php if ($output['member_info']['member_mobile']) {?>
                        <option value="mobile">手机 [<?php echo encryptShow($output['member_info']['member_mobile'],4,4);?>]</option>
                        <?php } ?>
                        <?php if ($output['member_info']['member_email']) {?>
                        <option value="email">邮箱 [<?php echo encryptShow($output['member_info']['member_email'],4,4);?>]</option>
                        <?php } ?>
                    </select>
                </dd>
                <dd>
                	<!--<div>
                        <input type="text" />
                        <a href="">获取验证码</a>
                    </div>-->
                    <div class="qz-relative">
						<input type="text" class="qz-txt3 qz-light3 qz-border"  maxlength="6" value="" name="auth_code" size="10" id="auth_code" autocomplete="off" />
                        <input type="button" id="send_auth_code" class="qz-button qz-light3" style="padding:8.5px;"  value="获取验证码">
						<input type="hidden" id="ck_time" value="" />
                    </div>
                </dd>
            </dl>
        </div>
        <div class="remainingSum1Sumbit">
        	<a href="javascript:" id="form-button">确认提交</a>
        </div>
    </div>
</form>
</section>
<script type="text/javascript">
function gotoback() {
    var type = document.getElementById('whatstype').value;
    if(type=='pd_cash') {
        window.location.href='index.php?act=predeposit&op=pd_cash_list';
    } else {
        history.back();
    }
}
$(function(){
	var CK_TIME = true;
    $('#form-button').click(function(){
		var code =$('input[name="auth_code"]').val();
		if( code =='' || code.length != 6){
			 alertPopWin('请输入正确的验证码','close')
		}else{
			$('#auth_form').submit()
		}
	})
	$('#send_auth_code').click(function(){
		if (!CK_TIME) return;
		CK_TIME = false;
		$('#send_auth_code').val('发送中...')
		$.getJSON('index.php?act=member_security&op=send_auth_code',{type:$('#auth_type').val()},function(data){
			if (data.state == 'true') {
				$('#ck_time').val(90);
			    setTimeout(StepTimes,1000);
			} else {
				CK_TIME = true;
				$('#send_auth_code').val('重新发送')
				 alertPopWin('发送失败','close')
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
	
})
</script>
</body>
</html>
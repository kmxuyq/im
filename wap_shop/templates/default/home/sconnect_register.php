<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/layer-v2.1/layer/layer.js"></script>
<title>完善账号信息</title>
<style>
 .sina{display: block;width: 100%;}
  .sina img{max-width: 100px;min-height: 100px;width: 100%;text-align: center;}
  .sina span{font-size:16px;}
</style>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()"></i>
    <h1 class="qz-color">完善账号信息</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<div class="sina">
<center><img alt="" src="<?php echo SHOP_TEMPLATES_URL;?>/img/sina.png">
<span>绑定新浪微博</span>
</center>
</div>
<form name="register_form" id="register_form" method="post" action="index.php?act=sconnect&op=register">
    <input type="hidden" value="ok" name="form_submit">
    <input type='hidden' name="regsname" value="<?php echo $output['sinauser_info']['name'];?>"/>
    <div class="qz-bk1"></div>
    <div class="qz-padding">
        <div class="qz-border qz-padding5 qz-background-white clearfix" >
            <input type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入用户名" autocomplete="off"  name="user_name" id="user_name" autofocus >
        </div>
        
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <input type="password" class="qz-fl qz-txt2 qz-light" placeholder="请输入密码（6位以上的字符）" name="password"  id="password" autocomplete="off">
        </div>
                
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <input type="password" class="qz-fl qz-txt2 qz-light" placeholder="确认密码" id="password_confirm" name="password_confirm" autocomplete="off">
        </div>
        
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <input type="email" class="qz-fl qz-txt2 qz-light" placeholder="请输入您的邮箱" id="email" name="email" autocomplete="off">
        </div>
        
        <div class="qz-bk10"></div>
        <?php if(C('captcha_status_register') == '1') { ?>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="width: 55%;float: left;">
            <input type="text" id="captcha" name="captcha" class="qz-fl qz-txt2 qz-light" maxlength="4" size="10"  placeholder="请输入验证码">
            
      </div>
      <div class=" qz-padding5 clearfix" style="width: 30%;float: right;">
      <img src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" border="0" id="codeimage" onclick="this.src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random()"  class="fl ml5"><a href="javascript:void(0)" class="fl ml5" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
      <input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />
      </div>
      <?php } ?>
      <div class="qz-bk10"></div>
        <div class="qz-padding5 clearfix">
      <input type="checkbox"  name="agree" value='1' id="agree" checked="checked" />
      <span style="line-height: 30px;"><?php echo $lang['home_sconnect_login_agree'];?><?php echo $lang['home_sconnect_login_useragreement']; ?></span>
      </div>
    </div>
    
    <div class="qz-bk1"></div>
    <div class="ui-btn-wrap">
<!--         <input type="submit" value="确认提交" class="ui-btn-lg ui-btn-primary qz-btn-lg" /> -->
    <input type="button" value="确认提交" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
    <div class="qz-bk20"></div>
</form>
</section>

<script type="text/javascript">
$('input[type="button"]').click(function(){
	var user_name = $('#user_name').val();
	var password = $('#password').val();
	var password_confirm = $('#password_confirm').val();
	var email = $('#email').val();
	var captcha = $('#captcha').val();
// alert(password+' '+password_confirm)；
// alert(password_confirm!=password);
	if(user_name==''){
// 		layer.tips('用户名不为空！');
		layer.msg('用户名不为空！');return false;$('#user_name').focus();
	}if(password=''){
		layer.msg('密码不为空！');return false;$('#password').focus();
	}if(password_confirm==''){
		layer.msg('再次确认密码！');return false;$('#password_confirm').focus();
// 	}if(password_confirm!=password){
// 		layer.msg('两次密码输入不一致！');return false;$('#password_confirm').focus();
	}if(email==''){
		layer.msg('邮箱不为空！');return false;$('#email').focus();
	}if(captcha==''){
		layer.msg('验证码不为空！');return false;$('#email').focus();
	}else{
		$('#register_form').submit();
		}
})

</script>

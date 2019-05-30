<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>忘记密码</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<script type="text/javascript" src="<?php echo  RESOURCE_SITE_URL?>/js/jquery.js"></script>
</head>

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color"><?php echo $lang['login_index_find_password'];?></h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div class="ui-container">
    <div class="qz-bk10"></div>
    <div class="qz-padding" id="find_password">
        <dd>
		<input type="text" id="username" name="username" class="qz-txt3 qz-light3 qz-border" placeholder="请输入您的帐号">
		</dd>
		<label></label>
        <div class="qz-bk10"></div>
		<dd>
        <input type="email" id="email" name="email" class="qz-txt3 qz-light3 qz-border" placeholder="请输入您的电子邮箱">
		</dd>
		<label></label>
    </div>
    
    <div class="ui-btn-wrap">
		<input type="button" class="ui-btn-lg ui-btn-primary qz-btn-lg" value="重置密码" name="Submit" id="submit">
    </div>
	<input type="hidden" id="ref_url" value="<?php echo $output['ref_url']?>" name="ref_url">
</div>

<script type="text/javascript">
$(function() {
	$('input[type="button"]').click(function(){
    		var url="index.php?act=login&op=find_password";
    		var form_submit=$('#form_submit').val();
    		var username=$('#username').val();
    		var email=$('#email').val();
    		var submit=$('#submit').val();
    		var ref_url=$('#ref_url').val();
    		if(email&&username){
    		$.ajax({
    			type: 'POST',
    			url:  url,
    			dataType: 'html',
    			data: {username:username,email:email,Submit:submit,ref_url:ref_url,form_submit:'ok'},
    			success:function (result) {
    				alert(result);
    				//document.location.reload();
                }
    			});
			}else{
				alert("请填写用户名或密码");
				}
		});
})
</script>

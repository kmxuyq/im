<?php defined('InShopNC') or exit('Access Invalid!');?>

<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
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
<?php require_once template('menu');?>

<section class="ui-container">
<div class="sina">
<center><img alt="" src="<?php echo SHOP_TEMPLATES_URL;?>/img/QQ_r.png">
<span>绑定QQ账号</span>
</center>
</div>
<form name="register_form" id="register_form" method="post" action="index.php?act=connect&op=register">
        <input type="hidden" value="ok" name="form_submit">
        <input type='hidden' name="loginnickname" value="<?php echo $output['qquser_info']['nickname'];?>"/>
    <div class="qz-bk1"></div>
    <div class="qz-padding">
        <div class="qz-border qz-padding5 qz-background-white clearfix" >
            <input type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入用户名" autocomplete="off"  name="user_name" id="user_name" autofocus >
        </div>
        
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <input type="password" class="qz-fl qz-txt2 qz-light" placeholder="设置密码" value="<?php echo $output['user_passwd'];?>" id="password" name="password"  autocomplete="off">
        </div>
        
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <input type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入您的邮箱" id="email" name="email" autocomplete="off">
        </div>
        
      <div class="qz-bk10"></div>
        <div class="qz-padding5 clearfix">
      <input type="checkbox"  name="agree" value='1' id="agree" checked="checked" />
      <span style="line-height: 30px;"><?php echo $lang['home_sconnect_login_agree'];?><?php echo $lang['home_sconnect_login_useragreement']; ?></span>
      </div>
    </div>
    
    <div class="qz-bk1"></div>
    <div class="ui-btn-wrap">
        <input type="submit" value="确认提交" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
    <div class="qz-bk20"></div>
</form>
</section>










<div class="nc-login-layout" style="display: none;">

  <div class="left-pic"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/login_qq.jpg" />
    <p><a href="#register_form"><?php echo $output['qquser_info']['nickname']; ?></a></p>
  </div>
  <div class="nc-login" id="rotate">
    <ul>
      <li class="w400"><a href="#register_form"><?php echo $lang['home_qqconnect_register_title']; ?><!-- 完善账号信息 --></a></li>
    </ul>
    <div class="nc-login-content">
      <form name="register_form" id="register_form" method="post" action="index.php?act=connect&op=register">
        <input type="hidden" value="ok" name="form_submit">
        <input type='hidden' name="loginnickname" value="<?php echo $output['qquser_info']['nickname'];?>"/>
        <dl>
          <dt><img src="<?php echo $output['qquser_info']['figureurl_qq_1'];?>" /></dt>
          <dd>
            <label><?php echo $lang['home_qqconnect_register_success']; ?></label>
          </dd>
        </dl>
        <dl class="mt20">
          <dt><?php echo $lang['login_register_username']; ?>: </dt>
          <dd>
            <label><?php echo $_SESSION['member_name'];?></label>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['login_register_pwd']; ?>: </dt>
          <dd>
            <input type="text" value="<?php echo $output['user_passwd'];?>" id="password" name="password" class="text tip" title="<?php echo $lang['login_register_password_to_login'];?>"/>
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['login_register_email']; ?>: </dt>
          <dd>
            <input type="text" id="email" name="email" class="text tip" title="<?php echo $lang['login_register_input_valid_email'];?>"/>
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt>&nbsp;</dt>
          <dd>
            <input type="submit" name="submit" value="<?php echo $lang['login_register_enter_now'];?>" class="submit fl"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="index.php"><?php echo $lang['home_qqconnect_homepage']; ?></a></span>
            <label></label>
          </dd>
        </dl>
      </form>
      <div class="clear"></div>
    </div>
    <div class="nc-login-bottom"></div>
  </div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script type="text/javascript">
//注册表单提示
$('.tip').poshytip({
	className: 'tip-yellowsimple',
	showOn: 'focus',
	alignTo: 'target',
	alignX: 'center',
	alignY: 'top',
	offsetX: 0,
	offsetY: 5,
	allowTipHover: false
});
    //注册表单验证
    $(function() {
        $('#register_form').validate({
            errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
            rules: {
                password: {
                    required: true,
                    minlength: 6,
                    maxlength: 20
                },
                email: {
                    required: true,
                    email: true,
                    remote: {
                        url: 'index.php?act=login&op=check_email',
                        type: 'get',
                        data: {
                            email: function() {
                                return $('#email').val();
                            }
                        }
                    }
                }
        },
        messages : {
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            },
            email : {
                required : '<?php echo $lang['login_register_input_email'];?>',
                email    : '<?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<?php echo $lang['login_register_email_exists'];?>'
            }
        }
    });
});
</script>
<?php echo $output['nc_synlogin_script'];?>
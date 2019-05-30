<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()"></i>
    <h1 class="qz-color">登录</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<section class="ui-container">
<form id="login_form" method="post" action="index.php?act=login&op=login"  class="bg">
<?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
    <div class="qz-bk1"></div>
    <div class="qz-padding">
        <div class="qz-border qz-padding5 qz-background-white clearfix" >
            <span class="qz-fl qz-ico qz-ico-user3"></span>
            <input type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入用户名" autocomplete="off"  name="user_name" id="user_name" autofocus >
        </div>
        
        <div class="qz-bk10"></div>
        <div class="qz-border qz-padding5 qz-background-white clearfix">
            <span class="qz-fl qz-ico qz-ico-user4"></span>
            <input type="password" class="qz-fl qz-txt2 qz-light" placeholder="请输入密码（6位以上的字符）" name="password" autocomplete="off"  id="password">
        </div>
    </div>
    
    <div class="qz-bk1"></div>
    <div class="ui-btn-wrap">
        <input type="submit" value="登 录" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
       <div  class="qz-padding">
            <span class="qz-fl"><a href="index.php?act=login&op=register&ref_url=<?php echo urlencode($_GET["ref_url"]);?>" >免费注册</a></span>
            <span class="qz-fl">&nbsp;  <a href="index.php?act=login&op=forget_password">找回密码</a></span>
        </div>
    <div class="qz-bk20"></div>
    
    <div  class="qz-padding" style="display: none;">
        <div class="qz-bk40"></div>
        <p>或使用第三方帐号登录，无需注册</p>
        
        <div class="qz-bk10"></div>
        <div class="qz-login-list qz-padding qz-padding-b5 qz-border qz-radius clearfix">
        <?php if ($output['setting_config']['qq_isuse'] == 1 || $output['setting_config']['sina_isuse'] == 1){?>
            <ul>
                <li><?php if ($output['setting_config']['qq_isuse'] == 1){?><a href="<?php echo WAP_SHOP_SITE_URL;?>/api.php?act=toqq"><img src="<?php echo SHOP_TEMPLATES_URL;?>/img/qq_ico.png" class="qz-img-block"></a><?php } ?></li>
                <li><?php if ($output['setting_config']['sina_isuse'] == 1){?><a href="<?php echo WAP_SHOP_SITE_URL;?>/api.php?act=tosina&ref_url=<?php echo urlencode($output['ref_url']);?>"><img src="<?php echo SHOP_TEMPLATES_URL;?>/img/sina_ico.png" class="qz-img-block"></a><?php } ?></li>
                <li style="display: none;"><a href="<?php echo SHOP_SITE_URL;?>/api.php?act=tosina"><img src="<?php echo SHOP_TEMPLATES_URL;?>/img/wx_ico.png" class="qz-img-block"></a></li>
            </ul>
        <?php } ?>
        </div>
    </div>
	 <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
</form>
</section>


<script type="text/javascript">
$(".qz-star-list .ico").click(function(){
    $(this).parent().children(".ico").removeClass("ico-hov");
    loc_num = $(this).index();
    for (var i=0;i<loc_num;i++) {
        $(this).parent().children(".ico").eq(i).addClass("ico-hov");
    }
    $(this).parent().parent().children(".star-input").val(loc_num);
});
</script>



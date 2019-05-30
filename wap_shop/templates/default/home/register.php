<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $lang['login_register_join_us'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<style type="text/css">
.header-wrap,.public-top-layout, .head-app, .head-search-bar, .head-user-menu, .public-nav-layout, .nch-breadcrumb-layout, #faq {
	display: none !important;
}
.public-head-layout {
	margin: 10px auto -10px auto;
}
.wrapper {
	width: 1000px;
}
#footer {
	border-top: none!important;
	padding-top: 30px;
}
</style>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color"><?php echo $lang['login_register_join_us'];?></h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<section class="ui-container">
    <div class="qz-bk10"></div>
    <div class="qz-padding">
     <form id="register_form" method="post" action="index.php?act=login&op=usersave" enctype="multipart/form-data">
     <?php Security::getToken();?>
     <dd>
        <input type="text" name="user_name" id="user_name" class="qz-txt3 qz-light3 qz-border" placeholder="请使用手机号码注册用户名">
        <label></label>
     </dd>
        <div class="qz-bk10"></div>
     <dd>
        <input type="password" name="password" id="password" class="qz-txt3 qz-light3 qz-border tip" placeholder="请输入密码（6位以上字符）">
        <label></label>
     </dd>
        <div class="qz-bk10"></div>
     <dd>
        <input type="password" name="password_confirm" id="password_confirm" class="qz-txt3 qz-light3 qz-border tip" placeholder="请再次输入密码">
        <label></label>
      </dd> 
        <div class="qz-bk10"></div>
<!--      <dd>
        <input type="text" name="email" id="email" class="qz-txt3 qz-light3 qz-border tip" placeholder="请输入您的邮箱">
        <label></label>
     </dd> -->
     </dd>  
    </div>
    
    <div class="ui-btn-wrap">
        <input type="submit" value="注 册" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
        <input name="agree" type="hidden" class="vm ml10" id="clause" value="1" checked="checked" />
        <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" value="<?php echo $_GET['zmr']?>" name="zmr">
    </div>
    </form>
    <div class="qz-bk20"></div>
    <div class="qz-padding">
        <div class="clearfix">
            <span class="qz-fl"><a href="<?php echo urlShopWap('login', 'index',array('ref_url'=>$_GET["ref_url"]));?>">登录</a></span>
        </div>
        <!--
        <div class="qz-bk40"></div>
        <p>或使用第三方帐号登录，无需注册</p>
        
        <div class="qz-bk10"></div>
        <div class="qz-login-list qz-padding qz-padding-b5 qz-border qz-radius clearfix"style="background:#fff;">
            <ul>
                <li><a href="#"><img src="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/images/new/qq_ico.png" class="qz-img-block"></a></li>
                <li><a href="#"><img src="<?php echo WAP_SHOP_SITE_URL;?>/templates/default/images/new/wx_ico.png" class="qz-img-block"></a></li>
            </ul>
        </div>
		-->
    </div>
	<div id="menu"></div>
</section>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script>
//注册表单提示
/*
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
*/
//注册表单验证
$(function(){
        jQuery.validator.addMethod("phonenum", function(value, element) {
            return this.optional(element) || /^1+\d{10}$/i.test($.trim(value));
        }, "must phone num");
		/*jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
		}, "Letters only please"); 
		jQuery.validator.addMethod("lettersmin", function(value, element) {
			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
		}, "Letters min please"); 
		jQuery.validator.addMethod("lettersmax", function(value, element) {
			return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
		}, "Letters max please");*/
    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
        rules : {
            user_name : {
                required : true,
                phonenum : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            // email : {
            //     required : true,
            //     email    : true,
            //     remote   : {
            //         url : 'index.php?act=login&op=check_email',
            //         type: 'get',
            //         data:{
            //             email : function(){
            //                 return $('#email').val();
            //             }
            //         }
            //     }
            // },
            agree : {
                required : true
            }
        },
        messages : {
            user_name : {
                required : '<?php echo $lang['login_register_input_username'];?>',
                phonenum : '请输入正确的手机号码！',
				remote	 : '<?php echo $lang['login_register_username_exists'];?>'
            },
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            },
            password_confirm : {
                required : '<?php echo $lang['login_register_input_password_again'];?>',
                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'
            },
            email : {
                required : '<?php echo $lang['login_register_input_email'];?>',
                email    : '<?php echo $lang['login_register_invalid_email'];?>',
				remote	 : '<?php echo $lang['login_register_email_exists'];?>'
            },
            agree : {
                required : '<?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
});
</script>
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
</body>
</html>
<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
 <form method="post" id="password_form" name="password_form" action="index.php?act=member_security&op=modify_paypwd">
 <input type="hidden" name="form_submit" value="ok" />
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">设置支付密码</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<section class="ui-container">
    <div class="qz-bk10"></div>
    <div class="qz-padding">
        <p>1. “支付密码”用于结算订单时使用<em>“账户余额”</em>时的密码输入，请牢记并确保安全。</p>
        <p>2.如修改或找回“支付密码”，请完成安全验证操作后重新提交。</p>
             <div class="qz-bk5"></div>
       <input type="password" class="qz-txt3 qz-light3 qz-border"  name="password" maxlength='20' id="password" placeholder="设置新密码">
        
        <div class="qz-bk10"></div>
        <input type="password" class="qz-txt3 qz-light3 qz-border" name="confirm_password" maxlength='20' id="confirm_password" placeholder="确认新密码">
        
    </div>
    <span class="qz-padding" id="msg"></span>
    <div class="ui-btn-wrap">
        <input type="submit" value="<?php echo $lang['home_member_submit'];?>" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
</section>
</form>
<script type="text/javascript">
    $(function () {
        $('input[type="submit"]').click(function () {
            var pwd = $('#password').val();
            var pwd2 = $('#confirm_password').val();
            if (pwd == '' || pwd2 == '') {
                $('#msg').text('密码不能为空'); return false;
            }
            
            if (pwd.substr().length < 6) {
                $('#msg').text('密码长度不能小于6位'); return false;
            }
            if (pwd != pwd2) {
                $('#msg').text('确认密码不对'); return false;
            }
        });

    });
</script>
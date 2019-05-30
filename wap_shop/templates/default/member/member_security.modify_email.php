<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
 <form method="post" id="email_form" action="index.php?act=member_security&op=send_bind_email">
 <input type="hidden" name="form_submit" value="ok" />
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">邮箱绑定</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-bk10"></div>
    <div class="qz-padding">
        <p>1. 此邮箱将接收密码找回，订单通知等敏感性安全服务及提醒使用，请务必填写正确地址。</p>
        <p>2. 设置提交后，系统将自动发送验证邮件到您绑定的信箱，您需在24小时内登录邮箱并完成验证。</p>
        <p>3. 更改邮箱时，请通过安全验证后重新输入新邮箱地址绑定。</p>
        <div class="qz-bk5"></div>
       
        <div class="qz-bk10"></div>
        <div class="qz-relative">
            <input type="input" class="qz-txt3 qz-light3 qz-border"  maxlength="40" value="<?php echo $output['member_info']['member_email'];?>" name="email" id="email" placeholder="请输入Email地址">
           
        </div>
        
    </div>
    <span class="qz-padding" id="msg"></span>
    <div class="ui-btn-wrap">
        <input type="submit" value="发送验证邮件" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
</section>
</form>

<script type="text/javascript">
    $(function () {
        $('input[type="submit"]').click(function () {
            var email = $('#email').val();
            if (email == '' || email == '请输入Email地址') {
                $('#msg').text('邮箱地址不能为空'); return false;
            }
            if (!isEmail(email)) {
                $('#msg').text('邮箱格式不对'); return false;
            }
        });
        function isEmail(str) {
            var myReg = /^[-_A-Za-z0-9]+@([_A-Za-z0-9]+\.)+[A-Za-z0-9]{2,3}$/;
            return myReg.exec(str) != null;
        }

    });
</script> 

<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/layer-v2.1/layer/layer.js"></script>
 <form method="post" id="auth_form" action="index.php?act=member_security&op=modify_mobile">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="type" value="<?php echo $_GET['type'];?>">
      <input name="nchash" type="hidden" value="<?php echo getNchash();?>" />
 <header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">手机号码绑定</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>


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
            <dl class="remainingSum1SelectList">
            	<dd>
          <input type="text" class="qz-txt3 qz-light3 qz-border"  maxlength="11" value="<?php echo $output['member_info']['member_mobile'];?>" name="mobile" id="mobile" />
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
        	<a href="javascript:" id="form-button">立即绑定</a>
        </div>
    </div>

</section></form>
<script type="text/javascript">
    $(function () {
        var CK_TIME = true;
        $('#form-button').click(function () {
            var code = $('input[name="auth_code"]').val();

            if (code === '' || code.length != 6) {
                layer.msg('请输入正确的验证码')
            } else {
                $('#auth_form').submit()
            }
        })
        $('#send_auth_code').click(function () {
            if (!CK_TIME) return;
            CK_TIME = false;
            $('#send_auth_code').val('发送中...');
			var url='index.php?act=member_security&op=send_modify_mobile&mobile='+$('#mobile').val();
            $.getJSON(url, function (data) {

                if (data.state == 'true') {
                    $('#ck_time').val(90);
                    setTimeout(StepTimes, 1000);
                } else {
                    CK_TIME = true;
                    $('#send_auth_code').val('重新发送')
                    layer.msg(data.msg)
                }
            });
        });
        function StepTimes() {
            $num = parseInt($('#ck_time').val());
            $num = $num - 1;
            $('#ck_time').val($num)
            $('#send_auth_code').val($num + '秒后再次获取');
            if ($num <= 0) {
                CK_TIME = true;
                $('#send_auth_code').val('重新发送')
            } else {
                setTimeout(StepTimes, 1000);
            }
        }

    })
</script>

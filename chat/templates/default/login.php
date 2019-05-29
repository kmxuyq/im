<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
            <meta content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0" name="viewport">
                <title>
                    登录
                </title>
                <link href="<?php echo CHAT_TEMPLATES_URL; ?>/lib/css/jx.css" rel="stylesheet"/>
                <link href="<?php echo CHAT_TEMPLATES_URL; ?>/css/login/login.css" rel="stylesheet"/>
                <script src="<?php echo CHAT_TEMPLATES_URL; ?>/lib/js/zepto.min.js" type="text/javascript">
                </script>
                <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-1.11.0.min.js"></script>
                <script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js"></script>
                <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/layer-v2.1/layer/layer.js"></script>
            </meta>
        </meta>
    </head>
    <body>
<form id="form_login" action="/chat/index.php?act=web_chat&op=login_act" method="post" >
    <?php Security::getToken();?>
    <input name="nchash" type="hidden" value="<?php echo $output['nchash']; ?>" />
    <input type="hidden" name="form_submit" value="ok" />
        <div class="container">
            <div class="item input-item">
                <input placeholder="输入您注册时的商家账号" name="seller_name" type="text" autocomplete="off" autofocus/>
            </div>
            <div class="item input-item">
                <input placeholder="输入密码" name="password" type="password" autocomplete="off"/>
            </div>
            <div class="item input-item">
             <b class="fluid-input">
             <b class="fluid-input-inner">
                <input class="checkcode-input text" placeholder="输入验证码" type="text" name="captcha" id="captcha" autocomplete="off"/>
                <a href="javascript:void(0)" nctype="btn_change_seccode">
                <img class="checkcode-img" src="/shop/index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash']; ?>" name="codeimage" border="0" id="codeimage"/></a>
                <a href="JavaScript:void(0);" class="change" nctype="btn_change_seccode" title="<?php echo $lang['login_index_change_checkcode']; ?>">
                <i class="checkcode-refresh">
                </i>
                </a>
            </b>
            </b>
            </div>
            <div class="item login-btn" onclick="submit()">
                登录
            </div>
            <div class="item login-other" style="display: none;">
                <a href="/wap_shop/index.php?act=login&op=forget_password">
                    找回密码
                </a>
                <span class="cover-symbol">
                </span>
                <a href="/wap_shop/index.php?act=login&op=register">
                    立即注册
                </a>
            </div>
        </div>
        </form>
    </body>
    <script type="text/javascript">
    //更换验证码
    function change_seccode() {
        $('#codeimage').attr('src', '/shop/index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash']; ?>&t=' + Math.random());
        $('#captcha').select();
    }

    //登录提交表单
    function submit() {
      var url = $("#form_login").attr('action');
      var data = $("#form_login").serializeArray();
      $.ajax({
          type: "POST",
          dataType: "json",
          url: url,
          data: data,
          success: function (obj) {
              if(obj.status == 1){
                  layer.msg(obj.info)
                  window.location.href=obj.url;
              }
              else{
                  layer.msg(obj.info);
                  $('#'+obj.name).focus();
                  change_seccode();
                  return false;
              }
          },
          error: function(data) {
              layer.msg("登录失败，请稍后再试！");
              change_seccode();
          }
      });
      return false;
    }
    $(document).ready(function() {
    $('[nctype="btn_change_seccode"]').on('click', function() {
        change_seccode();
    });

    //登陆表单验证
    $("#form_login").validate({
        errorPlacement:function(error, element) {
            layer.msg(error[0]['innerText']);
            // element.prev(".repuired").append(error);
        },
        onkeyup: false,
        rules:{
            seller_name:{
                required:true
            },
            password:{
                required:true
            },
            captcha:{
                required:true,
                remote:{
                    url:"/shop/index.php?act=seccode&op=check&nchash=<?php echo $output['nchash']; ?>",
                    type:"get",
                    data:{
                        captcha:function() {
                            return $("#captcha").val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                            change_seccode();
                        }
                    }
                }
            }
        },
        messages:{
            seller_name:{
                required:"<i class='icon-exclamation-sign'></i>用户名不能为空"
            },
            password:{
                required:"<i class='icon-exclamation-sign'></i>密码不能为空"
            },
            captcha:{
                required:"<i class='icon-exclamation-sign'></i>验证码不能为空",
                remote:"<i class='icon-frown'></i>验证码错误"
            }
        }
    });
    //Hide Show verification code
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });

});
    </script>
</html>

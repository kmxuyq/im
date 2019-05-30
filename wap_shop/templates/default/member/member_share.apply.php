<!doctype html>
<html>
<head>
    <title>云之南优品</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元抢购，伊美假日"/>
    <meta name="author" content="伊美假日"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/shareshop/css/reset.css">
    <link rel="stylesheet" href="/wap_shop/templates/default/shareshop/css/main.css">
    <script src="/wap_shop/templates/default/shareshop/js/less.min.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/jquery-1.11.3.min.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/main.js"></script>
    <script src="/wap_shop/templates/default/shareshop/js/TouchSlide.1.1.js"></script>
</head>
<body class="gary_bg">
<div class="manager_jion">
    <div class="tt">店主特权</div>
    <div class="jion_link">
        <div class="rows">
            <img src="/wap_shop/templates/default/shareshop/images/manager_jion1.jpg" alt="">
            <div class="text">
                <h1>独立微店</h1>
                <p>用又自己的微店及推广二维码</p>
            </div>
        </div>
        <div class="rows">
            <img src="/wap_shop/templates/default/shareshop/images/manager_jion2.jpg" alt="">
            <div class="text">
                <h1>销售拿佣金</h1>
                <p>微店卖出商品,您可以获得佣金</p>
            </div>
        </div>
        <div class="tips_text">店长的商品销售统一由厂家直接收款、直接开发，并提供产品的售后服务，分销佣金厂家统一设置</div>
    </div>
    <input type="button" class="jion_us" value="加入店长" >
</div>

<div class="join_pop">
    <div class="pop_wrap">
      <form class="" method="post">
        <div class="tt">店主申请</div>
        <div class="rows">
            <span class="nm">姓  名</span>
            <div class="ipt_wrap">
                <input type="text" name="name" id="user_name" required>
            </div>
        </div>
        <div class="rows">
            <span class="nm">联系方式</span>
            <div class="ipt_wrap">
                <input type="text" name="mobile" id="mobile" required>
            </div>
        </div>
        <input type="submit" name="submit" class="sub_jion" value="确  认" onclick="return checkFormData(this);"/>
        <div class="close" onclick="closePopWind('.join_pop')">×</div>
     </form>
    </div>
</div>
<style>
    .join_pop .rows .ipt_wrap input{float: none;}
</style>
<script>
function checkFormData(o){
   var name = $('#user_name').val();
   if(name.length<2 || name.length>5){
      alert('请输入正确的名字');
      return false;
   }
   var mobile=$('#mobile').val();
   if(!/^1\d{10}$/.test(mobile)){
      alert('请输入正确的手机号码');
      return false;
   }
   return true;
}
    $(function(){
        $('.jion_us').click(function(){
            $('.bg-mark').remove();
            $('body').after('<div class="bg-mark" ></div>');
            $('.bg-mark').fadeIn();
            $('.join_pop').fadeIn();
        });
        $('.join_pop .close').click(function(){
            $('.join_pop').hide()
        })
    });
</script>
</body>
</html>

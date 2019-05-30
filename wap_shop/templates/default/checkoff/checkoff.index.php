<div class="mobile-admin">
    <div class="moblie-wrap">
        <img class="logo" src="<?php echo SHOP_TEMPLATES_URL;?>/images/checkoff/mobile-log.png" alt="">
        <input type="text" class="ipt_ceil username" style="line-height: 30px;" placeholder="请输入账号" id="username">
        <input type="password" class="ipt_ceil password" style="line-height: 30px;" placeholder="请输入密码" id="password">
        <input type="button" class="login_subBtn" value="登&nbsp;录" id="login_subBtn">
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
   var close=function(){
      $('.onClose').click()
   };
   $('#login_subBtn').click(function(){
      var username = $('#username').val();
      var password = $('#password').val();
      if('' == username){
         alertPopWin('请输入用户名',close);
         return false;
      }
      if(''==password){
         alertPopWin('请输入密码',close);
         return false;
      }
      $(this).attr('disabled',true);
      $.post('/wap_shop/index.php?act=checkoff&op=dologin', { username:username, password:password}, function(data){
         if(data.status == 1){
            location.href = '/wap_shop/index.php?act=checkoff&op=index';
         } else {
            alertPopWin(data.msg,close);
         }
         $(this).removeAttr('disabled');
      }.bind(this), 'json');
   })
})
</script>

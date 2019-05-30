<div class="verification_title">
    <span class="tt">
      <a href="/wap_shop/index.php?act=checkoff&amp;op=list">查看核销记录</a>
   </span>
    <p class="lt">
        <?php echo $output['member_name']; ?>，你好
        <a href="/wap_shop/index.php?act=checkoff&amp;op=logout">退出</a>
    </p>
</div>
<h2 class="verification_tt">核销兑换码</h2>
<div class="verification_txt">请输入买家提供的兑换码，核对无误后提交，每个兑换码抵消单笔消费。</div>
<div class="verification_wrap">
    <div class="text_ipt">
        <div class="num"></div>
        <input type="button" class="icon" value="" id="submit_btn">
    </div>
    <div class="numb_tips"></div>
    <ul class="phone_number">
        <li class="nb"><i>1</i></li>
        <li class="nb"><i>2</i></li>
        <li class="nb"><i>3</i></li>
        <li class="nb"><i>4</i></li>
        <li class="nb"><i>5</i></li>
        <li class="nb"><i>6</i></li>
        <li class="nb"><i>7</i></li>
        <li class="nb"><i>8</i></li>
        <li class="nb"><i>9</i></li>
        <li class="prev" id="delone"><i></i></li>
        <li class="nb"><i>0</i></li>
        <li class="close" id="delall"><i></i></li>
    </ul>
    <div class="sub_number" id="sub_number">
        <input type="button" class="ipt">
    </div>
</div>
<style>
    body{background: #f5f5f5}
</style>
<script>
$(function(){
   var close=function(){
      $('.onClose').click()
   };
    var phoneNum=$('.text_ipt .num');
    $('.phone_number .nb').click(function(){
        if(phoneNum.text().length<18){
            _thisNum=$(this).find('i').html();
            phoneNum.html(phoneNum.html()+_thisNum)
        }else{
            alertPopWin('核销码长度不能超过18位',close);
        }

    });
    $('#sub_number,#submit_btn').click(function(){
      var code = $('.text_ipt .num').html();
      var len = code.length;
      // if(len>18||len<15){
      //    alertPopWin('兑换码长度为15-18位',close);
      //    return false;
      // }
      $.post('/wap_shop/index.php?act=checkoff&op=check', { code: code}, function(data){
         alertPopWin(data.msg,close);

         if(data.status == 1){
            $('.text_ipt .num').html('');
         }
      },'json');
   })
   $('#delone').click(function(){
      var str = phoneNum.html();
      var len = str.length;
      if(len > 1){
         str = str.substr(0, len - 1);
      } else {
         str = '';
      }
      phoneNum.html(str);
   })
   $('#delall').click(function(){
      phoneNum.html('')
   })
})
</script>

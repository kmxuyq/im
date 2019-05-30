<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>浏览历史</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.validation.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js" charset="utf-8"></script>
   <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL ?>/js/layer-v2.1/layer/layer.js"></script>
</head>
<style>
.img{
  width: 80px;
  height: 80px;
  overflow: hidden;
  border: none;
  border-radius: 50px;
  background:url(<?php echo getMemberAvatar($output['member_info']['member_avatar']); ?>);
}
</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member&op=home'"></i>
    <h1 class="qz-color">账户信息</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="member_data_wrap">
    <div class="member_data">
        <span>头像</span>
        <div class="imgBox" style="margin-right:10%">
        <form action="index.php?act=member_information&op=upload" enctype="multipart/form-data" id="form_avaster" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <div style="position: relative">
        <img id="save_pic" src="<?php echo getMemberAvatar($output['member_info']['member_avatar'], $output["member_time"]); ?>?<?php echo TIMESTAMP; ?>" alt=""/>
        <input style="opacity:0; position: absolute; width: 80px; height: 80px; top:0; left: 0; z-index: 99" type="file" hidefocus="true" size="1" name="pic" id="pic" file_id="0" multiple maxlength="0" accept="image/*"/>
        </div>
        </div>
        </form>
        <script>
        $(function(){
          $('#save_pic').click(function(){
            $('#pic').click()
          })
          $('#pic').change(function(){
              var PIC_EXT = new Array('.jpg','.jpeg','.gif','.png');
              var pic = $(this).val()
              var ck_pic = pic.substr(pic.indexOf('.'))
              var Error = '';
              if(pic !== ''){
                if(jQuery.inArray(ck_pic, PIC_EXT) === -1){
                return layer.msg("只允许上传'jpg','jpeg','gif','png'格式图片");//Error +="\n只允许上传'jpg','jpeg','gif','png'格式图片。";
                }
              }
              if(Error!==''){
                  alert('错误提示：'+Error)
              }else{
                  $('#form_avaster').submit()
              }
          })
        })
        </script>
    </div>
    <form method="post" id="profile_form" action="index.php?act=member_information&op=member">
<input type="hidden" name="form_submit" value="ok" />
<input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_username'] . $lang['nc_colon']; ?></span>
        <span class="text fr"><?php echo $output['member_info']['member_name']; ?></span>
    </div>
    <div class="member_data_text">
        <span class="text_block fl"><?php echo $output['member_info']['member_email']; ?></span>
        <span class="link_text fr">
        <?php if ($output['member_info']['member_email_bind'] == '1') {?>
            <a href="index.php?act=member_security&op=auth&type=modify_email">更换邮箱</a>
            <?php } else {?>
            <a href="index.php?act=member_security&op=auth&type=modify_email">绑定邮箱</a>
        <?php }?>
        <input type="hidden" name="privacy[email]" value="0" />
        </span>
    </div>
</div>

<div class="member_data_wrap">
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_truename'] . $lang['nc_colon']; ?></span>
        <input type="text" class="data_ipt" maxlength="20" name="member_truename" value="<?php echo $output['member_info']['member_truename']; ?>" />
        <input type="hidden" name="privacy[truename]" value="0"/>
  </div>
    <div class="member_data_text" id="m_sex">
        <span class="text fl"><?php echo $lang['home_member_sex'] . $lang['nc_colon']; ?></span>
        <p class="sex_choose <?php if ($output['member_info']['member_sex'] == 3 or ($output['member_info']['member_sex'] != 2 and $output['member_info']['member_sex'] != 1)) {?>on<?php }?>">
            <input  class="choose_radio" type="radio" name="member_sex" value="3" <?php if ($output['member_info']['member_sex'] == 3 or ($output['member_info']['member_sex'] != 2 and $output['member_info']['member_sex'] != 1)) {?>checked="checked"<?php }?> />
            <span><?php echo $lang['home_member_secret']; ?></span>
        </p>
        <p class="sex_choose <?php if ($output['member_info']['member_sex'] == 2) {?>on<?php }?>">
            <input class="choose_radio" type="radio" name="member_sex" value="2" <?php if ($output['member_info']['member_sex'] == 2) {?>checked="checked"<?php }?> />
            <span><?php echo $lang['home_member_female']; ?></span>
        </p>
        <p class="sex_choose <?php if ($output['member_info']['member_sex'] == 1) {?>on<?php }?>">
            <input  class="choose_radio" type="radio" name="member_sex" value="1" <?php if ($output['member_info']['member_sex'] == 1) {?>checked="checked"<?php }?> />
            <span><?php echo $lang['home_member_male']; ?></span>
        </p>
        <input type="hidden" value="0" name="privacy[sex]" />
    </div>
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_birthday'] . $lang['nc_colon']; ?></span>
        <input type="hidden" name="birthday" maxlength="10" id="birthday" value="<?php echo $output['member_info']['member_birthday']; ?>" />
        <input type="hidden" name="privacy[birthday]" value="0" />

        <input type="text" id="birthday_year" class="birthday_year"/>
        <span class="text fl">年</span>

        <input type="text" id="birthday_months" class="birthday_months"/>
        <span class="text fl">月</span>

        <input type="text" id="birthday_days" class="birthday_days"/>
        <div class="birthday_days">日</div>

    </div>
    <div class="member_data_text"  >
    <span style="float: left;">用户地址：</span>
        <span id="region" class="text fl">
            <input type="hidden" value="<?php echo $output['member_info']['member_provinceid']; ?>" name="province_id" id="province_id">
            <input type="hidden" value="<?php echo $output['member_info']['member_cityid']; ?>" name="city_id" id="city_id">
            <input type="hidden" value="<?php echo $output['member_info']['member_areaid']; ?>" name="area_id" id="area_id" class="area_ids" />
            <input type="hidden" value="<?php echo $output['member_info']['member_areainfo']; ?>" name="area_info" id="area_info" class="area_names" />
            <input type="hidden" value="0" name="privacy[area]" />
            <?php if (!empty($output['member_info']['member_areaid'])) {?>
            <span><?php echo $output['member_info']['member_areainfo']; ?></span>
            <input type="button" value="<?php echo $lang['nc_edit']; ?>" style="line-height: 30px;; background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer" class="edit_region" />
            <select style="display:none;" >
            </select>
            <?php } else {?>
            <select>
            </select>
            <?php }?>
            </span>
    </div>
</div>
<!---->
<div class="member_data_wrap">
    <div class="member_data_text">
        <span class="text_tt fl">QQ</span>
        <input type="text" class="data_ipt2" maxlength="30" name="member_qq" value="<?php echo $output['member_info']['member_qq']; ?>" />
        <input type="hidden" value="0" name="privacy[qq]" />
    </div>
    <div class="member_data_text">
        <span class="text_tt fl"><?php echo $lang['home_member_wangwang']; ?></span>
         <input name="member_ww" type="text" class="data_ipt2" maxlength="50" id="member_ww" value="<?php echo $output['member_info']['member_ww']; ?>" />
         <input name="privacy[ww]" value="0" type="hidden" />
    </div>
</div>
<div class="service_subBtn" style="padding-top: 0">
    <input type="button" id="form_button" class="public_btn1" value="确认提交">
</div>
</form>
 <script type="text/javascript" >
      //处理填写的日期
      $(function(){
          var DateStr =$('#birthday').val();
          SplitDateStr(DateStr,'-');
          $('#form_button').click(function(){
              var year = $.trim($('#birthday_year').val());
              var months = $.trim($('#birthday_months').val());
              var days = $.trim($('#birthday_days').val());
              var name = $('input[name="member_truename"]').val();
              var qq = $('input[name="member_qq"]').val();
              var date = '';
              var Error = '';
              if(qq.length < 5 || qq.length > 12 ){
                 return layer.msg('请输入正确的QQ');
                //   Error += '\n请输入正确的QQ'
              }
              if(year != '' || months != '' || days !=''){
              if(year.length != 4 ){
             return layer.msg('你输入的年份错误');
                //   Error += '\n你输入的年份错误'
              }
              if(months.length != 1 && months.length !=2 || parseInt(months) <1 || parseInt(months) >12){
             return layer.msg('你输入的月份有错误');
                //   Error +='\n你输入的月份有错误'
              }
              if(days.length != 1 && days.length !=2 || parseInt(days) <1 || parseInt(days) >31){
             return layer.msg('你输入的日期有误');
                //   Error += '\n你输入的日期有误'
              }
              }
              if(Error==''){
                  date = year+'-'+months+'-'+days;
                  $('#birthday').val(date)
                  $('#profile_form').submit()
              }else{
                  alert('错误提示：'+Error)
              }
          })

      })

         //输入的日期插入input
          function SplitDateStr(str,index){
              var DateArray = str.split(index)
              $('#birthday_year').val(DateArray[0])
              $('#birthday_months').val(DateArray[1])
              $('#birthday_days').val(DateArray[2])
          }
 </script>
<script>
$(function(){
    regionInit("region");
    $(".sex_choose").click(function(){
        if(!$(this).hasClass('on')){
            $(".sex_choose").removeClass('on');
            $(this).addClass('on');
            $(this).find('.choose_radio').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });
});
</script>
<style>
#region select{
    height:30px;
    margin-top:0px;
    margin-right:5px;
}
body{ background:#f5f5f5;}
</style>
</body>
</html>

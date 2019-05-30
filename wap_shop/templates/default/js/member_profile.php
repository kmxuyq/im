<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>浏览历史</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member_information&op=index'"></i>
    <h1 class="qz-color">账户信息</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<form method="post" id="profile_form" action="index.php?act=member_information&op=member">
<input type="hidden" name="form_submit" value="ok" />
<input type="hidden" name="old_member_avatar" value="<?php echo $output['member_info']['member_avatar']; ?>" />
<div class="member_data_wrap">
    <div class="member_data">
        <span>头像</span>
        <div class="imgBox" style="margin-right:10%">
		<a href="index.php?act=member_information&op=avatar" >
		<img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>" alt=""/>
		</a>
		</div>
    </div>
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_username'].$lang['nc_colon'];?></span>
        <span class="text fr"><?php echo $output['member_info']['member_name']; ?></span>
    </div>
    <!-- <div class="member_data_text">
        <span class="text_block fl"><?php echo $output['member_info']['member_email']; ?></span>
        <span class="link_text fr">
		<?php if ($output['member_info']['member_email_bind'] == '1') { ?>
            <a href="index.php?act=member_security&op=auth&type=modify_email">更换邮箱</a>
            <?php } else { ?>
            <a href="index.php?act=member_security&op=auth&type=modify_email">绑定邮箱</a>
        <?php } ?>
		<input type="hidden" name="privacy[email]" value="0" />
		</span>
    </div> -->
</div>

<div class="member_data_wrap">
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_truename'].$lang['nc_colon'];?></span>
		<input type="text" class="data_ipt" maxlength="20" name="member_truename" value="<?php echo $output['member_info']['member_truename']; ?>" />
        <input type="hidden" name="privacy[truename]" value="0"/>
  </div>
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_sex'].$lang['nc_colon'];?></span>
        <p class="sex_choose">
			<input  class="choose_radio" type="radio" name="member_sex" value="3" <?php if($output['member_info']['member_sex']==3 or ($output['member_info']['member_sex']!=2 and $output['member_info']['member_sex']!=1)) { ?>checked="checked"<?php } ?> />              
            <span><?php echo $lang['home_member_secret'];?></span>
        </p>
        <p class="sex_choose">
			<input class="choose_radio" type="radio" name="member_sex" value="2" <?php if($output['member_info']['member_sex']==2) { ?>checked="checked"<?php } ?> />
            <span><?php echo $lang['home_member_female'];?></span>
        </p>
        <p class="sex_choose">
			<input  class="choose_radio" type="radio" name="member_sex" value="1" <?php if($output['member_info']['member_sex']==1) { ?>checked="checked"<?php } ?> /> 
            <span><?php echo $lang['home_member_male'];?></span>
        </p>
		<input type="hidden" value="0" name="privacy[sex]" />
    </div>
    <div class="member_data_text">
        <span class="text fl"><?php echo $lang['home_member_birthday'].$lang['nc_colon'];?></span>
		<input type="hidden" name="birthday" maxlength="10" id="birthday" value="<?php echo $output['member_info']['member_birthday']; ?>" />
        <input type="hidden" name="privacy[birthday]" value="0" />
		
		<input type="text" class="birthday_year"/>
        <span class="text fl">年</span>

        <input type="text" class="birthday_months"/>
        <span class="text fl">月</span>

        <input type="text" class="birthday_days"/>
        <div class="birthday_days">日</div>

    </div>
	<div class="member_data_text" id="region" >
        <input type="hidden" value="<?php echo $output['member_info']['member_provinceid'];?>" name="province_id" id="province_id">
        <input type="hidden" value="<?php echo $output['member_info']['member_cityid'];?>" name="city_id" id="city_id">
        <input type="hidden" value="<?php echo $output['member_info']['member_areaid'];?>" name="area_id" id="area_id" class="area_ids" />
        <input type="hidden" value="<?php echo $output['member_info']['member_areainfo'];?>" name="area_info" id="area_info" class="area_names" />
        <input type="hidden" value="0" name="privacy[area]" />
		<?php if(!empty($output['member_info']['member_areaid'])){?>
		
		<span class="text fl"><?php echo $output['member_info']['member_areainfo'];?></span> 
		
		<input type="button" class="edit_region" value="<?php echo $lang['nc_edit'];?>" style="background-color: #F5F5F5; width: 60px; height: 32px; border: solid 1px #E7E7E7; cursor: pointer"  />
        <select style="display:none;">
        </select>
        <?php }else{?>
        <select>
        </select>
        <?php }?>  
    </div>
</div>
<!---->
<div class="member_data_wrap">
    <div class="member_data_text">
        <span class="text_tt fl">QQ</span>
		<input type="text" class="data_ipt2" maxlength="30" name="member_qq" value="<?php echo $output['member_info']['member_qq']; ?>" />
        <input type="hidden" value="0" name="privacy[qq]" />
		<span class="link_text fr">第三方账号登录</span>
    </div>
    <div class="member_data_text">
        <span class="text_tt fl"><?php echo $lang['home_member_wangwang'];?></span>
		 <input name="member_ww" type="text" class="data_ipt2" maxlength="50" id="member_ww" value="<?php echo $output['member_info']['member_ww'];?>" />
         <input name="privacy[ww]" value="0" type="hidden" />
    </div>
</div>
<div class="service_subBtn" style="padding-top: 0">
    <input type="button" class="public_btn1" value="确认提交">
</div>
</form>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/ct_select.js.js" charset="utf-8"></script> 
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
body{ background:#f5f5f5;}
</style>
</body>
</html>













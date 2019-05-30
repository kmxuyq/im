<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>虚拟订单列表-圈子</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.7.2.min.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">咨询平台客服</h1>
    <em class="recharge_em">
        <a class="color_blue" href="">咨询</a>
    </em>
</header>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>填写<br />咨询内容</span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span>平台<br />客服回复 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="">
            <span class="cell">咨询完成</span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<form id="mallconsult_form" method="post" action="index.php?act=member_mallconsult&op=save_mallconsult">
<input type="hidden" name="form_submit" value="ok" />
<!---->
<div class="service_wrap ">
    <div class="service_type_select">
        <div class="text">咨询类型</div>
        <select class="service_select" name="type_id" id="type_id">
            <option value="0"><?php echo $lang['nc_please_choose'];?></option>
            <?php if (!empty($output['type_list'])) {?>
            <?php foreach ($output['type_list'] as $val) {?>
            <option value="<?php echo $val['mct_id'];?>"><?php echo $val['mct_name'];?></option>
            <?php }?>
            <?php }?>
        </select>
    </div>

    <div class="service_msg">
        <div class="text">咨询内容</div>
        <textarea id="consult_content" name="consult_content" cols="30" rows="10"></textarea>
    </div>
</div>

<div class="service_subBtn">
    <input id="form_submit" type="button" class="public_btn1" value="确认并提交" />
    <input type="button" onclick="location.href = 'index.php?act=member_mallconsult&op=index'" class="public_btn2" value="返回" />
</div>
</form>
<script type="text/javascript">
$(function(){
	$('#form_submit').click(function(){
		var type_id = $('#type_id').val()
		var content = $('#consult_content').val()
		if(type_id == 0){
			alert('请选择咨询类型')
		}else if($.trim(content) == ''){
			alert('请填写咨询内容')
		}else{
			$('#mallconsult_form').submit()
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>咨询平台客服</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>

<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">咨询平台客服</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="schedule">
    <ul class="list">
        <li class="active">
            <span>填写<br />咨询内容</span>
            <b class="radius_dot"></b>
        </li>
        <li class="<?php if ($output['consult_info']['is_reply'] == 1) {?>active<?php }?>">
            <span>平台<br />客服回复 </span>
            <b class="radius_dot"></b>
        </li>
        <li class="<?php if ($output['consult_info']['is_reply'] == 1) {?>active<?php }?>">
            <span class="cell">咨询完成</span>
            <b class="radius_dot"></b>
        </li>
    </ul>
</div>
<!---->

<div class="platform_msg">
    <!-- 咨询 -->
    <div class="msg_text">
        <span class="text_title">咨询类型:</span>
        <p class="text"><?php echo $output['type_list'][$output['consult_info']['mct_id']]['mct_name'];?></p>
    </div>
    <div class="msg_text">
        <span class="text_title">咨询内容:</span>
        <p class="text"><?php echo $output['consult_info']['mc_content'];?></p>
    </div>
    <div class="msg_text text_last">
        <span class="text_title">咨询时间:</span>
        <p class="text"><?php echo date('Y-m-d H:i:s', $output['consult_info']['mc_addtime']);?></p>
    </div>


    <!-- 回复状态 -->
    <div class="msg_text">
        <span class="text_title">回复状态: </span>
        <p class="text"><?php echo $output['state'][$output['consult_info']['is_reply']];?></p>
    </div>
	<?php if ($output['consult_info']['is_reply'] == 1) {?>
    <div class="msg_text">
        <span class="text_title">回复内容:  </span>
        <p class="text"> <?php echo $output['consult_info']['mc_reply'];?></p>
    </div>
    <?php } ?>
</div>

<div class="service_subBtn">
    <input type="button" onclick="javascript:history.go(-1);" class="public_btn2" value="返回列表" />
</div>

<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
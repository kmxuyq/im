<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>平台咨询服务列表</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<style>
.show_page_1 li{
	float:left;
	margin:8px;
}
</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <a href="index.php?act=member&op=home">
	<i class="ui-icon-return"></i>
	</a>
    <h1 class="qz-color">咨询列表</h1>
    <em class="recharge_em">
        <a class="color_blue" href="index.php?act=member_mallconsult&op=add_mallconsult">咨询</a>
    </em>
</header>
<?php  if (!empty($output['consult_list'])) { ?>
<?php foreach($output['consult_list'] as $val) { ?>
<div class="counseling respond_list">

  <p class="counseling_text"><?php echo $val['mc_content'];?></p>
  
  <div class="counseling_dec">
        <span class="counseling_respond">状态：<?php echo $output['state'][$val['is_reply']];?></span>
        <em class="counseline_times2"><?php echo date('Y-m-d H:i:s', $val['mc_addtime']);?></em>
    </div>
	
    <div class="operate_btn">
	<a href="index.php?act=member_mallconsult&op=mallconsult_info&id=<?php echo $val['mc_id'];?>" >
        <input type="button" value="查看" class="btn"/>
	</a>
	<a href="index.php?act=member_mallconsult&op=del_mallconsult&id=<?php echo $val['mc_id'] ;?>" title="删除">
        <input type="button" value="" class="remove_btn"/>
    </a>
    </div>
</div>
<?php } ?>
<?php } ?>
<div class="counseling respond_list show_page_1">
<?php echo $output['show_page']; ?>
</div>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>
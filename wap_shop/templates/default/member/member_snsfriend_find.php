<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>我的好友</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">我的好友</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div id="leftTabBox" class="tabBox-main">
    <div class="hd tabBox-tt">
        <ul>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=find'" class="on">查找好友</li>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=follow'" class="">我关注的</li>
            <li onclick="location.href = 'index.php?act=member_snsfriend&op=fan'" class="">关注我的</li>
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
        <div class="bd">
            <form id="search_form" method="post" action="index.php?act=member_snsfriend&op=findlist">
            <div class="attention" style="background:#F5F5F5">
                <div class="attention_search">
                    <input name="searchname" id="searchname" value="<?php echo $_GET['searchname'];?>" class="ipt_sch" type="text"/>
                    <input class="ipt_schbtn" type="button" value="<?php echo $lang['snsfriend_search'];?>"/>
                </div>
            </div>
			</form>
        </div>
    </div>
</div>
<script type="text/javascript">
$(function(){
	$('.ipt_schbtn').click(function(){
		var search = $('#searchname').val();
		if(search !== ''){
			$('#search_form').submit()
		}else{
			alert('请输入会员名称(支持模糊搜索)')
		}
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

















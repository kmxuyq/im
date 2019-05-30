
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
</head>


<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="location.href = 'index.php?act=member&op=home'" ></i>
    <h1 class="qz-color">资料管理</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
	 <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<style>
body{ background:#FFF;}
</style>
<section class="ui-container" style="margin-top:-10px;">
    <dl class="management">
    	<dd>
        	<a href="index.php?act=member_information&op=member">
                <span>账户信息</span>
                <i></i>
            </a>
        </dd>
        <dd>
        	<a href="index.php?act=member_security&op=index">
                <span>账户安全</span>
                <i></i>
            </a>
        </dd>
        <dd>
        	<a href="index.php?act=member_address&op=address">
                <span>收货地址</span>
                <i></i>
            </a>
        </dd>
    </dl>
</section>

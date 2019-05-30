<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>忘记密码-圈子</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color">订单支付</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <div class="qz-bk10"></div>
    <div class="qz-padding">
    <div class="qz-txt3 qz-light3 qz-border">
    <h2>订单支付</h2>
    <h3>订单已生成，请扫码支付，祝您购物愉快。</h3><br>
	 <h3>这是您的支付二维码。请用手机扫描二维码完成支付。</h3>
  </div>
        <div class="qz-bk10"></div>
		<div align="center" id="qrcode"></div>
		<div class="qz-bk10"></div>
		<div class="qz-txt3 qz-light3 qz-border">
		<h3>可通过用户中心<a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=member_order">已买到的商品</a>查看订单状态。
		</h3><br>
		<div class="qz-bk10"></div>

		<div class="qz-padding qz-background-white clearfix">
        <div class="qz-ft-l qz-fl">
            <dl class="qz-fl">
                <a href="<?php echo WAP_SITE_URL ;?>" class="ui-btn-lg ui-btn-primary qz-btn-lg"><i class="icon-shopping-cart"></i>继续购物</a>
            </dl>
            
            <dl class="qz-fr"><a href="<?php echo WAP_SHOP_SITE_URL?>/index.php?act=member_order" class="ui-btn-lg ui-btn-primary qz-btn-lg qz-background-yellow"><i class="icon-file-text-alt"></i>查看订单</a>
            </dl>
        </div>
		
    </div>
	
    </div>
    
   <div id="menu"></div>
</section>

<script src="<?php echo WAP_SHOP_SITE_URL?>/resource/js/qrcode.js"></script>

<script>
	var time='<?php echo time()?>';
	if(<?php echo $output['unifiedOrderResult']['code_url'] != NULL; ?>)
	{
		var url = "<?php echo $output['code_url'];?>";
		//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
		var qr = qrcode(10, 'H');
		qr.addData(url);
		qr.make();
		var wording=document.createElement('p');
		wording.innerHTML = "<h1>长按一下二维码，进行支付</h1><br>";
		var code=document.createElement('DIV');
		code.innerHTML = qr.createImgTag();
		var element=document.getElementById("qrcode");
		element.appendChild(wording);
		element.appendChild(code);
	}
	
	//----------------------
	 //--------------
	 getmess();
	
	function ShowMess(){
		$.get('?act=weixinpay&op=ispayok&order_sn=<?php echo $_GET['out_trade_no']; ?>&time='+time,function(result){
			var mess=result.split('|');
			if(mess[0]>0){
				var is_vr='buy';
				//如果是虚拟订单
				if(mess[2]=='vr_order'){
					is_vr='buy_virtual';
				}
				//如果是用户预存款充值
				if(mess[2]=='pd_order'){
					window.location.href='/wap_shop/index.php?act=predeposit&op=pd_log_list';return false;
				}
				window.location.href='?act='+is_vr+'&op=pay_ok&order_sn=<?php echo $_GET['out_trade_no']; ?>&pay_amount='+mess[1];return false;
			}
		});
	}
	function getmess(){
		window.setInterval("ShowMess()",3000);	//显示消息
	}
	</script>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" name="viewport">
	<meta name="viewport" id="viewportid" content="target-densitydpi=285,width=750,user-scalable=no" />
	<meta charset="UTF-8">
	<title>婕珞芙微商城</title>
	<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
	<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/open_popup.css" />
	<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/css/womens_voucher.css"/>
</head>
<body>
<div class="content" align="center">
<img src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher_code1.jpg">
<div class="activation_bg">
<?php 
if(!isset($_SESSION["member_id"])){
	$ref_url=urlencode($_SERVER["REQUEST_URI"]);
	echo "<div style='height:80px;line-height:3.5;font-size:1.6rem'>您还没有<a href='?act=login&ref_url={$ref_url}'>登录</a>，还不是会员，点击此<a href='?act=login&op=register&ref_url={$ref_url}'>用户注册</a>。</div>";
}else{
	echo '<div style="height:70px"></div>';
}
?>	
<div class="input"><input type="text" id="code" placeholder="请输入优惠券兑换码"/><span id="check">点击验证</span></div>
<span id="msg"></span>
<br/><br/>
<div align="center"><span id="activation">确认激活</span></div>
<p>
<span class="title">优惠券使用规则：</span><br/>
1、每个兑换码仅限兑换一张优惠券<br/>
2、每个订单仅可使用一张优惠券<br/>
3、若使用优惠券的订单发生退款退货，仅退回现金支付部分金额，优惠券不予退回<br/>
</p>
</div>
</div>
<script type="text/javascript">
	$(function(){
		
		var login_url='/wap_shop/?act=login&ref_url=<?php echo $_SERVER["REQUEST_URI"]?>';
		$('#check').click(function(){
			var code=$('#code').val();
			$('#msg').text('');
			if(code==''){
				$('#msg').text('请输入优惠券编码');
				return false;
			}
			var url='/wap_shop/?act=voucher_activation&op=voucher_check&voucher_code='+code+"&time=<?php echo time()?>";			
			$.getJSON(url,function(result){
				if(result.state=='login'){
					alert(result.msg);
					window.location.href=login_url;
				}else{
					$('#msg').text(result.msg);
				}
			});
		});
		//--------------
		$('#activation').click(function(){
			var code=$('#code').val();
			$('#msg').text('');
			if(code==''){
				$('#msg').text('请输入优惠券编码');return false;
			}
			var url='/wap_shop/?act=voucher_activation&op=voucher_activation&voucher_code='+code+"&time=<?php echo time()?>";
			$.getJSON(url,function(result){
				
				if(result.state=='login'){
					alert(result.msg);
					window.location.href=login_url;
				}else if(result.state==false){
					$('#msg').text(result.msg);return false;
				} else{
					alert(result.msg);
					window.location.href='/wap_shop/?act=member_voucher';
				}
			});
			
		});
	});

</script>
</body>
</html>
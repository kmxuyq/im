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
<img src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher_code.jpg">
<div class="activation_bg">
<!-- <div style='height:80px;line-height:3.5;font-size:1.6rem'>您还没有<a href='?act=login&ref_url={$ref_url}'>登录</a>，还不是会员，点击此<a href='?act=login&op=register&ref_url={$ref_url}'>用户注册</a>。</div> -->
<div style="height:70px"></div>
<div id="voucher_div">
<img class="voucher50" src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher50-2.gif" style="width:507px"/>
<img class="voucher100" src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher100-2.gif" style="width:507px;display:none"/>
<img class="voucher100-2" src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher100-2-1.gif" style="width:507px;display:none"/>
<img class="voucher118" src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher118.gif" style="width:507px;display:none"/>
<img class="voucher58" src="<?php echo SHOP_TEMPLATES_URL?>/images/active/womens_voucher/voucher58.gif" style="width:507px;display:none"/>
</div>
<br/>
<p>
<span class="title">优惠券使用规则：</span><br/>
1、每个用户仅限兑换一张优惠券<br/>
2、每个订单仅可使用一张优惠券<br/>
3、若使用优惠券的订单发生退款退货，仅退回现金支付部分金额，优惠券不予退回<br/>
</p>
</div>
</div>
<script type="text/javascript">
$(function(){
	//------------
	var member_voucher_url='/wap_shop/?act=member_voucher';
	var login_url="/wap_shop/?act=login&ref_url=<?php echo urlencode($_SERVER["REQUEST_URI"])?>";
	$('#voucher_div img').click(function(){
		var url='/wap_shop/?act=womens_voucher2&op=voucher&type=<?php echo $_GET["type"]?>&voucher_t_id=<?php echo $_GET["voucher_t_id"]?>';
		$.getJSON(url,function(data){
			if(data.state=='false'){
				//弹出消息
				alert(data.msg);
			}else if(data.state=='true'){
				alert(data.msg);//领取成功
				window.location.href=member_voucher_url;
			}else if(data.state=='login'){
				alert(data.msg);
				window.location.href=login_url;
			}else if(data.state=='voucher_all'){
				alert(data.msg);//已领取过跳转到首页
				window.location.href='/wap';
			}
		});
	});
	var type='<?php echo decrypt($_GET["type"])?>';
	var voucher_t_id='<?php echo decrypt($_GET["voucher_t_id"])?>';
	if(type=='50'){
		$('#voucher_div img').hide();
		$('.voucher50').show();
	}else if(type=='100'){
		if(voucher_t_id=='28'){
			$('#voucher_div img').hide();
			$('.voucher100-2').show();
		}else{
			$('#voucher_div img').hide();
			$('.voucher100').show();
		}
	}else if(type=='118'){
		$('#voucher_div img').hide();
		$('.voucher118').show();
	}else if(type=='58'){
		$('#voucher_div img').hide();
		$('.voucher58').show();
	}
});

</script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script> 
<script>
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $output["signPackage"]["appId"];?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $output["signPackage"]["timestamp"];?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $output["signPackage"]["nonceStr"];?>', // 必填，生成签名的随机串
    signature: '<?php echo $output["signPackage"]["signature"];?>',// 必填，签名，见附录1
    jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage"] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});

var wxData = {
	imgUrl: '<?php echo BASE_SITE_URL?>/wap_shop/templates/default/images/weixin/gfloge.jpg', // 分享图标
	link: '<?php echo BASE_SITE_URL.$_SERVER["REQUEST_URI"]?>',// 分享链接
	desc: '婕珞芙女神节特惠，优惠券免费领！',// 分享描述
	title: '婕珞芙“魔药”，令你成迷！',// 分享标题
	share:function(){
		//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
		wx.onMenuShareTimeline({
			title: wxData.desc,
			link: wxData.link,
			imgUrl: wxData.imgUrl,
			success: function() {
				 //用户确认分享后执行的回调函数//wxData.callback();
				},
			cancel: function() {
				//用户取消分享后执行的回调函数
				}
			});		
		//获取“分享给朋友”按钮点击状态及自定义分享内容接口
		wx.onMenuShareAppMessage({
			title: wxData.title,
			desc: wxData.desc,
			link: wxData.link,
			imgUrl: wxData.imgUrl,
			type: '', // 分享类型,music、video或link，不填默认为link
			dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			success: function() {
				//用户确认分享后执行的回调函数//wxData.callback();
				},
			cancel: function() {
				//用户取消分享后执行的回调函数
				}
			});
		},
	callback:function(){	    
		}
	};//wxData{} end

wx.ready(function(){
    wxData.share();
	});
wx.error(function(res) {
	});
</script>
</body>
</html>
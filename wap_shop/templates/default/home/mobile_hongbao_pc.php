<!DOCTYPE html>
<script>
var bs={
        versions:function(){
           var u = navigator.userAgent, app = navigator.appVersion;
           return {//移动终端浏览器版本信息
                trident: u.indexOf('Trident') > -1, //IE内核
                presto: u.indexOf('Presto') > -1, //opera内核
                webKit: u.indexOf('AppleWebKit') > -1, //苹果、谷歌内核
                gecko: u.indexOf('Gecko') > -1 && u.indexOf('KHTML') == -1, //火狐内核
                mobile: !!u.match(/AppleWebKit.*Mobile.*/)||!!u.match(/AppleWebKit/), //是否为移动终端
                ios: !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/), //ios终端
                android: u.indexOf('Android') > -1 || u.indexOf('Linux') > -1, //android终端或者uc浏览器
                iPhone: u.indexOf('iPhone') > -1, //是否为iPhone或者QQHD浏览器
                iPad: u.indexOf('iPad') > -1 //是否iPad                
            };
         }(),
         language:(navigator.browserLanguage || navigator.language).toLowerCase()
    } 
    if(bs.versions.mobile){
        if(bs.versions.android||bs.versions.iPhone||bs.versions.iPad||bs.versions.ios){
            window.location.href='?act=mobile_hongbao&voucher=<?php echo $_GET["voucher"]?>&user=mobile';
        }
    }
</script>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>领取红包</title>
	<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
	<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/baidu.js" ></script>
	<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/hongbao_pc.css" />
	<style>
		#mobile{color:#555}
		
	</style>
	</head>
<body>
	<div class="top">
		<div class="topLeft">
			<div class="telephone"><span></span><input type="input" onKeyUp="this.value=this.value.replace(/\D/g,'').substring(0,11)" id="mobile" value='请输入手机号'/></div>
<p id="mobile_msg"></p>
		</div>
		<div class="topRight">
			<button id="form-button">提交并领取红包</button>
		</div>
	</div>
	<div class="main">
		<div class="quan">
			<div><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/quan1.jpg"></a></div>
			<div><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/quan2.jpg"></a></div>
		</div>
		<div class="shoufajingyou banxin">
			<div class="leftjingyou"><a><img width="509px" src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/jingyou.jpg"></div>
			<div class="rightdicre"><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/shoufajia.jpg"></a></div>
		</div>
		<div class="card">
			<div class="titleTxt">
				<span></span>
				<div class="titleFont"></div>
				<span></span>
			</div>
			<div class="cardPic">
				<a><div class="cardImg"></div></a>
				<a><div class="cardCont"></div></a>
			</div>
			<span class="centerLine"></span>
		</div>
		<div class="jiangpin">
			<div class="no1"><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/ta.jpg"></a></div>
			<span class="shuline"></span>
			<div class="no2"><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/iphone.jpg"></a></div>
		</div>
		<span class="hengLine"></span><span class="hengLine"></span>
		<div class="jiangpin">
			<div class="no1"><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/qingyou1.jpg"></a></div>
			<span class="shuline"></span>
			<div class="no2"><a><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/tangyan.jpg"></a></div>
		</div>
		<div class="para">
			<h2>中奖名单公布时间</h2>
			<span></span>
			<p>本次活动中奖名单将于2016年03月08日在婕珞芙ＧＦ官方微博中公布，</p>
			<p>谜之精灵将会与中奖用户联系确认中奖信息</p>
		</div>
		<div class="actiRules">
			<h2>活动规则</h2>
			<span class=""></span>
			<i>1</i><p>参与@婕珞芙GF官方微博 #2016让红包飞# 活动，抽中GELLÉ FRÈRES（婕珞芙）官方商城优惠券即可注册领取现金优惠券。</p>
			<i>2</i><p>用户进入注册页面，输入手机号和验证码，点击领取红包，一个用户只能领取一次。</p>
			<i>3</i><p>红包用于GELLÉ FRÈRES（婕珞芙）官方商城全场商品购买享满减优惠（详见优惠券使用规则）。</p>
			<i>4</i><p>红包使用时间有限，有效期以实际领取时提示的有效期为准，逾期失效。</p>
			<i>5</i><p>若发现参加活动用户存在不正当方式（包括但不限于恶意套现、机器作弊等），GELLÉ FRÈRES（婕珞芙）有权
禁止其参与活动，取消红包使用资格并<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 将红包作废。</p>
			<i>6</i><p>本次活动最终解释权归GELLÉ FRÈRES（婕珞芙）所有。</p>
		</div>
		<div class="actiRules" style="padding-top:0;">
			<h2>使用规则</h2>
			<span class=""></span>
			<i>1</i><p>领取现金优惠券成功的用户，GELLÉ FRÈRES（婕珞芙）官方商城全场通用。单笔订单仅可使用一张优惠券。</p>
			<i>2</i><p>具体优惠券使用条件如下：<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 100元优惠券（全场商品购买满200减100）；<br/>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 30元优惠券（全场商品购买满100减30）；</p>
			<i>3</i><p>请先到GELLÉ FRÈRES（婕珞芙）官方商城成功注册并登陆后，到个人中心——我的代金券查看并使用；</p>
			<i>4</i><p>如用户需退货，代金券作废，按实际付款金额返还，等价换货可享受代金券活动，非等价将按相应差价处理；</p>
			<i>5</i><p>代金券不可与其他活动共同使用，不找零，不折现，逾期作废；</p>
		</div>
	</div>
</body>
<!-- 预加载 -->
<img src='<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pc/voucher<?php echo $output["voucher"]?>.gif' style="height:1px"/>
<script type="text/javascript">
$(function(){
	var img_no='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic22.gif" style="width:25px"/>';
	var img_ok='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic21.gif" style="width:25px"/>';

    $('#form-button').click(function(){
		var mobile=$('#mobile').val();
    	var msg='';
		$('#mobile_msg').text('');
        if(mobile==''||mobile=='请输入手机号'){
			$('#mobile_msg').text('手机号不能为空');return false;
        }
		if(!isPhone(mobile)){
			$('#mobile_msg').text('手机号格式不对');return false;
		}
		
		//-----
		$.ajax({ 
		type:"GET", 
		async:false,
		dataType: 'json',
		url:"/wap_shop/?act=mobile_hongbao&op=checkmobile&mobile="+mobile, 
		success:function(data){
				if(data.state==false){
					msg=data.msg;						
				}
			} 
		});
		//-----
		if(msg!=''){
			alert(msg);return false;
		}
		window.location.href="/wap_shop/?act=mobile_hongbao&op=hongbao2&mobile="+mobile+'&voucher=<?php echo $_GET["voucher"]?>';
		
	});

	//------------
	$('#mobile').blur(function(){
		$('#mobile_msg').html('');
		var mobile=$(this).val();
		if (mobile != ''){
			if(!isPhone(mobile)){
				$('#mobile_msg').html(img_no);return false;
			}else{
				$('#mobile_msg').html(img_ok);
			}
			$.getJSON('?act=mobile_hongbao&op=checkmobile&mobile='+mobile,function(data){
				if(data.state==false){
					$('#mobile_msg').html(data.msg);
				}
			});
		}
	});
	$('#mobile').focus(function(){
		var str=$(this).val();
		var str2=str.replace(/请输入手机号/,'');
		$(this).val(str2);
	});
	$('#mobile').blur(function(){
		if($(this).val()==''){
			$(this).val('请输入手机号');
		}
	});
	//------------
	function isPhone(str){
	    var myReg = /^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|17|18)\d{9}$/;//;/^(1[0-9]{10})$/;
	    return myReg.exec(str) != null;
	}
	set_top_height();
	$(window).resize(function(){
		set_top_height();
	});
	function set_top_height(){
		var window_w= parseInt($(window).width());
		var w_bl=window_w/1920;
		var top_h=588*w_bl;
		$('.top').css({'margin-top':top_h});
	}
});
</script>
</html>
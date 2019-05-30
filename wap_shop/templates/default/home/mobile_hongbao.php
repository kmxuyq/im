<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="viewport" id="viewportid" content="target-densitydpi=285,width=750,user-scalable=no" />
<title>领取红包</title>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/baidu.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/hongbao.css" />
<link rel="stylesheet" type="text/css" href="/data/resource/icon/css/font-awesome.css" />
</head>
<body>
	<center>
<div class="hongbao_01">	
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-1.jpg"/>
<div class="input_bg"><br/><br/>
	<div class="left"><span><i class="fa fa-mobile"></i></span><input type="input" onKeyUp="this.value=this.value.replace(/\D/g,'').substring(0,11)" id="mobile" placeholder='请输入手机号'/>
	
	</div><div class="right"><input type="submit" value="提交并领取红包" id="form-button"/></div>
	<p id="mobile_msg"></p>
	</div>
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-3.jpg"/>
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-4.jpg"/>
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-5.jpg"/>
<div class="content_t"><p>活动规则</p>
1. 参与@婕珞芙GF官方微博 #2016让红包飞# 活动，抽中GELLÉ FRÈRES
 （婕珞芙）官方商城优惠券即可注册领取现金优惠券。<br/>
2. 用户进入注册页面，输入手机号和验证码，点击领取红包，一个用户只能领取一次。<br/>
3. 红包用于GELLÉ FRÈRES（婕珞芙）官方商城全场商品购买享满减优惠（详见优惠券
使用规则）。<br/>
4. 红包使用时间有限，有效期以实际领取时提示的有效期为准，逾期失效。<br/>
5.代金券不可与其他活动共同使用，不找零，不折现，逾期作废。<br/></div>
<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/hongbao_1-line2.gif"/></div>
</center>
<!-- 预加载 -->
<img src='<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/voucher<?php echo $output["voucher"]?>.gif' style="height:1px"/>
<script type="text/javascript">
$(function(){
	var img_no='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic22.gif" style="width:25px"/>';
	var img_ok='<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/pic21.gif" style="width:25px"/>';

    $('#form-button').click(function(){
    	var msg='';
		$('#mobile_msg').text('');
        if($('#mobile').val()==''){
			$('#mobile_msg').text('手机号不能为空');return false;
        }
		if(!isPhone($('#mobile').val())){
			$('#mobile_msg').text('手机号格式不对');return false;
		}
		
		//-----
		var mobile=$('#mobile').val();
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
		var mobile=$(this).val();
		if (mobile != '') {
			if (!isPhone(mobile)) {
				$('#mobile_msg').html(img_no);
			}
			else {
				$('#mobile_msg').html(img_ok);
			}
			$.getJSON('?act=mobile_hongbao&op=checkmobile&mobile=' + mobile, function(data){
				if (data.state == false) {
					$('#mobile_msg').html(data.msg);
				}
			});
		}
	});
	function isPhone(str){
	    var myReg = /^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|17|18)\d{9}$/;//;/^(1[0-9]{10})$/;
	    return myReg.exec(str) != null;
	}
})
</script>
</body>
</html>
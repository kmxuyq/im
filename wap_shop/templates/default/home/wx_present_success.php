<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
<!-- <meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no" />
<meta content="telephone=no" name="format-detection" />
<!-- UC默认竖屏 ，UC强制全屏 -->
<!-- <meta name="full-screen" content="yes" />
<meta name="browsermode" content="application" />
<!-- QQ强制竖屏 QQ强制全屏 -->
<!-- <meta name="x5-orientation" content="portrait" />
<meta name="x5-fullscreen" content="true" />
<meta name="x5-page-mode" content="app" />
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name=*apple-touch-fullscreen* content=*yes*>
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<meta name="viewport" id="viewportid"	content="target-densitydpi=285,width=600,user-scalable=no" />
-->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="viewport" id="viewportid"	content="target-densitydpi=285,width=600,user-scalable=no" />


<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/base.css">
<link rel="stylesheet" href="../css/wx/base.css">

<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js?v=1.0"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/TouchSlide.1.1.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/layer-v2.1/layer/layer.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>	

<title>申领成功，填写地址</title>

<style>
body {margin: 0 auto;}
</style>
</head>
<body>
	<div align="center">
		<div class="content">
			<!--content home-->
				<div class="address_content">
					<p style="height: 355px;"></p>
					<p class="message_title">恭喜您</p>
					<p class="message"><?php echo Language::get('wx_prize_message_0').'<em>'.$output['goods_type']['goods_name'].'</em>'.Language::get('wx_product').Language::get('wx_gt')?></p>
					<div class="message_div">
						<span class="left">姓名：</span><span class="input"><div align="left">
								<input type="text" id="true_name" value="<?php echo $output['member_info']['member_truename']; ?>"  placeholder="请填写真实姓名" />
							</div></span><span class="right"></span>
					</div>
					<div class="message_div">
						<span class="left">性别：</span><span class="input"><div align="left" >
								<p class="chose_chick <?php if($output['member_info']['member_sex']==1) { ?>on<?php } ?>"><input type="radio" id="member_sex"  value="1" <?php if($output['member_info']['member_sex']==1) { ?>checked="checked"<?php } ?>/>男</p>
								<p class="chose_chick <?php if($output['member_info']['member_sex']==2) { ?>on<?php } ?>"><input type="radio" id="member_sex"  value="2" <?php if($output['member_info']['member_sex']==2) { ?>checked="checked"<?php } ?>  />女</p>
							</div></span><span class="right"></span>
					</div>
					<div class="message_div">
						<span class="left">生日：</span><span class="input"><div align="left">
								<input type="date" id="birthday" value="<?php echo $output['member_info']['member_birthday']?>"  placeholder="例如：1990-01-01"/>
							</div></span><span class="right"></span>
					</div>
					<div class="message_div">
						<span class="left">电话：</span><span class="input"><div align="left">
								<input type="tel" id="mob_phone" value="<?php echo $output['member_info']['member_mobile']?>" placeholder="请填写您的电话" />
							</div></span><span class="right"></span>
					</div>
					<div class="message_div" >
						<span class="left">所在省市：</span><span class="input_city">
						<div align="left" id="region">
							<input type="hidden" value="<?php echo $output['member_info']['member_cityid'];?>"  id="city_id">
                            <input type="hidden" value="<?php echo $output['member_info']['member_areaid'];?>"  id="area_id" class="area_ids" />
                            <input type="hidden" value="<?php echo $output['member_info']['member_areainfo'];?>"  id="area_info" class="area_names" />
                        	<input type="hidden" value="0" name="privacy[area]" />
							<?php if(!empty($output['member_info']['member_areaid'])){?>
    							<span id="not_show">&nbsp;&nbsp;<?php echo $output['member_info']['member_areainfo'];?>
    							<input type="button" value="编辑"></span>
        						<select style="display:none;">
                                 </select>
                             <?php }else{ ?>
                                 <select >
                                 </select>
                        	<?php } ?>
                        	<div style="clear:both"></div>
							</div>
							<div style="clear: both;width: 100%;height: 1px;"></div>
							</span><span class="right"></span>
					</div>
					<div style="clear:both;"></div>
					<div class="message_div detailaddress">
						<span class="left">详细地址：</span><span class="input"><div align="left">
								<input type="text" id="address" value="<?php echo $output['member_info']['address']?>" placeholder="请填写地址" />
							</div></span><span class="right"></span>
					</div>
					<div class="message_div_sub">
						<a href="#"><span class="button">完成提交</span></a>
					</div>

				</div>
			<!--footer-->
			<div class="footer_wx">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/footer.gif" />
			</div>
			<!--content end-->
		</div>
	</div>
</body>
<script type="text/javascript">
function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
$(function(){
	regionInit("region");
    $('#region select').change(function(){
        $('#area_id').val($(this).val());
    });
	/***选择**/
    $(".chose_chick").click(function(){
        if(!$(this).hasClass('on')){
            $(".chose_chick").removeClass('on');
            $(this).addClass('on');
            $(this).find('input[type="radio"]').attr("checked",'checked');
        }else{
            $(this).removeClass('on')
        }
    });

	
	$('input[type="button"]').on('click',function(){
		$('#not_show').hide();
		$('select').show();
		});
	function foc(id) {
        $(id).focus();
    }
	$('span[class="button"]').click(function(){
		var url = 'index.php?act=active&t='+new Date().getTime();
        var type = '<?php echo $_GET['type']?>';
        var goods_id = '<?php echo $output['goods_type']['goods_id']?>';
        var wx = '<?php echo $_GET['wx'];?>';
        var cate_id ='<?php echo $_GET['cate_id'];?>';
        var present_member_id ='<?php echo decrypt($_GET['present_member_id']);?>';
        var member_id='<?php echo $_SESSION['member_id']?>';
        var member_name='<?php echo $_SESSION['member_name']?>';
        
		var true_name = $('#true_name').val();
		var member_sex = $('#member_sex').val();
		var birthday = $('#birthday').val();
		var mob_phone = $('#mob_phone').val();
		var city_id = $('#city_id').val();
		var area_id = $('#area_id').val();
		var area_info = $('#area_info').val();
		var address = $('#address').val();
		
		if(true_name==''){msg="<?php echo $lang['wx_name']?>";foc('#true_name');cherck = false;alert(msg);return false;}
		else if(member_sex=='' && member_sex=='3'){msg="<?php echo $lang['wx_sex']?>";foc('#member_sex');cherck = false;alert(msg);return false;}
		else if(birthday==''){msg="<?php echo $lang['wx_birthday']?>";foc('#birthday');cherck = false;alert(msg);return false;}
		else if(mob_phone==''){msg="<?php echo $lang['wx_mob']?>";foc('#mob_phone');cherck = false;alert(msg);return false;}
		else if(city_id==''){msg="<?php echo $lang['wx_city']?>";foc('#city_id');cherck = false;alert(msg);return false;}
		else if(area_id==''){msg="<?php echo $lang['wx_area']?>";foc('#area_id');cherck = false;alert(msg);return false;}
		else if(area_info==''){msg="<?php echo $lang['wx_info']?>";foc('#area_info');cherck = false;alert(msg);return false;}
		else if(address==''){msg="<?php echo $lang['wx_address']?>";foc('#address');cherck = false;alert(msg);return false;}
		else{
			var data={true_name:true_name,member_sex:member_sex,birthday:birthday,mob_phone:mob_phone,city_id:city_id,area_id:area_id,area_info:area_info,address:address,
					type:type,goods_id:goods_id,wx:wx,cate_id:cate_id,present_member_id:present_member_id,act:'active',op:'index',form_submit:'ok'}
			//$('span[class="right"]').html('<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weixin/ok.png" />');
			$.ajax({
				url: url,
				type: "POST",
				dataType: "json",
				data:data,
				success: function(data){
                    if(data){
                        if(data.done) {
                            window.location.href="?act=weixin_active&op=wx_present_success2";
                        } else {
                            alert(data.msg);
                            if(data.code==9) {
                                var is_wxb = isWeiXin();
                                if(is_wxb) {
                                    WeixinJSBridge.invoke('closeWindow',{
                                    },function(res){});
                                } else {
                                    window.close();
                                }
                            }
                        }
					}
				}
			});
        }
	});
});
</script>
</html>

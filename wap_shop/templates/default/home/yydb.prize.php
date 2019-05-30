<!DOCTYPE html>
<html lang="en">
<head>
    <title>一元去旅行</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元去旅行，九休旅行"/>
    <meta name="author" content="九休旅行"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/reset.css">
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/main.css">
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/TouchSlide.1.1.js"></script>
</head>
<body>
<!-- 支付结果 -->
<div class="header">
    <div class="arrow" onclick="javascript:history.back(-1);"></div>
    <div class="tt">领奖</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>
<div class="award" id="commentForm">
    <div class="award_tt">领奖人信息</div>
    <div class="award-tip">以下信息必须真实有效</div>
    <div class="award-fm">
		<input  id="gp_id" value="<?php echo ($output['gpid']);?>" type="hidden">
        <div class="row">
            <span class="t1">姓名</span>
            <input placeholder="请输入姓名" class="cent" id="name" maxlength="24" type="text">
            <span class="text-tips">必填</span>
        </div>
        <div class="row">
            <span class="t1">证件类型</span>
            <div class="select-down cent">
                <!-- 值显示 -->
                <div class="show-val">
                    <p>身份证</p>
                    <input class="sub-val-btn" id="zj_type" value="身份证" type="hidden"><!-- 隐藏值表单提交 -->
                </div>
            </div>
        </div>
        <div class="row">
            <span class="t1">证件号码</span>
            <i class="cent-wrap" ><input placeholder="请输入证件号码"type="text" maxlength="18"  class="cent" id="zj_num" size="25" datatype="idcard" nullmsg="请填写身份证号码！" errormsg="您填写的身份证号码不对！"/></i>
            <div class="cent-tips"></div>
            <span class="text-tips arrow">必填</span>
        </div>
        <div class="row">
            <span class="t1">手机号码</span>
            <input placeholder="请输入手机号码" maxlength="11" class="cent phoneName" id="tel" datatype="m" type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" maxlength="11" />
            <span class="text-tips">必填</span>
        </div>
    </div>
</div>
<!-- 留言信息 -->
<div class="award-msg">
    <textarea placeholder="请输入留言信息" class="msg-text" id="liuyan" maxlength="120"></textarea>
	<span class="text-tips">留言信息长度1~120字之间</span>
</div>
<input class="bt-btn" value="提交领奖信息" onclick="submitPrize();" type="button">
<link rel="stylesheet" href="css/validform.css">
<script src="js/Validform.js"></script>
<script>
var fla = true;
$(function(){
   // $('body').css({'background':'#fff'})
    $('.select-down p').click(function(){
        if($('.down-main').is(":hidden")){
            $('.down-main').slideDown('fast');
            $('.down-main dd').click(function(){
                var thisVal=$(this).text();
                var thisData=$(this).attr('data-text');
                $('.select-down p').text(thisVal);
                $('.sub-val-btn').val(thisData);
                $('.down-main').slideUp('fast');
            });
        }else{
            $('.down-main').slideUp('fast');
        }
    });
    //
     $("#commentForm").Validform({
        tiptype:2,
        datatype:{//传入自定义datatype类型【方式二】;
            "idcard":function(gets,obj,curform,datatype){
                //该方法由佚名网友提供;

                var Wi = [ 7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2, 1 ];// 加权因子;
                var ValideCode = [ 1, 0, 10, 9, 8, 7, 6, 5, 4, 3, 2 ];// 身份证验证位值，10代表X;

                if (gets.length == 15) {
                    return isValidityBrithBy15IdCard(gets);
                }else if (gets.length == 18){
                    var a_idCard = gets.split("");// 得到身份证数组
                    if (isValidityBrithBy18IdCard(gets)&&isTrueValidateCodeBy18IdCard(a_idCard)) {
                        return true;
                    }
                    return false;
                }
                return false;

                function isTrueValidateCodeBy18IdCard(a_idCard) {
                    var sum = 0; // 声明加权求和变量
                    if (a_idCard[17].toLowerCase() == 'x') {
                        a_idCard[17] = 10;// 将最后位为x的验证码替换为10方便后续操作
                    }
                    for ( var i = 0; i < 17; i++) {
                        sum += Wi[i] * a_idCard[i];// 加权求和
                    }
                    valCodePosition = sum % 11;// 得到验证码所位置
                    if (a_idCard[17] == ValideCode[valCodePosition]) {
                        return true;
                    }
                    return false;
                }

                function isValidityBrithBy18IdCard(idCard18){
                    var year = idCard18.substring(6,10);
                    var month = idCard18.substring(10,12);
                    var day = idCard18.substring(12,14);
                    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                    // 这里用getFullYear()获取年份，避免千年虫问题
                    if(temp_date.getFullYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){

                        return false;
                    }
                    return true;
                }

                function isValidityBrithBy15IdCard(idCard15){
                    var year =  idCard15.substring(6,8);
                    var month = idCard15.substring(8,10);
                    var day = idCard15.substring(10,12);
                    var temp_date = new Date(year,parseFloat(month)-1,parseFloat(day));
                    // 对于老身份证中的你年龄则不需考虑千年虫问题而使用getYear()方法
                    if(temp_date.getYear()!=parseFloat(year) || temp_date.getMonth()!=parseFloat(month)-1 || temp_date.getDate()!=parseFloat(day)){
                        return false;
                    }
                    return true;
                }

            }

        }
    });
});

//表单验证
function  submitPrize(){
	var id = $('#gp_id').val();
	if(id == ""){
		alert("系统错误");
		return;
	}
	var name = $('#name').val();
	if($.trim(name) == ""){
		alert("姓名必填");
		return;
	}else if(name.length > 24){
		alert("姓名长度在1-24字之间");
		return;
	}
	var type = $('#zj_type').val();
	if(type == ""){
		alert("证件类型必选");
		return;
	}
	var no = $('#zj_num').val();
	if(no == ""){
		alert("证件号必填");
		return;
	}
	var liuyan = $('#liuyan').val();
    if(liuyan.length > 0 && liuyan.length > 120){
		alert('留言信息长度(1-120)字之间');
		return;
	}
	var tel = $('#tel').val();
	if(tel == ""){
		alert("手机号必填");
		return;
	}
	if(fla){
		if($('.cent-tips span').hasClass('Validform_right')){
			if(!$('.phoneName').hasClass('Validform_error')){
				$.post('<?php echo BASE_SITE_URL;?>/wap_shop/index.php',{'act':'duobao','op':'setPrizeInf','gid':id,'name':name,'type':type,'num':no,'tel':tel,'liuyan':liuyan},function(msg){
					if(msg === '1'){
						alert('提交成功!');
						fla = false;
						location.href = '<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao';
					}else if(msg === '0'){
                        alert('请检查信息是否填写正确！');
                        return;
                    }else{
						alert('系统繁忙请稍后重试！');
						fla = true;
					}
				});
			}else{
				alert('请输入正确电话号码！');
			}
		}else{alert('请输入正确的证件号码！');}
	}else{
		alert('请不要重复提交！');
	}
}

function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}
</script>
</body>
</html>

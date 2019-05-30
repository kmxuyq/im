<title><?php echo $output['title']; ?></title>
<META HTTP-EQUIV="pragma" CONTENT="no-cache">
<META HTTP-EQUIV="Cache-Control" CONTENT="no-cache, must-revalidate">
<META HTTP-EQUIV="expires" CONTENT="Wed, 26 Feb 1997 08:21:57 GMT">
<META HTTP-EQUIV="expires" CONTENT="0">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/address_member_style.css" />
<style type="text/css">
.footer ul li a {
    text-decoration: none;
}
</style>

    <div class="address_wrap">
        <div class="address_item">
            <div class="address_tt">支付宝账号</div>
            <input class="address_ipt" type="text" id="alipay_account" name="alipay_account" value="<?php echo $output['alipay_info']['alipay_account']; ?>">
        </div>
        <div class="address_item">
            <div class="address_tt">真实姓名</div>
            <input class="address_ipt valid" type="text" id="alipay_account_name" name="alipay_account_name" value="<?php echo $output['alipay_info']['alipay_account_name']; ?>">
        </div>
        <div class="address_item">
            <div class="address_tt">手机号码</div>
            <input class="address_ipt" type="text" id="alipay_account_phone" name="alipay_account_phone" value="<?php echo $output['alipay_info']['alipay_account_phone']; ?>">
        </div>

        <input class="address_submit" id="form_submit_btn" type="submit" value="保存">
    </div>
<script type="text/javascript">
function Trim(str) { return str.replace(/(^\s*)|(\s*$)/g, ""); } 
$(function(){
    $("#form_submit_btn").click(function(){
        var url = 'index.php?act=weixin_spread&op=setting';
        var alipay_account_phone = Trim($("#alipay_account_phone").val());
        var alipay_account_name = Trim($("#alipay_account_name").val());
        var alipay_account = Trim($("#alipay_account").val());
        if(!alipay_account_phone) {
            alert('手机号必填');
        } else if(!alipay_account_name) {
            alert('真实姓名必填');
        } else if(!alipay_account) {
            alert('支付宝账号必填');
        } else {
            var act = "weixin_spread";
            var op = "setting";
            var form_submit = "ok";
            $.ajax({
                    //提交数据的类型 POST GET
                    type:"POST",
                    timeout: 3000,
                    //提交的网址
                    url: url,
                    //提交的数据
                    data:{
                        act:act,
                        op:op,
                        form_submit:form_submit,
                        alipay_account:alipay_account,
                        alipay_account_name:alipay_account_name,
                        alipay_account_phone:alipay_account_phone
                    },
                    //返回数据的格式
                    datatype: "json",//"xml", "html", "script", "json", "jsonp", "text".
                    //在请求之前调用的函数
                    beforeSend:function(){
                        $("#form_submit_btn").val("保存中...");
                    },
                    //成功返回之后调用的函数             
                    success:function(data){
                        $("#form_submit_btn").val("保存");
                        alert("保存成功");
                        window.location.href="<?php echo SPREAD_SITE_URL; ?>";
                    },
                    //调用执行后调用的函数
                    complete: function(XMLHttpRequest, textStatus){
                        if(textStatus=='timeout'){//超时,status还有success,error等值的情况
                    　　　　　  alert("网络异常,请求超时");
                    　　　　}
                    },
                    //调用出错执行的函数
                    error: function(){
                        //请求出错处理
                        $("#form_submit_btn").val("保存");
                        alert("保存失败");
                    }         
            });
        }
    })
})
</script>
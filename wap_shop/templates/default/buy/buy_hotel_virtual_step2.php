<!doctype html>
<html>
<head>
    <title>酒店预订支付</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.less">
    <script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
</head>
<body class="gt_newpage_body">
<form action="index.php?act=payment&op=vr_order" method="POST" id="buy_form">
<div class="train_position_top">
    <div class="arrow" onclick="history.go(-1)"></div>
    <div class="text">在线支付</div>
</div>

<div class="home_choose_msg long">
    <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/gt_home_true.jpg" alt="">
    <div class="msg">
        <h1>恭喜您预定成功!</h1>
        <div class="price_count">
            待付款<span><i>¥</i><em id="item_subtotal"><?php echo $output['order_info']['goods_price']; ?></em></span>
        </div>
    </div>
</div>
<div class="hotal_detail_link">
    <a href="javascript:;">订单详细</a>
</div>
<div class="qz-bottom-b">
    <p>订单号：<?php echo $output['order_info']['order_sn']; ?></p>
    <div class="qz-bk5"></div>
    <p>商&nbsp;&nbsp; 品：<?php echo $output['order_info']['goods_name']; ?></p>
    <div class="qz-bk5"></div>
    <p>入住&nbsp;&nbsp;时间：<?php echo $output['ticket_date'][0]; ?></p>
    <div class="qz-bk5"></div>
    <p>离店&nbsp;&nbsp;时间：<?php echo $output['ticket_date'][1]; ?></p>
    <div class="qz-bk5"></div>
    <p>数&nbsp;&nbsp;量：<em style="color:#DA3228;font-size:18px;"><?php echo $output['order_info']['goods_num']; ?>晚</em></p>
    <div class="qz-bk10"></div>
    <p>价&nbsp;&nbsp;格：￥<em style="color:#DA3228;font-size:18px;"><?php echo $output['order_info']['goods_price']; ?></em></p>
    <div class="qz-bk5"></div>

</div>

<div class="gtdetail_pay_method">
    <h1>支付方式</h1>
<!--    <?php /*foreach($output['payment_list'] as $val) { */?>
        <a href="#" class="wex_pay"   payment_code="<?php /*echo $val['payment_code']; */?>">
            <img src="<?php /*echo SHOP_TEMPLATES_URL;*/?>/images/<?php /*echo $val['payment_code']; */?>.jpg" alt="">
            <span><?php /*echo $val['payment_name']; */?></span>
        </a>
    --><?php /*} */?>
    <a href="#" class="wex_pay"  payment_code="wxpay_2" id="wxpay_2" style="display:" >
        <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weix.jpg" alt="">
        <span>微信支付</span>
    </a>
    <?php if ($output['member_info']['available_predeposit'] > 0 || $output['member_info']['available_rc_balance'] > 0) { ?>
        <?php if ($output['member_info']['available_rc_balance'] > 0) { ?>
            <a href="#" class="wex_pay" payment_code="rcb_pay">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/weix.jpg" alt="">
                <span>使用充值卡<em style="font-size: 12px">（可用金额：<?php echo $output['member_info']['available_rc_balance'];?></em>元）</span>
                <input type="checkbox" value="1" name="rcb_pay">
            </a>
         <?php } ?>
        <?php if ($output['member_info']['available_predeposit'] > 0) { ?>
            <a href="#" class="wex_pay predeposit" payment_code="pd_pay">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/pd_pay.png" alt="">
                <span >使用预存款<em style="font-size: 12px">（可用金额：<?php echo $output['member_info']['available_predeposit'];?></em>元）</span>
                <input type="checkbox"  value="1" id="pd_pay" name="pd_pay" style="display:none">
            </a>
        <?php }?>
    <?php  } ?>
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <input type="hidden" name="order_id" value="<?php echo $output['order_info']['order_id'];?>">

    <div  id="pd_password"  style="display:none ">
        <div class="pd_input_password" style="text-align:right; clear: both">
            支付密码：&nbsp;<input type="password" class="input_pwd" value="" name="password" id="pay-password" maxlength="35">
            <input type="hidden" value="" name="password_callback" id="password_callback">
        </div>
            <span class="qz-padding qz-padding-t clearfix">
                <p class="qz-fr">
                    <a  class="pd_submit"  id="pd_pay_submit" href="javascript:void(0)">使用</a>
                </p>
            </span>
    </div>
    <div class="qz-bk10"></div>
    <div class="qz-padding qz-padding-t clearfix" >
        <p class="qz-fr" id="set_pay_btn" style="display: none">
            <?php if (empty($output['member_info']['member_paypwd'])) {?>
                还未设置支付密码，<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" target="_blank">马上设置</a>
            <?php } ?>
        </p>
    </div>
</div>
</form>
<button class="gt_subbtns">确认支付</button>
</body>
</html>
<script>
    $(document).ready(function(){
        $('#pd_pay_submit').on('click',function(){
            if ($('#pay-password').val() == '') {
                layer.msg('请输入支付密码!');return false;
            }
            $('#password_callback').val('');
            $.get("index.php?act=buy&op=check_pd_pwd", {'password':$('#pay-password').val()}, function(data){
                if (data == '1') {
                    $('#password_callback').val('1');
                    $('#pd_password').hide();
                } else {
                    $('#pay-password').val('');
                    layer.msg('密码错误,请重新输入');

                }
            });
        });
        ua = navigator.userAgent.toLowerCase();
        if(ua.match(/MicroMessenger/i)=="micromessenger"){
            $('#wxpay_2').show();
        }

        $('.gtdetail_pay_method > a').on('click',function(){
            $('.gtdetail_pay_method > a').removeClass('using');
            $(this).addClass('using');
            $('#payment_code').val($(this).attr('payment_code'));
        });
    });
    //支付调用
    $('.gt_subbtns').on('click',function(){
        if ($('#payment_code').val() == '') {
            layer.msg('请选择支付方式');return false;
        }
        if ($('#payment_code').val() == 'wxpay_2' && ua.match(/MicroMessenger/i) == "micromessenger") {

            window.location.href='<?php echo $output["wxpay_jsapi_url"]?>';return false;//这里跳转后要加上退出，否则无效
        }
        else if ($('#payment_code').val() == 'alipay_2') {
            window.location.href = '<?php echo $output["alipay_api_url"]?>';return false;
        }else if($('#payment_code').val() == 'wxpay'){
            window.location.href = '<?php echo $output['wxpay_url']; ?>';return false
        }
        else if($('#payment_code').val() == 'pd_pay'){ //预存款
            $("#buy_form").attr("action", "index.php?act=buy_virtual&op=hotel_vr_pay");
            $('#buy_form').submit();
        }else if($('#payment_code').val() == 'rcb_pay'){ //充值卡付款
            $("#buy_form").attr("action", "index.php?act=buy_virtual&op=hotel_vr_pay");
            $('#buy_form').submit();
        }
        else{
            $('#buy_form').submit();
        }
    });
    $(function(){
        $('.hotal_detail_link').click(function(){
            var _detail = $('.qz-bottom-b');
            if(_detail.is(':hidden')){
                _detail.slideDown('fast')
            }else{
                _detail.slideUp('fast')
            }
        });

        $('.predeposit').click(function(){
            var _pd = $('#pd_password');
            var _sp = $('#set_pay_btn');
            if(_pd.is(':hidden')){
                _pd.show()
                _sp.show()
            }else{
                _pd.hide();
                _sp.hide();
            }
        })
    });
</script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
<style>
    .qz-bottom-b p{line-height: 25px; padding: 0 10px; background: #fff;}
    .qz-bottom-b{ display: none}
    #pd_password{display: none;}
    .gtdetail_pay_method *{box-sizing: content-box    }
    .gtdetail_pay_method .using{background: #ebebeb}
</style>




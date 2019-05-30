<!doctype html>
<html>
<head>
    <title>酒店预订</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="--------template_kewords----------"/>
    <meta name="author" content="--------template_author----------"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
    <script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>
</head>
<body class="gt_newpage_body">

<div class="train_position_top">
    <div class="arrow" onclick="location.href='index.php?act=goods&op=index&from=1&goods_id=<?php echo $output['goods_info']['goods_id'];?>'"></div>
    <div class="text">选房成功</div>
</div>

<div class="home_choose_msg">
    <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/gt_home_true.jpg" alt="">
    <p>恭喜您选房成功!</p>
</div>
<form action="<?php echo urlShopWAP('buy_virtual','buy_step3');?>" method="POST" id="form_buy" name="form_buy">
<div class="hotal_detail">
    <h1 class="tt"><?php echo $output['goods_info']['goods_name']; ?></h1>
    <?php if($output['goods_info']['input_spec']){?>
        <p class="room"><?php echo $output['goods_info']['input_spec'] ?></p>
    <?php }?>
    <div class="check_in_time">
        <div class="start">
            <span class="t1">入住</span>
            <span class="t2"><?php echo $output['goods_info']['hotel_state_time']; ?></span>
        </div>
        <div class="end">
            <span class="t1">离店</span>
            <span class="t2"><?php echo $output['goods_info']['hotel_end_time']; ?></span>
        </div>
        <div class="count"><?php echo $output['goods_info']['hotel_num']; ?>晚</div>
<!--        <a href="#" class="edit_time">修改时间</a>-->
        <input type="hidden" value="<?php echo $output['goods_info']['hotel_state_time'];?>" name="hotel_state_time">
        <input type="hidden" value="<?php echo $output['goods_info']['hotel_end_time'];?>" name="hotel_end_time">
        <input type="hidden" value="<?php echo $output['goods_info']['hotel_num'];?>" name="goods_num">
        <input type="hidden" value="<?php echo $output['goods_info']['hotel_total'];?>" name="goods_price">
        <input type="hidden" value="<?php echo encrypt($output['goods_info']['hotel_total']);?>" name="goods_total">
        <input type="hidden" value="<?php echo $output['goods_info']['storage_state'];?>" name="hotel_stock">
        <input type="hidden" name="is_share" value="<?php echo $output['goods_info']['isshare'];?>">
        <input type="hidden" value="2" name="calendar_type">
        <input type="hidden" value="<?php echo $output['goods_info']['state']; ?>" name="state"><!--判断商品是否能购买必填 1或0 -->
        <input type="hidden" name="get_info" value="<?php echo encrypt(json_encode($_GET));?>">
        <input type="hidden" name="goods_id" value="<?php echo $output['goods_info']['goods_id'];?>">
        <input type="hidden" name="goods_name" value="<?php echo $output['goods_info']['goods_name'];?>">
        <input type="hidden" name="input_spec" value="<?php echo $output['goods_info']['input_spec'];?>">
        <input type="hidden" name="calendar_array" value="<?php echo $output['calendar_array'];?>">
    </div>
    <div class="hotal_price">
        <span>订单总价</span>
        <em>¥<?php echo $output['goods_info']['hotel_total'];?></em>
    </div>
</div>

<div class="hotal_user_data">
    <div class="item">
        <div class="row">
            <span class="t1">姓名</span>
            <input type="text" name="buyer_hotel_name" id="buyer_hotel_name" placeholder="请填写入住姓名">
            <span class="require_fields">必填</span>
        </div>
    </div>
    <div class="item">
        <div class="row">
            <span class="t1">手机号</span>
            <input type="text" name="buyer_phone" id="buyer_phone" placeholder="电子券接收手机" maxlength="11">
            <span class="require_fields">必填</span>
        </div>
        <div class="tips">*请仔细填写手机号，以确保电子兑换券准确发送到您的手机上。</div>
    </div>
    <div class="item">
        <div class="row">
            <span class="t1">身份证</span>
            <input type="text" name="buyer_id_card" id="buyer_id_card" placeholder="入住登记身份证号" maxlength="18">
            <span class="require_fields">必填</span>
        </div>
        <div class="tips">*住宾馆要身份证是法律的要求，它将作为每个人独一无二的公民身份的证明工具。</div>
    </div>
    <div class="item">
        <div class="row" style="height: auto;padding-bottom: 0.3rem">
            <span class="t1">留言备注</span>
            <textarea maxlength="60" type="text" name="buyer_msg" placeholder="建议填写和商家达成一致的说明" ></textarea>
        </div>
    </div>
    <button class="gt_subbtns">提交订单</button>
</div>
</form>
</body>
</html>

<script>


var buyer_hotel_name,buyer_id_card,buyer_phone;
$('.gt_subbtns').on('click',function(){
    buyer_hotel_name = $('#buyer_hotel_name').val();
    buyer_id_card    = $('#buyer_id_card').val();
    buyer_phone      = $('#buyer_phone').val();
    if(buyer_hotel_name ==''){
        layer.msg("请填写姓名");return false;
    }
    if(buyer_phone ==''){
        layer.msg('请填写手机号码');return false;
    }
    var buyer_phoneRule = /^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0-9]|170)\d{8}$/;
    if (!buyer_phoneRule.test(buyer_phone)) {
        //自定义错误提示
        layer.msg('手机号码格式错误');return false;
    }
    if(buyer_id_card ==''){
        layer.msg('请填写身份证号');return false;
    }
    var buyer_id_cardRule = /(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/;
    if (!buyer_id_cardRule.test(buyer_id_card)) {
        layer.msg('请填写正确的身份证号码');return false;
    }
    $('#form_buy').submit();
});
</script>







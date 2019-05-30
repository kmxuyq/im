<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>交易投诉管理</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" ></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<style>
.show_page_1 li{
	float:left;
	margin:8px;
}
</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <a href="index.php?act=member&op=home">
    <i class="ui-icon-return" onclick="history.back()"></i>
	</a>
    <h1 class="qz-color">投诉管理</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<div class="tab_text1">
    <?php  if (count($output['list'])>0) { ?>
    <?php foreach($output['list'] as $val) {
        $goods = $output['goods_list'][$val['order_goods_id']];
    ?>
    <div class="refund">

        <div class="refund_tp">投诉时间：<?php echo date("Y-m-d H:i:s",$val['complain_datetime']);?></div>
        <div class="refund_main">
            <div class="refund_numb">
			<span>投诉主题：<?php echo $val['complain_subject_content'];?></span>
				<a href="index.php?act=member_complain&op=complain_show&complain_id=<?php echo $val['complain_id'];?>">
                <input value="查看" type="button">
				</a>
			<span><?php echo $val['accused_name'];?></span>
            </div>

            <div class="refund_text">

                <p class="imgBox">
				<a target="_blank" href="index.php?act=goods&op=index&goods_id=<?php echo $goods['goods_id']; ?>">
					<img src="<?php echo thumb($goods,60); ?>"/>
			    </a>
				</p>

                <div class="text">
                    <h3>
					<a target="_blank" href="index.php?act=goods&op=index&goods_id=<?php echo $goods['goods_id']; ?>">
					<?php echo $goods['goods_name']; ?>
					</a>
					</h3>
                </div>

            </div>

            <div class="refund_state">
                <p class="text_dec" style="width:100%">投诉状态：<?php
				if(intval($val['complain_state'])===10) echo $lang['complain_state_new'];
				if(intval($val['complain_state'])===20) echo $lang['complain_state_appeal'];
				if(intval($val['complain_state'])===30) echo $lang['complain_state_talk'];
				if(intval($val['complain_state'])===40) echo $lang['complain_state_handle'];
				if(intval($val['complain_state'])===99) echo $lang['complain_state_finish'];
				?>
				<?php if(intval($val['complain_state'])==10) { ?>
				<a href="index.php?act=member_complain&op=complain_cancel&complain_id=<?php echo $val['complain_id']; ?>" style="display:block;float:right;margin-right:15px;">取消</a>
				<?php } ?>
				</p>
            </div>

        </div>

    </div>
	<?php } ?>
    <?php }else{ ?>
        <div  class="no_message">暂无任何投诉信息</div>
    <?php } ?>
	<div class="refund show_page_1" style="margin-top:0px;">
	<?php echo $output['show_page'];?>
	</div>
</div>
<script>
$(function(){
    $('.complaint_choose .adchek').click(function(){
        var this_parent=$(this).parent();
        if($(this).is(':checked')){
            $('.complaint_choose li').removeClass('on');
            this_parent.addClass('on');
        }
    })
})
</script>

<style>
body{ background:#f5f5f5;}
.no_message{text-align: center; font-size: 20px; font-family: "Microsoft Yahei"; height:300px; line-height: 300px; background: #fff}
</style>
<script>
    $(function(){
        $('.no_message').css({
            height:$(window).height() - 150
        })
    })
</script>
</body>
</html>














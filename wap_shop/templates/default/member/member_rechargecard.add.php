<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>平台充值卡充值</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
</head>

<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">平台充值卡充值</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
    <form method="post" id="rechargecard_form" action="index.php">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="act" value="predeposit" />
      <input type="hidden" name="op" value="rechargecard_add" />
<section class="ui-container" style="margin-top:40px;">
    <div class="remainingSumBox">
		<dl class="cashApply">
            <dd>
                <h1 class="cashApplyTitle">
                    充值金额
                </h1>
                <div class="cashApplyInput cashApplyInput2">
                	<input class="fl" type="text" name="rc_sn" id="rc_sn" />
                </div>
            </dd>
        </dl>
        <div class="remainingSum1Sumbit">
        	<a  id="form_submit">提交</a>
        </div>
    </div>
</section>
</form>
<script>
$(function(){
	$('#form_submit').click(function(){
	var Cate = $('#rc_sn').val();
	if($.trim(Cate).length >50){
		alert('平台充值卡号长度小于50')
	}else if($.trim(Cate) ===''){
		alert('请填写你的充值卡号')
	}else{
		$('#rechargecard_form').submit()
	}
	})
})
</script>
</body>
</html>

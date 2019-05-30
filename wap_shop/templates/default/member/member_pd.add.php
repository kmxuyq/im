<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>在线充值</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">在线充值</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header><br><br>
<form method="post" id="recharge_form" action="index.php">
<input type="hidden" name="form_submit" value="ok" />
<input type="hidden" name="act" value="predeposit" />
<input type="hidden" name="op" value="recharge_add" />
<section class="ui-container">
    <div class="remainingSumBox">
		<dl class="cashApply">
            <dd>
                <h1 class="cashApplyTitle">
                    充值金额：
                </h1>
                <div class="cashApplyInput cashApplyInput2">
					<input name="pdr_amount" type="text" class="fl" id="pdr_amount" maxlength="6" onkeyup="this.value=this.value.replace(/[^\d\.]/g,'') " onafterpaste="this.value=this.value.replace(/[^\d]/g,'')"/>
                </div>
            </dd>
        </dl>
        <div class="remainingSum1Sumbit">
        	<a href="javascript:" id="form_button">提交</a>
        </div>
    </div>
</section>
</form>
<script type="text/javascript" >
$('#form_button').click(function(){
	var pdr = parseFloat($('#pdr_amount').val())
	if(pdr == ''){
		alert('请填写你要充值的金额')
	}else if(pdr < 0.01){
		alert('温馨提示：充值金额不能小于0.01(元)RMB')
	}else{
		$('#recharge_form').submit()
	}
})
</script>
</body>
</html>

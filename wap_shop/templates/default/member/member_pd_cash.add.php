<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>提现金额</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<style type="text/css">
	p{font-size:12px;padding:8px;margin:5px;}
    p strong{color:333;}
</style>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">提现金额</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
<form method="post" id="cash_form" action="index.php?act=predeposit&op=pd_cash_add">
    <input type="hidden" name="form_submit" value="ok" />

    <div class="qz-bk1"></div>
    <div class="qz-padding1">
	<p>（当前可用金额：<strong><?php echo floatval($output['member_info']['available_predeposit']); ?></strong>&nbsp;&nbsp;元）</p>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="margin:10px;padding:8px;">
            <input  name="pdc_amount" type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入提现金额"  id="pdc_amount" maxlength="10" >
        </div>
	</div>
	<div class="qz-bk1"></div>
    <div class="qz-padding1">
	<p class="single-text-sytle">强烈建议优先填写国有4大银行(中国银行、中国建设银行、中国工商银行和中国农业银行)
      请填写详细的开户银行分行名称。</p>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="margin:10px;padding:8px;">
            <input  name="pdc_bank_name" type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入收款银行"  id="pdc_bank_name" maxlength="40" >
        </div>
	</div>
	<span class="qz-f12 qz-fr"></span>
	<div class="qz-bk1"></div>
    <div class="qz-padding1">
	<p class="single-text-sytle">银行账号或虚拟账号(支付宝、财付通等账号)</p>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="margin:10px;padding:8px;">
            <input  name="pdc_bank_no" type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入收款账号"  id="pdc_bank_no" maxlength="30" >
        </div>
	</div>
<div class="qz-bk1"></div>
    <div class="qz-padding1">
	<p class="single-text-sytle">收款账号的开户人姓名</p>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="margin:10px;padding:8px;">
            <input  name="pdc_bank_user" type="text" class="qz-fl qz-txt2 qz-light" placeholder="请输入开户人姓名"  id="pdc_bank_user" maxlength="10" >
        </div>
	</div>
	<div class="qz-bk1"></div>
    <div class="qz-padding1">
	<p class="single-text-sytle">支付密码</p>
        <div class="qz-border qz-padding5 qz-background-white clearfix" style="margin:10px;padding:8px;">
            <input  name="password" type="password" class="qz-fl qz-txt2 qz-light" placeholder="请输入支付密码"  id="password" maxlength="20" >
        </div>
		<?php if (!$output['member_info']['member_paypwd']) {?>
              <p class="single-text-sytle"><strong>还未设置支付密码</strong><a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=member_security&op=auth&type=modify_paypwd" class="ncm-btn-mini ncm-btn-acidblue vm ml10" target="_blank" style="color: #DA3228">马上设置</a></p>
              <?php } ?>
	</div>
    <div class="qz-padding qz-background-white clearfix">
        <div class="qz-ft-l qz-fl" style="width: 100%">
            <div style="padding: 10px 0;">
               <?php if($output['share_settings']['apply_amount']>$output['member_info']['available_predeposit']):?>
                  当前余额低于<?php echo $output['share_settings']['apply_amount'];?>,不可申请提现。
               <?php else:?>
				<input type="submit" class="ui-btn-lg ui-btn-primary qz-btn-lg" value="确认提现" />
         <?php endif;?>
            </div>
            <div style="clear: both;width: 100%;margin: 0 auto;"><a href="javascript:history.go(-1);" class="ui-btn-lg ui-btn-primary qz-btn-lg qz-background-yellow"><i class="icon-file-text-alt"></i>取消并返回</a>
            </div>
        </div>

    </div>
  </form>

<div id="menu"></div>
</section>
<style>
    .single-text-sytle{line-height: 17px;padding: 0 12px;}
</style>
<script type="text/javascript">
$(function(){
    nav();
    document.ontouchmove=false;
	$('#cash_form').validate({
    	submitHandler:function(form){
			ajaxpost('cash_form', '', '', 'onerror')
		},
         errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        rules : {
	        pdc_amount      : {
	        	required  : true,
	            number    : true,
	            min       : 0.01,
	            max       : <?php echo floatval($output['member_info']['available_predeposit']); ?>
            },
            pdc_bank_name :{
            	required  : true
            },
            pdc_bank_no : {
            	required  : true
            },
            pdc_bank_user : {
	        	required  : true
	        },
	        password : {
	        	required  : true
	        }
        },
        messages : {
        	pdc_amount	  : {
            	required  :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	number    :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	min    	  :'<i class="icon-exclamation-sign"></i>请正确输入提现金额',
            	max       :'<i class="icon-exclamation-sign"></i>请正确输入提现金额'
            },
            pdc_bank_name :{
            	required   :'<i class="icon-exclamation-sign"></i>请输入收款银行'
            },
            pdc_bank_no : {
            	required   :'<i class="icon-exclamation-sign"></i>请输入收款账号'
            },
            pdc_bank_user : {
	        	required  : '<i class="icon-exclamation-sign"></i>请输入开户人姓名'
	        },
	        password : {
		        required : '<i class="icon-exclamation-sign"></i>请输入支付密码'
		    }
        }
    });
});
</script>

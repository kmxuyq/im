<?php defined('InShopNC') or exit('Access Invalid!');?>


<form method="POST" id="inv_form" action="index.php">
<input type="hidden" value="buy" name="act">
<input type="hidden" value="add_inv" name="op">
<input type="hidden" name="form_submit" value="ok"/>
    <div class="qz-padding qz-background-white qz-bottom-b">
        发票类型
      </div>
      <div class="qz-padding qz-background-white qz-bottom-b">
            <label class="ui-radio" for="radio">
                <input type="radio" checked name="invoice_type" value="1">普通发票<?php if (!$output['vat_deny']) {?><input type="radio" name="invoice_type" value="2">
              增值税发票<?php }?>
            </label>
        </div>
        <div class="qz-padding qz-background-white qz-bottom-b">
            发票抬头<?php echo $lang['nc_colon'];?><p>
            <div id="invoice_panel" class="qz-padding  qz-background-white">
            	<select name="inv_title_select" class="qz-select">
                   	<option value="person">个人</option>
                  	<option value="company">单位</option>
            	</select>
            	<div class="qz-bk10"></div>
            	<input class="az-input" style="display:none" name="inv_title" id="inv_title" placeholder="单位名称" value="">
            </div>
   
        发票内容<p>
        <div class="qz-padding  qz-background-white">
        <select id="inv_content" name="inv_content" class="qz-select">
            <option selected value="明细">明细</option>
            <option value="婕洛夫">婕洛夫</option>
            <option value="阿正">精品</option>
        </select>
     </div>
</div>
</form> 

<div class="qz-padding clearfix">
   <span class="qz-fl" style="width:48%;"><a id="hide_invoice_list" class="ui-btn-lg" href="javascript:void(0);"><?php echo $lang['cart_step1_invoice_submit'];?></a></span>
   <span class="qz-fr" style="width:48%;"><a id="cancel_invoice" class="ui-btn-lg ui-btn-primary qz-background-yellow" href="javascript:void(0);">不需要发票</a></span>
</div>

<script>
var postResult = false;
$(function(){
    $.ajaxSetup({async : false});
    //不需要发票
    $('#cancel_invoice').on('click',function(){
        $('#invoice_id').val('');
        hideInvList('不需要发票');
    });

    <?php if (empty($output['inv_list'])) {?>
    $('input[nc_type="inv"]').click();
    <?php } ?>
    //保存发票信息
    $('#hide_invoice_list').on('click',function(){
        var content = '';
        //如果是新增发票信息
        if ($('input[name="invoice_type"]:checked').val() == 1){
            //如果选择普通发票
            if ($('select[name="inv_title_select"]').val() == 'person'){
                content = '普通发票 个人 ' + $('select[name="inv_content"]').val();
            }else if($.trim($('#inv_title').val()) == '' || $.trim($('#inv_title').val()) == '单位名称'){
                showDialog('请填写单位名称', 'error','','','','','','','','',2);return false;
            }else{
                content = '普通发票 ' + $.trim($('#inv_title').val())+ ' ' + $('#inv_content').val();
            }
        }else{
            content = '增值税发票 ' + $.trim($('input[name="inv_company"]').val()) + ' ' + $.trim($('input[name="inv_code"]').val()) + ' ' + $.trim($('input[name="inv_reg_addr"]').val());
            //验证增值税发票表单
            if (!$('#inv_form').valid()){
                return false;
            }
        }
        var datas=$('#inv_form').serialize();
        
        $.post('index.php',datas,function(data){
            if (data.state=='success'){
                $('#invoice_id').val(data.id);
                postResult = true;
            }else{
                showDialog(data.msg, 'error','','','','','','','','',2);
                postResult = false;
            }
        },'json');
        if (postResult){
            hideInvList(content);
        }
    });
    
	$('input[name="invoice_type"]').on('click',function(){
		if ($(this).val() == 1) {
			$('#invoice_panel').show();
			$('#vat_invoice_panel').hide();
		} else {
			$('#invoice_panel').hide();
			$('#vat_invoice_panel').show();
		}
	});
	$('select[name="inv_title_select"]').on('change',function(){
	    if ($(this).val()=='company') {
	        $('#inv_title').show();
	    } else {
	        $('#inv_title').hide();
	    }
	});

    $('#inv_form').validate({
        rules : {
            inv_company : {
                required : true
            },
            inv_code : {
                required : true
            },
            inv_reg_addr : {
                required : true
            },
			inv_reg_phone : {
				required : true
			},
            inv_reg_bname : {
                required : true
            },
            inv_reg_baccount : {
                required : true
            },
            inv_rec_name : {
                required : true
            },
            inv_rec_mobphone : {
                required : true
            },            
            area_id : {
                required : true,
                min   : 1,
                checkarea:true
            },
            inv_goto_addr : {
                required : true
            }
        },
        messages : {
            inv_company : {
                required : '<i class="icon-exclamation-sign"></i>单位名称不能为空'
            },
            inv_code : {
                required : '<i class="icon-exclamation-sign"></i>纳税人识别号不能为空'
            },
            inv_reg_addr : {
                required : '<i class="icon-exclamation-sign"></i>注册地址不能为空'
            },
			inv_reg_phone : {
				required : '<i class="icon-exclamation-sign"></i>注册电话不能为空'
			},
            inv_reg_bname : {
                required : '<i class="icon-exclamation-sign"></i>开户银行不能为空'
            },
            inv_reg_baccount : {
                required : '<i class="icon-exclamation-sign"></i>银行账户不能为空'
            },
            inv_rec_name : {
                required : '<i class="icon-exclamation-sign"></i>收票人姓名不能为空'
            },
            inv_rec_mobphone : {
                required : '<i class="icon-exclamation-sign"></i>收票人手机号不能为空'
            },
            area_id : {
                required : '<i class="icon-exclamation-sign"></i>请选择地区',
                min : '<i class="icon-exclamation-sign"></i>请选择地区',
                checkarea:'<i class="icon-exclamation-sign"></i>请选择地区'
            },
            inv_goto_addr : {
                required : '<i class="icon-exclamation-sign"></i>送票地址不能为空'
            }
        }
    });
});
</script>
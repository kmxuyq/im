<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
select{box-sizing:border-box;height:40px;width:33%;border:1px solid #d0d0d0;float:left;display:inline;}
.error{color:red}
</style>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/address.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js" />

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL?>/js/layer-v2.1/layer/layer.js"></script>
  <form method="POST" id="addr_form" action="index.php">
    <input type="hidden" value="buy" name="act">
    <input type="hidden" value="add_addr" name="op">
    <input type="hidden" name="form_submit" value="ok"/>
    <input type="hidden" name="id" value="<?php echo $output['addr_info']['address_id']; ?>"/>

<div class="remainingSumBox">
        <dl class="cashApply">
            <dd>
                <h1 class="cashApplyTitle">
                    *<?php echo $lang['cart_step1_input_true_name'].$lang['nc_colon'];?>
                </h1>
                <div class="cashApplyInput cashApplyInput2">
                    <input type="text" name="true_name" class="fl"  maxlength="20" id="true_name" value="<?php echo $output['addr_info']['true_name']; ?>"/>
                </div>
            </dd>
            <dd>
                <h1 class="cashApplyTitle">
                    *</i><?php echo $lang['cart_step1_area'].$lang['nc_colon'];?>
                </h1>
                <div id="region" class="cashApplyInput cashApplyInput2">
                  <select>
                  </select>

                  <input type="hidden" value="" name="city_id" id="city_id" class="fl">
                  <input type="hidden" name="area_id" id="area_id" class="area_ids"/>
                  <input type="hidden" name="area_info" id="area_info" class="area_names"/>
                  <input type="hidden" name="area" id="area" value="" />
                </div>

         </dd>

        <dd>
                <h1 class="cashApplyTitle">
                    *<?php echo $lang['cart_step1_whole_address'].$lang['nc_colon'];?>
                </h1>
                <div class="cashApplyInput cashApplyInput2">
                <input type="text"  class="fl" name="address" id="address" maxlength="80" value="<?php echo $output['addr_info']['address']; ?>"/>
                <p><?php echo $lang['cart_step1_true_address'];?></p>
                </div>
            </dd>
            <dd>
                <h1 class="cashApplyTitle">
                    *<?php echo $lang['cart_step1_mobile_num'].$lang['nc_colon'];?>
                </h1>
                <div class="cashApplyInput cashApplyInput2">
                <input type="text" class="fl" name="mob_phone" id="mob_phone" maxlength="15" value="<?php echo $output['addr_info']['mob_phone']; ?>"/>
                </div>
            </dd>
            <dd>
                <h1 class="cashApplyTitle">
                    (或)<?php echo $lang['cart_step1_phone_num'].$lang['nc_colon'];?>
                </h1>
                <div class="cashApplyInput cashApplyInput2">
                <input type="text" class="fl" id="tel_phone" name="tel_phone" maxlength="20" value="<?php echo $output['addr_info']['tel_phone']; ?>"/>
                </div>
            </dd>
        </dl>

    </div>

  </form>

<script type="text/javascript">
$(document).ready(function(){
regionInit('region');

    $('#addr_form').validate({
        rules : {
            true_name : {
                required : true,
                minlength: 2,
                maxlength: 10,
            },
            area : {
                required : true
            },
            address : {
                required : true,
                minlength: 5,
            },
            mob_phone : {
                required : checkPhone,
                minlength : 11,
                maxlength : 11,
                digits : true
            },
            tel_phone : {
                required : checkPhone,
                minlength : 6,
                maxlength : 20
            }
        },
        messages : {
            true_name : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_receiver'];?>'
            },
            area : {
                required : '<i class="icon-exclamation-sign"></i>请继续选择地区'
            },
            address : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_input_address'];?>'
            },
            mob_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
                maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>',
                digits : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_mobile_num_error'];?>'
            },
            tel_phone : {
                required : '<i class="icon-exclamation-sign"></i><?php echo $lang['cart_step1_telphoneormobile'];?>',
                minlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>',
                maxlength: '<i class="icon-exclamation-sign"></i><?php echo $lang['member_address_phone_rule'];?>'
            }
        },
        groups : {
            phone:'mob_phone tel_phone'
        },
        /* 重写错误显示消息方法,以alert方式弹出错误消息 */
        /*showErrors: function(errorMap, errorList) {
            var msg = "";
            $.each( errorList, function(i,v){
              msg += (v.message+"\r\n");
            });
            if(msg!="") alert(msg);
        }*/
    });
    //-------------------

    var time1=setInterval(function(){
        var state=true;
        $.ajax({
                url:'/wap_shop/?act=buy&op=wx_addr_haddle&check_wx_addr=1',
                type:'get',
                async:false,
                cache:false,
                dataType:'JSON',
                success:function(res){
                    //alert(res.state);
                    if(res.state=='true'){
                        state=false;
                        if(res.true_name!=null&&res.true_name!='null'&&res.true_name!='undefined'){
                            $('#region select').eq(0).after('<select></select><select></select>');
                            $('#true_name').val(res.true_name);
                            $('#city_id').val(res.city_id);
                            $('#area_id').val(res.area_id);
                            $('#area_info').val(res.area_info);
                            $('#address').val(res.address);
                            $('#mob_phone').val(res.mob_phone);
                            $("#region select").eq(0).val(res.area_id);
                            $("#region select").eq(1).load('/wap_shop/?act=buy&op=wx_addr_haddle&area_parent_id='+res.area_id+'&area_deep=2');
                            $("#region select").eq(2).load('/wap_shop/?act=buy&op=wx_addr_haddle&area_parent_id='+res.city_id+'&area_deep=3');
                            $("#region select").eq(1).val(res.city_id);
                            $("#region select").eq(2).val(res.area_name_id);
                            $("#area").val('1');
                        }

                    }else if(res.state=='false'){
                        state=false;
                    }
                }

             });
        if(state==false){
            clearInterval(time1);
        }
        },400);

});

function checkPhone(){
   var m_o = $('input[name="mob_phone"]');
   var m_v = m_o.val();
   var t_o = $('input[name="tel_phone"]');
   var t_v = t_o.val();
    if (m_v == '' &&  t_v == ''){
      return true;
   } else {
      if(m_v != ''){
         if(!/^1[34587][0-9]{9}$/.test(m_v)){
            return true;
         }
      } else if(/^(([0\+]\d{2,3}-)?(0\d{2,3})-)?(\d{7,8})(-(\d{3,}))?$/.test(t_v)){
         return true;
      }
   }
   return false;
}

$("#region").on("change","select", function() {
     //do something here
    if($('#region select').eq(2).val()==undefined||$('#region select').eq(2).val()=='-请选择-'){
        $('#region label').text('请继续选择地区');
        $("#area").val('');
        $('#region label').show();
    } else {
        $("#area").val('1');
        $('#region label').hide();
        $('#region label').text('');
    }
});
function submitAddAddr(){
    if ($('#addr_form').valid()){
        $('#buy_city_id').val($('#region').find('select').eq(1).val());
        $('#city_id').val($('#region').find('select').eq(1).val());
        var datas=$('#addr_form').serialize();
        $.post('index.php',datas,function(data){
            if (data.state){
                var true_name = $.trim($("#true_name").val());
                var tel_phone = $.trim($("#tel_phone").val());
                var mob_phone = $.trim($("#mob_phone").val());
                var area_info = $.trim($("#area_info").val());
                var address = $.trim($("#address").val());
                showShippingPrice($('#city_id').val(),$('#area_id').val());
                hideAddrList(data.addr_id,true_name,area_info+'&nbsp;&nbsp;'+address,(mob_phone != '' ? mob_phone : tel_phone));
            }else{
                layer.msg(data.msg);
            }
        },'json');
    }else{
        var msg = '';
        $("label.error").each(function(i, n){
            if($.trim($(n).text()) != '' && $(n).css("display")!='none') {
                msg += '<p>• '+$(n).text()+'</p>';
            }

        });
        layer.msg(msg);
        return false;
    }
}
</script>
<div id="append_parent" style="width: 100%"></div>

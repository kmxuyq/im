<?php defined('InShopNC') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/member.css">
<style type="text/css">
  body{font-size: 12px}
.ncm-order-show {
    width: 100%;
}
.ncm-order-details {
    width: 100%;
}
table {
    border: solid 1px #D8D8D8;
}
table .border-right {
    border-right: 1px solid #E7E7E7;
}
.ncm-order-details .content dl dt {
  width: auto;
}
.ncm-order-details .content dl dd {
  width: auto;
}
.ncm-order-condition {
  width: auto;
}
</style>
<div class="ncm-order-show">
  <div class="ncm-order-info">
    <div class="ncm-order-details">
      <div class="title" style="height: auto;"><?php echo $lang['member_pointorder_info_ordersimple'];?></div>
      <div class="content">
        <dl>
          <dt><?php echo $lang['member_pointorder_info_shipinfo'].$lang['nc_colon'];?></dt>
          <dd>
            <span><?php echo $output['orderaddress_info']['point_truename']; ?></span>
            <span><?php echo $output['orderaddress_info']['point_mobphone']; ?></span>
            <span><?php echo $output['orderaddress_info']['point_telphone']; ?></span>
            <span><?php echo $output['orderaddress_info']['point_areainfo']; ?></span>
            <span><?php echo $output['orderaddress_info']['point_address']; ?></span>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_pointorder_info_ordermessage'].$lang['nc_colon'];?></dt>
          <dd>
            <?php if ($output['order_info']['point_ordermessage']){ ?>
            <?php echo $output['order_info']['point_ordermessage']; ?>
            <?php } ?>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_pointorder_ordersn'].$lang['nc_colon'];?></dt>
          <dd><?php echo $output['order_info']['point_ordersn']; ?><a href="javascript:void(0);" style="padding: 0 14px">更多<i class="icon-angle-down"></i>
            <div class="more"><span class="arrow"></span>
              <ul>
                <li><?php echo $lang['member_pointorder_addtime'].$lang['nc_colon'];?> <span><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></span></li>
                <?php if ($output['order_info']['point_shippingtime'] != ''){?>
                <li><?php echo $lang['member_pointorder_shipping_time'].$lang['nc_colon'];?> <span> <?php echo @date('Y-m-d H:i:s',$output['order_info']['point_shippingtime']);?> </span></li>
                <?php }?>
              </ul>
            </div>
            </a>
          </dd>
        </dl>
      </div>
    </div>
    <div class="ncm-order-details">
    <?php if ($output['order_info']['point_orderstate'] == $output['pointorderstate_arr']['canceled'][0]){ ?>
      <dl>
        <dt><i class="icon-off orange"></i>兑换订单状态：</dt>
        <dd><?php echo $output['pointorderstate_arr']['canceled'][1];?></dd>
      </dl>
      <ul>
        <li>已取消了该兑换订单，<a href="index.php?act=pointprod&op=plist">马上去看看其他兑换礼品</a></li>
      </ul>
    <?php } else { ?>
      <div>
        <div class="content">
          <dl>
            <dt>提交兑换</dt><dd><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_addtime']);?></dd>
          </dl>
          <?php if ($output['order_info']['point_shippingtime'] != ''){?>
          <dl>
            <dt>礼品发货</dt>
            <dd><?php echo @date('Y-m-d H:i:s',$output['order_info']['point_shippingtime']);?></dd>
          </dl>
          <?php } ?>
          <?php if($output['order_info']['point_finnshedtime'] != '') { ?>
          <dl>
            <dt>确认收货</dt>
            <dd><?php echo date("Y-m-d H:i:s",$output['order_info']['point_finnshedtime']); ?></dd>
          </dl>
          <?php } ?>
        </div>
      </div>
    <?php } ?>  
    </div>
  </div>
  <div class="ncm-order-contnet">
    <table class="ncm-default-table order">
      <thead>
        <tr>
          <th></th>
          <th colspan="2"><?php echo $lang['member_pointorder_info_prodinfo'];?></th>
          <th><?php echo $lang['member_pointorder_exchangepoints'];?></th>
          <th><?php echo $lang['member_pointorder_info_prodinfo_exnum'];?></th>
          <th><?php echo $lang['member_pointorder_orderstate'];?></th>
          <th class="border-right">兑换单操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(is_array($output['prod_list']) and !empty($output['prod_list'])) {?>
        <?php if ($output['order_info']['point_shippingtime'] != '') {?>
        <tr>
          <th colspan="20">
          <div class="order-deliver"><span>物流公司： <a target="_blank" href="<?php echo $output['express_info']['e_url'];?>"><?php echo $output['express_info']['e_name'];?></a></span><span> <?php echo $lang['member_pointorder_shipping_code'].$lang['nc_colon'];?> <?php echo $output['order_info']['point_shippingcode'];?> </span><span><a href="javascript:void(0);" id="show_shipping">物流跟踪<i class="icon-angle-down"></i>
              <div class="more"><span class="arrow"></span>
              <ul id="shipping_ul"><li>加载中...</li></ul>
              </div>
              </a></span></div></th>
        </tr>
        <?php }?>
        <?php foreach($output['prod_list'] as $k => $val) { ?>
        <tr>
          <td></td>
          <td><div class="pic-thumb"><a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $val['point_goodsid']));?>" target="_blank"> <img src="<?php echo $val['point_goodsimage_small'];?>" onMouseOver="toolTip('<img src=<?php echo $val['point_goodsimage'];?> />')" onMouseOut="toolTip()" /></a></div></td>
          <td>
            <dl class="goods-name">
              <dt>
                <a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $val['point_goodsid']));?>" target="_blank">
                  <?php echo $val['point_goodsname']; ?>
                </a>
              </dt>
            </dl>
          </td>
          <td><?php echo $val['point_goodspoints']; ?></td>
          <td><?php echo $val['point_goodsnum']; ?></td>
          <?php if ((count($output['prod_list']) > 1 && $k ==0) || (count($output['prod_list']) == 1)){?>
          <td rowspan="<?php echo count($output['prod_list']);?>"><?php echo $output['order_info']['point_orderstatetext']; ?></td>
          <td rowspan="<?php echo count($output['prod_list']);?>" class="border-right">
          <?php if ($output['order_info']['point_orderallowcancel']) { ?>
          <p><a href="javascript:void(0)" class="ncm-btn ncm-btn-orange" style="padding-top: 0" onclick="ajax_confirm('<?php echo $lang['member_pointorder_cancel_confirmtip']; ?>','index.php?act=member_pointorder&op=cancel_order&order_id=<?php echo $output['order_info']['point_orderid']; ?>');"><?php echo $lang['member_pointorder_cancel_title']; ?></a></p>
          <?php } ?>
            <?php if ($output['order_info']['point_orderallowreceiving']) { ?>
          <p><a href="javascript:void(0)" class="ncm-btn" onclick="ajax_confirm('<?php echo $lang['member_pointorder_confirmreceivingtip']; ?>','index.php?act=member_pointorder&op=receiving_order&order_id=<?php echo $output['order_info']['point_orderid']; ?>');" class="" ><?php echo $lang['member_pointorder_confirmreceiving']; ?></a></p>
          <?php } ?>
          </td>
          <?php } ?>
        </tr>
        <?php } }?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">
            <dl class="sum">
              <dt>兑换单所需：</dt>
              <dd><em><?php echo $output['order_info']['point_allpoint'];?></em>分</dd>
            </dl></td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
<script type="text/javascript">
$(function(){
    $('#show_shipping').on('hover',function(){
        var_send = '<?php echo date("Y-m-d H:i:s",$output['order_info']['point_shippingtime']); ?>&nbsp;&nbsp;平台已发货<br/>';
    	$.getJSON('index.php?act=member_order&op=get_express&e_code=<?php echo $output['express_info']['e_code']?>&shipping_code=<?php echo $output['order_info']['point_shippingcode']?>&t=<?php echo random(7);?>',function(data){
    		if(data){
    			data = var_send+data.join('<br/>');
    			$('#shipping_ul').html('<li>'+data+'</li>');
    			$('#show_shipping').unbind('hover');
    		}else{
    			$('#shipping_ul').html(var_send);
    			$('#show_shipping').unbind('hover');
    		}
    	});
    });
});
    function ajax_confirm(msg, url) {
        var r = confirm(msg);
        if (r==true)
        {
            window.location.href=url;
        }
    }
</script>

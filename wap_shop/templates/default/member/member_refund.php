<?php defined('InShopNC') or exit('Access Invalid!');?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>退款及退货</title>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/member_style.css" />
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js" ></script>
</head>
<style>
.tab_text1 li{
					float:left;
					margin:10px;
				}
</style>
<body>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb noProsition">
    <i class="ui-icon-return" onclick="history.back()"></i>
    <h1 class="qz-color">退款及退货</h1>
    <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<div id="leftTabBox" class="tabBox-main">
    <div class="hd tabBox-tt">
        <ul id="top-nav">
            <li class="<?php if($_GET['act'] == 'member_refund'){ echo 'on' ;}?>" >
			退款申请
			<form action="index.php" method="get">
			<input type="hidden" value="member_refund" name="act"/>
			<input type="hidden" value="index" name="op"/>
			</form>
			</li>
            <li class="<?php if($_GET['act'] == 'member_return'){ echo 'on' ;}?>">
			退货申请
			<form action="index.php" method="get">
			<input type="hidden" value="member_return" name="act"/>
			<input type="hidden" value="index" name="op"/>
			</form>
			</li>
			<!-- delete by tanzn start
            <li class="<?php if($_GET['act'] == 'member_vr_refund'){ echo 'on' ;}?>">虚拟兑换退款
			<form>
			<input type="hidden" value="member_vr_refund" name="act"/>
			<input type="hidden" value="index" name="op"/>
			</form>
			</li>
			delete by tanzn stop -->
        </ul>
    </div>
    <div class="tempWrap" style="clear: both">
        <div class="bd">
            <div class="tab_text1">
            <?php if (is_array($output['refund_list']) && !empty($output['refund_list'])) { ?>
            <?php foreach ($output['refund_list'] as $key => $val) { ?>
                <div class="refund">

                    <div class="refund_tp">
					<?php echo $lang['refund_buyer_add_time'].$lang['nc_colon'];?>
					<?php echo date("Y-m-d H:i:s",$val['add_time']);?>
					</div>

                    <div class="refund_main">
                        <p class="tip_tt">
						  <a href="index.php?act=show_store&op=index&store_id<?php echo $val['store_id'];?>" target="_blank" title="<?php echo $order_info['store_name'];?>">
						  <?php echo $val['store_name']; ?>
						  </a>
						</p>

                        <div class="refund_numb">
                            <span>
							<?php echo $lang['refund_order_refundsn'].$lang['nc_colon'];?>
							<?php echo $val['refund_sn']; ?>
							</span>
							<a href="index.php?act=member_refund&op=view&refund_id=<?php echo $val['refund_id']; ?>" >
						     <input value="<?php echo $lang['nc_view'];?> " type="button"/>
						    </a>
                        </div>

                        <div class="refund_text">
                            <p class="imgBox">
							<a href="index.php?act=goods&op=index&goods_id=<?php echo $val['goods_id'];?>" target="_blank">
							<img src="<?php echo cthumb($val['goods_image'], 60, $val['store_id']);?>" />
							</a>
							</p>
                            <div class="text">
                                <h3>
								<a href="index.php?act=goods&op=index&goods_id=<?php echo $val['goods_id'];?>" target="_blank">
								<?php echo $val['goods_name']; ?>
		                    	</a>
								</h3>
                                <p>退款:￥<?php echo $val['refund_amount'];?></p>
                                <p>
								<?php echo $lang['refund_order_ordersn'].$lang['nc_colon'];?>
								<a href="index.php?act=member_order&op=show_order&order_id=<?php echo $val['order_id']; ?>" target="_blank">
		                        <?php echo $val['order_sn'];?>
	                           	</a>
								</p>
                            </div>
                        </div>

                        <ul class="refund_state">
                            <li style="float:left;width:48%">审核状态:<?php echo $output['state_array'][$val['seller_state']]; ?></li>
                            <li style="float:left;width:48%">平台确认:<?php echo $val['seller_state']==2 ? $output['admin_array'][$val['refund_state']]:'无'; ?></li>
                        </ul>
                    </div>

                </div>
            <?php } ?>
            <?php } ?>
            </div>
			<div class="tab_text1">
			<?php echo $output['show_page']; ?>
			</div>

        </div>

    </div>

</div>
<script type="text/javascript">
$(function(){
	var obj = $('#top-nav').find('li');
	obj.each(function(index){

		 $(this).on('click',function(){
			 if(!$(this).hasClass('on')){
				 $(this).addClass('on');
			 }
			 obj.each(function(indexs){
				 if(index !== indexs){
					 $(this).removeClass('on')
				 }
			 })
             $(this).find('form').submit();
		 })
	})
})
</script>
<style>
body{ background:#f5f5f5;}
</style>
</body>
</html>

<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if ($output['result'] === true){?>
<form method="post" action="index.php?act=pointvoucher&op=voucherexchange_save">
<input type="hidden" name="form_submit" value="ok"/>
<input type="hidden" name="vid" value="<?php echo $output['template_info']['voucher_t_id']; ?>"/>
<div class="az_20">
	<img class="az_img" src="<?php echo $output['template_info']['voucher_t_customimg'];?>" onerror="this.src=<?php echo UPLOAD_SITE_URL.DS.defaultGoodsImage(240);?>'"/>
</div>
<div class="az_20">您正在使用<em>&nbsp;<?php echo $output['template_info']['voucher_t_points'];?>&nbsp;</em><?php echo$lang['points_unit'];?>&nbsp;兑换&nbsp;<em>1</em>&nbsp;张</div>
<div class="az_6"><?php echo $output['template_info']['store_name'];?><?php echo '<em>'.$output['template_info']['voucher_t_price'].'</em>'.$lang['currency_zh'];?>店铺代金券（<em>满<?php echo $output['template_info']['voucher_t_limit'];?>减<?php echo $output['template_info']['voucher_t_price'];?></em>）</div>
<div class="az_20">
    <span>店铺代金券有效期至:&nbsp;<em><?php echo @date('Y-m-d',$output['template_info']['voucher_t_end_date']);?></em></span><p>
    <span>
    <?php if ($output['template_info']['voucher_t_eachlimit'] > 0){?>
    每个ID领<?php echo $output['template_info']['voucher_t_eachlimit'];?>张
    <?php } else {?>
    每个ID领取不限量
    <?php }?>
    </span>
</div>
<div class="layui-layer-btn">
<input class="az_point" type="submit" class="submit" value="兑换"/> 

<a class="layui-layer-btn1">取消</a>
</div>
</form>
<?php }else {?>
<div class="az_20"><?php echo $output['message'];?></div>
<div class="layui-layer-btn">
<a class="layui-layer-btn1" >取消</a>
</div>
<?php }?>

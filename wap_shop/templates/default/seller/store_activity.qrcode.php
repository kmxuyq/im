<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <div class="text-intro"> 
  <?php echo $lang['store_activity_theme'];?> 
  <?php echo $output['activity_info']['activity_title'];?></div>
</div>
<div class="ncsc-form-default">
    <dl>
      <dt><i class="required">*</i><?php echo $lang['store_partner_href'];?></dt>
      <dd><?php echo $output['codeurl'] ?></dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['store_activity_codeimg'];?></dt>
      <dd><img src="<?php echo $output['codeimg']?>" /></dd>
    </dl>
</div>
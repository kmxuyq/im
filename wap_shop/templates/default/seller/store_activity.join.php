<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="ncsc-default-table">
  <h3>参与人员名单</h3>
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <thead>
    <tr>
      <th class="w20">&nbsp;</th>
      <th class="tl w200"><?php echo $lang['store_activity_theme'];?></th>
      <th class="tl"><?php echo $lang['store_activity_intro'];?></th>
      <th class="w150"><?php echo $lang['store_order_receiver'];?></th>
      <th class="w150"><?php echo $lang['store_activity_person_img'];?></th>
      <th class="w150"><?php echo $lang['store_activity_person_pasttime'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($output['list'] as $k => $v){?>
    <tr>
      <td></td>      
      <td class="tl"><a target="_blank" href="index.php?act=activity&activity_id=<?php echo $v['activity_id'];?>"><?php echo $v['activity_title']; ?></a></td>
      <td class="tl"><?php echo $v['activity_desc'];?></td>
      <td class="tl"><?php echo $v['member_name'];?></td>
      <td class="tl">
         <img style="width:60px;height:60px" src="<?php echo getMemberAvatar($v['member_avatar']);?>" id="view_img">
      </td>
      <td class="goods-time"><?php echo @date('Y-m-d',$v['addtime']);?></td>
    </tr>
    <?php } } else { ?>
    <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i>
          <span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['list']) && is_array($output['list'])){?>
    <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page'];?></div></td>
    </tr>
    <?php }?>
  </tfoot>
</table>

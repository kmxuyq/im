<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="ncsc-default-table">
  <h3>中奖人员信息</h3>
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <thead>
    <tr>
      <th>&nbsp;</th>
      <th ><?php echo $lang['store_activity_theme'];?></th>
      <th><?php echo $lang['store_activity_intro'];?></th>
      <th><?php echo $lang['store_order_receiver'];?></th>
      <th><?php echo $lang['store_activity_person_img'];?></th>
      <th><?php echo $lang['store_activity_person_pasttime'];?></th>
      <th><?php echo $lang['store_activity_person_az_code'];?></th>
      <th><?php echo $lang['store_activity_person_gifttime'];?></th>
      <th><?php echo $lang['store_order_goods_detail'];?></th> 
    </tr>
  </thead>
  <tbody>
    <?php foreach($output['list'] as $k => $v){?>
    <tr>
      <td></td>
      <td><a target="_blank" href="index.php?act=activity&activity_id=<?php echo $v['activity_id'];?>"><?php echo $v['activity_title']; ?></a></td>
      <td ><?php echo $v['activity_desc'];?></td>
      <td ><?php echo $v['member_name'];?></td>
      <td>
         <img style="width:60px;height:60px" src="<?php echo getMemberAvatar($v['member_avatar']);?>" id="view_img">
      </td>
      <td class="goods-time"><?php echo @date('Y-m-d',$v['addtime']);?></td>
      <td ><?php echo $v['az_code'];?></td>
      <td class="goods-time"><?php echo @date('Y-m-d',$v['active_time']);?></td>
      <td>
         <a href="<?php echo urlShop('goods','index',array('goods_id' => $v['goods_id']));?>" target="_blank">
           <img src="<?php echo cthumb($v['goods_image'], 60,$v['store_id']);?>"/>
         <br/>
         <?php  echo $v['goods_name']; ?>
         </a>  
      </td>
    </tr>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
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

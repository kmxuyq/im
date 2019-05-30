<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="ncsc-default-table order">
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <thead>
        <th><?php echo $lang['store_activity_goods_name'];?></th>
        <th>售价</th>
        <th>抽奖活动中用户奖品</th>
        <th>该奖品是否被人获得</th>
        <th><?php echo $lang['store_order_handle_desc']; ?></th>
  </thead>
  <tbody>
    <?php foreach($output['list'] as $k => $v){?>
    <tr>
      <td colspan="5" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="5">
      		<span><?php echo $lang['store_activity_theme'].$lang['nc_colon'];?><em><?php echo $v['activity_title']?></em></span>
      		<span><?php echo $lang['store_activity_type'].$lang['nc_colon'];?><em>
      		       <?php if($v['activity_type'] == '1'){
		        	echo   $lang['store_activity_goods'];
		        	}elseif($v['activity_type'] == '2'){
		        		echo   $lang['store_activity_group'];
		        	}else{
		        		echo   $lang['store_activity_gift'];
		           }?>
      		  </em></span>
      		<span><?php echo $lang['store_activity_start_time'].$lang['nc_colon'];?><em class="goods-time">
      		   <?php echo @date('Y-m-d',$v['activity_start_date']);?>
      		</em></span>
      		<span><?php echo $lang['store_activity_end_time'].$lang['nc_colon'];?><em class="goods-time">
      		   <?php echo @date('Y-m-d',$v['activity_end_date']);?>
      		</em></span> 
      		<span>
	      		<a class="ncsc-btn"  
	      		   href="index.php?act=store_activity&op=activityUrl&activity_id=<?php echo $v['activity_id'];?>"/>
			      <i class="icon-truck"></i><?php echo $lang['store_activity_join_url'];?>
			    </a>
      		</span>
      		
      		<span>
	      		<a class="ncsc-btn"  
	      		   href="index.php?act=store_activity&op=joinPerson&giftFlag=0&activity_id=<?php echo $v['activity_id'];?>"/>
                  <i class="icon-truck"></i><?php echo $lang['store_activity_join_person'];?>
			    </a>
      		</span>
 	 </th>
    </tr>
      <!-- detail循环 -->
     <?php if(!empty($v['detailItem'])){ ?> 
	     <?php foreach($v['detailItem'] as $value){?> 
	          <tr>
	              <td class="w70">
	                   <div class="ncsc-goods-thumb">
	                        <a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank">
			                  <img src="<?php echo cthumb($value['goods_image'], 60,$_SESSION['store_id']);?>" />
			                </a>
			           </div>
                  </td>
                  <td class="tl">
                      <dl class="goods-name">
					     <dt>
					        <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>">
					               <?php echo $value['goods_name']; ?></a>
					     </dt>
                	 </dl>
                 </td>
                
                 <td><?php echo $value['goods_price']; ?></td>
                 
                 <td><?php echo empty($value['member_id'])?'无':'有:中奖人-'.$value['member_id']; ?></td>
                  
                 <td>
                    <?php if(!empty($value['member_id'])){ ?>
                         <a class="ncsc-btn" 
                           href="index.php?act=store_activity&op=joinPerson&giftFlag=1&detail_id=<?php echo $value['activity_detail_id'];?>&activity_id=<?php echo $v['activity_id'];?>"/>
		                   <i class="icon-truck"></i><?php echo $lang['store_activity_zhong_person'];?>
                         </a>
                    <?php }else{?>
                         <a class="ncsc-btn" 
                         href="index.php?act=store_activity&op=drawprize&detail_id=<?php echo $value['activity_detail_id'];?>&activity_id=<?php echo $v['activity_id'];?>"/>
                 	       <i class="icon-truck"></i><?php echo $lang['store_activity_gift'];?>
                        </a>
                    <?php }?> 
                 </td>
	          </tr>
	     <?php } ?>
     <?php }else{?>
          <tr>
      		<td colspan="5" class="norecord">
      		     <div class="warning-option">
      		        <i class="icon-warning-sign"></i>
      		        <span><?php echo $lang['no_record'];?></span>
      		     </div>
      		</td>
    	 </tr>
     <?php }?>
    <?php } ?>
    <?php }else{ ?>
	    <tr>
	      <td colspan="5" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
	    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['list']) && is_array($output['list'])){?>
    <tr>
      <td colspan="5"><div class="pagination"><?php echo $output['show_page'];?></div></td>
    </tr>
    <?php }?>
  </tfoot>
</table>

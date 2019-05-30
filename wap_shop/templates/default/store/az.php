<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="nc-goods-sort">
            <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
            <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
            <?php }?>
          </ul>
          <?php }?>
          <div class="ncs-goods-info-content">
            <?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
          </div>
        </div>

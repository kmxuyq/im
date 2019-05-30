<section class="main_body">
    <?php if(!empty($output['goods'])){ ?>
        <?php foreach($output['goods'] as $k=>$v){ ?>
            <div class="index_theme_title" onclick="location.href = 'index.php?act=search&op=<?php if($_GET['op'] == 'share'){ echo 'share_';}?>product_list&cate_id=<?php echo $v['gc_id'];?>&store_id=<?php echo $output['store']['store_id'];?>'">
                <span class="car"><?php echo $v['gc_name'];?></span>
            </div>
            <?php  $cate_url_img = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$v['gc_id'].'.jpg';
            $cate_img = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$v['gc_id'].'.jpg';
            if(is_file($cate_img) or @getimagesize($cate_url_img)){ ?>
                <div class="gt_ceil_img" onclick="location.href = 'index.php?act=search&op=product_list&cate_id=<?php echo $v['gc_id'];?>&store_id=<?php echo $output['store']['store_id'];?>'">
                    <img src="<?php echo $cate_url_img ;?>">
                </div>
            <?php	} ?>

            <div class="gt_project_list">
                <?php foreach($v['goods_list'] as $kk=>$vv){?>
                    <div class="item" onclick="location.href = 'index.php?act=goods&op=index&goods_id=<?php echo $vv['goods_id'];?>'">
                        <img src="<?php echo $vv['goods_image'];?>" alt="<?php echo $vv['goods_name'];?>">
                        <p><?php echo $vv['goods_name'];?></p>
                        <div class="price">
                            <span>￥<?php echo $vv['goods_price'];?></span>
                            <span class="icon">起</span>
                        </div>
                    </div>
                <?php  } ?>
            </div>
        <?php } ?>
    <?php }?>
</section>
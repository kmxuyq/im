<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="adv_list swiper-container">
    <div class="swipe-wrap swiper-wrapper">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div class="item swiper-slide" nctype="item_image">
            <a nctype="btn_item" href="javascript:;" data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>">
                <img class="list_img" nctype="image" src="<?php echo $item['image']; ?>" alt="">
            </a>
        </div>
    <?php } ?>
    </div>
</div>

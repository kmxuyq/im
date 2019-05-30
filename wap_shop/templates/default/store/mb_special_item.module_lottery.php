<?php defined('InShopNC') or exit('Access Invalid!'); ?>
<div class="index_block home2" style="overflow: hidden; height: 3rem; position: relative;">
    <?php if(!empty($vv['title'])) {?>
        <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div class="lottery_list">
        <div class="lottery_list2" >
            <div class="swiper-wrapper">
                 <?php if(is_array($vv['item'])): ?>
                    <?php foreach($vv['item'] as $item): ?>
                <div class="swiper-slide" style="">
                    <img src="<?php echo $item['image']; ?>" alt="" nctype="btn_item"  data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>">
                </div>
             <?php endforeach ;endif;?>
            </div>
            <!-- Add Pagination -->
        </div>
    </div>

</div>
<style>
    .lottery_list2 .swiper-slide{width:3.9rem;height: 2rem; background: #fff; }
    .lottery_list2 img{width: 100%; height: 100%}
    .lottery_list2 .swiper-slide.move{transition: 1.3s all; transform: translate(375px,360px) rotateZ(720deg); opacity: 0; z-index: 999}
</style>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/swiper.min.js"></script>
<script>
    $(function(){
        var lottery_list = new Swiper('.lottery_list2', {
            slidesPerView: 3,
        });
    })
</script>

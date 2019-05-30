
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color">评价</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>

<section class="ui-container">
    <ul class="ui-list qz-background-none">
        <li class="ui-border-t">
            <div class="ui-list-thumb qz-list-thumb">
                <img src="<?php echo cthumb($output["goods_info"]["goods_image"], 60);?>" class="qz-img-block">
            </div>
            <div class="ui-list-info qz-light">
                <h4 class="ui-nowrap"><?php echo $output["goods_info"]["goods_name"];?> </h4>
                <p class="ui-nowrap">￥<font class="qz-color2"><?php echo $output["goods_info"]["goods_price"];?></font></p>
            </div>
        </li>
    </ul>
    
    <div class="qz-padding">
        <ul class="qz-star-list">
            <li class="clearfix">
                <span>商品评分</span>
				<?php 
					for($i=1;$i<=5;$i++)
					{
						if($i<=$output['goods_evaluate_info']['star_average'])
						{
							echo '<span class="ico ico-hov"></span>';
						}
						else
						{
							echo '<span class="ico"></span>';
						}
					}
				?>
                
               
            </li>
            <input type="hidden" class="star-input" value="4">
        </ul>
        
        <div class="qz-bk15"></div>
        <textarea class="qz-textarea"></textarea>
        
        <div class="qz-bk10"></div>
        <ul class="qz-star-list">
            <li class="clearfix">
                <span>描述相符</span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
            </li>
            <input type="hidden" class="star-input" value="0">
        </ul>
        
        <div class="qz-bk10"></div>
        <ul class="qz-star-list">
            <li class="clearfix">
                <span>服务态度</span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
            </li>
            <input type="hidden" class="star-input" value="0">
        </ul>
        
        <div class="qz-bk10"></div>
        <ul class="qz-star-list">
            <li class="clearfix">
                <span>发货速度</span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
                <span class="ico"></span>
            </li>
            <input type="hidden" class="star-input" value="0">
        </ul>
    </div>
    
    <div class="ui-btn-wrap">
        <input type="submit" value="提 交" class="ui-btn-lg ui-btn-primary qz-btn-lg" />
    </div>
</section>


<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript">
$(".qz-star-list .ico").click(function(){
    $(this).parent().children(".ico").removeClass("ico-hov");
    loc_num = $(this).index();
    for (var i=0;i<loc_num;i++) {
        $(this).parent().children(".ico").eq(i).addClass("ico-hov");
    }
    $(this).parent().parent().children(".star-input").val(loc_num);
});
</script>
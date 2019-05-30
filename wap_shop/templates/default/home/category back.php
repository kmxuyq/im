<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title><?php echo $lang['category_index_goods_class'];?></title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />

<link rel="stylesheet" type="text/css" href="/data/resource/icon/css/font-awesome.min.css" />
</head>

<body ontouchstart>

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color">商品分类</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<section class="ui-container">
    <ul class="ui-list ui-list-pure ui-list-text ui-list-link ui-border-b">
	<?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
          <?php foreach ($output['show_goods_class'] as $key => $val) { $i++; ?>
        <li cat_id="<?php echo $val['gc_id'];?>">
		<a href="<?php echo WAP_SHOP_SITE_URL;?>/index.php?act=search&op=index&cate_id=<?php echo $val['gc_id'];?>" style="color:#4F5F6F;">
           <!--  <h1><?php echo $val['gc_name'];?></h1>
            <div class="qz-bk5"></div> -->
            <div class="qz-link clearfix">
			<?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
              <?php foreach ($val['class2'] as $k => $v) { ?>
              <!--<a href="<?php echo urlShopWAP('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name']; ?>"><?php echo $v['gc_name'];?></a>-->
			  <div class="menu_border"><?php echo $v['gc_name'];?><span class="ico"><i class="fa fa-angle-right"></i></span></div>
              <?php } ?>
              <?php } ?>
               <!--<div class="sub-class" cat_menu_id="<?php echo $val['gc_id'];?>">
              <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
              <?php foreach ($val['class2'] as $k => $v) { ?>
              <dl>
                <dt>
                  <h3><a href="<?php echo urlShopWAP('search','index',array('cate_id'=> $v['gc_id']));?>"><?php echo $v['gc_name'];?></a></h3>
                </dt>
                <dd class="goods-class">
                  <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
                  <?php foreach ($v['class3'] as $k3 => $v3) { ?>
                  <a href="<?php echo urlShopWAP('search','index',array('cate_id'=> $v3['gc_id']));?>"><?php echo $v3['gc_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>
                <?php if (!empty($v['brands']) && is_array($v['brands'])) { $n = 0; ?>
                <dd class="brands-class">
                  <h5><?php echo $lang['nc_brand'].$lang['nc_colon'];?></h5>
                  <?php foreach ($v['brands'] as $k3 => $v3) {
                    if ($n++ < 10) {
                    ?>
                    <a href="<?php echo urlShopWAP('brand','list',array('brand'=> $v3['brand_id'])); ?>"><?php echo $v3['brand_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>
                <?php } ?>
              </dl>
              <?php } ?>
              <?php } ?>
            </div>-->
			
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
    
    <div id="menu"></div>
</section>


<script type="text/javascript" src="js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
    $(".qz-number .qz-ico-plus").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        num_total = parseInt($(this).parent().children(".num").attr("total"));
        if (num_val < num_total) {
            num_val = num_val + 1;
        } else {
            num_val = num_total;
            alert("已超过最大库存！");
        }
	   $(this).parent().children(".num").val(num_val);
    });

    $(".qz-number .qz-ico-reduction").click(function(){
        num_val = parseInt($(this).parent().children(".num").val());
        if (num_val<=0) {
            num_val = 0;
        } else {
            num_val = num_val - 1;
        }
        $(this).parent().children(".num").val(num_val);
    });
    
    $(".qz-tcxz span").click(function(){
        $(this).parent().find("span").removeClass("ui-btn-primary");
        $(this).addClass("ui-btn-primary");
        loc_num = $(this).attr("value");
        $(this).parent().children(".type").val(loc_num);
    });

$(function(){
	$("#categoryList").imagesLoaded( function(){
		$("#categoryList").masonry({
			itemSelector : '.classes'
		});
	});
});
</script> 

<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<title>商品列表</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<body ontouchstart>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onClick="history.back()" ></i>
    <h1 class="qz-color">商品列表</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<section class="ui-container">

    <div class="ui-tab">
        <ul class="ui-tab-nav ui-border-b">
            <li <?php if(!$_GET['key']){?>class="qz-background-blue" <?php }?>><font class="qz-color7">默认</font></li>
		    <!--<li class="qz-background-blue">-->
			<li>销量&nbsp;<span class="qz-sort"></span></li>
            <li>人气</li>
            <li>价格</li>
        </ul>
    </div>

    <div class="qz-padding qz-padding-t">
        <ul class="ui-list" style="border-bottom:none;">
            <li class="ui-border-b">
                <div class="ui-list-thumb qz-list-thumb" style="width:90px;">
                    <img src="images/pj_pic.png" class="qz-img-block">
                    <i class="qz-label2">抢</i>
                </div>
                <div class="ui-list-info qz-light3">
                    <h4 class="ui-nowrap">石林九乡一日游石林九乡一日游石
林九乡一日游石林九乡一日游</h4>
                    <div class="qz-bk5"></div>
                    <ul class="qz-star-list2">
                        <li class="clearfix">
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico"></span>
                        </li>
                    </ul>

                    <div class="qz-bk20"></div>
                    <div class="clearfix">
                        <span class="qz-fl qz-color2"><font class="qz-f22">￥ 99</font>.00</span>
                        <span class="qz-fr qz-ico qz-ico-shopping"></span>
                    </div>
                </div>
            </li>

            <li class="ui-border-b">
                <div class="ui-list-thumb qz-list-thumb qz-relative" style="width:90px;">
                    <img src="images/pj_pic.png" class="qz-img-block">
                    <i class="qz-label3">折</i>
                </div>
                <div class="ui-list-info qz-light3">
                    <h4 class="ui-nowrap">石林九乡一日游石林九乡一日游石
林九乡一日游石林九乡一日游</h4>
                    <div class="qz-bk5"></div>
                    <ul class="qz-star-list2">
                        <li class="clearfix">
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico"></span>
                        </li>
                    </ul>

                    <div class="qz-bk20"></div>
                    <div class="clearfix">
                        <span class="qz-fl qz-color2"><font class="qz-f22">￥ 99</font>.00</span>
                        <span class="qz-fr qz-ico qz-ico-shopping"></span>
                    </div>
                </div>
            </li>

            <li class="ui-border-b">
                <div class="ui-list-thumb qz-list-thumb" style="width:90px;">
                    <img src="images/pj_pic.png" class="qz-img-block">
                </div>
                <div class="ui-list-info qz-light3">
                    <h4 class="ui-nowrap">石林九乡一日游石林九乡一日游石
林九乡一日游石林九乡一日游</h4>
                    <div class="qz-bk5"></div>
                    <ul class="qz-star-list2">
                        <li class="clearfix">
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico"></span>
                        </li>
                    </ul>

                    <div class="qz-bk20"></div>
                    <div class="clearfix">
                        <span class="qz-fl qz-color2"><font class="qz-f22">￥ 99</font>.00</span>
                        <span class="qz-fr qz-ico qz-ico-shopping"></span>
                    </div>
                </div>
            </li>

            <li class="ui-border-b">
                <div class="ui-list-thumb qz-list-thumb" style="width:90px;">
                    <img src="images/pj_pic.png" class="qz-img-block">
                </div>
                <div class="ui-list-info qz-light3">
                    <h4 class="ui-nowrap">石林九乡一日游石林九乡一日游石
林九乡一日游石林九乡一日游</h4>
                    <div class="qz-bk5"></div>
                    <ul class="qz-star-list2">
                        <li class="clearfix">
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico ico-hov"></span>
                            <span class="ico"></span>
                        </li>
                    </ul>

                    <div class="qz-bk20"></div>
                    <div class="clearfix">
                        <span class="qz-fl qz-color2"><font class="qz-f22">￥ 99</font>.00</span>
                        <span class="qz-fr qz-ico qz-ico-shopping"></span>
                    </div>
                </div>
            </li>

        </ul>
    </div>
</section>

<div class="shop_con_list" id="main-nav-holder">
      <nav class="sort-bar" id="main-nav">

        <div class="nch-sortbar-array"> 排序方式：
          <ul>
            <li <?php if(!$_GET['key']){?>class="selected"<?php }?>><a href="<?php echo dropParam(array('order', 'key'));?>"  title="<?php echo $lang['goods_class_index_default_sort'];?>"><?php echo $lang['goods_class_index_default'];?></a></li>
            <li <?php if($_GET['key'] == '1'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1') ? replaceParam(array('key' => '1', 'order' => '1')):replaceParam(array('key' => '1', 'order' => '2')); ?>" <?php if($_GET['key'] == '1'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')?$lang['goods_class_index_sold_asc']:$lang['goods_class_index_sold_desc']; ?>"><?php echo $lang['goods_class_index_sold'];?><i></i></a></li>
            <li <?php if($_GET['key'] == '2'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '2') ? replaceParam(array('key' => '2', 'order' => '1')):replaceParam(array('key' => '2', 'order' => '2')); ?>" <?php if($_GET['key'] == '2'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php  echo ($_GET['order'] == '2' && $_GET['key'] == '2')?$lang['goods_class_index_click_asc']:$lang['goods_class_index_click_desc']; ?>"><?php echo $lang['goods_class_index_click']?><i></i></a></li>
            <li <?php if($_GET['key'] == '3'){?>class="selected"<?php }?>><a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3') ? replaceParam(array('key' => '3', 'order' => '1')):replaceParam(array('key' => '3', 'order' => '2')); ?>" <?php if($_GET['key'] == '3'){?>class="<?php echo $_GET['order'] == 1 ? 'asc' : 'desc';?>"<?php }?> title="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '3')?$lang['goods_class_index_price_asc']:$lang['goods_class_index_price_desc']; ?>"><?php echo $lang['goods_class_index_price'];?><i></i></a></li>
          </ul>
        </div>
      </nav>
      <!-- 商品列表循环  -->

      <div>
        <?php require_once (BASE_TPL_PATH.'/home/goods.squares.php');?>
      </div>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/zepto.min.js"></script>
<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/ui.js"></script>
<script type="text/javascript">
$(".qz-zyxzt-list li").click(function(){
    $(this).children(".lev-sub").show();
    $(this).siblings().children(".lev-sub").hide();
    $(this).eq(0).addClass("li-hov");
    $(this).siblings().removeClass("li-hov");
});

 /**
 *
 */
(function (){
    var tab = new fz.Scroll('.ui-tab', {
        role: 'tab',
        autoplay: false,
        interval: 3000
    });
		    /* 滑动开始前 */
    tab.on('beforeScrollStart', function(fromIndex, toIndex) {
        console.log(fromIndex,toIndex);// from 为当前页，to 为下一页
    })
})();
</script>

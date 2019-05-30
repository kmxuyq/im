<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<style>
body{font-family: "Microsoft YaHei",Arial,Helvetica,sans-serif; background: #f5f5f5; color: #4f5f6f;}
</style>

<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color"><?php echo $value['gc_name']?></h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<?php $gc_id = $_GET['cate_id'];
foreach ($output['goods_class_array'] as $value) {?>
<?php if($value['gc_id'] == $gc_id){?>
<section class="ui-container">
    <div class="qz-relative qz-zyxzt-list az-light">
        <ul>
		<?php if (!empty($value['class2'])) {?>
		<?php foreach ($value['class2'] as $val) {?>
            <li <?php if ($v['gc_id'] == $_GET['cate_id']) {?>class="li-hov"<?php }?>>
			<a href="<?php echo urlShopWAP('search', 'product_list', array('cate_id' => $val['gc_id'], 'keyword' => $_GET['keyword']));?>" <?php if ($v['gc_id'] == $_GET['cate_id']) {?>class="selected"<?php }?>>
			<?php echo $val['gc_name']?></a>
                <div class="lev-sub qz-background-white" style="display:block;">
				<?php if (!empty($val['class3'])) {?>
                    <ul>
					<?php foreach ($val['class3'] as $v) {?>
					<a href="<?php echo urlShopWAP('search', 'product_list', array('cate_id' => $v['gc_id'], 'keyword' => $_GET['keyword']));?>" <?php if ($v['gc_id'] == $_GET['cate_id']) {?>class="selected"<?php }?> style="color: rgba(79,95,111,.7);">
					<li>
					<?php echo $v['gc_name']?>
					</li></a>
					<?php }?>
                    </ul>
					<?php }?>
                </div>
				<?php }?>
            </li>
            <?php }?>
        </ul>
		<?php }?>
    </div>
	<?php }?>
	<div id="menu"></div>
</section>



<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
$(".qz-zyxzt-list li").click(function(){

    $(this).children(".lev-sub").show();
    $(this).siblings().children(".lev-sub").hide();
    $(this).eq(0).addClass("li-hov");
    $(this).siblings().removeClass("li-hov");
});
</script>

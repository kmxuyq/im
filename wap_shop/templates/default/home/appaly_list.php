<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="keywords" content="your keywords">
<meta name="description" content="your description">
<title><?php echo $lang['az_appaly']?></title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/base.css">
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/apply.css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
</head>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<script type="text/javascript" src="/wap/js/search.js"></script>


<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">申领试用列表</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<div class="potions_list">
	<!--搜索框
    <form>
        <div class="search_wrap">
            <div class="search">
                <input type="search" id="keyword" placeholder="请输入商品名或商品ID" class="az"/>
                <a class="empty"></a>
                 <a class="search_btn"></a>
            </div>
        </div>
    </form></div> -->
<div class="ym-product-box">
<?php if(is_array($output['goods_list']) && count($output['goods_list'])>0){?>
<?php foreach($output['goods_list'] as $value){?>
<dl class="ym-product-list clearfix">
<dt><img src="<?php echo thumb($value)?>" width="100%"/></dt>
<dd>
<h2><?php echo $value['goods_name']?></h2>
<p class="desc"><?php echo $value['goods_jingle']?></p>
<p class="price">
价值：<em class="ym-col-bba">￥</em><span class="ym-col-bba"><?php echo $value['goods_price']?></span>
</p>

<?php if(!empty($output['active_list'][$value['goods_id']])) { ?>
<?php $va = $output['active_list'][$value['goods_id']]; ?>
<!-- 申领判断 -->
<?php if($va['az_active_state']=='0'){?>
<a href="<?php echo urlShopWAP('goods','index',array('type'=>'appaly_goods','wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<img src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-get.png"/>
<?php }elseif($va['az_active_state']=='1'){?>
<a href="<?php echo urlShopWAP('goods','index',array('type'=>'appaly_goods','wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<img src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-success.png"/>
<?php }elseif ($va['az_active_state']=='2') {?>
<a href="<?php echo urlShopWAP('goods','index',array('type'=>'appaly_goods','wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<img src = "<?php echo SHOP_TEMPLATES_URL;?>/img/apply-fail.png"/>
<?php }?>
<?php }else {?>
<!-- <az>
<?php if($va['member_id']==$_SESSION['member_id']){?>
<a  class="btn_n"><?php echo $lang['az_appaly_check']?></a><az></az>
<?php }else {?>
<a href="<?php echo urlShopWAP('goods','index',array('type'=>"appaly_goods",'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<?php }?>
</az> -->
<a href="<?php echo urlShopWAP('goods','index',array('type'=>'appaly_goods','wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<?php }?>
</dd>
</dl>
<?php }?>

<?php }else{?>

<!-- <az>
<?php if ($_SESSION['is_login']) {?>
<a href="<?php echo urlShopWAP('goods','index',array('type'=>"appaly_goods",'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<?php }else {?>
  <a href="<?php echo urlShopWap('login', 'index');?>" class="btn"><?php echo $lang['az_appaly_check']?></a></a>
<?php }?>
</az> -->
<a href="<?php echo urlShopWAP('goods','index',array('type'=>"appaly_goods",'wx'=>$_GET['wx'],'cate_id'=>$_GET['cate_id'],'goods_id'=>$value['goods_id']));?>" class="btn"><?php echo $lang['az_appaly_check']?></a>
<?php }?>
</div>
<script>
$(document).ready(function(){
	 $(".btn_n").click(function () {
		$("az").text("<?php echo $lang['az_appaly_read']?>");
	})
	});
</script>
</body>

<!doctype html>
<html>
<head>
<title><?php echo $output['title'] ;?>列表</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<meta http-equiv="Content-Language" content="UTF-8"/>
<meta name="format-detection" content="telephone=no"/>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.css">
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
</head>
<body class="gt_newpage_body">
<div class="train_position_top">
    <div class="arrow" onclick="location.href='index.php?act=show_store&op=index&store_id=<?php echo $_GET['store_id'];?>'"></div>
    <div class="text"><?php echo $output['title'] ;?></div>
</div>
<form id="search-form" method="get" action="index.php?">
    <input type="hidden" value="search" id="search_act" name="act" />
    <input type="hidden" value="product_list" id="search_act" name="op" />
    <input type="hidden" value="<?php echo $output["store_id"];?>"  name="store_id" />
    <input type="hidden" value="<?php echo $_GET["cate_id"];?>"  name="cate_id" />
    <div class="search_wrap_new" style="display: block;top: 45px;">
        <div class="search">
            <input type="text" id="keyword" name="keyword" placeholder="请输入商品名或商品ID" />
            <a class="search_btn"></a>
        </div>
    </div>
    <input type="submit" id="button" value="搜索" class="input-submit" style="display:none" />
</form>
<ul class="gt_list_order_title">
    <li class="<?php echo ($_GET['order']==1 && $_GET['key']==0) ? "on" : "";?>"><a class="<?php echo ($_GET['key']==0) ? "font_color" : "";?>" href="<?php echo $output['order_url'];?>&key=0&order=<?php echo $_GET['order']==1 ? 2:1;?>">默认</a></li>
    <li class="<?php echo ($_GET['order']==1 && $_GET['key']==1) ? "on" : "";?>"><a class="<?php echo ($_GET['key']==1) ? "font_color" : "";?>" href="<?php echo $output['order_url'];?>&key=1&order=<?php echo $_GET['order']==1 ? 2:1;?>"> 销量</a></li>
    <li class="<?php echo ($_GET['order']==1 && $_GET['key']==2) ? "on" : "";?>"><a class="<?php echo ($_GET['key']==2) ? "font_color" : "";?>" href="<?php echo $output['order_url'];?>&key=2&order=<?php echo $_GET['order']==1 ? 2:1;?>"> 人气</a></li>
    <li class="<?php echo ($_GET['order']==1 && $_GET['key']==3) ? "on" : "";?>"><a class="<?php echo ($_GET['key']==3) ? "font_color" : "";?>" href="<?php echo $output['order_url'];?>&key=3&order=<?php echo $_GET['order']==1 ? 2:1;?>"> 价格</a></li>
</ul>
<div class="gt_list_order">
    <?php foreach($output['goods_list'] as $value){?>
        <div class="item"  onclick="location.href='index.php?act=goods&op=index&from=1&goods_id=<?php echo $value['goods_id'] ?>'">
            <div class="img_wrap">
                <img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>">
            </div>
            <div class="text">
                <div class="tt"><?php echo $value['goods_name'];?></div>
                <div class="price">
                    <span><i>¥</i><?php echo $value['goods_price'];?></span>
                    <em>¥<?php echo $value['goods_marketprice'];?></em>
                </div>
                <div class="desc">
                    <span class="sales">已销 <?php echo $value['goods_salenum']; ?>份 </span>
                    <div class="mark">
                        <?php $jingle_array = explode(" ",$value['goods_jingle']);
                            if(!empty($jingle_array)){
                                foreach($jingle_array as $val){
                                    echo "<span>".$val."</span>";
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
</body>
</html>
<style>
    a{color: #666}
    .search_wrap_new {
        padding: 12px;
        background: #fff;
        height: 64px;
        top: 0;
        z-index: 1000;
        max-width: 750px;
    }
</style>
<script>
    $(".search_btn").click(function(){
        $("#search-form").submit();
    })
</script>








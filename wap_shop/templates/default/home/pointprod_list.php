<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>积分商城首页</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<style type="text/css">
    .integral_mall .integ_mallpro .mallpic .mall_right a {
        color:white;
    }
	.mallproduct_tit a {
        color:white;
    }
</style>
</head>

<body class="bg_gray">

<!--积分首页导航-->
<div class="integral_mall">
    <div class="search_results">
        <div class="search_results_nav_wrap fixed">
            <ul class="search_results_nav">
                <?php
                    $choice_class['default'] = '';
                    $choice_class['pointsdesc'] = '';
                    $choice_class['pointsndesc'] = '';

                    if($_GET['orderby']=='pointsdesc') {
                        $choice_class['pointsdesc'] = ' class="choice"';
                    } elseif($_GET['orderby']=='pointsndesc') {
                        $choice_class['pointsndesc'] = ' class="choice"';
                    } else {
                        $choice_class['default'] = ' class="choice"';
                    }
                ?>
                <li><a href="index.php?act=pointprod&op=index&orderby=default"<?php echo $choice_class['default']; ?>>全部</a></li>
                <li><a href="index.php?act=pointprod&op=index&orderby=pointsdesc"<?php echo $choice_class['pointsdesc']; ?>>积分值</a></li>
                <li><a href="index.php?act=pointprod&op=index&orderby=pointsndesc"<?php echo $choice_class['pointsndesc']; ?>>兑换量</a></li>
                <div class="clear"></div>
            </ul>
        </div>
    </div>
    <div class="integ_mallpro">
    <!--首页内容-->
    <?php if (is_array($output['pointprod_list']) && count($output['pointprod_list'])){?>
        <?php foreach ($output['pointprod_list'] as $v){?>
        <div class="mallpro">
            <div class="mallproduct">
                <a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>">
                    <img src="<?php echo str_replace('_mid', '', $v['pgoods_image']) ?>" title="<?php echo $v['pgoods_name']; ?>" alt="<?php echo $v['pgoods_name']; ?>">
                </a>
                <div class="mallproduct_tit">
                <a href="<?php echo urlShopWAP('pointprod', 'pinfo', array('id' => $v['pgoods_id']));?>">
                    <?php echo $v['pgoods_name']; ?>
                </a>
                <?php if (intval($v['pgoods_limitmgrade']) > 0){ ?>
                    <span class="pgoods-grade">V<?php echo intval($v['pgoods_limitmgrade']); ?></span>
                <?php } ?>
                </div>
            </div>
            <div class="mallpic">
                <div class="mall_left">
                     <span class="mlpic01"></span>
                     <span class="mlpic02"><?php echo $v['pgoods_points']; ?></span>
                     <span class="mlpic03">￥<?php echo $v['pgoods_price']; ?></span>
                </div>
                <input type="hidden" id="storagenum_<?php echo $v['pgoods_storage']; ?>" value="">
                <?php if ($output['prodinfo']['pgoods_islimit'] == 1){?>
                    <input type="hidden" id="limitnum_<?php echo $v['pgoods_id']; ?>" value="<?php echo $v['pgoods_limitnum']; ?>"/>
                <?php } else {?>
                    <input type="hidden" id="limitnum_<?php echo $v['pgoods_id']; ?>" value=""/>
                <?php } ?>
                <input type="hidden" id="exnum_<?php echo $v['pgoods_id']; ?>" value="1">
                <div class="mall_right">
                     <a href="javascript:;" class="mlpic04" onclick="add_to_cart(<?php echo $v['pgoods_id']; ?>)">我要兑换</a>
                </div>
            </div>
        </div>
        <?php } ?>
    <?php }else{?>
        <div class="norecord"><?php echo $lang['pointprod_list_null'];?></div>
    <?php }?>
    </div>
</div>

<!--公共底部导航-->
<div class="bottomHeight"></div>
<!--返回头部-->
<a href="javascript:;" class="back_to_top gf_back_to_top has_bottom"><img src="<?php echo SHOP_TEMPLATES_URL; ?>/images/backtop.png" /></a>
<script>
$(function(){
    //页面滚动显示，点击返回头部按钮
    $(window).scroll(function(){
        if($(window).scrollTop()>0){
            $(".back_to_top").fadeIn(500);
            }
        else{
            $(".back_to_top").fadeOut(500);
            }
        })
    //点击返回头部页面滚动到页面顶部
    $(".back_to_top").on("click",function(){
        $("body,html").stop().animate({"scrollTop":"0px"},500,function(){
            $(".back_to_top").fadeOut(500);
            });
        })
    
    //公共底部 点击选中效果范例展示
    $(".footer ul li").click(function(){
        $(this).find("a").addClass("selected").end().siblings().find("a").removeClass("selected");
        })
        
        
    //搜索列表导航点击效果
    $(".integral_mall .search_results .search_results_nav li").click(function(){
        $(this).find("a").addClass("choice").end().siblings("li").find("a").removeClass("choice");
        })
})
//加入购物车
function add_to_cart(pgid)
{
    var storagenum = parseInt($("#storagenum_"+pgid).val());//库存数量
    var limitnum = parseInt($("#limitnum_"+pgid).val());//限制兑换数量
    var quantity = parseInt($("#exnum_"+pgid).val());//兑换数量
    //验证数量是否合法
    var checkresult = true;
    var msg = '';
    if(!quantity >=1 ){//如果兑换数量小于1则重新设置兑换数量为1
        quantity = 1;
    }
    if(limitnum > 0 && quantity > limitnum){
        checkresult = false;
        msg = '<?php echo $lang['pointprod_info_goods_exnummaxlimit_error']; ?>';
    }
    if(storagenum > 0 && quantity > storagenum){
        checkresult = false;
        msg = '<?php echo $lang['pointprod_info_goods_exnummaxlast_error']; ?>';
    }
    if(checkresult == false){
        alert(msg);
        return false;
    }else{
        $.getJSON('index.php?act=pointcart&op=add&pgid='+pgid+'&quantity='+quantity, function(result){
            console.log(result);
            if(result.done){
                //window.location.href = 'index.php?act=pointcart';
                window.location.href = 'index.php?act=pointcart&op=address';
            } else {
                if(result.url){
                    alert(result.msg);
                    window.location.href = result.url;
                } else if(result.msg) {
                    alert(result.msg);
                } else {
                    window.location.href = 'index.php?act=member&op=home';
                }
            }
        });     
    }
}  
</script>
</body>
</html>

<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">

<title>商品列表</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/style.css" />

<link rel="stylesheet" type="text/css" href="<?php echo WAP_SITE_URL;?>/css/member_style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/common.css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/swiper.min.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/iscroll.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL;?>/js/menu.js"></script>
<style>
#choice<?php echo $_GET['key']?> {
    color: #bba059;
}
.search_results .search_results_nav li>a .price img {
    float: left;
    width: 10px;
    margin-left: 6px;
    margin-top: 16px;
    transform: rotate(0deg);
    transition: all .5s ease-in-out;
}
.search_results .search_results_nav li>a .price.click img {
    transform: rotate(0deg);
}
.search_wrap_new {
    padding: 12px;
    background: #fff;
    height: 64px;
    top: 0;
    z-index: 1000;
    max-width: 750px;
    display: none;
}
    .search_results .search_results_nav li {
    width: 25% !important;}
</style>
<header class="ui-header ui-header-positive qz-header-background qz-header-bb">
    <i class="ui-icon-return" onclick="history.back()" ></i>
    <h1 class="qz-color">商品列表</h1>
     <span class="top_menu_ico"><i class="fa fa-navicon"></i></span>
</header>
<?php require_once template('menu');?>

<!--魔药系列-->
<div class="potions_list">
	<!--搜索框-->
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
    <!--搜索列表-->
    <div class="search_results">
        <div class="search_results_nav_wrap fixed">
            <ul class="search_results_nav">
                <li><a href="<?php echo replaceParam(array('key' => '0', 'order' => '0')); ?>"
                       <?php if($_GET['key']==0){ ?>class="choice"<?php } ?>>默认
                    </a>
                </li>
                <li>
                    <a href="<?php echo ($_GET['order'] == '2' && $_GET['key'] == '1')
                                ? replaceParam(array('key' => '1', 'order' => '1'))
                                : replaceParam(array('key' => '1', 'order' => '2'));
                            ?>" <?php if($_GET['key']==1){ ?>class="choice"<?php } ?>>
                        <div class="price
              <?php if($_GET['key']!=1 || ($_GET['order']==2 && $_GET['key']==1)){ ?> click<?php } ?>">
                            <span>销量</span>
                            <?php if($_GET['key']!=1
                                || ($_GET['order']==2
                                    && $_GET['key']==1
                                )
                            ){ ?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_down.png" />
                            <?php } else {?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_up.png" />
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php
                                echo ($_GET['order'] == '2' && $_GET['key'] == '2')
                                ? replaceParam(array('key' => '2', 'order' => '1'))
                                :replaceParam(array('key' => '2', 'order' => '2'));
                            ?>" <?php if($_GET['key']==2){ ?>class="choice"<?php } ?>>
                        <div class="price
                                <?php if($_GET['key']!=2
                                         || ($_GET['order']==2
                                             && $_GET['key']==2
                                            )
                                        ){ ?> click<?php } ?>"
                            >
                            <span>人气</span>
                            <?php if($_GET['key']!=2
                                || ($_GET['order']==2
                                   && $_GET['key']==2
                                )
                            ){ ?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_down.png" />
                            <?php } else {?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_up.png" />
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php
                                echo ($_GET['order'] == '2' && $_GET['key'] == '3')
                                ? replaceParam(array('key' => '3', 'order' => '1'))
                                :replaceParam(array('key' => '3', 'order' => '2'));
                            ?>" <?php if($_GET['key']==3){ ?>class="choice"<?php } ?>>
                        <div class="price
                                <?php if($_GET['key']!=3
                                         || ( $_GET['order']==2
                                              &&$_GET['key']==3
                                            )
                                        ){ ?> click<?php } ?>"
                            >
                            <span>价格</span>
                            <?php
                            if($_GET['key']!=3
                                || ( $_GET['order']==2&&$_GET['key']==3)
                            ){ ?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_down.png" />
                            <?php } else { ?>
                            <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/sj_up.png" />
                            <?php } ?>
                            <div class="clear"></div>
                        </div>
                    </a>
                </li>
                 <!-- <li><a href="javascript:;"><div class="shaixuan2"><span>筛选</span><span class="sj"></span><div class="clear"></div></div></a></li> -->
                <div class="clear"></div>
            </ul>
            <!--筛选-->
            <div class="screening" id="screening">
                <ul>
                <?php foreach($output["product_type"] as $type){?>
                    <li><a href="<?php echo urlShopWAP('search','product_list',array('cate_id'=>$type['gc_id']));?>"><?php echo $type["gc_name"]?></a></li>
                   <?php }?>
                    <div class="clear"></div>
                </ul>
            </div>
        </div>
        <div class="order_states_list">
            <ul>
            <?php foreach($output['goods_list'] as $value){?>
                <li>
                    <div class="middle_wrap">
                        <div class="middle">
                            <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_name'];?>">
                                <div class="order_goods" nctype_goods=" <?php echo $value['goods_id'];?>" nctype_store="<?php echo $value['store_id'];?>">
                                    <div class="goods_pic"><div class="self_width"><img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" /></div></div>
                                    <div class="goods_see"></div>
                                    <div class="goods_dis">
                                        <div class="title"><?php echo $value['goods_name'];?></div>
                                        <div class="goods_fun"><?php echo $value['goods_jingle'];?></div>
                                        <div class="goods_price">
                                            <div class="present_price"><span>&yen;</span><?php echo $value['goods_price'];?></div>
                                            <span class="original_price">&yen;<?php echo $value['goods_marketprice'];?></span>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                   </div>
                </li>
            <?php }?>

            </ul>
            <?php if(empty($output['goods_list'])) {?>
            <p>暂无相关记录</p>
            <?php } ?>
            <!--<div class="pullup">
                <div class="pullupcons">

                    <div class="loading-3">
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                        <i></i>
                    </div>

                    <span class="msg">下拉加载更多</span>
                    <span id="over" style="display:none">已加载全部数据</span>
                </div>-->
            </div>
            <div class="shaixuan_mask"></div>
			<div class="show_page"><center><?php echo $output["page"]?></center></div>
        </div>
    </div>
</div>

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
        $(".top_menu_ico").on("click",function(){
        if(!$(".sub_menu").hasClass("animate")){
            $(".shaixuan").removeClass("animate");
            $(".screening").slideUp(500);
            $(".shaixuan_mask").hide();

            $(".sub_menu").addClass("animate");
            $("body,html").css({"height":"200px","overflow":"hidden"});
            }
        else{
            $(".sub_menu").removeClass("animate");
            $("body,html").css({"height":"auto","overflow":"auto"});
            }
        })

    //公共底部 点击选中效果范例展示
    $(".footer ul li").click(function(){
        $(this).find("a").addClass("selected").end().siblings().find("a").removeClass("selected");
        })

    //搜索框关闭按钮显示/隐藏 和事件
    $(".search input").keydown(function(){
        $(".empty").show();
        })
    $(".empty").on("click",function(){
        $(this).hide().siblings("input").val("").focus();
        })

    //搜索列表导航点击效果
    $(".search_results .search_results_nav li").click(function(){
        $(this).find("a").addClass("choice").end().siblings("li").find("a").removeClass("choice");
        })

    //搜索列表 里 点击筛选
    $(".search_results .search_results_nav .shaixuan").on("click",function(){
        $(".screening").slideToggle(500);
        $(this).toggleClass("animate");
        $(".shaixuan_mask").toggle();
        })
    $(".screening ul li a").on("click",function(){
        $(".screening").slideUp(500);
        $(".search_results .search_results_nav .shaixuan").removeClass("animate");
        $(".shaixuan_mask").hide();
        })

    //点击价格
    $(".search_results .search_results_nav li>a .price").click(function(){
        $(this).toggleClass("click");
    })
    $('.shaixuan2').toggle(function(){
		$(".screening").slideDown(500);
		},function(){
			$(".screening").slideUp(500);
	});
	$(".search_btn").click(function(){
		$("#search-form").submit();
	})
    //下拉效果范例
    var t=3;//设置假的加载次数
    $(window).scroll(function(){
           //滚动条到网页头部的 高度，兼容ie,ff,chrome
           var top = document.documentElement.scrollTop + document.body.scrollTop;
           var bottomH=$(".bottomHeight").height()-50;
           //alert(document.documentElement.scrollTop)
           //alert(document.body.scrollTop)
           //网页的高度
          var textheight =$(document).height();
          //alert(textheight)
          // 网页高度-top-当前窗口高度
          var topHeight =$(window).height();
          var lastHeight=textheight-top-topHeight;
          //$(".movehtml").html("页面高度："+textheight+"<br />"+"滚动的高度："+top+"<br />"+"屏幕高度："+topHeight+"<br />"+"滚动剩余的高度："+lastHeight+"<br />"+"要求加载要小于的高度："+bottomH+"<br />"+"剩余加载次数"+t);
          if (lastHeight<=bottomH){
              //加载数据
              var html='';
              if(t==0){//加载了全部
                  $("#over").show();
                  $(".msg,.loading-3").hide();
                  }
             else{
              $(".msg").html("加载中...");
              setTimeout(function(){
                  for(i=0;i<3;i++){
                      html+='<li><div class="middle_wrap"><div class="middle"><a href="javascript:;"><div class="order_goods"><div class="goods_pic"><div class="self_width"><img src="images/sppic_03.png" /></div></div><div class="goods_see"></div><div class="goods_dis"><div class="title">雅诗兰黛面部精华ANR特润修护精华露30/50ml小棕瓶雅诗兰黛面部精华ANR特润修护精华露30/50ml小棕瓶</div><div class="goods_fun">限时加赠 原生液7ml 眼精华4ml 滋润保湿限时加赠 原生液7ml 眼精华4ml 滋润保湿</div><div class="goods_price"><div class="present_price"><span>&yen;</span>590.00</div><span class="original_price">&yen;1090.00</span><div class="clear"></div></div></div></div></a></div></div></li>'
                  }
                  $(".potions_list .order_states_list ul").append(html);
                  $(".msg").html("下拉加载更多");
                  t--;
                  },1000);
                 }
          }
         });
    })
</script>
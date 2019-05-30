<title><?php echo $output['html_title'] ?></title>
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css">

<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/style.css" />

<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL; ?>/js/TouchSlide.1.1.js"></script>
<script type="text/javascript" src="<?php echo WAP_SITE_URL; ?>/js/menu.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/masonry.pkgd.min.js"></script>
<!--<script src="<?php /*echo RESOURCE_SITE_URL;*/?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php /*echo RESOURCE_SITE_URL;*/?>/js/sns.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php /*echo RESOURCE_SITE_URL;*/?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>-->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL ?>/js/layer-v2.1/layer/layer.js"></script>

<script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL; ?>/js/main.js"></script> <!--替换alert -->

<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/goods.css" />
<style type="text/css">
    .goods_title{word-break: break-all;}
    .goods_details .buy_choice .kucun{
        font-size: 16px;
        line-height: 40px;
        height: 34px;
    }
    .goods_details .buy_choice .mykucun {
        display: none;
    }
</style>
<header class="product_detail_hd">
    <?php if (!empty($_GET['from']) && $_GET['from'] == 1) {
        $back_url = "index.php?act=search&op=product_list&cate_id=" . $output['goods']['gc_id'] . "&store_id=" . $output['goods']['store_id'];
    }elseif($_GET['from']=="singlemessage" || $_GET['from']=="timeline"){
        $back_url= "index.php?act=show_store&op=index&store_id=". $output['goods']['store_id'];
    }?>
    <div class="arrow" onclick=" <?php if($back_url){echo "location.href='$back_url'";}else{echo "history.back()";} ?>"></div>
    <h1>详情</h1>
    <div class="icon">
        <a class="icon1" href="javascript:" id="shouchang" ></a>
<!--        <a class="icon2" href="javascrpt:" id="fengxiang"></a>-->
    </div>
</header>
<script type="text/javascript" src="/wap/js/swiper.min.js"></script>
<script type="text/javascript" src="/wap/js/iscroll.js"></script>
<script type="text/javascript" src="/wap/js/menu.js"></script>

<div class="sub_menu_bg"></div>
<div class="sub_menu" id="myScroll" style="display:none"></div>
<input type="hidden" id="lockcompare" value="unlock" />
<!--商品详情页面-->
<div class="goods_details">
    <!--商品图-->
        <div id="qz-picScroll" class="qz-picScroll">
        <div class="hd">
            <ul></ul>
        </div>

        <div class="bd">
            <ul>
                <?php if (!empty($output['goods_image'])) {
    ?>

                    <?php $imglist = $output['goods_image'];
    foreach ($imglist as $key => $img) {
        $img_one = explode(",", $img);

        $img_one_url_array = explode("'", $img_one[2]);

        $img_one_url = $img_one_url_array[1];
        //$img_one_url = str_replace("'","",$img_one_url);
        $imglist_new[] = $img_one_url;

    }

    for ($i = 0; $i < count($imglist_new); $i++) {
        ?>
                             <li>
                    <a href="javascript:"><img src="<?php echo $imglist_new[$i]; ?>" class="qz-img-block"></a>
                    <!--<div class="qz-loc-num qz-light"><?php /*echo ($i+1).'/'.(count($imglist_new));*/?></div>-->
                    <!--<div class="qz-loc-title">
                        <p class="ui-nowrap qz-light"><?php /*echo $output['goods']['goods_name']; */?></p>
                    </div>-->
                </li>
                            <?php
}
    ?>
                 <?php }?>

            </ul>
        </div>
    </div>
    <script type="text/javascript">
        var price_old ;
        TouchSlide({
            slideCell:"#qz-picScroll",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul",
            effect:"left",
            autoPlay:true,//自动播放
            autoPage:true, //自动分页
            interTime:6000,
        });
    </script>

    <div class="product_detail_title">
        <?php echo $output['goods']['goods_name'] ?>
    </div>
    <?php if ($output['goods']['calendar_type'] == 2) {
    ?>
        <div class="goods_mark">

            <div class="marks">
                <?php if ($output['goods']['goods_jingle']) {
        ?>
                   <?php $goods_jingle_arr = explode(" ", $output['goods']['goods_jingle']);
        foreach ($goods_jingle_arr as $value) {?>
                        <a href="javascript:;"><?php echo $value ?></a>
                    <?php }?>
                <?php }?>
            </div>
            <div class="goods_price">
                <i>￥</i>
                <span><?php echo $output['goods']['goods_price']; ?></span>
                <em>起</em>
            </div>
        </div>
    <?php } else {?> <!--显示商城价格以及折数 -->
        <div class="goods_price2">
            <?php if (($output['goods']['goods_price'] / $output['goods']['goods_marketprice']) < 1) {?>
                <div class="tagging">
            <span>
                    <?php echo $rate = round($output['goods']['goods_price'] / $output['goods']['goods_marketprice'] * 10, 1) > 0 ? round($output['goods']['goods_price'] / $output['goods']['goods_marketprice'] * 10, 1) : 0.1; ?>
                </span>折
                </div>
            <?php }?>
            <div class="price">
                <p class="org">商城价：<span>&yen;</span><i id="goods_price">
                        <?php if ($_SESSION['share_shop'] == 1 and $output['goods']['isshare']) {?><!-- 是否是分销商品 -->
                        <?php echo $output['goods']['share_price']; ?><!--分销价格-->
                        <?php } elseif (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
                            <?php echo $output['goods']['promotion_price']; ?><!--促销价-->
                        <?php } else {?>
                            <?php echo $output['goods']['goods_price']; ?>
                        <?php }?></i>
                </p>
            </div>
            <span class="qz-del">
            市场价:&yen;<i id="market_price"><?php echo $output['goods']['goods_marketprice']; ?></i>
            </span>


        <?php } ?>

        <script>
            $(document).ready(function () {
                $("#calendar_hour").bind("change",function(){
                    $("#calendar_hour_input").val($(this).val());
                });
                $("#calendar_min").bind("change",function(){
                    $("#calendar_min_input").val($(this).val());
                });
            });
        </script>

        <!-- 价格日历结束-->
    <script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>
    <script type="text/javascript">
        function hotel_x(data){
//            var data=JSON.parse(data);
            var data = eval('(' + data + ')');//json字符串转换成对象
            var price=data.price;
            var day=data.day;
            var stock=data.stock;
            $('.buy_quantity .hotel_in_date').val(day[0]);
            $('.buy_quantity .hotel_out_date').val(day[1]);

            $('.buy_quantity .hotel_price').val(price[0]);//第一天的价格
            str1 = day[0].replace(/-/g,'/');
            str2 = day[1].replace(/-/g,'/');
            var date1 = new Date(str1);
            var date2 = new Date(str2);
            var hotel_num = (date2 -date1)/(60*60*24)/1000;
            $(".buy_quantity .hotel_num").val(hotel_num);//住宿天数
            $('.mykucun span').text(stock[1]);//库存取第二天


        }
        function putong_x(data){
            $('.bill_btns').css("display","block");
            var data=JSON.parse(data);
            var child_price=data.child_pirece;
            var man_price=data.man_pirece;
            var diff_price=data.diff_price;
            var day=data.day;
           /* if(child_price == "无"){
                $("#putong_child_price").prev('i').text('').parent("div").addClass("no");
            }
            if(man_price == "无"){
                $("#putong_man_price").prev('i').text('').parent("div").addClass("no");

            }
            if(diff_price == "无"){
                $("#putong_diff_price").prev('i').text('').parent("div").addClass("no");
            }*/
            if(child_price == "无" || child_price=='0'){
                $("#putong_child_price").parent("div").css('display','none');
            }
            if(man_price == "无" || man_price=='0'){
                $("#putong_man_price").parent("div").css('display','none');

            }
            if(diff_price == "无" || diff_price=='0'){
                $("#putong_diff_price").parent("div").css('display','none');
            }
            $('.putong_date').val(day);
            $("#putong_child_price").text(child_price);//儿童价
            $("#putong_man_price").text(man_price);//儿童价
            $("#putong_diff_price").text(diff_price);//单房差
        }
        function golf_x(data){
            var data=JSON.parse(data);
            var day=data.day;
            $('#picktime').val(day);
        }

        $('#rili_span_stock').click(function(){
            var url=$(this).attr('title');
            if(<?php echo $output['calendar_type']['calendar_type'] ==1 ? 1:0 ?>){ //普通旅游门票价格日历弹出窗
                var common_id= '<?php   echo $output['calendar_type']['goods_commonid'];?>'*1;
                var package = $("#spec_name_input").val();

                if(<?php echo !empty($output['goods']['spec_name']) ==1 ? 1:0 ?>){ //如果添加了商品规格及价格日历套餐
                    if(package =='-1'){
                        var title = $('.goods_types section:eq(0)').find('.buy_choice_content dt').attr("title");
                        layer.msg("请选择"+title);return;
                    }
                    url = '?act=goods_stock_price&op=index&commonid='+common_id+'&calendar_type=1&package='+package;
                }

            }

            layer.open({
                type: 2,
                title:'  ',
                area: ['100%', '90%'],
                content: url //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        });

    </script>
    <?php if($output['calendar_type']['calendar_type'] ==2 || $output['calendar_type']['calendar_type'] ==3){ ?>
		<!--   酒店和高尔夫单个存订单，数量为1-->
        <div class="buy_choice">
            <div class="kucun">
                <span>销售： <?php echo $output['goods']['goods_salenum']; ?></span>
                <input type="hidden" class="num" id="quantity" value="1" />

                <div class="mykucun">库存： <span><?php echo $output['goods']['goods_storage']; ?> </span></div><!--库存验证 -->
            </div>

        <?php } ?>

        <script>
            $(document).ready(function () {
                $("#calendar_hour").bind("change",function(){
                    $("#calendar_hour_input").val($(this).val());
                });
                $("#calendar_min").bind("change",function(){
                    $("#calendar_min_input").val($(this).val());
                });
            });
        </script>

        <!-- 价格日历结束-->
    <script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>
    <script type="text/javascript">
        function hotel_x(data){
//            var data=JSON.parse(data);
            var data = eval('(' + data + ')');//json字符串转换成对象
            var price=data.price;
            var day=data.day;
            var stock=data.stock;
            $('.buy_quantity .hotel_in_date').val(day[0]);
            $('.buy_quantity .hotel_out_date').val(day[1]);

            $('.buy_quantity .hotel_price').val(price[0]);//第一天的价格
            str1 = day[0].replace(/-/g,'/');
            str2 = day[1].replace(/-/g,'/');
            var date1 = new Date(str1);
            var date2 = new Date(str2);
            var hotel_num = (date2 -date1)/(60*60*24)/1000;
            $(".buy_quantity .hotel_num").val(hotel_num);//住宿天数
            $('.mykucun span').text(stock[1]);//库存取第二天


        }
        function putong_x(data){
            $('.bill_btns').css("display","block");
            var data=JSON.parse(data);
            var child_price=data.child_pirece;
            var man_price=data.man_pirece;
            var diff_price=data.diff_price;
            var day=data.day;
           /* if(child_price == "无"){
                $("#putong_child_price").prev('i').text('').parent("div").addClass("no");
            }
            if(man_price == "无"){
                $("#putong_man_price").prev('i').text('').parent("div").addClass("no");

            }
            if(diff_price == "无"){
                $("#putong_diff_price").prev('i').text('').parent("div").addClass("no");
            }*/
            if(child_price == "无" || child_price=='0'){
                $("#putong_child_price").parent("div").css('display','none');
            }
            if(man_price == "无" || man_price=='0'){
                $("#putong_man_price").parent("div").css('display','none');

            }
            if(diff_price == "无" || diff_price=='0'){
                $("#putong_diff_price").parent("div").css('display','none');
            }
            $('.putong_date').val(day);
            $("#putong_child_price").text(child_price);//儿童价
            $("#putong_man_price").text(man_price);//儿童价
            $("#putong_diff_price").text(diff_price);//单房差
        }
        function golf_x(data){
            var data=JSON.parse(data);
            var day=data.day;
            $('#picktime').val(day);
        }

        $('#rili_span_stock').click(function(){
            var url=$(this).attr('title');
            if(<?php echo $output['calendar_type']['calendar_type'] ==1 ? 1:0 ?>){ //普通旅游门票价格日历弹出窗
                var common_id= '<?php   echo $output['calendar_type']['goods_commonid'];?>';
                var package = $("#spec_name_input").val();

                if(<?php echo !empty($output['goods']['spec_name']) ==1 ? 1:0 ?>){ //如果添加了商品规格及价格日历套餐
                    if(package =='-1'){
                        var title = $('.goods_types section:eq(0)').find('.buy_choice_content dt').attr("title");
                        layer.msg("请选择"+title);return;
                    }
                    url = '?act=goods_stock_price&op=index&commonid='+common_id+'&calendar_type=1&package='+package;
                }

            }

            layer.open({
                type: 2,
                title:'  ',
                area: ['100%', '90%'],
                content: url //这里content是一个URL，如果你不想让iframe出现滚动条，你还可以content: ['http://sentsin.com', 'no']
            });
        });

    </script>
    <?php if($output['calendar_type']['calendar_type'] ==2 || $output['calendar_type']['calendar_type'] ==3){ ?>
		<!--   酒店和高尔夫单个存订单，数量为1-->
        <div class="buy_choice">
            <div class="kucun">
                <span>销售： <?php echo $output['goods']['goods_salenum']; ?></span>
                <input type="hidden" class="num" id="quantity" value="1" />

                <div class="mykucun">库存： <span><?php echo $output['goods']['goods_storage']; ?> </span></div><!--库存验证 -->
            </div>

        </div>
    <?php }?>


    <!--选择类型-->

    <ul class="goods_telmsg" style="clear: both;">
        <a href="tel:<?php echo $output['store_info']['store_phone']; ?>">
            <li class="item" mce_href="tel:<?php echo $output['store_info']['store_phone']; ?>" style="width: 30%;">
                <span class="icon"></span>
                <span class="text">电话咨询</span>
            </li>
        </a>
        <li class="item"<?php if ($output['goods']['flag'] == 1) {
    ?> onclick="loadHtml()"<?php }?> style="width: 70%;">
            <span <?php if ($output['goods']['flag'] == 1) {
    ?>class="icon2"<?php } else {?>style="display: inline-block; width: 30px; height: 30px; position: relative; top: 0.6rem;background: url(/wap_shop/templates/default/images/kefu_offline.png) no-repeat center; background-size: 22px 22px;"<?php }?>></span>
       在线客服
     <span class="text"><em>(服务时段:<?php echo $output['goods']['kefu_time']; ?>)</em></span>

        </li>
    </ul>
    <!--价格日历，车票，规格-->
<?php require_once BASE_PATH . DS . "/templates/default/store/calendar.php"?>

  <!-- -图文详情- -->

    <?php if (!empty($output['store_info']['store_qq']) || !empty($output['store_info']['store_ww']) || !empty($output['store_info']['store_phone'])) {?>
        <div class="qz-zxlist qz-background-white qz-padding clearfix" style="display:none">
        <!--<dl>
            <span class="qz-ico qz-ico-message"></span>
            <div class="ti">即时通讯</div>
        </dl>-->
        <?php if (!empty($output['store_info']['store_qq'])) {?>
        <dl>
        <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $output['store_info']['store_qq']; ?>&site=qq&menu=yes" title="QQ: <?php echo $output['store_info']['store_qq']; ?>">
            <span class="qz-ico qz-ico-qq"></span>
            <div class="ti">企鹅QQ</div>
        </a>
        </dl>
        <?php }?>
        <?php if (!empty($output['store_info']['store_phone'])) {?>
        <dl>
        <a href="tel:<?php echo $output['store_info']['store_phone']; ?>" mce_href="tel:<?php echo $output['store_info']['store_phone']; ?>"><span class="qz-ico qz-ico-tel"></span>
            <div class="ti">联系电话</div></a>
        </dl>
        <?php }?>
        <?php if (!empty($output['store_info']['store_ww'])) {?>
        <dl>
        <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $output['store_info']['store_ww']; ?>&site=cntaobao&s=1&charset=<?php echo CHARSET; ?>" >
            <span class="qz-ico qz-ico-wangw"></span>
            <div class="ti">阿里旺旺</div>
        </a>
        </dl>
        <?php }?>
    </div>
    <?php }?>
    <div class="qz-bk10"></div>
  <!--商品属性、图文描述、商品评价-->
    <div class="goods_type">
        <ul>
            <li class="sel">图文描述</li>
            <li class="borderRnone" id='goods_commond'>商品评价</li>
            <div class="clear"></div>
        </ul>
        <div class="goods_type_box goods_type_box1" id="goods_info">
            <!-- -图文描述信息HOME- -->
            <?php
if (!empty($output['goods']['mobile_body'])) {
    echo $output['goods']['mobile_body'];
} else {
    echo $output['goods']['goods_body'];
}
?>
            <!-- -图文描述信息END- -->
        </div>
        <div class="goods_type_box goods_type_box2" id="goods_commond">
            <div class="goods_pj_empty" style="display:none">暂无相关评价内容</div>
            <ul class="commodity_evaluation">

            </ul>

            <a  href="<?php echo urlShopWap('goods', 'good_pj_list', array("goods_id" => $output['goods']["goods_id"])); ?>" class="see_more_pj">查看更多评价</a>
        </div>
    </div>

    <!--用户还买了-商品推荐-->
    <?php if (!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend']) > 1) {?>
    <div class="product_recommendation">
        <div class="title">用户还买了</div>
        <ul>
        <!--推荐HOME-->
        <?php foreach ($output['goods_commend'] as $goods_commend) {?>
       <?php if ($output['goods']['goods_id'] != $goods_commend['goods_id']) {?>
            <li>
                <div class="middle_wrap">
                    <div class="middle">
                        <a href="<?php echo urlShopWAP('goods', 'index', array('goods_id' => $goods_commend['goods_id'])); ?>" target="_blank" title="<?php echo $goods_commend['goods_jingle']; ?>">
                            <div class="order_goods">
                                <div class="goods_pic"><div class="self_width"><img src="<?php echo thumb($goods_commend, 240); ?>" /></div></div>
                                <div class="goods_dis">
                                    <div class="title"><?php echo $goods_commend['goods_name']; ?></div>
                                    <div class="goods_fun"><?php echo $goods_commend['goods_jingle']; ?></div>
                                    <div class="goods_price">
                                        <div class="present_price"><span>&yen;</span> <?php echo $goods_commend['goods_price']; ?></div>
                                        <span class="goods_cars"></span>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </li>
        <!----推荐END--->
            <?php }}?>
        </ul>
    </div>
    <?php }?>
</div>

<!--购买产品时的底部购买、分享、收藏、加入购物车导航-->
<form id="buynow_form" method="post" action="<?php echo WAP_SHOP_SITE_URL; ?>/index.php">
           <input id="act" name="act" type="hidden" value="buy" />
           <input id="op" name="op" type="hidden" value="buy_step1" />
           <input name="calendar_type" type="hidden" value="<?php echo $output['goods']['calendar_type']; ?>"/>
            <input id="cart_id" name="cart_id[]" type="hidden"/>
           <input id="tb_commonid" name="tb_commonid" value='<?php echo $output["goods"]["goods_commonid"] ?>' type="hidden"/>
            <input id="tb_package" name="tb_package" type="hidden" />
            <input id="tb_spec_value" name="tb_spec_value" type="hidden" />
            <input id="tb_goods_name" name="tb_goods_name" type="hidden" />
            <input id="tb_goods_id" name="tb_goods_id" type="hidden" />
            <input id="vald_msg" type="hidden" />
            <input id="get_date" name="date" type="hidden" value="<?php echo $output['goods']['get_date']; ?>"/>
            <input id="is_share" type="hidden" name="is_share"  value="<?php echo $output['goods']['isshare']; ?>"/><!-- 分销商品-->
            <input id="tb_type_id" name="tb_type_id" value='<?php echo $output["goods"]["type_id"] ?>' type="hidden" />
            <input id="calendar_date" name="calendar_date" value='calendar_date[]' type="hidden" autocomplete="off" /><!--价格类型商品属性 -->
            <input  name="store_id"  value='<?php echo $output['goods']['store_id']; ?>' type="hidden" /><!--店铺ID-->
  </form><div style="height:20px"></div>
<!--返回头部
<a href="javascript:;" class="back_to_top gf_back_to_top has_bottom"><img src="<?php echo SHOP_TEMPLATES_URL; ?>/images/backtop.png" /></a>-->
<footer class="ui-footer" style="height:auto;"><div id="menu"></div>
    <ul class="qz-padding qz-background-white qz-bottom-b qz-top-b qz-light" id="qz-buttom">
    <?php if ($_SESSION['is_login']) {?>

    <a href="<?php echo WAP_SHOP_SITE_URL; ?>/index.php?act=member&op=home"><?php echo $output['member_info']['member_name']; ?></a>&nbsp;&nbsp;
    <a href="<?php echo WAP_SHOP_SITE_URL; ?>/index.php?act=login&op=logout">[退出]</a>
    <?php } else {?>
    <a href="<?php echo WAP_SHOP_SITE_URL; ?>/index.php?act=login&op=index">[登录]</a>
    <a href="<?php echo WAP_SHOP_SITE_URL; ?>/index.php?act=login&op=register">[注册]</a>
    <?php }?>

        <a class="qz-color4 top-btn" href="javascript:void(0);">
            <i class="qz-ico qz-ico-top qz-fr"></i>
            <span class="qz-fr">返回顶部&nbsp;</span>
        </a>

    </ul>


     <div id="gt_subPrice_btn" class="qz-padding qz-background-white clearfix">
        <div class="qz-ft-l qz-fl">
        <!--S 立即购买-->
        <?php if ($_SESSION['is_login']) {?>
           <dl class="qz-fl" >
           <a href="javascript:void(0);" id="az" class="fr buynow <?php if (($output['goods']['isshare'] == 1 and $_SESSION['share_shop'] == 1 and $output['goods']['share_stock'] <= 0) or ($_SESSION['share_shop'] == 0 and ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)))) {?> no-buynow <?php }?>" title="<?php echo $output['goods']['buynow_text']; ?>">
             <input type="submit" value="<?php /*echo $output['goods']['buynow_text'];*/?>立即预定" class="ui-btn-lg ui-btn-primary" />
             </a>
              <!--  显示的调整,如果是分销那么判断  share_stock 不是判断  goods_storage-->
              <?php if($_SESSION['share_shop'] == 1 and $output['goods']['isshare'] == 1){  ?>
                    <?php if($output['goods']['share_stock']){?>
		                    <?php if ($output['goods']['calendar_type'] == 1) {?>
		                       <div class="sub_price">
		                          	 在线支付 <span>￥<em id="ss_price" class="old_price">0</em></span>
		                       </div>
		                   <?php } else {?>
		                       <div class="sub_price">
		                           	在线支付 <span>￥<em id="ss_price" class="old_price"><?php echo $output['goods']['goods_price']; ?></em></span>
		                       </div>
		                  <?php }?>
                    <?php }?>
              <?php }else{ ?>
	              	<?php if ($output['goods']['goods_storage'] > 0 ) {?>
	              	      <?php if ($output['goods']['calendar_type'] == 1) {?>
		                       <div class="sub_price">
		                          	 在线支付 <span>￥<em id="ss_price" class="old_price">0</em></span>
		                       </div>
		                   <?php } else {?>
		                       <div class="sub_price">
		                           	在线支付 <span>￥<em id="ss_price" class="old_price"><?php echo $output['goods']['goods_price']; ?></em></span>
		                       </div>
		                  <?php }?>
	              	<?php }?>
               <?php }?>
          </dl>
        <!--E 立即购买-->

        <!--S 加入购物车-->

        <?php if (false and $output['goods']['cart'] == true) {?>
            <dl class="qz-fr" id="cart-add" >
             <a href="javascript:void(0);"  nctype="addcart_submit" class="addcart  <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
             no-addcart
             <?php }?>"
             title="<?php echo $lang['goods_index_add_to_cart']; ?>">
             <i class="icon-shopping-cart"></i>
               <input type="submit" value="<?php echo $lang['goods_index_add_to_cart']; ?>" class="ui-btn-lg ui-btn-primary" />
             </a>
            </dl>
        <?php }?>
        <!--E 加入购物车-->
        <!--S 我的购物车-->

        </div>
        <a href="index.php?act=cart">
            <!--测试说把这个购物车标识隐藏起来-->
       <!-- <span class="qz-fr qz-ico qz-ico-shopping qz-relative" id="span_car">
           <div class="ui-badge-corner"><AZheng></div>
        </span>-->
        </a>
        <!--E 我的购物车-->

    <?php } else {?>
            <dl class="qz-fl" >
           <a href="<?php echo urlShopWap('login', 'index'); ?>'" nctype="buynow_submit" class="buynow
             <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
             no-buynow
             <?php }?>"
             title="<?php echo $output['goods']['buynow_text']; ?>">
             <input type="button" value="<?php echo $output['goods']['buynow_text']; ?>" class="ui-btn-lg ui-btn-primary" />
             </a>
          </dl>

        <!--E 立即购买-->

        <!--S 加入购物车-->

        <?php if (false and $output['goods']['cart'] == true) {?>
            <dl class="qz-fr" id="" >
             <a href="<?php echo urlShopWap('login', 'index'); ?>'" nctype="addcart_submit"
             class="addcart <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?> no-addcart
             <?php }?>"
             title="添加购物车">
             <i class="icon-shopping-cart"></i>
               <input type="submit" value="添加购物车" class="ui-btn-lg ui-btn-primary" />
             </a>
            </dl>
        <?php }?>
        <!--E 加入购物车-->
        <!--S 我的购物车-->

        </div>

    <?php }?>
    </div>

</footer>

<input type="hidden" id="is_commond"/>

<!-- ------弹窗HOME------ -->

<!-- ------弹窗END------ -->
<script>

    var putong_man_price,
        putong_child_price;
$(function(){
    $('body').css({
        'background':'#f4f4f4'
    })


    //商品详情页面 选择商品净含量 js
    /*$(".goods_details .buy_choice dd:not('.not_selected')").on("click",function(){
        $(this).addClass("has_selected").siblings("dd").removeClass("has_selected");
        })*/
    //商品详情页面 购买数量 js
    var upper_limit='<?php echo $output['goods']['upper_limit']; ?>';
    if(!isNaN(upper_limit)) {
        upper_limit = parseInt($('.mykucun span').text());
    }
    <?php if($output['goods']['calendar_type'] == 2){?>
       //酒店
       price_old  = <?php echo intval($output['goods']['goods_price']); ?>;
    <?php }else{?>
       //打折票务
       price_old  = parseInt($("#goods_price").text());
    <?php }?>
    $(".goods_details .buy_choice .choice_num .min").on("click",function(){
        if(!$(this).hasClass("disable")&&parseInt($(this).siblings(".num").val())>1){
            $('#ss_price').text(price_old * ($('.buy_quantity .num').val() - 1) );
            $(this).siblings("input").val(parseInt($(this).siblings("input").val())-1);
            $('#stock_msg').text('');


            }
        })
    $(".goods_details .buy_choice .choice_num .plus").on("click",function(){
        if($('#quantity').val()>= upper_limit){
            $('#stock_msg').text('购买数量不能超过'+upper_limit+'件');
            return false;
        }else{
            $('#stock_msg').text('');
        }
        //---
        if($('#quantity').val()< parseInt($('.mykucun span').text())){
            $(this).siblings("input").val(parseInt($(this).siblings("input").val())+1);
            $('#stock_msg').text('');
            $('#ss_price').text(price_old * $('.buy_quantity .num').val());
        }else{
            $('#stock_msg').text('库存不足');return false;
        }
    });

    $(".goods_details,.buy_choice,.choice_num input").keyup(function(){
        if($(this).val()==''){
            $(this).val("1");
        }

        if( parseInt($(this).val())>=upper_limit){
            $('#stock_msg').text('购买数量不能超过'+upper_limit+'件');
            $(this).val(upper_limit);
            return false;
        }else{
            $('#stock_msg').text('');
        }
        //---
        if($('#quantity').val()> parseInt($('.mykucun span').text())){
            $('#stock_msg').text('库存不足');
            return false;
        }else{
            $('#stock_msg').text('');
        }
        //---------

    });

    //商品详情 商品属性选项卡
    $(".goods_details .goods_type>ul li").on("click",function(){
        var index=$(this).index()+1;
        $(this).addClass("sel").siblings("li").removeClass("sel");
        $(".goods_details .goods_type .goods_type_box"+index).show().siblings(".goods_type_box").hide();
        })

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
    })
//如果图片大小不一，固定图片大小
window.onload=function(){
    $(".goods_details .product_recommendation ul li a img").each(function(){
        var w=$(this).width();
        $(this).height(w);
        })
    }
</script>
<script language="javascript">
//立即购买处理
    <?php if (($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0) or ($_SESSION['share_shop'] == 1 and $output['goods']['isshare'] == 1)) {?>
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        $('#az').click(function(){

            buynow(<?php echo $output['goods']['goods_id'] ?>,checkQuantity());
        });
        <?php }?>
    <?php }?>

function isbuy(goods_id){
    $.ajax({
        url: "index.php?act=goods&op=isbuy&goods_id="+goods_id,
        type: 'GET',
        async: false,
        dataType: 'json',
        success:function(data){
            if(data.is_buy){
                $('#az_msg').val(data.msg);
                alertPopWin(data.msg,'close');
                return false;
            }
            }
       });
}

//登录判断级跳转函数
function buynow(goods_id,quantity){
    is_buy=goods_id;
    if(quantity > parseInt($('.mykucun span').text())){
        alertPopWin('库存不足','close');return false;
    }
    //----------------
<?php if ($_SESSION['is_login'] !== '1') {?>
    login_dialog();
<?php } else {?>

    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) {?>
//    alertPopWin('不能购买自己店铺的商品','close');return;
    <?php }?>
    //此处限制每个商品只能购买一次
   // if(is_buy){
        //isbuy(goods_id);
    //  if($('#az_msg').val() !='') return;
    //}
    $("#cart_id").val(goods_id+'|'+quantity);
    //验证是否选择产品类型
    var check_stat=check_choose();
    if($('#vald_msg').val()==''){
            check_stat && $("#buynow_form").submit();
        $("#buynow_form").reset()
    }
<?php }?>

}
//验证是否选择产品类型
function check_choose(){
    $('#vald_msg').val('');
    //-----------验证是否选择HOME------------
    var type = '<?php echo $output["goods"]["type_id"] ?>';
    var vald_msg='';
    var spec_value='';
    <?php
//如果商品有规格
if (!empty($output['goods']['spec_name'])) {
    ?>
        $('.goods_types section').each(function(){
            var title=$(this).find('dt').attr('title');
            $(this).find('i').text('');
            console.log(title);
            if(!$(this).find('dd').hasClass('has_selected')){
                $(this).find('i').text('请选择'+title);
                vald_msg+=' '+title;return false
                //alert('请选择'+title);return false;
            }
            spec_value+=' '+$(this).find('.has_selected a').html();
            $('#tb_spec_value').val(spec_value);
            //---------------
        });

        $('#vald_msg').val(vald_msg);
        if(vald_msg!=''){
            alertPopWin('请选择'+vald_msg,'close');return false;
        }
    <?php }?>
//价格日历传值
    <?php if ($output['goods']['calendar_type'] == 1) {?>
        var putong_date =  $('.putong_date').val();
        var putong_man_price =  $('.putong_man_input').val();
        var putong_child_price =  $('.putong_child_input').val();
        var putong_diff_price =  $('.putong_diff_price').val();//单房差
        var spec_name_input = $('#spec_name_input').val();//套餐

        if(putong_date =='' || putong_date.length !==10){
            $('#vald_msg').val('日期错误');
            alertPopWin('请选择出行时间','close');
            return false;
        }
        if(putong_man_price == '-1'&& putong_child_price=='-1' && putong_diff_price =='-1'){
            $('#vald_msg').val('参数错误');
            alertPopWin('请先选票的规格','close');
            return false;
        }
        if((putong_man_price && putong_man_price > 0) || (putong_child_price && putong_child_price > 0) || (putong_diff_price && putong_diff_price > 0) ){
            $('#calendar_date').val(putong_date+','+putong_man_price+','+putong_child_price+','+spec_name_input+','+putong_diff_price);
        }else {
            $('#vald_msg').val('参数错误');
            alertPopWin('请选票的规格','close');
            return false;
        }


    <?php } elseif ($output['goods']['calendar_type'] == 2) {?>
        var hotel_in_date       =  $('.input_hotel_in_date').val();//入住时间
        var hotel_out_date      =  $('.input_hotel_out_date').val();//离店时间
        var hotel_num           =  $('.input_hotel_num').val();//入住间数
        var hotel_price         =  $('.checked_input_price').val();//价格
        var checked_input_spec  =  $('.checked_input_name').val();//套餐
        if(hotel_price != ''){
            $('#calendar_date').val(hotel_in_date+','+hotel_out_date+','+hotel_num+','+hotel_price+','+checked_input_spec);
        }else{
            $('#vald_msg').val('参数错误');
            alertPopWin("请选择入住时间",'close');return false;
        }
    <?php } elseif ($output['goods']['calendar_type'] == 3) {?>
        var picktime =  $('#picktime').val();
        var calendar_hour =  $('#calendar_hour_input').val();
        var calendar_min =  $('#calendar_min_input').val();

        if(picktime == '' || picktime.length !==10){
             $('#vald_msg').val('参数错误');
            alertPopWin("请选择打球日期",'close');return false;
        }
        if(calendar_hour == ''){
            $('#vald_msg').val('参数错误');
            alertPopWin("请选择打球小时段",'close');return false;
        }
        if(calendar_min =='' ){
            $('#vald_msg').val('参数错误');
            alertPopWin("请选择打球分钟段",'close');return false;
        }
    $('#calendar_date').val(picktime+' '+calendar_hour+':'+calendar_min);
    <?php } elseif ($output['goods']['calendar_type'] == 4) {?>
        var date = $('.pw_start_date').text();
        if(date==''){
            alertPopWin("请选择发车时间",'close');return false;
        }
    <?php } else {?>
        $('#calendar_date').val("");
    <?php }?>

    //------------验证EDN-----------
    return true;
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());

    if (quantity < 1) {
        alertPopWin("<?php echo $lang['goods_index_pleaseaddnum']; ?>",'close');
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[nctype="goods_stock"]').text());
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit']; ?>;
    if(quantity > max){
        alertPopWin('最多限购'+max+'件','close');
        $("#quantity").val(max);
//        return max;
        return false;
    }
    <?php }?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
    max = <?php echo $output['goods']['upper_limit']; ?>;
    if(quantity > max){
        alertPopWin('最多限购'+max+'件','close');
        $("#quantity").val(max);
//        return max;
        return false;
    }
    <?php }?>
    if(quantity > max){
        alertPopWin("<?php echo $lang['goods_index_add_too_much']; ?>",'close');
        return false;
    }
    return quantity;
}

//购物车操作
$('#cart-add').click(function(){
    check_choose();
    if($('#vald_msg').val()!='')return false;
    var goods_id='';
    if($('#tb_goods_id').val()==''){
        goods_id='<?php echo $output['goods']['goods_id'] ?>';
    }else{
        goods_id=$('#tb_goods_id').val();
    }
    var url = 'index.php?act=cart&op=add';
    quantity = parseInt(checkQuantity());
    var data = {'goods_id':goods_id, 'quantity':quantity};
    if ('<?php echo $output['goods']['is_virtual'] ?>' == 1) {
      data.date = $('.putong_date').val();
      data.calendar = $('#calendar_date').val();
   }
    $.getJSON(url, data, function(data) {
        if (data != null) {
            if (data.state) {
                //alert( data.state);
               resetCart();
            } else {
                alertPopWin(data.msg,'close');
            }
        }
    });
});
resetCart();
//刷新购物车数量
function resetCart(){

    var num = parseInt($('AZheng').text());

     $.getJSON('index.php?act=cart&op=ajax_load&callback=?', function(result){

                if(result){
                      if(result.cart_goods_num >0){
                          if( result.cart_goods_num !== num){
                            $('AZheng').empty();
                            $('AZheng').append(result.cart_goods_num);
                           }
                         } else {
                          $('AZheng').empty();
                          $('AZheng').append(0);
                         }
                    }
                });
}


    $('.grid').masonry({
        itemSelector: '.grid-item'
    });

    $(".top-btn").click(function(){
        $('body,html').animate({scrollTop:0},500);
        return false;
    });

    /*$(".qz-rt .qz-ico-allowl").click(function(){
        if ($(this).hasClass("qz-ico-allowr")) { //展开收藏
            $(this).removeClass("qz-ico-allowr");
            $(this).parent().animate({width:"40px"});
            $(this).parent().parent().find("p").css("width","86%");
        } else {//收缩收藏
            $(this).addClass("qz-ico-allowr");
            $(this).parent().animate({width:"80px"});
            $(this).parent().parent().find("p").css("width","76%");
        }
    });

    $(".qz-rt .qz-ico-collection").click(function(){
        if ($(this).hasClass("qz-ico-collection-hov")) {//取消收藏
            $(this).removeClass("qz-ico-collection-hov");
        } else {//收藏
            $(this).addClass("qz-ico-collection-hov");
        }
    });*/
    $.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id']; ?>});

$(document).ready(function(){
   $('.raty').raty({
        path: "<?php echo RESOURCE_SITE_URL; ?>/js/jquery.raty/img",
        readOnly: true,
        score: function() {
          return $(this).attr('data-score');
        }
    });

   $('a[nctype="nyroModal"]').nyroModal();
    //输出评论
    $('#goods_commond').click(function(){
        $('#is_commond').val('1');
        $('.commodity_evaluation').load('/wap_shop/index.php?act=goods&op=get_goods_commond&goods_id=<?php echo $_GET["goods_id"] ?>',function(data){
                if(data=='暂无评论'){
                    $('.see_more_pj').hide();
                }
            });
    });
    //$('body').append('<div class="height:300px"></div>');
    //收藏



    <?php if (!empty($output['favorites_info'])): ?>
        $("#shouchang").css("background-image","url(<?php echo SHOP_TEMPLATES_URL; ?>/images/gt_sced.png)");
    <?php endif;?>
     var goods_id ='<?php echo $_GET["goods_id"] ?>';
     var member_fav_src='<?php echo SHOP_TEMPLATES_URL; ?>/images/goods_sel_03.png';//已收藏
     $("#shouchang").click(function (){
         var key = '<?php echo $_SESSION["member_id"] ?>';//登录标记
         if(key==''){
           window.location.href = '?act=login';
         }else {
            $.getJSON('?act=member_favorites&op=favoritesgoods&fid='+goods_id,function(data){
                if((data.msg)=='收藏成功'){
                    $("#shouchang").css("background-image","url(<?php echo SHOP_TEMPLATES_URL; ?>/images/gt_sced.png)");
                }else if(data.status==2){//取消收藏成功
                    $("#shouchang").css("background-image","url(<?php echo SHOP_TEMPLATES_URL; ?>/images/gt_icon_start.jpg)");
                }
                   layer.msg(data.msg);
            });
         }
     });
     //规格选择HOME

     $('section dd').click(function(){
        var vald_msg='';
        var spec_value='';
        $(this).addClass("has_selected").siblings("dd").removeClass("has_selected");

         $('.goods_types section').each(function(){
            var title=$(this).find('dt').attr('title');
            spec_value+=' '+$(this).find('.has_selected a').html();
            $(this).find('i').text('');

            if(!$(this).find('dd').hasClass('has_selected')){
                //$(this).find('i').text('请选择'+title);
                vald_msg+=title;
            }
        });
        $('#tb_spec_value').val(spec_value);

        if(vald_msg==''){
            var commonid="<?php echo $output['goods']['goods_commonid'] ?>";
            var goods_name="<?php echo $output['goods']['goods_name'] ?>"+spec_value;
            $('#tb_goods_name').val(goods_name);
            var get_url='/wap_shop/?act=goods&op=get_goods_price&commonid='+commonid+'&goods_name='+goods_name;
            $.getJSON(get_url,function(data){
                $('#market_price').text(data.goods_marketprice);
                $('#goods_price').text(data.goods_price);
                <?php if ($output['goods']['calendar_type'] != 1) {?>
                    $('#ss_price').text(data.goods_price);
                <?php }?>
                $('#tb_goods_id').val(data.goods_id);
                var discount = (data.goods_price/data.goods_marketprice)*10;//折扣显示，小于0.1折的按照0.1折来算
                var rebate = discount.toFixed(1)>0 ? discount.toFixed(1):0.1;
                $(".tagging span").text(rebate);
            });
        }
         if(<?php echo $output['goods']['calendar_type'] == 1 ? 1 : 0 ?>){ //普通门票旅游
             //价格日历规格
             var package =$(".goods_types section:eq(0)").find(".has_selected a").html();
             $("#spec_name_input").val(package);
             var spec_name = $(".goods_types section:eq(0)").find(".has_selected a").html();
             var spec_name_first_input =$("#spec_name_input").val();
             var putong_date =   $(".putong_date").val();
             var commonid="<?php echo $output['goods']['goods_commonid'] ?>";
             if(putong_date !='' && putong_date.length ==10){ //更换套餐选项
                 var spec_url='/wap_shop/?act=goods_stock_price&op=get_goods_price&commonid='+commonid+'&package_name='+spec_name+'&date='+putong_date;
                 $.getJSON(spec_url,function(data){
                    if(data.done ==false){
                        alert("此类型暂无商品，请重新选择！");
                        $(".goods_types section:eq(0)").find(".has_selected").css("display","none");
                        return;
                    }

                     if(!data.man_price){
                         $(".putong_man_input").parent('.bill_btns').css('display','none');
                     }else{
                         $(".putong_man_input").parent('.bill_btns').css('display','block');
                     }
                     if(!data.child_price){
                         $(".putong_child_input").parent('.bill_btns').css('display','none');
                     }else{
                         $(".putong_child_input").parent('.bill_btns').css('display','block');
                     }
                     if(!data.diff_price){
                         $(".putong_diff_price").parent('.bill_btns').css('display','none');
                     }else{
                         $(".putong_diff_price").parent('.bill_btns').css('display','block');
                     }

                     $('#putong_putong_diff_price').text(data.diff_price);//儿童票价格
                     $('#putong_child_price').text(data.child_price);//儿童票价格
                     $('#putong_man_price').text(data.man_price);//成人票价格
                     for(var i=0;i<$('#hotel_ceshi .bill_btns').length;i++) {
                         $('#hotel_ceshi .bill_btns').eq(i).removeClass('on').find("input[type='hidden']").val('');
                         $('#ss_price').text('0');
                     }
                 })
             }
         }

     });
     //规格选择end

     //--------------
     //分享指引
     $("#fengxiang").on("click",function(){
        $(".share-id").show(100);
         $("body").eq(0).css("overflow","hidden")
         $('body,html').animate({scrollTop:0},0);
         return false;
    });
    $('.share-id span').click(function(){
        $('.share-id').hide(100);
        $("body").eq(0).css("overflow","auto")
        return true;
    });
    //---------------
    $('#footer_html').hide();
    //是否包邮
    var goods_freight='<?php echo intval($output["goods"]["goods_freight"]) ?>';
    if(goods_freight>0){
        $('#baoyou').hide();
        $('.goods_share a').width('45%');
    }
});

</script>
<!-- 分享指引 -->
<div class = "share-id">
    <span style="float:right;color:#fff; display:inline-block;width:40px;height:40px;margin:10px 10px 0 0">关闭</span>
    <img src = "<?php echo SHOP_TEMPLATES_URL; ?>/images/share-to-img.png"/>
</div>
<?php if (!empty($output['is_share'])) {
    ?>
<style type="text/css">
#mcover {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: none;
    z-index: 20000;
}
#mcover img#weixinshare {
    position: fixed;
    right: 40px;
    top: 5px;
    width: 80%;
    height: auto;
    z-index: 20001;
}
#mcover #putongshare {
    display: none;
}
.choose_bill{
    line-height:28px;
}
</style>


<div id="mcover" style="<?php
if (!empty($output['show_spread'])) {
        ?>display: block;<?php
} else {
        ?>display:none;<?php
}?>">
    <span style="float:right;color:#fff; display:inline-block;width:40px;height:40px;margin:10px 0 0 0">关闭</span>
    <img src="<?php echo SHOP_TEMPLATES_URL; ?>/images/share-to-img.png" id="weixinshare" style="display:none;" onload="document.getElementById('weixinshare').style.display='block'">
    <!-- JiaThis Button BEGIN -->
    <div class="jiathis_style_m" id="putongshare"></div>
    <!-- <script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_m.js" charset="utf-8"></script> -->
    <!-- JiaThis Button END -->
</div>
<?php if (!empty($output['signPackage'])) {?>
<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
$(function(){
    $("#mcover").click(function(){
        $("#mcover").css('display', 'none');
    });
});



function isWeiXin(){
    var ua = window.navigator.userAgent.toLowerCase();
    if(ua.match(/MicroMessenger/i) == 'micromessenger'){
        return true;
    }else{
        return false;
    }
}
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $output['signPackage']['appId']; ?>', // 必填，公众号的唯一标识
    timestamp: <?php echo $output['signPackage']['timestamp']; ?>, // 必填，生成签名的时间戳
    nonceStr: '<?php echo $output['signPackage']['nonceStr']; ?>', // 必填，生成签名的随机串
    signature: '<?php echo $output['signPackage']['signature']; ?>',// 必填，签名，见附录1
    jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'onMenuShareQZone'
    ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
wx.ready(function(){
    wx.onMenuShareTimeline({
        title: '<?php echo $output["goods"]["goods_name"]; ?>', // 分享标题
        link: '<?php echo $output['signPackage']['url']; ?>', // 分享链接
        imgUrl: '<?php echo cthumb($output["goods"]["goods_image"][0], 240); ?>', // 分享图标
        success: function () {
            // 用户确认分享后执行的回调函数
            top.window.location='<?php echo SPREAD_SITE_URL; ?>';
            //history.back(-1);
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    });
    wx.onMenuShareAppMessage({
        title: '<?php echo $output["goods"]["goods_name"]; ?>', // 分享标题
        desc: '<?php echo $output["goods"]["goods_name"]; ?>', // 分享描述
        link: '<?php echo $output['signPackage']['url']; ?>', // 分享链接
        imgUrl: '<?php echo cthumb($output["goods"]["goods_image"][0], 240); ?>', // 分享图标
        type: 'link', // 分享类型,music、video或link，不填默认为link
        dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
        success: function () {
            // 用户确认分享后执行的回调函数
            top.window.location='<?php echo SPREAD_SITE_URL; ?>';
            //history.back(-1);
        },
        cancel: function () {
            // 用户取消分享后执行的回调函数
        }
    });
});
</script>
<?php }?>
<?php }?>
<style>
.bill_btns{
    border: 1px #ccc solid;
    line-height: 28px;
    float: left;
    display: inline-block;
    padding: 2px 10px;
    color: #666;
    border-radius: 3px;
    clear: both;
    margin-top: 9px;
    display: none;
}
.bill_btns.on{
    border: 1px #3b84ed solid;
    line-height: 28px;
    float: left;
    display: inline-block;
    padding: 2px 10px;
    color: #666;
    border-radius: 3px;
    margin-top: 13px;
    color: red;
}
.bill_btns.no{
    display: none;
    line-height: 28px;
    float: left;
    display: inline-block;
    padding: 2px 10px;
    color: #666;
    border-radius: 3px;
    margin-top: 13px;
    background-color: gainsboro;
    pointer-events: none;
}
.font_css{
    font-size: 16px;
    line-height: 40px;
    height: 34px;
}
.talk_server_wrap{
    height:100%;
    width:100%;
    position:fixed;
    top:0;
    left:100%;
    background:#f4f4f4;
    z-index:999;
}
.talk_send_box .text_ipt {
    margin:0;
    padding:0
}
</style>
<!-- 客服聊天弹窗 -->
<div class="talk_server_wrap">
</div>
<script>
function loadHtml(){
    <?php
if ($_SESSION['share_shop'] == 1 and $output['goods']['isshare']) {
    $price = $output['goods']['share_price'];
} elseif (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {
    $price = $output['goods']['promotion_price'];
} else {
    $price = $output['goods']['goods_price'];
}
$data = array(
    'url'         => urlencode($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']),
    'goods_name'  => $output['goods']['goods_name'],
    'price'       => $price,
    'org_price'   => $output['goods']['goods_marketprice'],
    'goods_img'   => $imglist_new[0],
    'member_id'   => $output['member_info']['member_id'],
    'kefu_id'     => $output['goods']['kefu_id'],
    'member_name' => $output['member_info']['member_name'],
);
?>
    var member_id = <?php echo intval($output['member_info']['member_id']); ?>;
    if(member_id != 0){
        window.location.href="/wap_shop/tmpl/member/chat_info.html?goods_id=<?php echo $output['goods']['goods_id']; ?>&t_id=<?php echo $data['kefu_id']; ?>";
    }else{
        layer.msg('请先登录!');
    }
}
    $(function(){
        $('.no-buynow').click(function(){
            alertPopWin('库存不足','close');
        })
    })
</script>

<?php defined('InShopNC') or exit('Access Invalid!');?>
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
<title>抢购详情</title>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/style.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL; ?>/css/goods.css" />
<link rel="stylesheet" type="text/css" href="/wap_shop/templates/default/css/open_popup.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL."/js/layer/layer.js"?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL; ?>/js/main.js"></script> <!--替换alert -->
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
</head>
<body class="bg_gray">
<header class="product_detail_hd">
    <div class="arrow" onclick="location.href=history.back()"></div>
    <h1>抢购详情</h1>

</header>


<!--商品详情页面-->
<div class="goods_details potions_details">
   <div id="qz-picScroll" class="qz-picScroll">
	<!--商品图-->
     <div class="goods_pic"><img src="<?php echo XianShiThumb($output['groupbuy_info']['goods_image'],'360');?>" alt=""></div>
    <!--商品描述及分享-->
    <div class="goods_cons">
        <div class="goods_title"><?php echo $output['groupbuy_info']['goods_name'];?></div>

        <div class="goods_price">
        	<div class="price"><span>&yen;</span><em><?php echo $output['groupbuy_info']['xianshi_price'];?></em>&nbsp;&nbsp;&nbsp;&nbsp;

         <!-- calendar -->
         <?php require_once BASE_PATH . DS . "/templates/default/store/calendar_2.php"?>
         <!-- ./clendar -->
			<a href="javascript:void(0);" id="az" class="buynow" nctype="buynow_submit" href="javascript:;">
                <?php echo $output['groupbuy_info']['button_text'];?>
			</a>
			</div>
        </div>

        <div class="discount">
        	<div>
            	<span>原价</span>
            	<span><?php echo $lang['currency'];?><?php echo $output['groupbuy_info']['goods_price'];?></span>
            </div>
        	<div>
            	<span>折扣</span>
            	<span><?php echo $output['groupbuy_info']['xianshi_discount'];?></span>
            </div>
        	<div>
            	<span>节省</span>
            	<span><?php echo $lang['currency'];?><?php echo sprintf("%01.2f",$output['groupbuy_info']['goods_price']-$output['groupbuy_info']['xianshi_price']);?></span>
            </div>
        </div>

        <div class="purchase">每人限购 <span><em><?php echo $output['groupbuy_info']['lower_limit']; ?></em></span> 件，数量有限，欲购从速！</div>

        <div class="surplus_time">
              <?php if(!empty($output['groupbuy_info']['count_down'])) { ?>
              <!-- 倒计时 距离本期结束 -->
              <i class="icon-time"></i>剩余时间：<span id="d1">0</span><strong><?php echo $lang['text_tian'];?></strong><span id="h1">0</span><strong><?php echo $lang['text_hour'];?></strong><span id="m1">0</span><strong><?php echo $lang['text_minute'];?></strong><span id="s1">0</span><strong><?php echo $lang['text_second'];?></strong>
              <script type="text/javascript">
                    var tms = [];
                    var day = [];
                    var hour = [];
                    var minute = [];
                    var second = [];

                    tms[tms.length] = "<?php echo $output['groupbuy_info']['count_down'];?>";
                    day[day.length] = "d1";
                    hour[hour.length] = "h1";
                    minute[minute.length] = "m1";
                    second[second.length] = "s1";
                    function groupbuyTakeCount() {
                        for (var i = 0, j = tms.length; i < j; i++) {
                            tms[i] -= 1;
                            //计算天、时、分、秒、
                            var days = Math.floor(tms[i] / (1 * 60 * 60 * 24));
                            var hours = Math.floor(tms[i] / (1 * 60 * 60)) % 24;
                            var minutes = Math.floor(tms[i] / (1 * 60)) % 60;
                            var seconds = Math.floor(tms[i] / 1) % 60;
                            if (days < 0)
                                days = 0;
                            if (hours < 0)
                                hours = 0;
                            if (minutes < 0)
                                minutes = 0;
                            if (seconds < 0)
                                seconds = 0;
                            //将天、时、分、秒插入到html中
                            document.getElementById(day[i]).innerHTML = days;
                            document.getElementById(hour[i]).innerHTML = hours;
                            document.getElementById(minute[i]).innerHTML = minutes;
                            document.getElementById(second[i]).innerHTML = seconds;
                        }
                    }
                    setInterval(groupbuyTakeCount, 1000);
              </script>
              <?php } ?>
            </div>
            <!-- goods detail -->
            <div class="goods_details">
               <div class="bd">
                  <ul><li style="text-align: center; padding: 10px 15px; background: #3b84ed; margin: 10px 0; color: #ffffff;">图文描述</li></ul>
                  <ul>
                     <?php echo $output['goods']['mobile_body'] ?$output['goods']['mobile_body']: $output['goods']['goods_body']; ?>
                  </ul>
               </div>
            </div>
            <!-- ./goods detail -->
    </div>
</div>
<form id="buynow_form" method="post" action="<?php echo WAP_SHOP_SITE_URL; ?>/index.php">
           <input id="act" name="act" type="hidden" value="buy" />
           <input id="op" name="op" type="hidden" value="buy_step1" />
           <input name="calendar_type" type="hidden" value="<?php echo $output['goods']['calendar_type']; ?>"/>
            <input id="cart_id" name="cart_id[]" type="hidden" value="<?php echo $output["goods"]["goods_id"]."|1"?>"/>
           <input id="tb_commonid" name="tb_commonid" value='<?php echo $output["goods"]["goods_commonid"] ?>' type="hidden"/>
            <input id="tb_package" name="tb_package" type="hidden" />
            <input id="tb_spec_value" name="tb_spec_value" type="hidden" />
            <input id="tb_goods_name" name="tb_goods_name" type="hidden" />
            <input id="tb_goods_id" name="tb_goods_id" type="hidden" />
            <input id="goods_id" name="goods_id" type="hidden" value="<?php echo $output["goods"]["goods_id"]?>"/>
            <input id="vald_msg" type="hidden" />
            <input id="get_date" name="date" type="hidden" value="<?php echo $output['goods']['get_date']; ?>"/>
            <input id="is_share" type="hidden" name="is_share"  value="<?php echo $output['goods']['isshare']; ?>"/><!-- 分销商品-->
            <input id="tb_type_id" name="tb_type_id" value='<?php echo $output["goods"]["type_id"] ?>' type="hidden" />
            <input id="calendar_date" name="calendar_date" value='calendar_date[]' type="hidden" /><!--价格类型商品属性 -->
            <input  name="store_id"  value='<?php echo $output['goods']['store_id']; ?>' type="hidden" /><!--店铺ID-->
            <input  name="is_cx"  value='is_cx' type="hidden" /><!--店铺ID-->
  </form>
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

<script>
$(function(){
   var max_buy = <?php echo intval($output['groupbuy_info']['lower_limit']); ?>;
	//商品详情页面 选择商品净含量 js
	$(".goods_details .buy_choice dd:not('.not_selected')").on("click",function(){
		$(this).addClass("has_selected").siblings("dd").removeClass("has_selected");
		})
	//商品详情页面 购买数量 js
	$(".goods_details .buy_choice .choice_num .min").on("click",function(){
		changeBuyNum($(this).siblings('input'), $(this).siblings('input').val() - 1);
	})
	$(".goods_details .buy_choice .choice_num .plus").on("click",function(){
      changeBuyNum($(this).siblings('input'), $(this).siblings('input').val() + 1);
	});
	$(".goods_details .buy_choice .choice_num input").keyup(function(){
      changeBuyNum($(this), $(this).val())
	});
   function changeBuyNum(obj, v){
      v = parseInt(v);
      if(v<=0){
         v = 1;
      } else if(v > max_buy){
         v = max_buy;
      }
      obj.val(v)
   }

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

	//收藏和取消收藏
	$(".hide-first2").hide();
	$(".hide-first").on("click",function(){
		$(".hide-first").hide();
		$(".hide-first2").show();
		});
	$(".hide-first2").on("click",function(){
		$(".hide-first2").hide();
		$(".hide-first").show();
		});
	<!-- 分享指引 -->
	$(".shared-btn").on("click",function(){
		$(".share-id").show();
		 $("body").eq(0).css("overflow","hidden")
		 $('body,html').animate({scrollTop:0},0);
         return false;
		});
	$(".hide-first").on("click",function(){
		$(".free-send-id").show();
		$(".goods_details .goods_cons .goods_share a:first").css('marginLeft','0%');
		});
	$(".hide-first2").on("click",function(){
		$(".free-send-id").hide();
		$(".goods_details .goods_cons .goods_share a:first").css('marginLeft','14%');
		});
	<!-- 判断是否显示官网包邮 -->
	$(".hide-first").on("click",function(){
		//显示

		//隐藏

		});
	})
//如果图片大小不一，固定图片大小
window.onload=function(){
	$(".goods_details .product_recommendation ul li a img").each(function(){
		var w=$(this).width();
		$(this).height(w);
		})
	};

$("#az").click(function(){
    var check_stat=check_choose();
    check_stat && $("#buynow_form").submit()
})


//验证是否选择产品类型
function check_choose(){
    $('#vald_msg').val('');
    //-----------验证是否选择HOME------------
    var type = '<?php echo $output["goods"]["type_id"] ?>';
    var vald_msg='';
    var spec_value='';
//价格日历传值
    <?php if ($output['goods']['calendar_type'] == 1) {?>
    var putong_date =  $('.putong_date').val();var spec_name_input = $('#spec_name_input').val();//套餐
    var quantity = $("#quantity").val();//数量

    if(putong_date =='' || putong_date.length !==10){
        $('#vald_msg').val('日期错误');
        alertPopWin('请选择出行时间','close');
        return false;
    }
    $('#calendar_date').val(putong_date+','+"xs_price"+','+quantity+','+spec_name_input);


    <?php } elseif ($output['goods']['calendar_type'] == 2) {?>
    var hotel_in_date       =  $('.input_hotel_in_date').val();//入住时间
    var hotel_out_date      =  $('.input_hotel_out_date').val();//离店时间
    var hotel_num           =  $('.input_hotel_num').val();//入住间数
    var hotel_price         =  "xs_price";//价格
    var checked_input_spec  =  '<?php echo $package = !empty($output['package']) ? $output['package'] : "" ;?>';//套餐
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
</script>


<style>
.goods_price a {
    display: block;
    height: 32px;
    line-height: 32px;
    text-align: center;
    color: #FFF;
    font-size: 18px;
    background: #2D1A47 none repeat scroll 0% 0%;
}
</style>

</body>
</html>

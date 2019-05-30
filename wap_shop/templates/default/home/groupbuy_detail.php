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
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">
<!--商品详情页面-->
<div class="goods_details potions_details">
	<!--商品图-->
     <div class="goods_pic"><img src="<?php echo gthumb($output['groupbuy_info']['groupbuy_image'],'max');?>" alt=""></div>
    <!--商品描述及分享-->
    <div class="goods_cons">
        <div class="goods_title"><?php echo $output['groupbuy_info']['groupbuy_name'];?></div>

        <div class="goods_price">
        	<div class="price"><span>&yen;</span><em><?php echo $output['groupbuy_info']['groupbuy_price'];?></em>&nbsp;&nbsp;&nbsp;&nbsp;

			<a href="<?php 
			$table_change = array('shop'=>'wap_shop');	
	        echo strtr($output['groupbuy_info']['goods_url'],$table_change);?>" target="_blank"><?php echo $output['groupbuy_info']['button_text'];?>
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
            	<span><?php echo $output['groupbuy_info']['groupbuy_rebate'];?><?php echo $lang['text_zhe'];?></span>
            </div>
        	<div>
            	<span>节省</span>
            	<span><?php echo $lang['currency'];?><?php echo sprintf("%01.2f",$output['groupbuy_info']['goods_price']-$output['groupbuy_info']['groupbuy_price']);?></span>
            </div>
        </div> 

        <div class="has_potions">本商品已被抢购 <span><em><?php echo $output['groupbuy_info']['virtual_quantity']+$output['groupbuy_info']['buy_quantity']; ?></em></span> 件</div>
        <div class="purchase">每人最多购买 <span><em><?php echo $output['buy_limit']; ?></em></span> 件，数量有限，欲购从速！</div>

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
    </div>

<script>
$(function(){
	//商品详情页面 选择商品净含量 js
	$(".goods_details .buy_choice dd:not('.not_selected')").on("click",function(){
		$(this).addClass("has_selected").siblings("dd").removeClass("has_selected");
		})
	//商品详情页面 购买数量 js
	$(".goods_details .buy_choice .choice_num .min").on("click",function(){
		if(!$(this).hasClass("disable")&&parseInt($(this).siblings(".num").val())>1){
			$(this).siblings("input").val(parseInt($(this).siblings("input").val())-1);
			}
		})
	$(".goods_details .buy_choice .choice_num .plus").on("click",function(){
		$(this).siblings("input").val(parseInt($(this).siblings("input").val())+1);
		})
	$(".goods_details .buy_choice .choice_num input").keyup(function(){
		if($(this).val()==''){
			$(this).val("1");
			}
		})
		
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


<?php defined('InShopNC') or exit('Access Invalid!');?>

<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>魔药系列</title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">

<!--魔药系列-->
<div class="potions_list">
	<!--搜索框-->
    <form method="post">
        <div class="search_wrap">
            <div class="search">
                <a href="javascript:;" class="search_btn"></a>
                <input type="search" name="keyword" placeholder="会员独享套装" />
                <a href="javascript:;" class="empty"></a>
            </div>
        </div>
    </form>
    <!--魔药图片展示-->
	<div class="potions_show_pic"><img src="<?php echo SHOP_TEMPLATES_URL;?>/images/apply_02.jpg" /></div>
    <!--魔药列表-->
    <div class="order_states_list">
        <ul>
        <?php if(is_array($output['goods_list']) && count($output['goods_list']) >0){?>
        <?php foreach($output['goods_list'] as $value){?>
            <li>
                <div class="middle_wrap">
                    <div class="middle">
                        <a href="<?php echo urlShopWAP('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" >
                            <div class="order_goods">
                               <div class="goods_pic"><div class="self_width"><img class="goods_pic" src="<?php echo thumb($value, 80);?>" /></div></div>
                                <div class="goods_see"></div>
                                <div class="goods_dis">
                                    <div class="title"><?php echo $value['goods_name']?></div>
                                    <div class="goods_fun"><?php echo $value['goods_jingle']?></div>
                                    <div class="goods_price">
                                        <div class="present_price"><span>&yen;</span><?php echo $value['goods_price']?></div>
                                        <span class="original_price">&yen;<?php echo $value['goods_marketprice']?></span>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
               </div>
            </li>
           <?php }?>
           <?php }else {?>
           <li>
           <div class="middle_wrap">
                    <div class="order_goods">
                    <div class="az_null az_content">
                    没有找到相关数据！！
					</div>
                    </div>
           </div>
           </li>
           <?php }?>
        </ul>
        <?php if(count($output['goods_list'])>10){?>
        <div class="pullup">
            <div class="pullupcons">
                <!--//加载动画-->
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
            </div>
        </div>
    </div>
    <?php }?>
</div>

<!--公共底部导航-->
<div class="bottomHeight"></div>
<footer class="footer">
	<ul>
    	<li class="border_right"><a href="/wap" class="selected"><span class="sy"></span><br />首页</a></li>
    	<li class="border_left_right"><a href="/wap_shop/index.php?act=member&op=home"><span class="wd"></span><br />个人中心</a></li>
    	<li class="border_left_right"><a href="/wap_shop/index.php?act=cart"><span class="kf"></span><br />购物车</a></li>
    	<li class="border_left"><a href="/wap_shop/index.php?act=member_order"><span class="hd"></span><br />订单</a></li>
    </ul>
</footer>
<!--返回头部-->
<a href="javascript:;" class="back_to_top gf_back_to_top has_bottom"><img src="images/backtop.png" /></a>
<script>
$(function(){
	
	$(".search_btn").on("click", function() {
		url="/wap_shop/index.php?act=search&op=product_list&type=magic";
		$("form").attr("action", url); 
		$("form").submit();
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

		//下拉效果范例
	var t=3;//设置假的加载次数
	$(window).scroll(function(){
		   //滚动条到网页头部的 高度，兼容ie,ff,chrome
		   var top = document.documentElement.scrollTop + document.body.scrollTop;
		   var bottomH=$(".bottomHeight").height()-50;
		   //网页的高度
		  var textheight =$(document).height();
		  // 网页高度-top-当前窗口高度
		  var topHeight =$(window).height();
		  var lastHeight=textheight-top-topHeight;
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
</body>
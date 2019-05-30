<!DOCTYPE html>
<html lang="en">
<head>
    <title>一元去旅行</title>
    <meta charset="UTF-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,user-scalable=no"/>
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="一元去旅行，九休旅行"/>
    <meta name="author" content="九休旅行"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/reset.css">
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL?>/duobao/main.css">
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/TouchSlide.1.1.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/main.js"></script>
</head>
<body>
<!--banner-->
<div class="index-baner">
    <img class="banner-bg" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/baner.jpg" />
    <img class="icon baner-icon-map" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/banner-icon-map.png" alt="">
    <img class="icon baner-icon-clouds2" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/banner-icon-y2.png" alt="">
    <img class="icon baner-icon-yydb" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/yiydb.png" alt="">
    <img class="icon baner-icon-qq" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/banner-icon-qq.png" alt="">
    <img class="icon baner-icon-ceil" src="<?php echo SHOP_TEMPLATES_URL?>/duobao/banner-icon-cel.png" alt="">
</div>

<!--用户当前积分-->
<div class="user-integral">
    <div class="intergal-numb">
        <i class="fl">您当前有</i><span class="fl"><?php echo $output['mem_points'];?></span>
        <input type="button" class="btn" value="往期参与" onclick="viewOldRec();" />
    </div>
    <input class="btns" type="button" onclick="window.location.href='<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&op=viewdbRec'" value="查看参与记录" />
</div>
<!-- 1元区  5元区 -->
<div id="index-tab" class="index-tab-inner">
    <ul class="tab-tt">
        <li>一元专区</li>
        <li>五元专区</li>
    </ul>
    <div class="tab-bd tab-body">
        <div class="tab-bd-ceil">
            <!-- 1 -->
			<?php if(!empty($output['group1'])){?>
			<?php foreach($output['group1'] as $val){?>
				<?php if(intval($val['zongxu']) != 0 && intval($val['shengyu']) != 0){ ?>
					<div class="index-project">
						<div class="hd-img" style="background-image:url('<?php echo $val['goods_imageurl'];?>') " onclick="lijiduobao(<?php echo $val['groupbuy_id'];?>);"></div>
						<h2 class="pjt-tt"><?php echo ($val['groupbuy_name'].'（'.$val['goods_name'].'）');?></h2>
						<div class="bm">
							<div class="lt-speed">
								<div class="tt">总需 <span class="red"><?php echo $val['zongxu'];?></span>人次</div>
								<div class="speed">
									<p style="width:<?php echo ($val['yigou']*100/$val['zongxu']);?>%"></p>
								</div>
								<div class="des">
									<div class="lt"> <span class="red"><?php echo $val['yigou'];?></span> 已参与</div>
									<div class="rt">剩余 <span class="red"><?php echo $val['shengyu'];?></span></div>
								</div>
							</div>
							<input class="btn2" type="button" yid="<?php echo $val['gids'];?>" onclick="lijiduobao(<?php echo $val['groupbuy_id'];?>);" value="立即参与">
						</div>
					</div>
				<?php } ?>
			<?php }}?>
        </div>
        <div class="tab-bd-ceil">
			<!-- 5 -->
			<?php if(!empty($output['group5'])){?>
			<?php foreach($output['group5'] as $val){?>
			<?php if(intval($val['zongxu']) != 0 && intval($val['shengyu']) != 0){ ?>
            <div class="index-project">
                <div class="hd-img" style="background-image:url('<?php echo $val['goods_imageurl'];?>') " onclick="lijiduobao(<?php echo $val['groupbuy_id'];?>);"></div>
                <h2 class="pjt-tt"><?php echo ($val['groupbuy_name'].'（'.$val['goods_name'].'）');?></h2>
                <div class="bm">
                    <div class="lt-speed">
                        <div class="tt">总需 <span class="red"><?php echo $val['zongxu'];?></span>人次</div>
                        <div class="speed">
                            <p style="width:<?php echo ($val['yigou']*100/$val['zongxu']);?>%"></p>
                        </div>
                        <div class="des">
                            <div class="lt"> <span class="red"><?php echo $val['yigou'];?></span> 已参与</div>
                            <div class="rt">剩余 <span class="red"><?php echo $val['shengyu'];?></span></div>
                        </div>
                    </div>
                    <input class="btn2" type="button" yid="<?php echo $val['gids'];?>" onclick="lijiduobao(<?php echo $val['groupbuy_id'];?>);" value="立即参与">
                </div>
            </div>
			<?php } ?>
			<?php }}?>
		</div>
    </div>
</div>

<!--气球弹出规则玩法说明-->
<div class="buying-mark" onclick="hideShopping()"></div>
<div class="index-popwin"  id="pop">
    <img src="<?php echo SHOP_TEMPLATES_URL?>/duobao/popyy.png" alt="" class="icon">
    <div class="pop-msg">
        <div class="text-inner">
            参与规则：<br />
            1、参与条件：用户需要使用一元去旅行平台“参与积分”参与活动；<br />
            2、参与方法：用户挑选喜欢的商品，对应商品所需的参与积分，使用对应参与积分数量，即可以获得1组参与号码（系统随机分配）；<br />
            3、获得商品：当参与商品所有参与号码都被分配完毕后，系统根据系统规则计算出1个幸运号码，持有该号码的支持者，直接获得该商品。<br />
            4、参与积分：用户参与一元去旅行活动需要使用平台参与积分，当积分不足参与标准时则需要购买足量积分。积分兑换标准为0.1元=1参与积分<br />
            （如商品A：单次参与机会为10参与积分，用户剩余参与积分为5积分，则需要购买差额的5积分即可参与）<br />
            <br />
            参与号码分配规则：<br />
            中奖算法：把所有奖号放入奖号池中，取奖号池中随机的一个奖号作为中奖码！<br />
            <br />
            生成奖号算法:以从1970年1月1日（UTC/GMT的午夜）开始至用户购买号码的时间所经过的秒数加入一个随机值产生新的数字，然后通过此随机数字格式化生成一个八位的奖票号码。<br />
        </div>
    </div>
</div>

<!-- 二维码弹出  -->
<!-- <div class="follow-code">
    <h2>请扫描二维码，关注公众号</h2>
    <div class="pay-result">
        <div class="reuslt-code">
            <img src="testImg/test-code.jpg" alt="a&quot;&quot;">
        </div>
        <div class="code-help-tip">
            <p>输入关键词<br/><span>“参与记录”</span>查询中奖记录</p>
        </div>
    </div>
</div> -->
<style>
    .pop-msg .text-inner{position: relative;top: 0;left: 0; }
</style>
<script type="text/javascript">
$(function(){
    $(window).scroll(function(){
        var _tabTop=$('#index-tab').offset().top-$(window).scrollTop();
        if(_tabTop<0){
            if($('.tab-tt').hasClass('fixed')) return;
            $('.tab-tt').addClass('fixed');
        }else if(_tabTop>10){
            $('.tab-tt').removeClass('fixed');
        }
    });
    $('.tab-tt li').eq(0).addClass('on');
    $('.tab-bd .tab-bd-ceil').hide();
    $('.tab-bd .tab-bd-ceil').eq(0).show();
    $('.tab-tt li').click(function(){
        var _index=$(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        $('.tab-bd .tab-bd-ceil').hide();
        $('.tab-bd .tab-bd-ceil').eq(_index).show();
    })
    //banner
    bannerInit();

    //
    $('.baner-icon-qq').click(function(e){
        $('.buying-mark,.buying-information').fadeIn();
        $('.index-popwin').css({height:$(window).height()-80,width:$(window).width()-40}).fadeIn();
        $('.index-popwin .pop-msg').css({height:$(window).height()-180});

        var pop=document.getElementById('pop');
        var pageY=0;
        pop.ontouchstart=function(e){
            pageY= e.touches[0].pageY;
            e.preventDefault();
        };
        pop.ontouchmove=function(e){
            var this_pageY=pageY-e.touches[0].pageY;
            pageY=e.touches[0].pageY;

            var t=-(parseInt($('.pop-msg .text-inner').css('top')));
            var scrollH=$('.pop-msg .text-inner').height()-$('.pop-msg').height() +50;
            if(t>scrollH){
                $('.pop-msg div').css({top:-scrollH});
            }else if(t<0){
                $('.pop-msg div').css({top:0});
            }
            console.log(t);
            $('.pop-msg div').css({top:"-="+this_pageY});
            e.preventDefault();
        }
    })

});

function hideShopping(){
    $('.buying-mark,.buying-information').fadeOut();
    $('.index-popwin').fadeOut();
    $('.follow-code').fadeOut('fast');
}
function showCode(){
    $('.buying-mark').fadeIn();
    $('.follow-code').show(300);
}

// function  viewDbRec(){
// 	location.href="<?php echo BASE_SITE_URL;?>/yydb/index.php?act=index&op=viewDbRec";
// 	return;
// }
function  viewOldRec(){
	location.href="<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&op=viewOldRec";
	return;
}
function  lijiduobao(gid){
	location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=dbgoods&op=index&gr_id="+gid;
	return;
}
</script>
<?php require_once(BASE_TPL_PATH ."/layout/nav.php"); ?>
<!-- 引入分享 -->
<?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</body>
</html>

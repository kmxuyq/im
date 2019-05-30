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
</head>
<body>
<!-- 头部 -->
<div class="gray-inner">
<div class="header">
    <div class="arrow" onclick="javascript:history.back(-1);"></div>
    <div class="tt">参与记录</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>

<div id="db-tab" class="db-tab">
    <ul class="db-tab-tt">
        <li>即将揭晓</li>
        <li>已经揭晓</li>
        <li>正在开奖</li>
        <li>参与成功</li>
    </ul>
    <div class="db-tab-body">

        <div class="column">
            <?php if(!empty($output['g_jjjx'])){?>
			<?php foreach($output['g_jjjx'] as $k1=>$v1){?>
            <div class="cl-main">
                <div class="tp">
                    <div class="hd"><img src="<?php echo $v1['goods_imageurl'];?>" alt=""></div>
                    <div class="tp-rt">
                        <div class="tt">
                            <?php echo ($v1['groupbuy_name'].'（'.$v1['goods_name'].'）');?>
                        </div>
                        <p><?php echo ($v1['remark']);?></p>
                    </div>
                </div>
                <div class="bm">
                    <div class="lt-speed">
                        <div class="tt">总需 <span class="red"><?php echo $v1['total_quantity'];?></span>人次</div>
                        <div class="speed">
                            <p style="width:<?php echo (intval($v1['buy_quantity'])*100/intval($v1['total_quantity']));?>%"></p>
                        </div>
                        <div class="des">
                            <div class="lt"> <span class="red"><?php echo $v1['buy_quantity'];?></span> 已参与</div>
                            <div class="rt">剩余 <span class="red"><?php echo (intval($v1['total_quantity'])-intval($v1['buy_quantity']));?></span></div>
                        </div>
                    </div>
                    <input class="btn2" type="button" onclick="groupAdd(<?php echo $v1['groupbuy_id'];?>)" value="追加">
                </div>
            </div>
			<?php }?>
			<?php }else{?>
				<div class="member-wrap">
                <div class="yiyuan-icon2"></div>
                <div class="go-home-tip">
                    商品很快揭晓，您可以多购买次数加快进度又能增加中奖几率呦！<br />
                </div>
            </div>
            <input type="button" class="btn" onclick="another();" value="去购买">
			<?php }?>
        </div>

        <div class="column">
            <!-- 2 -->
			<?php if(!empty($output['g_yjkj'])){?>
			<?php foreach($output['g_yjkj'] as $k2=>$v2){?>
            <div class="cl-main">
                <div class="tp">
                    <div class="hd"><img src="<?php echo $v2['goods_imageurl'];?>" alt=""></div>
                    <div class="tp-rt">
                        <div class="tt">
                            <?php echo ($v2['groupbuy_name'].'（'.$v2['goods_name'].'）');?>
                        </div>
                        <p><?php echo ($v2['remark']);?><em class="read-numb" onclick="popWin('<?php echo $v2['g_codeStr'];?>');"> 查看号码 </em></p>
                        <div class="el">获得者:<?php echo ($v2['g_winBuyer']);?></div>
                        <div class="el">幸运号码: <span class="red"><?php echo ($v2['g_winBuyerCode']);?></span> </div>
                        <div class="el">揭晓时间:<?php echo date('Y-m-d H:i',($v2['gend_time']+1800));?></div>
                        <div class="el">本期参与:<?php echo ($v2['g_winBuyerTotal']);?>人次</div>
                    </div>
                </div>
                <input type="button" class="db-cl-btn" onclick="another();" value="再次参与">
            </div>
			<?php }?>
			<?php }else{?>
				<div class="member-wrap">
                <div class="yiyuan-icon2"></div>
                <div class="go-home-tip">暂无已揭晓的参与活动，请耐心等待...</div>
            </div>
			<?php }?>
        </div>

        <div class="column">
            <!-- 3 -->
			<?php if(!empty($output['g_zzkj'])){?>
			<?php foreach($output['g_zzkj'] as $k3=>$v3){?>
            <div class="cl-main">
                <div class="tp">
                    <div class="hd"><img src="<?php echo $v3['goods_imageurl'];?>" alt=""></div>
                    <div class="tp-rt">
                        <div class="tt">
                            <?php echo ($v3['groupbuy_name'].'（'.$v3['goods_name'].'）');?>
                        </div>
                        <p><?php echo ($v3['remark']);?><em class="read-numb" onclick="popWin('<?php echo $v3['g_codeStr'];?>');"> 查看号码 </em></p>
                        <div class="el"><span class="red">正在开奖</span> </div>
                        <div class="el">揭晓时间:<?php echo date('Y-m-d H:i',($v3['gend_time']+1800));?></div>
                        <div class="el">本期参与:<?php echo ($v3['g_winBuyerTotal']);?>人次</div>
                    </div>
                </div>
                <input type="button" class="db-cl-btn" onclick="groupAdd(<?php echo $v3['groupbuy_id'];?>);" value="查看详细">
            </div>
			<?php }?>
			<?php }else{?>
				<div class="member-wrap">
                <div class="yiyuan-icon2"></div>
                <div class="go-home-tip">
                    系统正在开奖很快揭晓<br />
                    再去其他商品逛逛吧【去逛逛】
                </div>
            </div>
			<?php }?>
        </div>

        <div class="column">
            <!-- 4 -->
			<?php if(!empty($output['g_dbcg'])){?>
			<?php foreach($output['g_dbcg'] as $k4=>$v4){?>
            <div class="cl-main">
                <div class="tp">
                    <div class="hd"><img src="<?php echo $v4['goods_imageurl'];?>" alt=""></div>
                    <div class="tp-rt">
                        <div class="tt">
                            <?php echo ($v4['groupbuy_name'].'（'.$v4['goods_name'].'）');?>
                        </div>
                        <p><?php echo ($v4['remark']);?></p>
                        <div class="el">获得者:<?php echo ($v4['g_winBuyer']);?></div>
                        <div class="el">幸运号码: <span class="red"><?php echo ($v4['g_winBuyerCode']);?></span> </div>
                        <div class="el">揭晓时间:<?php echo date('Y-m-d H:i',($v4['gend_time']+1800));?></div>
                        <div class="el">本期参与:<?php echo ($v4['g_winBuyerTotal']);?>人次</div>
                    </div>
                </div>
				<?php if($v4['isGetPrized']){?>
                <input type="button" class="db-cl-btn" value="已领奖">
				<?php }else{?>
				<input type="button" class="db-cl-btn" onclick="getPrize(<?php echo $v4['groupbuy_id'];?>);" value="领奖">
				<?php }?>
            </div>
			<?php }?>
			<?php }else{?>
				<!-- 没有记录显示 -->
				<div class="member-wrap">
					<div class="yiyuan-icon2"></div>
					<div class="go-home-tip">您还没有参与成功的记录哦!</div>
				</div>
				<input type="button" class="btn" onclick="another();" value="立即参与">
			<?php }?>
        </div>
    </div>
</div>
</div>

<!--弹窗-->
<div class="pop-mark"></div>
<div class="pop-win-text">
    <div class="close-btn" onclick="closePop()"></div>
    <div class="tt"></div>
    <div class="numb">
        <div class="numb-tt">以下是<?php echo $_SESSION['member_name'];?>的参与号码</div>
        <div class="numb-me">
			<label id="codeStr"></label>
        </div>
    </div>
</div>

<!---->
<div class="loading-tip">
    <img src="<?php echo SHOP_TEMPLATES_URL?>/duobao/loading2.gif">
    <div class="tips-text" style="color:#fff;">数据加载中,请稍后</div>
</div>

<script>
$(function(){
    $(window).scroll(function(){
        var _tabTop=$('#db-tab').offset().top-$(window).scrollTop();
        if(_tabTop<0){
            if($('.db-tab-tt').hasClass('fixed')) return;
            $('.db-tab-tt').addClass('fixed');
        }else if(_tabTop>10){
            $('.db-tab-tt').removeClass('fixed');
        }
    });
    $('.db-tab-tt li').eq(0).addClass('on');
    $('.db-tab-body .column').hide();
    $('.db-tab-body .column').eq(0).show();
    $('.db-tab-tt li').click(function(){
        var _index=$(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        $('.db-tab-body .column').hide();
        $('.db-tab-body .column').eq(_index).show();
    });
});

function popWin(s){

    $('#codeStr').html(s);
    $('.pop-mark').addClass('in');
    $('.loading-tip').fadeIn('fast',function(){
        setTimeout(function(){
            $('.pop-mark').addClass('in');
            $('.pop-win-text').css({marginTop:-$('.pop-win-text').height()/2});
            setTimeout(function(){
                $('.pop-win-text').addClass('in');
                $('.pop-win-text').css({marginTop:-$('.pop-win-text').height()/2});
            },500);
            $('.loading-tip').hide();
        },1000);
    })

    $('.pop-mark').click(function(){
        closePop();
    })
}
function closePop(){
    $('.pop-mark').removeClass('in')
    $('.pop-win-text').removeClass('in');
    $('.loading-tip').hide();
}


//
function  groupAdd(gid){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=dbgoods&gr_id="+gid;
    return;
}

//
function  another(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao";
    return;
}

//
function  getPrize(gid){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&op=getPrize&gr_id="+gid;
    return;
}
function goHome(){
    location.href = "<?php echo BASE_SITE_URL;?>/wap_shop/index.php";
    return;
}
</script>
</body>
</html>

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
<div class="header">
    <div class="arrow" onclick="location.href='index.php?act=duobao'"></div>
    <div class="tt"><?php echo (intval($output['group']['vr_class_id'])==5?'五':'一');?>元抢购区</div>
    <div class="go-home" onclick="location.href = 'index.php?act=duobao'"></div>
</div>

<!-- banner -->
<div id="banner">
    <div class="hd">
        <ul></ul>
    </div>
    <div class="bd">
        <ul>
        <?php if (!empty($output['group']['goods_images_url'])) {?>
            <?php foreach($output['group']['goods_images_url'] as $ival){?>
            <li><a href=""><img src="<?php echo $ival;?>" alt=""></a></li>
			<?php }?>
        <?php }else {?>
            <li></li>
        <?php }?>
        </ul>
    </div>
</div>
<!-- 产品 -->
<div class="product">
    <div class="pt-tt"><?php echo ($output['group']['groupbuy_name'].'（'.$output['group']['goods_name'].'）');?></div>
    <ul class="pt-mark">
    <?php if (!empty($output['goods']['goods_jingle'])) {
        $jingle = explode('/', $output['goods']['goods_jingle']);
        foreach ($jingle as $val) {
            if ($val != "") {?>
                <li><?php echo ($val);?></li>
            <?php }?>
        <?php }?>
    <?php }?>
    </ul>
</div>
<div class="setbacks">
    <div class="tt"><?php echo ($output['group']['remark']);?></div>
    <!--正在开奖倒计时-->
	<?php if(intval($output['group']['state']) == 32 && intval($output['ge_time']) > 0){?>
    <div class="end-time">
        <span>揭晓倒计时</span>
        <span class="hour">00</span>:
        <span class="min">00</span>:
        <span class="second">00</span>
    </div>
	<?php }?>

    <div class="speed">
        <div class="line" style="width: <?php echo (intval($output['group']['buy_quantity'])*100/intval($output['group']['total_quantity']));?>%;"></div>
    </div>
    <div class="setbacks-tx">
        <div class="lt">总需 <span><?php echo $output['group']['total_quantity'];?></span> 人次</div>
        <div class="rt">剩余<span><?php echo (intval($output['group']['total_quantity'])-intval($output['group']['buy_quantity']));?></span></div>
    </div>
</div>
<!-- 购买提示 -->
<div class="about-product">
	<?php if(intval($output['if_this'])==0){?>
    <p>已经参与<?php echo $output['group']['buy_quantity'];?>人次，尚需<?php echo (intval($output['group']['total_quantity'])-intval($output['group']['buy_quantity']));?>人次购买</p>
	<p>您心爱的商品在等您，购买的次数越多中奖几率就越大呦！</p>
	<?php }else{?>
    <div class="you-jion">
         恭喜您已成功参与了<i><?php echo $output['g_count'];?></i>次购买!<br />
		<?php if(intval($output['g_count'])){?>
		<span class="read" onclick="popWin()"> 查看号码  </span>
		<?php }?>
    </div>
	<p>购买的次数越多就越高几率得到心爱的商品呦！</p>
	<?php }?>
</div>

<!-- 往期揭晓 -->
<div class="product-more">
    <div class="tt">往期揭晓</div>
    <a class="more" onclick="wqjx();">更多 <i></i></a>
</div>
<!-- banner -->
<div id="product-tab">
    <ul class="tab-tt">
        <li>图文介绍</li>
        <li>商品详情</li>
        <li>参与者记录</li>
    </ul>
    <div class="tab-bd">
        <div class="article bd-ceil">
            <div class="des">
                <?php echo ($output['goods']['goods_body']);?><br />
        </div>
           </div>
        <div class="shopping bd-ceil"><?php echo ($output['group']['groupbuy_intro']?$output['group']['groupbuy_intro']:'暂无本期抢购介绍！');?></div>
        <div class="specifications">
            <div class="spe-inner bd-ceil">
                <div class="line"></div><!-- 分割线 -->

                <!-- 数据组一 -->
				<?php if(!empty($output['grp_a_inf'])){?>
				<?php foreach($output['grp_a_inf'] as $gk=>$gv){?>
                <div class="time-line"><?php echo ($gv['code']);?></div>
				<?php foreach($gv['a_oinf'] as $gk2=>$gv2){?>
                <div class="column">
                    <div class="user-head"><img src="<?php echo ($gv2['miUrl']?$gv2['miUrl']:SHOP_TEMPLATES_URL.'/duobao/user-header.jpg');?>" alt=""></div>
                    <div class="user-right">
                        <div class="user-name">
                            <span><?php echo ($gv2['buyer_name']);?></span>
                            <em class="ip"><?php echo ($gv2['ipqian'].$gv2['iphou']);?></em>

                        </div>
                        <div class="user-jion">
                            参与了<span>1</span>次<?php echo date('Y-m-d H:i:s',$gv2['add_time']);?>
                        </div>
                    </div>
                </div>
				<?php }?>
				<?php }}?>
            </div>
        </div>
    </div>
</div>

<!-- 确认抢购 -->
<div class="buying-mark" onclick="hideShopping()"></div>
<div class="buying-information">
    <div class="buying-hd">
        <img src="<?php echo ($output['group']['goods_imageurl']);?>" alt="">
    </div>
    <div class="buying-content">
	<form method="post" action="<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao" id="buyCountForm">
	<input name="act" value="duobao" type="hidden" />
	<input name="op" value="setOrder" type="hidden" />
	<input name="gr_id" value="<?php echo ($output['group']['groupbuy_id']);?>" type="hidden" />
	<input name="buyCount" value="" type="hidden" />
        <div class="bying-inner-pd">
            <div class="row">总需：<span><?php echo $output['group']['total_quantity'];?>人次</span></div>
            <div class="row">剩余：<span><?php echo (intval($output['group']['total_quantity'])-intval($output['group']['buy_quantity']));?>人次</span></div>
            <div class="purchase">
                <span class="people">购买人次</span>
                <div class="count-inner">
                    <span class="btn1"></span>
                    <input class="text" type="text" id="buy_count" value="1" size="80" name="txtPostalCode" onKeypress="if (event.keyCode < 45 || event.keyCode > 57) event.returnValue = false;" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" maxlength="6" oninput="maxNumber()" />
                    <span class="btn2"></span>
                </div>
            </div>
            <div class="buy-tips">购买人次越多，中奖概率更大</div>
        </div>
        <div class="choose-numb">
            <input class="btns on" type="button" onclick="buy1()" value="1">
            <input class="btns" type="button" onclick="buy5()" value="5">
            <input class="btns" type="button" onclick="buy10()" value="10">
        </div>
        <div class="methood-btns">
            <div class="numb">
                <i></i><span><?php echo $output['mem_points'];?></span>
            </div>
            <input type="button" class="btn2" id="pull_buy" value="提 交" />
        </div>
    </div>
	</form>
</div>

<?php if(intval($output['status']) == 20){ ?>
<input class="bt-btn" value="立即抢购" type="button" onclick="showShopping()">
<?php }else{ ?>
<style>
body{padding-bottom: 0px;}
</style>
<?php } ?>

<!--弹窗-->
<div class="pop-mark"></div>
<div class="pop-win-text">
    <div class="close-btn" onclick="closePop()"></div>
    <div class="tt"><?php echo ($output['group']['groupbuy_name'].'（'.$output['group']['goods_name'].'）');?></div>
    <div class="numb">
        <div class="numb-tt">以下是<?php echo ($_SESSION['member_name']);?>的参与号码</div>
        <div class="numb-me">
			<?php if(!empty($output['code_str'])){?>
			<?php foreach($output['code_str'] as $cval){?>
            <span><?php echo ($cval);?></span>
			<?php }}?>

        </div>
    </div>
</div>
<!--购买提示-->
<div class="loading-tip">
    <img src="<?php echo SHOP_TEMPLATES_URL?>/duobao/loading2.gif">
    <div class="tips-text" style="color:#fff;">数据加载中,请稍后</div>
</div>

<script type="text/javascript">
var intDiff = parseInt(<?php echo ($output['ge_time']); ?>) || 0;
$(function(){
    if(intDiff>0){
        $('.bt-btn').hide();
    }
    //banner
    TouchSlide({
        slideCell:"#banner",
        titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
        mainCell:".bd ul",
        effect:"left",
        autoPlay:true,//自动播放
        autoPage:true, //自动分页
        switchLoad:"_src" //切换加载，真实图片路径为"_src"
    });

	//提交购买
    var fla = true;
	$('#pull_buy').click(function(){
		var curr_points = "<?php echo ($output['member_points']);?>";
		var curr_buy = $('#buy_count').val();
		var gxc = parseInt("<?php echo ($output['gxc']);?>");
        if (curr_buy=="") {
            alert("购买份数不能为空");
            return;
        }
        if(parseInt(curr_buy) > gxc){
			alert("超出剩余量"+gxc+"份");
			return;
		}
		$('input[name="buyCount"]').val(curr_buy);
		//var 防止用户重复提交
		if(fla){
            $('#buyCountForm').submit();
		}
    });

    $(window).scroll(function(){
        var _tabTop=$('#product-tab').offset().top-$(window).scrollTop();
        if(_tabTop<0){
            if($('.tab-tt').hasClass('fixed')) return;
            $('.tab-tt').addClass('fixed');
        }else if(_tabTop>10){
            $('.tab-tt').removeClass('fixed');
        }
    });
    $('.tab-tt li').eq(0).addClass('on');
    $('.tab-bd .bd-ceil').hide();
    $('.tab-bd .bd-ceil').eq(0).show();
    $('.tab-tt li').click(function(){
       var _index=$(this).index();
        $(this).addClass('on').siblings().removeClass('on');
        $('.tab-bd .bd-ceil').hide();
        $('.tab-bd .bd-ceil').eq(_index).show();
    });

    /*选择切换*/
    $('.choose-numb .btns').click(function(){
        $('.choose-numb .btns').removeClass('on');
        $(this).addClass('on');
    });

    /*购买次数增减小*/
    $('.buying-information .count-inner .btn1').click(function(){
        countObj('subtract');
    });
    $('.buying-information .count-inner .btn2').click(function(){
        countObj('add');
    });

    //加减商品赋值
    var goods_number=<?php echo (intval($output['group']['total_quantity'])-intval($output['group']['buy_quantity']));?>;
    function countObj(argsMtd){
        var input_text=$('.buying-information .count-inner .text');
        var this_val=input_text.val();
        if(argsMtd=='add'){
            this_val=parseInt(this_val)+1;
            if(this_val > goods_number){
                alert("购买数不能超过剩余库存"+goods_number+"份");
                this_val=goods_number;
            }
        }else if(argsMtd=='subtract'){
            if(this_val<=1){
                this_val=1;
                return;
            }
            this_val=parseInt(this_val)-1;
        }
        input_text.val(this_val);
    }

    window.setInterval(function(){
        var day=0,
            hour=0,
            minute=0,
            second=0;//时间默认值
        if(intDiff > 0){
            day = Math.floor(intDiff / (60 * 60 * 24));
            hour = Math.floor(intDiff / (60 * 60)) - (day * 24);
            minute = Math.floor(intDiff / 60) - (day * 24 * 60) - (hour * 60);
            second = Math.floor(intDiff) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
        }
        if (minute <= 9) minute = '0' + minute;
        if (second <= 9) second = '0' + second;
        $('.hour').text(hour);
        $('.min').text(minute);
        $('.second').text(second);
        intDiff--;
    }, 1000);

});
function showShopping(){
    $('.buying-mark,.buying-information').fadeIn();
}
function hideShopping(){
    $('.buying-mark,.buying-information').fadeOut();
}


function maxNumber(){
    var input_text=$('.buying-information .count-inner .text');
    var this_val=input_text.val();
    var goods_number=<?php echo (intval($output['group']['total_quantity'])-intval($output['group']['buy_quantity']));?>;
    if(this_val > goods_number){
        alert("购买数不能超过剩余库存"+goods_number+"份");
        this_val=goods_number;
    }
    input_text.val(this_val);
}

function popWin(){

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
function  wqjx(){
	location.href="<?php echo BASE_SITE_URL;?>/wap_shop/index.php?act=duobao&op=viewOldJxRec&goods_id=<?php echo ($output['group']['goods_id']);?>&gr_id=<?php echo $output['group']['groupbuy_id'];?>";
	return;
}

//
function  buy1(){
	$('#buy_count').val('1');
	//
	return;
}
function  buy10(){
	$('#buy_count').val('10');
	//
	return;
}
function  buy5(){
	$('#buy_count').val('5');
	//
	return;
}
</script>
</body>
</html>

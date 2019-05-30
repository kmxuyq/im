<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" >
<meta http-equiv="Cache-Control" content="no-siteapp">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no" >
<meta name="format-detection" content="email=no" >
<meta name="author" content="evua" >
<title>GF婕珞芙</title>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/wx/main.css" rel="stylesheet" type="text/css">
<style type="text/css">
.main, .Time, .musicBtn{ display:none;}
.videoBox{ opacity:0;}
</style>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?671f47e9362e65c0aacfae8bc4fbd771";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</head>
<?php $imgpath=SHOP_TEMPLATES_URL.'/images/weixin/img/';?>
<body>
<section class="clearfix LoadPage" id="LoadPage">
	<div class="box">0%</div>
</section>

<div class="videoBox" id="videoBox">	
    <video id="video" x-webkit-airplay="true" webkit-playsinline="true" preload="auto" poster="<?php echo $imgpath?>video0_bg.jpg" src="<?php echo $imgpath?>cheweixiang-luckydraw-video-8.mp4"></video>
</div>

<div class="music" id="music">
    <span class="musicBtn" id="musicBtn"></span>
    <audio id="audio1" src="<?php echo $imgpath?>gf1.mp3" preload="auto" loop >Audio player</audio>
    <audio id="audio2" src="<?php echo $imgpath?>gf2.mp3" preload="auto" >Audio player</audio>    
</div>

<!--imgPackage-->
<div style="display: none;" id="imgPackage">
    <img data-src="<?php echo $imgpath?>load_1.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>news_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>news_2.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>news_3.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>year_btn_1.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year_btn_2.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year_btn_3.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year_btn_4.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year0.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year1.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year2.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year3.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year4.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year5.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year6.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year7.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year8.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year9.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year10.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>year11.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>year12.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>arr_1.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>arr_2.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>arr_3.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>arr_4.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>arr_5.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m1_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m1_2.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m1_title.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m2_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m2_2.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m2_title.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m3_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m3_2.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m3_title.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m4_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m4_2.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m4_title.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m6_1.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m6_2.png" class="preImg" />
    <img data-src="<?php echo $imgpath?>m6_3.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>m6_4.jpg" class="preImg" />
    <img data-src="<?php echo $imgpath?>share.png" class="preImg" />
</div>
<!--imgPackage end-->

<div class="news clearfix" id="news"></div>
<section class="Time clearfix" id="Time"></section>
<section class="main main2 clearfix" id="main2"></section>
<section class="main main3 clearfix" id="main3"></section>
<section class="main main4 clearfix" id="main4"></section>
<section class="main main5 clearfix" id="main5"></section>
<section class="main main6 clearfix" id="main6"></section>
<div class="share clearfix" id="share"></div>

<script id="newsHtml" type="text/html">
	<div class="wrap">
    	<div class="btn"><img src="<?php echo $imgpath?>news_1.jpg" /></div>
        <div><img src="<?php echo $imgpath?>news_2.jpg" /></div>
        <div class="btn"><img src="<?php echo $imgpath?>news_3.jpg" /></div>
    </div>
</script>
<script id="TimeHtml" type="text/html">
	<div class="wraper">
    	<div class="bg"><img src="<?php echo $imgpath?>year10.jpg" /></div>
        <div class="timeBox" id="timeBox">
        	<div class="box">
                <div data-index="1" class="items item1"></div>
                <div data-index="8" class="items item2"></div>
                <div data-index="4" class="items item3"></div>
                <div data-index="2" class="items item4"></div>
            </div>
        </div>
        <aside class="timeFrame timeFrame_1">
       		<div class="wrap">
                <time datetime="2016">2016</time>
                <i></i>
            </div>
       </aside>
       <aside class="timeFrame timeFrame_2">
       		<div class="wrap">
                <time datetime="1927">1927</time>
                <i></i>
            </div>
       </aside>
       <aside class="timeFrame timeFrame_3">
       		<div class="wrap">
                <time datetime="1878">1878</time>
                <i></i>
            </div>
       </aside>
       <aside class="timeFrame timeFrame_4">
       		<div class="wrap">
                <time datetime="1826">1826</time>
                <i></i>
            </div>
       </aside>
       <aside class="thruBtn" id="thruBtn">
            <div class="box">
                <div class="items item1"></div>
                <div class="items item2"></div>
                <div class="items item3"></div>
            </div>
            <div class="text"></div>
       </aside>
    </div>
</script>

<script id="main2Html" type="text/html">
	<div class="wraper">
        <div class="mArr">
            <p class="arr1"></p>
            <p class="arr2"></p>  
            <figure><img src="<?php echo $imgpath?>arr_2.png" /></figure>
        </div>
        <div class="box1"><img src="<?php echo $imgpath?>m1_1.jpg" /></div>      
        <div class="box2"><img src="<?php echo $imgpath?>m1_2.jpg" /></div>    
        <div class="title"><img src="<?php echo $imgpath?>m1_title.png" /></div>    
    </div>
</script>

<script id="main3Html" type="text/html">
	<div class="wraper">
		<div class="mArr">
			<p class="arr1"></p>
			<p class="arr2"></p>  
			<figure><img src="<?php echo $imgpath?>arr_3.png" /></figure>
		</div>
		<div class="box1"><img src="<?php echo $imgpath?>m2_1.jpg" /></div>      
		<div class="box2"><img src="<?php echo $imgpath?>m2_2.jpg" /></div>
		<div class="title"><img src="<?php echo $imgpath?>m2_title.png" /></div>  
	</div>
</script>

<script id="main4Html" type="text/html">
	<div class="wraper">
		<div class="mArr">
			<p class="arr1"></p>
			<p class="arr2"></p>  
			<figure><img src="<?php echo $imgpath?>arr_4.png" /></figure>
		</div>
		<div class="box1"><img src="<?php echo $imgpath?>m3_1.jpg" /></div>      
		<div class="box2"><img src="<?php echo $imgpath?>m3_2.jpg" /></div>
		<div class="title"><img src="<?php echo $imgpath?>m3_title.png" /></div>  
	</div>
</script>

<script id="main5Html" type="text/html">
	<div class="wraper">
		<div class="mArr">
			<p class="arr1"></p>
			<p class="arr2"></p>  
			<figure><img src="<?php echo $imgpath?>arr_5.png" /></figure>
		</div>
		<div class="box1"><img src="<?php echo $imgpath?>m4_1.jpg" /></div>      
		<div class="box2"><img src="<?php echo $imgpath?>m4_2.jpg" /></div>
		<div class="title"><img src="<?php echo $imgpath?>m4_title.png" /></div>  
	</div>
</script>

<script id="main6Html" type="text/html">
	<div class="wraper">
    	<a href="http://mp.weixin.qq.com/s?__biz=MzIxNzA1NDcxNA==&mid=701889549&idx=1&sn=708f73b96923fe160bfc43b8578cdc38#rd" class="link" title="深度寻迷"></a>
        <img src="<?php echo $imgpath?>m6_3.jpg" style="margin-top:5px;" />
    </div>
</script>

<script id="shareHtml" type="text/html">
	<figure><img src="<?php echo $imgpath?>share.png" /></figure>
</script>

<script src="<?php echo SHOP_TEMPLATES_URL?>/js/wx/zepto.min.oncemin.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL?>/js/wx/gf.js"></script>
<script src="<?php echo SHOP_TEMPLATES_URL?>/js/wx/app.js"></script>
<script>
$(function(){
	Global.eventUtil.addHandler(window, "load", LoadPage.Init);
	});
</script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>

<script>
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $output["signPackage"]["appId"];?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $output["signPackage"]["timestamp"];?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $output["signPackage"]["nonceStr"];?>', // 必填，生成签名的随机串
    signature: '<?php echo $output["signPackage"]["signature"];?>',// 必填，签名，见附录1
    jsApiList: ["onMenuShareTimeline", "onMenuShareAppMessage"] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	});

var wxData = {
    nickname: decodeURIComponent(queryString.GetValue('nickname')),//获取用户昵称
	imgUrl: 'http://m.gellefreres.com/wap_shop/templates/default/images/weixin/gfloge.jpg', // 分享图标
	link: 'http://m.gellefreres.com/wap_shop/?act=weixin_blog&source=share',// 分享链接
	desc: '揭秘：唐嫣真爱居然是Ta？神秘GF被曝光 。穿越1826一起#GF唐嫣谜样揭秘#',// 分享描述
	title: "唐嫣真爱居然是Ta？神秘GF被曝光",// 分享标题
	share:function(){
		//获取“分享到朋友圈”按钮点击状态及自定义分享内容接口
		wx.onMenuShareTimeline({
			title: wxData.nickname + wxData.desc,
			link: wxData.link,
			imgUrl: wxData.imgUrl,
			success: function() {
				 //用户确认分享后执行的回调函数//wxData.callback();
				},
			cancel: function() {
				//用户取消分享后执行的回调函数
				}
			});		
		//获取“分享给朋友”按钮点击状态及自定义分享内容接口
		wx.onMenuShareAppMessage({
			title: wxData.title,
			desc: wxData.nickname + wxData.desc,
			link: wxData.link,
			imgUrl: wxData.imgUrl,
			type: '', // 分享类型,music、video或link，不填默认为link
			dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
			success: function() {
				//用户确认分享后执行的回调函数//wxData.callback();
				},
			cancel: function() {
				//用户取消分享后执行的回调函数
				}
			});
		},
	callback:function(){	    
		}
	};//wxData{} end

wx.ready(function(){
    wxData.share();
	});
wx.error(function(res) {
	});
</script>
</body>
</html>

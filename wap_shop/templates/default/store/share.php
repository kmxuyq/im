<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title><?php echo $output['store']['name'];?></title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="<?php echo $output['store']['keywords'];?>"/>
	<meta name="Desccredit" content="<?php echo $output['store']['desccredit'];?>"/>
    <meta name="author" content="怡美集团"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/reset.css">
    <!--<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.less">-->
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/TouchSlide.1.1.js"></script>
	<script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/flexible.js"></script>
    <script type="text/javascript" src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/iscroll5.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
</head>
<body style="background: #f2f2f2; overflow-x:hidden">
<div id="MyScroller">
    <div class="warpper">
        <div id="PullDown" class="scroller-pullDown" style="display: none;">
            <span id="pullDown-msg" class="pull-down-msg">已经到顶了</span>
        </div>
    <!-- top -->
    <div class="header_top" style=" width: 100%; z-index: 999;">
	<form action="index.php" method="GET" id="search_form">
		<input name="act" value="show_store" type="hidden" />
		<input name="op" value="index" type="hidden"/>
		<input name="store_id" value="<?php echo $output['store']['store_id'];?>" type="hidden"/>
       <div class="top_sreach noLeft " style="width: 90%">
          	<span class="index_title fl" onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $output['store']['store_id'];?>'" ><?php echo $output['city'];?><i class="arrow_down"></i></span>
		   <div class="ipt_wrap">
			   <span class="icon" onclick="submit_from();"></span>
			   <input name="keywords" type="text" placeholder="搜索你想了解的关键词" />
			   <input name="category" type="hidden" value="" />
			   <input name="p" type="hidden" value="" />
		   </div>
       </div>
	</form>
        <div class="rt_icon"></div>
    </div>
    <!--banner-->
	<?php if(!empty($output['banner'])){ ?>
		<div class="banner bn1">
			<ul>
			<?php foreach($output['banner'] as $v){ ?>
				<li><img src="<?php echo $v['img'];?>" alt="" onclick="location.href = '<?php echo $v['url'];?>'"></li>
			<?php } ?>
			</ul>
		</div>
	<?php } ?>
    <ul class="hot_column">
        <li class="">

            <a href="index.php?act=station&op=ticket_search&store_id=<?php echo $output['store']['store_id'];?>">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_01.jpg" alt="" class="icon">
                <span>车票</span>
            </a>
        </li>
		<li>
			<a href="index.php?act=search&op=share_product_list&cate_id=1126&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_02.jpg" alt="" class="icon">
				<span>包车</span>
			</a>
		</li>
		<li>
			<a href="index.php?act=search&op=share_product_list&cate_id=1130&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/index_last_tab.png" alt="" class="icon" style="width: 44px" >
				<span>线路</span>
			</a>
		</li>
        <li>
            <a href="index.php?act=search&op=share_product_list&cate_id=1127&store_id=<?php echo $output['store']['store_id'];?>">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_03.jpg" alt="" class="icon">
                <span>门票</span>
            </a>
        </li>
		<li>
			<a href="index.php?act=search&op=share_product_list&cate_id=1128&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_04.jpg" alt="" class="icon">
				<span>酒店</span>
			</a>
		</li>
    </ul>
<?php if(!empty($output['special_info'])): ?>
	<!--  -->
	<div id="customBlock">
		<script>
			$.get(
				"index.php?act=mb_special&special_id=<?php echo $output['special_info']['special_id']; ?>&store_id=<?php echo $_SESSION['route_store_id']; ?>&type=iframe",function(data){
				$('#customBlock').html(data)
			})
		</script>
      <iframe src="index.php?act=mb_special&special_id=<?php echo $output['special_info']['special_id']; ?>&store_id=<?php echo $_SESSION['route_store_id']; ?>&type=iframe" id="myiframe" scrolling="no" frameborder="0" style="width:100%;"></iframe>
	</div>
<script>
jQuery(function($){
   $('#myiframe').load(function(){
      $(this).height($(this).contents().find('body').height())
   })
})
</script>
<?php endif;?>
	<!-- VCR --->
	<div class="index_vcr">
		<div class="index_theme_title" onclick="location.href='index.php?act=videos&op=index'"><span class="textArrow">精彩VR</span></div>
		<div class="vcr_wrap">
			<?php foreach($output['recommoned_videos'] as $k=>$v){ ?>
				<div class="item" onclick="ajax_setInc_videos_click('<?php echo $v['videos_id'];?>')">
					<a href="<?php echo $v['videos_url'] ?>"><img src="..<?php echo DS.$v['thumb'];?>" alt="<?php echo $v['title'];?>"> </a>
					<p class="tt"><?php echo $v['title'];?></p>
					<p class="text">播放:<?php echo $v['click_num'];?></p>
				</div>
			<?php } ?>
		</div>
	</div>
    <!--选项卡切换-->
    <div class="index_tab_wrap" style="display:none">
        <ul class="tab_tt">
		<?php foreach($output['category'] as $k=>$v) { ?>
            <li  class="<?php if(intval($v['stc_id']) == intval($_GET['p'])){ echo 'on';}?>" onclick="refreshIndex('<?php echo $v['stc_id'];?>','<?php echo $v['stc_id'];?>');"><em class="icon<?php echo $k+1;?>"></em><span><?php echo $v['stc_name'];?></span></li>
		<?php } ?>
        </ul>
		<?php foreach($output['category'] as $k=>$v){ ?>
			<?php if(!empty($v['child'])){?>
				<div class="tab_body">
					<ul>
					<li class="cell"><a onclick="refreshIndex('0','0');" class="<?php if(empty($_GET['category'])){ echo 'on';}?>">全部</a></li>
					<?php foreach($v['child'] as $kk=>$vv){ ?>
						<li class="cell"><a onclick="refreshIndex('<?php echo $vv['stc_id'];?>','<?php echo $v['stc_id'];?>');" class="<?php if($vv['stc_id'] == $_GET['category']){ echo 'on';}?>"><?php echo $vv['stc_name'];?></a></li>
					<?php } ?>
					</ul>
					<div class="show_more"><em class="more"></em></div>
				</div>
			<?php } ?>
		<?php }?>
    </div>
		<!-- 动态拉取商品列表-->
		<div id="main_body">
			<?php require_once BASE_PATH . DS . "/templates/default/store/push_goods_list.php"?>
		</div>

	<?php if(!empty($output['adv'])){ ?>
		<div class="banner bn2">
			<ul>
			<?php foreach($output['adv'] as $v){ ?>
				<li><img src="<?php echo $v['img'];?>" alt="" onclick="location.href = '<?php echo $v['url'];?>'"></li>
			<?php } ?>
			</ul>
		</div>
	<?php } ?>
	<div id="PullUp" class="scroller-pullUp" style="display: none;">
		<span id="pullUp-msg" class="pull-up-msg">数据加载</span>
	</div>
</div>
<!---->
<div class="star_encode">
	<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/yimayCode.jpg" alt="">
	<div>微信扫一扫关注</div>
</div>

	<!--加载公共导航 -->
<?php require_once(BASE_TPL_PATH."/layout/nav.php"); ?>


<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<?php
	function get_access_token(){
		$token = rkcache('access_token');
		if(!empty($token)){
			$token = unserialize($token);
		} else{
			$token = array();
		}
		if(empty($token) || time()-$token['create_time'] > 7200){
			$uri = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $_SESSION["weixin_appid"] . '&secret=' . $_SESSION["weixin_appsecret"];
			$token = curl($uri);
			$token = json_decode($token,true);
			if(!isset($token['access_token'])||empty($token['access_token'])){
				return '';
			}
			$token['create_time']=time();
			wkcache('access_token', serialize($token));
		}
		return $token['access_token'];

	}
	function get_jsapi_ticket() {
		$tickets = rkcache('jsapi_ticket');
		if ($tickets) {
			$tickets = unserialize($tickets);
		}
		if (empty($tickets) || time() - $tickets['create_time'] >= 7200) {
			$access_token = get_access_token();
			if ('' == $access_token) {
				return '';
			}
			$uri     = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $access_token . '&type=jsapi';
			$tickets = curl($uri);
			$tickets = json_decode($tickets, true);
			if (empty($tickets['ticket'])) {
				return '';
			}
			$tickets['create_time'] = time();
			wkcache('jsapi_ticket', serialize($tickets));
		}
		return $tickets['ticket'];
	}
	$jsapi_ticket = get_jsapi_ticket();
	$timestamp = time();
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	$str   = '';
	for ($i = 0; $i < 16; $i++) {
		$str .= $chars{mt_rand(0, strlen($chars) - 1)};
	}
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') !== false ? 'https://' : 'http://';
	$host     = $protocol . $_SERVER['HTTP_HOST'];
	$url      = $host . $_SERVER['REQUEST_URI'];
	$string1   = "jsapi_ticket={$jsapi_ticket}&noncestr={$str}&timestamp={$timestamp}&url={$url}";
	$signature = sha1($string1);
	?>

<script>
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $_SESSION["weixin_appid"]; ?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $timestamp; ?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $str; ?>', // 必填，生成签名的随机串
    signature: '<?php echo $signature; ?>',// 必填，签名，见附录1
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});
var sharedata = {
   title:'<?php echo $output['store']['name'];?>小店',
   desc:'<?php echo $output['store']['name'];?>小店',
   link:'http://wx.yimayholiday.com/wap_shop/index.php?act=show_store&op=share&store_id=<?php echo $output['store']['store_id'];?>&share_uid=<?php echo $_SESSION['member_id'];?>&store_member_info=<?php echo $output['store']['store_id'];?>',
   imgUrl:'<?php echo $output['banner'][0]['img'];?>',
   success:function(){
      $.post('http://wx.yimayholiday.com/wap_shop/index.php?act=show_store&op=share&store_id=<?php echo $output['store']['store_id'];?>&share_uid=<?php echo $_SESSION['member_id'];?>',{},function(data){
         //do something
      }, 'json');
   },
   cancel: function(){
      console.log('user cancel share');
   }
};
wx.ready(function () {
   wx.onMenuShareAppMessage(sharedata);
   wx.onMenuShareTimeline(sharedata);
});
</script>

<script>
    var myScroll;
    var scrollTs=0;
var store_id = '<?php echo $output['store']['store_id'];?>';
function addGoodsLike(goodsid){
	if(goodsid){
		$.get('index.php',{'id':goodsid,'store_id':store_id,'act':'show_store','op':'add_goods_like'},function(state){
			if(state === '1'){
				var num = parseInt($('.goods_like_'+goodsid).text())+1;
				$('.goods_like_'+goodsid).text(num);
			}
		})
	}
}
function refreshIndex(id,p_id){
	if(id){
		$('input[name="category"]').val(id);
		$('input[name="p"]').val(p_id);
		submit_from();
	}
}

function submit_from(){
	$('#search_form').submit();
}

//起始方法
$(function(){

    banner('.bn1');
    banner('.bn2');

	scrollPullDown();

	$.ajax();
	$('#customBlock')

 	var tabIndex=$('.index_tab_wrap .tab_tt li.on').index() || 0;
     TabFn.init({
        tt:'.tab_tt li',
        tb:'.tab_body',
        _index:tabIndex,

    });

	var tabLi=$('.index_tab_wrap .tab_body').eq(tabIndex).find('li');
	for(var i=0;i<tabLi.length;i++){
		if(tabLi.eq(i).find('a').hasClass('on') && i>=4 ){
			$('.index_tab_wrap .tab_body').eq(tabIndex).find('ul').css({
				height:'auto'
			})
		}
	}

    $('.tab_body .show_more').click(function(){
        var __prev=$(this).prev('ul');
        if(__prev.height()<=63){
            __prev.css({
                height:'auto'
            })
        }else{
            __prev.height(63);
        }
    });
	document.ontouchmove=function(){
		return false;
	};
	$('.header_top .rt_icon').click(function(){
		openPopWin('.star_encode')
	})
});

function scrollPullDown(){
    // 初始化body高度
    var bdH=document.body.style.height = Math.max(document.documentElement.clientHeight, window.innerHeight || 0) + 'px';

    var pullDown = document.querySelector("#PullDown"),
            pullUp = document.querySelector("#PullUp"),
            isPulled = false; // 拉动标记

    myScroll = new IScroll('#MyScroller', {
        probeType: 3,
        mouseWheel: true,
        scrollbars: true,
        preventDefault: false,
        fadeScrollbars: true
    });
    myScroll.scrollBy(0,scrollTs)
    myScroll.on('scroll', function() {
        var height = this.y,
                bottomHeight = this.maxScrollY - height;
        scrollTs=Math.round(height);


        // 控制下拉显示
        if (height >= 60) {
            pullDown.style.display = "block";
            return;
        }
        else if (height < 60 && height >= 0) {
            pullDown.style.display = "none";
            return;
        }

        // 控制上拉显示
        if (bottomHeight >= 60) {
            pullUp.style.display = "block";
            isPulled = true;
            return;
        }
        else if (bottomHeight < 60 && bottomHeight >= 0) {
            pullUp.style.display = "none";
            return;
        }
    });

    myScroll.on('scrollEnd', function() { // 滚动结束
        if (isPulled) { // 如果达到触发条件，则执行加载
            isPulled = false;
            addEl();
            myScroll.refresh();
        }
    });
}
function addEl(){
    //ajax 滚动加载首页内容
	var img_path = "<?php echo SHOP_TEMPLATES_URL;?>";
	var store_id = '<?php echo $output['store']['store_id'];?>';
	flag =true;
	if(flag){
		flag =false;
	$.get('index.php',{'act':'show_store','op':'ajax_load','store_id':<?php echo $output['store']['store_id'];?>},function(data){
		if(data != ''){
			$('#main_body').append(data);
				myScroll.refresh();
		}else{
			$('#pullUp-msg').empty();
			$('#pullUp-msg').html('没有更多数据了......');
		}
		flag = true;
	});
	}
}
//banner
function banner(str){
    var num=0;
    var maxLen=$(str+' ul li').length-1;
    if(maxLen <=1) return;
    setInterval(function(){
        $(str+' ul li').eq(num).fadeIn().siblings().fadeOut();
        num>maxLen?num=0:num++;
    },3000)
}
//视频播放数量增加
function ajax_setInc_videos_click(id){
	if(id=='') return false;
	$.get('index.php?act=videos&op=setInc_click_num',{id:id},function(){
	})
}
</script>
<style type="text/css">
#MyScroller {  position: relative;  width: 100%;  height: 100%;  }
#MyScroller  .warpper {  position: absolute;  width: 100%;  }
.scroller-pullDown,.scroller-pullUp {  width: 100%;  height: 30px;  padding: 10px 0;  text-align: center;  }
.dropdown-list {  padding: 0;  margin: 0;  }
.dropdown-list li {  width: 100%;  background: #ddd;  line-height: 45px;  text-align: center;  color: #FFF;  border-bottom: 1px solid #FFF;  }
</style>

</body>
</html>

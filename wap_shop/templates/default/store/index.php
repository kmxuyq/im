<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title><?php echo $output['store']['name'];?></title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="<?php echo $output['store']['keywords'];?>"/>
	<meta name="Desccredit" content="<?php echo $output['store']['desccredit'];?>"/>
    <meta name="author" content="怡美集团"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/reset.css">
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
		<input name="act" value="search" type="hidden" />
		<input name="op" value="product_list" type="hidden"/>
		<input name="store_id" value="<?php echo $output['store']['store_id'];?>" type="hidden"/>
       <div class="top_sreach noLeft " style="width: 90%">
          	<span class="index_title fl" onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $output['store']['store_id'];?>'" ><?php echo $output['city'];?><i class="arrow_down"></i></span>
		   <div class="ipt_wrap">
			   <span class="icon" onclick="submit_from();"></span>
			   <input name="keyword" type="text" placeholder="搜索你想了解的关键词" />
			   <input name="category" type="hidden" value="" />
			   <input name="p" type="hidden" value="" />
		   </div>
       </div>
	</form><?php if($output['store_wx_info']['qr']): ?>
        <div class="rt_icon"></div><?php endif; ?>
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
      <style type="text/css">
      .index_btn_list{
         display: table;
         width: 100%;
         margin-bottom: 1px;
      }
      .index_btn_list .index_btn_main{
         display: table-row;
         background: #fff;
      }
      .index_btn_list .index_btn_main li {
         display: table-cell;
         text-align: center;
      }
      .index_btn_list .index_btn_main img {
        max-width: 100%;
        display: block;
        margin: 10px auto 5px;
        color: #fff;
        background: transparent;
        max-height: 50px;
      }
      </style>
      <?php if(is_array($output['btn_list']) and !empty($output['btn_list'])):
         foreach($output['btn_list'] as $rows): ?>
         <div class="index_btn_list">
      <ul class="index_btn_main">
         <?php foreach($rows as $item): ?>
        <li class="">
            <a href="<?php echo $item['url'];?>">
                <img src="<?php echo UPLOAD_SITE_URL . $item['icon']; ?>" alt="<?php echo $item['name']; ?>" class="icon">
                <span><?php echo $item['name']; ?></span>
            </a>
        </li>
        <?php endforeach; ?>
     </ul>
  </div>
     <?php endforeach; else: ?>
     <div class="index_btn_list">
     <ul class="index_btn_main">
        <li class="">
            <a href="index.php?act=station&op=ticket_search&store_id=<?php echo $output['store']['store_id'];?>">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_01.jpg" alt="" class="icon">
                <span>车票</span>
            </a>
        </li>
		<li>
			<a href="index.php?act=search&op=product_list&cate_id=1126&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_02.jpg" alt="" class="icon">
				<span>包车</span>
			</a>
		</li>
		<li>
			<a href="index.php?act=search&op=product_list&cate_id=1130&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/index_last_tab.png" alt="" class="icon" >
				<span>线路</span>
			</a>
		</li>
        <li>
            <a href="index.php?act=search&op=product_list&cate_id=1127&store_id=<?php echo $output['store']['store_id'];?>">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_03.jpg" alt="" class="icon">
                <span>门票</span>
            </a>
        </li>
		<li>
			<a href="index.php?act=search&op=product_list&cate_id=1128&store_id=<?php echo $output['store']['store_id'];?>">
				<img src="<?php echo SHOP_TEMPLATES_URL;?>/style/images/product_04.jpg" alt="" class="icon">
				<span>酒店</span>
			</a>
		</li>
   </ul>
</div>
   <?php endif; ?>
<?php if(!empty($output['special_info'])): ?>
	<!--  -->
	<div id="customBlock">
  <script>
      $.get(
        "index.php?act=mb_special&special_id=<?php echo $output['special_info']['special_id']; ?>&store_id=<?php echo $_SESSION['route_store_id']; ?>&type=iframe",function(data){
        $('#customBlock').html(data)
      })
    </script>
	</div>
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
	<div id="PullUp" class="scroller-pullUp" style="display: none;margin-bottom:35px;">
		<span id="pullUp-msg" class="pull-up-msg">数据加载</span>
	</div>
</div>
<!---->
<?php if($output['store_wx_info']['qr']): ?>
<div class="star_encode">
	<img src="<?php echo UPLOAD_SITE_URL . $output['store_wx_info']['qr']; ?>" alt="">
	<div>微信扫一扫关注</div>
</div>
<?php endif;?>
	<!--加载公共导航 -->
<?php require_once(BASE_TPL_PATH."/layout/nav.php"); ?>
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
function refreshIndex(id,p_id){//选项卡切换
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
    loading();
});
    function loading(){
        var flag = false,load_height = 30;
        $(window).bind("scroll", function () {
            //$(window).height() 当前可视窗口的高度
            //$(document).height()  整个文档的高度
            if ($(document).scrollTop() + $(window).height() > $(document).height() - 10 && !flag) {
                addEl();
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
                }else{
                    $('#pullUp-msg').empty();
                    $("#PullUp").css("display","block");
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
.scroller-pullDown,.scroller-pullUp {  width: 100%;  height: 50px;
    margin-bottom:40px;  padding: 10px 0;  text-align: center;}
.dropdown-list {  padding: 0;  margin: 0;  }
.dropdown-list li {  width: 100%;  background: #ddd;  line-height: 45px;  text-align: center;  color: #FFF;  border-bottom: 1px solid #FFF;  }
</style>
<!-- 引入 -->
<?php include BASE_TPL_PATH.'/store_wx_share.php' ; ?>
</div>
</body>
</html>
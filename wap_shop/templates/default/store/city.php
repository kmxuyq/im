<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
    <title>城市</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="format-detection" content="telephone=no"/>

    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/reset.css">
    <!--<link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.less">-->
    <link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/style/css/main.css">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/style/js/TouchSlide.1.1.js"></script>
</head>
<body>
<div class="current_location">
    <div class="arrow" onclick="history.go(-1);"></div>
    <span><?php echo $output['this_city']['city'];?></span>
</div>
<div class="sreach_addres">
    <div class="sh_wrap">
	<form action="index.php" method="get" id="key_form" >
		<input name="act" value="show_store" type="hidden" />
		<input name="op" value="map" type="hidden" />
		<input name="store_id" value="<?php echo $_GET['store_id'];?>" type="hidden" />
        <input type="text" name="city_key" onchange="submit_form();" placeholder="请输入城市名称">
	</form>
    </div>
</div>
<script>
function submit_form(){
	var key = $('input[name="city_key"]').val();
	if(key.length > 0){
		$('#key_form').submit();
	}
}
</script>
<div class="city_grid">
    <ul>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=A'">A</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=B'">B</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=C'">C</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=D'">D</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=E'">E</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=F'">F</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=G'">G</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=H'">H</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=I'">I</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=J'">J</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=K'">K</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=L'">L</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=M'">M</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=N'">N</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=O'">O</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=P'">P</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=Q'">Q</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=R'">R</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=S'">S</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=T'">T</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=U'">U</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=V'">V</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=W'">W</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=X'">X</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=Y'">Y</li>
        <li onclick="location.href = 'index.php?act=show_store&op=map&store_id=<?php echo $_GET['store_id'];?>&city_pinyin=Z'">Z</li>
    </ul>
</div>

<div class="current_city">定位城市</div>
<ul class="city_list">
    <li onclick="location.href = 'index.php?act=show_store&op=set_map&store_id=<?php echo $_GET['store_id'];?>&id=<?php echo $output['this_city']['id'];?>'"><?php echo $output['this_city']['city'];?></li>
</ul>
<div class="current_city">最近访问城市</div>
<ul class="city_list">
	<?php foreach($output['history_city'] as $v){ ?>
	 <li onclick="location.href = 'index.php?act=show_store&op=set_map&store_id=<?php echo $_GET['store_id'];?>&id=<?php echo $v['city_id'];?>'"><?php echo $v['city_name'];?></li>
	<?php } ?>
</ul>
<div class="current_city">热门城市</div>
<ul class="city_list">
	<?php foreach($output['hot_city'] as $v){ ?>
	 <li onclick="location.href = 'index.php?act=show_store&op=set_map&store_id=<?php echo $_GET['store_id'];?>&id=<?php echo $v['city_id'];?>'"><?php echo $v['city_name'];?></li>
	<?php } ?>
</ul>
<?php foreach($output['city'] as $k=>$v){ ?>
	<div class="city_index">
		<?php echo $k;?>
	</div>
	<ul class="city_name">
		<?php foreach($v as $kk=>$vv){ ?>
		 <li onclick="location.href = 'index.php?act=show_store&op=set_map&store_id=<?php echo $_GET['store_id'];?>&id=<?php echo $vv['area_id'];?>'"><?php echo $vv['area_name'];?></li>
		<?php } ?>
	</ul>
<?php } ?>
</body>
</html>
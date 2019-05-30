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
    <span><?php echo $output['title'];?></span>
</div>
<div class="sreach_addres">
    <div class="sh_wrap">
        <input onchange="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&key='+$(this).val();" type="text" placeholder="请输入站点名称">
    </div>
</div>

<div class="city_grid">
    <ul>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=A'">A</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=B'">B</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=C'">C</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=D'">D</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=E'">E</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=F'">F</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=G'">G</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=H'">H</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=I'">I</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=J'">J</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=K'">K</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=L'">L</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=M'">M</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=N'">N</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=O'">O</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=P'">P</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=Q'">Q</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=R'">R</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=S'">S</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=T'">T</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=U'">U</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=V'">V</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=W'">W</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=X'">X</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=Y'">Y</li>
        <li onclick="location.href = 'index.php?act=station&op=station_location&type=<?php echo $output['type'];?>&pinyin=Z'">Z</li>
    </ul>
</div>

<div class="city_index">
   <?php echo $output['search_title'];?>
</div>
<ul class="city_name">
<?php if(!empty($output['data'])){ ?>
	<?php foreach($output['data'] as $v){ ?>
    <li onclick="location.href = 'index.php?act=station&op=set_station_location&type=<?php echo $output['type'];?>&address=<?php echo $v['address'];?>'" ><?php echo $v['address'];?></li>
	<?php } ?>
<?php }else{ ?>
没有找到相关数据！
<?php } ?>
</ul>
</body>
</html>








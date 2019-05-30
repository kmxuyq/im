<?php defined('InShopNC') or exit('Access Invalid!');?>
<meta charset="utf-8">
<meta name="description" content="怡美天香" />
<meta name="keywords" content="怡美天香" />   
<meta content="telephone=no" name="format-detection">
<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title><?php echo $lang['az_active_info']?></title>
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css" />
<link rel="stylesheet" href="<?php echo SHOP_TEMPLATES_URL;?>/css/wechat.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/az.css" />
<script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.9.1.min.js"></script>
</head>

<body class="bg_gray">
<header class="az-header az-header-positive az-header-background az-header-bb noProsition">
   <a href="index.php?act=member_order&state_type=<?php echo $output['order_info']['order_state'];?>">
    <i class="az-icon-return"></i>
   </a>
   <h1 class="az-color"><?php echo $lang['az_active_info']?></header>

<!--精彩活动详情-->
<div class="az_active_botton order_dels" style="padding-top: 47px;">
	<div class="title"><?php echo $output['goods']['goods_name']; ?></div>
	<?php if($output['active_state']['az_active_state'] =='0'){?>
	<div class="az_active_state"><?php echo '&#12288'.$lang['az_active_lang']; ?></div>
	<?php }elseif($output['active_state']['az_active_state'] =='1') {?>
	<div class="az_active_state"><?php echo '&#12288'.$lang['az_active_success']; ?></div>
	<?php }elseif ($output['active_state']['az_active_state'] =='2'){?>
	<div class="az_active_state"><?php echo '&#12288'.$lang['az_active_fail']; ?></div>
	<?php }?>
    <div class="time"><?php echo $lang['az_active_addtime_f'].'：'. date("Y-m-d H:i:s",$output['goods']['goods_addtime']); ?></div>
    </div>
    <div class="az_active_top"></div>
   <div class="az_active_botton_img">
    <div id="az-picScroll">
        <div class="hd">
            <ul></ul>
        </div>
                
        <div class="bd">
            <ul>
				<?php if (!empty($output['goods_image'])) {?>
					
	                <?php $imglist = $output['goods_image'];
						foreach($imglist as $key=>$img)
						{
							$img_one = explode(",",$img);
							
							$img_one_url_array = explode("'",$img_one[2]);
							
							$img_one_url = $img_one_url_array[1];
							//$img_one_url = str_replace("'","",$img_one_url);
							$imglist_new[] = $img_one_url;
							
						}
						
						for($i=0;$i<count($imglist_new);$i++)
						{
							?>
							 <li>
                    <a href="javascript:"><img src="<?php echo $imglist_new[$i];?>" class="az-img-block"></a>
                    <div class="az-loc-num qz-light"><?php echo ($i+1);?>/<?php echo (count($imglist_new)-1);?></div>
                    <div class="az-loc-title">
                        <div class="az-rt">
                            <i class="az-ico2 qz-ico-allowl"></i>
                            <i class="az-ico2 qz-ico-collection"></i>
                        </div>
						
                    </div>
                </li>
							<?php
						}
					?>
	             <?php }?>
               
            </ul>
        </div>
    </div>
    
    <script type="text/javascript">
        TouchSlide({ 
            slideCell:"#az-picScroll",
            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
            mainCell:".bd ul", 
            effect:"left", 
            autoPlay:true,//自动播放
            autoPage:true, //自动分页
            interTime:6000,
        });
    </script> 
 </div>
 <div class="az_active_top"></div>
 <div class="az_active_botton">
    <p><?php echo $output['goods']['goods_jingle'];?></p>
</div>

<div class="az_active_top">&nbsp;</div>
<?php if ($_SESSION['is_login']) {?>
<?php if($output['active_state']['az_active_state']!=''){?>
<a href="javascript:;" class="az_active_add" id="az_active_state"><?php echo $lang['az_active']?></a>
<?php }else{?>
<a href="<?php echo urlShopWap('active', 'index',array('type'=>'act_goods','wx'=>$_GET['wx'],'goods_id'=>$output['goods']['goods_id']));?>" class="az_active_add">我要参加</a>
<?php }?>
<?php }else {?>
<a href="<?php echo urlShopWap('login', 'index');?>" class="az_active_add"><?php echo $lang['az_active']?></a>
<?php }?>
</body>
<script>
$(function() {
	$("#az_active_state").click(function() {
		alert("<?php echo $lang['az_active_lang']?>");
	})
})
</script>

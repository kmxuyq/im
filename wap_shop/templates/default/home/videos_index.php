<!doctype html>
<html>
<head>
    <title>视频列表</title>
    <meta http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <meta http-equiv="Content-Language" content="UTF-8"/>
    <meta name="Keywords" content="--------template_kewords----------"/>
    <meta name="author" content="--------template_author----------"/>
    <meta name="Copyright" content="版权所有,违者必究"/>
    <meta name="format-detection" content="telephone=no"/>
    <link rel="stylesheet" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/reset.css">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/public.less">
    <link rel="stylesheet/less" type="text/css" href="<?php echo SHOP_TEMPLATES_URL;?>/css/gt_newpage.less">
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/flexible.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/less.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/jquery-1.11.3.min.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/main.js"></script>
    <script src="<?php echo SHOP_TEMPLATES_URL;?>/js/TouchSlide.1.1.js"></script>
</head>
<body class="gt_newpage_body">

<div class="train_position_top">
    <div class="arrow" onclick="history.back()"></div>
    <div class="text">VCR列表</div>
</div>
<div class="video_list">
    <?php  foreach($output['videos_list'] as $value){ ?>
        <div class="item">
            <a  href="<?php echo $value['url'];?>" onclick="ajax_setInc_videos_click('<?php echo $value['id'];?>')">
                <img src="<?php echo $value['img_url'];?>" alt="<?php  echo $value['name']?>">
            </a>
            <span class="tt"><?php  echo $value['name']?></span>
            <p>播放:<?php  echo $value['click_num']?></p>
        </div>
    <?php }?>
</div>
</body>
</html>
<script>
    function ajax_setInc_videos_click(id){
        if(id=='') return false;
            $.get('index.php?act=videos&op=setInc_click_num',{id:id},function(){
        })
    }
</script>







